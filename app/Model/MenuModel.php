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

  private function __construct(string $menuName, string $description) {
    parent::__construct();
    $this->menuName = $menuName;
    $this->description = $description;
  }

  public static function make(string $menuName, string $description): MenuModel {
    $menuName = htmlspecialchars($menuName);
    $description = htmlspecialchars($description);
    return new MenuModel($menuName, $description);
  }

  /**
   * Insert a menu INTO table menu with its menuName and description
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
      if (!$stmt->execute()) {
        throw new BadQueryException();
      }

      $this->database->commit();
    } catch (PDOException | BadQueryException $ex) {
      if ($this->database->inTransaction()) {
        $this->database->rollBack();
      }
      return -1;
    }
    $this->menuId = (int) $this->database->lastInsertId();
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
  public static function findMenuIdByName(string $menuName): int {
    $menuId = -1;
    try {
      App::getDatabaseConnection()->beginTransaction();
      $query = "SELECT * FROM menus WHERE menus.menu_name = :menuName";
      $stmt = App::getDatabaseConnection()->prepare($query);
      $stmt->bindValue(":menuName", $menuName);
      if (! $stmt->execute()) {
        throw new BadQueryException();
      }
      $menu = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
      $menuId = $menu["menu_id"];
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

  public static function getNumberOfMenus(): int {
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


}


?>