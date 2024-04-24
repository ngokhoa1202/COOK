<?php

use App\Model\Model;

class ProductModel extends Model {
  protected int $productId;
  protected int $typeId;
  protected string $productName;
  protected string $description;

  private function __construct(int $typeId, string $productName, string $description) {
    parent::__construct();
    $this->typeId = $typeId;
    $this->productName = $productName;
    $this->description = $description;
  }

  public static function make(int $typeId, string $productName, string $description): ProductModel {
    return new ProductModel($typeId, $productName, $description);
  }
}



?>