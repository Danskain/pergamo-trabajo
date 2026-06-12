<?php

namespace App\Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccountingNatureSeeder::class,
            AccountClassSeeder::class,
            FinancialStatementSeeder::class,
            ReferenceSeeder::class,
        ]);
    }
}
