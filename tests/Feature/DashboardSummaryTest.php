<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\WeatherLog;

class DashboardSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_returns_data()
    {
        WeatherLog::create([
            'temperature' => 10,
            'humidity' => 50,
            'wind_speed' => 1,
            'pressure' => 1000,
            'precipitation' => 0,
            'lat' => 1,
            'lon' => 1,
            'source' => 'test',
            'timestamp' => now(),
        ]);

        $response = $this->get('/api/dashboard/summary');
        $response->assertStatus(200);
        $response->assertJsonStructure(['week' => ['temperature'], 'month' => ['temperature']]);
    }
}
