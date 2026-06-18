<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountClassesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_account_classes_with_pagination(): void
    {
        $debitNatureId = $this->createAccountingNature('D', 'Debito');
        $creditNatureId = $this->createAccountingNature('C', 'Credito');

        $responseA = $this->postJson('/api/v1/accounting/account-classes', [
            'name' => 'Activo',
            'accounting_nature_id' => $debitNatureId,
            'description' => 'Clase contable de activo',
        ]);

        $responseA
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Activo')
            ->assertJsonPath('data.accounting_nature.code', 'D');

        $responseB = $this->postJson('/api/v1/accounting/account-classes', [
            'name' => 'Pasivo',
            'accounting_nature_id' => $creditNatureId,
            'description' => 'Clase contable de pasivo',
        ]);

        $responseB
            ->assertCreated()
            ->assertJsonPath('success', true);

        $listResponse = $this->getJson('/api/v1/accounting/account-classes?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Clases contables obtenidas exitosamente')
            ->assertJsonPath('data.data.0.name', 'Activo')
            ->assertJsonPath('data.data.0.accounting_nature.name', 'Debito')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_account_class(): void
    {
        $debitNatureId = $this->createAccountingNature('D', 'Debito');
        $creditNatureId = $this->createAccountingNature('C', 'Credito');

        $id = DB::table('account_class')->insertGetId([
            'name' => 'Temporal',
            'accounting_nature_id' => $debitNatureId,
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/account-classes/{$id}", [
            'name' => 'Actualizado',
            'accounting_nature_id' => $creditNatureId,
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.accounting_nature.code', 'C');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/account-classes/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('account_class', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/account-classes/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.accounting_nature.code', 'C');

        $this->assertDatabaseHas('account_class', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    private function createAccountingNature(string $code, string $name): int
    {
        return DB::table('accounting_nature')->insertGetId([
            'name' => $name,
            'code' => $code,
            'description' => 'Naturaleza contable '.$name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
