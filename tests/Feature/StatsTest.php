<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\WeatherLog;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_returns_average(): void
    {
        WeatherLog::create([
            'temperature' => 10,
            'humidity' => 50,
            'wind_speed' => 1,
            'pressure' => 1010,
            'precipitation' => 0,
            'lat' => 49.1951,
            'lon' => 16.6068,
            'source' => 'test',
            'timestamp' => now(),
        ]);

        $response = $this->get('/api/stats');
        $response->assertStatus(200);
    }
}
