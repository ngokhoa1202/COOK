<?php

declare(strict_types=1);
namespace App\Model;

enum UserRole: string {
  case ADMIN = "admin";
  case MEMBER = "member";

  public static function getRole(self $role) {
    return match($role) {
      UserRole::ADMIN => "admin",
      UserRole::MEMBER => "member"
    };
  }
}


?>