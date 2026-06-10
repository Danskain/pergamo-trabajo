<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TypeAccountsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_types_accounts_with_pagination(): void
    {
        foreach ([
            ['name' => 'Auxiliar', 'code' => 'AUX'],
            ['name' => 'Mayor', 'code' => 'MAY'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/types-accounts', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Tipo de cuenta',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/types-accounts?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Tipos de cuenta obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'Auxiliar')
            ->assertJsonPath('data.data.0.code', 'AUX')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_type_account(): void
    {
        $id = DB::table('types_accounts')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TEMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/types-accounts/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/types-accounts/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('types_accounts', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/types-accounts/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('types_accounts', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
