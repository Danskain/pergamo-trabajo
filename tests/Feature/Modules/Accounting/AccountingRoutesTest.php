<?php

namespace Tests\Feature\Modules\Accounting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_accounting_health_endpoint_is_available(): void
    {
        $response = $this->getJson('/api/v1/accounting/health');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.module', 'accounting')
            ->assertJsonPath('data.status', 'ok');
    }
}
