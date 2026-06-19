<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeyOperation extends Model
{
    use SoftDeletes;

    protected $table = 'key_operations';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'module_id',
        'accounting_nature_id',
        'affects_taxes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'affects_taxes' => 'boolean',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function accountingNature(): BelongsTo
    {
        return $this->belongsTo(AccountingNature::class, 'accounting_nature_id');
    }
}
