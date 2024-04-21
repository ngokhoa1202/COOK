<?php

declare(strict_types=1);
namespace App\Controller;

use App\View;

class LoginController {
  
  public function index(): View {
    return View::make("login");
  }

  public function login(): string {
    return $_SERVER["email"];
  }
}


?>