<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingEvent extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_events';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'accounting_moment_id',
        'origin',
    ];

    public function accountingMoment(): BelongsTo
    {
        return $this->belongsTo(AccountingMoment::class, 'accounting_moment_id');
    }
}
