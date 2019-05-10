--
--  Comment Meta Language Constructs:
--
--  #IfNotTable
--    argument: table_name
--    behavior: if the table_name does not exist,  the block will be executed

--  #IfTable
--    argument: table_name
--    behavior: if the table_name does exist, the block will be executed

--  #IfColumn
--    arguments: table_name colname
--    behavior:  if the table and column exist,  the block will be executed

--  #IfMissingColumn
--    arguments: table_name colname
--    behavior:  if the table exists but the column does not,  the block will be executed

--  #IfNotColumnType
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a column colname with a data type equal to value, then the block will be executed

--  #IfNotRow
--    arguments: table_name colname value
--    behavior:  If the table table_name does not have a row where colname = value, the block will be executed.

--  #IfNotRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfNotRow3D
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.

--  #IfNotRow4D
--    arguments: table_name colname value colname2 value2 colname3 value3 colname4 value4
--    behavior:  If the table table_name does not have a row where colname = value AND colname2 = value2 AND colname3 = value3 AND colname4 = value4, the block will be executed.

--  #IfNotRow2Dx2
--    desc:      This is a very specialized function to allow adding items to the list_options table to avoid both redundant option_id and title in each element.
--    arguments: table_name colname value colname2 value2 colname3 value3
--    behavior:  The block will be executed if both statements below are true:
--               1) The table table_name does not have a row where colname = value AND colname2 = value2.
--               2) The table table_name does not have a row where colname = value AND colname3 = value3.

--  #IfRow2D
--    arguments: table_name colname value colname2 value2
--    behavior:  If the table table_name does have a row where colname = value AND colname2 = value2, the block will be executed.

--  #IfRow3D
--        arguments: table_name colname value colname2 value2 colname3 value3
--        behavior:  If the table table_name does have a row where colname = value AND colname2 = value2 AND colname3 = value3, the block will be executed.

--  #IfIndex
--    desc:      This function is most often used for dropping of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the table and index exist the relevant statements are executed, otherwise not.

--  #IfNotIndex
--    desc:      This function will allow adding of indexes/keys.
--    arguments: table_name colname
--    behavior:  If the index does not exist, it will be created

--  #EndIf
--    all blocks are terminated with a #EndIf statement.

--  #IfNotListReaction
--    Custom function for creating Reaction List

--  #IfNotListOccupation
--    Custom function for creating Occupation List

--  #IfTextNullFixNeeded
--    desc: convert all text fields without default null to have default null.
--    arguments: none

--  #IfTableEngine
--    desc:      Execute SQL if the table has been created with given engine specified.
--    arguments: table_name engine
--    behavior:  Use when engine conversion requires more than one ALTER TABLE

--  #IfInnoDBMigrationNeeded
--    desc: find all MyISAM tables and convert them to InnoDB.
--    arguments: none
--    behavior: can take a long time.

#IfMissingColumn history_data risk_factors
ALTER TABLE `history_data` ADD COLUMN `risk_factors` TEXT NULL DEFAULT NULL AFTER `exams`;
#EndIf
DELETE FROM `code_types` WHERE `code_types`.`ct_key` = 'ICD9';
#IfMissingColumn patient_data picture_url
  ALTER TABLE `patient_data` ADD COLUMN `picture_url` VARCHAR(2000) NOT NULL AFTER `industry`;
#EndIf


#IfMissingColumn users menu_role
ALTER TABLE `users` ADD COLUMN `menu_role` varchar(100) NOT NULL default "Default User";
#EndIf

#IfMissingColumn users fullscreen_page
ALTER TABLE `users` ADD COLUMN `fullscreen_page` text NOT NULL;
#EndIf

#IfMissingColumn users fullscreen_enable
ALTER TABLE `users` ADD COLUMN `fullscreen_enable` int(11) NOT NULL default 0;
#EndIf

#IfMissingColumn users menu_role
ALTER TABLE `users` ADD COLUMN `menu_role` varchar(100) NOT NULL default "Default User";
#EndIf

