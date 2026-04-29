<?php

declare(strict_types=1);

namespace App\Contracts;

interface SerpApiServiceInterface
{
    public function googleSearch(string $keyword, string $location): array;

    public function googleLocalPack(string $query, string $location): array;

    public function googleAutocomplete(string $query): array;

    public function getPeopleAlsoAsk(string $keyword, string $location): array;

    public function googleMaps(string $query, float $lat, float $lon): array;

    public function fullAnalysis(int $pageId, string $service, string $city, string $department): void;
}
