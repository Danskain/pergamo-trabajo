<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingEntryPosition extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_entry_position';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'business_structure_id',
        'accounting_document_id',
        'accounting_entry_header_id',
        'accounting_accounts_id',
        'id_tercero',
        'indicator_dc',
        'amount',
        'coin_id',
        'cost_center_id',
        'position_text',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function businessStructure(): BelongsTo
    {
        return $this->belongsTo(BusinessStructure::class, 'business_structure_id');
    }

    public function accountingDocument(): BelongsTo
    {
        return $this->belongsTo(AccountingDocument::class, 'accounting_document_id');
    }

    public function accountingEntryHeader(): BelongsTo
    {
        return $this->belongsTo(AccountingEntryHeader::class, 'accounting_entry_header_id');
    }

    public function accountingAccount(): BelongsTo
    {
        return $this->belongsTo(AccountingAccount::class, 'accounting_accounts_id');
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }
}
