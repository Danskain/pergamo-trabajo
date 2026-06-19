<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingEntryHeadersCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_entry_headers_with_pagination(): void
    {
        $dependencies = $this->createDependencies();

        foreach ([
            ['reference_document' => 'REF-001', 'total_debits' => '1500.50', 'total_credits' => '1500.50'],
            ['reference_document' => 'REF-002', 'total_debits' => '2500.75', 'total_credits' => '2500.75'],
        ] as $index => $payload) {
            $response = $this->postJson('/api/v1/accounting/accounting-entry-headers', [
                'business_structure_id' => $dependencies['business_structure_id'],
                'accounting_document_id' => $dependencies['accounting_document_id'],
                'accounting_period' => 202601 + $index,
                'coin_id' => $dependencies['coin_id'],
                'description' => 'Encabezado contable de prueba',
                'total_debits' => $payload['total_debits'],
                'total_credits' => $payload['total_credits'],
                'reference_document' => $payload['reference_document'],
                'documents_source_id' => $dependencies['document_source_id'],
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/accounting-entry-headers?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Encabezados contables obtenidos exitosamente')
            ->assertJsonPath('data.data.0.reference_document', 'REF-001')
            ->assertJsonPath('data.data.0.business_structure.enterprise.name', 'Empresa Demo')
            ->assertJsonPath('data.data.0.coin.name', 'Peso Colombiano')
            ->assertJsonPath('data.data.0.coin.alphabetic_code', 'COP')
            ->assertJsonPath('data.data.0.accounting_document.code', 'COMP')
            ->assertJsonPath('data.data.0.document_source.number_document_source', 'DOC-001')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_entry_header(): void
    {
        $dependencies = $this->createDependencies();

        $id = DB::table('accounting_entry_header')->insertGetId([
            'business_structure_id' => $dependencies['business_structure_id'],
            'accounting_document_id' => $dependencies['accounting_document_id'],
            'accounting_period' => 202601,
            'coin_id' => $dependencies['coin_id'],
            'description' => 'Registro temporal',
            'total_debits' => '1000.00',
            'total_credits' => '1000.00',
            'reference_document' => 'REF-TMP',
            'documents_source_id' => $dependencies['document_source_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-entry-headers/{$id}", [
            'business_structure_id' => $dependencies['business_structure_id'],
            'accounting_document_id' => $dependencies['accounting_document_id'],
            'accounting_period' => 202602,
            'coin_id' => $dependencies['coin_id'],
            'description' => 'Registro actualizado',
            'total_debits' => '2222.25',
            'total_credits' => '2222.25',
            'reference_document' => 'REF-UPD',
            'documents_source_id' => $dependencies['document_source_id'],
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.reference_document', 'REF-UPD')
            ->assertJsonPath('data.total_debits', '2222.25')
            ->assertJsonPath('data.accounting_period', 202602)
            ->assertJsonPath('data.business_structure.enterprise.name', 'Empresa Demo')
            ->assertJsonPath('data.coin.name', 'Peso Colombiano')
            ->assertJsonPath('data.coin.alphabetic_code', 'COP');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-entry-headers/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_entry_header', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-entry-headers/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.reference_document', 'REF-UPD');

        $this->assertDatabaseHas('accounting_entry_header', [
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

        return [
            'business_structure_id' => $businessStructureId,
            'accounting_document_id' => $accountingDocumentId,
            'coin_id' => $coinId,
            'document_source_id' => $documentSourceId,
        ];
    }
}
