<?php

declare(strict_types=1);

namespace App;

require_once __DIR__ . "/../vendor/autoload.php";
define("VIEW_PATH", __DIR__ . "/views");

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$router = new Router();
$router
  ->get("/", [\App\Controller\HomeController::class, "index"])
  ->get("/login", [\App\Controller\LoginController::class, "index"])
  ->post("/login", [\App\Controller\LoginController::class, "login"])
  ->get("/menu", [\App\Controller\MenuController::class, "index"])
  ->get("/menu/all", [\App\Controller\MenuController::class, "readAllMenus"])
  ->post("/menu/new", [\App\Controller\MenuController::class, "createMenu"])
  ->post("/menu/category/new", [\App\Controller\MenuController::class, "createCategory"]);
  
$config = new Configuration($_ENV);

$app = App::make(
  $router,
  [
    "uri" => $_SERVER["REQUEST_URI"],
    "method" => strtolower($_SERVER["REQUEST_METHOD"])
  ],
  $config
);

$app->run();

?>