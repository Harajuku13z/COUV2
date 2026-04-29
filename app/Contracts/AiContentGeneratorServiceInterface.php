<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Company;
use App\Models\Page;

interface AiContentGeneratorServiceInterface
{
    public function generatePage(Page $page, Company $company): array;

    public function generateBlogPost(string $topic, string $service, Company $company): array;
}
