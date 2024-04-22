<?php

namespace App\Controller;

use App\Model\UserModel;
use App\View;
use App\App;
use App\Model\UserRole;
use App\Model\UserStatus;

class LoginController {
  public const LOGIN_SUCCESS_MSG = "Logged in succesfully";
  public const SIGNUP_SUCCESS_MSG = "Signed up successfully";

  public function index(): string {
    return View::make("login");
  }

  public function login(): string {
    if (isset($_POST["signup"])) {
      return $this->signup();
    }
    return json_encode(static::LOGIN_SUCCESS_MSG);
  }

  public function signup(): string {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_ENCODED);
    
    $userModel = UserModel::make($email, $email, $password, "", UserRole::getRole(UserRole::MEMBER), UserStatus::getStatus((UserStatus::OFFLINE)));
    $userModel->create();
    return json_encode(static::SIGNUP_SUCCESS_MSG);
  }

  
}


?>