<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KeyOperationsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_key_operations_with_pagination(): void
    {
        $moduleId = $this->createModule('ACC', 'Accounting');
        $accountingNatureId = $this->createAccountingNature('D', 'Debito');

        $responseA = $this->postJson('/api/v1/accounting/key-operations', [
            'code' => 'KO-001',
            'name' => 'Operacion de Compra',
            'module_id' => $moduleId,
            'accounting_nature_id' => $accountingNatureId,
            'affects_taxes' => true,
        ]);

        $responseA
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.code', 'KO-001')
            ->assertJsonPath('data.module.code', 'ACC')
            ->assertJsonPath('data.accounting_nature.code', 'D')
            ->assertJsonPath('data.affects_taxes', true);

        $responseB = $this->postJson('/api/v1/accounting/key-operations', [
            'code' => 'KO-002',
            'name' => 'Operacion de Venta',
            'module_id' => $moduleId,
            'accounting_nature_id' => $accountingNatureId,
            'affects_taxes' => false,
        ]);

        $responseB
            ->assertCreated()
            ->assertJsonPath('success', true);

        $listResponse = $this->getJson('/api/v1/accounting/key-operations?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Operaciones clave obtenidas exitosamente')
            ->assertJsonPath('data.data.0.code', 'KO-001')
            ->assertJsonPath('data.data.0.name', 'Operacion de Compra')
            ->assertJsonPath('data.data.0.module.name', 'Accounting')
            ->assertJsonPath('data.data.0.accounting_nature.name', 'Debito')
            ->assertJsonPath('data.data.0.affects_taxes', true)
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_key_operation(): void
    {
        $moduleId = $this->createModule('ACC', 'Accounting');
        $accountingNatureId = $this->createAccountingNature('D', 'Debito');
        $updatedAccountingNatureId = $this->createAccountingNature('C', 'Credito');
        $updatedModuleId = $this->createModule('INV', 'Inventarios');

        $id = DB::table('key_operations')->insertGetId([
            'code' => 'KO-TMP',
            'name' => 'Operacion Temporal',
            'module_id' => $moduleId,
            'accounting_nature_id' => $accountingNatureId,
            'affects_taxes' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/key-operations/{$id}", [
            'code' => 'KO-UPD',
            'name' => 'Operacion Actualizada',
            'module_id' => $updatedModuleId,
            'accounting_nature_id' => $updatedAccountingNatureId,
            'affects_taxes' => true,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.code', 'KO-UPD')
            ->assertJsonPath('data.name', 'Operacion Actualizada')
            ->assertJsonPath('data.module.code', 'INV')
            ->assertJsonPath('data.accounting_nature.code', 'C')
            ->assertJsonPath('data.affects_taxes', true);

        $deleteResponse = $this->deleteJson("/api/v1/accounting/key-operations/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('key_operations', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/key-operations/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.code', 'KO-UPD')
            ->assertJsonPath('data.module.code', 'INV')
            ->assertJsonPath('data.accounting_nature.code', 'C')
            ->assertJsonPath('data.affects_taxes', true);

        $this->assertDatabaseHas('key_operations', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    private function createModule(string $code, string $name): int
    {
        return DB::table('modules')->insertGetId([
            'code' => $code,
            'name' => $name,
            'description' => 'Modulo '.$name,
            'created_at' => now(),
            'updated_at' => now(),
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
