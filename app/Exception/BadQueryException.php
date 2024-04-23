<?php

namespace App\Exception;

use Exception;
class BadQueryException extends Exception {
  protected $message = "Bad query";
}

?>