<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\City;

interface WeatherServiceInterface
{
    public function getCurrentWeather(float $lat, float $lon): array;

    public function getWeatherContext(City $city): string;

    public function refreshAllActiveCities(): int;

    public function getWeatherRisks(City $city): string;
}
