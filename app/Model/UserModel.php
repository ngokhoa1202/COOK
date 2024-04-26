<?php

namespace App\Model;

use App\App;
use App\Database;
use App\Exception\BadQueryException;
use App\Exception\ForbiddenException;
use PDO;
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

  private const MIN_LENGTH_PASSWORD = 8;
  public const REQUIRED_EMAIL_MSG = "Email is required";
  public const WRONG_EMAIL_PATTERN = "Wrong email pattern";
  public const REQUIRED_PASSWORD_MSG = "Password is required";
  public const WRONG_PASSWORD_PATTERN = "Wrong password pattern";
  public const TOO_SHORT_PASSWORD = "At least 8 characters for password";
  public const WRONG_CONFIRM_PASSWORD = "Wrong connfirmed password";
  public const EMAIL_NOT_EXIST_MSG = "Email does not exist";
  public const WRONG_PASSWORD_MSG = "Wrong password";

  private function __construct(string $username, string $email, string $password, string $avatar, string $role, 
    string $status) {
    parent::__construct();
    $this->username = $username;
    $this->email = $email;
    $this->password = $password;
    $this->avatar = $avatar;
    $this->role = $role;
    $this->status = $status;
  }

  public static function make(string $username, string $email, string $password, string $confirmPassword, string $avatar, string $role, 
    string $status): static | array {
    
    $error = static::validate($username, $email, $password, $confirmPassword, $avatar, $role, $status);
    if (is_array($error)) {
      return $error;
    }  
    return new UserModel($username, $email, $password, $avatar, $role, $status);
  }
  
  /**
   * Make a transaction and insert a user into database. If failed, database will be rolled back
   *
   * @return integer -1 if failed, otherwise last inserted id of user
   */
  public function create(): int {
    try {
      $this->database->beginTransaction();
      $query = "
        INSERT INTO users(username, email, `password`, avatar, `role`, `status`, created_at, updated_at)
        VALUES(:username, :email, :password, :avatar, :role, :status, NOW(), NOW());
      ";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(":username", $this->username);
      $stmt->bindValue(":email", $this->email);
      $stmt->bindValue(":password", $this->password);
      $stmt->bindValue(":avatar", $this->avatar);
      $stmt->bindValue(":role", $this->role);
      $stmt->bindValue(":status", $this->status);
      $stmt->execute();
      $this->userId = $this->database->lastInsertId();
      $this->database->commit();
    } catch (PDOException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->userId = -1;
    }
    return $this->userId;
  }

  /**
   * Validate all user input field. All fields are passed by reference
   *
   * @param string $username
   * @param string $email
   * @param string $password
   * @param string $confirmPassword
   * @param string $avatar
   * @param string $role
   * @param string $status
   * @return bool if all fields are successfully validated
   * @return array if there is a wrong field, return its name and error.
   */
  public static function validate(string &$username, string &$email, string &$password, string &$confirmPassword,
    string &$avatar, string &$role, string &$status): bool | array {

    $error = static::validateEmail($email);
    if (is_array($error)) {
      return $error;
    }
    $error = static::validateAndEncryptPlainPassword($password, $confirmPassword);
    if (is_array($error)) {
      return $error;
    }
    $error = static::validateRole($role);
    if (is_array($error)) {
      return $error;
    }
    return true;
  }

  /**
   * Validate user email.
   *
   * @param string $email
   * @return bool | array if email is validated successfully; otherwise, field with its error
   */
  private static function validateEmail(string &$email): bool | array {
    $validatedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (! $validatedEmail) {
      return ["email" => static::WRONG_EMAIL_PATTERN];
    }
    $email = $validatedEmail;
    return true;
  }

  private static function validateAndEncryptPlainPassword(string &$password, string &$confirmPassword): bool | array {
    if (strlen($password) < static::MIN_LENGTH_PASSWORD) {
      return ["password" => static::TOO_SHORT_PASSWORD];
    }
    if (strcmp($password, $confirmPassword) !== 0) {
      return ["confirm_password" => static::WRONG_CONFIRM_PASSWORD];
    }

    $validatedPlainPassword = filter_var($password, FILTER_SANITIZE_ENCODED);
    if (! $validatedPlainPassword) {
      return false;
    }
    $password = App::getEncryptionConfiguration()->hashPassword($validatedPlainPassword);
    return true;
  }

  private static function validateRole(string &$role): bool | array {
    $validatedRole = filter_var($role, FILTER_SANITIZE_SPECIAL_CHARS);
    if (! $validatedRole) {
      return ["role" => "Role does not exist"];
    }
    $roles = static::getAllRoles();
    if (! in_array($validatedRole, $roles)) {
      return ["role" => "Role does not exist"];
    }
    $role = $validatedRole;
    return true;
  }


  private static function validateAvatarLink(string &$avatar): bool | array {
    $validatedAvatar = filter_var($avatar, FILTER_SANITIZE_URL);
    if (! $validatedAvatar) {
      return ["avatar" => ""];
    }
    $avatar = $validatedAvatar;
    return true;
  }

  private function checkEmailExist(): bool {
    $emailExist = true;
    try {
      $this->database->beginTransaction();
      $query = "SELECT COUNT(*) FROM users WHERE users.email = :email";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(":email", $this->email);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      if ($stmt->fetchColumn() === 0) {
        $emailExist = false;
      }
      $this->database->commit();
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $emailExist = false;
    }

    return $emailExist;
  }

  /**
   * Check if a user with email, password and role exists in database. If wrong email, or wrong password, return 
   * an associative array. If true, return a user model
   *
   * @return UserModel | array
   */
  public function verify(): UserModel | array {
    if (! $this->checkEmailExist()) {
      return ["email" => static::EMAIL_NOT_EXIST_MSG];
    }
    $result = $this;

    try {
      $this->database->beginTransaction();
      $query = "SELECT * FROM users WHERE users.email = :email AND users.password = :password";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(":email", $this->email);
      $stmt->bindValue(":password", $this->password);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $userArr = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($userArr)) {
        $result = ["password" => static::WRONG_PASSWORD_MSG];
      }
      else {
        $this->userId = $userArr["user_id"];
        $this->role = $userArr["role"];
      }
     
      $this->database->commit();
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->userId = -1;
    }

    return $result;
  }

  public function isAdmin() {
    return $this->role === UserRole::getRole(UserRole::ADMIN);
  }

  public function getRole(): string {
    return $this->role;
  }

  public static function getAllRoles(): array {
    $roles = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT DISTINCT `role` FROM users";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      while (($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
        array_push($roles, $row["role"]);
      }

      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $roles;
  }
}



?>