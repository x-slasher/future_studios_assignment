<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\ExportStatus;
use App\Models\CustomerExport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CustomerExport */
class CustomerExportResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'status' => $this->status->value,
            'row_count' => $this->row_count,
            'download_url' => $this->status === ExportStatus::Completed
                ? route('customers.exports.download', $this)
                : null,
            'error_message' => $this->error_message,
            'created_at' => $this->created_at->toIso8601ZuluString(),
            'completed_at' => $this->completed_at?->toIso8601ZuluString(),
        ];
    }
}
