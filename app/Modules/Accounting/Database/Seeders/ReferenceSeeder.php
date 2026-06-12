<?php

namespace App\Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $item) {
            DB::table('reference')->updateOrInsert(
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
                'code' => 'FAC',
                'name' => 'Factura',
                'description' => 'Referencia utilizada para identificar documentos tipo factura.',
            ],
            [
                'code' => 'ORD',
                'name' => 'Orden',
                'description' => 'Referencia utilizada para identificar documentos tipo orden.',
            ],
            [
                'code' => 'ACT',
                'name' => 'Acta',
                'description' => 'Referencia utilizada para identificar documentos tipo acta.',
            ],
            [
                'code' => 'RAD',
                'name' => 'Radicado',
                'description' => 'Referencia utilizada para identificar documentos tipo radicado.',
            ],
            [
                'code' => 'SEX',
                'name' => 'Soporte externo',
                'description' => 'Referencia utilizada para identificar documentos o soportes generados fuera del sistema.',
            ],
        ];
    }
}
