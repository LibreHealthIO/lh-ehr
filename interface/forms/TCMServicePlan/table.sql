SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `form_TCMServicePlan`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `form_TCMServicePlan` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `TCMServicePlanId` BIGINT(20) UNSIGNED NULL ,
  `Type` ENUM('INITIAL','UPDATE') NULL ,
  `pid` BIGINT NOT NULL ,
  `ClientName` VARCHAR(255) NULL ,
  `MedicaidId` VARCHAR(255) NULL ,
  `DateWritten` VARCHAR(255) NULL DEFAULT NULL ,
  `DateComplete` VARCHAR(255) NULL DEFAULT NULL ,
  `user` VARCHAR(255) NOT NULL ,
  `CaseManagerName` VARCHAR(255) NULL ,
  `groupname` VARCHAR(255) NULL ,
  `CaseManagerSupervisorId` VARCHAR(255) NULL ,
  `CaseManagerSupervisorName` VARCHAR(255) NULL ,
  `DiagnosisSource` TEXT NULL ,
  `CurrentServiceNeeds` TEXT NULL ,
  `DischargePlan` MEDIUMTEXT NOT NULL,
  `authorized` TINYINT(4) NULL ,
  `activity` ENUM('0','1') NOT NULL ,
  `date` DATETIME NOT NULL ,
  `ManagerNote` TEXT NULL ,
  `FinalizedDate` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMSPDiagnosis`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMSPDiagnosis` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMServicePlanId` BIGINT(20) UNSIGNED NOT NULL ,
  `ListsId` BIGINT(20) UNSIGNED NULL ,
  `ICD` TINYINT NULL ,
  `Axis` ENUM('i','ii','iii','iv','v') NULL ,
  `Code` VARCHAR(45) NULL ,
  `Description` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `BehavioralHealthProblems`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `BehavioralHealthProblems` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `PlanType` VARCHAR(45) NOT NULL ,
  `PlanId` BIGINT(20) UNSIGNED NOT NULL ,
  `Area` VARCHAR(45) NULL ,
  `AreaId` BIGINT(20) UNSIGNED NOT NULL ,
  `Problem` TEXT NULL ,
  `Activities` TEXT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `BehavioralHealthGoals`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `BehavioralHealthGoals` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `BehavioralHealthProblemId` BIGINT(20) UNSIGNED NOT NULL ,
  `Goal` TEXT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `BehavioralHealthObjectives`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `BehavioralHealthObjectives` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `BehavioralHealthGoalId` BIGINT(20) UNSIGNED NOT NULL ,
  `Objective` TEXT NULL ,
  `TargetDate` VARCHAR(45) NULL ,
  `ProgressRate` TINYINT NULL ,
  `Status` ENUM('new','ongoing','deferred','achieved') NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `BehavioralHealthAgents`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `BehavioralHealthAgents` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `BehavioralHealthProblemId` BIGINT(20) UNSIGNED NOT NULL ,
  `Type` BIGINT(20) UNSIGNED NULL ,
  `TypeOther` VARCHAR(45) NULL ,
  `Agency` VARCHAR(255) NULL ,
  `Agent` VARCHAR(255) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `BehavioralHealthAgentsTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `BehavioralHealthAgentsTypes` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Label` VARCHAR(45) NOT NULL ,
  `Priority` TINYINT(3) UNSIGNED NULL DEFAULT NULL ,
  `Disabled` ENUM('0','1') NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `BehavioralHealthAgentsTypes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `BehavioralHealthAgentsTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Targeted Case Manager', 1, '0');
INSERT INTO `BehavioralHealthAgentsTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Psychiatric Provider', 2, '0');
INSERT INTO `BehavioralHealthAgentsTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Client', 3, '0');
INSERT INTO `BehavioralHealthAgentsTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Caregiver', 4, '0');
INSERT INTO `BehavioralHealthAgentsTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Counselor', 5, '0');
INSERT INTO `BehavioralHealthAgentsTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Child Welfare Case Manager', 6, '0');

COMMIT;
