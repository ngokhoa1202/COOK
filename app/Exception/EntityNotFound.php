<?php

// Use for menu, category, type, product not existed

declare(strict_types=1);

namespace App\Exception;

use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(string $entityType)
    {
        $this->message = sprintf('%s not found', ucfirst($entityType));
        $this->code = 404;
    }
}
