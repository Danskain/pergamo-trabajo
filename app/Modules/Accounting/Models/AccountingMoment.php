<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingMoment extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_moments';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];
}
