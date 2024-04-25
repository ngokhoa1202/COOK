<?php

namespace App;

use App\Exception\RouteNotFoundException;

class App {
  private static Database $databaseConnection;
  private static Encryption $encryption;
  private static int $sessionInterval;
  public const DEFAULT_VIEW_FILE = "index.php";
  public const DEFAULT_SCRIPT_FILE = "script.js";
  public const DEFAULT_STYLE_FILE = "style.css";

  private function __construct(protected Router $router, protected array $request, protected Configuration $config) {
    static::$databaseConnection = new Database($config->database);
    static::$encryption = new Encryption($config->encryption);
    static::$sessionInterval = $config->session["interval"];
  }

  public static function make(Router $router, array $request, Configuration $config): static {
    return new App($router, $request, $config);
  }

  public static function getEncryptionConfiguration(): Encryption {
    return static::$encryption;
  }

  public static function getDatabaseConnection(): Database {
    return static::$databaseConnection;
  }

  public function run(): void {
    try {
      echo $this->router->resolve($this->request["uri"], $this->request["method"]);
    } catch (RouteNotFoundException $ex) {
      http_response_code(404);
      echo View::make("error/404");
    }
  }

  public static function getSessionInterval(): int {
    return static::$sessionInterval;
  }

}

?>