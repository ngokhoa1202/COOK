<?php

namespace App\Controller;

use App\View;

class HomeController {

  public function index(): View {
    return View::make("home");
  }
}

?>