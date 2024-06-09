<?php

declare(strict_types=1);

namespace Test\Unit;

use App\Controller\AuthenticateController;
use App\Exception\RouteNotFoundException;
use App\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase {

  protected function setUp(): void {
    parent::setUp();
    $this->router = new Router();
  }

  public function testRegisterRoute(): void {
    // when calling a register method
    $this->router->get("/login", [AuthenticateController::class, "index"]);

    $expected = [
      "get" => [
        "/login" => [AuthenticateController::class, "index"]
      ]
    ];

    // assert route registered successfully
    $this->assertEquals($expected, $this->router->routes());
  }

  public function testRegisterGetRoute(): void {
    // given a router obj
    // when calling a register method
    $this->router->get("/login", [AuthenticateController::class, "index"]);

    $expected = [
      "get" => [
        "/login" => [AuthenticateController::class, "index"]
      ]
    ];

    // assert route registered successfully
    $this->assertEquals($expected, $this->router->routes());
  }

  public function testThereAreNoRoute(): void {
    // given a router obj
    $this->router = new Router();
    $expected = [];

    // assert route registered successfully
    $this->assertEmpty($this->router->routes());
  }

  /**
   * @param string $requestUri
   * @param string $requestMethod
   * @return void
   * @test
   * @dataProvider \Test\DataProviders\RouterDataProvider::routeNotFoundCases
   */
  public function testThrowRouteNotFoundException(string $requestUri, string $requestMethod): void {
    $login = new class() {
      public function delete(): bool {
        return true;
      }
    };
    $this->router->get("/login", [\App\Controller\AuthenticateController::class, "index"]);
    $this->router->post("/login", [$login::class, "login"]);
    $this->router->post("/login", [\App\Controller\AuthenticateController::class, "login"]);
    $this->expectException(RouteNotFoundException::class);
    $this->router->resolve($requestUri, $requestMethod);
  }

}