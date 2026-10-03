<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\CustomerExport;
use App\Repositories\Contracts\CustomerExportRepositoryInterface;

final class CustomerExportRepository implements CustomerExportRepositoryInterface
{
    public function create(array $attributes): CustomerExport
    {
        return CustomerExport::query()->create($attributes);
    }

    public function update(CustomerExport $export, array $attributes): CustomerExport
    {
        $export->update($attributes);

        return $export;
    }

    public function findById(int $id): CustomerExport
    {
        return CustomerExport::query()->findOrFail($id);
    }
}
