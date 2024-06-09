<?php

namespace App\Model;

use App\App;
use App\Database;

abstract class Model {
  protected Database $database;
  
  public function __construct() {
    $this->database = App::getDatabaseConnection();
  }

  public function getDatabaseConnection(): Database {
    return $this->database;
  }
}

?>