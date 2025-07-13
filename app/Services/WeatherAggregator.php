<?php

namespace App\Services;

class WeatherAggregator
{
    public function __construct(
        protected OpenMeteoService $openMeteo,
        protected MetNorwayService $metNorway
    ) {
    }

    public function aggregate(float $lat, float $lon): array
    {
        $openMeteo = $this->openMeteo->getCurrent($lat, $lon);
        $metno = $this->metNorway->getCurrent($lat, $lon);

        return [
            'open_meteo' => $openMeteo,
            'met_norway' => $metno,
        ];
    }
}
