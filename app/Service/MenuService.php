<?php

namespace App\Service;

use App\App;
use App\Exception\BadQueryException;
use App\Model\MenuModel;
use PDO;
use PDOException;

class MenuService {
  protected MenuModel $menu;

  public function __construct(MenuModel $menu) {
    $this->menu = $menu;
  }
  
  /**
   * Get the number of menus
   * @return int
   */
  public function getNumberOfMenus(): int {
    return MenuModel::countNumberOfMenus();
  }
  
  /**
   * 
   * @param integer $offset
   * @param integer $limit
   * @return array
   */
  public function getAllMenusInRange(int $offset, int $limit): array {
    return MenuModel::getAllMenusInRange($offset, $limit);
  }

  /**
   * Find menu id by menu name
   *
   * @param string $menuName
   * @return int
   */
  public function findMenuIdByName(string $menuName): int {
    $menu = MenuModel::findMenuByMenuName($menuName);
    if (empty($menu)) {
      return -1;
    }
    return (int) $menu["menu_id"];
  }

  public function createMenu(string $menuName, string $description) {
    
  }

}