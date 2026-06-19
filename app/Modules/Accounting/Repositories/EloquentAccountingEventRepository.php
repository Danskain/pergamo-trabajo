<?php

namespace App\Modules\Accounting\Repositories;

use App\Modules\Accounting\DTOs\CreateAccountingEventDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEventDTO;
use App\Modules\Accounting\Models\AccountingEvent;
use App\Modules\Accounting\Repositories\Contracts\AccountingEventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentAccountingEventRepository implements AccountingEventRepositoryInterface
{
    /**
     * @var array<int, string>
     */
    protected array $with = [
        'accountingMoment',
    ];

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return AccountingEvent::query()
            ->with($this->with)
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findOrFail(int $id): AccountingEvent
    {
        return AccountingEvent::query()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function findTrashedOrFail(int $id): AccountingEvent
    {
        return AccountingEvent::query()
            ->withTrashed()
            ->with($this->with)
            ->findOrFail($id);
    }

    public function create(CreateAccountingEventDTO $dto): AccountingEvent
    {
        $accountingEvent = AccountingEvent::query()->create([
            'code' => $dto->code,
            'name' => $dto->name,
            'accounting_moment_id' => $dto->accountingMomentId,
            'origin' => $dto->origin,
        ]);

        return $accountingEvent->load($this->with);
    }

    public function update(AccountingEvent $accountingEvent, UpdateAccountingEventDTO $dto): AccountingEvent
    {
        $accountingEvent->update([
            'code' => $dto->code,
            'name' => $dto->name,
            'accounting_moment_id' => $dto->accountingMomentId,
            'origin' => $dto->origin,
        ]);

        return $accountingEvent->load($this->with);
    }

    public function delete(AccountingEvent $accountingEvent): void
    {
        $accountingEvent->delete();
    }

    public function restore(AccountingEvent $accountingEvent): AccountingEvent
    {
        $accountingEvent->restore();

        return $accountingEvent->load($this->with);
    }
}
