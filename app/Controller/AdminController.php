<?php

namespace App\Controller;

use App\App;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Model\CategoryModel;
use App\Model\MenuModel;
use App\Model\TypeModel;
use App\Model\UserModel;
use App\Model\UserRole;
use App\Model\UserStatus;
use App\Model\ProductModel;
use App\View;
use Exception;
use PDOException;

class AdminController {
  public const INVALID_FIELDS_MSG = "Invalid fields in form";
  public const ADMIN_LOGIN_SUCCESS_MSG = "Admin logged in successfully";

  public const CREATE_USER_SUCCESS_MSG = "User created successfully";
  public const CREATE_USER_FAILURE_MSG = "Failed to create category";
  public const UPDATE_USER_SUCCESS_MSG = "User updated successfully";
  public const UPDATE_USER_FAILURE_MSG = "Failed to update user";
  public const DELETE_USER_SUCCESS_MSG = "User deleted successfully";
  public const DELETE_USER_FAILURE_MSG = "Failed to delete user";

  public const CREATE_MENU_SUCCESS_MSG = "Menu created successfully";
  public const CREATE_MENU_FAILURE_MSG = "Failed to create menu";
  public const UPDATE_MENU_SUCCESS_MSG = "Menu updated successfully";
  public const UPDATE_MENU_FAILURE_MSG = "Failed to update menu";
  public const DELETE_MENU_SUCCESS_MSG = "Delete menu successfully";
  public const DELETE_MENU_FAILURE_MSG = "Failed to delete menu";
  public const NOT_EXIST_MENU_MSG = "Menu does not exist";

  public const CREATE_CATEGORY_SUCCESS_MSG = "Category created successfully";
  public const CREATE_CATEGORY_FAILURE_MSG = "Failed to create category successfully";
  public const UPDATE_CATEGORY_SUCCESS_MSG = "Category updated successfully";
  public const UPDATE_CATEGORY_FAILURE_MSG = "Failed to update category";
  public const DELETE_CATEGORY_SUCCESS_MSG = "Delete category successfully";
  public const DELETE_CATEGORY_FAILURE_MSG = "Failed to delete category";
  public const NOT_EXIST_CATEGORY_MSG = "Category does not exist";
  public const DUPLICATE_CATEGORY_IN_SAME_MENU_MSG = "Duplicate category in the same menu";

  public const CREATE_TYPE_SUCCESS_MSG = "Category created successfully";
  public const CREATE_TYPE_FAILURE_MSG = "Failed to create category successfully";
  public const UPDATE_TYPE_SUCCESS_MSG = "Type updated successfully";
  public const UPDATE_TYPE_FAILURE_MSG = "Failed to update type";
  public const DELETE_TYPE_SUCCESS_MSG = "Delete type successfully";
  public const DELETE_TYPE_FAILURE_MSG = "Failed to delete type";
  public const NOT_EXIST_TYPE_MSG = "Type does not exist";
  public const DUPLICATE_TYPE_IN_SAME_CATEGORY_MSG = "Duplicate type in the same category";

  public const CREATE_PRODUCT_SUCCESS_MSG = "Product created successfully";
  public const CREATE_PRODUCT_FAILURE_MSG = "Failed to create product";
  public const DUPLICATE_PRODUCT_IN_SAME_TYPE_MSG = "Duplicate product in the same type";
  public const UPDATE_PRODUCT_SUCCESS_MSG = "Product updated successfully";
  public const UPDATE_PRODUCT_FAILURE_MSG = "Failed to update product";
  public const NOT_EXIST_PRODUCT_MSG = "Product does not exist";

  protected const SESSION_USER_ID = "user_id";
  protected const SESSION_LAST_GENERATED = "last_generated";
  protected const SESSION_USER_USERNAME = "user_username";
  protected const SESSION_USER_PASSWORD = "user_password";

