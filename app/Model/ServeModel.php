<?php

namespace App\Model;

use App\App;
use App\Database;
use App\Exception\EntityNotFoundException;
use App\Exception\BadQueryException;
use App\View;
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

    // public function index(): View {
    //     return View::make("/product-detail");
    // }

    private function __construct(int $productId, string $serveName, int $price, int $discount, bool $status, string $instruction) {
        parent::__construct();
        $this->productId = $productId;
        $this->serveName = $serveName;
        $this->price = $price;
        $this->discount = $discount;
        $this->status = $status;
        $this->instruction = $instruction;
    }

    

    public static function make(int $productId, string $serveName, int $price, int $discount, bool $status, string $instruction): static {
        return new ServeModel($productId, $serveName, $price, $discount, $status, $instruction);
    }

    public function create(): int {
        try {
            $this->database->beginTransaction();
            $query = "INSERT INTO serves(product_id, serve_name, price, discount, `status`, instruction) VALUES(:product_id, :serve_name, :price, :discount, :status, :instruction)";
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
        $this->serveId = (int) $this->database->lastInsertId();
        return $this->serveId;
    }

    public static function getServesByProductId(int $productId): array {
        // $serves = [];
        try {
            App::getDatabaseConnection()->beginTransaction();
            $query = "SELECT * FROM serves WHERE product_id = :product_id";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":product_id", $productId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            // while ($serveData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //     $serves[] = new ServeModel($serveData['product_id'], $serveData['serve_name'], $serveData['price'], $serveData['discount'], $serveData['status'], $serveData['instruction']);
            // }

            $serveData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$serveData) {
                throw new EntityNotFoundException('serve');
            }
            
            App::getDatabaseConnection()->commit();
        } catch (PDOException | BadQueryException $ex) {
            if (App::getDatabaseConnection()->inTransaction()) {
                App::getDatabaseConnection()->rollBack();
            }
        }
        return $serveData;
    }

    public static function getById(int $serveId): array | null {
        try {
            $query = "SELECT * FROM serves WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":serveId", $serveId);
            if (!$stmt->execute()) {
                throw new BadQueryException();
            }
            $serveData = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$serveData) {
                throw new EntityNotFoundException('serve');
            }
            return $serveData;
        }
        catch (PDOException | EntityNotFoundException $ex) {
            return [$ex];
        }
    }

    public static function updateServe(int $serveId, string $serveName, int $price, int $discount, bool $status, string $instruction) {
        try {
            $query = "UPDATE serves SET serve_name = :serveName, price = :price, discount = :discount, `status` = :status, instruction = :instruction WHERE serve_id = :serveId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            // $stmt->bindValue(':productId', $productId);
            // $stmt->bindValue(':productName', $productName);
            // $stmt->bindValue(':description', $description);
            $stmt->bindValue(':serveName', $serveName);
            $stmt->bindValue(':price', $price);
            $stmt->bindValue(':discount', $discount);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':instruction', $instruction);
            $stmt->bindValue(':serveId', $serveId);
            return $stmt->execute();
        } catch (PDOException $ex) {
            return false;
        }
    }
    
    //Serve    
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

    
}

?>