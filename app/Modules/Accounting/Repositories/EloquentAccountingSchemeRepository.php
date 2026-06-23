<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingSchemeDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingSchemeDTO;
use App\Modules\Accounting\Models\AccountingScheme;
use App\Modules\Accounting\Repositories\Contracts\AccountingSchemeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingSchemeRepository implements AccountingSchemeRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'businessStructure',
        'businessStructure.country',
        'businessStructure.coin',
        'businessStructure.enterprise',
        'chartAccount',
        'typeMovement',
        'accountingEvent',
        'accountingEvent.accountingMoment',
        'keyOperation',
        'keyOperation.module',
        'keyOperation.accountingNature',
        'accountingAccount',
        'accountingNature',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingScheme::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingScheme
    {
        return AccountingScheme::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingScheme
    {
        return AccountingScheme::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateAccountingSchemeDTO $dto): AccountingScheme
    {
        $accountingScheme = AccountingScheme::query()->create([
            'business_structure_id' => $dto->businessStructureId,
            'chart_account_id' => $dto->chartAccountId,
            'assessment_class' => $dto->assessmentClass,
            'type_movement_id' => $dto->typeMovementId,
            'accounting_event_id' => $dto->accountingEventId,
            'key_operation_id' => $dto->keyOperationId,
            'accounting_account_id' => $dto->accountingAccountId,
            'accounting_nature_id' => $dto->accountingNatureId,
            'require_coce' => $dto->requireCoce,
        ]);

        return $accountingScheme->load($this->with);
    }

    public function update(AccountingScheme $accountingScheme, UpdateAccountingSchemeDTO $dto): AccountingScheme
    {
        $accountingScheme->update([
            'business_structure_id' => $dto->businessStructureId,
            'chart_account_id' => $dto->chartAccountId,
            'assessment_class' => $dto->assessmentClass,
            'type_movement_id' => $dto->typeMovementId,
            'accounting_event_id' => $dto->accountingEventId,
            'key_operation_id' => $dto->keyOperationId,
            'accounting_account_id' => $dto->accountingAccountId,
            'accounting_nature_id' => $dto->accountingNatureId,
            'require_coce' => $dto->requireCoce,
        ]);

        return $accountingScheme->load($this->with);
    }

    public function delete(AccountingScheme $accountingScheme): void
    {
        $accountingScheme->delete();
    }

    public function restore(AccountingScheme $accountingScheme): AccountingScheme
    {
        $accountingScheme->restore();

        return $accountingScheme->load($this->with);
    }
}
