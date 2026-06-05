<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->months() as $month) {
            DB::table('months')->updateOrInsert(
                ['name' => $month],
                ['updated_at' => now(), 'created_at' => now()],
            );
        }
    }

    /**
     * @return array<int, string>
     */
    protected function months(): array
    {
        return [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
        ];
    }
}
