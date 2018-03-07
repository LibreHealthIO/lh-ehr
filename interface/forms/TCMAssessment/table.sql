SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `form_TCMAssessment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `form_TCMAssessment` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Type` ENUM('INITIAL','UPDATE') NOT NULL DEFAULT 'INITIAL' ,
  `pid` BIGINT UNSIGNED NOT NULL COMMENT 'ClientId' ,
  `ClientName` VARCHAR(255) NULL DEFAULT NULL ,
  `MedicaidId` VARCHAR(255) NULL DEFAULT NULL ,
  `AdmitDate` VARCHAR(255) NULL DEFAULT NULL ,
  `HomeVisitDates` VARCHAR(255) NULL DEFAULT NULL ,
  `user` VARCHAR(255) NOT NULL COMMENT 'CaseManagerId' ,
  `CaseManagerName` VARCHAR(255) NULL ,
  `groupname` VARCHAR(255) NULL DEFAULT NULL ,
  `CaseManagerSupervisorId` VARCHAR(255) NOT NULL ,
  `CaseManagerSupervisorName` VARCHAR(255) NULL ,
  `FamilyHistory` TEXT NULL DEFAULT NULL ,
  `VocationalHistory` TEXT NULL DEFAULT NULL ,
  `LegalDependencyHistory` TEXT NULL DEFAULT NULL ,
  `SignificantRelationships` TEXT NULL DEFAULT NULL ,
  `CurrentPotentialStrengthsClient` TEXT NULL DEFAULT NULL ,
  `CurrentPotentialStrengthsClientViaParent` TEXT NULL DEFAULT NULL ,
  `CurrentPotentialStrengthsFamily` TEXT NULL DEFAULT NULL ,
  `ResourcesAvailable` TEXT NULL DEFAULT NULL ,
  `SafetyAssessmentOther` VARCHAR(255) NULL DEFAULT NULL ,
  `SafetyAssessmentDescription` TEXT NULL DEFAULT NULL ,
  `SafetyAssessmentPlan` TEXT NULL DEFAULT NULL ,
  `MedicalHistoryNotes` TEXT NULL ,
  `authorized` TINYINT(4) UNSIGNED NULL ,
  `activity` ENUM('0','1') NOT NULL DEFAULT '1' ,
  `date` DATETIME NOT NULL ,
  `ManagerNote` TEXT NULL ,
  `CaseManagerSignatureDate` DATETIME NULL DEFAULT NULL ,
  `CaseManagerSupervisorSignatureDate` DATETIME NULL DEFAULT NULL ,
  `TreatmentHistoryNotes` TEXT NULL DEFAULT NULL,
  `MedicationsCurrentNotes` TEXT NULL DEFAULT NULL,
  `MedicationsPastNotes` TEXT NULL DEFAULT NULL, 
  `EducationalHistoryNotes` TEXT NULL DEFAULT NULL,
  `SubstanceAbuseNotes` TEXT NULL DEFAULT NULL,
  `CurrentCircumstances` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMASources`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMASources` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `Type` VARCHAR(45) NULL DEFAULT NULL ,
  `Date` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMASourcesLink`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMASourcesLink` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMASourcesId` BIGINT(20) UNSIGNED NOT NULL ,
  `TCMAssessmentField` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMATreatmentHistory`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMATreatmentHistory` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `Provider` VARCHAR(45) NULL DEFAULT NULL ,
  `Dates` VARCHAR(45) NULL DEFAULT NULL ,
  `Type` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMAMedicalHistory`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMAMedicalHistory` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `Type` VARCHAR(45) NULL DEFAULT NULL ,
  `Provider` VARCHAR(45) NULL DEFAULT NULL ,
  `LastExam` VARCHAR(45) NULL DEFAULT NULL ,
  `Findings` VARCHAR(45) NULL DEFAULT NULL ,
  `KnownConditions` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMAEducationalHistory`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMAEducationalHistory` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `School` VARCHAR(45) NULL DEFAULT NULL ,
  `Grades` VARCHAR(45) NULL DEFAULT NULL ,
  `Type` VARCHAR(45) NULL DEFAULT NULL ,
  `IEP504Plan` VARCHAR(45) NULL DEFAULT NULL ,
  `Performance` VARCHAR(45) NULL DEFAULT NULL ,
  `Behavior` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMASubstanceAbuse`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMASubstanceAbuse` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `Substance` VARCHAR(45) NULL DEFAULT NULL ,
  `Frequency` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMASafetyTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMASafetyTypes` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Label` VARCHAR(45) NOT NULL ,
  `Priority` TINYINT(3) UNSIGNED NULL DEFAULT NULL ,
  `Disabled` ENUM('0','1') NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMASafety`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMASafety` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `TypeId` BIGINT(20) UNSIGNED NOT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMACurrentServicesTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMACurrentServicesTypes` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Label` VARCHAR(45) NOT NULL ,
  `Priority` TINYINT(3) UNSIGNED NULL DEFAULT NULL ,
  `Disabled` ENUM('0','1') NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMACurrentServices`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMACurrentServices` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `Provider` VARCHAR(45) NULL DEFAULT NULL ,
  `DateBegin` VARCHAR(45) NULL DEFAULT NULL ,
  `TypeId` BIGINT(20) UNSIGNED NOT NULL ,
  `TypeOther` VARCHAR(255) NULL ,
  `Effectiveness` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMAFunctionalTypes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMAFunctionalTypes` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `Label` VARCHAR(45) NOT NULL ,
  `Description` VARCHAR(255) NULL DEFAULT NULL ,
  `Priority` TINYINT(3) UNSIGNED NULL DEFAULT NULL ,
  `Disabled` ENUM('0','1') NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TCMAFunctional`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TCMAFunctional` (
  `Id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `TCMAssessmentId` BIGINT(20) UNSIGNED NOT NULL ,
  `TypeId` BIGINT(20) UNSIGNED NOT NULL ,
  `ServiceNeeds` ENUM('no','some','immediate') NOT NULL DEFAULT 'no' ,
  `GoalWillingness` ENUM('0','1') NOT NULL DEFAULT '0' ,
  `NeedSummary` TEXT NULL DEFAULT NULL ,
  `ActivityRecommendations` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `TCMASafetyTypes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'No Current Risk Factors', 1, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Running Away', 2, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Verbal Aggression', 3, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Prior Inpatient Treatment', 4, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Suicidal Ideation or Attempts', 5, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Physical Aggression', 6, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Unsafe Home Environment', 7, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Self-Injurious Behavior', 8, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Threats to Harm Others', 9, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Sexual Acting Out', 10, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Impulsive/Dangerous Behaviors', 11, '0');
