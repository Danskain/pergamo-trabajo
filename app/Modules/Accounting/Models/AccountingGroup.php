<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingGroup extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_groups';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'account_class_id',
        'name',
        'description',
        'account_from',
        'account_to',
        'affects_closing',
        'affects_financial_statements',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'affects_closing' => 'boolean',
        'affects_financial_statements' => 'boolean',
    ];

    public function accountClass(): BelongsTo
    {
        return $this->belongsTo(AccountClass::class, 'account_class_id');
    }
}
