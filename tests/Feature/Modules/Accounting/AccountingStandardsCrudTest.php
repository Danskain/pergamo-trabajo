<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingStandardsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_standards_with_pagination(): void
    {
        foreach ([
            ['name' => 'NIIF Plenas', 'code' => 'NIIF-FULL'],
            ['name' => 'NIIF Pymes', 'code' => 'NIIF-SME'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/accounting-standards', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Estandar contable',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/accounting-standards?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Estandares contables obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'NIIF Plenas')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_standard(): void
    {
        $id = DB::table('accounting_standard')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TEMP',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-standards/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-standards/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_standard', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-standards/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('accounting_standard', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
