<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;
use PDOStatement;

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

  public function __call($callback, $arguments) {
    return call_user_func_array([$this->pdo, $callback], $arguments);
  }

  public function beginTransaction(): bool {
    return $this->pdo->beginTransaction();
  }

  public function commit(): bool {
    return $this->pdo->commit();
  }

  public function rollBack(): bool {
    return $this->pdo->rollBack();
  }

  public function prepare(string $query): false | PDOStatement {
    return $this->pdo->prepare($query);
  }

  public function inTransaction(): bool {
    return $this->pdo->inTransaction();
  }

  public function lastInsertId(string $name = null): string {
    return $this->pdo->lastInsertId($name);
  }

}

?>