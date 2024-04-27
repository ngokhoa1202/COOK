<?php

namespace App\Model;

use App\App;
use App\Database;
use App\Exception\EntityNotFoundException;
use App\Exception\BadQueryException;
use PDOException;
use PDO;

class ServeModel extends Model {
    protected int $serveId;
    protected int $productId;
    protected string $serveName;
    protected int $price;
    protected int $discount;
    protected bool $status;
    protected string $instruction;
    protected string $createdAt;
    protected string $updatedAt;

    private function __construct(int $productId, string $serveName, int $price, int $discount, bool $status, string $instruction) {
        parent::__construct();
        $this->productId = $productId;
        $this->serveName = $serveName;
        $this->price = $price;
        $this->discount = $discount;
        $this->status = $status;
        $this->instruction = $instruction;
    }

    public static function make(int $productId, string $serveName, int $price, int $discount, bool $status, string $instruction): ServeModel {
        return new ServeModel($productId, $serveName, $price, $discount, $status, $instruction);
    }

    public function create(): int {
        try {
            $this->database->beginTransaction();
            $query = "INSERT INTO serves(product_id, serve_name, price, discount, status, instruction, created_at, updated_at) VALUES(:product_id, :serve_name, :price, :discount, :status, :instruction, NOW(), NOW())";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(":product_id", $this->productId);
            $stmt->bindValue(":serve_name", $this->serveName);
            $stmt->bindValue(":price", $this->price);
            $stmt->bindValue(":discount", $this->discount);
            $stmt->bindValue(":status", $this->status);
            $stmt->bindValue(":instruction", $this->instruction);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            $this->database->commit();
        } catch (PDOException | BadQueryException $ex) {
            if ($this->database->inTransaction()) {
                $this->database->rollBack();
            }
            return -1;
        }
        $this->serveId = $this->database->lastInsertId();
        return $this->serveId;
    }

    public static function getServesByProductId(int $productId): array {
        $serves = [];
        try {
            App::getDatabaseConnection()->beginTransaction();
            $query = "SELECT * FROM serves WHERE product_id = :product_id";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":product_id", $productId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            while ($serveData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $serves[] = new ServeModel($serveData['product_id'], $serveData['serve_name'], $serveData['price'], $serveData['discount'], $serveData['status'], $serveData['instruction'], $serveData['created_at'], $serveData['updated_at']);
            }
            App::getDatabaseConnection()->commit();
        } catch (PDOException | BadQueryException $ex) {
            if (App::getDatabaseConnection()->inTransaction()) {
                App::getDatabaseConnection()->rollBack();
            }
        }
        return $serves;
    }
    
    //Serve

    public static function insertServe(int $productId, string $serveName, int $price, int $discount, bool $status, string $instruction): int {
        try {
            App::getDatabaseConnection()->beginTransaction();
            $query = "INSERT INTO serves(product_id, serve_name, price, discount, status, instruction, created_at, updated_at) VALUES(:product_id, :serve_name, :price, :discount, :status, :instruction, NOW(), NOW())";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":product_id", $productId);
            $stmt->bindValue(":serve_name", $serveName);
            $stmt->bindValue(":price", $price);
            $stmt->bindValue(":discount", $discount);
            $stmt->bindValue(":status", $status);
            $stmt->bindValue(":instruction", $instruction);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            App::getDatabaseConnection()->commit();
            $lastId = App::getDatabaseConnection()->lastInsertId();
            return $lastId;
        } catch (PDOException | BadQueryException $ex) {
            if (App::getDatabaseConnection()->inTransaction()) {
                App::getDatabaseConnection()->rollBack();
            }
            return -1;
        }
    }
    
    public static function deleteServe(int $serveId): bool {
        try {
            App::getDatabaseConnection()->beginTransaction();
            $query = "DELETE FROM serves WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            App::getDatabaseConnection()->commit();
            return true;
        } catch (PDOException | BadQueryException $ex) {
            if (App::getDatabaseConnection()->inTransaction()) {
                App::getDatabaseConnection()->rollBack();
            }
            return false;
        }
    }    

    //ServeName
    public static function getServeNameById(int $serveId): string {
        try {
            $query = "SELECT serve_name FROM serves WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            $serveName = $stmt->fetchColumn(0);
            if (!$serveName) {
                throw new EntityNotFoundException('Serve');
            }
            return $serveName;
        } catch (PDOException | EntityNotFoundException $ex) {
            return '';
        }
    }
    
    public static function updateServeNameById(int $serveId, string $newServeName): bool {
        try {
            $query = "UPDATE serves SET serve_name = :newServeName, updated_at = NOW() WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            $stmt->bindValue(":newServeName", $newServeName);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            return true;
        } catch (PDOException | BadQueryException $ex) {
            return false;
        }
    }
    

    //Price
    public static function getPriceById(int $serveId): int {
        try {
            $query = "SELECT price FROM serves WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            $price = $stmt->fetchColumn(0);
            if (!$price) {
                throw new EntityNotFoundException('Serve');
            }
            return $price;
        } catch (PDOException | EntityNotFoundException $ex) {
            return -1;
        }
    }
    
    public static function updatePriceById(int $serveId, int $newPrice): bool {
        try {
            $query = "UPDATE serves SET price = :newPrice, updated_at = NOW() WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            $stmt->bindValue(":newPrice", $newPrice);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            return true;
        } catch (PDOException | BadQueryException $ex) {
            return false;
        }
    }
    
    //Discount
    public static function getDiscountById(int $serveId): int {
        try {
            $query = "SELECT discount FROM serves WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            $discount = $stmt->fetchColumn(0);
            if (!$discount) {
                throw new EntityNotFoundException('Serve');
            }
            return $discount;
        } catch (PDOException | EntityNotFoundException $ex) {
            return 0;
        }
    }
    
    public static function updateDiscountById(int $serveId, int $newDiscount): bool {
        try {
            $query = "UPDATE serves SET discount = :newDiscount, updated_at = NOW() WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            $stmt->bindValue(":newDiscount", $newDiscount);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            return true;
        } catch (PDOException | BadQueryException $ex) {
            return false;
        }
    }

    //Status
    public static function getStatusById(int $serveId): bool {
        try {
            $query = "SELECT status FROM serves WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            $status = (bool) $stmt->fetchColumn(0);
            if (!$status) {
                throw new EntityNotFoundException('Serve');
            }
            return $status;
        } catch (PDOException | EntityNotFoundException $ex) {
            return false;
        }
    }

    public static function updateStatusById(int $serveId, bool $newStatus): bool {
        try {
            $query = "UPDATE serves SET status = :newStatus, updated_at = NOW() WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            $stmt->bindValue(":newStatus", $newStatus);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            return true;
        } catch (PDOException | BadQueryException $ex) {
            return false;
        }
    }

    //Instruction
    public static function getInstructionById(int $serveId): string {
        try {
            $query = "SELECT instruction FROM serves WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            $instruction = $stmt->fetchColumn(0);
            if (!$instruction) {
                throw new EntityNotFoundException('Serve');
            }
            return $instruction;
        } catch (PDOException | EntityNotFoundException $ex) {
            return '';
        }
    }
    
    public static function updateInstructionById(int $serveId, string $newInstruction): bool {
        try {
            $query = "UPDATE serves SET instruction = :newInstruction, updated_at = NOW() WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            $stmt->bindValue(":newInstruction", $newInstruction);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            return true;
        } catch (PDOException | BadQueryException $ex) {
            return false;
        }
    }    
}

?>