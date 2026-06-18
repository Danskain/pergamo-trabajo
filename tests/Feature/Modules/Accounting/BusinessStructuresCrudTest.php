<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BusinessStructuresCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_business_structures_with_pagination(): void
    {
        $countryId = DB::table('country')->insertGetId([
            'name' => 'Colombia',
            'status_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $coinId = DB::table('coins')->insertGetId([
            'name' => 'Peso Colombiano',
            'alphabetic_code' => 'COP',
            'numeric_code' => 170,
            'subunit' => 'Centavo',
            'subunit_ratio' => 100,
            'decimal_digits' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $enterpriseId = DB::table('enterprises')->insertGetId([
            'name' => 'Pergamo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthFromId = DB::table('months')->insertGetId([
            'name' => 'Enero',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthToId = DB::table('months')->insertGetId([
            'name' => 'Diciembre',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exerciseVariationId = DB::table('exercise_variations')->insertGetId([
            'code' => 'EV-01',
            'name' => 'Anual',
            'start_exercise' => $monthFromId,
            'end_exercise' => $monthToId,
            'normal_periods' => 12,
            'special_periods' => 0,
            'calendar_dependent' => true,
            'description' => 'Ejercicio anual',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        foreach ([1, 2] as $index) {
            $response = $this->postJson('/api/v1/accounting/business-structures', [
                'country_id' => $countryId,
                'coin_id' => $coinId,
                'enterprise_id' => $enterpriseId,
                'exercise_variation_id' => $exerciseVariationId,
                'chart_account_id' => $chartAccountId,
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true)
                ->assertJsonPath('data.country_id', $countryId + 0 * $index);
        }

        $listResponse = $this->getJson('/api/v1/accounting/business-structures?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Estructuras de negocio obtenidas exitosamente')
            ->assertJsonPath('data.data.0.country_id', $countryId)
            ->assertJsonPath('data.data.0.country.name', 'Colombia')
            ->assertJsonPath('data.data.0.coin.name', 'Peso Colombiano')
            ->assertJsonPath('data.data.0.enterprise.name', 'Pergamo')
            ->assertJsonPath('data.data.0.exercise_variation.code', 'EV-01')
            ->assertJsonPath('data.data.0.chart_account.code', 'CAJA')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_business_structure(): void
    {
        $countryId = DB::table('country')->insertGetId([
            'name' => 'Colombia',
            'status_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherCountryId = DB::table('country')->insertGetId([
            'name' => 'Peru',
            'status_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $coinId = DB::table('coins')->insertGetId([
            'name' => 'Peso Colombiano',
            'alphabetic_code' => 'COP',
            'numeric_code' => 170,
            'subunit' => 'Centavo',
            'subunit_ratio' => 100,
            'decimal_digits' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherCoinId = DB::table('coins')->insertGetId([
            'name' => 'Sol',
            'alphabetic_code' => 'PEN',
            'numeric_code' => 604,
            'subunit' => 'Centimo',
            'subunit_ratio' => 100,
            'decimal_digits' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $enterpriseId = DB::table('enterprises')->insertGetId([
            'name' => 'Pergamo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherEnterpriseId = DB::table('enterprises')->insertGetId([
            'name' => 'Pergamo Peru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthFromId = DB::table('months')->insertGetId([
            'name' => 'Enero',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthToId = DB::table('months')->insertGetId([
            'name' => 'Diciembre',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherMonthFromId = DB::table('months')->insertGetId([
            'name' => 'Julio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherMonthToId = DB::table('months')->insertGetId([
            'name' => 'Junio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exerciseVariationId = DB::table('exercise_variations')->insertGetId([
            'code' => 'EV-01',
            'name' => 'Anual',
            'start_exercise' => $monthFromId,
            'end_exercise' => $monthToId,
            'normal_periods' => 12,
            'special_periods' => 0,
            'calendar_dependent' => true,
            'description' => 'Ejercicio anual',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherExerciseVariationId = DB::table('exercise_variations')->insertGetId([
            'code' => 'EV-02',
            'name' => 'Semestral',
            'start_exercise' => $otherMonthFromId,
            'end_exercise' => $otherMonthToId,
            'normal_periods' => 6,
            'special_periods' => 0,
            'calendar_dependent' => false,
            'description' => 'Ejercicio semestral',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        $otherChartAccountId = DB::table('chart_accounts')->insertGetId([
            'name' => 'Clientes',
            'code' => 'CLI',
            'description' => 'Cuenta del plan',
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $id = DB::table('business_structure')->insertGetId([
            'country_id' => $countryId,
            'coin_id' => $coinId,
            'enterprise_id' => $enterpriseId,
            'exercise_variation_id' => $exerciseVariationId,
            'chart_account_id' => $chartAccountId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/business-structures/{$id}", [
            'country_id' => $otherCountryId,
            'coin_id' => $otherCoinId,
            'enterprise_id' => $otherEnterpriseId,
            'exercise_variation_id' => $otherExerciseVariationId,
            'chart_account_id' => $otherChartAccountId,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.country_id', $otherCountryId)
            ->assertJsonPath('data.coin_id', $otherCoinId)
            ->assertJsonPath('data.enterprise_id', $otherEnterpriseId)
            ->assertJsonPath('data.country.name', 'Peru')
            ->assertJsonPath('data.coin.name', 'Sol')
            ->assertJsonPath('data.enterprise.name', 'Pergamo Peru')
            ->assertJsonPath('data.exercise_variation_id', $otherExerciseVariationId)
            ->assertJsonPath('data.chart_account_id', $otherChartAccountId)
            ->assertJsonPath('data.exercise_variation.code', 'EV-02')
            ->assertJsonPath('data.chart_account.code', 'CLI');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/business-structures/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('business_structure', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/business-structures/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.chart_account_id', $otherChartAccountId);

        $this->assertDatabaseHas('business_structure', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
