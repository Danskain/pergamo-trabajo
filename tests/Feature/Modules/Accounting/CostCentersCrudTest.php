<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CostCentersCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_cost_centers_with_pagination(): void
    {
        $dependencies = $this->createDependencies();

        foreach ([
            ['code' => 'CC-001', 'name' => 'Administracion'],
            ['code' => 'CC-002', 'name' => 'Operacion'],
        ] as $index => $payload) {
            $response = $this->postJson('/api/v1/accounting/cost-centers', [
                'business_structure_id' => $dependencies['business_structure_id'],
                'campus_id' => $dependencies['campus_id'],
                'code' => $payload['code'],
                'name' => $payload['name'],
                'description' => 'Centro de costo de prueba',
                'cost_center_type_id' => $dependencies['cost_center_type_id'],
                'cost_center_class_id' => $dependencies['cost_center_class_id'],
                'cost_center_nature_id' => $dependencies['cost_center_nature_id'],
                'allows_allocation' => $index === 0,
                'distributes_costs' => true,
                'functional_unit' => false,
                'profit_center' => true,
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/cost-centers?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Centros de costo obtenidos exitosamente')
            ->assertJsonPath('data.data.0.code', 'CC-001')
            ->assertJsonPath('data.data.0.name', 'Administracion')
            ->assertJsonPath('data.data.0.campus.name', 'Sede Norte')
            ->assertJsonPath('data.data.0.cost_center_type.code', 'ADM')
            ->assertJsonPath('data.data.0.cost_center_class.code', 'CLS')
            ->assertJsonPath('data.data.0.cost_center_nature.code', 'NAT')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_cost_center(): void
    {
        $dependencies = $this->createDependencies();
        $otherDependencies = $this->createDependencies('B');

        $id = DB::table('cost_center')->insertGetId([
            'business_structure_id' => $dependencies['business_structure_id'],
            'campus_id' => $dependencies['campus_id'],
            'code' => 'TMP',
            'name' => 'Temporal',
            'description' => 'Registro temporal',
            'cost_center_type_id' => $dependencies['cost_center_type_id'],
            'cost_center_class_id' => $dependencies['cost_center_class_id'],
            'cost_center_nature_id' => $dependencies['cost_center_nature_id'],
            'allows_allocation' => false,
            'distributes_costs' => false,
            'functional_unit' => false,
            'profit_center' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/cost-centers/{$id}", [
            'business_structure_id' => $otherDependencies['business_structure_id'],
            'campus_id' => $otherDependencies['campus_id'],
            'code' => 'UPD',
            'name' => 'Actualizado',
            'description' => 'Registro actualizado',
            'cost_center_type_id' => $otherDependencies['cost_center_type_id'],
            'cost_center_class_id' => $otherDependencies['cost_center_class_id'],
            'cost_center_nature_id' => $otherDependencies['cost_center_nature_id'],
            'allows_allocation' => true,
            'distributes_costs' => true,
            'functional_unit' => true,
            'profit_center' => true,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.code', 'UPD')
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.allows_allocation', true)
            ->assertJsonPath('data.campus.name', 'Sede Sur')
            ->assertJsonPath('data.cost_center_type.code', 'OPR');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/cost-centers/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('cost_center', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/cost-centers/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.code', 'UPD');

        $this->assertDatabaseHas('cost_center', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    /**
     * @return array<string, int>
     */
    private function createDependencies(string $suffix = 'A'): array
    {
        $countryId = DB::table('country')->insertGetId([
            'name' => 'Pais '.$suffix,
            'status_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $coinId = DB::table('coins')->insertGetId([
            'name' => 'Moneda '.$suffix,
            'alphabetic_code' => 'C'.$suffix.'P',
            'numeric_code' => $suffix === 'A' ? 170 : 171,
            'subunit' => 'Centavo',
            'subunit_ratio' => 100,
            'decimal_digits' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $businessGroupId = DB::table('business_group')->insertGetId([
            'code' => $suffix === 'A' ? 1 : 2,
            'name' => 'Grupo '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $enterpriseId = DB::table('enterprises')->insertGetId([
            'name' => 'Empresa '.$suffix,
            'business_group_id' => $businessGroupId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthStartId = DB::table('months')->insertGetId([
            'name' => 'Mes Inicio '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $monthEndId = DB::table('months')->insertGetId([
            'name' => 'Mes Fin '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exerciseVariationId = DB::table('exercise_variations')->insertGetId([
            'code' => 'EV-'.$suffix,
            'name' => 'Variacion '.$suffix,
            'start_exercise' => $monthStartId,
            'end_exercise' => $monthEndId,
            'normal_periods' => 12,
            'special_periods' => 0,
            'calendar_dependent' => true,
            'description' => 'Ejercicio '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $accountingStandardId = DB::table('accounting_standard')->insertGetId([
            'name' => 'Norma '.$suffix,
            'code' => 'STD-'.$suffix,
            'description' => 'Estandar '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $typePlanId = DB::table('types_plans')->insertGetId([
            'name' => 'Plan '.$suffix,
            'code' => 'PLN-'.$suffix,
            'description' => 'Tipo de plan '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chartAccountId = DB::table('chart_accounts')->insertGetId([
            'code' => 'CTA-'.$suffix,
            'name' => 'Cuenta '.$suffix,
            'description' => 'Cuenta contable '.$suffix,
            'accounting_standard_id' => $accountingStandardId,
            'types_plan_id' => $typePlanId,
            'ceco_permission' => false,
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

        $campusId = DB::table('campus')->insertGetId([
            'name' => $suffix === 'A' ? 'Sede Norte' : 'Sede Sur',
            'address' => 'Direccion '.$suffix,
            'enable_code' => 'SED-'.$suffix,
            'status_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $costCenterTypeId = DB::table('cost_center_type')->insertGetId([
            'name' => 'Tipo '.$suffix,
            'code' => $suffix === 'A' ? 'ADM' : 'OPR',
            'description' => 'Tipo de centro de costo '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $costCenterClassId = DB::table('cost_center_class')->insertGetId([
            'name' => 'Clase '.$suffix,
            'code' => $suffix === 'A' ? 'CLS' : 'CLB',
            'description' => 'Clase de centro de costo '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $costCenterNatureId = DB::table('cost_center_nature')->insertGetId([
            'name' => 'Naturaleza '.$suffix,
            'code' => $suffix === 'A' ? 'NAT' : 'NAB',
            'description' => 'Naturaleza de centro de costo '.$suffix,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'business_structure_id' => $businessStructureId,
            'campus_id' => $campusId,
            'cost_center_type_id' => $costCenterTypeId,
            'cost_center_class_id' => $costCenterClassId,
            'cost_center_nature_id' => $costCenterNatureId,
        ];
    }
}
