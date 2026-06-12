<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostCenter extends Model
{
    use SoftDeletes;

    protected $table = 'cost_center';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'business_structure_id',
        'campus_id',
        'code',
        'name',
        'description',
        'cost_center_type_id',
        'cost_center_class_id',
        'cost_center_nature_id',
        'allows_allocation',
        'distributes_costs',
        'functional_unit',
        'profit_center',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'allows_allocation' => 'boolean',
        'distributes_costs' => 'boolean',
        'functional_unit' => 'boolean',
        'profit_center' => 'boolean',
    ];

    public function businessStructure(): BelongsTo
    {
        return $this->belongsTo(BusinessStructure::class, 'business_structure_id');
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function costCenterType(): BelongsTo
    {
        return $this->belongsTo(CostCenterType::class, 'cost_center_type_id');
    }

    public function costCenterClass(): BelongsTo
    {
        return $this->belongsTo(CostCenterClass::class, 'cost_center_class_id');
    }

    public function costCenterNature(): BelongsTo
    {
        return $this->belongsTo(CostCenterNature::class, 'cost_center_nature_id');
    }
}
