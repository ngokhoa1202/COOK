<?php

namespace App;
use App\Exception\ClassNotFoundException;
use App\Exception\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface {

  private array $entries = [];

  /**
   * @param string $id fully qualified name of class
   * @return mixed
   */
  public function get(string $id) {
    if ( $this->has($id)) {
      $callback = $this->entries[$id];
      return $callback($this);
    }

    return $this->resolve($id);
  }

  public function resolve(string $id) {
    // Inspect class
    $reflectionClass = new \ReflectionClass($id);
    // If class has its constructor
    if (! $reflectionClass->isInstantiable()) {
      throw new ContainerException();
    }

    // Inspect its constructor
    $constructor = $reflectionClass->getConstructor();
    if (! $constructor) {
      return new $id;
    }

    // Inspect constructor param === dependencies
    $parameters = $constructor->getParameters();
    if (! $parameters) {
      return new $id;
    }

    // If there is any dependencies in constructor params, resolve that dependency
    $dependencies = array_map(function(\ReflectionParameter $param) use ($id) {
      $name = $param->getName();
      $type = $param->getType();

      // check if param is type hinted
      // require type hints to run
      if (! $type) {
        throw new ContainerException("Failed to resolve class because there is no type hints");
      }

      if ($type instanceof \ReflectionUnionType) {
        throw new ContainerException("Failed to resolve class becasue there is a dependency of union type");
      }

      if ($type instanceof  \ReflectionNamedType && ! $type->isBuiltin()) {
        return $this->get($type->getName());
      }

      throw new ContainerException("Failed to resolve class because there is an invalid param " . $name);
    }, $parameters);

    return $reflectionClass->newInstanceArgs($dependencies);
  }

  public function has(string $id): bool {
    return isset($this->entries[$id]);
  }

  /**
   * @param string $id
   * @param callable $callback function that creates the requested object
   * @return void
   */
  public function set(string $id, callable $callback): void {
    $this->entries[$id] = $callback;
  }
}