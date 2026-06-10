<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeAccount extends Model
{
    use SoftDeletes;

    protected $table = 'types_accounts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];
}
