<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\WeatherServiceInterface;
use App\Models\City;
use App\Models\WeatherEvent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService implements WeatherServiceInterface
{
    private string $baseUrl;

    private const WMO_MAIN = [
        0 => 'Clear', 1 => 'Clear', 2 => 'Clouds', 3 => 'Clouds',
        45 => 'Fog', 48 => 'Fog',
        51 => 'Drizzle', 53 => 'Drizzle', 55 => 'Drizzle',
        56 => 'Drizzle', 57 => 'Drizzle',
        61 => 'Rain', 63 => 'Rain', 65 => 'Rain',
        66 => 'Rain', 67 => 'Rain',
        71 => 'Snow', 73 => 'Snow', 75 => 'Snow', 77 => 'Snow',
        80 => 'Rain', 81 => 'Rain', 82 => 'Rain',
        85 => 'Snow', 86 => 'Snow',
        95 => 'Thunderstorm', 96 => 'Thunderstorm', 99 => 'Thunderstorm',
    ];

    private const WMO_DESC = [
        0 => 'ciel dégagé', 1 => 'généralement dégagé', 2 => 'partiellement nuageux', 3 => 'couvert',
        45 => 'brouillard', 48 => 'brouillard givrant',
        51 => 'bruine légère', 53 => 'bruine modérée', 55 => 'bruine dense',
        56 => 'bruine verglaçante légère', 57 => 'bruine verglaçante forte',
        61 => 'pluie légère', 63 => 'pluie modérée', 65 => 'pluie forte',
        66 => 'pluie verglaçante légère', 67 => 'pluie verglaçante forte',
        71 => 'neige légère', 73 => 'neige modérée', 75 => 'neige forte', 77 => 'grains de neige',
        80 => 'averses légères', 81 => 'averses modérées', 82 => 'averses violentes',
        85 => 'averses de neige légères', 86 => 'averses de neige fortes',
        95 => 'orage', 96 => 'orage avec grêle légère', 99 => 'orage avec grêle forte',
    ];

    public function __construct()
    {
        $this->baseUrl = (string) config('services.open_meteo.base_url', 'https://api.open-meteo.com/v1/forecast');
    }

    public function getCurrentWeather(float $lat, float $lon): array
    {
        return Cache::remember(
            sprintf('weather_om:%s,%s', round($lat, 3), round($lon, 3)),
            now()->addHours(3),
            function () use ($lat, $lon): array {
                $response = Http::timeout((int) config('services.open_meteo.timeout', 15))
                    ->get($this->baseUrl, [
                        'latitude'               => $lat,
                        'longitude'              => $lon,
                        'current'                => 'temperature_2m,apparent_temperature,relative_humidity_2m,precipitation,wind_speed_10m,weather_code',
                        'wind_speed_unit'        => 'ms',
                        'models'                 => config('services.open_meteo.model', 'meteofrance_seamless'),
                    ])
                    ->throw()
                    ->json();

                $current = $response['current'] ?? [];
                $code    = (int) ($current['weather_code'] ?? 0);

                return [
                    'temp'                => data_get($current, 'temperature_2m'),
                    'feels_like'          => data_get($current, 'apparent_temperature'),
                    'humidity'            => data_get($current, 'relative_humidity_2m'),
                    'wind_speed'          => data_get($current, 'wind_speed_10m'),
                    'rain_1h'             => data_get($current, 'precipitation', 0),
                    'weather_id'          => $code,
                    'weather_main'        => self::WMO_MAIN[$code] ?? 'Unknown',
                    'weather_description' => self::WMO_DESC[$code] ?? 'inconnu',
                ];
            }
        );
    }

    public function getWeatherContext(City $city): string
    {
        $weather = $city->weather_data ?? [];
        $temp    = (float) ($weather['temp'] ?? 0);
        $rain    = (float) ($weather['rain_1h'] ?? 0);
        $wind    = (float) ($weather['wind_speed'] ?? 0) * 3.6;

        return match (true) {
            $rain > 5  => 'Temps pluvieux prévu, pertinent pour évoquer la prévention et les infiltrations.',
            $wind > 35 => 'Vent soutenu annoncé, contexte utile pour parler de sécurisation et de réparation.',
            $temp < 0  => 'Gel attendu, pertinent pour mentionner isolation et protection hivernale.',
            $temp > 32 => 'Canicule en cours, intéressant pour mettre en avant l'isolation thermique.',
            default    => 'Conditions météo modérées, à relier à l'entretien régulier et préventif.',
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
                    try {
                        $weather = $this->getCurrentWeather((float) $city->lat, (float) $city->lon);

                        $city->update([
                            'weather_data'       => $weather,
                            'weather_updated_at' => now(),
                        ]);

                        $this->createEventIfNeeded($city, $weather);
                        $count++;
                    } catch (\Throwable) {
                        // skip city on API error
                    }
                }
            });

        return $count;
    }

    public function getWeatherRisks(City $city): string
    {
        $weather = $city->weather_data ?? [];
        $month   = now()->month;
        $risks   = [];

        if (($weather['rain_1h'] ?? 0) > 5 || in_array($month, [10, 11, 12, 1, 2], true)) {
            $risks[] = 'risque d'infiltration et d'humidité';
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
        } elseif (in_array($weather['weather_main'] ?? '', ['Thunderstorm'], true)) {
            $eventType = 'storm';
            $intensity = 'high';
        }

        if ($eventType === null) {
            return;
        }

        WeatherEvent::query()->updateOrCreate(
            [
                'city_id'    => $city->id,
                'event_type' => $eventType,
                'event_date' => now()->toDateString(),
            ],
            [
                'intensity'        => $intensity,
                'description'      => (string) ($weather['weather_description'] ?? ''),
                'used_for_content' => false,
            ]
        );
    }
}
