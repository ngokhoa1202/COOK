<?php

namespace App;

use App\Exception\RouteNotFoundException;

class Router {
  private array $routes = [];

  private function register(string $requestMethod, string $route, array $action): self {
    $this->routes[$requestMethod][$route] = $action;
    return $this;
  }

  public function get(string $route, array $action): self {
    return $this->register("get", $route, $action);
  }

  public function post(string $route, array $action): self {
    return $this->register("post", $route, $action);
  }

  public function put(string $route, array $action): self {
    return $this->register("put", $route, $action);
  }

  public function delete(string $route, array $action): self {
    return $this->register("delete", $route, $action);
  }

  public function resolve(string $requestUri, string $requestMethod): string {
    $route = explode("?", $requestUri)[0];
    if (!array_key_exists($route, $this->routes[$requestMethod])) {
      throw new RouteNotFoundException();
    }

    
    $action = $this->routes[$requestMethod][$route];

    if (is_array($action)) {
      [$class, $method] = $action;
      if (class_exists($class)) {
        $obj = new $class();
        return call_user_func([$obj, $method], []);
      }
    }

  }
}


?>