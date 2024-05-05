<?php

// Use for menu, category, type, product, serve not existed

namespace App\Exception;

use Exception;

class EntityNotFoundException extends Exception
{
    protected $message;
    protected $code = 404;
    public function __construct(string $entityType)
    {
        $this->message = sprintf('%s not found', ucfirst($entityType));
    }
}
