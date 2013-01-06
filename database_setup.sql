SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `pharmaQR` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `pharmaQR` ;


-- -----------------------------------------------------
-- Table `pharmaQR`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pharmaQR`.`account_type` ;

CREATE  TABLE IF NOT EXISTS `pharmaQR`.`account_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `account_type` VARCHAR(40) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pharmaQR`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pharmaQR`.`users` ;

CREATE  TABLE IF NOT EXISTS `pharmaQR`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `OHIP` VARCHAR(40) NOT NULL,
  `first_name` VARCHAR(40) NOT NULL ,
  `last_name` VARCHAR(40) NOT NULL,
  `account_type_id`  INT NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  UNIQUE(`id`),
  PRIMARY KEY (`OHIP`), 
  FOREIGN KEY (`account_type_id` )
  REFERENCES `pharmaQR`.`account_type` (`id` ) )
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pharmaQR`.`prescriptions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pharmaQR`.`prescriptions` ;

CREATE  TABLE IF NOT EXISTS `pharmaQR`.`prescriptions` (
  `presc_id` INT NOT NULL AUTO_INCREMENT ,
  `drug_name` VARCHAR(75) NOT NULL ,
  `user_id` INT NOT NULL ,
  `doctor_id` VARCHAR(75) NOT NULL ,
  `qrcode` LONGBLOB NOT NULL,
  `note` varchar (500),
  `date` date,
  `refills` int,
  `times_filled` int, 
  PRIMARY KEY (`id`) ) 
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `pharmaQR`.`account_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `pharmaQR`;
INSERT INTO `pharmaQR`.`account_type` (`id`, `account_type`) VALUES (NULL, 'Doctor');
INSERT INTO `pharmaQR`.`account_type` (`id`, `account_type`) VALUES (NULL, 'Patient');
INSERT INTO `pharmaQR`.`account_type` (`id`, `account_type`) VALUES (NULL, 'Pharmacist');
COMMIT;
