ALTER TABLE `elitesleeper_country` ADD `status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Active' AFTER `value`;

UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'belarus';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'burundi';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'central african republic';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'democratic republic of the congo';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'ukraine';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'cuba';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'iran';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'lebanon';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'libya';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'north korea';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'russia';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'somalia';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'south sudan';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'sudan';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'syria';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'venezuela';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'yemen';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'zimbabwe';

UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'iraq';
UPDATE `elitesleeper_country` SET `status` = 'Inactive' WHERE LOWER(`elitesleeper_country`.`name`) = 'liberia';
