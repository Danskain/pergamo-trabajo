<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentSourceType extends Model
{
    use SoftDeletes;

    protected $table = 'document_source_type';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'generates_accounting',
        'manual_entry',
        'requires_approval',
        'requires_third',
        'requires_ceco',
        'affects_inventory',
        'affects_cartera',
        'affects_cxp',
        'affects_treasury',
        'allows_reversal',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'generates_accounting' => 'boolean',
        'manual_entry' => 'boolean',
        'requires_approval' => 'boolean',
        'requires_third' => 'boolean',
        'requires_ceco' => 'boolean',
        'affects_inventory' => 'boolean',
        'affects_cartera' => 'boolean',
        'affects_cxp' => 'boolean',
        'affects_treasury' => 'boolean',
        'allows_reversal' => 'boolean',
    ];
}
