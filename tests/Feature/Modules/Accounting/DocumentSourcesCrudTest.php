<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DocumentSourcesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_documents_source_with_pagination(): void
    {
        $dependencies = $this->createDependencies();

        foreach ([
            ['number_document_source' => 'DOC-001', 'total_value' => '1500.50'],
            ['number_document_source' => 'DOC-002', 'total_value' => '2500.75'],
        ] as $index => $payload) {
            $response = $this->postJson('/api/v1/accounting/documents-source', [
                'business_structure_id' => $dependencies['business_structure_id'],
                'modules_id' => $dependencies['module_id'],
                'document_source_type_id' => $dependencies['document_source_type_id'],
                'number_document_source' => $payload['number_document_source'],
                'document_date' => "2026-06-1{$index} 08:00:00",
                'accounting_date' => "2026-06-1{$index} 10:00:00",
                'reference_id' => $dependencies['reference_id'],
                'total_value' => $payload['total_value'],
                'coin_id' => $dependencies['coin_id'],
                'financial_statement_id' => $dependencies['financial_statement_id'],
                'accounting_document_id' => $dependencies['accounting_document_id'],
                'exercise' => 2026,
                'description' => 'Fuente documental de prueba',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/documents-source?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Fuentes de documentos obtenidas exitosamente')
            ->assertJsonPath('data.data.0.number_document_source', 'DOC-001')
            ->assertJsonPath('data.data.0.business_structure.enterprise.name', 'Empresa Demo')
            ->assertJsonPath('data.data.0.module.code', 'ACC')
            ->assertJsonPath('data.data.0.reference.code', 'FAC')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_document_source(): void
    {
        $dependencies = $this->createDependencies();

        $id = DB::table('documents_source')->insertGetId([
            'business_structure_id' => $dependencies['business_structure_id'],
            'modules_id' => $dependencies['module_id'],
            'document_source_type_id' => $dependencies['document_source_type_id'],
            'number_document_source' => 'DOC-TMP',
            'document_date' => '2026-06-12 08:00:00',
            'accounting_date' => '2026-06-12 10:00:00',
            'reference_id' => $dependencies['reference_id'],
            'total_value' => '1000.00',
            'coin_id' => $dependencies['coin_id'],
            'financial_statement_id' => $dependencies['financial_statement_id'],
            'accounting_document_id' => $dependencies['accounting_document_id'],
            'exercise' => 2026,
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/documents-source/{$id}", [
            'business_structure_id' => $dependencies['business_structure_id'],
            'modules_id' => $dependencies['module_id'],
            'document_source_type_id' => $dependencies['document_source_type_id'],
            'number_document_source' => 'DOC-UPD',
            'document_date' => '2026-06-13 08:00:00',
            'accounting_date' => '2026-06-13 10:00:00',
            'reference_id' => $dependencies['reference_id'],
            'total_value' => '2222.25',
            'coin_id' => $dependencies['coin_id'],
            'financial_statement_id' => $dependencies['financial_statement_id'],
            'accounting_document_id' => $dependencies['accounting_document_id'],
            'exercise' => 2027,
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.number_document_source', 'DOC-UPD')
            ->assertJsonPath('data.total_value', '2222.25')
            ->assertJsonPath('data.exercise', 2027)
            ->assertJsonPath('data.business_structure.enterprise.name', 'Empresa Demo');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/documents-source/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('documents_source', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/documents-source/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.number_document_source', 'DOC-UPD');

        $this->assertDatabaseHas('documents_source', [
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

        return [
            'business_structure_id' => $businessStructureId,
            'module_id' => $moduleId,
            'document_source_type_id' => $documentSourceTypeId,
            'reference_id' => $referenceId,
            'coin_id' => $coinId,
            'financial_statement_id' => $financialStatementId,
            'accounting_document_id' => $accountingDocumentId,
        ];
    }
}
