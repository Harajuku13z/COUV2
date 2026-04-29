<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiGeneration;
use App\Models\ApiErrorLog;
use App\Models\Lead;
use App\Models\Page;
use App\Models\WeatherEvent;
use Illuminate\Support\Facades\DB;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Contracts\MetricsRepository;

class DashboardController extends Controller
{
    public function index(JobRepository $jobs, MetricsRepository $metrics)
    {
        $stats = [
            'pages' => [
                'total' => Page::query()->count(),
                'published' => Page::query()->where('status', 'published')->count(),
                'draft' => Page::query()->where('status', 'draft')->count(),
            ],
            'leads' => [
                'today' => Lead::query()->whereDate('created_at', today())->count(),
                'week' => Lead::query()->where('created_at', '>=', now()->subWeek())->count(),
                'month' => Lead::query()->where('created_at', '>=', now()->subMonth())->count(),
                'by_status' => Lead::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            ],
            'top_cities' => Lead::query()->selectRaw('city_label, count(*) as total')->whereNotNull('city_label')->groupBy('city_label')->orderByDesc('total')->limit(5)->get(),
            'top_services' => Lead::query()->selectRaw('service_requested, count(*) as total')->whereNotNull('service_requested')->groupBy('service_requested')->orderByDesc('total')->limit(5)->get(),
            'openai_cost_month' => (float) AiGeneration::query()->where('created_at', '>=', now()->startOfMonth())->sum('cost_usd'),
            'jobs' => [
                'pending' => method_exists($jobs, 'count') ? $jobs->count() : null,
                'failed' => method_exists($jobs, 'countRecentlyFailed') ? $jobs->countRecentlyFailed() : null,
                'throughput' => method_exists($metrics, 'jobsProcessedPerMinute') ? $metrics->jobsProcessedPerMinute() : [],
            ],
            'api_errors' => ApiErrorLog::query()->latest('occurred_at')->limit(10)->get(),
            'weather_events' => WeatherEvent::query()->where('created_at', '>=', now()->subDays(7))->latest()->limit(7)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
