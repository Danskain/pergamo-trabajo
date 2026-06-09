<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TypePlansCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_types_plans_with_pagination(): void
    {
        foreach ([
            ['name' => 'Plan General', 'code' => 'PGC'],
            ['name' => 'Plan Simplificado', 'code' => 'PSM'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/types-plans', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Tipo de plan contable',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/types-plans?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Tipos de plan obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'Plan General')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_type_plan(): void
    {
        $id = DB::table('types_plans')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TEMP',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/types-plans/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/types-plans/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('types_plans', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/types-plans/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('types_plans', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
