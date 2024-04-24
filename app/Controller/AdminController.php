<?php

declare(strict_types=1);
namespace App\Controller;

use App\Exception\BadQueryException;
use App\Exception\ForbiddenException;
use App\Model\UserModel;
use App\Model\UserRole;
use App\Model\UserStatus;
use App\View;

class AdminController {
  public const ADMIN_LOGIN_SUCCESS_MSG = "Admin logged in successfully";

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
}

?>