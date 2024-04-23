<?php

namespace App\Model;

use App\App;
use App\Exception\BadQueryException;
use PDOException;
use PDO;

class CategoryModel extends Model {
  protected int $categoryId;
  protected int $menuId;
  protected string $categoryName;
  protected string $description;

  private function __construct(int $menuId, string $categoryName, string $description) {
    parent::__construct();
    $this->menuId = $menuId;
    $this->categoryName = $categoryName;
    $this->description = $description;
  }

  public static function make(int $menuId, string $categoryName, string $description): static {
    return new CategoryModel($menuId, $categoryName, $description);
  }

  public function create(): int {

    try {
      $this->database->beginTransaction();
      $query = "INSERT INTO categories(menu_id, `description`, category_name) VALUES (:menuId, :categoryName ,:description)";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(':menuId', $this->menuId);
      $stmt->bindValue(":categoryName", $this->categoryName);
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