<?php

declare(strict_types=1);
namespace App\Model;

use App\App;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use PDO;
use PDOException;

class MenuModel extends Model {
  protected int $menuId;
  protected string $menuName;
  protected string $description;

  private function __construct(string $menuName, string $description, int $menuId = 0) {
    parent::__construct();
    $this->menuName = $menuName;
    $this->description = $description;
    $this->menuId = $menuId;
  }

  public static function make(string $menuName, string $description, int $menuId = 0): MenuModel {
    $menuName = filter_var($menuName, FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
    return new MenuModel($menuName, $description, $menuId);
  }

  /**
   * Insert a menu into table menu with its menuName and description
   *
   * @return int id of inserted menu. -1 means failed to insert
   */
  public function create(): int {
    try {
      $this->database->beginTransaction();
      $query = "
        INSERT INTO menus(menu_name, description)
        VALUES(:menuName, :description);
      ";
      $stmt = $this->database->prepare($query);
      $stmt->bindValue(':menuName', $this->menuName);
      $stmt->bindValue(':description', $this->description);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $this->menuId = (int) $this->database->lastInsertId();
      $this->database->commit();
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->menuId = 0;
    }
    return $this->menuId;
  }

  /**
   * @return int
   */
  public function getMenuId(): int {
    return $this->menuId;
  }

  /**
   * Find menu id by menu name
   *
   * @param string $menuName
   * @return int menu id of menu. -1 if error
   */
  public static function findMenuIdByName(string &$menuName): int {
    $menuName = filter_var($menuName, FILTER_SANITIZE_SPECIAL_CHARS);
    $menuId = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM menus WHERE menus.menu_name = :menuName";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":menuName", $menuName);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $menuId = (int) $stmt->fetch(PDO::FETCH_ASSOC)["menu_id"];
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $menuId;
  }

  /**
   * @return array -1 if failed, array of menu if successful
   */
  public static function getAllMenus(): array {
    $menus = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM menus;";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }

    return $menus;
  }

  public static function countNumberOfMenus(): int {
    $totalMenus = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT COUNT(*) FROM menus";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $totalMenus = $stmt->fetchColumn();
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }

    return $totalMenus;
  }

  public static function getAllMenusInRange(int $offset, int $limit): array {
    $menus = [];
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM menus LIMIT :limit OFFSET :offset;";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":offset", $offset);
      $stmt->bindValue(":limit", $limit);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }

      $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $menus;
  }

  public static function countNumberOfMenuPages(int $length): int {
    $numberOfMenus = static::countNumberOfMenus();
    return ($numberOfMenus % $length === 0) ? intdiv($numberOfMenus, $length) : (intdiv($numberOfMenus, $length) + 1);
  }

  public function update(): int {
    return MenuModel::updateByMenuId($this->menuId, $this->menuName, $this->description);
  }

  public function delete(): int {
    return MenuModel::deleteByMenuId($this->menuId);
  }

  public static function updateByMenuId(int &$menuId, string &$menuName, string &$description): int {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = '
        UPDATE menus
        SET menus.menu_name = :menuName,
            menus.description = :description
        WHERE menus.menu_id = :menuId;
      ';
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":menuId", $menuId);
      $stmt->bindValue(":menuName", $menuName);
      $stmt->bindValue(":description", $description);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
      
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $menuId = 0;
    }
    return $menuId;
  }

  public static function deleteByMenuId(int &$menuId): int {
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = 'DELETE FROM menus WHERE menus.menu_id = :menuId';
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":menuId", $menuId);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      App::getDatabaseConnection()->commit();
    } catch (BadQueryException | PDOException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
      $menuId = 0;
    }
    return $menuId;
  }
  
}


?>