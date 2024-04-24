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

  private const MIN_LENGTH_PASSWORD = 8;
  public const REQUIRED_EMAIL_MSG = "Email is required";
  public const WRONG_EMAIL_PATTERN = "Wrong email pattern";
  public const REQUIRED_PASSWORD_MSG = "Password is required";
  public const WRONG_PASSWORD_PATTERN = "Wrong password pattern";
  public const TOO_SHORT_PASSWORD = "At least 8 characters for password";
  public const WRONG_CONFIRM_PASSWORD = "Wrong connfirmed password";

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
      // $stmt->execute([
      //   "username" => $this->username,
      //   "email" => $this->email,
      //   "password" => $this->password,
      //   "avatar" => $this->avatar,
      //   "role" => $this->role,
      //   "status" => $this->status
      // ]);
      $this->database->commit();
    } catch (PDOException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      return -1;
    }
    $this->userId = $this->database->lastInsertId();
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
    if (!$validatedEmail) {
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


  private static function validateAvatarLink(string &$avatar): bool | array {
    $validatedAvatar = filter_var($avatar, FILTER_SANITIZE_URL);
    if (! $validatedAvatar) {
      return ["avatar" => ""];
    }
    $avatar = $validatedAvatar;
    return true;
  }

}



?>