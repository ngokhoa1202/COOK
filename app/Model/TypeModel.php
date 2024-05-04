<?php

namespace App\Model;

use App\Exception\BadQueryException;
use PDOException;

class TypeModel extends Model {
  protected int $typeId;
  protected int $categoryId;
  protected string $typeName;
  protected string $description;

  private function __construct(int $categoryId, string $typeName, string $description) {
    parent::__construct();
    $this->categoryId = $categoryId;
    $this->typeName = $typeName;
    $this->description = $description;
  }

  public static function make(int $categoryId, string $typeName, string $description): TypeModel {
    return new TypeModel($categoryId, $typeName, $description);
  }

  public function create(): int {
    try {
      $this->database->beginTransaction();

      $query = "INSERT INTO types(category_id, type_name, `description`) VALUES(:categoryId, :typeName, :description);";
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
      $this->typeId = -1;
    }

    return $this->typeId;
  }
}


?>