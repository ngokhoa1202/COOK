<?php

namespace App;

class Encryption {
  private array $config;
  private const DEFAULT_ALGORITHM="sha256";
  public function __construct(array $config) {
    $this->config = [
      "password" => $config["EN_PASSWORD"]
    ];
  }

  public function __get(string $fieldname): string {
    return $this->config[$fieldname] ?? static::DEFAULT_ALGORITHM;
  }
}

?>