  /**
   * Handle post request from login form
   *
   * @return string
   */
  public function login(): string {
    try {
      if (!array_key_exists("email", $_POST) || !array_key_exists("password", $_POST)) {
        throw new BadQueryException();
      }
    } catch (BadQueryException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $userModel = UserModel::make($email, $email, $password, $password, "", UserRole::getRole(UserRole::ADMIN), UserStatus::getStatus(UserStatus::OFFLINE));
    $returnedResult = $userModel->authenticate();
    if (is_array($returnedResult)) {
      return json_encode($returnedResult);
    }
    try {
      if (! $returnedResult->isAdmin()) {
        throw new ForbiddenException();
      } else {
        $this->setUpSession();
        $_SESSION[static::SESSION_USER_USERNAME] = $email;
        $_SESSION[static::SESSION_USER_PASSWORD] = $password;
        return json_encode(static::ADMIN_LOGIN_SUCCESS_MSG);
      }
    } catch (ForbiddenException $ex) {
      header("HTTP/1.1 403 Forbidden");
      echo View::make("error/403");
    }
  }

  public function index(): View {
    return View::make("/admin/home");
  }

  /**
   * Handle get request for login form
   *
   * @return View
   */
  public function getAdminLoginView(): View {
    return View::make("admin/login");
  } 

  private function setUpSession(): void {
    session_start();
    $currentTime = time();
    if (array_key_exists(static::SESSION_USER_ID, $_SESSION)) { // already logged in
      if (
        !array_key_exists(static::SESSION_LAST_GENERATED, $_SESSION) // session not set = first time logged in
        || $currentTime - $_SESSION[static::SESSION_LAST_GENERATED] >= App::getSessionInterval() // session expires
      ) {
        session_regenerate_id(true); // delete all old files existed in last sessions
        $newSessionId = session_create_id(); // create id for new session
        $sessionId = $newSessionId . "_" . $_SESSION[static::SESSION_USER_ID];
        session_id($sessionId); // set session id for new session
        $_SESSION[static::SESSION_LAST_GENERATED] = $currentTime;
      }
    } else if (!array_key_exists(static::SESSION_LAST_GENERATED, $_SESSION)
      || $currentTime - $_SESSION[static::SESSION_LAST_GENERATED] >= App::getSessionInterval()
    ) { // not logged in yet
      session_regenerate_id(false); // do not delete old files in last session
      $_SESSION[static::SESSION_LAST_GENERATED] = $currentTime; // just extend to new session
    }
  }

  public function getAdminUsersView(): View | string {
    return View::make("admin/users");
  }

  public function createUser(): string {
    try {
      if (!array_key_exists("email", $_POST) || ! array_key_exists("password", $_POST) 
        || ! array_key_exists("role", $_POST) || ! array_key_exists("confirm_password", $_POST)
      ) {
        throw new BadRequestException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $role = $_POST["role"];
    $userModel = UserModel::make($email, $email, $password, $confirmPassword, "", $role, UserStatus::getStatus((UserStatus::OFFLINE)));
    if (is_array($userModel)) {
      return json_encode($userModel);
    }
    $userModel->create();
    return json_encode(static::CREATE_USER_SUCCESS_MSG);
  }

  public function getUserForOnePage(): string {
    $pageIndex = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($pageIndex === false || $length === false) {
        throw new BadQueryException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
    $offset = ($pageIndex - 1) * $length;
    return json_encode(UserModel::getAllUsersInRange($offset, $length));
  }

  public function getNumberOfUsers(): int {
    return UserModel::countNumberOfUsers();
  }

  public function getNumberOfMembers(): int {
    return UserModel::countNumberOfMembers();
  }

  public function getNumberOfActiveUsers(): int {
    return UserModel::countNumberOfActiveUsers();
  }

  public function getNumberOfUserPages(): int {
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($length === false) {
        throw new BadQueryException();
      }
    } catch (BadQueryException $ex) {
      header("HTTP/1.1 Bad Request");
      echo View::make("error/400");
    }
    return UserModel::countNumberOfUserPages($length);  
  }

  public function getUserByUserId(): string {
    $userId = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
    try {
      if ($userId === false) {
        throw new BadQueryException();
      }
    } catch (BadQueryException $ex) {
      header("HTTP/1.1 Bad Request");
      echo View::make("error/400");
    }
    return json_encode(UserModel::getUserByUserId($userId));
  }

  public function updateUserByUserId(): string {
    try {
      if (
        ! array_key_exists("email", $_POST) || !array_key_exists("password", $_POST)
        || !array_key_exists("role", $_POST) || !array_key_exists("confirm_password", $_POST)
        || ! array_key_exists("status", $_POST) || !array_key_exists("username", $_POST)
        || ! array_key_exists("user_id", $_POST) || ! array_key_exists("avatar", $_POST)
      ) {
        throw new BadRequestException();
      }

      $userId = $_POST["user_id"];
      $username = $_POST["username"];
      $email = $_POST["email"];
      $password = $_POST["password"] ?? "";
      $confirmPassword = $_POST["confirm_password"] ?? "";
      $avatar = $_POST["avatar"];
      $role = $_POST["role"];
      $status = $_POST["status"];
      $userModel = UserModel::make($username, $email, $password, $confirmPassword, $avatar, $role, $status, $userId, true);
      if (is_array($userModel)) {
        return json_encode($userModel);
      } 
      $userId = $userModel->update();
      return ($userId > 0) ? json_encode(static::UPDATE_USER_SUCCESS_MSG) : json_encode(static::UPDATE_USER_FAILURE_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
  }

  public function getAdminMenusView(): string {
    return View::make("admin/menus");
  }

  public function getNumberOfMenus(): string {
    return json_encode(MenuModel::countNumberOfMenus());
  }

  public function getMenuForOnePage(): string {
    $pageIndex = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($pageIndex === false || $length === false) {
        throw new BadQueryException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode([]);
    }
    $offset = ($pageIndex - 1) * $length;
    return json_encode(MenuModel::getAllMenusInRange($offset, $length));
  }

  public function getNumberOfMenuPages(): int {
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($length === false || is_null($length)) {
        throw new BadQueryException();
      }
    } catch (BadQueryException $ex) {
      header("HTTP/1.1 Bad Request");
      echo View::make("error/400");
    }
    return MenuModel::countNumberOfMenuPages($length);
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
    if ($menuModel->create() === 0) {
      return json_encode(static::CREATE_MENU_FAILURE_MSG);
    }
    return json_encode(static::CREATE_MENU_SUCCESS_MSG);
  }

  public function updateMenuByMenuId(): string {
    try {
      if (
        !array_key_exists("menu_id", $_POST) || !array_key_exists("menu_name", $_POST)
        || !array_key_exists("description", $_POST)
      ) {
        throw new BadRequestException();
      }

      $menuId = filter_input(INPUT_POST, "menu_id", FILTER_VALIDATE_INT);
      if ($menuId === false) {
        throw new BadRequestException();
      }

      $menuName = $_POST["menu_name"];
      $description = $_POST["description"] ?? "";
      $menuModel = MenuModel::make($menuName, $description, $menuId);
      if (is_array($menuModel)) {
        header("HTTP/1.1 400 Bad Request");
        return json_encode($menuModel);
      } 
      $menuId = $menuModel->update();
      if ($menuId === 0) {
        throw new BadRequestException();
      }
      return json_encode(static::UPDATE_MENU_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode(static::UPDATE_MENU_FAILURE_MSG);
    }
  }

  public function deleteMenuByMenuId(): string {
    try {
      if (!array_key_exists("menu_id", $_POST)) {
        throw new BadRequestException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode(static::DELETE_MENU_FAILURE_MSG);
    }
    $menuId = filter_input(INPUT_POST, "menu_id", FILTER_VALIDATE_INT);
    try {
      if ($menuId === false) {
        throw new BadQueryException();
      }
      MenuModel::deleteByMenuId($menuId);
      if ($menuId === 0) {
        throw new BadRequestException();
      }
      return json_encode(static::DELETE_MENU_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode(static::DELETE_MENU_FAILURE_MSG);
    }
  }

  public function deleteUserByUserId(): string {
    try {
      if (! array_key_exists("user_id", $_POST)) {
        throw new BadRequestException();
      }
      $userId = filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT);
      if ($userId === false) {
        throw new BadRequestException();
      }
      UserModel::deleteByUserId($userId);
      if ($userId === 0) {
        throw new BadRequestException();
      }
      return json_encode(static::DELETE_USER_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode(static::DELETE_USER_FAILURE_MSG);
    }
  }

  public function getCategoryForOnePage(): string {
    $pageIndex = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($pageIndex === false || $length === false) {
        throw new BadQueryException(static::INVALID_FIELDS_MSG, 400);
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
    $offset = ($pageIndex - 1) * $length;
    return json_encode(CategoryModel::getAllCategoriesInRange($offset, $length));
  }

  public function getAdminCategoriesView(): string {
    return View::make("admin/categories");
  }

  public function createCategory(): string {
    try {
      if (!array_key_exists("menu_name", $_POST) || !array_key_exists("description", $_POST)
        || !array_key_exists("category_name", $_POST)
      ) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }

      $categoryName = $_POST["category_name"];
      $menuName = $_POST["menu_name"];
      $menuId = MenuModel::findMenuIdByName($menuName);
      if ($menuId === 0) {
        throw new BadRequestException(static::NOT_EXIST_MENU_MSG);
      }

      $description = $_POST["description"] ?? "";
      $categoryModel = CategoryModel::make($menuId, $categoryName, $description);
      if ($categoryModel->create() === 0) {
        throw new BadRequestException(static::DUPLICATE_CATEGORY_IN_SAME_MENU_MSG);
      }
      return json_encode(static::CREATE_CATEGORY_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }

  public function getNumberOfCategories(): string {
    return json_encode(CategoryModel::countNumberOfCategories());
  }

  public function getNumberOfCategoryPages(): string {
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($length === false || is_null($length)) {
        throw new BadQueryException(static::INVALID_FIELDS_MSG, 400);
      }
    } catch (BadQueryException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
    return json_encode(CategoryModel::countNumberOfCategoryPages($length));
  }

  public function updateCategoryByCategoryId() {
    try {
      if (! array_key_exists("category_id", $_POST) || ! array_key_exists("category_name", $_POST) 
        || ! array_key_exists("menu_name", $_POST) || ! array_key_exists("description", $_POST)
      ) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG, 400);
      }

      $categoryId = $_POST["category_id"];
      $categoryName = $_POST["category_name"];
      $menuName = $_POST["menu_name"];
      $description = $_POST["description"] ?? "";
      $menuId = MenuModel::findMenuIdByName($menuName);
      if ($menuId === 0) {
        throw new BadRequestException(static::NOT_EXIST_MENU_MSG, 400);
      }
      $categoryModel = CategoryModel::make($menuId, $categoryName, $description, $categoryId);
      if ($categoryModel->update() === 0) {
        throw new BadRequestException(static::DUPLICATE_CATEGORY_IN_SAME_MENU_MSG, 400);
      }
      return json_encode(static::UPDATE_CATEGORY_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }

  public function deleteCategoryByCategoryId(): string {
    try {
      if (! array_key_exists("category_id", $_POST)) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      $categoryId = filter_input(INPUT_POST, "category_id", FILTER_VALIDATE_INT);
      if ($categoryId === false) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      if (CategoryModel::deleteByCategoryId($categoryId) === 0) {
        throw new BadQueryException(static::NOT_EXIST_CATEGORY_MSG);
      }
      return json_encode(static::DELETE_CATEGORY_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }

  public function getAdminTypesView(): string {
    return View::make("admin/types");
  }

  public function createType(): string {
    try {
      if (
        ! array_key_exists("type_name", $_POST) || ! array_key_exists("category_name", $_POST)
        || ! array_key_exists("menu_name", $_POST) || ! array_key_exists("description", $_POST)
      ) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      $typeName = $_POST["type_name"];
      $categoryName = $_POST["category_name"];
      $menuName = $_POST["menu_name"];
      $description = $_POST["description"] ?? "";
      $menuId = MenuModel::findMenuIdByName($menuName);
      if ($menuId === 0) {
        throw new BadRequestException(static::NOT_EXIST_MENU_MSG);
      }
      $categoryId = CategoryModel::findCategoryIdByMenuIdAndCategoryName($menuId, $categoryName);
      if ($categoryId === 0) {
        throw new BadRequestException(static::NOT_EXIST_CATEGORY_MSG);
      }
      $typeModel = TypeModel::make($categoryId, $typeName, $description);
      if ($typeModel->create() === 0) {
        throw new BadRequestException(static::DUPLICATE_TYPE_IN_SAME_CATEGORY_MSG);
      }
      return json_encode(static::CREATE_TYPE_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }

  public function updateTypeByTypeId(): string {
    try {
      if (! array_key_exists("type_id", $_POST) || !array_key_exists("category_name", $_POST)
        || !array_key_exists("type_name", $_POST) || !array_key_exists("menu_name", $_POST) 
        || !array_key_exists("description", $_POST)
      ) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      $typeId = $_POST["type_id"];
      $typeName = $_POST["type_name"];
      $categoryName = $_POST["category_name"];
      $menuName = $_POST["menu_name"];
      $description = $_POST["description"] ?? "";
      $menuId = MenuModel::findMenuIdByName($menuName);
      if ($menuId === 0) {
        throw new BadRequestException(static::NOT_EXIST_MENU_MSG);
      }
      $categoryId = CategoryModel::findCategoryIdByMenuIdAndCategoryName($menuId, $categoryName);
      if ($categoryId === 0) {
        throw new BadRequestException(static::NOT_EXIST_CATEGORY_MSG);
      }
      $typeModel = TypeModel::make($categoryId, $typeName, $description, $typeId);
      if ($typeModel->update() === 0) {
        throw new BadRequestException(static::DUPLICATE_TYPE_IN_SAME_CATEGORY_MSG);
      }
      return json_encode(static::UPDATE_TYPE_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Request");
      return json_encode($ex->getMessage());
    }
  }

  public function deleteTypeByTypeId(): string {
    try {
      if (! array_key_exists("type_id", $_POST)) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      $typeId = filter_input(INPUT_POST, "type_id", FILTER_VALIDATE_INT);
      if ($typeId === false) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      if (TypeModel::deleteByTypeId($typeId) === 0) {
        throw new BadRequestException(static::NOT_EXIST_TYPE_MSG);
      }
      return json_encode(static::DELETE_TYPE_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }

  public function getNumberOfTypes(): string {
    return json_encode(TypeModel::countNumberOfTypes());
  }

  public function getNumberOfTypePages(): string {
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($length === false || is_null($length)) {
        throw new BadQueryException(static::INVALID_FIELDS_MSG, 400);
      }
    } catch (BadQueryException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
    return json_encode(TypeModel::countNumberOfTypePages($length));
  }

  public function getTypeForOnePage(): string {
    $pageIndex = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($pageIndex === false || $length === false) {
        throw new BadQueryException(static::INVALID_FIELDS_MSG, 400);
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
    $offset = ($pageIndex - 1) * $length;
    return json_encode(TypeModel::getAllTypesInRange($offset, $length));
  }

  public function createProduct(): string {
    try {
      if (!array_key_exists("product_name", $_POST) || !array_key_exists("type_name", $_POST) 
        || !array_key_exists("category_name", $_POST) || !array_key_exists("menu_name", $_POST)
        || !array_key_exists("description", $_POST)
      ) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      
      $productName = $_POST["product_name"];
      $typeName = $_POST["type_name"];
      $categoryName = $_POST["category_name"];
      $menuName = $_POST["menu_name"];
      $description = $_POST["description"] ?? "";

      $menuId = MenuModel::findMenuIdByName($menuName);
      if ($menuId === 0) {
        throw new BadRequestException(static::NOT_EXIST_MENU_MSG);
      }
      $categoryId = CategoryModel::findCategoryIdByMenuIdAndCategoryName($menuId, $categoryName);
      if ($categoryId === 0) {
        throw new BadRequestException(static::NOT_EXIST_CATEGORY_MSG);
      }
      $typeId = TypeModel::findTypeIdByCategoryIdAndTypeName($categoryId, $typeName);
      if ($typeId === 0) {
        throw new BadRequestException(static::NOT_EXIST_TYPE_MSG);
      }
      $productModel = ProductModel::make($typeId, $productName, $description);
      if ($productModel->create() === 0) {
        throw new BadRequestException(static::DUPLICATE_PRODUCT_IN_SAME_TYPE_MSG);
      }
      return json_encode(static::CREATE_PRODUCT_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }

  public function getAdminProductsView(): string {
    return View::make("admin/products");
  }

  public function getNumberOfProducts(): string {
    return json_encode(ProductModel::countNumberOfProducts());
  }

  public function getNumberOfProductPages(): string {
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($length === false || is_null($length)) {
        throw new BadQueryException(static::INVALID_FIELDS_MSG, 400);
      }
    } catch (BadQueryException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
    return json_encode(ProductModel::countNumberOfProductPages($length));
  }

  public function getProductForOnePage(): string {
    $pageIndex = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
    $length = filter_input(INPUT_GET, "length", FILTER_VALIDATE_INT);
    try {
      if ($pageIndex === false || $length === false) {
        throw new BadQueryException(static::INVALID_FIELDS_MSG);
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request"); 
      return json_encode($ex->getMessage());
    }
    $offset = ($pageIndex - 1) * $length;
    return json_encode(ProductModel::getAllProductsInRange($offset, $length));
  }

  public function updateProductByProductId() {
    try {
      if (
        !array_key_exists("product_name", $_POST) || !array_key_exists("type_name", $_POST)
        || !array_key_exists("category_name", $_POST) || !array_key_exists("menu_name", $_POST)
        || !array_key_exists("description", $_POST)
      ) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }

      $productId = $_POST["product_id"];
      $productName = $_POST["product_name"];
      $typeName = $_POST["type_name"];
      $categoryName = $_POST["category_name"];
      $menuName = $_POST["menu_name"];
      $description = $_POST["description"] ?? "";

      $menuId = MenuModel::findMenuIdByName($menuName);
      if ($menuId === 0) {
        throw new BadRequestException(static::NOT_EXIST_MENU_MSG);
      }
      $categoryId = CategoryModel::findCategoryIdByMenuIdAndCategoryName($menuId, $categoryName);
      if ($categoryId === 0) {
        throw new BadRequestException(static::NOT_EXIST_CATEGORY_MSG);
      }
      $typeId = TypeModel::findTypeIdByCategoryIdAndTypeName($categoryId, $typeName);
      if ($typeId === 0) {
        throw new BadRequestException(static::NOT_EXIST_TYPE_MSG);
      }
      $productModel = ProductModel::make($typeId, $productName, $description, $productId);
      if ($productModel->update() === 0) {
        throw new BadRequestException(static::DUPLICATE_PRODUCT_IN_SAME_TYPE_MSG);
      }
      return json_encode(static::UPDATE_PRODUCT_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }

  public function deleteProductByProductId(): string {
    try {
      if (!array_key_exists("product_id", $_POST)) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      $productId = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);
      if ($productId === false) {
        throw new BadRequestException(static::INVALID_FIELDS_MSG);
      }
      if (ProductModel::deleteByProductId($productId) === 0) {
        throw new BadRequestException(static::NOT_EXIST_PRODUCT_MSG);
      }
      return json_encode(static::DELETE_TYPE_SUCCESS_MSG);
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return json_encode($ex->getMessage());
    }
  }
}
  

?>