<?php

namespace App\Modules\Accounting\DTOs;

use App\Modules\Accounting\Http\Requests\UpdateAccountingEntryPositionRequest;

class UpdateAccountingEntryPositionDTO
{
    public function __construct(
        public readonly int $businessStructureId,
        public readonly int $accountingDocumentId,
        public readonly int $accountingEntryHeaderId,
        public readonly int $accountingAccountsId,
        public readonly ?int $idTercero,
        public readonly ?string $indicatorDc,
        public readonly string $amount,
        public readonly int $coinId,
        public readonly ?int $costCenterId,
        public readonly ?string $positionText,
    ) {
    }

    public static function fromRequest(UpdateAccountingEntryPositionRequest $request): self
    {
        return new self(
            businessStructureId: $request->integer('business_structure_id'),
            accountingDocumentId: $request->integer('accounting_document_id'),
            accountingEntryHeaderId: $request->integer('accounting_entry_header_id'),
            accountingAccountsId: $request->integer('accounting_accounts_id'),
            idTercero: $request->filled('id_tercero') ? $request->integer('id_tercero') : null,
            indicatorDc: $request->filled('indicator_dc') ? $request->string('indicator_dc')->toString() : null,
            amount: $request->string('amount')->toString(),
            coinId: $request->integer('coin_id'),
            costCenterId: $request->filled('cost_center_id') ? $request->integer('cost_center_id') : null,
            positionText: $request->filled('position_text') ? $request->string('position_text')->toString() : null,
        );
    }
}
