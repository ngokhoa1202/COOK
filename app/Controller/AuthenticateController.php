<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Exception\BadQueryException;
use App\View;
use App\App;
use App\Exception\ForbiddenException;
use App\Exception\BadRequestException;
use App\Model\UserRole;
use App\Model\UserStatus;

class AuthenticateController {
  public const LOGIN_SUCCESS_MSG = "Logged in succesfully";
  public const SIGNUP_SUCCESS_MSG = "Signed up successfully";
  protected const SESSION_USER_ID = "user_id";
  protected const SESSION_LAST_GENERATED = "last_generated";
  protected const SESSION_USER_USERNAME = "user_username";
  protected const SESSION_USER_PASSWORD = "user_password";

  public function index(): string {
    return View::make("login");
  }

  public function login(): string | false {
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
    $userModel = UserModel::make($email, $email, $password, $password, "", UserRole::getRole(UserRole::MEMBER), UserStatus::getStatus(UserStatus::OFFLINE));
    $returnedResult = $userModel->authenticate();
    if (is_array($returnedResult)) {
        return json_encode($returnedResult);
    }
    try {
        $this->setUpSession();
        $_SESSION[static::SESSION_USER_USERNAME] = $email;
        $_SESSION[static::SESSION_USER_PASSWORD] = $password;
        return json_encode(static::LOGIN_SUCCESS_MSG);
    } catch (ForbiddenException $ex) {
      header("HTTP/1.1 403 Forbidden");
      echo View::make("error/403");
    }
  }

  public function signup(): string {
    try {
      if (!array_key_exists("email", $_POST) || !array_key_exists("password", $_POST) || !array_key_exists("confirm_password", $_POST)) {
        throw new BadRequestException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      return View::make("error/400");
    }
    
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    $userModel = UserModel::make($email, $email, $password, $confirmPassword, "", UserRole::getRole(UserRole::MEMBER), UserStatus::getStatus((UserStatus::OFFLINE)), false);
    if (is_array($userModel)) {
      return json_encode($userModel);
    }
    $userModel->create();
    return json_encode(static::SIGNUP_SUCCESS_MSG);
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
}


?>