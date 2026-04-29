<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface GeoGouvServiceInterface
{
    public function getCitiesByDepartment(string $deptCode): Collection;

    public function getCityByInseeCode(string $inseeCode): ?array;

    public function searchByPostalCode(string $postalCode): Collection;

    public function searchByName(string $name, ?string $deptCode = null): Collection;

    public function importDepartment(string $deptCode): int;
}
