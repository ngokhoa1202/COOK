<?php

namespace App\Model;

use App\App;
use App\Database;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
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
  private const WRONG_USER_ID_MSG = "Wrong user id";
  public const REQUIRED_EMAIL_MSG = "Email is required";
  public const WRONG_EMAIL_PATTERN = "Wrong email pattern";
  public const REQUIRED_PASSWORD_MSG = "Password is required";
  public const WRONG_PASSWORD_PATTERN = "Wrong password pattern";

  /* password has at least 8 characters which contain at least one numeric digit and a special character */
  public const PASSWORD_PATTERN = "/^(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
  public const TOO_SHORT_PASSWORD = "At least 8 characters for password";
  public const WRONG_CONFIRM_PASSWORD = "Wrong confirmed password";
  public const EMAIL_NOT_EXIST_MSG = "Email does not exist";
  public const WRONG_PASSWORD_MSG = "Wrong password";

  private function __construct(string $username, string $email, string $password, string $avatar, string $role, 
    string $status, int $userId = 0) {
    parent::__construct();
    $this->userId = $userId;
    $this->username = $username;
    $this->email = $email;
    $this->password = $password;
    $this->avatar = $avatar;
    $this->role = $role;
    $this->status = $status;
  }

  public static function make(string $username, string $email, string $password, string $confirmPassword, string $avatar, string $role, 
    string $status, int $userId = 0, bool $isUpdated = false): UserModel | array {
    
    if (! $isUpdated) {
      $error = static::validateForCreation($username, $email, $password, $confirmPassword, $avatar, $role, $status, $userId);
    } else {
      $error = static::validateForUpdate($username, $email, $password, $confirmPassword, $avatar, $role, $status, $userId);
    }
    
    if (is_array($error)) {
      return $error;
    }  
    return new UserModel($username, $email, $password, $avatar, $role, $status, $userId);
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
   * @param int $userId
   * @return bool | array if all fields are successfully validated, array if there is a wrong field, return its name and error.
   */
  public static function validateForCreation(string &$username, string &$email, string &$password, string &$confirmPassword,
    string &$avatar, string &$role, string &$status): bool | array {
    

    if (strcmp($username, $email) === 0) {
      $error = static::validateUsername($username);
      if (is_array($error)) {
        return $error;
      }
    }

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

  private static function validateForUpdate(string &$username, string &$email, string &$password, string &$confirmPassword,
    string &$avatar, string &$role, string &$status, int &$userId) {
    
    $error = static::validateUserId($userId);
    if (is_array($error)) {
      return $error;
    }

    if (strcmp($username, $email) === 0) {
      $error = static::validateUsername($username);
      if (is_array($error)) {
        return $error;
      }
    }

    $error = static::validateEmail($email);
    if (is_array($error)) {
      return $error;
    }
    if (! empty($password) && ! empty($confirmPassword)) {
      $error = static::validateAndEncryptPlainPassword($password, $confirmPassword);
      if (is_array($error)) {
        return $error;
      }
    }
    
    $error = static::validateRole($role);
    if (is_array($error)) {
      return $error;
    }
    return true;
  }

  private static function validateUsername(string &$username): bool {
    $validatedUsername = htmlspecialchars($username);
    $username = $validatedUsername;
    return true;
  }

  private static function validateUserId(int &$userId): bool | array {
    $validatedUserId = filter_var($userId, FILTER_VALIDATE_INT);
    if (! $validatedUserId) {
      return ["user_id" => static::WRONG_USER_ID_MSG];
    }
    $userId = $validatedUserId;
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
  public function authenticate(): UserModel | array {
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
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if (empty($user)) {
        $result = ["password" => static::WRONG_PASSWORD_MSG];
      }
      else {
        $this->userId = $user["user_id"];
        $this->role = $user["role"];
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

  public static function getAllUsersInRange(int $offset, int $limit): array {
    $users = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM users LIMIT :limit OFFSET :offset;";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":offset", $offset);
      $stmt->bindValue(":limit", $limit);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $users;
  }

  public static function countNumberOfUsers(): int {
    $counter = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT COUNT(*) FROM users";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $counter = $stmt->fetchColumn(0);
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $counter;
  }


  public static function countNumberOfMembers(): int {
    $counter = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 'SELECT COUNT(user_id) FROM users WHERE users.role = "member"';
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      $counter = $stmt->fetchColumn(0);
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $counter;
  }

  public static function countNumberOfActiveUsers(): int {
    $counter = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 'SELECT COUNT(user_id) FROM users WHERE users.status = "active"';
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      $counter = $stmt->fetchColumn(0);
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $counter;
  }

  public static function countNumberOfUserPages(int $length): int {
    $numberOfUsers = static::countNumberOfUsers();
    return ($numberOfUsers % $length === 0) ? ($numberOfUsers / $length) : ($numberOfUsers / $length + 1);
  }

  public static function getUserByUserId(int $userId): array | null {
    $user = null;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM users WHERE users.user_id = :userId";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":userId", $userId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }

    return $user;
  }

  public function update() {
    $updatedId = UserModel::updateByUserId($this->userId, $this->username, $this->email, $this->password,
      $this->avatar, $this->role, $this->status
    );
    return $updatedId;
  }

  public static function updateByUserId(int $userId, string $username, string $email, string $password, string $avatar,
    string $role, string $status) {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "";

      if (! empty($password)) {
        $query = 'UPDATE users
          SET users.username = :username, 
              users.email = :email, 
              users.password = :password,
              users.avatar = :avatar,
              users.status = :status,
              users.role = :role
          WHERE users.user_id = :userId;
        ';
        $stmt = App::getDatabaseConnection()->prepare($query);
        $stmt->bindValue(":username", $username);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", $password);
        $stmt->bindValue(":avatar", $avatar);
        $stmt->bindValue(":status", $status);
        $stmt->bindValue(":role", $role);
        $stmt->bindValue(":userId", $userId);
      } else {
        $query = 'UPDATE users
          SET users.username = :username,
              users.email = :email, 
              users.avatar = :avatar,
              users.status = :status,
              users.role = :role
          WHERE users.user_id = :userId;
        ';
        $stmt = App::getDatabaseConnection()->prepare($query);
        $stmt->bindValue(":username", $username);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":avatar", $avatar);
        $stmt->bindValue(":status", $status);
        $stmt->bindValue(":role", $role);
        $stmt->bindValue(":userId", $userId);
      }
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
      
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $userId;
  }

  public function setUserId(int $userId): void {
    $this->userId = $userId;
  }

  public static function makeUserByUserId(int $userId): UserModel {
    $user = UserModel::getUserByUserId($userId);
    return new UserModel($user["username"], $user['email'], $user['password'], $user['avatar'], $user['role'], $user['status'], $user["user_id"]);
  }
}



?>