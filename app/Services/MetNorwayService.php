<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MetNorwayService
{
    public function getCurrent(float $lat, float $lon): array
    {
        $cacheKey = sprintf('metno_%s_%s', $lat, $lon);
        return Cache::remember($cacheKey, 300, function () use ($lat, $lon) {
            $response = Http::withHeaders([
                'User-Agent' => 'WeathyApp/1.0 github.com/example/weathy',
            ])->baseUrl(config('services.metno.base_url'))
                ->get('/weatherapi/locationforecast/2.0/compact', [
                    'lat' => $lat,
                    'lon' => $lon,
                ]);

            return $response->json('properties.timeseries.0.data.instant.details') ?? [];
        });
    }
}
