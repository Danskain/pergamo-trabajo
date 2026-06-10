<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingAccountsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_accounts_with_pagination(): void
    {
        $accountingStandardId = DB::table('accounting_standard')->insertGetId([
            'name' => 'NIIF Plenas',
            'code' => 'NIIF-FULL',
            'description' => 'Estandar contable',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typePlanId = DB::table('types_plans')->insertGetId([
            'name' => 'General',
            'code' => 'GEN',
            'description' => 'Tipo de plan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chartAccountId = DB::table('chart_accounts')->insertGetId([
            'name' => 'Caja',
            'code' => 'CAJA',
            'description' => 'Cuenta del plan',
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        $typeAccountId = DB::table('types_accounts')->insertGetId([
            'name' => 'Auxiliar',
            'code' => 'AUX',
            'description' => 'Tipo de cuenta',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingGroupId = DB::table('accounting_groups')->insertGetId([
            'code' => '11',
            'account_class_id' => $accountClassId,
            'name' => 'Disponible',
            'description' => 'Grupo disponible',
            'account_from' => 1100,
            'account_to' => 1199,
            'affects_closing' => true,
            'affects_financial_statements' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([
            ['account' => '110505', 'name' => 'Caja General'],
            ['account' => '110510', 'name' => 'Caja Menor'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/accounting-accounts', [
                'account' => $payload['account'],
                'chart_account_id' => $chartAccountId,
                'name' => $payload['name'],
                'account_class_id' => $accountClassId,
                'types_account_id' => $typeAccountId,
                'accounting_group_id' => $accountingGroupId,
                'allows_manual_transactions' => true,
                'associated_account' => false,
                'accepts_taxes' => false,
                'foreign_currency' => false,
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/accounting-accounts?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Cuentas contables obtenidas exitosamente')
            ->assertJsonPath('data.data.0.account', '110505')
            ->assertJsonPath('data.data.0.name', 'Caja General')
            ->assertJsonPath('data.data.0.chart_account.name', 'Caja')
            ->assertJsonPath('data.data.0.account_class.name', 'Activo')
            ->assertJsonPath('data.data.0.type_account.code', 'AUX')
            ->assertJsonPath('data.data.0.accounting_group.code', '11')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_account(): void
    {
        $accountingStandardId = DB::table('accounting_standard')->insertGetId([
            'name' => 'NIIF Pymes',
            'code' => 'NIIF-SME',
            'description' => 'Estandar contable',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typePlanId = DB::table('types_plans')->insertGetId([
            'name' => 'Auxiliar',
            'code' => 'AUXP',
            'description' => 'Tipo de plan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chartAccountId = DB::table('chart_accounts')->insertGetId([
            'name' => 'Bancos',
            'code' => 'BAN',
            'description' => 'Cuenta del plan',
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        $typeAccountId = DB::table('types_accounts')->insertGetId([
            'name' => 'Mayor',
            'code' => 'MAY',
            'description' => 'Tipo de cuenta',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingGroupId = DB::table('accounting_groups')->insertGetId([
            'code' => '41',
            'account_class_id' => $accountClassId,
            'name' => 'Operacionales',
            'description' => 'Grupo operacional',
            'account_from' => 4100,
            'account_to' => 4199,
            'affects_closing' => false,
            'affects_financial_statements' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = DB::table('accounting_accounts')->insertGetId([
            'account' => '410505',
            'chart_account_id' => $chartAccountId,
            'name' => 'Ingreso Temporal',
            'account_class_id' => $accountClassId,
            'types_account_id' => $typeAccountId,
            'accounting_group_id' => $accountingGroupId,
            'allows_manual_transactions' => false,
            'associated_account' => true,
            'accepts_taxes' => true,
            'foreign_currency' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-accounts/{$id}", [
            'account' => '410510',
            'chart_account_id' => $chartAccountId,
            'name' => 'Ingreso Actualizado',
            'account_class_id' => $accountClassId,
            'types_account_id' => $typeAccountId,
            'accounting_group_id' => $accountingGroupId,
            'allows_manual_transactions' => true,
            'associated_account' => false,
            'accepts_taxes' => false,
            'foreign_currency' => true,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.account', '410510')
            ->assertJsonPath('data.name', 'Ingreso Actualizado')
            ->assertJsonPath('data.allows_manual_transactions', true)
            ->assertJsonPath('data.associated_account', false)
            ->assertJsonPath('data.accepts_taxes', false)
            ->assertJsonPath('data.foreign_currency', true)
            ->assertJsonPath('data.chart_account.id', $chartAccountId);

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-accounts/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_accounts', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-accounts/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.account', '410510');

        $this->assertDatabaseHas('accounting_accounts', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
