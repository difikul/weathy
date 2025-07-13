<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\WeatherLog;

class WeatherLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_weather_endpoint_logs_data(): void
    {
        Http::fake([
            'https://api.openweathermap.org/data/2.5/weather*' => Http::response([
                'main' => ['temp' => 20, 'humidity' => 60, 'pressure' => 1000],
                'wind' => ['speed' => 2],
            ], 200),
        ]);

        $response = $this->get('/api/weather?lat=49.1951&lon=16.6068');
        $response->assertStatus(200);
        $this->assertDatabaseCount('weather_logs', 1);
    }
}
