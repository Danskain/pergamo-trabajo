<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingEntryHeader extends Model
{
    use SoftDeletes;

    protected $table = 'accounting_entry_header';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'business_structure_id',
        'accounting_document_id',
        'accounting_period',
        'coin_id',
        'description',
        'total_debits',
        'total_credits',
        'reference_document',
        'documents_source_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'total_debits' => 'decimal:2',
        'total_credits' => 'decimal:2',
    ];

    public function businessStructure(): BelongsTo
    {
        return $this->belongsTo(BusinessStructure::class, 'business_structure_id');
    }

    public function accountingDocument(): BelongsTo
    {
        return $this->belongsTo(AccountingDocument::class, 'accounting_document_id');
    }

    public function documentSource(): BelongsTo
    {
        return $this->belongsTo(DocumentSource::class, 'documents_source_id');
    }
}
