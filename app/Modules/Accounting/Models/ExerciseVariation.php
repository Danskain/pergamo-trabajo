<?php

namespace App\Modules\Accounting\Models;

use App\Modules\Catalogs\Models\Month;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseVariation extends Model
{
    use SoftDeletes;

    protected $table = 'exercise_variations';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_exercise',
        'end_exercise',
        'normal_periods',
        'special_periods',
        'calendar_dependent',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'calendar_dependent' => 'boolean',
    ];

    public function startMonth(): BelongsTo
    {
        return $this->belongsTo(Month::class, 'start_exercise');
    }

    public function endMonth(): BelongsTo
    {
        return $this->belongsTo(Month::class, 'end_exercise');
    }
}
