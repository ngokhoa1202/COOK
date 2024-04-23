<?php

namespace App\Model;

use App\App;
use App\Exception\BadQueryException;
use PDOException;
use PDO;

class CategoryModel extends Model {
  protected int $categoryId;
  protected int $menuId;
  protected string $description;

  private function __construct(int $menuId, string $description) {
    $this->menuId = $menuId;
    $this->description = $description;
  }

  public static function make(int $menuId, string $description): static {
    return new CategoryModel($menuId, $description);
  }

  public function create(): int {

    try {
      $this->database->beginTransaction();
      $query = "INSERT INTO category (menu_id, description) VALUES (:menuId, :description)";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(':menuId', $this->menuId);
      $stmt->bindValue(':description', $this->description);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      $this->database->commit();
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->categoryId = -1;
    }
    $this->categoryId = $this->database->lastInsertId();
    return $this->categoryId;
  }

  public static function getAllCategories(): array {
    $categories = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM category";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $categories;
  }
}

?>