INSERT INTO `TCMASafetyTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Homicidial Ideation or Attempts', 12, '0');

COMMIT;

-- -----------------------------------------------------
-- Data for table `TCMACurrentServicesTypes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `TCMACurrentServicesTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Targeted Case Management', 1, '0');
INSERT INTO `TCMACurrentServicesTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Counseling', 2, '0');
INSERT INTO `TCMACurrentServicesTypes` (`Id`, `Label`, `Priority`, `Disabled`) VALUES (NULL, 'Psychiatric', 3, '0');

COMMIT;

-- -----------------------------------------------------
-- Data for table `TCMAFunctionalTypes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Mental Health Services', 'psychiatric, counseling, substance abuse treatment, residential treatment', 1, '0');
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Family Support / Family Education', 'support services or educational needs related to client\'s treatment', 2, '0');
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Educational', 'services/placement, vocational testing, job training, or education-related therapies', 3, '0');
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Basic Needs', 'housing, food, clothing and transportation', 4, '0');
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Financial', 'entitlements, assistance, employment', 5, '0');
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Health Care', 'medical, dental, vision', 6, '0');
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Legal', 'probation, competency evaluations, child welfare, custody/parenting plans', 7, '0');
INSERT INTO `TCMAFunctionalTypes` (`Id`, `Label`, `Description`, `Priority`, `Disabled`) VALUES (NULL, 'Environmental Support', 'for the purpose of development of a natural support system (extra-curricular activities, support groups, community, activities, networking with other families)', 8, '0');

COMMIT;
