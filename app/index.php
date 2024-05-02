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
  ->post("/menu/category/new", [\App\Controller\MenuController::class, "createCategory"])
  ->post("/menu/category/type/new", [\App\Controller\MenuController::class, "createType"])
  ->get("/admin/login", [\App\Controller\AdminController::class, "getAdminLoginView"])

  ->get("/product/:id", [\App\Controller\ProductController::class, "getProductById"])
  ->post("/product/new", [\App\Controller\ProductController::class, "createProduct"])
  ->put("/product/:id/:name/:description", [\App\Controller\ProductController::class, "updateProduct"])
  ->delete("/product/:id", [\App\Controller\ProductController::class, "deleteProduct"])
  
  ->get("/admin", [\App\Controller\AdminController::class, "index"])
  ->post("/admin/login", [\App\Controller\AdminController::class, "login"])
  ->get("/admin/users", [\App\Controller\AdminController::class, "getAdminUsersView"])
  ->get("/admin/users/id", [\App\Controller\AdminController::class, "getUserByUserId"])
  ->get("/admin/users/list", [\App\Controller\AdminController::class, "getUserForOnePage"])
  ->get("/admin/users/total", [\App\Controller\AdminController::class, "getNumberOfUsers"])
  ->get("/admin/users/pages/total", [\App\Controller\AdminController::class, "getNumberOfUserPages"])
  ->get("/admin/users/members/total", [\App\Controller\AdminController::class, "getNumberOfMembers"])
  ->get("/admin/users/active/total", [\App\Controller\AdminController::class, "getNumberOfActiveUsers"])
  ->post("/admin/users/new", [\App\Controller\AdminController::class, "createUser"])
  ;
  
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