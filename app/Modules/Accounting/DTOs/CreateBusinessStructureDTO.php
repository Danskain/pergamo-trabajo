<?php

namespace App\Modules\Accounting\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateBusinessStructureDTO
{
    public function __construct(
        public readonly int $countryId,
        public readonly int $coinId,
        public readonly int $enterpriseId,
        public readonly int $exerciseVariationId,
        public readonly int $chartAccountId,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            countryId: (int) $request->integer('country_id'),
            coinId: (int) $request->integer('coin_id'),
            enterpriseId: (int) $request->integer('enterprise_id'),
            exerciseVariationId: (int) $request->integer('exercise_variation_id'),
            chartAccountId: (int) $request->integer('chart_account_id'),
        );
    }
}
