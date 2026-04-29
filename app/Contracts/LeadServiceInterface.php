<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Lead;
use App\Models\Page;
use Illuminate\Http\Request;

interface LeadServiceInterface
{
    public function createFromRequest(Request $request, ?Page $page = null): Lead;

    public function getStatsByPeriod(string $period = '30d'): array;

    public function updateStatus(Lead $lead, string $status, string $notes = ''): Lead;
}
