<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingSchemesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_schemes_with_relations(): void
    {
        $dependencies = $this->createDependencies();

        $responseA = $this->postJson('/api/v1/accounting/accounting-schemes', [
            'business_structure_id' => $dependencies['business_structure_id'],
            'chart_account_id' => $dependencies['chart_account_id'],
            'assessment_class' => 'Inventarios',
            'type_movement_id' => $dependencies['type_movement_id'],
            'accounting_event_id' => $dependencies['accounting_event_id'],
            'key_operation_id' => $dependencies['key_operation_id'],
            'accounting_account_id' => $dependencies['accounting_account_id'],
            'accounting_nature_id' => $dependencies['accounting_nature_id'],
            'require_coce' => true,
        ]);

        $responseA
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.assessment_class', 'Inventarios')
            ->assertJsonPath('data.business_structure.enterprise.name', 'Empresa Demo')
            ->assertJsonPath('data.chart_account.code', '1105')
            ->assertJsonPath('data.accounting_event.code', 'EV-001')
            ->assertJsonPath('data.key_operation.code', 'KO-001')
            ->assertJsonPath('data.accounting_account.account', '110505')
            ->assertJsonPath('data.accounting_nature.code', 'D')
            ->assertJsonPath('data.require_coce', true);

        $responseB = $this->postJson('/api/v1/accounting/accounting-schemes', [
            'business_structure_id' => $dependencies['business_structure_id'],
            'chart_account_id' => $dependencies['chart_account_id'],
            'assessment_class' => 'Compras',
            'type_movement_id' => $dependencies['type_movement_id'],
            'accounting_event_id' => $dependencies['accounting_event_id'],
            'key_operation_id' => $dependencies['key_operation_id'],
            'accounting_account_id' => $dependencies['accounting_account_id'],
            'accounting_nature_id' => $dependencies['accounting_nature_id'],
            'require_coce' => false,
        ]);

        $responseB
            ->assertCreated()
            ->assertJsonPath('success', true);

        $listResponse = $this->getJson('/api/v1/accounting/accounting-schemes?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Esquemas contables obtenidos exitosamente')
            ->assertJsonPath('data.data.0.assessment_class', 'Inventarios')
            ->assertJsonPath('data.data.0.business_structure.enterprise.name', 'Empresa Demo')
            ->assertJsonPath('data.data.0.accounting_event.accounting_moment.code', 'CAU')
            ->assertJsonPath('data.data.0.key_operation.module.code', 'ACC')
            ->assertJsonPath('data.data.0.type_movement.quantity', 12)
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_scheme(): void
    {
        $dependencies = $this->createDependencies();
        $updatedDependencies = $this->createDependencies('B');

        $id = DB::table('accounting_schemes')->insertGetId([
            'business_structure_id' => $dependencies['business_structure_id'],
            'chart_account_id' => $dependencies['chart_account_id'],
            'assessment_class' => 'Temporal',
            'type_movement_id' => $dependencies['type_movement_id'],
            'accounting_event_id' => $dependencies['accounting_event_id'],
            'key_operation_id' => $dependencies['key_operation_id'],
            'accounting_account_id' => $dependencies['accounting_account_id'],
            'accounting_nature_id' => $dependencies['accounting_nature_id'],
            'require_coce' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-schemes/{$id}", [
            'business_structure_id' => $updatedDependencies['business_structure_id'],
            'chart_account_id' => $updatedDependencies['chart_account_id'],
            'assessment_class' => 'Actualizado',
            'type_movement_id' => $updatedDependencies['type_movement_id'],
            'accounting_event_id' => $updatedDependencies['accounting_event_id'],
            'key_operation_id' => $updatedDependencies['key_operation_id'],
            'accounting_account_id' => $updatedDependencies['accounting_account_id'],
            'accounting_nature_id' => $updatedDependencies['accounting_nature_id'],
            'require_coce' => true,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.assessment_class', 'Actualizado')
            ->assertJsonPath('data.business_structure.enterprise.name', 'Empresa Demo B')
            ->assertJsonPath('data.accounting_event.code', 'EV-B01')
            ->assertJsonPath('data.key_operation.code', 'KO-B01')
            ->assertJsonPath('data.accounting_account.account', '220505')
            ->assertJsonPath('data.accounting_nature.code', 'C')
            ->assertJsonPath('data.require_coce', true);

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-schemes/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_schemes', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-schemes/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.assessment_class', 'Actualizado')
            ->assertJsonPath('data.accounting_event.accounting_moment.code', 'PAG')
            ->assertJsonPath('data.key_operation.module.code', 'LOG')
            ->assertJsonPath('data.require_coce', true);

        $this->assertDatabaseHas('accounting_schemes', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    /**
     * @return array<string, int>
     */
    private function createDependencies(string $suffix = ''): array
    {
        $suffixLabel = $suffix === '' ? '' : ' '.$suffix;
        $monthStartId = DB::table('months')->insertGetId([
            'name' => 'Enero'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthEndId = DB::table('months')->insertGetId([
            'name' => 'Diciembre'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingStandardId = DB::table('accounting_standard')->insertGetId([
            'name' => 'NIIF'.$suffixLabel,
            'code' => $suffix === '' ? 'NIIF' : 'NIIF-'.$suffix,
            'description' => 'Norma base'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typePlanId = DB::table('types_plans')->insertGetId([
            'name' => 'General'.$suffixLabel,
            'code' => $suffix === '' ? 'GEN' : 'GEN'.$suffix,
            'description' => 'Plan general'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chartAccountId = DB::table('chart_accounts')->insertGetId([
            'code' => $suffix === '' ? '1105' : '2205',
            'name' => 'Caja'.$suffixLabel,
            'description' => 'Cuenta base'.$suffixLabel,
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exerciseVariationId = DB::table('exercise_variations')->insertGetId([
            'code' => $suffix === '' ? 'AN' : 'SM',
            'name' => $suffix === '' ? 'Anual' : 'Semestral',
            'start_exercise' => $monthStartId,
            'end_exercise' => $monthEndId,
            'normal_periods' => 12,
            'special_periods' => 0,
            'calendar_dependent' => true,
            'description' => 'Ejercicio contable'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $countryId = DB::table('country')->insertGetId([
            'name' => 'Colombia'.$suffixLabel,
            'status_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $coinId = DB::table('coins')->insertGetId([
            'name' => $suffix === '' ? 'Peso Colombiano' : 'Dolar',
            'alphabetic_code' => $suffix === '' ? 'COP' : 'USD',
            'numeric_code' => $suffix === '' ? 170 : 840,
            'subunit' => 'Centavo',
            'subunit_ratio' => 100,
            'decimal_digits' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $businessGroupId = DB::table('business_group')->insertGetId([
            'code' => $suffix === '' ? 1 : 2,
            'name' => 'Grupo'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $enterpriseId = DB::table('enterprises')->insertGetId([
            'name' => 'Empresa Demo'.$suffixLabel,
            'business_group_id' => $businessGroupId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $businessStructureId = DB::table('business_structure')->insertGetId([
            'country_id' => $countryId,
            'coin_id' => $coinId,
            'enterprise_id' => $enterpriseId,
            'exercise_variation_id' => $exerciseVariationId,
            'chart_account_id' => $chartAccountId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingNatureId = DB::table('accounting_nature')->insertGetId([
            'name' => $suffix === '' ? 'Debito' : 'Credito',
            'code' => $suffix === '' ? 'D' : 'C',
            'description' => 'Naturaleza contable'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountClassId = DB::table('account_class')->insertGetId([
            'name' => 'Clase'.$suffixLabel,
            'accounting_nature_id' => $accountingNatureId,
            'description' => 'Clase contable'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typeAccountId = DB::table('types_accounts')->insertGetId([
            'name' => 'Auxiliar'.$suffixLabel,
            'code' => $suffix === '' ? 'AUX' : 'AUX'.$suffix,
            'description' => 'Tipo de cuenta'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingGroupId = DB::table('accounting_groups')->insertGetId([
            'code' => $suffix === '' ? '11' : '22',
            'account_class_id' => $accountClassId,
            'name' => 'Grupo disponible'.$suffixLabel,
            'description' => 'Grupo contable'.$suffixLabel,
            'account_from' => $suffix === '' ? 1100 : 2200,
            'account_to' => $suffix === '' ? 1199 : 2299,
            'affects_closing' => true,
            'affects_financial_statements' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingAccountId = DB::table('accounting_accounts')->insertGetId([
            'account' => $suffix === '' ? '110505' : '220505',
            'chart_account_id' => $chartAccountId,
            'name' => 'Cuenta Scheme'.$suffixLabel,
            'account_class_id' => $accountClassId,
            'types_account_id' => $typeAccountId,
            'accounting_group_id' => $accountingGroupId,
            'allows_manual_transactions' => true,
            'associated_account' => false,
            'accepts_taxes' => false,
            'foreign_currency' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingMomentId = DB::table('accounting_moments')->insertGetId([
            'name' => $suffix === '' ? 'Causacion' : 'Pago',
            'code' => $suffix === '' ? 'CAU' : 'PAG',
            'description' => 'Momento contable'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingEventId = DB::table('accounting_events')->insertGetId([
            'code' => $suffix === '' ? 'EV-001' : 'EV-B01',
            'name' => 'Evento'.$suffixLabel,
            'accounting_moment_id' => $accountingMomentId,
            'origin' => $suffix === '' ? 'manual' : 'automatico',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $moduleId = DB::table('modules')->insertGetId([
            'name' => $suffix === '' ? 'Accounting' : 'Logistica',
            'code' => $suffix === '' ? 'ACC' : 'LOG',
            'description' => 'Modulo'.$suffixLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $keyOperationId = DB::table('key_operations')->insertGetId([
            'code' => $suffix === '' ? 'KO-001' : 'KO-B01',
            'name' => 'Operacion'.$suffixLabel,
            'module_id' => $moduleId,
            'accounting_nature_id' => $accountingNatureId,
            'affects_taxes' => $suffix === '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typeMovementId = DB::table('product_inventory_movements')->insertGetId([
            'batch_id' => $suffix === '' ? 1 : 2,
            'concept_id' => $suffix === '' ? 10 : 20,
            'control_book' => $suffix === '',
            'date' => $suffix === '' ? '2026-06-22' : '2026-06-23',
            'invoice_id' => null,
            'pharmacy_stock_id' => null,
            'prescription_number' => null,
            'quantity' => $suffix === '' ? 12 : 7,
            'record_number' => $suffix === '' ? 'ACT-001' : 'ACT-002',
            'remarks' => 'Movimiento'.$suffixLabel,
            'user_id' => 1,
            'pharmacy_product_request_id' => null,
            'inventory_count_id' => null,
            'movement_dispatch_id' => null,
            'request_inventory_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'business_structure_id' => $businessStructureId,
            'chart_account_id' => $chartAccountId,
            'type_movement_id' => $typeMovementId,
            'accounting_event_id' => $accountingEventId,
            'key_operation_id' => $keyOperationId,
            'accounting_account_id' => $accountingAccountId,
            'accounting_nature_id' => $accountingNatureId,
        ];
    }
}
