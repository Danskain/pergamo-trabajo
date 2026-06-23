<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingScheme extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_schemes';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'business_structure_id',
        'chart_account_id',
        'assessment_class',
        'type_movement_id',
        'accounting_event_id',
        'key_operation_id',
        'accounting_account_id',
        'accounting_nature_id',
        'require_coce',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'require_coce' => 'boolean',
    ];

    public function businessStructure(): BelongsTo
    {
        return $this->belongsTo(BusinessStructure::class, 'business_structure_id');
    }

    public function chartAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'chart_account_id');
    }

    public function typeMovement(): BelongsTo
    {
        return $this->belongsTo(ProductInventoryMovement::class, 'type_movement_id');
    }

    public function accountingEvent(): BelongsTo
    {
        return $this->belongsTo(AccountingEvent::class, 'accounting_event_id');
    }

    public function keyOperation(): BelongsTo
    {
        return $this->belongsTo(KeyOperation::class, 'key_operation_id');
    }

    public function accountingAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'accounting_account_id');
    }

    public function accountingNature(): BelongsTo
    {
        return $this->belongsTo(AccountingNature::class, 'accounting_nature_id');
    }
}
