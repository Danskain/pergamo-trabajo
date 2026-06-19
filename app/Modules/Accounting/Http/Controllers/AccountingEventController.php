<?php

namespace App\Modules\Accounting\Http\Controllers;

use App\Modules\Accounting\DTOs\CreateAccountingEventDTO;
use App\Modules\Accounting\DTOs\UpdateAccountingEventDTO;
use App\Modules\Accounting\Http\Requests\StoreAccountingEventRequest;
use App\Modules\Accounting\Http\Requests\UpdateAccountingEventRequest;
use App\Modules\Accounting\Http\Resources\AccountingEventResource;
use App\Modules\Accounting\Models\AccountingEvent;
use App\Modules\Accounting\Services\AccountingEventService;
use App\Shared\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class AccountingEventController extends ApiController
{
    public function __construct(
        protected AccountingEventService $service,
    ) {
    }

    public function index(): JsonResponse
    {
        $page = max(1, (int) request()->integer('page', 1));
        $perPage = max(1, (int) request()->integer('per_page', 15));
        $paginator = $this->service->paginate($perPage, $page);
        $payload = AccountingEventResource::collection($paginator)
            ->response()
            ->getData(true);

        unset($payload['meta']['links']);

        return response()->json([
            'status' => true,
            'message' => 'Eventos contables obtenidos exitosamente',
            'data' => $payload,
        ]);
    }

    public function store(StoreAccountingEventRequest $request): JsonResponse
    {
        $item = $this->service->create(CreateAccountingEventDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingEventResource($item))->resolve(),
            'Accounting event created successfully.',
            201,
        );
    }

    public function show(int $accountingEvent): JsonResponse
    {
        $item = $this->service->findOrFail($accountingEvent);

        return $this->successResponse(
            (new AccountingEventResource($item))->resolve(),
            'Accounting event retrieved successfully.',
        );
    }

    public function update(UpdateAccountingEventRequest $request, AccountingEvent $accountingEvent): JsonResponse
    {
        $item = $this->service->update($accountingEvent, UpdateAccountingEventDTO::fromRequest($request));

        return $this->successResponse(
            (new AccountingEventResource($item))->resolve(),
            'Accounting event updated successfully.',
        );
    }

    public function destroy(AccountingEvent $accountingEvent): JsonResponse
    {
        $this->service->delete($accountingEvent);

        return $this->successResponse(null, 'Accounting event soft deleted successfully.');
    }

    public function restore(int $accountingEvent): JsonResponse
    {
        $item = $this->service->restore($this->service->findTrashedOrFail($accountingEvent));

        return $this->successResponse(
            (new AccountingEventResource($item))->resolve(),
            'Accounting event restored successfully.',
        );
    }
}
