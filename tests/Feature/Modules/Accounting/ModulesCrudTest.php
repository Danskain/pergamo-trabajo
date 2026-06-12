<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ModulesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_modules_with_pagination(): void
    {
        foreach ([
            ['name' => 'Contabilidad', 'code' => 'ACC'],
            ['name' => 'Tesoreria', 'code' => 'TRE'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/modules', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Modulo del sistema',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/modules?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Modulos obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'Contabilidad')
            ->assertJsonPath('data.data.0.code', 'ACC')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_module(): void
    {
        $id = DB::table('modules')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/modules/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/modules/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('modules', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/modules/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('modules', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
