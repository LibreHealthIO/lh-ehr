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

#IfMissingColumn insurance_data inactive_time
ALTER TABLE insurance_data ADD COLUMN inactive_time datetime DEFAULT NULL;
#EndIf

#IfMissingColumn insurance_data eDate
ALTER TABLE insurance_data ADD COLUMN eDate date NOT NULL default '0000-00-00' AFTER `date`;
#EndIf

#IfIndex insurance_data pid_type_date
ALTER TABLE insurance_data DROP KEY pid_type_date;
#EndIf

#IfNotIndex insurance_data pid_type_date_inactivetime
ALTER TABLE insurance_data ADD UNIQUE KEY pid_type_date_inactivetime (pid,type,date,inactive_time);
#EndIf

#IfMissingColumn billing exclude_from_insurance_billing
  ALTER TABLE billing ADD COLUMN exclude_from_insurance_billing tinyint(1) NOT NULL default 0;
#EndIf

#IfMissingColumn codes exclude_from_insurance_billing
  ALTER TABLE codes ADD COLUMN exclude_from_insurance_billing tinyint(1) NOT NULL default 0;
#EndIf

#IfMissingColumn form_encounter eft_number
  ALTER TABLE form_encounter ADD COLUMN eft_number varchar(80) DEFAULT NULL ;
#EndIf

#IfMissingColumn form_encounter claim_number
  ALTER TABLE form_encounter ADD COLUMN claim_number varchar(80) DEFAULT NULL ;
#EndIf

#IfMissingColumn form_encounter document_image
  ALTER TABLE form_encounter ADD COLUMN document_image varchar(80) DEFAULT NULL ;
#EndIf

#IfMissingColumn form_encounter seq_number
  ALTER TABLE form_encounter ADD COLUMN seq_number varchar(80) DEFAULT NULL ;
#EndIf

#IfMissingColumn form_encounter ordering_physician
  ALTER TABLE form_encounter ADD COLUMN ordering_physician INT(11) DEFAULT '0' COMMENT 'ordering provider, if any, for this visit';
#EndIf

#IfMissingColumn form_encounter referring_physician
  ALTER TABLE form_encounter ADD COLUMN referring_physician INT(11) DEFAULT '0' COMMENT 'referring provider, if any, for this visit';
#EndIf

#IfMissingColumn form_encounter contract_physician
  ALTER TABLE form_encounter ADD COLUMN contract_physician INT(11) DEFAULT '0' COMMENT 'contract provider, if any, for this visit';
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2016-10-01 load_filename 2017-PCS-Long-Abbrev-Titles.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2016-10-01', '2017-PCS-Long-Abbrev-Titles.zip', '4669c47f6a9ca34bf4c14d7f93b37993');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2016-10-01 load_filename 2017-GEM-DC.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2016-10-01', '2017-GEM-DC.zip', '5a0affdc77a152e6971781233ee969c1');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2016-10-01 load_filename 2017-ICD10-Code-Descriptions.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2016-10-01', '2017-ICD10-Code-Descriptions.zip', 'ed9c159cb4ac4ae4f145062e15f83291');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2016-10-01 load_filename 2017-GEM-PCS.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2016-10-01', '2017-GEM-PCS.zip', 'a4e08b08fb9a53c81385867c82aa8a9e');
#EndIf

#IfNotRow2D list_options list_id lists option_id ub_admit_source
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','ub_admit_source','UB Admit Source', 1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','1','Physician Referral',5,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','2','Clinic Referral',10,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','3','HMO Referral',20,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','4','Transfer from Hospital',25,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','5','Transfer from SNF',30,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','6','Transfer From Another Health Care Facility',35,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','7','Emergency Room',40,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','8','Court/Law Enforcement',45,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_source','9','Information Not Available',50,0);
#EndIf

