<?php

namespace App\Controller;

use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Exception\EntityNotFoundException;
use App\Model\ProductModel;
use PDOException;
use App\View;

class ProductController {

  public const CREATE_PRODUCT_SUCCESS_MSG = "Product created successfully";
  public const CREATE_PRODUCT_FAILURE_MSG = "Failed to create product";

  public function getProductById(int $productId): string {
    try {
      $product = ProductModel::getById($productId);
      if (!$product) {
        throw new EntityNotFoundException('Product');
      }
  
      // Convert product data to ProductModel object
      return json_encode($product);
  
    } catch (EntityNotFoundException $ex) {
      return json_encode(['error' => 'Product not found']);
    } catch (PDOException $ex) {
      throw new BadQueryException('Failed to get product details: ' . $ex->getMessage());
    }
  }
  

  public function createProduct(): string {
    try {
      // Validate request data (omitted for brevity)
      if (!array_key_exists("type_id", $_POST) || !array_key_exists("product_name", $_POST) || !array_key_exists("description", $_POST)) {
        throw new BadRequestException();
      }
  
      $typeId = filter_input(INPUT_POST, "type_id", FILTER_SANITIZE_NUMBER_INT);
      $productName = filter_input(INPUT_POST, "product_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
  
      // Create a new ProductModel instance
      $productModel = ProductModel::make($typeId, $productName, $description);
  
      // Call ProductModel's create method to insert product data
      $productId = $productModel->create();
      if ($productId === -1) {
        return json_encode(static::CREATE_PRODUCT_FAILURE_MSG);
      }
  
      return json_encode(static::CREATE_PRODUCT_SUCCESS_MSG);
  
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode(['error' => 'Invalid product data']);
    } catch (PDOException $ex) {
      throw new BadQueryException('Failed to create product: ' . $ex->getMessage());
    }
  }
  
  public function getAllProducts(int $typeId = null): string {
    try {
      $products = [];
      if ($typeId !== null) {
        $products = ProductModel::getAllByTypeId($typeId);
      } else {
        throw new BadRequestException('Get all products functionality not implemented');
      }
  
      // Convert product data to an array of objects
      $productDataArray = [];
      foreach ($products as $product) { // Here, $product refers to each element in the $products array
        $productDataArray[] = $product;
      }
  
      return json_encode($productDataArray);
  
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
  }
  
  public function deleteProduct(int $productId): string {
    try {
      $productModel = ProductModel::getById($productId);
      if (!$productModel) {
        throw new EntityNotFoundException('Product');
      }
  
      $isDeleted = $productModel->delete($productId);
  
      if ($isDeleted) {
        return json_encode('Product deleted successfully');
      } else {
        return json_encode('Failed to delete product');
      }
  
    } catch (EntityNotFoundException $ex) {
      return json_encode(['error' => 'Product not found']);
    } catch (PDOException $ex) {
      throw new BadQueryException('Failed to delete product: ' . $ex->getMessage());
    }
  }
  
    
  public function updateProduct(int $productId): string {
    try {
      // Validate request data (omitted for brevity)
      if (!array_key_exists("product_name", $_POST) || !array_key_exists("description", $_POST)) {
        throw new BadRequestException();
      }
  
      $productName = filter_input(INPUT_POST, "product_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
  
      $productModel = ProductModel::getById($productId);
      if (!$productModel) {
        throw new EntityNotFoundException('Product');
      }
  
      $isUpdated = $productModel->updateProduct($productId, $productName, $description);
  
      if ($isUpdated) {
        return json_encode('Product updated successfully');
      } else {
        return json_encode('Failed to update product');
      }
  
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode(['error' => 'Invalid product data']);
    } catch (EntityNotFoundException $ex) {
      return json_encode(['error' => 'Product not found']);
    } catch (PDOException $ex) {
      throw new BadQueryException('Failed to update product: ' . $ex->getMessage());
    }
  }
}
