<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessStructure extends Model
{
    use SoftDeletes;

    protected $table = 'business_structure';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'coin_id',
        'enterprise_id',
        'exercise_variation_id',
        'chart_account_id',
    ];

    public function exerciseVariation(): BelongsTo
    {
        return $this->belongsTo(ExerciseVariation::class, 'exercise_variation_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'coin_id');
    }

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }

    public function chartAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'chart_account_id');
    }
}
