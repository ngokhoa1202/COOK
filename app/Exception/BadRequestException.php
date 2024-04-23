<?php

namespace App\Exception;

use Exception;

class BadRequestException extends Exception {
  protected $message = "400 Bad Request";
  protected $code = 400;
}

?>