#IfColumn users fullscreen_role
ALTER TABLE `users` DROP `fullscreen_role`;
#EndIf

#IfTable menu_trees
DROP TABLE IF EXISTS `menu_trees`;
#EndIf

#IfTable menu_entries
DROP TABLE IF EXISTS `menu_entries`;
#EndIf

#IfMissingColumn users picture_url
ALTER TABLE `users` ADD `picture_url` VARCHAR(2000) NOT NULL AFTER `suffix`;
#EndIf

#IfMissingColumn patient_data facility
ALTER TABLE patient_data ADD COLUMN facility INT(11) NOT NULL default '1' AFTER `DOB`;
#EndIf

#IfMissingColumn users created_at
  ALTER TABLE `users` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment 'needed for laravel' AFTER `picture_url`;
#EndIf

#IfMissingColumn users updated_at
  ALTER TABLE `users` ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'needed for laravel' AFTER `created_at` ;
#EndIf

#IfNotTable updater_users
CREATE TABLE `updater_users` (
  `authUserId` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB;
#EndIf

#IfNotTable updater_settings
CREATE TABLE `updater_settings` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB;
#EndIf

#IfNotTable updater_user_mode_download_entry
--
-- Table structure for table `updater_user_mode_download_entry`
--

CREATE TABLE `updater_user_mode_download_entry` (
  `filename` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `old_name` varchar(255) NOT NULL
) ENGINE=InnoDB;
#Endif

#IfNotTable updater_user_mode_backup_entry
--
-- Table structure for table `updater_user_mode_download_entry`
--

CREATE TABLE `updater_user_mode_backup_entry` (
  `filename` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `old_name` varchar(255) NOT NULL
) ENGINE=InnoDB;
#Endif

#IfMissingColumn form_misc_billing_options onset_date
 ALTER TABLE `form_misc_billing_options` ADD `onset_date` DATE NOT NULL ;
#EndIf

#IfMissingColumn libreehr_postcalendar_events case_number
 ALTER TABLE `libreehr_postcalendar_events` ADD `case_number` VARCHAR(50) DEFAULT NULL;
#EndIf

#IfMissingColumn libreehr_postcalendar_events case_body_part
 ALTER TABLE `libreehr_postcalendar_events` ADD `case_body_part` VARCHAR(50) DEFAULT NULL;
#EndIf

ALTER TABLE `list_options`
CHANGE `list_id` `list_id` varchar(50) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '',
CHANGE `option_id` `option_id` varchar(50) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '';

--Appointment Cancellation Reasons
#IfNotRow2D list_options list_id lists option_id cancellation_reasons
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`,`activity` ) VALUES ('lists','cancellation_reasons','Cancellation Reasons', 1);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('cancellation_reasons','1','No reason given',5,0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('cancellation_reasons','2','Work',10,0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('cancellation_reasons','3','Sick',20,0);
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('cancellation_reasons','4','Weather',25,0);
#EndIf

#IfMissingColumn patient_tracker_element reason
ALTER TABLE `patient_tracker_element` ADD `reason` VARCHAR(255) NOT NULL AFTER `user`;
#EndIf

#IfMissingColumn users picture_url
ALTER TABLE `users` ADD `picture_url` VARCHAR(2000) NOT NULL AFTER `suffix`;
#EndIf

#IfNotRow layout_options field_id facility
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`, `fld_rows`, `list_backup_id`, `source`, `conditions`) VALUES
('DEM', 'facility', '1Face Sheet', 'Facility', 32, 35, 1, 0, 0, '', 1, 1, '', '', '', 0, '', 'F', '');
#EndIf

#IfMissingColumn form_enhanced_prior_auth bodypart
 ALTER TABLE `form_enhanced_prior_auth` ADD `bodypart` VARCHAR(120) NOT NULL ;
#EndIf

#IfMissingColumn libreehr_postcalendar_events bodypart
  ALTER TABLE `libreehr_postcalendar_events` ADD `bodypart` VARCHAR(120) NOT NULL ;
