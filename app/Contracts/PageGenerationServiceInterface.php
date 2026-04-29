<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\City;
use App\Models\Page;
use App\Models\Service;

interface PageGenerationServiceInterface
{
    public function generatePageTypesForCity(City $city, Service $service): array;

    public function generateAllPagesForDepartment(string $deptCode): int;

    public function regeneratePage(Page $page): void;
}
