<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingEntryPositionsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_entry_positions_with_pagination(): void
    {
        $dependencies = $this->createDependencies();

        foreach ([
            ['indicator_dc' => 'Debito', 'amount' => '1500.50', 'position_text' => 'Movimiento de debito'],
            ['indicator_dc' => 'Credito', 'amount' => '1500.50', 'position_text' => 'Movimiento de credito'],
        ] as $index => $payload) {
            $response = $this->postJson('/api/v1/accounting/accounting-entry-positions', [
                'business_structure_id' => $dependencies['business_structure_id'],
                'accounting_document_id' => $dependencies['accounting_document_id'],
                'accounting_entry_header_id' => $dependencies['accounting_entry_header_id'],
                'accounting_accounts_id' => $dependencies['accounting_accounts_id'],
                'id_tercero' => 1000 + $index,
                'indicator_dc' => $payload['indicator_dc'],
                'amount' => $payload['amount'],
                'coin_id' => $dependencies['coin_id'],
                'cost_center_id' => $dependencies['cost_center_id'],
                'position_text' => $payload['position_text'],
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/accounting-entry-positions?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Posiciones de asiento contable obtenidas exitosamente')
            ->assertJsonPath('data.data.0.indicator_dc', 'Debito')
            ->assertJsonPath('data.data.0.business_structure.enterprise.name', 'Empresa Demo')
            ->assertJsonPath('data.data.0.accounting_document.code', 'COMP')
            ->assertJsonPath('data.data.0.accounting_entry_header.reference_document', 'REF-001')
            ->assertJsonPath('data.data.0.accounting_account.account', '110505')
            ->assertJsonPath('data.data.0.coin.name', 'Peso Colombiano')
            ->assertJsonPath('data.data.0.coin.alphabetic_code', 'COP')
            ->assertJsonPath('data.data.0.cost_center.code', 'CC-001')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_entry_position(): void
    {
        $dependencies = $this->createDependencies();

        $id = DB::table('accounting_entry_position')->insertGetId([
            'business_structure_id' => $dependencies['business_structure_id'],
            'accounting_document_id' => $dependencies['accounting_document_id'],
            'accounting_entry_header_id' => $dependencies['accounting_entry_header_id'],
            'accounting_accounts_id' => $dependencies['accounting_accounts_id'],
            'id_tercero' => 1001,
            'indicator_dc' => 'Debito',
            'amount' => '1000.00',
            'coin_id' => $dependencies['coin_id'],
            'cost_center_id' => $dependencies['cost_center_id'],
            'position_text' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-entry-positions/{$id}", [
            'business_structure_id' => $dependencies['business_structure_id'],
            'accounting_document_id' => $dependencies['accounting_document_id'],
            'accounting_entry_header_id' => $dependencies['accounting_entry_header_id'],
            'accounting_accounts_id' => $dependencies['accounting_accounts_id'],
            'id_tercero' => 2002,
            'indicator_dc' => 'Credito',
            'amount' => '2222.25',
            'coin_id' => $dependencies['coin_id'],
            'cost_center_id' => $dependencies['cost_center_id'],
            'position_text' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.id_tercero', 2002)
            ->assertJsonPath('data.indicator_dc', 'Credito')
            ->assertJsonPath('data.amount', '2222.25')
            ->assertJsonPath('data.position_text', 'Registro actualizado');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-entry-positions/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_entry_position', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-entry-positions/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.position_text', 'Registro actualizado')
            ->assertJsonPath('data.business_structure.enterprise.name', 'Empresa Demo')
            ->assertJsonPath('data.coin.name', 'Peso Colombiano');

        $this->assertDatabaseHas('accounting_entry_position', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    /**
     * @return array<string, int>
     */
    private function createDependencies(): array
    {
        $monthStartId = DB::table('months')->insertGetId([
            'name' => 'Enero',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthEndId = DB::table('months')->insertGetId([
            'name' => 'Diciembre',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingStandardId = DB::table('accounting_standard')->insertGetId([
            'name' => 'NIIF',
            'code' => 'NIIF',
            'description' => 'Norma base',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typePlanId = DB::table('types_plans')->insertGetId([
            'name' => 'General',
            'code' => 'GEN',
            'description' => 'Plan general',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chartAccountId = DB::table('chart_accounts')->insertGetId([
            'code' => '1105',
            'name' => 'Caja',
            'description' => 'Caja general',
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exerciseVariationId = DB::table('exercise_variations')->insertGetId([
            'code' => 'AN',
            'name' => 'Anual',
            'start_exercise' => $monthStartId,
            'end_exercise' => $monthEndId,
            'normal_periods' => 12,
            'special_periods' => 0,
            'calendar_dependent' => true,
            'description' => 'Ejercicio anual',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        $businessGroupId = DB::table('business_group')->insertGetId([
            'code' => 1,
            'name' => 'Grupo 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $enterpriseId = DB::table('enterprises')->insertGetId([
            'name' => 'Empresa Demo',
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

        $accountingAccountId = DB::table('accounting_accounts')->insertGetId([
            'account' => '110505',
            'chart_account_id' => $chartAccountId,
            'name' => 'Caja General',
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

        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'Accounting',
            'code' => 'ACC',
            'description' => 'Modulo contable',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $documentSourceTypeId = DB::table('document_source_type')->insertGetId([
            'name' => 'Manual',
            'code' => 'MAN',
            'description' => 'Documento manual',
            'generates_accounting' => true,
            'manual_entry' => true,
            'requires_approval' => false,
            'requires_third' => false,
            'requires_ceco' => false,
            'affects_inventory' => false,
            'affects_cartera' => false,
            'affects_cxp' => false,
            'affects_treasury' => true,
            'allows_reversal' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $referenceId = DB::table('reference')->insertGetId([
            'name' => 'Factura',
            'code' => 'FAC',
            'description' => 'Referencia factura',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $financialStatementId = DB::table('financial_statements')->insertGetId([
            'name' => 'Pendiente',
            'code' => 'P',
            'description' => 'Estado pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingDocumentId = DB::table('accounting_document')->insertGetId([
            'name' => 'Comprobante',
            'code' => 'COMP',
            'description' => 'Documento contable',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $documentSourceId = DB::table('documents_source')->insertGetId([
            'business_structure_id' => $businessStructureId,
            'modules_id' => $moduleId,
            'document_source_type_id' => $documentSourceTypeId,
            'number_document_source' => 'DOC-001',
            'document_date' => '2026-06-12 08:00:00',
            'accounting_date' => '2026-06-12 10:00:00',
            'reference_id' => $referenceId,
            'total_value' => '1500.50',
            'coin_id' => $coinId,
            'financial_statement_id' => $financialStatementId,
            'accounting_document_id' => $accountingDocumentId,
            'exercise' => 2026,
            'description' => 'Fuente documental base',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingEntryHeaderId = DB::table('accounting_entry_header')->insertGetId([
            'business_structure_id' => $businessStructureId,
            'accounting_document_id' => $accountingDocumentId,
            'accounting_period' => 202601,
            'coin_id' => $coinId,
            'description' => 'Encabezado base',
            'total_debits' => '1500.50',
            'total_credits' => '1500.50',
            'reference_document' => 'REF-001',
            'documents_source_id' => $documentSourceId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $campusId = DB::table('campus')->insertGetId([
            'name' => 'Sede Norte',
            'address' => 'Direccion Norte',
            'enable_code' => 'SED-NOR',
            'status_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $costCenterTypeId = DB::table('cost_center_type')->insertGetId([
            'name' => 'Tipo A',
            'code' => 'ADM',
            'description' => 'Tipo centro de costo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $costCenterClassId = DB::table('cost_center_class')->insertGetId([
            'name' => 'Clase A',
            'code' => 'CLS',
            'description' => 'Clase centro de costo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $costCenterNatureId = DB::table('cost_center_nature')->insertGetId([
            'name' => 'Naturaleza A',
            'code' => 'NAT',
            'description' => 'Naturaleza centro de costo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $costCenterId = DB::table('cost_center')->insertGetId([
            'business_structure_id' => $businessStructureId,
            'campus_id' => $campusId,
            'code' => 'CC-001',
            'name' => 'Administracion',
            'description' => 'Centro de costo base',
            'cost_center_type_id' => $costCenterTypeId,
            'cost_center_class_id' => $costCenterClassId,
            'cost_center_nature_id' => $costCenterNatureId,
            'allows_allocation' => true,
            'distributes_costs' => true,
            'functional_unit' => false,
            'profit_center' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'business_structure_id' => $businessStructureId,
            'accounting_document_id' => $accountingDocumentId,
            'accounting_entry_header_id' => $accountingEntryHeaderId,
            'accounting_accounts_id' => $accountingAccountId,
            'coin_id' => $coinId,
            'cost_center_id' => $costCenterId,
        ];
    }
}
