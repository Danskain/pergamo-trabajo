<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingNaturesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_natures_with_pagination(): void
    {
        foreach ([
            ['name' => 'Debito', 'code' => 'D'],
            ['name' => 'Credito', 'code' => 'C'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/accounting-natures', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Naturaleza contable del sistema',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/accounting-natures?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Naturalezas contables obtenidas exitosamente')
            ->assertJsonPath('data.data.0.name', 'Debito')
            ->assertJsonPath('data.data.0.code', 'D')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_nature(): void
    {
        $id = DB::table('accounting_nature')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-natures/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-natures/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_nature', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-natures/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('accounting_nature', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
