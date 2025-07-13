<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenMeteoService
{
    public function getCurrent(float $lat, float $lon): array
    {
        $cacheKey = sprintf('openmeteo_%s_%s', $lat, $lon);
        return Cache::remember($cacheKey, 300, function () use ($lat, $lon) {
            $response = Http::baseUrl(config('services.openmeteo.base_url'))
                ->get('/v1/forecast', [
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,pressure_msl,windspeed_10m',
                    'windspeed_unit' => 'ms',
                    'timezone' => 'auto',
                ]);

            return $response->json('current') ?? [];
        });
    }
}