#IfNotRow2D list_options list_id lists option_id ub_admit_type
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','ub_admit_type','UB Admit Type', 1,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_type','1','Emergency',10,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_type','2','Urgent',20,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_type','3','Elective',30,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_type','4','Newborn',40,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_type','5','Trauma',50,0);
INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('ub_admit_type','9','Information Not Available',60,0);

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2017-10-01 load_filename 2018-ICD-10-PCS-Order-File.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES
('ICD10', 'CMS', '2017-10-01', '2018-ICD-10-PCS-Order-File.zip', '264b342310236f2b3927062d2c72cfe3');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2017-10-01 load_filename 2018-ICD-10-CM-General-Equivalence-Mappings.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES
('ICD10', 'CMS', '2017-10-01', '2018-ICD-10-CM-General-Equivalence-Mappings.zip', '787a025fdcf6e1da1a85be779004f670');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2017-10-01 load_filename 2018-ICD-10-Code-Descriptions.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES
('ICD10', 'CMS', '2017-10-01', '2018-ICD-10-Code-Descriptions.zip', '6f9c77440132e30f565222ca9bb6599c');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2017-10-01 load_filename 2018-ICD-10-PCS-General-Equivalence-Mappings.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES
('ICD10', 'CMS', '2017-10-01', '2018-ICD-10-PCS-General-Equivalence-Mappings.zip', 'bb73c80e272da28712887d7979b1cebf');
#EndIf
#IfMissingColumn clinical_rules active
  ALTER TABLE clinical_rules ADD COLUMN active tinyint(4) DEFAULT NULL COMMENT 'Is this measure turned on?';
#EndIf

#IfMissingColumn clinical_rules pqrs_individual_2016_flag
  ALTER TABLE clinical_rules ADD COLUMN pqrs_individual_2016_flag tinyint(4) DEFAULT NULL COMMENT 'Is MIPS flag';
#EndIf

#IfMissingColumn clinical_rules pqrs_group_type
  ALTER TABLE clinical_rules ADD COLUMN pqrs_group_type varchar(2) DEFAULT 'X' COMMENT 'XML output scheme type';
#EndIf

#IfMissingColumn clinical_rules pqrs_code
  ALTER TABLE clinical_rules ADD COLUMN pqrs_code varchar(35) DEFAULT NULL COMMENT 'Measure number';
#EndIf

#IfNotTable onsite_mail
CREATE TABLE `onsite_mail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `owner` bigint(20) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `recipient_id` varchar(128) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `sender_id` varchar(128) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0' COMMENT 'flag indicates note is deleted',
  `delete_date` datetime DEFAULT NULL,
  `mtype` varchar(128) DEFAULT NULL,
  `message_status` varchar(20) NOT NULL DEFAULT 'New',
  `mail_chain` int(11) DEFAULT NULL,
  `reply_mail_chain` int(11) DEFAULT NULL,
  `is_msg_encrypted` tinyint(2) DEFAULT '0' COMMENT 'Whether messsage encrypted 0-Not encrypted, 1-Encrypted',
  PRIMARY KEY (`id`),
  KEY `pid` (`owner`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#Endif

#IfNotTable onsite_documents
CREATE TABLE `onsite_documents` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED DEFAULT NULL,
  `facility` int(10) UNSIGNED DEFAULT NULL,
  `provider` int(10) UNSIGNED DEFAULT NULL,
  `encounter` int(10) UNSIGNED DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `doc_type` varchar(255) NOT NULL,
  `patient_signed_status` smallint(5) UNSIGNED NOT NULL,
  `patient_signed_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `authorize_signed_time` datetime DEFAULT NULL,
  `accept_signed_status` smallint(5) NOT NULL,
  `authorizing_signator` varchar(50) NOT NULL,
  `review_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `denial_reason` varchar(255) NOT NULL,
  `authorized_signature` text,
  `patient_signature` text,
  `full_document` blob,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable onsite_messages
CREATE TABLE `onsite_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `message` longtext,
  `ip` varchar(15) NOT NULL,
  `date` datetime NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'who sent id',
 `recip_id` varchar(255) NOT NULL COMMENT 'who to id array',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Portal messages' AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable onsite_online
