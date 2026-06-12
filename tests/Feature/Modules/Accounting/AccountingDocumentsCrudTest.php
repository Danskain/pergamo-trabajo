<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingDocumentsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_documents_with_pagination(): void
    {
        foreach ([
            ['name' => 'Comprobante ingreso', 'code' => 'CI'],
            ['name' => 'Comprobante egreso', 'code' => 'CE'],
        ] as $payload) {
            $response = $this->postJson('/api/v1/accounting/accounting-documents', [
                'name' => $payload['name'],
                'code' => $payload['code'],
                'description' => 'Documento contable del sistema',
            ]);

            $response
                ->assertCreated()
                ->assertJsonPath('success', true);
        }

        $listResponse = $this->getJson('/api/v1/accounting/accounting-documents?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Documentos contables obtenidos exitosamente')
            ->assertJsonPath('data.data.0.name', 'Comprobante ingreso')
            ->assertJsonPath('data.data.0.code', 'CI')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_document(): void
    {
        $id = DB::table('accounting_document')->insertGetId([
            'name' => 'Temporal',
            'code' => 'TMP',
            'description' => 'Registro temporal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-documents/{$id}", [
            'name' => 'Actualizado',
            'code' => 'UPD',
            'description' => 'Registro actualizado',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.name', 'Actualizado')
            ->assertJsonPath('data.code', 'UPD');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-documents/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_document', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-documents/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.name', 'Actualizado');

        $this->assertDatabaseHas('accounting_document', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }
}
