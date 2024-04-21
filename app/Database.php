<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class Database {
  private PDO $pdo;
  private const DEFAULT_DATABASE_OPTIONS = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ];

  public function __construct(array $databaseConfig) {
    try {
      $this->pdo = new PDO(
        $databaseConfig["driver"] . ":host=" . $databaseConfig["host"] . ";dbname=" . $databaseConfig["database"],
        $databaseConfig["username"],
        $databaseConfig["password"],
        $databaseConfig["options"] ?? static::DEFAULT_DATABASE_OPTIONS
      );
    } catch (PDOException $ex) {
      throw new PDOException($ex->getMessage(), (int) $ex->getCode());
    }
  }
}

?>