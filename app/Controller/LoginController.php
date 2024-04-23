<?php

namespace App\Controller;

use App\Model\UserModel;
use App\View;
use App\App;
use App\Exception\BadRequestException;
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
    try {
      if (!array_key_exists("email", $_POST) || !array_key_exists("password", $_POST) || !array_key_exists("confirm_password", $_POST)) {
        throw new BadRequestException();
      }
    } catch (BadRequestException $ex) {
      header("HTTP/1.1 400 Bad Request");
      echo View::make("error/400");
    }
    
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    $userModel = UserModel::make($email, $email, $password, $confirmPassword, "", UserRole::getRole(UserRole::MEMBER), UserStatus::getStatus((UserStatus::OFFLINE)));
    if (is_array($userModel)) {
      return json_encode($userModel);
    }
    $userModel->create();
    return json_encode(static::SIGNUP_SUCCESS_MSG);
  }


}


?>