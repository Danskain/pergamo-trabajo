<?php

namespace App\Modules\Accounting\DTOs;

use App\Modules\Accounting\Http\Requests\StoreExerciseVariationRequest;

class CreateExerciseVariationDTO
{
    public function __construct(
        public readonly ?string $code,
        public readonly ?string $name,
        public readonly int $startExercise,
        public readonly int $endExercise,
        public readonly int $normalPeriods,
        public readonly int $specialPeriods,
        public readonly bool $calendarDependent,
        public readonly ?string $description,
    ) {
    }

    public static function fromRequest(StoreExerciseVariationRequest $request): self
    {
        return new self(
            code: $request->filled('code') ? $request->string('code')->toString() : null,
            name: $request->filled('name') ? $request->string('name')->toString() : null,
            startExercise: $request->integer('start_exercise'),
            endExercise: $request->integer('end_exercise'),
            normalPeriods: $request->integer('normal_periods'),
            specialPeriods: $request->integer('special_periods'),
            calendarDependent: $request->boolean('calendar_dependent'),
            description: $request->filled('description') ? $request->string('description')->toString() : null,
        );
    }
}
