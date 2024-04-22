<?php

declare(strict_types=1);
namespace App\Exception;

use Exception;

class EncryptionAlgorithmNotFoundExeption extends Exception {
  protected $message = "Encryption Algorithm Not Found";
}

?>