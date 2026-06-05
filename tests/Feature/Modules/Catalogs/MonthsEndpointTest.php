<?php

namespace Tests\Feature\Modules\Catalogs;

use Database\Seeders\MonthsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthsEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_months(): void
    {
        $this->seed(MonthsSeeder::class);

        $response = $this->getJson('/api/v1/catalogs/months');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.name', 'Enero')
            ->assertJsonPath('data.11.name', 'Diciembre');

        $this->assertCount(12, $response->json('data'));
    }
}
