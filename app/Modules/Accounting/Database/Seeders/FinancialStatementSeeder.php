<?php

namespace App\Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialStatementSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $item) {
            DB::table('financial_statements')->updateOrInsert(
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
     * @return array<int, array{code:string,name:string,description:string}>
     */
    protected function items(): array
    {
        return [
            [
                'code' => 'P',
                'name' => 'Pendiente',
                'description' => 'Estado utilizado cuando el registro aun no ha sido contabilizado ni finalizado.',
            ],
            [
                'code' => 'C',
                'name' => 'Contabilizado',
                'description' => 'Estado utilizado cuando el registro ya fue procesado y contabilizado en el sistema.',
            ],
            [
                'code' => 'R',
                'name' => 'Reversado',
                'description' => 'Estado utilizado cuando un registro contabilizado fue revertido mediante el proceso correspondiente.',
            ],
            [
                'code' => 'A',
                'name' => 'Anulado',
                'description' => 'Estado utilizado cuando el registro pierde validez y queda anulado dentro del sistema.',
            ],
        ];
    }
}
