<?php

namespace Tests\Feature\Modules\Accounting;

use Database\Seeders\MonthsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExerciseVariationsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_exercise_variations_with_pagination(): void
    {
        $this->seed(MonthsSeeder::class);

        foreach ([
            ['name' => 'Ejercicio Enero - Diciembre', 'start_exercise' => 1, 'end_exercise' => 12],
            ['name' => 'Ejercicio Febrero - Noviembre', 'start_exercise' => 2, 'end_exercise' => 11],
        ] as $payload) {
            $createResponse = $this->postJson('/api/v1/accounting/exercise-variations', [
                'name' => $payload['name'],
                'start_exercise' => $payload['start_exercise'],
                'end_exercise' => $payload['end_exercise'],
                'normal_periods' => 12,
                'special_periods' => 2,
                'calendar_dependent' => true,
                'description' => 'Ejercicio fiscal anual',
            ]);

            $createResponse
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/exercise-variations?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Variaciones de ejercicio obtenidas exitosamente')
            ->assertJsonPath('data.data.0.name', 'Ejercicio Enero - Diciembre')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_exercise_variation(): void
    {
        $this->seed(MonthsSeeder::class);

        $id = DB::table('exercise_variations')->insertGetId([
            'name' => 'Temporal',
            'start_exercise' => 1,
            'end_exercise' => 6,
            'normal_periods' => 6,
            'special_periods' => 1,
            'calendar_dependent' => false,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/exercise-variations/{$id}", [
            'name' => 'Actualizado',
            'start_exercise' => 2,
            'end_exercise' => 11,
            'normal_periods' => 10,
            'special_periods' => 1,
            'calendar_dependent' => true,
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.start_month.name', 'Febrero')
            ->assertJsonPath('data.end_month.name', 'Noviembre');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/exercise-variations/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('exercise_variations', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/exercise-variations/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('exercise_variations', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
