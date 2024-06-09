<?php

namespace App\Controller;

use App\App;
use App\Service\MenuService;
use App\View;

class HomeController {

  public function index(): string {
    //return View:  :make("home");
    echo App::$container->get(MenuService::class)->getNumberOfMenus();
  }


}

?>