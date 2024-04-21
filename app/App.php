<?php

namespace App;

use App\Exception\RouteNotFoundException;

class App {
  private static Database $databaseConnection;

  private function __construct(protected Router $router, protected array $request, protected Configuration $config) {
    static::$databaseConnection = new Database($config->database);
  }

  public static function make(Router $router, array $request, Configuration $config): static {
    return new App($router, $request, $config);
  }

  public static function getDatabaseConnection(): Database {
    return static::$databaseConnection;
  }

  public function run() {
    try {
      echo $this->router->resolve($this->request["uri"], $this->request["method"]);
    } catch (RouteNotFoundException $ex) {
      header("HTTP/1.1 404 Not Found");
      echo View::make("/error/404");
    }
  }


}

?>