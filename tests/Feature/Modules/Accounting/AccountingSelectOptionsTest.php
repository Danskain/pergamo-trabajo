<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingSelectOptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_select_options_for_a_supported_catalog(): void
    {
        DB::table('cost_center_type')->insert([
            [
                'name' => 'Administrativo',
                'code' => 'ADM',
                'description' => 'Tipo administrativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operativo',
                'code' => 'OPE',
                'description' => 'Tipo operativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->getJson('/api/v1/accounting/select-options/cost_center_type?search=ADM&limit=10');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Select options retrieved successfully.')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.catalogs.0', 'cost_center_type')
            ->assertJsonPath('meta.enriched_labels', false)
            ->assertJsonPath('data.0.label', 'ADM - Administrativo')
            ->assertJsonPath('data.0.meta.code', 'ADM')
            ->assertJsonPath('data.0.meta.name', 'Administrativo');
    }

    public function test_it_returns_multiple_catalogs_in_a_single_request(): void
    {
        DB::table('cost_center_type')->insert([
            'name' => 'Administrativo',
            'code' => 'ADM',
            'description' => 'Tipo administrativo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('cost_center_class')->insert([
            'name' => 'Clase General',
            'code' => 'CLS',
            'description' => 'Clase de prueba',
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
            'name' => 'Pergamo SAS',
            'business_group_id' => $businessGroupId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $campusId = DB::table('campus')->insertGetId([
            'name' => 'Sede Norte',
            'address' => 'Calle 1',
            'enable_code' => 'SED-N',
            'status_id' => 1,
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

        DB::table('account_class')->insert([
            'name' => 'Activo',
            'accounting_nature_id' => $accountingNatureId,
            'description' => 'Clase contable de activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/accounting/select-options?catalogs=cost_center_type,cost_center_class,account_class,country,coins,enterprises,campus');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.catalogs.0', 'cost_center_type')
            ->assertJsonPath('meta.catalogs.1', 'cost_center_class')
            ->assertJsonPath('meta.catalogs.2', 'account_class')
            ->assertJsonPath('meta.catalogs.3', 'country')
            ->assertJsonPath('meta.catalogs.4', 'coins')
            ->assertJsonPath('meta.catalogs.5', 'enterprises')
            ->assertJsonPath('meta.catalogs.6', 'campus')
            ->assertJsonPath('data.cost_center_type.0.label', 'ADM - Administrativo')
            ->assertJsonPath('data.cost_center_class.0.label', 'CLS - Clase General')
            ->assertJsonPath('data.account_class.0.label', 'Activo')
            ->assertJsonPath('data.account_class.0.meta.accounting_nature_id', $accountingNatureId)
            ->assertJsonPath('data.country.0.value', $countryId)
            ->assertJsonPath('data.country.0.label', 'Colombia')
            ->assertJsonPath('data.coins.0.value', $coinId)
            ->assertJsonPath('data.coins.0.label', 'COP - Peso Colombiano')
            ->assertJsonPath('data.enterprises.0.value', $enterpriseId)
            ->assertJsonPath('data.enterprises.0.label', 'Pergamo SAS')
            ->assertJsonPath('data.campus.0.value', $campusId)
            ->assertJsonPath('data.campus.0.label', 'SED-N - Sede Norte');
    }

    public function test_it_can_return_enriched_labels_when_requested(): void
    {
        $this->createBusinessStructureDependencies();
        $documentSourceId = $this->createDocumentSourceDependencies();
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
            'description' => 'Clase contable de activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $businessStructureResponse = $this->getJson('/api/v1/accounting/select-options/business_structure?enriched_labels=true');

        $businessStructureResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.enriched_labels', true)
            ->assertJsonPath('data.0.label', 'Pergamo SAS - Colombia - COP - Anual');

        $documentSourceResponse = $this->getJson('/api/v1/accounting/select-options/documents_source?enriched_labels=true');

        $documentSourceResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.value', $documentSourceId)
            ->assertJsonPath('data.0.label', 'FV - 000123 - Factura - 2026');

        $accountClassResponse = $this->getJson('/api/v1/accounting/select-options/account_class?enriched_labels=true');

        $accountClassResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.value', $accountClassId)
            ->assertJsonPath('data.0.label', 'Activo - D - Debito');
    }

    public function test_it_returns_accounting_accounts_using_real_table_columns(): void
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

        $response = $this->getJson('/api/v1/accounting/select-options/accounting_accounts?search=1105&limit=10');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.value', $accountingAccountId)
            ->assertJsonPath('data.0.label', '110505 - Caja General')
            ->assertJsonPath('data.0.meta.account', '110505')
            ->assertJsonPath('data.0.meta.name', 'Caja General');
    }

    public function test_it_returns_key_operations_in_select_options(): void
    {
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'Accounting',
            'code' => 'ACC',
            'description' => 'Modulo contable',
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

        $keyOperationId = DB::table('key_operations')->insertGetId([
            'code' => 'KO-001',
            'name' => 'Operacion de Compra',
            'module_id' => $moduleId,
            'accounting_nature_id' => $accountingNatureId,
            'affects_taxes' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/accounting/select-options/key_operations?search=KO-001&limit=10');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.value', $keyOperationId)
            ->assertJsonPath('data.0.label', 'KO-001 - Operacion de Compra')
            ->assertJsonPath('data.0.meta.module_id', $moduleId)
            ->assertJsonPath('data.0.meta.accounting_nature_id', $accountingNatureId)
            ->assertJsonPath('data.0.meta.affects_taxes', true);
    }

    public function test_it_returns_accounting_moments_in_select_options(): void
    {
        $accountingMomentId = DB::table('accounting_moments')->insertGetId([
            'name' => 'Causacion',
            'code' => 'CAU',
            'description' => 'Momento de causacion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/accounting/select-options/accounting_moments?search=CAU&limit=10');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.value', $accountingMomentId)
            ->assertJsonPath('data.0.label', 'CAU - Causacion')
            ->assertJsonPath('data.0.meta.code', 'CAU')
            ->assertJsonPath('data.0.meta.name', 'Causacion');
    }

    public function test_it_returns_accounting_events_in_select_options(): void
    {
        $accountingMomentId = DB::table('accounting_moments')->insertGetId([
            'name' => 'Causacion',
            'code' => 'CAU',
            'description' => 'Momento de causacion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingEventId = DB::table('accounting_events')->insertGetId([
            'code' => 'EV-001',
            'name' => 'Evento de Causacion',
            'accounting_moment_id' => $accountingMomentId,
            'origin' => 'manual',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/accounting/select-options/accounting_events?search=EV-001&limit=10');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.value', $accountingEventId)
            ->assertJsonPath('data.0.label', 'EV-001 - Evento de Causacion')
            ->assertJsonPath('data.0.meta.accounting_moment_id', $accountingMomentId)
            ->assertJsonPath('data.0.meta.origin', 'manual');
    }

    public function test_it_validates_catalog_against_allowed_configuration(): void
    {
        $response = $this->getJson('/api/v1/accounting/select-options/not-allowed');

        $response
            ->assertStatus(422)
            ->assertJsonPath('message', 'Validation failed.')
            ->assertJsonPath('errors.catalog.0', 'The selected catalog is invalid.');
    }

    private function createBusinessStructureDependencies(): int
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

        $businessGroupId = DB::table('business_group')->insertGetId([
            'code' => 1,
            'name' => 'Grupo 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $enterpriseId = DB::table('enterprises')->insertGetId([
            'name' => 'Pergamo SAS',
            'business_group_id' => $businessGroupId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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

        return DB::table('business_structure')->insertGetId([
            'country_id' => $countryId,
            'coin_id' => $coinId,
            'enterprise_id' => $enterpriseId,
            'exercise_variation_id' => $exerciseVariationId,
            'chart_account_id' => $chartAccountId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createDocumentSourceDependencies(): int
    {
        $businessStructureId = $this->createBusinessStructureDependencies();

        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'Facturacion',
            'code' => 'FAC',
            'description' => 'Modulo de facturacion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $documentSourceTypeId = DB::table('document_source_type')->insertGetId([
            'name' => 'Factura Venta',
            'code' => 'FV',
            'description' => 'Documento de venta',
            'generates_accounting' => true,
            'manual_entry' => true,
            'requires_approval' => false,
            'requires_third' => false,
            'requires_ceco' => false,
            'affects_inventory' => false,
            'affects_cartera' => true,
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

        $coinId = DB::table('coins')->value('id');

        return DB::table('documents_source')->insertGetId([
            'business_structure_id' => $businessStructureId,
            'modules_id' => $moduleId,
            'document_source_type_id' => $documentSourceTypeId,
            'number_document_source' => '000123',
            'document_date' => now(),
            'accounting_date' => now(),
            'reference_id' => $referenceId,
            'total_value' => 1500.25,
            'coin_id' => $coinId,
            'financial_statement_id' => $financialStatementId,
            'accounting_document_id' => $accountingDocumentId,
            'exercise' => '2026',
            'description' => 'Documento fuente de prueba',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
