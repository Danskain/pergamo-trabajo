<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DocumentSourceTypesCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_document_source_types_with_pagination(): void
    {
        foreach ([
            [
                'name' => 'Manual',
                'code' => 'MAN',
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
            ],
            [
                'name' => 'Automatico',
                'code' => 'AUT',
                'generates_accounting' => true,
                'manual_entry' => false,
                'requires_approval' => true,
                'requires_third' => true,
                'requires_ceco' => true,
                'affects_inventory' => true,
                'affects_cartera' => false,
                'affects_cxp' => true,
                'affects_treasury' => false,
                'allows_reversal' => false,
            ],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/document-source-types', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Tipo de origen del documento',
                'generates_accounting' => $payload['generates_accounting'],
                'manual_entry' => $payload['manual_entry'],
                'requires_approval' => $payload['requires_approval'],
                'requires_third' => $payload['requires_third'],
                'requires_ceco' => $payload['requires_ceco'],
                'affects_inventory' => $payload['affects_inventory'],
                'affects_cartera' => $payload['affects_cartera'],
                'affects_cxp' => $payload['affects_cxp'],
                'affects_treasury' => $payload['affects_treasury'],
                'allows_reversal' => $payload['allows_reversal'],
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/document-source-types?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Tipos de origen de documento obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'Manual')
            ->assertJsonPath('data.data.0.code', 'MAN')
            ->assertJsonPath('data.data.0.generates_accounting', true)
            ->assertJsonPath('data.data.0.manual_entry', true)
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_a_document_source_type(): void
    {
        $id = DB::table('document_source_type')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'generates_accounting' => false,
            'manual_entry' => false,
            'requires_approval' => false,
            'requires_third' => false,
            'requires_ceco' => false,
            'affects_inventory' => false,
            'affects_cartera' => false,
            'affects_cxp' => false,
            'affects_treasury' => false,
            'allows_reversal' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/document-source-types/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
            'generates_accounting' => true,
            'manual_entry' => true,
            'requires_approval' => true,
            'requires_third' => true,
            'requires_ceco' => true,
            'affects_inventory' => true,
            'affects_cartera' => true,
            'affects_cxp' => true,
            'affects_treasury' => true,
            'allows_reversal' => true,
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD')
            ->assertJsonPath('data.generates_accounting', true)
            ->assertJsonPath('data.affects_inventory', true);

        $deleteResponse = $this->deleteJson("/api/v1/accounting/document-source-types/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('document_source_type', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/document-source-types/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('document_source_type', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
