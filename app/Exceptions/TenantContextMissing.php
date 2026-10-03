<?php

declare(strict_types=1);

namespace App\Exceptions;

use LogicException;

final class TenantContextMissing extends LogicException
{
    public function __construct()
    {
        parent::__construct('No tenant context is set for a tenant-scoped query.');
    }
}
