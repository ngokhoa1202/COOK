ALTER TABLE `menus` CHANGE `description` `description` VARCHAR(1022) NULL DEFAULT NULL;

ALTER TABLE categories ADD category_name varchar(255) AFTER menu_id;

ALTER TABLE `categories` CHANGE `description` `description` VARCHAR(1022) NULL DEFAULT NULL;

ALTER TABLE `types` CHANGE `description` `description` VARCHAR(1022) NULL DEFAULT NULL;

ALTER TABLE `types` ADD `type_name` VARCHAR(255) NULL DEFAULT NULL AFTER `category_id`;

-- ALTER TABLE `categories` DROP CONSTRAINT category_name;

ALTER TABLE `categories` CHANGE COLUMN `category_name` `category_name` VARCHAR(255) AFTER menu_id;

ALTER TABLE `types` CHANGE `description` `description` VARCHAR(1022) NULL DEFAULT NULL;

ALTER TABLE categories ADD CONSTRAINT UC_categories UNIQUE (menu_id, category_name);