CREATE TABLE `onsite_online` (
  `hash` varchar(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `last_update` datetime NOT NULL,
  `username` varchar(64) NOT NULL,
  `userid` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB;
#EndIf

#IfNotTable onsite_portal_activity
CREATE TABLE `onsite_portal_activity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `patient_id` bigint(20) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `require_audit` tinyint(1) DEFAULT '1',
  `pending_action` varchar(255) DEFAULT NULL,
  `action_taken` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `narrative` longtext,
  `table_action` longtext,
  `table_args` longtext,
  `action_user` int(11) DEFAULT NULL,
  `action_taken_time` datetime DEFAULT NULL,
  `checksum` longtext,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable onsite_signatures
CREATE TABLE `onsite_signatures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` varchar(128) NOT NULL DEFAULT 'waiting',
  `type` varchar(128) NOT NULL,
  `created` int(11) NOT NULL,
  `lastmod` datetime NOT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` int(11) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `activity` tinyint(4) NOT NULL DEFAULT '0',
  `authorized` tinyint(4) DEFAULT NULL,
  `signator` varchar(255) NOT NULL,
  `sig_image` text,
  `signature` text,
  `sig_hash` varchar(128) NOT NULL,
  `ip` varchar(46) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pid` (`pid`,`user`),
  KEY `encounter` (`encounter`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotRow categories name Onsite Portal
INSERT INTO categories select (select MAX(id) from categories) + 1, 'Onsite Portal', '', 1, rght, rght + 5 from categories where name = 'Categories';
INSERT INTO categories select (select MAX(id) from categories) + 1, 'Patient', '', (select id from categories where name = 'Onsite Portal'), rght + 1, rght + 2 from categories where name = 'Categories';
INSERT INTO categories select (select MAX(id) from categories) + 1, 'Reviewed', '', (select id from categories where name = 'Onsite Portal'), rght + 3, rght + 4 from categories where name = 'Categories';
UPDATE categories SET rght = rght + 6 WHERE name = 'Categories';
UPDATE categories_seq SET id = (select MAX(id) from categories);
#EndIf

#IfMissingColumn clinical_rules active
  ALTER TABLE clinical_rules ADD COLUMN active tinyint(4) DEFAULT NULL COMMENT 'Is this measure turned on?';
#EndIf

#IfMissingColumn clinical_rules pqrs_individual_2016_flag
  ALTER TABLE clinical_rules ADD COLUMN pqrs_individual_2016_flag tinyint(4) DEFAULT NULL COMMENT 'Is MIPS flag';
#EndIf

#IfMissingColumn clinical_rules pqrs_group_type
  ALTER TABLE clinical_rules ADD COLUMN pqrs_group_type varchar(2) DEFAULT 'X' COMMENT 'XML output scheme type';
#EndIf

#IfMissingColumn clinical_rules pqrs_code
  ALTER TABLE clinical_rules ADD COLUMN pqrs_code varchar(35) DEFAULT NULL COMMENT 'Measure number';
#EndIf
#IfMissingColumn insurance_companies ins_inactive
  ALTER TABLE insurance_companies ADD COLUMN ins_inactive tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Yes Is This Record Inactive?';
#EndIf


#IfMissingColumn patient_data statement_y_n
SET @group_name = (SELECT group_name FROM layout_options WHERE field_id='vfc' AND form_id='DEM');
SET @backup_group_name = (SELECT group_name FROM layout_options WHERE field_id='deceased_date' AND form_id='DEM');
SET @seq = (SELECT MAX(seq) FROM layout_options WHERE group_name = IFNULL(@group_name,@backup_group_name) AND form_id='DEM');
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`) VALUES ('DEM', 'statement_y_n', IFNULL(@group_name,@backup_group_name), 'Print Statement', @seq+1, 1, 1, 5, 0, 'yesno', 1, 3, '', '', 'Do Not Print a Patient Statement If NO' ) ;
ALTER TABLE patient_data ADD COLUMN statement_y_n text NOT NULL default '';

#IfNotTable onsite_mail
CREATE TABLE `onsite_mail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `owner` bigint(20) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext,
  `recipient_id` varchar(128) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `sender_id` varchar(128) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0' COMMENT 'flag indicates note is deleted',
  `delete_date` datetime DEFAULT NULL,
  `mtype` varchar(128) DEFAULT NULL,
  `message_status` varchar(20) NOT NULL DEFAULT 'New',
  `mail_chain` int(11) DEFAULT NULL,
  `reply_mail_chain` int(11) DEFAULT NULL,
  `is_msg_encrypted` tinyint(2) DEFAULT '0' COMMENT 'Whether messsage encrypted 0-Not encrypted, 1-Encrypted',
  PRIMARY KEY (`id`),
  KEY `pid` (`owner`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#Endif

#IfNotTable onsite_documents
CREATE TABLE `onsite_documents` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED DEFAULT NULL,
  `facility` int(10) UNSIGNED DEFAULT NULL,
  `provider` int(10) UNSIGNED DEFAULT NULL,
  `encounter` int(10) UNSIGNED DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `doc_type` varchar(255) NOT NULL,
  `patient_signed_status` smallint(5) UNSIGNED NOT NULL,
  `patient_signed_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `authorize_signed_time` datetime DEFAULT NULL,
  `accept_signed_status` smallint(5) NOT NULL,
  `authorizing_signator` varchar(50) NOT NULL,
  `review_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `denial_reason` varchar(255) NOT NULL,
  `authorized_signature` text,
  `patient_signature` text,
  `full_document` blob,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable onsite_messages
CREATE TABLE `onsite_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `message` longtext,
  `ip` varchar(15) NOT NULL,
  `date` datetime NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'who sent id',
 `recip_id` varchar(255) NOT NULL COMMENT 'who to id array',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Portal messages' AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable onsite_online
CREATE TABLE `onsite_online` (
  `hash` varchar(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `last_update` datetime NOT NULL,
  `username` varchar(64) NOT NULL,
  `userid` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB;
#EndIf

#IfNotTable onsite_portal_activity
CREATE TABLE `onsite_portal_activity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `patient_id` bigint(20) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `require_audit` tinyint(1) DEFAULT '1',
  `pending_action` varchar(255) DEFAULT NULL,
  `action_taken` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `narrative` longtext,
  `table_action` longtext,
  `table_args` longtext,
  `action_user` int(11) DEFAULT NULL,
  `action_taken_time` datetime DEFAULT NULL,
  `checksum` longtext,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable onsite_signatures
CREATE TABLE `onsite_signatures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` varchar(128) NOT NULL DEFAULT 'waiting',
  `type` varchar(128) NOT NULL,
  `created` int(11) NOT NULL,
  `lastmod` datetime NOT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` int(11) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `activity` tinyint(4) NOT NULL DEFAULT '0',
  `authorized` tinyint(4) DEFAULT NULL,
  `signator` varchar(255) NOT NULL,
  `sig_image` text,
  `signature` text,
  `sig_hash` varchar(128) NOT NULL,
  `ip` varchar(46) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pid` (`pid`,`user`),
  KEY `encounter` (`encounter`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotRow categories name Onsite Portal
INSERT INTO categories select (select MAX(id) from categories) + 1, 'Onsite Portal', '', 1, rght, rght + 5 from categories where name = 'Categories';
INSERT INTO categories select (select MAX(id) from categories) + 1, 'Patient', '', (select id from categories where name = 'Onsite Portal'), rght + 1, rght + 2 from categories where name = 'Categories';
INSERT INTO categories select (select MAX(id) from categories) + 1, 'Reviewed', '', (select id from categories where name = 'Onsite Portal'), rght + 3, rght + 4 from categories where name = 'Categories';
UPDATE categories SET rght = rght + 6 WHERE name = 'Categories';
UPDATE categories_seq SET id = (select MAX(id) from categories);

#EndIf

