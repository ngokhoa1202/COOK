<?php

namespace App\Exception;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \Exception implements ContainerExceptionInterface {
  protected $message;
  protected $code = 404;
  public function __construct() {
    $this->message = "Class is not instantiable";
  }
}