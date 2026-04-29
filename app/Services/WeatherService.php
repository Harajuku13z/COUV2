<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\WeatherServiceInterface;
use App\Models\City;
use App\Models\WeatherEvent;
use App\Traits\TracksApiCost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService implements WeatherServiceInterface
{
    use TracksApiCost;

    public function getCurrentWeather(float $lat, float $lon): array
    {
        return Cache::remember(
            sprintf('weather:%s,%s', $lat, $lon),
            now()->addHours(6),
            function () use ($lat, $lon): array {
                $response = Http::baseUrl((string) config('services.openweather.base_url'))
                    ->timeout((int) config('services.openweather.timeout', 30))
                    ->get('/data/2.5/weather', [
                        'appid' => config('services.openweather.key'),
                        'units' => 'metric',
                        'lang' => 'fr',
                        'lat' => $lat,
                        'lon' => $lon,
                    ])
                    ->throw()
                    ->json();

                return [
                    'temp' => data_get($response, 'main.temp'),
                    'feels_like' => data_get($response, 'main.feels_like'),
                    'humidity' => data_get($response, 'main.humidity'),
                    'wind_speed' => data_get($response, 'wind.speed'),
                    'rain_1h' => data_get($response, 'rain.1h', 0),
                    'weather_id' => data_get($response, 'weather.0.id'),
                    'weather_main' => data_get($response, 'weather.0.main'),
                    'weather_description' => data_get($response, 'weather.0.description'),
                ];
            }
        );
    }

    public function getWeatherContext(City $city): string
    {
        $weather = $city->weather_data ?? [];
        $temp = (float) ($weather['temp'] ?? 0);
        $rain = (float) ($weather['rain_1h'] ?? 0);
        $wind = (float) ($weather['wind_speed'] ?? 0) * 3.6;

        return match (true) {
            $rain > 5 => 'Temps pluvieux prévu, pertinent pour évoquer la prévention et les infiltrations.',
            $wind > 35 => 'Vent soutenu annoncé, contexte utile pour parler de sécurisation et de réparation.',
            $temp < 0 => 'Gel attendu, pertinent pour mentionner isolation et protection hivernale.',
            $temp > 32 => 'Canicule en cours, intéressant pour mettre en avant l’isolation thermique.',
            default => 'Conditions météo modérées, à relier à l’entretien régulier et préventif.',
        };
    }

    public function refreshAllActiveCities(): int
    {
        $count = 0;

        City::query()
            ->active()
            ->whereNotNull('lat')
            ->whereNotNull('lon')
            ->chunkById(100, function ($cities) use (&$count): void {
                foreach ($cities as $city) {
                    $weather = $this->getCurrentWeather((float) $city->lat, (float) $city->lon);

                    $city->update([
                        'weather_data' => $weather,
                        'weather_updated_at' => now(),
                    ]);

                    $this->createEventIfNeeded($city, $weather);
                    $count++;
                }
            });

        return $count;
    }

    public function getWeatherRisks(City $city): string
    {
        $weather = $city->weather_data ?? [];
        $month = now()->month;
        $risks = [];

        if (($weather['rain_1h'] ?? 0) > 5 || in_array($month, [10, 11, 12, 1, 2], true)) {
            $risks[] = 'risque d’infiltration et d’humidité';
        }

        if (($weather['wind_speed'] ?? 0) > 14) {
            $risks[] = 'risque de soulèvement lié au vent';
        }

        if (($weather['temp'] ?? 0) < 0 || in_array($month, [12, 1, 2], true)) {
            $risks[] = 'risque de gel et de fissuration';
        }

        if (($weather['temp'] ?? 0) > 32 || in_array($month, [6, 7, 8], true)) {
            $risks[] = 'risque de surchauffe et de dilatation';
        }

        return $risks === [] ? 'Aucun risque météo majeur identifié actuellement.' : ucfirst(implode(', ', $risks)).'.';
    }

    private function createEventIfNeeded(City $city, array $weather): void
    {
        $eventType = null;
        $intensity = 'low';

        if (($weather['rain_1h'] ?? 0) > 5) {
            $eventType = 'rain';
            $intensity = ($weather['rain_1h'] ?? 0) > 20 ? 'high' : 'medium';
        } elseif (($weather['wind_speed'] ?? 0) > 14) {
            $eventType = 'wind';
            $intensity = ($weather['wind_speed'] ?? 0) > 20 ? 'high' : 'medium';
        } elseif (($weather['temp'] ?? 0) < 0) {
            $eventType = 'frost';
        } elseif (($weather['temp'] ?? 0) > 32) {
            $eventType = 'heatwave';
            $intensity = ($weather['temp'] ?? 0) > 38 ? 'extreme' : 'high';
        } elseif (($weather['weather_id'] ?? 0) >= 200 && ($weather['weather_id'] ?? 0) <= 299) {
            $eventType = 'storm';
            $intensity = 'high';
        }

        if ($eventType === null) {
            return;
        }

        WeatherEvent::query()->updateOrCreate(
            [
                'city_id' => $city->id,
                'event_type' => $eventType,
                'event_date' => now()->toDateString(),
            ],
            [
                'intensity' => $intensity,
                'description' => (string) ($weather['weather_description'] ?? ''),
                'used_for_content' => false,
            ]
        );
    }
}
