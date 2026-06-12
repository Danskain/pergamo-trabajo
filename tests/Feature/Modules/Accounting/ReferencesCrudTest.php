<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReferencesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_references_with_pagination(): void
    {
        foreach ([
            ['name' => 'Factura', 'code' => 'FAC'],
            ['name' => 'Orden', 'code' => 'ORD'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/references', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Tipo de referencia del sistema',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/references?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Referencias obtenidas exitosamente')
            ->assertJsonPath('data.data.0.name', 'Factura')
            ->assertJsonPath('data.data.0.code', 'FAC')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_reference(): void
    {
        $id = DB::table('reference')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/references/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/references/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('reference', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/references/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('reference', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
