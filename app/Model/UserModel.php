<?php

namespace App\Model;

use App\App;
use App\Database;
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
  protected Database $database;

  private function __construct(string $username, string $email, string $password, string $avatar, string $role, 
    string $status) {
    $this->database = App::getDatabaseConnection();
    $this->username = $username;
    $this->email = $email;
    $this->password = App::getEncryptionConfiguration()->hashPassword($password);
    $this->avatar = $avatar;
    $this->role = $role;
    $this->status = $status;
  }

  public static function make(string $username, string $email, string $password, string $avatar, string $role, 
    string $status): static {
    
    return new UserModel($username, $email, $password, $avatar, $role, $status);
  }
  
  public function create(): int {
    try {
      $this->database->beginTransaction();
      $query = "
        INSERT INTO users(username, email, `password`, avatar, `role`, `status`, created_at, updated_at)
        VALUES(:username, :email, :password, :avatar, :role, :status, NOW(), NOW());
      ";
      $stmt = $this->database->prepare($query);
      $stmt->execute([
        "username" => $this->username,
        "email" => $this->email,
        "password" => $this->password,
        "avatar" => $this->avatar,
        "role" => $this->role,
        "status" => $this->status
      ]);
      $this->database->commit();
    } catch (PDOException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
    }
    return (int) $this->database->lastInsertId();
  }


}



?>