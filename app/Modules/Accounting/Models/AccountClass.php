<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountClass extends Model
{
    use SoftDeletes;

    protected $table = 'account_class';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'accounting_nature_id',
        'description',
    ];

    public function accountingNature(): BelongsTo
    {
        return $this->belongsTo(AccountingNature::class, 'accounting_nature_id');
    }

    public function accountingGroups(): HasMany
    {
        return $this->hasMany(AccountingGroup::class, 'account_class_id');
    }
}
