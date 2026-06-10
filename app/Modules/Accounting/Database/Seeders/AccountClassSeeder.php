<?php

namespace App\Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountClassSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $item) {
            DB::table('account_class')->updateOrInsert(
                ['id' => $item['id']],
                [
                    'name' => $item['name'],
                    'accounting_nature_id' => $item['accounting_nature_id'],
                    'description' => $item['description'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
        }
    }

    /**
     * @return array<int, array{id:int,name:string,accounting_nature_id:int,description:string}>
     */
    protected function items(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Activo',
                'accounting_nature_id' => 1,
                'description' => 'Clase contable que agrupa los bienes, derechos y recursos controlados por la entidad.',
            ],
            [
                'id' => 2,
                'name' => 'Pasivo',
                'accounting_nature_id' => 2,
                'description' => 'Clase contable que representa las obligaciones y deudas a cargo de la entidad.',
            ],
            [
                'id' => 3,
                'name' => 'Patrimonio',
                'accounting_nature_id' => 2,
                'description' => 'Clase contable que refleja el valor residual de los activos una vez descontados los pasivos.',
            ],
            [
                'id' => 4,
                'name' => 'Ingreso',
                'accounting_nature_id' => 2,
                'description' => 'Clase contable que registra los incrementos economicos generados por la operacion del negocio.',
            ],
            [
                'id' => 5,
                'name' => 'Gasto',
                'accounting_nature_id' => 1,
                'description' => 'Clase contable que registra los consumos y egresos necesarios para la operacion de la entidad.',
            ],
            [
                'id' => 6,
                'name' => 'Costo',
                'accounting_nature_id' => 1,
                'description' => 'Clase contable que acumula los valores directamente asociados a la produccion o prestacion de servicios.',
            ],
        ];
    }
}
