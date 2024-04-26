<?php

declare(strict_types=1);
namespace App\Controller;

use App\App;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Model\UserModel;
use App\Model\UserRole;
use App\Model\UserStatus;
use App\View;

class AdminController {
  public const ADMIN_LOGIN_SUCCESS_MSG = "Admin logged in successfully";
  public const CREATE_USER_SUCCESS_MSG = "User created successfully";
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
    $returnedResult = $userModel->verify();
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
}

?>