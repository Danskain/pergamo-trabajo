<?php

namespace App\Modules\Catalogs\Models;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'months';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];
}
