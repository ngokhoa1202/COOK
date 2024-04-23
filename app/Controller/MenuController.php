<?php

namespace App\Controller;

use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Model\CategoryModel;
use App\Model\MenuModel;
use App\View;

class MenuController {
  public const CREATE_MENU_SUCCESS_MSG = "Menu created successfully";
  public const CREATE_MENU_FAILURE_MSG = "Failed to create menu";
  public const CREATE_CATEGORY_SUCCESS_MSG = "Category created successfully";
  public const CREATE_CATEGORY_FAILURE_MSG = "Failed to create category";

  public function index(): string {
    return View::make("/menu");
  }

  public function createMenu(): string {
    try {
      if (!array_key_exists("menu_name", $_POST) || !array_key_exists("description", $_POST)) {
        throw new BadRequestException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
    
    $menuName = $_POST["menu_name"];
    $description = $_POST["description"];
    $menuModel = MenuModel::make($menuName, $description);
    if ($menuModel->create() === -1) {
      return json_encode(static::CREATE_MENU_FAILURE_MSG);
    }
    return json_encode(static::CREATE_MENU_SUCCESS_MSG);
  }

  public function readAllMenus(): string {
    return json_encode(MenuModel::getAllMenus());
  }

  public function createCategory(): string {
    try {
      if (!array_key_exists("menu_name", $_POST) || !array_key_exists("description", $_POST) ||
        !array_key_exists("category_name", $_POST)) {
        throw new BadRequestException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 4004 Bad Request");
      echo View::make("error/400");
    }

    $menuName = filter_input(INPUT_POST, "menu_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $menuId = MenuModel::findMenuIdByName($menuName);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
    $categoryName = filter_input(INPUT_POST, "category_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $categoryModel =  CategoryModel::make($menuId, $categoryName, $description);
    if ($categoryModel->create() === -1) {
      return json_encode(static::CREATE_CATEGORY_FAILURE_MSG);
    }
    return json_encode(static::CREATE_CATEGORY_SUCCESS_MSG);
  }
}


?>