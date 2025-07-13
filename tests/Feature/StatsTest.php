<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\WeatherLog;
use Illuminate\Support\Facades\Date;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_returns_data(): void
    {
        WeatherLog::create([
            'temperature' => 10,
            'humidity' => 80,
            'wind_speed' => 1.2,
            'pressure' => 1000,
            'precipitation' => 0,
            'lat' => 49.1951,
            'lon' => 16.6068,
            'source' => 'test',
            'timestamp' => Date::now(),
        ]);

        $response = $this->getJson('/api/stats');
        $response->assertStatus(200);
        $response->assertJsonStructure(['avg_temp','min_temp','max_temp','predicted_temp']);
    }
}
