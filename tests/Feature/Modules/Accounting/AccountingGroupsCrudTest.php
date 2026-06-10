<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingGroupsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_groups_with_pagination(): void
    {
        $accountingNatureId = DB::table('accounting_nature')->insertGetId([
            'name' => 'Debito',
            'code' => 'D',
            'description' => 'Naturaleza debito',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountClassId = DB::table('account_class')->insertGetId([
            'name' => 'Activo',
            'accounting_nature_id' => $accountingNatureId,
            'description' => 'Clase activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([
            ['code' => '11', 'name' => 'Disponible', 'account_from' => 1100, 'account_to' => 1199],
            ['code' => '12', 'name' => 'Inversiones', 'account_from' => 1200, 'account_to' => 1299],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/accounting-groups', [
                'code' => $payload['code'],
                'account_class_id' => $accountClassId,
                'name' => $payload['name'],
                'description' => 'Grupo contable de prueba',
                'account_from' => $payload['account_from'],
                'account_to' => $payload['account_to'],
                'affects_closing' => true,
                'affects_financial_statements' => true,
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/accounting-groups?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Grupos contables obtenidos exitosamente')
            ->assertJsonPath('data.data.0.code', '11')
            ->assertJsonPath('data.data.0.name', 'Disponible')
            ->assertJsonPath('data.data.0.account_class.name', 'Activo')
            ->assertJsonPath('data.data.0.account_class.accounting_nature.code', 'D')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_group(): void
    {
        $accountingNatureId = DB::table('accounting_nature')->insertGetId([
            'name' => 'Credito',
            'code' => 'C',
            'description' => 'Naturaleza credito',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountClassId = DB::table('account_class')->insertGetId([
            'name' => 'Ingreso',
            'accounting_nature_id' => $accountingNatureId,
            'description' => 'Clase ingreso',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = DB::table('accounting_groups')->insertGetId([
            'code' => '41',
            'account_class_id' => $accountClassId,
            'name' => 'Operacionales',
            'description' => 'Grupo temporal',
            'account_from' => 4100,
            'account_to' => 4199,
            'affects_closing' => false,
            'affects_financial_statements' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-groups/{$id}", [
            'code' => '42',
            'account_class_id' => $accountClassId,
            'name' => 'No Operacionales',
            'description' => 'Grupo actualizado',
            'account_from' => 4200,
            'account_to' => 4299,
            'affects_closing' => true,
            'affects_financial_statements' => false,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.code', '42')
            ->assertJsonPath('data.name', 'No Operacionales')
            ->assertJsonPath('data.account_from', 4200)
            ->assertJsonPath('data.account_to', 4299)
            ->assertJsonPath('data.affects_closing', true)
            ->assertJsonPath('data.affects_financial_statements', false)
            ->assertJsonPath('data.account_class.id', $accountClassId);

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-groups/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_groups', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-groups/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.code', '42')
            ->assertJsonPath('data.name', 'No Operacionales');

        $this->assertDatabaseHas('accounting_groups', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
