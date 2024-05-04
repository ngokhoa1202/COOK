<?php

namespace App\Controller;

use App\App;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Model\MenuModel;
use App\Model\UserModel;
use App\Model\UserRole;
use App\Model\UserStatus;
use App\View;

class AdminController {
  public const ADMIN_LOGIN_SUCCESS_MSG = "Admin logged in successfully";
  public const CREATE_USER_SUCCESS_MSG = "User created successfully";
  public const UPDATE_USER_SUCCESS_MSG = "User updated successfully";
  public const UPDATE_USER_FAILURE_MSG = "Failed to update user";
  public const CREATE_MENU_SUCCESS_MSG = "Menu created successfully";
  public const CREATE_MENU_FAILURE_MSG = "Failed to create menu";
  public const UPDATE_MENU_SUCCESS_MSG = "Menu updated successfully";
  public const UPDATE_MENU_FAILURE_MSG = "Failed to update menu";
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
      echo View::make("error/400");
    }
    $offset = ($pageIndex - 1) * $length;
    return json_encode(MenuModel::getAllMenusInRange($offset, $length));
  }

  public static function getNumberOfMenuPages(): int {
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

  public function deleteMenuByMenuId() {
    
  }
}
  
?>