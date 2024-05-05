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

  private function __construct(int $menuId, string $categoryName, string $description, int $categoryId = 0) {
    parent::__construct();
    $this->menuId = $menuId;
    $this->categoryName = $categoryName;
    $this->description = $description;
    $this->categoryId = $categoryId;
  }

  public function getCategoryId(): int {
    return $this->categoryId;
  }

  public static function make(int $menuId, string $categoryName, string $description, int | string $categoryId = 0): CategoryModel {
    $categoryName = filter_var($categoryName, FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
    if (is_string($categoryId)) {
      $categoryId = filter_var($categoryId, FILTER_VALIDATE_INT);
    }
    
    return new CategoryModel($menuId, $categoryName, $description, $categoryId);
  }

  public function create(): int {
    try {
      $this->database->beginTransaction();
      $query = "INSERT INTO categories(menu_id, category_name, `description`) VALUES (:menuId, :categoryName ,:description)";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(':menuId', $this->menuId);
      $stmt->bindValue(":categoryName", $this->categoryName);
      $stmt->bindValue(':description', $this->description);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      $this->categoryId = (int) $this->database->lastInsertId();
      $this->database->commit();
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->categoryId = 0;
    }
    return $this->categoryId;
  }

  public function update(): int {
    return static::updateByCategoryId($this->categoryId, $this->menuId, $this->categoryName, $this->description);
  }

  public static function updateByCategoryId(int &$categoryId, int &$menuId, string &$categoryName, string &$description): int {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = '
        UPDATE categories 
        SET menu_id = :menuId, 
            category_name = :categoryName, 
            `description` = :description
        WHERE categories.category_id = :categoryId;
      ';
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":menuId", $menuId);
      $stmt->bindValue(":categoryName", $categoryName);
      $stmt->bindValue(":description", $description);
      $stmt->bindValue(":categoryId", $categoryId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $categoryId = 0;
    }
    return $categoryId;
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

  public static function findCategoryIdByMenuIdAndCategoryName(int | string &$menuId, string &$categoryName): int {
    if (is_string($menuId)) {
      $menuId = filter_var($menuId, FILTER_VALIDATE_INT);
    }
    $categoryName = filter_var($categoryName, FILTER_SANITIZE_SPECIAL_CHARS);
    $categoryId = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 
        "SELECT * FROM categories 
          WHERE categories.category_name = :categoryName AND categories.menu_id = :menuId;
        ";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":categoryName", $categoryName);
      $stmt->bindValue(":menuId", $menuId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $categoryId = (int) $stmt->fetch(PDO::FETCH_ASSOC)["category_id"];
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $categoryId;
  }

  public static function getAllCategoriesInRange(int $offset, int $limit) {
    $categories = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 
          "SELECT category_id, category_name, menu_name, categories.description 
          FROM categories
          INNER JOIN menus
          WHERE categories.menu_id = menus.menu_id
          ORDER BY category_id
          LIMIT :limit OFFSET :offset;
      ";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":offset", $offset);
      $stmt->bindValue(":limit", $limit);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }

      $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $categories;
  }

  public static function countNumberOfCategories(): int {
    $totalCategories = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT COUNT(*) FROM categories;";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $totalCategories = $stmt->fetchColumn();
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $totalCategories;
  }

  public static function countNumberOfCategoryPages(int $length): int {
    $numberOfCategories = static::countNumberOfCategories();
    return ($numberOfCategories % $length === 0) ? intdiv($numberOfCategories, $length) : (intdiv($numberOfCategories, $length) + 1);
  }

  public function delete(): int {
    return static::deleteByCategoryId($this->categoryId);
  }

  public static function deleteByCategoryId(int &$categoryId): int {
    
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 'DELETE FROM categories WHERE categories.category_id = :categoryId';
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":categoryId", $categoryId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }

      App::getDatabaseConnection()->commit();
    } catch (BadQueryException | PDOException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $categoryId = 0;
    }
    return $categoryId;
  }
}
?>