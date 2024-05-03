<?php

declare(strict_types=1);
namespace App\Model;

use App\App;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Exception\EntityNotFoundException;
use App\View;
use PDO;
use PDOException;

class ProductModel extends Model {
  protected int $productId;
  protected int $typeId;
  protected string $productName;
  protected string $description;

  public function index(): View {
    return View::make("/product");
  }

  private function __construct(int $typeId, string $productName, string $description) {
    parent::__construct();
    $this->typeId = $typeId;
    $this->productName = $productName;
    $this->description = $description;
  }

  public static function make(int $typeId, string $productName, string $description): ProductModel {
    return new ProductModel($typeId, $productName, $description);
  }

  public function create(): int {
    try {
      $this->database->beginTransaction();
      $query = "INSERT INTO products (type_id, product_name, description) VALUES (:typeid, :productname, :description)";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(':typeid', $this->typeId);
      $stmt->bindValue(':productname', $this->productName);
      $stmt->bindValue(':description', $this->description);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
    }
    catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      return -1;
    }

    $this->productId = (int) $this->database->lastInsertId();
    return $this->productId;
  }

  public static function getById(int $productId): ProductModel | null {
    try {
        $query = "SELECT * FROM products WHERE product_id = :productId";
        $stmt = App::getDatabaseConnection()->prepare($query);
        $stmt->bindValue(":productId", $productId);
        if (!$stmt->execute()) {
            throw new BadQueryException();
        }
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$productData) {
            throw new EntityNotFoundException('Product');
        }
        return new ProductModel($productData['type_id'], $productData['product_name'], $productData['description']);
    } catch (PDOException | EntityNotFoundException $ex) {
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
        $products = [];
        foreach ($productsData as $productData) {
            $products[] = new ProductModel($productData['type_id'], $productData['product_name'], $productData['description']);
        }
        return $products;
    } catch (PDOException | BadQueryException $ex) {
        return [];
    }
  }

  public function delete(int $productId): bool {
      try {
          $query = "DELETE FROM products WHERE product_id = :productId";
          $stmt = App::getDatabaseConnection()->prepare($query);
          $stmt->bindValue(":productId", $productId);
          return $stmt->execute();
      } catch (PDOException $ex) {
          return false;
      }
  }

  public function updateProduct(int $productId, string $productName, string $description): bool {
    try {
        $query = "UPDATE products SET product_name = :productName, description = :description WHERE product_id = :productId";
        $stmt = App::getDatabaseConnection()->prepare($query);
        $stmt->bindValue(':productId', $productId);
        $stmt->bindValue(':productName', $productName);
        $stmt->bindValue(':description', $description);
        return $stmt->execute();
    } catch (PDOException $ex) {
        return false;
    }
  }

}
?>