<?php

namespace App\Service;

class CategoryService {
  
  protected MenuService $menuService;
  public function __construct(MenuService $menuService) {
    $this->menuService = $menuService;
  }
}



?>