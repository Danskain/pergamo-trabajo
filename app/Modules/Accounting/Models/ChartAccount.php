<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartAccount extends Model
{
    use SoftDeletes;

    protected $table = 'chart_accounts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'accounting_standard_id',
        'types_plan_id',
        'ceco_permission',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'ceco_permission' => 'boolean',
    ];

    public function accountingStandard(): BelongsTo
    {
        return $this->belongsTo(AccountingStandard::class, 'accounting_standard_id');
    }

    public function typePlan(): BelongsTo
    {
        return $this->belongsTo(TypePlan::class, 'types_plan_id');
    }
}
