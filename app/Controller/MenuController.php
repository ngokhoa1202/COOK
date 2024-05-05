<?php

namespace App\Controller;

use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Model\CategoryModel;
use App\Model\MenuModel;
use App\Model\TypeModel;
use App\View;
use PDOException;

class MenuController {
  
  public const CREATE_CATEGORY_SUCCESS_MSG = "Category created successfully";
  public const CREATE_CATEGORY_FAILURE_MSG = "Failed to create category";
  public const CREATE_TYPE_SUCCESS_MSG = "Type created successfully";
  public const CREATE_TYPE_FAILURE_MSG = "Failed to create type";

  public function index(): string {
    return View::make("/menus");
  }

  public function readAllMenus(): string {
    return json_encode(MenuModel::getAllMenus());
  }

  public function createCategory(): string {
    try {
      if (!array_key_exists("menu_id", $_POST) || !array_key_exists("description", $_POST) ||
        !array_key_exists("category_name", $_POST)) {
        throw new BadRequestException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }

    $menuId = filter_input(INPUT_POST, "menu_name", FILTER_SANITIZE_NUMBER_INT);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
    $categoryName = filter_input(INPUT_POST, "category_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $categoryModel =  CategoryModel::make($menuId, $categoryName, $description);
    if ($categoryModel->create() === -1) {
      return json_encode(static::CREATE_CATEGORY_FAILURE_MSG);
    }
    return json_encode(static::CREATE_CATEGORY_SUCCESS_MSG);
  }

  public function createType(): string {
    try {
      if (!array_key_exists("type_name", $_POST) || !array_key_exists("category_id", $_POST) 
        || !array_key_exists("description", $_POST)
      ) {
        throw new BadRequestException();
      }

      $typeName = filter_input(INPUT_POST, "type_name", FILTER_SANITIZE_SPECIAL_CHARS);
      $categoryId = filter_input(INPUT_POST, "category_id", FILTER_SANITIZE_NUMBER_INT);
      $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
      $typeModel = TypeModel::make($categoryId, $typeName, $description);
      if (! $typeModel->create()) {
        return json_encode(static::CREATE_TYPE_FAILURE_MSG);  
      }
      return json_encode(static::CREATE_TYPE_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
  }

  
}


?>