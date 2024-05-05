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
  //product
  ->get("/product/typeid", [\App\Controller\ProductController::class, "getAllProducts"]) //Solved
  ->get("/product/id", [\App\Controller\ProductController::class, "getProductById"]) //Solved
  ->post("/product/new", [\App\Controller\ProductController::class, "createProductByTypeId"]) //Solved
  ->post("/product/update", [\App\Controller\ProductController::class, "updateProductById"]) //Solved
  ->post("/product/delete", [\App\Controller\ProductController::class, "deleteProductById"]) //Solved
  //serve
  ->get("/serve/productid", [\App\Controller\ServeController::class, "getAllServesByProductId"]) //Solved
  ->get("/serve/id", [\App\Controller\ServeController::class, "getServeById"]) //Solved
  ->post("/serve/new", [\App\Controller\ServeController::class, "createServeByProductId"]) //Solved
  ->post("/serve/update", [\App\Controller\ServeController::class, "updateServeById"]) //Solved
  ->post("/serve/delete", [\App\Controller\ServeController::class, "deleteServeById"]) //Solved
  //order
  ->get("/order/userid", [\App\Controller\OrderController::class, "getOrderByUserId"])
  ->post("/order/new", [\App\Controller\OrderController::class, "createOrderByUserId"])
  ->post("/order/update", [\App\Controller\OrderController::class, "updateOrderByUserId"])
  ->post("/order/delete", [\App\Controller\OrderController::class, "deleteOrderByUserId"])
  //order_product
  // ->get("order_product/orderid", [\App\Controller\OrderProductController::class, "getOrderProductByOrderId"])
  // ->post("order_product/orderid/new", [\App\Controller\OrderProductController::class, "createOrderProductByOrderId"])
  // ->post("order_product/orderid/update", [\App\Controller\OrderProductController::class, "updateOrderProductByOrderId"])
  // ->post("order_product/orderid/delete", [\App\Controller\OrderProductController::class, "deleteOrderProductByOrderId"])

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