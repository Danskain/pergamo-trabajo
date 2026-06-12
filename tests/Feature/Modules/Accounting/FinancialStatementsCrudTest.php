<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FinancialStatementsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_financial_statements_with_pagination(): void
    {
        foreach ([
            ['name' => 'Pendiente', 'code' => 'P'],
            ['name' => 'Contabilizado', 'code' => 'C'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/financial-statements', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Estado financiero del sistema',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/financial-statements?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Estados financieros obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'Pendiente')
            ->assertJsonPath('data.data.0.code', 'P')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_financial_statement(): void
    {
        $id = DB::table('financial_statements')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/financial-statements/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/financial-statements/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('financial_statements', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/financial-statements/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('financial_statements', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
