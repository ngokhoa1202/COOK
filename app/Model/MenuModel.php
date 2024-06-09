<?php

declare(strict_types=1);
namespace App\Model;

use App\App;
use App\Exception\BadQueryException;
use App\Exception\BadRequestException;
use PDO;
use PDOException;

class MenuModel extends Model {
  protected int $menuId = 0;
  protected string $menuName;
  protected string $description;

  public function __construct() {
    parent::__construct();
    $this->menuId = 0;
    $this->menuName = "";
    $this->description = "";
  }

  /**
   * @return string
   */
  public function getMenuName(): string {
    return $this->menuName;
  }

  /**
   * @param string $menuName
   */
  public function setMenuName(string $menuName): void {
    $this->menuName = $menuName;
  }

  /**
   * @return string
   */
  public function getDescription(): string {
    return $this->description;
  }

  /**
   * @param string $description
   */
  public function setDescription(string $description): void {
    $this->description = $description;
  }



  public static function make(string $menuName = "", string $description = "", int $menuId = 0): MenuModel {
    
    return new MenuModel($menuName, $description, $menuId);
  }

  /**
   * Insert a menu into table menu with its menuName and description
   *
   * @return array contains menu information
   */
  public function create(): array {
    $menu = [];
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
      $menu = (array) $this;
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      $this->menuId = 0;
    }
    return $menu;
  }

  /**
   * @return int
   */
  public function getMenuId(): int {
    return $this->menuId;
  }

  /**
   * Find meny bu menu name
   *
   * @param string $menuName
   * @return array contains a menu
   */
  public static function findMenuByMenuName(string &$menuName): array {
    $menu = null;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM menus WHERE menus.menu_name = :menuName";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":menuName", $menuName);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $menu = $stmt->fetch(PDO::FETCH_ASSOC);
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $menu;
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

  public static function countNumberOfMenuPages(): int {
    $totalCategories = 0;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT COUNT(*) FROM categories;";
      $stmt = App::getDatabaseConnection()->prepare($query);
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }
      $totalCategories = $stmt->fetchColumn();
      App::getDatabaseConnection()->commit();
    } catch (PDOException | BadQueryException $ex) {
      if (App::getDatabaseConnection()->inTransaction()) {
        App::getDatabaseConnection()->rollBack();
      }
    }
    return $totalCategories;
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
}


?>