<?php

namespace App\Model;

use App\App;
use App\Exception\BadQueryException;
use PDOException;
use PDO;

class TypeModel extends Model {
  protected int $typeId;
  protected int $categoryId;
  protected string $typeName;
  protected string $description;

  private function __construct(int $categoryId, string $typeName, string $description, int $typeId = 0) {
    parent::__construct();
    $this->categoryId = $categoryId;
    $this->typeName = $typeName;
    $this->description = $description;
    $this->typeId = $typeId;
  }

  public static function make(int $categoryId, string $typeName, string $description, int | string $typeId = 0): TypeModel {
    $typeName = filter_var($typeName, FILTER_SANITIZE_SPECIAL_CHARS);
    $categoryId = filter_var($categoryId, FILTER_SANITIZE_NUMBER_INT);
    $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
    if (is_string($typeId)) {
      $typeId = filter_var($typeId, FILTER_VALIDATE_INT);
    }
    return new TypeModel($categoryId, $typeName, $description, $typeId);
  }

  public function create(): int {
    try {
      $this->database->beginTransaction();

      $query = 
        "INSERT INTO types(category_id, type_name, `description`) 
          VALUES(:categoryId, :typeName, :description);";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(":categoryId", $this->categoryId);
      $stmt->bindValue(":typeName", $this->typeName);
      $stmt->bindValue(":description", $this->description);

      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $this->typeId = (int) $this->database->lastInsertId();
      $this->database->commit();
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->typeId = 0;
    }

    return $this->typeId;
  }

  public function update(): int {
    return static::updateByTypeId($this->typeId, $this->categoryId, $this->typeName, $this->description);
  }

  public static function updateByTypeId(int &$typeId, int &$categoryId, string &$typeName, string &$description): int {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 
        "UPDATE types 
          SET category_id = :categoryId,
              type_name = :typeName,
              `description` = :description
          WHERE types.type_id = :typeId; 
        ";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":categoryId", $categoryId);
      $stmt->bindValue(":typeName", $typeName);
      $stmt->bindValue(":description", $description);
      $stmt->bindValue(":typeId", $typeId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $typeId = 0;
    } 

    return $typeId;
  }

  public static function deleteByTypeId(int &$typeId): int {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "DELETE FROM types WHERE type_id = :typeId";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":typeId", $typeId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $typeId = 0;
    }
    return $typeId;
  }

  public static function countNumberOfTypes(): int {
    $totalTypes = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT COUNT(*) FROM types";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $totalTypes = $stmt->fetchColumn();
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $totalTypes;
  }

  public static function countNumberOfTypePages(int $length): int {
    $totalTypes = static::countNumberOfTypes();
    return ($totalTypes % $length === 0) ? intdiv($totalTypes, $length) : (intdiv($totalTypes, $length) + 1);
  }

  public static function getAllTypesInRange(int $offset, int $limit): array {
    $types = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query =
      "SELECT types.type_id, types.type_name, categories_menus.menu_name, categories_menus.category_name, types.description 
        FROM types
          INNER JOIN (
            SELECT category_id, category_name, menu_name 
            FROM categories
            INNER JOIN menus
            WHERE categories.menu_id = menus.menu_id
          ) AS categories_menus
          WHERE types.category_id = categories_menus.category_id
          ORDER BY types.type_id
          LIMIT :limit OFFSET :offset;
        ";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":offset", $offset);
      $stmt->bindValue(":limit", $limit);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }

      $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $types;
  }
}


?>