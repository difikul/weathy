<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForecastTest extends TestCase
{
    use RefreshDatabase;

    public function test_forecast_requires_coordinates(): void
    {
        $response = $this->get('/api/forecast');
        $response->assertStatus(422);
    }
}
