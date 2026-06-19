<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingMomentsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_moments_with_pagination(): void
    {
        $responseA = $this->postJson('/api/v1/accounting/accounting-moments', [
            'name' => 'Causacion',
            'code' => 'CAU',
            'description' => 'Momento de causacion',
        ]);

        $responseA
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Causacion')
            ->assertJsonPath('data.code', 'CAU');

        $responseB = $this->postJson('/api/v1/accounting/accounting-moments', [
            'name' => 'Pago',
            'code' => 'PAG',
            'description' => 'Momento de pago',
        ]);

        $responseB
            ->assertCreated()
            ->assertJsonPath('success', true);

        $listResponse = $this->getJson('/api/v1/accounting/accounting-moments?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Momentos contables obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'Causacion')
            ->assertJsonPath('data.data.0.code', 'CAU')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_moment(): void
    {
        $id = DB::table('accounting_moments')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-moments/{$id}", [
            'name' => 'Actualizado',
            'code' => 'ACT',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'ACT')
            ->assertJsonPath('data.description', 'Registro actualizado');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-moments/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_moments', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-moments/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'ACT');

        $this->assertDatabaseHas('accounting_moments', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
