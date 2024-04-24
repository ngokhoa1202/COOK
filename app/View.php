<?php

declare(strict_types=1);

namespace App;

use App\Exception\ViewNotFoundException;

class View {

  
  private function __construct(protected string $view) {
    
  }

  public static function make(string $view): static {
    return new static($view);
  }

  public function __toString(): string {
    return $this->render();
  }

  public function render(): string {
    try {
      $viewPath = VIEW_PATH . "/" . $this->view . "/" . App::DEFAULT_VIEW_FILE;
      $stylePath = VIEW_PATH . "/" . $this->view . "/" . App::DEFAULT_STYLE_FILE;
      $scriptPath = VIEW_PATH . "/" . $this->view . "/" . App::DEFAULT_SCRIPT_FILE;
      if (!file_exists($viewPath) || !file_exists($stylePath) || !file_exists($scriptPath)) {
        throw new ViewNotFoundException();
      }
    } catch (ViewNotFoundException $ex) {
      header("HTTP/1.1 404 Not Found");
      echo View::make("error/404");
    }
    
    ob_start();
    include_once $viewPath;
    return ob_get_clean();
  }
}

?>