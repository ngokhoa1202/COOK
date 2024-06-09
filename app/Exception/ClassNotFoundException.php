<?php

// Use for menu, category, type, product, serve not existed

namespace App\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ClassNotFoundException extends Exception implements NotFoundExceptionInterface {
  protected $message;
  protected $code = 404;
  public function __construct(string $className) {
    $this->message = "Class " . $className . " not found";
  }
}
