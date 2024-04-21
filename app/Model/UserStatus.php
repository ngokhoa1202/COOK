<?php

declare(strict_types=1);
namespace App\Model;

enum UserStatus: string {
  case OFFLINE = "online";
  case ONLINE = "offline";
}


?>