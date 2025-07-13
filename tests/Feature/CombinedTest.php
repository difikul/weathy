<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CombinedTest extends TestCase
{
    use RefreshDatabase;

    public function test_combined_requires_coordinates(): void
    {
        $response = $this->getJson('/api/combined');
        $response->assertStatus(422);
    }
}
