<?php

// declare(strict_types=1);

namespace App;

use App\Exception\RouteNotFoundException;

class Router {
  private array $routes = [];

  private function register(string $requestMethod, string $route, callable | array | string $action): self {
    $this->routes[$requestMethod][$route] = $action;
    return $this;
  }

  public function get(string $route, callable | array | string $action): self {
    return $this->register("get", $route, $action);
  }

  public function post(string $route, callable | array | string $action): self {
    return $this->register("post", $route, $action);
  }

  public function put(string $route, callable | array | string $action): self {
    return $this->register("put", $route, $action);
  }

  public function delete(string $route, callable | string | array $action): self {
    return $this->register("delete", $route, $action);
  }

  public function resolve(string $requestUri, string $requestMethod): string | null {
    $route = explode("?", $requestUri)[0];
    $action = $this->routes[$requestMethod][$route];

    if (! $action) {
      throw new RouteNotFoundException();
    }

    if (is_callable($action)) {
      return call_user_func($action); // invoke a function call $action
    }

    if (is_array($action)) {
      [$class, $method] = $action;
      if (class_exists($class)) {
        $obj = new $class();
        return call_user_func([$obj, $method], []);
      }
    }

    return $action;
  }
}


?>