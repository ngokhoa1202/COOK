<?php

declare(strict_types=1);
namespace App\Exception;

use Exception;

class RouteNotFoundException extends Exception {
  protected $message = "Route Not Found";
  protected $code = 404;
}


?>