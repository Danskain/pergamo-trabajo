<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentSource extends Model
{
    use SoftDeletes;

    protected $table = 'documents_source';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'business_structure_id',
        'modules_id',
        'document_source_type_id',
        'number_document_source',
        'document_date',
        'accounting_date',
        'reference_id',
        'total_value',
        'coin_id',
        'financial_statement_id',
        'accounting_document_id',
        'exercise',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'document_date' => 'datetime',
        'accounting_date' => 'datetime',
        'total_value' => 'decimal:2',
    ];

    public function businessStructure(): BelongsTo
    {
        return $this->belongsTo(BusinessStructure::class, 'business_structure_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'modules_id');
    }

    public function documentSourceType(): BelongsTo
    {
        return $this->belongsTo(DocumentSourceType::class, 'document_source_type_id');
    }

    public function reference(): BelongsTo
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }

    public function financialStatement(): BelongsTo
    {
        return $this->belongsTo(FinancialStatement::class, 'financial_statement_id');
    }

    public function accountingDocument(): BelongsTo
    {
        return $this->belongsTo(AccountingDocument::class, 'accounting_document_id');
    }
}
