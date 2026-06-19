<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountingEventsCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_lists_accounting_events_with_pagination(): void
    {
        $accountingMomentId = $this->createAccountingMoment('CAU', 'Causacion');

        $responseA = $this->postJson('/api/v1/accounting/accounting-events', [
            'code' => 'EV-001',
            'name' => 'Evento de Causacion',
            'accounting_moment_id' => $accountingMomentId,
            'origin' => 'manual',
        ]);

        $responseA
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.code', 'EV-001')
            ->assertJsonPath('data.accounting_moment.code', 'CAU')
            ->assertJsonPath('data.origin', 'manual');

        $responseB = $this->postJson('/api/v1/accounting/accounting-events', [
            'code' => 'EV-002',
            'name' => 'Evento Automatico',
            'accounting_moment_id' => $accountingMomentId,
            'origin' => 'automatico',
        ]);

        $responseB
            ->assertCreated()
            ->assertJsonPath('success', true);

        $listResponse = $this->getJson('/api/v1/accounting/accounting-events?page=1&per_page=1');

        $listResponse
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Eventos contables obtenidos exitosamente')
            ->assertJsonPath('data.data.0.code', 'EV-001')
            ->assertJsonPath('data.data.0.name', 'Evento de Causacion')
            ->assertJsonPath('data.data.0.accounting_moment.name', 'Causacion')
            ->assertJsonPath('data.data.0.origin', 'manual')
            ->assertJsonPath('data.meta.current_page', 1)
            ->assertJsonPath('data.meta.per_page', 1)
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.meta.last_page', 2);
    }

    public function test_it_updates_soft_deletes_and_restores_an_accounting_event(): void
    {
        $accountingMomentId = $this->createAccountingMoment('CAU', 'Causacion');
        $updatedAccountingMomentId = $this->createAccountingMoment('PAG', 'Pago');

        $id = DB::table('accounting_events')->insertGetId([
            'code' => 'EV-TMP',
            'name' => 'Evento Temporal',
            'accounting_moment_id' => $accountingMomentId,
            'origin' => 'manual',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $updateResponse = $this->putJson("/api/v1/accounting/accounting-events/{$id}", [
            'code' => 'EV-UPD',
            'name' => 'Evento Actualizado',
            'accounting_moment_id' => $updatedAccountingMomentId,
            'origin' => 'automatico',
        ]);

        $updateResponse
            ->assertOk()
            ->assertJsonPath('data.code', 'EV-UPD')
            ->assertJsonPath('data.name', 'Evento Actualizado')
            ->assertJsonPath('data.accounting_moment.code', 'PAG')
            ->assertJsonPath('data.origin', 'automatico');

        $deleteResponse = $this->deleteJson("/api/v1/accounting/accounting-events/{$id}");

        $deleteResponse
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('accounting_events', ['id' => $id]);

        $restoreResponse = $this->postJson("/api/v1/accounting/accounting-events/{$id}/restore");

        $restoreResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.code', 'EV-UPD')
            ->assertJsonPath('data.accounting_moment.code', 'PAG')
            ->assertJsonPath('data.origin', 'automatico');

        $this->assertDatabaseHas('accounting_events', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    private function createAccountingMoment(string $code, string $name): int
    {
        return DB::table('accounting_moments')->insertGetId([
            'name' => $name,
            'code' => $code,
            'description' => 'Momento '.$name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
