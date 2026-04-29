<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiGeneration;
use App\Models\ApiErrorLog;
use App\Models\Lead;
use App\Models\Page;
use App\Models\WeatherEvent;
use App\Jobs\GenerateSitemapJob;
use App\Jobs\RefreshWeatherDataJob;
use Illuminate\Http\RedirectResponse;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Contracts\MetricsRepository;

class DashboardController extends Controller
{
    public function index(JobRepository $jobs, MetricsRepository $metrics)
    {
        $leadCountsByDay = collect(range(6, 0))
            ->map(fn (int $offset) => now()->subDays($offset))
            ->mapWithKeys(fn ($date) => [$date->format('d/m') => Lead::query()->whereDate('created_at', $date)->count()]);

        $leadStatusCounts = Lead::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

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
            'weather_events' => WeatherEvent::query()->with('city')->where('created_at', '>=', now()->subDays(7))->latest()->limit(7)->get(),
            'charts' => [
                'leads_by_day' => [
                    'labels' => $leadCountsByDay->keys()->all(),
                    'data' => $leadCountsByDay->values()->all(),
                ],
                'lead_status' => [
                    'labels' => $leadStatusCounts->keys()->all(),
                    'data' => $leadStatusCounts->values()->all(),
                ],
            ],
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function regenerateSitemap(): RedirectResponse
    {
        GenerateSitemapJob::dispatch();

        return back()->with('status', 'Regeneration du sitemap lancee.');
    }

    public function refreshWeather(): RedirectResponse
    {
        RefreshWeatherDataJob::dispatch();

        return back()->with('status', 'Rafraichissement meteo lance.');
    }
}
