<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Company;
use App\Models\Page;

interface SeoServiceInterface
{
    public function generateSitemap(): void;

    public function generateRobotsTxt(): string;

    public function generateCanonicalUrl(Page $page): string;

    public function generateOpenGraphData(Page $page, Company $company): array;

    public function generateTwitterCardData(Page $page, Company $company): array;

    public function generateBreadcrumb(Page $page): array;
}
