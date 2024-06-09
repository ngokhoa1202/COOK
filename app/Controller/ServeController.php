<?php

namespace App\Controller;

use App\Exception\BadQueryException;
use App\Exception\ClassNotFoundException;
use App\Exception\BadRequestException;
use App\Model\ServeModel;
use App\View;
use PDOException;
use PDO;

class ServeController {
    public const SUCCESS = "Operation Success!";
    public const FAIL = "Operation Failed!";

    public function getAllServesByProductId(): string {
        try {
            if (!array_key_exists("product_id", $_GET)) {
              throw new BadQueryException();
            }
      
            $productId = filter_input(INPUT_GET, "product_id", FILTER_SANITIZE_NUMBER_INT);
      
            $serves = [];
            if ($productId !== null) {
              $serves = ServeModel::getServesByProductId($productId);
            } else {
              throw new BadRequestException('Get all serve functionality not implemented');
            }
        
            return json_encode($serves);
        
          } catch (BadRequestException $ex) {
            header("HTTP/1.1 400 Bad Request");
            echo View::make("error/400");
          }
    }

    public function getServeById(): string {
        try {
            if (!array_key_exists("serve_id", $_GET)) {
              throw new BadQueryException();
            }
      
            $serveId = filter_input(INPUT_GET, "serve_id", FILTER_SANITIZE_NUMBER_INT);
      
            $serve = ServeModel::getById($serveId);
            if (!$serve) {
              throw new ClassNotFoundException('Serve');
            }
        
            return json_encode($serve);
        
        } catch (ClassNotFoundException $ex) {
        return json_encode(['error' => 'Serve not found']);
        } catch (PDOException $ex) {
        throw new BadQueryException('Failed to get serve details: ' . $ex->getMessage());
        }
    }

    public function createServeByProductId(): string {
        try {
            // Validate request data
            if (!array_key_exists("product_id", $_POST) || !array_key_exists("serve_name", $_POST) || !array_key_exists("price", $_POST) || !array_key_exists("discount", $_POST) || !array_key_exists("status", $_POST) || !array_key_exists("instruction", $_POST)) {
              throw new BadRequestException();
            }
        
            $productId = filter_input(INPUT_POST, "product_id", FILTER_SANITIZE_NUMBER_INT);
            $serveName = filter_input(INPUT_POST, "serve_name", FILTER_SANITIZE_SPECIAL_CHARS);
            $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_NUMBER_INT);
            $discount = filter_input(INPUT_POST, "discount", FILTER_SANITIZE_NUMBER_INT);
            $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_NUMBER_INT);
            $instruction = filter_input(INPUT_POST, "instruction", FILTER_SANITIZE_SPECIAL_CHARS);
      
            // Create a new ProductModel instance
            $serveModel = ServeModel::make($productId, $serveName, $price, $discount, $status, $instruction);
      
            // Call ProductModel's create method to insert product data
            $serveId = $serveModel->create();
            if ($serveId === -1) {
              return json_encode(static::FAIL);
            }
        
            return json_encode(static::SUCCESS);
        
        } catch (BadRequestException $ex) {
        header("HTTP/1.1 400 Bad Request");
        return json_encode(['error' => 'Invalid serve data']);
        } catch (PDOException $ex) {
        throw new BadQueryException('Failed to create serve: ' . $ex->getMessage());
        }
    }

    public function updateServeById(): string {
        try {
            // Validate request data
            if (!array_key_exists("serve_id", $_POST) || !array_key_exists("serve_name", $_POST) || !array_key_exists("price", $_POST) || !array_key_exists("discount", $_POST) || !array_key_exists("status", $_POST) || !array_key_exists("instruction", $_POST)) {
                throw new BadRequestException();
            }
        
            $serveId = filter_input(INPUT_POST, "serve_id", FILTER_SANITIZE_NUMBER_INT);
            $serveName = filter_input(INPUT_POST, "serve_name", FILTER_SANITIZE_SPECIAL_CHARS);
            $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_NUMBER_INT);
            $discount = filter_input(INPUT_POST, "discount", FILTER_SANITIZE_NUMBER_INT);
            $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_NUMBER_INT);
            $instruction = filter_input(INPUT_POST, "instruction", FILTER_SANITIZE_SPECIAL_CHARS);

            $serveModel = ServeModel::getById($serveId);
            if (!$serveModel) {
                throw new ClassNotFoundException('serve');
            }

            $isUpdated = ServeModel::updateServe($serveId, $serveName, $price, $discount, $status, $instruction);

            if ($isUpdated) {
                return json_encode('Serve updated successfully');
            } else {
                return json_encode('Failed to update serve');
            }
        } catch (BadRequestException $ex) {
            header("HTTP/1.1 400 Bad Request");
            return json_encode(['error' => 'Invalid serve data']);
        } catch (ClassNotFoundException $ex) {
            return json_encode(['error' => 'Serve not found']);
        } catch (PDOException $ex) {
            throw new BadQueryException('Failed to update serve: ' . $ex->getMessage());
        }
        
    }

    public function deleteServeById(): string {
        try {
            if (!array_key_exists("serve_id", $_POST)) {
                throw new BadRequestException();
            }
            $serveId = filter_input(INPUT_POST, "serve_id", FILTER_SANITIZE_NUMBER_INT);
        
            // $productModel = ProductModel::getById($productId);
            $isDeleted = ServeModel::deleteServe($serveId);
        
            if (!$isDeleted) {
                throw new ClassNotFoundException('Serve');
            }
            return json_encode('Serve deleted successfully');
            
        } catch (ClassNotFoundException $ex) {
            return json_encode(['error' => 'Product not found']);
        } catch (PDOException $ex) {
            throw new BadQueryException('Failed to delete product: ' . $ex->getMessage());
        }
    }
}


?>