<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingAccount extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_accounts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'account',
        'chart_account_id',
        'name',
        'account_class_id',
        'types_account_id',
        'accounting_group_id',
        'allows_manual_transactions',
        'associated_account',
        'accepts_taxes',
        'foreign_currency',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'allows_manual_transactions' => 'boolean',
        'associated_account' => 'boolean',
        'accepts_taxes' => 'boolean',
        'foreign_currency' => 'boolean',
    ];

    public function chartAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'chart_account_id');
    }

    public function accountClass(): BelongsTo
    {
        return $this->belongsTo(AccountClass::class, 'account_class_id');
    }

    public function typeAccount(): BelongsTo
    {
        return $this->belongsTo(TypeAccount::class, 'types_account_id');
    }

    public function accountingGroup(): BelongsTo
    {
        return $this->belongsTo(AccountingGroup::class, 'accounting_group_id');
    }
}
