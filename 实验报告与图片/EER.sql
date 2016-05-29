-- MySQL Script generated by MySQL Workbench
-- Sat May 28 03:34:48 2016
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` (16) NOT NULL,
  `email` (255) NOT NULL,
  `password` (32) NOT NULL,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC));


-- -----------------------------------------------------
-- Table `mydb`.`password_resets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`password_resets` (
  `email` CHAR(255) NOT NULL,
  `token` CHAR(255) NOT NULL,
  PRIMARY KEY (`email`, `token`));


-- -----------------------------------------------------
-- Table `mydb`.`event_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`event_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` CHAR(100) NOT NULL,
  `parent_event_type_id` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_event_types_event_types_idx` (`parent_event_type_id` ASC),
  CONSTRAINT `fk_event_types_event_types`
    FOREIGN KEY (`parent_event_type_id`)
    REFERENCES `mydb`.`event_types` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT);


-- -----------------------------------------------------
-- Table `mydb`.`user_event_types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user_event_types` (
  `user_id` INT UNSIGNED NOT NULL,
  `event_types_id` INT UNSIGNED NOT NULL,
  `event_type_name` CHAR(100) NULL,
  `event_name` CHAR(100) NULL,
  PRIMARY KEY (`user_id`, `event_types_id`),
  INDEX `fk_user_event_types_event_types1_idx` (`event_types_id` ASC),
  CONSTRAINT `fk_user_event_types_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_event_types_event_types1`
    FOREIGN KEY (`event_types_id`)
    REFERENCES `mydb`.`event_types` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT);


-- -----------------------------------------------------
-- Table `mydb`.`events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_type_id` INT UNSIGNED NOT NULL,
  `note` CHAR(100) NULL,
  `host_user_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_events_event_types1_idx` (`event_type_id` ASC),
  INDEX `fk_events_users1_idx` (`host_user_id` ASC),
  CONSTRAINT `fk_events_event_types1`
    FOREIGN KEY (`event_type_id`)
    REFERENCES `mydb`.`event_types` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_events_users1`
    FOREIGN KEY (`host_user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table `mydb`.`user_now_events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user_now_events` (
  `user_id` INT UNSIGNED NOT NULL,
  `event_id` INT UNSIGNED NOT NULL,
  `event_name` CHAR(100) NULL,
  INDEX `fk_user_now_events_events1_idx` (`event_id` ASC),
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
  CONSTRAINT `fk_user_now_events_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_now_events_events1`
    FOREIGN KEY (`event_id`)
    REFERENCES `mydb`.`events` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT);


-- -----------------------------------------------------
-- Table `mydb`.`user_friends`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user_friends` (
  `user_id` INT UNSIGNED NOT NULL,
  `friend_user_id` INT UNSIGNED NOT NULL,
  `confirmed` TINYINT(1) NOT NULL,
  INDEX `fk_user_friends_users1_idx` (`user_id` ASC),
  INDEX `fk_user_friends_users2_idx` (`friend_user_id` ASC),
  PRIMARY KEY (`user_id`, `friend_user_id`),
  CONSTRAINT `fk_user_friends_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_friends_users2`
    FOREIGN KEY (`friend_user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table `mydb`.`user_events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user_events` (
  `user_id` INT UNSIGNED NOT NULL,
  `event_id` INT UNSIGNED NOT NULL,
  `event_name` CHAR(100) NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME NULL,
  INDEX `fk_user_events_users1_idx` (`user_id` ASC),
  INDEX `fk_user_events_events1_idx` (`event_id` ASC),
  PRIMARY KEY (`user_id`, `event_id`),
  INDEX `date` (`start_time` ASC),
  CONSTRAINT `fk_user_events_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_events_events1`
    FOREIGN KEY (`event_id`)
    REFERENCES `mydb`.`events` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT);


-- -----------------------------------------------------
-- Table `mydb`.`user_friend_requests`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user_friend_requests` (
  `user_id` INT UNSIGNED NOT NULL,
  `request_user_id` INT UNSIGNED NOT NULL,
  `confirmed` TINYINT(1) NOT NULL,
  INDEX `fk_user_friend_requests_users2_idx` (`request_user_id` ASC),
  PRIMARY KEY (`user_id`, `request_user_id`),
  CONSTRAINT `fk_user_friend_requests_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_friend_requests_users2`
    FOREIGN KEY (`request_user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table `mydb`.`user_invitations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user_invitations` (
  `event_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  INDEX `fk_user_invitations_events1_idx` (`event_id` ASC),
  INDEX `fk_user_invitations_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_invitations_events1`
    FOREIGN KEY (`event_id`)
    REFERENCES `mydb`.`events` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_user_invitations_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
