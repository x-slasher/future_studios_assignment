<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\CustomerExport;

interface CustomerExportRepositoryInterface
{
    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): CustomerExport;

    /** @param array<string, mixed> $attributes */
    public function update(CustomerExport $export, array $attributes): CustomerExport;

    public function findById(int $id): CustomerExport;
}
