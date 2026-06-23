<?php

namespace App\Modules\Accounting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountingSchemeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'business_structure_id' => $this->business_structure_id,
            'chart_account_id' => $this->chart_account_id,
            'assessment_class' => $this->assessment_class,
            'type_movement_id' => $this->type_movement_id,
            'accounting_event_id' => $this->accounting_event_id,
            'key_operation_id' => $this->key_operation_id,
            'accounting_account_id' => $this->accounting_account_id,
            'accounting_nature_id' => $this->accounting_nature_id,
            'require_coce' => $this->require_coce,
            'business_structure' => $this->whenLoaded('businessStructure', function (): array {
                return [
                    'id' => $this->businessStructure->id,
                    'country_id' => $this->businessStructure->country_id,
                    'coin_id' => $this->businessStructure->coin_id,
                    'enterprise_id' => $this->businessStructure->enterprise_id,
                    'country' => $this->businessStructure->relationLoaded('country') && $this->businessStructure->country !== null
                        ? [
                            'id' => $this->businessStructure->country->id,
                            'name' => $this->businessStructure->country->name,
                        ]
                        : null,
                    'coin' => $this->businessStructure->relationLoaded('coin') && $this->businessStructure->coin !== null
                        ? [
                            'id' => $this->businessStructure->coin->id,
                            'name' => $this->businessStructure->coin->name,
                            'alphabetic_code' => $this->businessStructure->coin->alphabetic_code,
                        ]
                        : null,
                    'enterprise' => $this->businessStructure->relationLoaded('enterprise') && $this->businessStructure->enterprise !== null
                        ? [
                            'id' => $this->businessStructure->enterprise->id,
                            'name' => $this->businessStructure->enterprise->name,
                        ]
                        : null,
                ];
            }),
            'chart_account' => $this->whenLoaded('chartAccount', function (): array {
                return [
                    'id' => $this->chartAccount->id,
                    'name' => $this->chartAccount->name,
                    'code' => $this->chartAccount->code,
                    'description' => $this->chartAccount->description,
                ];
            }),
            'type_movement' => $this->whenLoaded('typeMovement', function (): array {
                return [
                    'id' => $this->typeMovement->id,
                    'control_book' => $this->typeMovement->control_book,
                    'date' => $this->typeMovement->date?->toDateString(),
                    'quantity' => $this->typeMovement->quantity,
                    'record_number' => $this->typeMovement->record_number,
                ];
            }),
            'accounting_event' => $this->whenLoaded('accountingEvent', function (): array {
                return [
                    'id' => $this->accountingEvent->id,
                    'code' => $this->accountingEvent->code,
                    'name' => $this->accountingEvent->name,
                    'origin' => $this->accountingEvent->origin,
                    'accounting_moment' => $this->accountingEvent->relationLoaded('accountingMoment') && $this->accountingEvent->accountingMoment !== null
                        ? [
                            'id' => $this->accountingEvent->accountingMoment->id,
                            'code' => $this->accountingEvent->accountingMoment->code,
                            'name' => $this->accountingEvent->accountingMoment->name,
                        ]
                        : null,
                ];
            }),
            'key_operation' => $this->whenLoaded('keyOperation', function (): array {
                return [
                    'id' => $this->keyOperation->id,
                    'code' => $this->keyOperation->code,
                    'name' => $this->keyOperation->name,
                    'affects_taxes' => $this->keyOperation->affects_taxes,
                    'module' => $this->keyOperation->relationLoaded('module') && $this->keyOperation->module !== null
                        ? [
                            'id' => $this->keyOperation->module->id,
                            'code' => $this->keyOperation->module->code,
                            'name' => $this->keyOperation->module->name,
                        ]
                        : null,
                    'accounting_nature' => $this->keyOperation->relationLoaded('accountingNature') && $this->keyOperation->accountingNature !== null
                        ? [
                            'id' => $this->keyOperation->accountingNature->id,
                            'code' => $this->keyOperation->accountingNature->code,
                            'name' => $this->keyOperation->accountingNature->name,
                        ]
                        : null,
                ];
            }),
            'accounting_account' => $this->whenLoaded('accountingAccount', function (): array {
                return [
                    'id' => $this->accountingAccount->id,
                    'account' => $this->accountingAccount->account,
                    'name' => $this->accountingAccount->name,
                ];
            }),
            'accounting_nature' => $this->whenLoaded('accountingNature', function (): array {
                return [
                    'id' => $this->accountingNature->id,
                    'code' => $this->accountingNature->code,
                    'name' => $this->accountingNature->name,
                    'description' => $this->accountingNature->description,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'deleted_at' => $this->deleted_at?->toISOString(),
        ];
    }
}