#EndIf

#IfColumn form_enhanced_prior_auth ddesc
 ALTER TABLE `form_enhanced_prior_auth` CHANGE `ddesc` `ddesc` VARCHAR(255) NOT NULL ;
#ENDIF

#IfNotRow2D list_options list_id apptstat option_id Deleted
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`, `subtype`) VALUES
 ('apptstat', 'Deleted', 'Deleted', 85, 0, 0, '', '0F0F0F|0', '', 0, 0, 1, '');
#Endif

#IfMissingColumn patient_data nickname
  ALTER TABLE `patient_data` ADD COLUMN `nickname` text  NOT NULL  AFTER `industry`;
#EndIf

#IfMissingColumn libreehr_postcalendar_events cancel_reason
  ALTER TABLE `libreehr_postcalendar_events` ADD `cancel_reason` text NOT NULL ;
#EndIf

#IfMissingColumn form_enhanced_prior_auth addused
 ALTER TABLE `form_enhanced_prior_auth` ADD `addused` INT(10) NULL AFTER `code7` ;
#EndIf

#IfMissingColumn log_form_enhanced_prior_auth addused
  ALTER TABLE `log_form_enhanced_prior_auth` ADD `addused` INT(10) NULL AFTER `code7` ;
#EndIf

#IfMissingColumn libreehr_postcalendar_events bodypart_2
  ALTER TABLE `libreehr_postcalendar_events` ADD `bodypart_2` VARCHAR(120) NOT NULL ;
#EndIf

#IfMissingColumn libreehr_postcalendar_events prior_auth_2
 ALTER TABLE `libreehr_postcalendar_events` ADD `prior_auth_2` VARCHAR(50) DEFAULT NULL;
#EndIf

#IfMissingColumn libreehr_postcalendar_events prior_auth
 ALTER TABLE `libreehr_postcalendar_events` ADD `prior_auth` VARCHAR(50) DEFAULT NULL;
#EndIf

#IfMissingColumn insurance_companies tier
 ALTER TABLE `insurance_companies` ADD `tier` VARCHAR(5) DEFAULT NULL;
#EndIf

#IfMissingColumn insurance_companies ins_co_initials
 ALTER TABLE `insurance_companies` ADD `ins_co_initials` VARCHAR(5) DEFAULT NULL;
#EndIf

#IfNotTable lims_analysisrequests
CREATE TABLE `lims_analysisrequests` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `procedure_order_id` int(11) NOT NULL COMMENT 'references procedure_order.procedure_order_id ',
 `analysisrequest_id` varchar(80) NOT NULL COMMENT 'refers to analysis request id in the lims',
 `status` text NOT NULL COMMENT 'received, processing, complete',
 PRIMARY KEY (`id`),
 UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
#Endif

#IfMissingColumn libreehr_postcalendar_categories pc_categories_icon
  ALTER TABLE `libreehr_postcalendar_categories` ADD `pc_categories_icon` VARCHAR(100) NOT NULL ;
#EndIf

#IfMissingColumn log_form_enhanced_prior_auth temp_auth
  ALTER TABLE `log_form_enhanced_prior_auth` ADD `temp_auth` TINYINT(1) ;
#EndIf

#IfMissingColumn log_form_enhanced_prior_auth primary_auth
  ALTER TABLE `log_form_enhanced_prior_auth` ADD `primary_auth` TINYINT(1) ;
#EndIf

#IfMissingColumn log_form_enhanced_prior_auth secondary_auth
  ALTER TABLE `log_form_enhanced_prior_auth` ADD `secondary_auth` TINYINT(1) ;
#EndIf

#IfMissingColumn form_enhanced_prior_auth temp_auth
  ALTER TABLE `form_enhanced_prior_auth` ADD `temp_auth` TINYINT(1) ;
#EndIf

#IfMissingColumn form_enhanced_prior_auth primary_auth
  ALTER TABLE `form_enhanced_prior_auth` ADD `primary_auth` TINYINT(1) ;
#EndIf

#IfMissingColumn form_enhanced_prior_auth secondary_auth
  ALTER TABLE `form_enhanced_prior_auth` ADD `secondary_auth` TINYINT(1) ;
#EndIf

#IfNotTable pt_accident_claim
  CREATE TABLE `pt_accident_claim` (
    `pac_id` INT (20) NOT NULL AUTO_INCREMENT,
    `pac_case_number` INT (20) NOT NULL COMMENT 'case_number in pt_case table',
    `pac_pid` INT (20) NOT NULL COMMENT 'patient_data.pid',
    `pac_claim_type` varchar(20) NOT NULL COMMENT 'Auto accident or Workers Comp',
    `pac_state` varchar(30) DEFAULT NULL COMMENT 'State where accident occurred for auto accident',
    `pac_provider_name` varchar (60) DEFAULT NULL COMMENT 'MCO Provider/Auto Insurance Name',
    `pac_claim_no` varchar (40) DEFAULT NULL COMMENT 'MCO Provider/Auto Insurance Claim Number',
    `pac_rep_name` varchar (60) DEFAULT NULL COMMENT 'MCO Provider/Auto Insurance Rep name',
    `pac_phone` varchar (15) DEFAULT NULL COMMENT 'MCO Provider/Auto Insurance Rep contact number',
    `pac_notes` varchar (255) DEFAULT NULL COMMENT 'accident claim notes',
    `pac_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `pac_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`pac_id`)
  ) ENGINE = INNODB CHARSET = utf8 COLLATE = utf8_unicode_ci;
#Endif

#IfTable pt_case_info
SET FOREIGN_KEY_CHECKS = 0;
-- DROP TABLE IF EXISTS `pt_case_info`;
SET FOREIGN_KEY_CHECKS = 1;
#EndIf

#IfNotTable pt_case_info
  SET FOREIGN_KEY_CHECKS = 0;
  CREATE TABLE `pt_case_info` (
    `pci_id` INT (20) NOT NULL AUTO_INCREMENT,
    `pci_case_number` INT (20) NOT NULL COMMENT 'case_number in pt_case table',
    `pci_case_injury_type` text COMMENT 'injury type from patient intake',
    `pci_case_injury_date` date COMMENT 'date of injury',
    `pci_insurance_data_id` INT(20) DEFAULT NULL,
    `pci_case_auto_accident` TINYINT (1) DEFAULT NULL COMMENT 'Is this an auto accident case? 1=yes',
    `pci_case_workers_comp` TINYINT (1) DEFAULT NULL COMMENT 'Is this a workers comp case? 1=yes',
    `pci_case_accident_claim_id` INT (20) DEFAULT NULL COMMENT 'References pt_accident_claim.pac_id',
    `pci_surgery` TINYINT (1) DEFAULT NULL COMMENT 'Did the patient have recent surgery?',
    `pci_surgery_date` DATE DEFAULT NULL COMMENT 'date of the surgery',
    `pci_note` varchar(255) DEFAULT NULL,
    `pci_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `pci_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`pci_id`),
    KEY `pci_case_number` (`pci_case_number`),
    CONSTRAINT `pci_fk_case_number` FOREIGN KEY (`pci_case_number`) REFERENCES `pt_case` (`case_number`) ON DELETE NO ACTION ON UPDATE NO ACTION
  ) ENGINE = INNODB CHARSET = utf8 COLLATE = utf8_unicode_ci;
  SET FOREIGN_KEY_CHECKS = 1;
#Endif

#IfTable pt_case
SET FOREIGN_KEY_CHECKS = 0;
-- DROP TABLE IF EXISTS `pt_case`;
SET FOREIGN_KEY_CHECKS = 1;
#EndIf

#IfNotTable pt_case
  SET FOREIGN_KEY_CHECKS = 0;
  CREATE TABLE `pt_case` (
    `case_number` INT (20) AUTO_INCREMENT NOT NULL,
    `pc_patient_id` INT (11) NOT NULL COMMENT 'patient_data.pid',
    `pc_case_status` enum ('OPEN', 'CLOSED', 'CANCELED') CHARSET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OPEN',
    `pc_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `pc_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`case_number`)
  ) ENGINE = INNODB CHARSET = utf8 COLLATE = utf8_unicode_ci;
  SET FOREIGN_KEY_CHECKS = 1;
  ALTER TABLE `pt_case` AUTO_INCREMENT = 20000;
#EndIf
#IfMissingColumn patient_data pref_facility_id
  ALTER TABLE `patient_data` ADD COLUMN `pref_facility_id` INT(5) NULL COMMENT 'patient preferred treatment facility id from db.facility' AFTER `patient_pref_schd`;
#Endif

#IfMissingColumn form_encounter case_number
  ALTER TABLE `form_encounter` ADD COLUMN `case_number` INT (20) NOT NULL COMMENT 'case_number in pt_case table' AFTER `seq_number`;
#EndIf

#IfMissingColumn form_encounter case_body_part
  ALTER TABLE `form_encounter` ADD COLUMN `case_body_part` varchar(25) NOT NULL COMMENT 'case body part for injury_type' AFTER `case_number`;
#EndIf
#IfColumn form_enhanced_prior_auth case_number
  ALTER TABLE `form_enhanced_prior_auth` CHANGE `case_number` `case_number` INT (20) NULL ;
#ENDIF

#IfColumn log_form_enhanced_prior_auth case_number
  ALTER TABLE `log_form_enhanced_prior_auth` CHANGE `case_number` `case_number` INT (20) NULL ;
#ENDIF

#IfMissingColumn insurance_data case_number
  ALTER TABLE `insurance_data` ADD COLUMN `case_number` INT (20) NULL COMMENT 'case_number in pt_case table' AFTER `id`;
#EndIf

#IfMissingColumn billing case_number
  ALTER TABLE `billing` ADD COLUMN `case_number` INT (20) NULL COMMENT 'case_number in pt_case table' AFTER `id`;
#EndIf
#IfMissingColumn patient_data contact_pref
ALTER TABLE `patient_data` ADD COLUMN `contact_pref` varchar (10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Patient Contact Preference - email, text, phone call' AFTER `email_direct`;
#Endif

#IfMissingColumn insurance_data case_info_id
ALTER TABLE insurance_data ADD COLUMN `case_info_id` int (20) NULL COMMENT 'pci_id in pt_case_info' AFTER `case_number`;
#Endif

#IfMissingColumn patient_data work_status
  ALTER TABLE `patient_data` ADD COLUMN `work_status` varchar(25) NOT NULL  AFTER `patient_pref_schd`;
#EndIf


#IfMissingColumn patient_data pcpID
  ALTER TABLE `patient_data` ADD COLUMN `pcpID` varchar(25) NOT NULL  AFTER `work_status`;
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2018-10-01 load_filename 2019-ICD-10-CM-Code-Descriptions.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2018-10-01', '2019-ICD-10-CM-Code-Descriptions.zip', 'b23e0128eb2dce0cb007c31638a8dc00');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2018-10-01 load_filename 2019-ICD-10-PCS-Order-File.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2018-10-01', '2019-ICD-10-PCS-Order-File.zip', 'eb545fe61ada9efad0ad97a669f8671f');
#Endif

#IfTable libreehr_modules
DROP TABLE IF EXISTS `libreehr_modules`;
#EndIf

#IfTable libreehr_module_vars
DROP TABLE IF EXISTS `libreehr_module_vars`;
#EndIf

#IfTable libreehr_postcalendar_topics
DROP TABLE IF EXISTS `libreehr_postcalendar_topics`;
#EndIf

#IfTable libreehr_postcalendar_limits
DROP TABLE IF EXISTS `libreehr_postcalendar_limits`;
#EndIf
