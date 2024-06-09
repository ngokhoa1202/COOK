<?php

declare(strict_types=1);
namespace App\Model;

use App\App;
use App\Database;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Exception\ClassNotFoundException;
use App\View;
use PDO;
use PDOException;

class ProductModel extends Model {
  protected int $productId;
  protected int $typeId;
  protected string $productName;
  protected string $description;

  // public function index(): View {
  //   return View::make("/product");
  // }

  private function __construct(int $typeId, string $productName, string $description, int $productId = 0) {
    parent::__construct();
    $this->typeId = $typeId;
    $this->productName = $productName;
    $this->description = $description;
    $this->productId = $productId;
  }

  public static function make(int $typeId, string $productName, string $description, int | string $productId = 0): static {
    $typeId = filter_var($typeId, FILTER_VALIDATE_INT);
    $productName = filter_var($productName, FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
    if (is_string($productId)) {
      $productId = filter_var($productId, FILTER_VALIDATE_INT);
    }
    return new ProductModel($typeId, $productName, $description, $productId);
  }

  public function create(): int {
    try {
      $this->database->beginTransaction();
      $query = "INSERT INTO products (type_id, product_name, `description`) VALUES (:typeid, :productname, :description)";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(':typeid', $this->typeId);
      $stmt->bindValue(':productname', $this->productName);
      $stmt->bindValue(':description', $this->description);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $this->productId = (int) $this->database->lastInsertId();
      $this->database->commit();
    }
    catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->productId = 0;
    }
    return $this->productId;
  }

  public static function getById(int $productId): array | null {
    try {
        $query = "SELECT * FROM products WHERE product_id = :productId";
        $stmt = App::getDatabaseConnection()->prepare($query);
        $stmt->bindValue(":productId", $productId);
        if (!$stmt->execute()) {
            throw new BadQueryException();
        }
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$productData) {
            throw new ClassNotFoundException('Product');
        }
        return $productData;
    } catch (PDOException | ClassNotFoundException $ex) {
        return null;
    }
  }

  public static function getAllByTypeId(int $typeId): array {
    try {
        $query = "SELECT * FROM products WHERE type_id = :typeId";
        $stmt = App::getDatabaseConnection()->prepare($query);
        $stmt->bindValue(":typeId", $typeId);
        if (!$stmt->execute()) {
            throw new BadQueryException();
        }
        $productsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $products = [];
        // foreach ($productsData as $productData) {
        //     $products[] = new ProductModel($productData['type_id'], $productData['product_name'], $productData['description']);
        // }
        return $productsData;
    } catch (PDOException | BadQueryException $ex) {
        throw new BadQueryException();
    }
  }

  public static function delete(int $productId): bool {
      try {
          $query = "DELETE FROM products WHERE product_id = :productId";
          $stmt = App::getDatabaseConnection()->prepare($query);
          $stmt->bindValue(":productId", $productId);
          return $stmt->execute();
      } catch (PDOException $ex) {
          return false;
      }
  }

  public static function getAllProductsInRange(int $offset, int $limit) {
    $products = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query =
        "SELECT products.product_id, products.product_name, types_categories_menus.menu_name, 
          types_categories_menus.category_name, types_categories_menus.type_name, products.description

          FROM products
          INNER JOIN
            (SELECT types.type_id, types.type_name, categories_menus.menu_name, categories_menus.category_name
                FROM types
                  INNER JOIN (
                    SELECT category_id, category_name, menu_name 
                    FROM categories
                    INNER JOIN menus
                    WHERE categories.menu_id = menus.menu_id
                  ) AS categories_menus
                  WHERE types.category_id = categories_menus.category_id
                ) AS types_categories_menus
          WHERE products.type_id = types_categories_menus.type_id
          ORDER BY products.product_id
          LIMIT :limit OFFSET :offset;
        ";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":offset", $offset);
      $stmt->bindValue(":limit", $limit);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $products;
  }

  public static function countNumberOfProducts(): int {
    $totalProducts = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT COUNT(*) FROM products;";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $totalProducts = $stmt->fetchColumn();
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $totalProducts;
  }

  public static function countNumberOfProductPages(int $length): int {
    $totalProducts = static::countNumberOfProducts();
    return ($totalProducts % $length === 0) ? intdiv($totalProducts, $length) : (intdiv($totalProducts, $length) + 1);
  }

  public function update() {
    return static::updateByProductId($this->productId, $this->typeId, $this->productName, $this->description);
  }

  public static function updateByProductId(int &$productId, int &$typeId, string &$productName, string &$description): int {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 
        "UPDATE products 
          SET type_id = :typeId, product_name = :productName, `description` = :description 
          WHERE product_id = :productId;
        ";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":typeId", $typeId);
      $stmt->bindValue(":productName", $productName);
      $stmt->bindValue(":description", $description);
      $stmt->bindValue(":productId", $productId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $productId = 0;
    }
    return $productId;
  }

  public static function deleteByProductId(int &$productId): int {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "DELETE FROM products WHERE product_id = :productId";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":productId", $productId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $productId = 0;
    }

    return $productId;
  }

}
?>