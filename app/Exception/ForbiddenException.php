<?php

namespace App\Exception;

use Exception;

class ForbiddenException extends Exception {
  protected $message = "403 Forbidden";
}



?>