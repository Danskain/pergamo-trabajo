<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CostCenterClassesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_cost_center_classes_with_pagination(): void
    {
        foreach ([
            ['name' => 'Administrativa', 'code' => 'ADM'],
            ['name' => 'Operativa', 'code' => 'OPE'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/cost-center-classes', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Clase de centro de costo del sistema',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/cost-center-classes?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Clases de centro de costo obtenidas exitosamente')
            ->assertJsonPath('data.data.0.name', 'Administrativa')
            ->assertJsonPath('data.data.0.code', 'ADM')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_cost_center_class(): void
    {
        $id = DB::table('cost_center_class')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/cost-center-classes/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/cost-center-classes/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('cost_center_class', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/cost-center-classes/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('cost_center_class', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
