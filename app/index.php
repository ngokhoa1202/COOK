<?php

declare(strict_types=1);

namespace App;

require_once __DIR__ . "/../vendor/autoload.php";
define("VIEW_PATH", __DIR__ . "/views");
define("DEFAULT_VIEW_FILE", "index.php");
define("DEFAULT_SCRIPT_FILE", "script.js");
define("DEFAULT_STYLE_FILE", "style.css");

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$router = new Router();
$router
  ->get("/", [\App\Controller\HomeController::class, "index"]);
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