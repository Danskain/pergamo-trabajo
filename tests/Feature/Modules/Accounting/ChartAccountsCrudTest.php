<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChartAccountsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_chart_accounts_with_pagination(): void
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

        foreach (['Caja General', 'Bancos'] as $name) {
            $response = $this->postJson('/api/v1/accounting/chart-accounts', [
                'name' => $name,
                'code' => str($name)->upper()->replace(' ', '-')->toString(),
                'description' => 'Cuenta del plan contable',
                'accounting_standard_id' => $accountingStandardId,
                'types_plan_id' => $typePlanId,
                'ceco_permission' => true,
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/chart-accounts?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Cuentas del plan contable obtenidas exitosamente')
            ->assertJsonPath('data.data.0.name', 'Caja General')
            ->assertJsonPath('data.data.0.code', 'CAJA-GENERAL')
            ->assertJsonPath('data.data.0.description', 'Cuenta del plan contable')
            ->assertJsonPath('data.data.0.accounting_standard.name', 'NIIF Plenas')
            ->assertJsonPath('data.data.0.type_plan.name', 'General')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_chart_account(): void
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
            'code' => 'AUX',
            'description' => 'Tipo de plan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = DB::table('chart_accounts')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TEMP',
            'description' => 'Cuenta temporal',
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/chart-accounts/{$id}", [
            'name' => 'Cuenta Actualizada',
            'code' => 'CTA-ACT',
            'description' => 'Cuenta actualizada del plan',
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => true,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Cuenta Actualizada')
            ->assertJsonPath('data.code', 'CTA-ACT')
            ->assertJsonPath('data.description', 'Cuenta actualizada del plan')
            ->assertJsonPath('data.ceco_permission', true)
            ->assertJsonPath('data.accounting_standard.id', $accountingStandardId)
            ->assertJsonPath('data.type_plan.id', $typePlanId);

        $deleteResponse = $this->deleteJson("/api/v1/accounting/chart-accounts/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('chart_accounts', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/chart-accounts/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Cuenta Actualizada')
            ->assertJsonPath('data.code', 'CTA-ACT');

        $this->assertDatabaseHas('chart_accounts', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
