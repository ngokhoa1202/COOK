<?php

namespace App\Model;

use App\App;
use PDOException;

class UserModel extends Model {
  protected int $userId;
  protected string $username;
  protected string $email;
  protected string $password;
  protected string $avatar;
  protected string $role;
  protected string $status;
  protected string $createdAt;
  protected string $updatedAt;

  private function __construct(string $username, string $email, string $password, string $avatar, string $role, 
    string $status) {
    $this->username = $username;
    $this->email = $email;
    $this->password = hash(App::getEncryptionAlgorithm("password"), $this->password);
    $this->avatar = $avatar;
    $this->role = $role;
    $this->status = $status;
  }

  public static function make(string $username, string $email, string $password, string $avatar, string $role, 
    string $status): static {
    
    return new UserModel($username, $email, $password, $avatar, $role, $status);
  }
  
  public function create(string $username, string $email, string $encryptedPassword, string $avatar = "", 
    string $role = UserRole::MEMBER, string $status = UserStatus::OFFLINE): int {

    $query = "
      INSERT INTO users(username, email, `password`, avatar, `role`, `status`, created_at, updated_at)
      VALUES(:username, :email, :password, :avatar, :role, :status, NOW(), NOW());
    ";
    $stmt = $this->database->prepare($query);
    $stmt->execute([
      "username" => $username,
      "email" => $email,
      "password" => $encryptedPassword,
      "avatar" => $avatar,
      "role" => $role,
      "status" => $status
    ]);
    return (int) $this->database->lastInsertId();
  }
}



?>