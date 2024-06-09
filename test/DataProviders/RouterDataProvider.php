<?php

declare(strict_types=1);

namespace Test\DataProviders;

class RouterDataProvider {
  public static function routeNotFoundCases(): array {
    return [
      ["/login", "put"],
      ["/login", "delete"],
      ["/signup", "post"]
    ];
  }
}