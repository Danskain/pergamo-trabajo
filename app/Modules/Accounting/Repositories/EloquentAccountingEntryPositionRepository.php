<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingEntryPositionDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEntryPositionDTO;
use App\Modules\Accounting\Models\AccountingEntryPosition;
use App\Modules\Accounting\Repositories\Contracts\AccountingEntryPositionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingEntryPositionRepository implements AccountingEntryPositionRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'businessStructure',
        'accountingDocument',
        'accountingEntryHeader',
        'accountingAccount',
        'costCenter',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingEntryPosition::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingEntryPosition
    {
        return AccountingEntryPosition::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingEntryPosition
    {
        return AccountingEntryPosition::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateAccountingEntryPositionDTO $dto): AccountingEntryPosition
    {
        $accountingEntryPosition = AccountingEntryPosition::query()->create([
            'business_structure_id' => $dto->businessStructureId,
            'accounting_document_id' => $dto->accountingDocumentId,
            'accounting_entry_header_id' => $dto->accountingEntryHeaderId,
            'accounting_accounts_id' => $dto->accountingAccountsId,
            'id_tercero' => $dto->idTercero,
            'indicator_dc' => $dto->indicatorDc,
            'amount' => $dto->amount,
            'coin_id' => $dto->coinId,
            'cost_center_id' => $dto->costCenterId,
            'position_text' => $dto->positionText,
        ]);

        return $accountingEntryPosition->load($this->with);
    }

    public function update(AccountingEntryPosition $accountingEntryPosition, UpdateAccountingEntryPositionDTO $dto): AccountingEntryPosition
    {
        $accountingEntryPosition->update([
            'business_structure_id' => $dto->businessStructureId,
            'accounting_document_id' => $dto->accountingDocumentId,
            'accounting_entry_header_id' => $dto->accountingEntryHeaderId,
            'accounting_accounts_id' => $dto->accountingAccountsId,
            'id_tercero' => $dto->idTercero,
            'indicator_dc' => $dto->indicatorDc,
            'amount' => $dto->amount,
            'coin_id' => $dto->coinId,
            'cost_center_id' => $dto->costCenterId,
            'position_text' => $dto->positionText,
        ]);

        return $accountingEntryPosition->load($this->with);
    }

    public function delete(AccountingEntryPosition $accountingEntryPosition): void
    {
        $accountingEntryPosition->delete();
    }

    public function restore(AccountingEntryPosition $accountingEntryPosition): AccountingEntryPosition
    {
        $accountingEntryPosition->restore();

        return $accountingEntryPosition->load($this->with);
    }
}
