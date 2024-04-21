<?php

declare(strict_types=1);

namespace App;

class Configuration {
  protected array $config = [];

  public function __construct(array $env) {
    $this->config = [
      "database" => [
        "driver" => $env["DB_DRIVER"] ?? "mysql",
        "host" => $env["DB_HOST"],
        "database" => $env["DB_DATABASE"],
        "username" => $env["DB_USERNAME"],
        "password" => $env["DB_PASSWORD"]
      ]
    ];
  }

  public function __get(string $name): mixed {
    return $this->config[$name];
  }
}

?>