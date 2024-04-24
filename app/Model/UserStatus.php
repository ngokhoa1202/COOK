<?php

declare(strict_types=1);
namespace App\Model;

enum UserStatus: string {
  case OFFLINE = "online";
  case ONLINE = "offline";

  public static function getStatus(self $status) {
    return match($status) {
      UserStatus::OFFLINE => "offline",
      UserStatus::ONLINE => "online"
    };
  }
}


?>