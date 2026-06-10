<?php

namespace App\Modules\Accounting\Models;

use App\Modules\Accounting\Models\AccountClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingNature extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_nature';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function accountClasses(): HasMany
    {
        return $this->hasMany(AccountClass::class, 'accounting_nature_id');
    }
}
