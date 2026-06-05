<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingStandard extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_standard';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];
}
