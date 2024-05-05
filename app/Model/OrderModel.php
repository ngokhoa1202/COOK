<?php

declare(strict_types=1);
namespace App\Model;

use App\App;
use App\Database;
use App\Exception\BadQueryException;
use App\Exception\ForbiddenException;
use App\View;
use PDO;
use PDOException;

class OrderModel extends Model {
    protected int $orderId;
    protected int $userId;
    protected int $preTotalCost;
    protected int $promotionalCost;
    protected int $deliveryCost;
    protected int $finalCost;
    protected string $deliveryDate;
    protected string $leaveOrderWhenAbsent;
    protected string $paymentMethod;

    public function index(): View {
        return View::make("/cart");
    }

    private function __construct(int $userId, string $deliveryDate, string $leaveOrderWhenAbsent, string $paymentMethod) {
        parent::__construct();
        $this->userId = $userId;
        $this->deliveryDate = $deliveryDate;
        $this->leaveOrderWhenAbsent = $leaveOrderWhenAbsent;
        $this->paymentMethod = $paymentMethod;
        $this->preTotalCost = 0;
        $this->promotionalCost = 0;
        $this->deliveryCost = 0;
        $this->finalCost = 0;
    }

    public static function make(int $userId, string $deliveryDate, string $leaveOrderWhenAbsent, string $paymentMethod): static {
        return new OrderModel($userId, $deliveryDate, $leaveOrderWhenAbsent, $paymentMethod);
    }

    public function create(): int {
        try {
            $this->database->beginTransaction();
            $query = "INSERT INTO orders (user_id, delivery_date, leave_order_when_absent, payment_method) VALUES (:userid, :deliverydate, :leaveorderwhenabsent, :paymentmethod)";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':userid', $this->userId);
            $stmt->bindValue(':deliverydate', $this->deliveryDate);
            $stmt->bindValue(':leaveorderwhenabsent', $this->leaveOrderWhenAbsent);
            $stmt->bindValue(':paymentmethod', $this->paymentMethod);
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

    $this->orderId = (int) $this->database->lastInsertId();
    return $this->orderId;
    }

    public static function delete(int $orderId): bool {
        try {
            $query = "DELETE FROM orders WHERE order_id = :orderId";
            $stmt = App::getDatabaseConnection()->prepare($query);
            $stmt->bindValue(":orderId", $orderId);
            return $stmt->execute();
        } catch (PDOException $ex) {
            return false;
        }
    }

    //Trigger when order_product changes
    public static function triggerOrder(int $orderId, int $productCost): void { 

    }

    public static function getAllOrderbyUserId(int $userId): void {

    }
}

?>