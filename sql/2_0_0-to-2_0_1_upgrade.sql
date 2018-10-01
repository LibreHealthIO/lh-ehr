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
ALTER TABLE users ADD COLUMN menu_role varchar(100) NOT NULL default "Default User";
#EndIf

#IfMissingColumn users fullscreen_page
ALTER TABLE users ADD COLUMN fullscreen_page text NOT NULL;
#EndIf

#IfMissingColumn users fullscreen_enable
ALTER TABLE users ADD COLUMN fullscreen_enable int(11) NOT NULL default 0;
#EndIf

#IfMissingColumn users menu_role
ALTER TABLE users ADD COLUMN menu_role varchar(100) NOT NULL default "Default User";
#EndIf

#IfColumn users fullscreen_role
ALTER TABLE `users` DROP `fullscreen_role`;
#EndIf

DROP TABLE IF EXISTS `menu_trees`;

DROP TABLE IF EXISTS `menu_entries`;

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

#IfMissingColumn patient_data facility
ALTER TABLE patient_data ADD COLUMN facility INT(11) NOT NULL default '1' AFTER `DOB`;
#EndIf
#IfNotRow layout_options field_id facility
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`, `fld_rows`, `list_backup_id`, `source`, `conditions`) VALUES
('DEM', 'facility', '1Face Sheet', 'Facility', 32, 35, 1, 0, 0, '', 1, 1, '', '', '', 0, '', 'F', '');
#EndIf

--Appointment Cancellation Reasons
#IfNotRow2D list_options list_id lists option_id cancellation_reasons
INSERT INTO list_options ( list_id, option_id, title,activity ) VALUES ('lists','cancellation_reasons','Cancellation Reasons', 1);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('cancellation_reasons','1','No reason given',5,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('cancellation_reasons','2','Work',10,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('cancellation_reasons','3','Sick',20,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('cancellation_reasons','4','Weather',25,0);
#EndIf

#IfMissingColumn patient_tracker_element reason
ALTER TABLE `patient_tracker_element` ADD `reason` VARCHAR(255) NOT NULL AFTER `user`;
#EndIf

#IfMissingColumn users picture_url
ALTER TABLE `users` ADD `picture_url` VARCHAR(2000) NOT NULL AFTER `suffix`;
#EndIf

#IfNotRow2D list_options list_id apptstat option_id Deleted
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`, `subtype`) VALUES
 ('apptstat', 'Deleted', 'Deleted', 85, 0, 0, '', '0F0F0F|0', '', 0, 0, 1, '');
#Endif

#IfMissingColumn libreehr_postcalendar_events cancel_reason
  ALTER TABLE `libreehr_postcalendar_events` ADD `cancel_reason` text NOT NULL ;
#EndIf

#IfNotTable lims_analysisrequests
CREATE TABLE `lims_analysisrequests` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `procedure_order_id` int(11) NOT NULL COMMENT 'references procedure_order.procedure_order_id ',
 `analysisrequest_id` varchar(80) NOT NULL COMMENT 'refers to analysis request id in the lims',
 `status` text NOT NULL COMMENT 'received, processing, complete',
 PRIMARY KEY (`id`),
 UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
#EndIf

