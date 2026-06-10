<?php

namespace App\Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountingNatureSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $item) {
            DB::table('accounting_nature')->updateOrInsert(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    protected function items(): array
    {
        return [
            [
                'code' => 'D',
                'name' => 'Debito',
                'description' => 'Naturaleza contable para movimientos de debito.',
            ],
            [
                'code' => 'C',
                'name' => 'Credito',
                'description' => 'Naturaleza contable para movimientos de credito.',
            ],
        ];
    }
}
