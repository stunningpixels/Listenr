SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP SCHEMA IF EXISTS `listenr_test`;
CREATE SCHEMA `listenr_test` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE `listenr_test`.`client_status` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender` BIGINT(255) UNSIGNED NULL,
  `status` VARCHAR(255) DEFAULT NULL,
  `session` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `listenr_test`.`campaigns` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `listenr_test`.`values` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` VARCHAR(255) NULL,
  `campaign_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `values_ibfk_1_idx` (`campaign_id` ASC),
  CONSTRAINT `FK_campaign_id`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `listenr_test`.`campaigns` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE `listenr_test`.`queue` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender` BIGINT(255) UNSIGNED NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `campaign_id` INT(11) UNSIGNED NOT NULL,
  `value_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_q_values_id_idx` (`value_id` ASC),
  INDEX `FK_q_campaign_id_idx` (`campaign_id` ASC),
  CONSTRAINT `FK_q_campaign_id`
    FOREIGN KEY (`campaign_id`)
    REFERENCES `listenr_test`.`campaigns` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_q_value_id`
    FOREIGN KEY (`value_id`)
    REFERENCES `listenr_test`.`values` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);