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

#IfMissingColumn insurance_companies ins_inactive
  ALTER TABLE insurance_companies ADD COLUMN ins_inactive tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Yes Is This Record Inactive?';
#EndIf

#IfMissingColumn insurance_companies allow_print_statement
  ALTER TABLE insurance_companies ADD COLUMN allow_print_statement tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 1 = Yes Print Statements';
#EndIf

#IfNotRow background_services name ccdaservice
INSERT INTO `background_services` (`name`, `title`, `execute_interval`, `function`, `require_once`, `sort_order`) VALUES ('ccdaservice', 'C-CDA Node Service', 1, 'runCheck', '/ccdaservice/ssmanager.php', 95);
ALTER TABLE `background_services` CHANGE `running` `running` TINYINT(1) NOT NULL DEFAULT '-1' COMMENT 'True indicates managed service is busy. Skip this interval.';
#EndIf

#IfNotColumnType onsite_mail owner varchar(128)
ALTER TABLE `onsite_mail` CHANGE `owner` `owner` varchar(128) DEFAULT NULL;
#Endif

#IfNotTable menu_entries
CREATE TABLE `menu_entries` (
  `id` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `icon` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `helperText` varchar(50) NOT NULL,
  `target` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `requirement` int(11) DEFAULT NULL,
  `acl_reqs` varchar(255) DEFAULT NULL,
  `global_reqs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `menu_entries` (`id`, `label`, `icon`, `class`, `helperText`, `target`, `url`, `requirement`, `acl_reqs`, `global_reqs`) VALUES
('About|/interface/main/about_page.php', 'About', '', '', '', 'msc', '/interface/main/about_page.php', NULL, NULL, NULL),
('ACL|/interface/usergroup/adminacl.php', 'ACL', '', '', '', 'adm', '/interface/usergroup/adminacl.php', 0, '["admin","acl"]', 'null'),
('Activity|/interface/reports/inventory_activity.php', 'Activity', '', '', '', 'rep', '/interface/reports/inventory_activity.php', 0, 'null', 'null'),
('Addr Book|/interface/usergroup/addrbook_list.php', 'Address Book', '', '', '', 'adm', '/interface/usergroup/addrbook_list.php', 0, '["admin","practice"]', 'null'),
('Address Label|/interface/patient_file/addr_label.php', 'Address Label', '', '', '', 'pop', '/interface/patient_file/addr_label.php', 1, NULL, 'addr_label_type'),
('Administration:admimg', 'Administration', '', '', '', NULL, NULL, 0, 'null', 'null'),
('Alerts Log|/interface/reports/cdr_log.php', 'Alerts Log', '', '', '', 'rep', '/interface/reports/cdr_log.php', 0, 'null', 'null'),
('Alerts|/interface/super/rules/index.php?action=alerts!listactmgr', 'Alerts', '', '', '', 'adm', '/interface/super/rules/index.php?action=alerts!listactmgr', 0, '["admin","super"]', '"enable_cdr"'),
('Appointments|/interface/reports/appointments_report.php', 'Appointments', '', '', '', 'rep', '/interface/reports/appointments_report.php', 0, 'null', 'null'),
('Appt-Enc|/interface/reports/appt_encounter_report.php', 'Appt-Enc', '', '', '', 'rep', '/interface/reports/appt_encounter_report.php', 0, 'null', 'null'),
('Appts|/interface/reports/appointments_report.php?patient=', 'Appts', '', '', '', 'pat', '/interface/reports/appointments_report.php?patient=', 1, NULL, NULL),
('Authorizations|/interface/main/authorizations/authorizations.php', 'Authorizations', '', '', '', 'msc', '/interface/main/authorizations/authorizations.php', 0, 'null', 'null'),
('Automated Measures (AMC)|/interface/reports/cqm.php?type=amc', 'Automated Measures (AMC)', '', '', '', 'rep', '/interface/reports/cqm.php?type=amc', 0, 'null', 'null'),
('Background Services|/interface/reports/background_services.php', 'Background Services', '', '', '', 'rep', '/interface/reports/background_services.php', 0, 'null', 'null'),
('Backup|/interface/main/backup.php', 'Backup', '', '', '', 'adm', '/interface/main/backup.php', 0, '["admin","super"]', 'null'),
('Barcode Label|/interface/patient_file/barcode_label.php', 'Barcode Label', '', '', '', 'pop', '/interface/patient_file/barcode_label.php', 1, NULL, 'barcode_label_type'),
('Batch Payments|/interface/billing/new_payment.php', 'Batch Payments', '', '', '', 'pat', '/interface/billing/new_payment.php', 0, 'null', 'null'),
('Batch Results|/interface/orders/orders_results.php?batch=1', 'Batch Results', '', '', '', 'pat', '/interface/orders/orders_results.php?batch=1', 0, 'null', 'null'),
('BatchCom|/interface/batchcom/batchcom.php', 'BatchCom', '', '', '', 'msc', '/interface/batchcom/batchcom.php', 0, 'null', 'null'),
('Billing|/interface/billing/billing_report.php', 'Billing', '', '', '', 'pat', '/interface/billing/billing_report.php', 0, 'null', 'null'),
('Blank Forms:', 'Blank Forms', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Calendar Administration|/modules/calendar/admin.php', 'Calendar Administration', '', '', '', 'lst', '/modules/calendar/admin.php', 0, '["admin","calendar"]', 'null'),
('Calendar|/interface/main/main_info.php', 'Calendar', '', '', '', 'lst', '/interface/main/main_info.php', 0, 'null', 'null'),
('Cash Rec|/interface/billing/sl_receipts_report.php', 'Cash Rec', '', '', '', 'rep', '/interface/billing/sl_receipts_report.php', 0, 'null', 'null'),
('Certificates|/interface/usergroup/ssl_certificates_admin.php', 'Certificates', '', '', '', 'adm', '/interface/usergroup/ssl_certificates_admin.php', 0, '["admin","users"]', 'null'),
('Chart Activity|/interface/reports/chart_location_activity.php', 'Chart Activity', '', '', '', 'rep', '/interface/reports/chart_location_activity.php', 0, 'null', 'null'),
('Chart Label|/interface/patient_file/label.php', 'Chart Label', '', '', '', 'pop', '/interface/patient_file/label.php', 1, NULL, 'chart_label_type'),
('Chart Tracker|/custom/chart_tracker.php', 'Chart Tracker', '', '', '', 'msc', '/custom/chart_tracker.php', 0, 'null', 'null'),
('Charts Out|/interface/reports/charts_checked_out.php', 'Charts Out', '', '', '', 'rep', '/interface/reports/charts_checked_out.php', 0, 'null', 'null'),
('Checkout|/interface/patient_file/pos_checkout.php', 'Checkout', '', '', '', 'pop', '/interface/patient_file/pos_checkout.php', 1, 'null', ''),
('Clients:', 'Clients', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Clinic:', 'Clinic', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Clinical|/interface/reports/clinical_reports.php', 'Report Generator', '', '', '', 'rep', '/interface/reports/clinical_reports.php', 0, 'null', 'null'),
('Codes|/interface/patient_file/encounter/superbill_custom_full.php', 'Codes', '', '', '', 'adm', '/interface/patient_file/encounter/superbill_custom_full.php', 0, '["admin","superbill"]', 'null'),
('Collections|/interface/reports/collections_report.php', 'Collections', '', '', '', 'rep', '/interface/reports/collections_report.php', 0, 'null', 'null'),
('Configuration|/interface/orders/types.php', 'Configuration', '', '', '', 'pat', '/interface/orders/types.php', 0, 'null', 'null'),
('Create Visit|/interface/forms/newpatient/new.php?autoloaded=1&calenc=', 'Create Visit', '', '', '', 'enc', '/interface/forms/newpatient/new.php?autoloaded=1&calenc=', 1, 'null', 'null'),
('Current|/interface/patient_file/encounter/encounter_top.php', 'Current', '', '', '', 'enc', '/interface/patient_file/encounter/encounter_top.php', 3, 'null', 'null'),
('Database|/phpmyadmin/index.php', 'Database', '', '', '', 'adm', '/phpmyadmin/index.php', 0, '["admin","database"]', '"!disable_phpmyadmin_link"'),
('Demographics|/interface/patient_file/summary/demographics_print.php', 'Demographics', '', '', '', 'rep', '/interface/patient_file/summary/demographics_print.php', 0, 'null', 'null'),
('Destroyed|destroyed_drugs_report.php', 'Destroyed', '', '', '', 'report', 'destroyed_drugs_report.php', 0, 'null', 'null'),
('Direct Message Log|/interface/reports/direct_message_log.php', 'Direct Message Log', '', '', '', 'rep', '/interface/reports/direct_message_log.php', 0, 'null', 'null'),
('Distribution|/interface/reports/insurance_allocation_report.php', 'Distribution', '', '', '', 'rep', '/interface/reports/insurance_allocation_report.php', 0, 'null', 'null'),
('Document Templates|/interface/super/manage_document_templates.php', 'Document Templates', '', '', '', 'msc', '/interface/super/manage_document_templates.php', 0, 'null', 'null'),
('Electronic Reports|/interface/orders/list_reports.php', 'Electronic Reports', '', '', '', 'pat', '/interface/orders/list_reports.php', 0, 'null', 'null'),
('Eligibility Response|/interface/reports/edi_271.php', 'Eligibility Response', '', '', '', 'rep', '/interface/reports/edi_271.php', 0, 'null', 'null'),
('Eligibility|/interface/reports/edi_270.php', 'Eligibility', '', '', '', 'rep', '/interface/reports/edi_270.php', 0, 'null', 'null'),
('Encounters|/interface/reports/encounters_report.php', 'Encounters', '', '', '', 'rep', '/interface/reports/encounters_report.php', 0, 'null', 'null'),
('Export|/custom/export_xml.php', 'Export', '', '', '', 'pop', '/custom/export_xml.php', 1, NULL, NULL),
('External Data Loads|/interface/code_systems/dataloads_ajax.php', 'External Data Loads', '', '', '', 'adm', '/interface/code_systems/dataloads_ajax.php', 0, '["admin","super"]', 'null'),
('Facilities|/interface/usergroup/facilities.php', 'Facilities', '', '', '', 'adm', '/interface/usergroup/facilities.php', 0, '["admin","users"]', 'null'),
('Fax/Scan|/interface/fax/faxq.php', 'Fax/Scan', '', '', '', 'msc', '/interface/fax/faxq.php', 0, 'null', '["enable_hylafax","enable_scanner"]'),
('Fee Sheet|/interface/patient_file/encounter/load_form.php?formname=fee_sheet', 'Fee Sheet', '', '', '', 'fee', '/interface/patient_file/encounter/load_form.php?formname=fee_sheet', 2, 'null', 'null'),
('Fees:feeimg', 'Fees', '', '', '', NULL, NULL, 0, 'null', 'null'),
('File:file0', 'File', '', '', '', NULL, NULL, NULL, NULL, NULL),
('Files|/interface/super/manage_site_files.php', 'Files', '', '', '', 'adm', '/interface/super/manage_site_files.php', 0, '["admin","super"]', 'null'),
('Financial Summary by Service Code|/interface/reports/svc_code_financial_report.php', 'Financial Summary by Service Code', '', '', '', 'rep', '/interface/reports/svc_code_financial_report.php', 0, 'null', 'null'),
('Financial:', 'Financial', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Financial:top', 'Financial', '', '', '', NULL, NULL, NULL, NULL, NULL),
('Flow Board|/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1', 'Flow Board', '', '', '', 'lst', '/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1', 0, 'null', 'null'),
('Forms|/interface/forms_admin/forms_admin.php', 'Forms', '', '', '', 'adm', '/interface/forms_admin/forms_admin.php', 0, '["admin","forms"]', 'null'),
('Front Rec|/interface/reports/front_receipts_report.php', 'Front Rec', '', '', '', 'rep', '/interface/reports/front_receipts_report.php', 0, 'null', 'null'),
('Globals|/interface/super/edit_globals.php', 'Globals', '', '', '', 'adm', '/interface/super/edit_globals.php', 0, '["admin","super"]', 'null'),
('Edit Menu|/interface/main/tabs/edit_menu.php', 'Edit Menu', '', '', '', 'adm', '/interface/main/tabs/edit_menu.php', 0, '["admin","super"]', 'null'),
('Immunization Registry|/interface/reports/immunization_report.php', 'Immunization Registry', '', '', '', 'rep', '/interface/reports/immunization_report.php', 0, 'null', 'null'),
('Import:', 'Import', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Import|/custom/import_xml.php', 'Import', '', '', '', 'pop', '/custom/import_xml.php', 1, NULL, NULL),
('Indigents|/interface/billing/indigent_patients_report.php', 'Indigents', '', '', '', 'rep', '/interface/billing/indigent_patients_report.php', 0, 'null', 'null'),
('Insurance:', 'Insurance', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Inventory:invimg', 'Inventory', '', '', '', NULL, NULL, 0, 'null', 'null'),
('Issues|/interface/patient_file/problem_encounter.php', 'Issues', '', '', '', 'tre', '/interface/patient_file/problem_encounter.php', 1, NULL, NULL),
('Lab Documents|/interface/main/display_documents.php', 'Lab Documents', '', '', '', 'pat', '/interface/main/display_documents.php', 0, 'null', 'null'),
('Lab Overview|/interface/patient_file/summary/labdata.php', 'Lab Overview', '', '', '', 'enc', '/interface/patient_file/summary/labdata.php', 1, 'null', 'null'),
('Language|/interface/language/language.php', 'Language', '', '', '', 'adm', '/interface/language/language.php', 0, '["admin","language"]', 'null'),
('Layouts|/interface/super/edit_layout.php', 'Layouts', '', '', '', 'adm', '/interface/super/edit_layout.php', 0, '["admin","super"]', 'null'),
('Lists|/interface/super/edit_list.php', 'Lists', '', '', '', 'adm', '/interface/super/edit_list.php', 0, '["admin","super"]', 'null'),
('List|/interface/reports/inventory_list.php', 'List', '', '', '', 'rep', '/interface/reports/inventory_list.php', 0, 'null', 'null'),
('List|/interface/reports/patient_list.php', 'List', '', '', '', 'rep', '/interface/reports/patient_list.php', 0, 'null', 'null'),
('Load Compendium|/interface/orders/load_compendium.php', 'Load Compendium', '', '', '', 'pat', '/interface/orders/load_compendium.php', 0, 'null', 'null'),
('Logs|/interface/logview/logview.php', 'Logs', '', '', '', 'adm', '/interface/logview/logview.php', 0, '["admin","users"]', 'null'),
('Management|/interface/drugs/drug_inventory.php', 'Management', '', '', '', 'pat', '/interface/drugs/drug_inventory.php', 0, 'null', 'null'),
('Menu:admins', 'Menu', '', '', '', NULL, NULL, 0, '["super","admin"]', NULL),
('Merge Patients|/interface/patient_file/merge_patients.php', 'Merge Patients', '', '', '', 'adm', '/interface/patient_file/merge_patients.php', 0, 'null', 'null'),
('Messages|/interface/main/messages/messages.php?form_active=1', 'Messages', '', '', '', 'msg', '/interface/main/messages/messages.php?form_active=1', 0, 'null', 'null'),
('Miscellaneous:misimg', 'Miscellaneous', '', '', '', NULL, NULL, 0, 'null', 'null'),
('Modules:modimg', 'Modules', '', '', '', NULL, NULL, 0, 'null', 'null'),
('Native Data Loads|/interface/super/load_codes.php', 'Native Data Loads', '', '', '', 'adm', '/interface/super/load_codes.php', 0, '["admin","super"]', 'null'),
('New Documents|/controller.php?document&list&patient_id=00', 'New Documents', '', '', '', 'msc', '/controller.php?document&list&patient_id=00', 0, 'null', 'null'),
('New/Patient|/interface/new/new.php', 'Add Patient', 'fa-group', '', '', 'pat', '/interface/new/new.php', 0, 'null', 'null'),
('Order Catalog|/interface/orders/types.php', 'Order Catalog', '', '', '', 'msc', '/interface/orders/types.php', 0, 'null', 'null'),
('Other:', 'Other', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Password|/interface/usergroup/user_info.php', 'Password', '', '', '', 'msc', '/interface/usergroup/user_info.php', 0, 'null', 'null'),
('Pat Ledger|/interface/reports/pat_ledger.php?form=0', 'Patient Ledger', '', '', '', 'rep', '/interface/reports/pat_ledger.php?form=0', 0, 'null', 'null'),
('Patient Education|/interface/reports/patient_edu_web_lookup.php', 'Patient Education', '', '', '', 'msc', '/interface/reports/patient_edu_web_lookup.php', 0, 'null', 'null'),
('Patient Billing Encounter by Carrier|/interface/reports/encounters_report_carrier.php', 'Patient Billing Encounter by Carrier', '', '', '', 'rep', '/interface/reports/encounters_report_carrier.php', 0, 'null', 'null'),
('Patient Flow Board|/interface/reports/patient_flow_board_report.php', 'Patient Flow Board', '', '', '', 'msc', '/interface/reports/patient_flow_board_report.php', 0, 'null', 'null'),
('Patient List Creation|/interface/reports/patient_list_creation.php', 'Patient List Creation', '', '', '', 'rep', '/interface/reports/patient_list_creation.php', 0, 'null', 'null'),
('Patient Record Request|/interface/patient_file/transaction/record_request.php', 'Patient Record Request', '', '', '', 'enc', '/interface/patient_file/transaction/record_request.php', 1, 'null', 'null'),
('Patient Reminders|/interface/patient_file/reminder/patient_reminders.php?mode=admin&patient_id=', 'Clinical Reminders', '', '', '', 'adm', '/interface/patient_file/reminder/patient_reminders.php?mode=admin&patient_id=', 0, '["admin","super"]', '"enable_cdr"'),
('Patient Results|/interface/orders/orders_results.php', 'Patient Results', '', '', '', 'enc', '/interface/orders/orders_results.php', 1, 'null', 'null'),
('Patient/Client:patimg', 'Patient', '', '', '', NULL, NULL, 0, 'null', 'null'),
('Patients|/interface/main/finder/dynamic_finder.php', 'Find Patient', 'fa-search', '', '', 'lst', '/interface/main/finder/dynamic_finder.php', 0, 'null', 'null'),
('Payment|/interface/patient_file/front_payment.php', 'Payment', '', '', '', 'fee', '/interface/patient_file/front_payment.php', 1, 'null', 'null'),
('Pending Approval|/interface/patient_file/ccr_pending_approval.php', 'Pending Approval', '', '', '', 'pat', '/interface/patient_file/ccr_pending_approval.php', 0, 'null', 'null'),
('Pending Res|/interface/orders/pending_orders.php', 'Pending Res', '', '', '', 'rep', '/interface/orders/pending_orders.php', 0, 'null', 'null'),
('Pending Review|/interface/orders/orders_results.php?review=1', 'Pending Review', '', '', '', 'enc', '/interface/orders/orders_results.php?review=1', 1, 'null', 'null'),
('Pmt Method|/interface/reports/receipts_by_method_report.php', 'Pmt Method', '', '', '', 'rep', '/interface/reports/receipts_by_method_report.php', 0, 'null', 'null'),
('Popup:lists', 'Popups', '', '', '', NULL, NULL, 0, NULL, NULL),
('Posting|/interface/billing/sl_eob_search.php', 'Posting', '', '', '', 'billing', '/interface/billing/sl_eob_search.php', 0, '["admin","acl"]', 'null'),
('Practice|/controller.php?practice_settings&pharmacy&action=list', 'Practice', '', '', '', 'adm', '/controller.php?practice_settings&pharmacy&action=list', 0, '["admin","practice"]', 'null'),
('Preferences|/interface/super/edit_globals.php?mode=user', 'Preferences', 'fa-gears', '', '', 'msc', '/interface/super/edit_globals.php?mode=user', 0, 'null', 'null'),
('Procedures:', 'Procedures', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Procedures:proimg', 'Labs/Testing', '', '', '', NULL, NULL, 0, 'null', 'null'),
('Providers|/interface/orders/procedure_provider_list.php', 'Providers', '', '', '', 'pat', '/interface/orders/procedure_provider_list.php', 0, 'null', 'null'),
('Quality Measures (CQM)|/interface/reports/cqm.php?type=cqm', 'Quality Measures (CQM)', '', '', '', 'rep', '/interface/reports/cqm.php?type=cqm', 0, 'null', 'null'),
('Records:', 'Records', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Referrals|/interface/reports/referrals_report.php', 'Referrals', '', '', '', 'rep', '/interface/reports/referrals_report.php', 0, 'null', 'null'),
('Referral|/interface/patient_file/transaction/print_referral.php', 'Referral', '', '', '', 'rep', '/interface/patient_file/transaction/print_referral.php', 0, 'null', 'null'),
('Report Results|/interface/reports/report_results.php', 'Report Results', '', '', '', 'rep', '/interface/reports/report_results.php', 0, 'null', 'null'),
('Reports:repimg', 'Reports', '', '', '', NULL, NULL, 0, 'null', 'null'),
('Rules|/interface/super/rules/index.php?action=browse!list', 'Rules', '', '', '', 'adm', '/interface/super/rules/index.php?action=browse!list', 0, '["admin","super"]', '"enable_cdr"'),
('Rx|/interface/reports/prescriptions_report.php', 'Rx', '', '', '', 'rep', '/interface/reports/prescriptions_report.php', 0, 'null', 'null'),
('Sales|/interface/reports/sales_by_item.php', 'Sales', '', '', '', 'rep', '/interface/reports/sales_by_item.php', 0, 'null', 'null'),
('Services:', 'Services', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Services|/interface/reports/services_by_category.php', 'Services', '', '', '', 'rep', '/interface/reports/services_by_category.php', 0, 'null', 'null'),
('Standard Measures|/interface/reports/cqm.php?type=standard', 'Standard Measures', '', '', '', 'rep', '/interface/reports/cqm.php?type=standard', 0, 'null', 'null'),
('Statistics|/interface/orders/procedure_stats.php', 'Statistics', '', '', '', 'rep', '/interface/orders/procedure_stats.php', 0, 'null', 'null'),
('Summary|/interface/patient_file/summary/demographics.php', 'Summary', '', '', '', 'pat', '/interface/patient_file/summary/demographics.php', 1, 'null', 'null'),
('Superbill/Fee Sheet|/interface/patient_file/printed_fee_sheet.php', 'Superbill/Fee Sheet', '', '', '', 'rep', '/interface/patient_file/printed_fee_sheet.php', 0, 'null', 'null'),
('Superbill|/interface/patient_file/printed_fee_sheet.php?fill=1', 'Superbill', '', '', '', 'pop', '/interface/patient_file/printed_fee_sheet.php?fill=1', 1, NULL, NULL),
('Superbill|/interface/reports/custom_report_range.php', 'Superbill', '', '', '', 'rep', '/interface/reports/custom_report_range.php', 0, 'null', 'null'),
('Syndromic Surveillance|/interface/reports/non_reported.php', 'Syndromic Surveillance', '', '', '', 'rep', '/interface/reports/non_reported.php', 0, 'null', 'null'),
('TestLBF|/interface/forms/LBF/printable.php?formname=LBF1', 'TestLBF', '', '', '', 'rep', '/interface/forms/LBF/printable.php?formname=LBF1', 0, 'null', 'null'),
('Transactions|/interface/reports/inventory_transactions.php', 'Transactions', '', '', '', 'rep', '/interface/reports/inventory_transactions.php', 0, 'null', 'null'),
('Unique SP|/interface/reports/unique_seen_patients_report.php', 'Unique SP', '', '', '', 'rep', '/interface/reports/unique_seen_patients_report.php', 0, 'null', 'null'),
('Upload|/interface/patient_file/ccr_import.php', 'Upload', '', '', '', 'pat', '/interface/patient_file/ccr_import.php', 0, 'null', 'null'),
('Users|/interface/usergroup/usergroup_admin.php', 'Users', '', '', '', 'adm', '/interface/usergroup/usergroup_admin.php', 0, '["admin","users"]', 'null'),
('View:', 'View', '', '', '', NULL, NULL, 0, NULL, NULL),
('Visit Forms:', 'Visit Forms', 'fa-caret-right', '', '', 'forms', '', 0, 'null', 'null'),
('Visit History|/interface/patient_file/history/encounters.php', 'Visit History', '', '', '', 'enc', '/interface/patient_file/history/encounters.php', 1, 'null', 'null'),
('Visits:encounter', 'Visits', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null'),
('Visits:reports', 'Visits', 'fa-caret-right', '', '', NULL, NULL, 0, 'null', 'null');
#EndIf

#IfNotTable menu_trees
CREATE TABLE `menu_trees` (
  `menu_set` varchar(255) NOT NULL,
  `entry_id` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `helperText` varchar(50) NOT NULL,
  `parent` varchar(255) NOT NULL,
  `seq` int(11) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`menu_set`,`entry_id`,`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `menu_trees` (`menu_set`, `entry_id`, `icon`, `helperText`, `parent`, `seq`, `label`) VALUES
('Administrators', 'Calendar|/interface/main/main_info.php', '', '', '', 0, NULL),
('Administrators', 'Flow Board|/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1', '', '', '', 100, NULL),
('Administrators', 'Messages|/interface/main/messages/messages.php?form_active=1', '', '', '', 200, NULL),
('Administrators', 'Patient/Client:patimg', '', '', '', 300, NULL),
('Administrators', 'Fees:feeimg', '', '', '', 400, NULL),
('Administrators', 'Modules:modimg', '', '', '', 500, NULL),
('Administrators', 'Procedures:proimg', '', '', '', 700, NULL),
('Administrators', 'Administration:admimg', '', '', '', 800, NULL),
('Administrators', 'Reports:repimg', '', '', '', 900, NULL),
('Administrators', 'Miscellaneous:misimg', '', '', '', 1000, NULL),
('Administrators', 'Globals|/interface/super/edit_globals.php', '', '', 'Administration:admimg', 0, NULL),
('Administrators', 'Edit Menu|/interface/main/tabs/edit_menu.php', '', '', 'Administration:admimg', 0, NULL),
('Administrators', 'Facilities|/interface/usergroup/facilities.php', '', '', 'Administration:admimg', 100, NULL),
('Administrators', 'Users|/interface/usergroup/usergroup_admin.php', '', '', 'Administration:admimg', 200, NULL),
('Administrators', 'Addr Book|/interface/usergroup/addrbook_list.php', '', '', 'Administration:admimg', 300, NULL),
('Administrators', 'Practice|/controller.php?practice_settings&pharmacy&action=list', '', '', 'Administration:admimg', 400, NULL),
('Administrators', 'Codes|/interface/patient_file/encounter/superbill_custom_full.php', '', '', 'Administration:admimg', 500, NULL),
('Administrators', 'Layouts|/interface/super/edit_layout.php', '', '', 'Administration:admimg', 600, NULL),
('Administrators', 'Lists|/interface/super/edit_list.php', '', '', 'Administration:admimg', 700, NULL),
('Administrators', 'ACL|/interface/usergroup/adminacl.php', '', '', 'Administration:admimg', 800, NULL),
('Administrators', 'Files|/interface/super/manage_site_files.php', '', '', 'Administration:admimg', 900, NULL),
('Administrators', 'Backup|/interface/main/backup.php', '', '', 'Administration:admimg', 1000, NULL),
('Administrators', 'Rules|/interface/super/rules/index.php?action=browse!list', '', '', 'Administration:admimg', 1100, NULL),
('Administrators', 'Alerts|/interface/super/rules/index.php?action=alerts!listactmgr', '', '', 'Administration:admimg', 1200, NULL),
('Administrators', 'Patient Reminders|/interface/patient_file/reminder/patient_reminders.php?mode=admin&patient_id=', '', '', 'Administration:admimg', 1300, NULL),
('Administrators', 'Other:', '', '', 'Administration:admimg', 1400, NULL),
('Administrators', 'Demographics|/interface/patient_file/summary/demographics_print.php', '', '', 'Blank Forms:', 0, NULL),
('Administrators', 'Superbill/Fee Sheet|/interface/patient_file/printed_fee_sheet.php', '', '', 'Blank Forms:', 100, NULL),
('Administrators', 'Referral|/interface/patient_file/transaction/print_referral.php', '', '', 'Blank Forms:', 200, NULL),
('Administrators', 'TestLBF|/interface/forms/LBF/printable.php?formname=LBF1', '', '', 'Blank Forms:', 300, NULL),
('Administrators', 'List|/interface/reports/patient_list.php', '', '', 'Clients:', 0, NULL),
('Administrators', 'Rx|/interface/reports/prescriptions_report.php', '', '', 'Clients:', 100, NULL),
('Administrators', 'Patient List Creation|/interface/reports/patient_list_creation.php', '', '', 'Clients:', 200, NULL),
('Administrators', 'Clinical|/interface/reports/clinical_reports.php', '', '', 'Clients:', 300, NULL),
('Administrators', 'Referrals|/interface/reports/referrals_report.php', '', '', 'Clients:', 400, NULL),
('Administrators', 'Immunization Registry|/interface/reports/immunization_report.php', '', '', 'Clients:', 500, NULL),
('Administrators', 'Report Results|/interface/reports/report_results.php', '', '', 'Clinic:', 0, NULL),
('Administrators', 'Standard Measures|/interface/reports/cqm.php?type=standard', '', '', 'Clinic:', 100, NULL),
('Administrators', 'Quality Measures (CQM)|/interface/reports/cqm.php?type=cqm', '', '', 'Clinic:', 200, NULL),
('Administrators', 'Automated Measures (AMC)|/interface/reports/cqm.php?type=amc', '', '', 'Clinic:', 300, NULL),
('Administrators', 'Alerts Log|/interface/reports/cdr_log.php', '', '', 'Clinic:', 500, NULL),
('Administrators', 'Fee Sheet|/interface/patient_file/encounter/load_form.php?formname=fee_sheet', '', '', 'Fees:feeimg', 0, NULL),
('Administrators', 'Payment|/interface/patient_file/front_payment.php', '', '', 'Fees:feeimg', 100, NULL),
('Administrators', 'Checkout|/interface/patient_file/pos_checkout.php?framed=1', '', '', 'Fees:feeimg', 200, NULL),
('Administrators', 'Billing|/interface/billing/billing_report.php', '', '', 'Fees:feeimg', 300, NULL),
('Administrators', 'Batch Payments|/interface/billing/new_payment.php', '', '', 'Fees:feeimg', 400, NULL),
('Administrators', 'Sales|/interface/reports/sales_by_item.php', '', '', 'Financial:', 0, NULL),
('Administrators', 'Cash Rec|/interface/billing/sl_receipts_report.php', '', '', 'Financial:', 100, NULL),
('Administrators', 'Front Rec|/interface/reports/front_receipts_report.php', '', '', 'Financial:', 200, NULL),
('Administrators', 'Pmt Method|/interface/reports/receipts_by_method_report.php', '', '', 'Financial:', 300, NULL),
('Administrators', 'Collections|/interface/reports/collections_report.php', '', '', 'Financial:', 400, NULL),
('Administrators', 'Pat Ledger|/interface/reports/pat_ledger.php?form=0', '', '', 'Financial:', 500, NULL),
('Administrators', 'Financial Summary by Service Code|/interface/reports/svc_code_financial_report.php', '', '', 'Financial:', 600, NULL),
('Administrators', 'Upload|/interface/patient_file/ccr_import.php', '', '', 'Import:', 0, NULL),
('Administrators', 'Pending Approval|/interface/patient_file/ccr_pending_approval.php', '', '', 'Import:', 100, NULL),
('Administrators', 'Distribution|/interface/reports/insurance_allocation_report.php', '', '', 'Insurance:', 0, NULL),
('Administrators', 'Indigents|/interface/billing/indigent_patients_report.php', '', '', 'Insurance:', 100, NULL),
('Administrators', 'Unique SP|/interface/reports/unique_seen_patients_report.php', '', '', 'Insurance:', 200, NULL),
('Administrators', 'List|/interface/reports/inventory_list.php', '', '', 'Inventory:invimg', 0, NULL),
('Administrators', 'Management|/interface/drugs/drug_inventory.php', '', '', 'Inventory:invimg', 0, NULL),
('Administrators', 'Destroyed|destroyed_drugs_report.php', '', '', 'Inventory:invimg', 100, NULL),
('Administrators', 'Activity|/interface/reports/inventory_activity.php', '', '', 'Inventory:invimg', 100, NULL),
('Administrators', 'Transactions|/interface/reports/inventory_transactions.php', '', '', 'Inventory:invimg', 200, NULL),
('Administrators', 'Superbill/Fee Sheet|/interface/patient_file/printed_fee_sheet.php', '', '', 'Menu:admins', 0, NULL),
('Administrators', 'Patient Education|/interface/reports/patient_edu_web_lookup.php', '', '', 'Miscellaneous:misimg', 0, NULL),
('Administrators', 'Authorizations|/interface/main/authorizations/authorizations.php', '', '', 'Miscellaneous:misimg', 100, NULL),
('Administrators', 'Fax/Scan|/interface/fax/faxq.php', '', '', 'Miscellaneous:misimg', 200, NULL),
('Administrators', 'Addr Book|/interface/usergroup/addrbook_list.php', '', '', 'Miscellaneous:misimg', 300, NULL),
('Administrators', 'Order Catalog|/interface/orders/types.php', '', '', 'Miscellaneous:misimg', 400, NULL),
('Administrators', 'Chart Tracker|/custom/chart_tracker.php', '', '', 'Miscellaneous:misimg', 500, NULL),
('Administrators', 'BatchCom|/interface/batchcom/batchcom.php', '', '', 'Miscellaneous:misimg', 700, NULL),
('Administrators', 'Password|/interface/usergroup/user_info.php', '', '', 'Miscellaneous:misimg', 800, NULL),
('Administrators', 'Preferences|/interface/super/edit_globals.php?mode=user', '', '', 'Miscellaneous:misimg', 900, NULL),
('Administrators', 'New Documents|/controller.php?document&list&patient_id=00', '', '', 'Miscellaneous:misimg', 1000, NULL),
('Administrators', 'Document Templates|/interface/super/manage_document_templates.php', '', '', 'Miscellaneous:misimg', 1100, NULL),
('Administrators', 'Menu:admins', '', '', 'Miscellaneous:misimg', 1200, NULL),
('Administrators', 'Language|/interface/language/language.php', '', '', 'Other:', 0, NULL),
('Administrators', 'Forms|/interface/forms_admin/forms_admin.php', '', '', 'Other:', 100, NULL),
('Administrators', 'Calendar Administration|/modules/calendar/admin.php', '', '', 'Other:', 200, NULL),
('Administrators', 'Logs|/interface/logview/logview.php', '', '', 'Other:', 300, NULL),
('Administrators', 'Database|/phpmyadmin/index.php', '', '', 'Other:', 400, NULL),
('Administrators', 'Certificates|/interface/usergroup/ssl_certificates_admin.php', '', '', 'Other:', 500, NULL),
('Administrators', 'Native Data Loads|/interface/super/load_codes.php', '', '', 'Other:', 600, NULL),
('Administrators', 'External Data Loads|/interface/code_systems/dataloads_ajax.php', '', '', 'Other:', 700, NULL),
('Administrators', 'Merge Patients|/interface/patient_file/merge_patients.php', '', '', 'Other:', 800, NULL),
('Administrators', 'Patients|/interface/main/finder/dynamic_finder.php', '', '', 'Patient/Client:patimg', 0, NULL),
('Administrators', 'New/Search|/interface/new/new.php', '', '', 'Patient/Client:patimg', 100, NULL),
('Administrators', 'Summary|/interface/patient_file/summary/demographics.php', '', '', 'Patient/Client:patimg', 200, NULL),
('Administrators', 'Visits:encounter', '', '', 'Patient/Client:patimg', 300, NULL),
('Administrators', 'Records:', '', '', 'Patient/Client:patimg', 400, NULL),
('Administrators', 'Visit Forms:', '', '', 'Patient/Client:patimg', 500, NULL),
('Administrators', 'Import:', '', '', 'Patient/Client:patimg', 600, NULL),
('Administrators', 'Pending Res|/interface/orders/pending_orders.php', '', '', 'Procedures:', 0, NULL),
('Administrators', 'Statistics|/interface/orders/procedure_stats.php', '', '', 'Procedures:', 100, NULL),
('Administrators', 'Providers|/interface/orders/procedure_provider_list.php', '', '', 'Procedures:proimg', 0, NULL),
('Administrators', 'Configuration|/interface/orders/types.php', '', '', 'Procedures:proimg', 100, NULL),
('Administrators', 'Load Compendium|/interface/orders/load_compendium.php', '', '', 'Procedures:proimg', 200, NULL),
('Administrators', 'Pending Review|/interface/orders/orders_results.php?review=1', '', '', 'Procedures:proimg', 300, NULL),
('Administrators', 'Patient Results|/interface/orders/orders_results.php', '', '', 'Procedures:proimg', 400, NULL),
('Administrators', 'Lab Overview|/interface/patient_file/summary/labdata.php', '', '', 'Procedures:proimg', 500, NULL),
('Administrators', 'Batch Results|/interface/orders/orders_results.php?batch=1', '', '', 'Procedures:proimg', 600, NULL),
('Administrators', 'Electronic Reports|/interface/orders/list_reports.php', '', '', 'Procedures:proimg', 700, NULL),
('Administrators', 'Lab Documents|/interface/main/display_documents.php', '', '', 'Procedures:proimg', 800, NULL),
('Administrators', 'Patient Record Request|/interface/patient_file/transaction/record_request.php', '', '', 'Records:', 0, NULL),
('Administrators', 'Clients:', '', '', 'Reports:repimg', 0, NULL),
('Administrators', 'Clinic:', '', '', 'Reports:repimg', 100, NULL),
('Administrators', 'Visits:reports', '', '', 'Reports:repimg', 200, NULL),
('Administrators', 'Financial:', '', '', 'Reports:repimg', 300, NULL),
('Administrators', 'Inventory:invimg', '', '', 'Reports:repimg', 400, NULL),
('Administrators', 'Procedures:', '', '', 'Reports:repimg', 500, NULL),
('Administrators', 'Insurance:', '', '', 'Reports:repimg', 600, NULL),
('Administrators', 'Blank Forms:', '', '', 'Reports:repimg', 700, NULL),
('Administrators', 'Services:', '', '', 'Reports:repimg', 800, NULL),
('Administrators', 'Background Services|/interface/reports/background_services.php', '', '', 'Services:', 0, NULL),
('Administrators', 'Direct Message Log|/interface/reports/direct_message_log.php', '', '', 'Services:', 100, NULL),
('Administrators', 'Create Visit|/interface/forms/newpatient/new.php?autoloaded=1&calenc=', '', '', 'Visits:encounter', 0, NULL),
('Administrators', 'Current|/interface/patient_file/encounter/encounter_top.php', '', '', 'Visits:encounter', 100, NULL),
('Administrators', 'Visit History|/interface/patient_file/history/encounters.php', '', '', 'Visits:encounter', 200, NULL),
('Administrators', 'Appointments|/interface/reports/appointments_report.php', '', '', 'Visits:reports', 0, NULL),
('Administrators', 'Patient Flow Board|/interface/reports/patient_flow_board_report.php', '', '', 'Visits:reports', 100, NULL),
('Administrators', 'Patient Billing Encounter by Carrier|/interface/reports/encounters_report_carrier.php', '', '', 'Visits:reports', 0, NULL),
('Administrators', 'Encounters|/interface/reports/encounters_report.php', '', '', 'Visits:reports', 200, NULL),
('Administrators', 'Appt-Enc|/interface/reports/appt_encounter_report.php', '', '', 'Visits:reports', 300, NULL),
('Administrators', 'Superbill|/interface/reports/custom_report_range.php', '', '', 'Visits:reports', 400, NULL),
('Administrators', 'Eligibility|/interface/reports/edi_270.php', '', '', 'Visits:reports', 500, NULL),
('Administrators', 'Eligibility Response|/interface/reports/edi_271.php', '', '', 'Visits:reports', 600, NULL),
('Administrators', 'Chart Activity|/interface/reports/chart_location_activity.php', '', '', 'Visits:reports', 700, NULL),
('Administrators', 'Charts Out|/interface/reports/charts_checked_out.php', '', '', 'Visits:reports', 800, NULL),
('Administrators', 'Services|/interface/reports/services_by_category.php', '', '', 'Visits:reports', 900, NULL),
('Administrators', 'Syndromic Surveillance|/interface/reports/non_reported.php', '', '', 'Visits:reports', 1000, NULL),
('AnsServ', 'File:file0', '', '', '', 0, NULL),
('AnsServ', 'View:', '', '', '', 100, NULL),
('AnsServ', 'Messages|/interface/main/messages/messages.php?form_active=1', '', '', '', 200, NULL),
('AnsServ', 'Patient/Client:patimg', '', '', '', 300, NULL),
('AnsServ', 'Miscellaneous:misimg', '', '', '', 1000, NULL),
('AnsServ', 'Preferences|/interface/super/edit_globals.php?mode=user', '', '', 'File:file0', 100, NULL),
('AnsServ', 'Calendar Administration|/modules/calendar/admin.php', '', '', 'Other:', 200, NULL),
('AnsServ', 'Patients|/interface/main/finder/dynamic_finder.php', '', '', 'Patient/Client:patimg', 0, NULL),
('AnsServ', 'New/Patient|/interface/new/new.php', '', '', 'Patient/Client:patimg', 100, NULL),
('AnsServ', 'Calendar|/interface/main/main_info.php', '', '', 'View:', 0, NULL),
('AnsServ', 'Flow Board|/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1', '', '', 'View:', 100, NULL),
('AnsServ', 'Addr Book|/interface/usergroup/addrbook_list.php', '', '', 'View:', 300, NULL),
('Clinical Staff', 'File:file0', '', '', '', 0, NULL),
('Clinical Staff', 'View:', '0', '0', '', 100, NULL),
('Clinical Staff', 'Messages|/interface/main/messages/messages.php?form_active=1', '0', '0', '', 200, NULL),
('Clinical Staff', 'Patient/Client:patimg', '0', '0', '', 300, NULL),
('Clinical Staff', 'Fees:feeimg', '', '', '', 400, NULL),
('Clinical Staff', 'Procedures:proimg', '', '', '', 400, NULL),
('Clinical Staff', 'Popup:lists', '', '', '', 500, NULL),
('Clinical Staff', 'Patient Education|/interface/reports/patient_edu_web_lookup.php', '0', '0', '', 600, NULL),
('Clinical Staff', 'Fee Sheet|/interface/patient_file/encounter/load_form.php?formname=fee_sheet', '', '', 'Fees:feeimg', 0, NULL),
('Clinical Staff', 'Payment|/interface/patient_file/front_payment.php', '', '', 'Fees:feeimg', 100, NULL),
('Clinical Staff', 'Checkout|/interface/patient_file/pos_checkout.php?framed=1', '', '', 'Fees:feeimg', 200, NULL),
('Clinical Staff', 'Billing|/interface/billing/billing_report.php', '', '', 'Fees:feeimg', 300, NULL),
('Clinical Staff', 'Batch Payments|/interface/billing/new_payment.php', '', '', 'Fees:feeimg', 400, NULL),
('Clinical Staff', 'Posting|/interface/billing/sl_eob_search.php', '', '', 'Fees:feeimg', 500, 'Posting'),
('Clinical Staff', 'About|/interface/main/about_page.php', '0', '0', 'File:file0', 0, NULL),
('Clinical Staff', 'Preferences|/interface/super/edit_globals.php?mode=user', '0', '0', 'File:file0', 100, NULL),
('Clinical Staff', 'Upload|/interface/patient_file/ccr_import.php', '', '', 'Import:', 0, NULL),
('Clinical Staff', 'Pending Approval|/interface/patient_file/ccr_pending_approval.php', '', '', 'Import:', 100, NULL),
('Clinical Staff', 'Patients|/interface/main/finder/dynamic_finder.php', '0', '0', 'Patient/Client:patimg', 0, NULL),
('Clinical Staff', 'New/Patient|/interface/new/new.php', '0', '0', 'Patient/Client:patimg', 100, NULL),
('Clinical Staff', 'Summary|/interface/patient_file/summary/demographics.php', '0', '0', 'Patient/Client:patimg', 200, NULL),
('Clinical Staff', 'Visits:encounter', '0', '0', 'Patient/Client:patimg', 300, NULL),
('Clinical Staff', 'Visit Forms:', '', '', 'Patient/Client:patimg', 400, NULL),
('Clinical Staff', 'Records:', '', '', 'Patient/Client:patimg', 500, NULL),
('Clinical Staff', 'Issues|/interface/patient_file/problem_encounter.php', '', '', 'Popup:lists', 0, NULL),
('Clinical Staff', 'Import|/custom/import_xml.php', '', '', 'Popup:lists', 100, NULL),
('Clinical Staff', 'Export|/custom/export_xml.php', '', '', 'Popup:lists', 200, NULL),
('Clinical Staff', 'Appts|/interface/reports/appointments_report.php?patient=', '', '', 'Popup:lists', 300, NULL),
('Clinical Staff', 'Superbill|/interface/patient_file/printed_fee_sheet.php?fill=1', '', '', 'Popup:lists', 400, NULL),
('Clinical Staff', 'Payment|/interface/patient_file/front_payment.php', '', '', 'Popup:lists', 500, NULL),
('Clinical Staff', 'Checkout|/interface/patient_file/pos_checkout.php', '', '', 'Popup:lists', 600, NULL),
('Clinical Staff', 'Letter|/interface/patient_file/letter.php', '', '', 'Popup:lists', 700, NULL),
('Clinical Staff', 'Chart Label|/interface/patient_file/label.php', '', '', 'Popup:lists', 800, NULL),
('Clinical Staff', 'Barcode Label|/interface/patient_file/barcode_label.php', '', '', 'Popup:lists', 900, NULL),
('Clinical Staff', 'Address Label|/interface/patient_file/addr_label.php', '', '', 'Popup:lists', 1000, NULL),
('Clinical Staff', 'Pending Res|/interface/orders/pending_orders.php', '', '', 'Procedures:', 0, NULL),
('Clinical Staff', 'Statistics|/interface/orders/procedure_stats.php', '', '', 'Procedures:', 100, NULL),
('Clinical Staff', 'Providers|/interface/orders/procedure_provider_list.php', '', '', 'Procedures:proimg', 0, NULL),
('Clinical Staff', 'Configuration|/interface/orders/types.php', '', '', 'Procedures:proimg', 100, NULL),
('Clinical Staff', 'Load Compendium|/interface/orders/load_compendium.php', '', '', 'Procedures:proimg', 200, NULL),
('Clinical Staff', 'Pending Review|/interface/orders/orders_results.php?review=1', '', '', 'Procedures:proimg', 300, NULL),
('Clinical Staff', 'Patient Results|/interface/orders/orders_results.php', '', '', 'Procedures:proimg', 400, NULL),
('Clinical Staff', 'Lab Overview|/interface/patient_file/summary/labdata.php', '', '', 'Procedures:proimg', 500, NULL),
('Clinical Staff', 'Batch Results|/interface/orders/orders_results.php?batch=1', '', '', 'Procedures:proimg', 600, NULL),
('Clinical Staff', 'Electronic Reports|/interface/orders/list_reports.php', '', '', 'Procedures:proimg', 700, NULL),
('Clinical Staff', 'Lab Documents|/interface/main/display_documents.php', '', '', 'Procedures:proimg', 800, NULL),
('Clinical Staff', 'Patient Record Request|/interface/patient_file/transaction/record_request.php', '', '', 'Records:', 200, NULL),
('Clinical Staff', 'Addr Book|/interface/usergroup/addrbook_list.php', '0', '0', 'View:', 100, NULL),
('Clinical Staff', 'Calendar|/interface/main/main_info.php', '0', '0', 'View:', 200, NULL),
('Clinical Staff', 'Flow Board|/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1', '0', '0', 'View:', 300, NULL),
('Clinical Staff', 'Create Visit|/interface/forms/newpatient/new.php?autoloaded=1&calenc=', '0', '0', 'Visits:encounter', 0, NULL),
('Clinical Staff', 'Current|/interface/patient_file/encounter/encounter_top.php', '0', '0', 'Visits:encounter', 100, NULL),
('Clinical Staff', 'Visit History|/interface/patient_file/history/encounters.php', '0', '0', 'Visits:encounter', 200, NULL),
('default', 'Calendar|/interface/main/main_info.php', '', '', '', 0, NULL),
('default', 'Flow Board|/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1', '', '', '', 100, NULL),
('default', 'Messages|/interface/main/messages/messages.php?form_active=1', '', '', '', 200, NULL),
('default', 'Patient/Client:patimg', '', '', '', 300, NULL),
('default', 'Appointments|/interface/reports/appointments_report.php?patient=', '', '', '', 300, NULL),
('default', 'Fees:feeimg', '', '', '', 400, NULL),
('default', 'Modules:modimg', '', '', '', 500, NULL),
('default', 'Inventory:invimg', '', '', '', 600, NULL),
('default', 'Procedures:proimg', '', '', '', 700, NULL),
('default', 'Administration:admimg', '', '', '', 800, NULL),
('default', 'Reports:repimg', '', '', '', 900, NULL),
('default', 'Miscellaneous:misimg', '', '', '', 1000, NULL),
('default', 'Popup:lists', '', '', '', 1100, NULL),
('default', 'About|/interface/main/about_page.php', '', '', '', 1200, ''),
('default', 'Globals|/interface/super/edit_globals.php', '', '', 'Administration:admimg', 0, NULL),
('default', 'Facilities|/interface/usergroup/facilities.php', '', '', 'Administration:admimg', 100, NULL),
('default', 'Users|/interface/usergroup/usergroup_admin.php', '', '', 'Administration:admimg', 200, NULL),
('default', 'Addr Book|/interface/usergroup/addrbook_list.php', '', '', 'Administration:admimg', 300, NULL),
('default', 'Practice|/controller.php?practice_settings&pharmacy&action=list', '', '', 'Administration:admimg', 400, NULL),
('default', 'Codes|/interface/patient_file/encounter/superbill_custom_full.php', '', '', 'Administration:admimg', 500, NULL),
('default', 'Layouts|/interface/super/edit_layout.php', '', '', 'Administration:admimg', 600, NULL),
('default', 'Lists|/interface/super/edit_list.php', '', '', 'Administration:admimg', 700, NULL),
('default', 'ACL|/interface/usergroup/adminacl.php', '', '', 'Administration:admimg', 800, NULL),
('default', 'Files|/interface/super/manage_site_files.php', '', '', 'Administration:admimg', 900, NULL),
('default', 'Backup|/interface/main/backup.php', '', '', 'Administration:admimg', 1000, NULL),
('default', 'Rules|/interface/super/rules/index.php?action=browse!list', '', '', 'Administration:admimg', 1100, NULL),
('default', 'Alerts|/interface/super/rules/index.php?action=alerts!listactmgr', '', '', 'Administration:admimg', 1200, NULL),
('default', 'Patient Reminders|/interface/patient_file/reminder/patient_reminders.php?mode=admin&patient_id=', '', '', 'Administration:admimg', 1300, NULL),
('default', 'Other:', '', '', 'Administration:admimg', 1400, NULL),
('default', 'Demographics|/interface/patient_file/summary/demographics_print.php', '', '', 'Blank Forms:', 0, NULL),
('default', 'Superbill/Fee Sheet|/interface/patient_file/printed_fee_sheet.php', '', '', 'Blank Forms:', 100, NULL),
('default', 'Referral|/interface/patient_file/transaction/print_referral.php', '', '', 'Blank Forms:', 200, NULL),
('default', 'TestLBF|/interface/forms/LBF/printable.php?formname=LBF1', '', '', 'Blank Forms:', 300, NULL),
('default', 'List|/interface/reports/patient_list.php', '', '', 'Clients:', 0, NULL),
('default', 'Rx|/interface/reports/prescriptions_report.php', '', '', 'Clients:', 100, NULL),
('default', 'Patient List Creation|/interface/reports/patient_list_creation.php', '', '', 'Clients:', 200, NULL),
('default', 'Clinical|/interface/reports/clinical_reports.php', '', '', 'Clients:', 300, NULL),
('default', 'Referrals|/interface/reports/referrals_report.php', '', '', 'Clients:', 400, NULL),
('default', 'Immunization Registry|/interface/reports/immunization_report.php', '', '', 'Clients:', 500, NULL),
('default', 'Report Results|/interface/reports/report_results.php', '', '', 'Clinic:', 0, NULL),
('default', 'Standard Measures|/interface/reports/cqm.php?type=standard', '', '', 'Clinic:', 100, NULL),
('default', 'Quality Measures (CQM)|/interface/reports/cqm.php?type=cqm', '', '', 'Clinic:', 200, NULL),
('default', 'Automated Measures (AMC)|/interface/reports/cqm.php?type=amc', '', '', 'Clinic:', 300, NULL),
('default', 'Alerts Log|/interface/reports/cdr_log.php', '', '', 'Clinic:', 500, NULL),
('default', 'Fee Sheet|/interface/patient_file/encounter/load_form.php?formname=fee_sheet', '', '', 'Fees:feeimg', 0, NULL),
('default', 'Payment|/interface/patient_file/front_payment.php', '', '', 'Fees:feeimg', 100, NULL),
('default', 'Checkout|/interface/patient_file/pos_checkout.php?framed=1', '', '', 'Fees:feeimg', 200, NULL),
('default', 'Billing|/interface/billing/billing_report.php', '', '', 'Fees:feeimg', 300, NULL),
('default', 'Batch Payments|/interface/billing/new_payment.php', '', '', 'Fees:feeimg', 400, NULL),
('default', 'Posting|/interface/billing/sl_eob_search.php', '', '', 'Fees:feeimg', 500, 'Posting'),
('default', 'EDI History|/interface/billing/edih_view.php', '', '', 'Fees:feeimg', 600, NULL),
('default', 'Sales|/interface/reports/sales_by_item.php', '', '', 'Financial:', 0, NULL),
('default', 'Cash Rec|/interface/billing/sl_receipts_report.php', '', '', 'Financial:', 100, NULL),
('default', 'Front Rec|/interface/reports/front_receipts_report.php', '', '', 'Financial:', 200, NULL),
('default', 'Pmt Method|/interface/reports/receipts_by_method_report.php', '', '', 'Financial:', 300, NULL),
('default', 'Collections|/interface/reports/collections_report.php', '', '', 'Financial:', 400, NULL),
('default', 'Pat Ledger|/interface/reports/pat_ledger.php?form=0', '', '', 'Financial:', 500, NULL),
('default', 'Financial Summary by Service Code|/interface/reports/svc_code_financial_report.php', '', '', 'Financial:', 600, NULL),
('default', 'Upload|/interface/patient_file/ccr_import.php', '', '', 'Import:', 0, NULL),
('default', 'Pending Approval|/interface/patient_file/ccr_pending_approval.php', '', '', 'Import:', 100, NULL),
('default', 'Distribution|/interface/reports/insurance_allocation_report.php', '', '', 'Insurance:', 0, NULL),
('default', 'Indigents|/interface/billing/indigent_patients_report.php', '', '', 'Insurance:', 100, NULL),
('default', 'Unique SP|/interface/reports/unique_seen_patients_report.php', '', '', 'Insurance:', 200, NULL),
('default', 'List|/interface/reports/inventory_list.php', '', '', 'Inventory:invimg', 0, NULL),
('default', 'Management|/interface/drugs/drug_inventory.php', '', '', 'Inventory:invimg', 0, NULL),
('default', 'Destroyed|destroyed_drugs_report.php', '', '', 'Inventory:invimg', 100, NULL),
('default', 'Activity|/interface/reports/inventory_activity.php', '', '', 'Inventory:invimg', 100, NULL),
('default', 'Transactions|/interface/reports/inventory_transactions.php', '', '', 'Inventory:invimg', 200, NULL),
('default', 'Patient Education|/interface/reports/patient_edu_web_lookup.php', '', '', 'Miscellaneous:misimg', 0, NULL),
('default', 'Authorizations|/interface/main/authorizations/authorizations.php', '', '', 'Miscellaneous:misimg', 100, NULL),
('default', 'Fax/Scan|/interface/fax/faxq.php', '', '', 'Miscellaneous:misimg', 200, NULL),
('default', 'Addr Book|/interface/usergroup/addrbook_list.php', '', '', 'Miscellaneous:misimg', 300, NULL),
('default', 'Order Catalog|/interface/orders/types.php', '', '', 'Miscellaneous:misimg', 400, NULL),
('default', 'Chart Tracker|/custom/chart_tracker.php', '', '', 'Miscellaneous:misimg', 500, NULL),
('default', 'BatchCom|/interface/batchcom/batchcom.php', '', '', 'Miscellaneous:misimg', 700, NULL),
('default', 'Password|/interface/usergroup/user_info.php', '', '', 'Miscellaneous:misimg', 800, NULL),
('default', 'Preferences|/interface/super/edit_globals.php?mode=user', '', '', 'Miscellaneous:misimg', 900, NULL),
('default', 'New Documents|/controller.php?document&list&patient_id=00', '', '', 'Miscellaneous:misimg', 1000, NULL),
('default', 'Document Templates|/interface/super/manage_document_templates.php', '', '', 'Miscellaneous:misimg', 1100, NULL),
('default', 'Language|/interface/language/language.php', '', '', 'Other:', 0, NULL),
('default', 'Forms|/interface/forms_admin/forms_admin.php', '', '', 'Other:', 100, NULL),
('default', 'Calendar Administration|/modules/calendar/admin.php', '', '', 'Other:', 200, NULL),
('default', 'Logs|/interface/logview/logview.php', '', '', 'Other:', 300, NULL),
('default', 'Database|/phpmyadmin/index.php', '', '', 'Other:', 400, NULL),
('default', 'Certificates|/interface/usergroup/ssl_certificates_admin.php', '', '', 'Other:', 500, NULL),
('default', 'Native Data Loads|/interface/super/load_codes.php', '', '', 'Other:', 600, NULL),
('default', 'External Data Loads|/interface/code_systems/dataloads_ajax.php', '', '', 'Other:', 700, NULL),
('default', 'Merge Patients|/interface/patient_file/merge_patients.php', '', '', 'Other:', 800, NULL),
('default', 'Patients|/interface/main/finder/dynamic_finder.php', '', '', 'Patient/Client:patimg', 0, NULL),
('default', 'New/Search|/interface/new/new.php', '', '', 'Patient/Client:patimg', 100, NULL),
('default', 'Summary|/interface/patient_file/summary/demographics.php', '', '', 'Patient/Client:patimg', 200, NULL),
('default', 'Visits:encounter', '', '', 'Patient/Client:patimg', 300, NULL),
('default', 'Records:', '', '', 'Patient/Client:patimg', 400, NULL),
('default', 'Visit Forms:', '', '', 'Patient/Client:patimg', 500, NULL),
('default', 'Import:', '', '', 'Patient/Client:patimg', 600, NULL),
('default', 'Issues|/interface/patient_file/problem_encounter.php', '', '', 'Popup:lists', 0, NULL),
('default', 'Import|/custom/import_xml.php', '', '', 'Popup:lists', 100, NULL),
('default', 'Export|/custom/export_xml.php', '', '', 'Popup:lists', 200, NULL),
('default', 'Appts|/interface/reports/appointments_report.php?patient=', '', '', 'Popup:lists', 300, NULL),
('default', 'Superbill|/interface/patient_file/printed_fee_sheet.php?fill=1', '', '', 'Popup:lists', 400, NULL),
('default', 'Payment|/interface/patient_file/front_payment.php', '', '', 'Popup:lists', 500, NULL),
('default', 'Checkout|/interface/patient_file/pos_checkout.php', '', '', 'Popup:lists', 600, NULL),
('default', 'Letter|/interface/patient_file/letter.php', '', '', 'Popup:lists', 700, NULL),
('default', 'Chart Label|/interface/patient_file/label.php', '', '', 'Popup:lists', 800, NULL),
('default', 'Barcode Label|/interface/patient_file/barcode_label.php', '', '', 'Popup:lists', 900, NULL),
('default', 'Address Label|/interface/patient_file/addr_label.php', '', '', 'Popup:lists', 1000, NULL),
('default', 'Pending Res|/interface/orders/pending_orders.php', '', '', 'Procedures:', 0, NULL),
('default', 'Statistics|/interface/orders/procedure_stats.php', '', '', 'Procedures:', 100, NULL),
('default', 'Providers|/interface/orders/procedure_provider_list.php', '', '', 'Procedures:proimg', 0, NULL),
('default', 'Configuration|/interface/orders/types.php', '', '', 'Procedures:proimg', 100, NULL),
('default', 'Load Compendium|/interface/orders/load_compendium.php', '', '', 'Procedures:proimg', 200, NULL),
('default', 'Pending Review|/interface/orders/orders_results.php?review=1', '', '', 'Procedures:proimg', 300, NULL),
('default', 'Patient Results|/interface/orders/orders_results.php', '', '', 'Procedures:proimg', 400, NULL),
('default', 'Lab Overview|/interface/patient_file/summary/labdata.php', '', '', 'Procedures:proimg', 500, NULL),
('default', 'Batch Results|/interface/orders/orders_results.php?batch=1', '', '', 'Procedures:proimg', 600, NULL),
('default', 'Electronic Reports|/interface/orders/list_reports.php', '', '', 'Procedures:proimg', 700, NULL),
('default', 'Lab Documents|/interface/main/display_documents.php', '', '', 'Procedures:proimg', 800, NULL),
('default', 'Patient Record Request|/interface/patient_file/transaction/record_request.php', '', '', 'Records:', 0, NULL),
('default', 'Clients:', '', '', 'Reports:repimg', 0, NULL),
('default', 'Clinic:', '', '', 'Reports:repimg', 100, NULL),
('default', 'Visits:reports', '', '', 'Reports:repimg', 200, NULL),
('default', 'Financial:', '', '', 'Reports:repimg', 300, NULL),
('default', 'Inventory:', '', '', 'Reports:repimg', 400, NULL),
('default', 'Procedures:', '', '', 'Reports:repimg', 500, NULL),
('default', 'Insurance:', '', '', 'Reports:repimg', 600, NULL),
('default', 'Blank Forms:', '', '', 'Reports:repimg', 700, NULL),
('default', 'Services:', '', '', 'Reports:repimg', 800, NULL),
('default', 'Background Services|/interface/reports/background_services.php', '', '', 'Services:', 0, NULL),
('default', 'Direct Message Log|/interface/reports/direct_message_log.php', '', '', 'Services:', 100, NULL),
('default', 'Create Visit|/interface/forms/newpatient/new.php?autoloaded=1&calenc=', '', '', 'Visits:encounter', 0, NULL),
('default', 'Current|/interface/patient_file/encounter/encounter_top.php', '', '', 'Visits:encounter', 100, NULL),
('default', 'Visit History|/interface/patient_file/history/encounters.php', '', '', 'Visits:encounter', 200, NULL),
('default', 'Appointments|/interface/reports/appointments_report.php', '', '', 'Visits:reports', 0, NULL),
('default', 'Patient Flow Board|/interface/reports/patient_flow_board_report.php', '', '', 'Visits:reports', 100, NULL),
('default', 'Patient Billing Encounter by Carrier|/interface/reports/encounters_report_carrier.php', '', '', 'Visits:reports', 0, NULL),
('default', 'Encounters|/interface/reports/encounters_report.php', '', '', 'Visits:reports', 200, NULL),
('default', 'Appt-Enc|/interface/reports/appt_encounter_report.php', '', '', 'Visits:reports', 300, NULL),
('default', 'Superbill|/interface/reports/custom_report_range.php', '', '', 'Visits:reports', 400, NULL),
('default', 'Eligibility|/interface/reports/edi_270.php', '', '', 'Visits:reports', 500, NULL),
('default', 'Eligibility Response|/interface/reports/edi_271.php', '', '', 'Visits:reports', 600, NULL),
('default', 'Chart Activity|/interface/reports/chart_location_activity.php', '', '', 'Visits:reports', 700, NULL),
('default', 'Charts Out|/interface/reports/charts_checked_out.php', '', '', 'Visits:reports', 800, NULL),
('default', 'Services|/interface/reports/services_by_category.php', '', '', 'Visits:reports', 900, NULL),
('default', 'Syndromic Surveillance|/interface/reports/non_reported.php', '', '', 'Visits:reports', 1000, NULL),
('Front Office', 'File:file0', '', '', '', 0, NULL),
('Front Office', 'View:', '', '', '', 100, NULL),
('Front Office', 'Patient/Client:patimg', '', '', '', 200, NULL),
('Front Office', 'Patient Reminders|/interface/patient_file/reminder/patient_reminders.php?mode=admin&patient_id=', '', '', '', 200, NULL),
('Front Office', 'Financial:top', '', '', '', 300, NULL),
('Front Office', 'Popup:lists', '', '', '', 400, NULL),
('Front Office', 'Miscellaneous:misimg', '', '', '', 500, NULL),
('Front Office', 'About|/interface/main/about_page.php', '0', '0', 'File:file0', 0, NULL),
('Front Office', 'Preferences|/interface/super/edit_globals.php?mode=user', '', '', 'File:file0', 100, NULL),
('Front Office', 'Payment|/interface/patient_file/front_payment.php', '', '', 'Financial:top', 0, NULL),
('Front Office', 'Checkout|/interface/patient_file/pos_checkout.php', '', '', 'Financial:top', 100, NULL),
('Front Office', 'Front Rec|/interface/reports/front_receipts_report.php', '', '', 'Financial:top', 200, NULL),
('Front Office', 'Pat Ledger|/interface/reports/pat_ledger.php?form=0', '', '', 'Financial:top', 500, NULL),
('Front Office', 'Messages|/interface/main/messages/messages.php?form_active=1', '', '', 'Miscellaneous:misimg', 0, NULL),
('Front Office', 'Patient Education|/interface/reports/patient_edu_web_lookup.php', '', '', 'Miscellaneous:misimg', 100, NULL),
('Front Office', 'Patients|/interface/main/finder/dynamic_finder.php', '', '', 'Patient/Client:patimg', 0, NULL),
('Front Office', 'New/Search|/interface/new/new.php', '', '', 'Patient/Client:patimg', 100, NULL),
('Front Office', 'New/Patient|/interface/new/new.php', '', '', 'Patient/Client:patimg', 200, NULL),
('Front Office', 'Clinical|/interface/reports/clinical_reports.php', '', '', 'Patient/Client:patimg', 300, NULL),
('Front Office', 'Issues|/interface/patient_file/problem_encounter.php', '', '', 'Popup:lists', 0, NULL),
('Front Office', 'Import|/custom/import_xml.php', '', '', 'Popup:lists', 100, NULL),
('Front Office', 'Export|/custom/export_xml.php', '', '', 'Popup:lists', 200, NULL),
('Front Office', 'Appts|/interface/reports/appointments_report.php?patient=', '', '', 'Popup:lists', 300, NULL),
('Front Office', 'Superbill|/interface/patient_file/printed_fee_sheet.php?fill=1', '', '', 'Popup:lists', 400, NULL),
('Front Office', 'Payment|/interface/patient_file/front_payment.php', '', '', 'Popup:lists', 500, NULL),
('Front Office', 'Checkout|/interface/patient_file/pos_checkout.php', '', '', 'Popup:lists', 600, NULL),
('Front Office', 'Letter|/interface/patient_file/letter.php', '', '', 'Popup:lists', 700, NULL),
('Front Office', 'Chart Label|/interface/patient_file/label.php', '', '', 'Popup:lists', 800, NULL),
('Front Office', 'Barcode Label|/interface/patient_file/barcode_label.php', '', '', 'Popup:lists', 900, NULL),
('Front Office', 'Address Label|/interface/patient_file/addr_label.php', '', '', 'Popup:lists', 1000, NULL),
('Front Office', 'Addr Book|/interface/usergroup/addrbook_list.php', '', '', 'View:', 100, NULL),
('Front Office', 'Calendar|/interface/main/main_info.php', '', '', 'View:', 200, NULL),
('Front Office', 'Flow Board|/interface/patient_tracker/patient_tracker.php?skip_timeout_reset=1', '', '', 'View:', 300, NULL);
#EndIf


#IfMissingColumn users fullscreen_role
ALTER TABLE users ADD COLUMN fullscreen_role text NOT NULL;
#EndIf

#IfMissingColumn users fullscreen_page
ALTER TABLE users ADD COLUMN fullscreen_page text NOT NULL;
#EndIf

#IfMissingColumn users fullscreen_enable
ALTER TABLE users ADD COLUMN fullscreen_enable int(11) NOT NULL default 0;
#EndIf
