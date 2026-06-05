<?php

namespace App\Modules\Catalogs\Services;

use App\Modules\Catalogs\Models\Month;
use Illuminate\Database\Eloquent\Collection;

class MonthService
{
    public function all(): Collection
    {
        return Month::query()
            ->orderBy('id')
            ->get();
    }
}
