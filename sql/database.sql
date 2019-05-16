--
-- Database: `libreehr`
--

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL default '0',
  `line1` varchar(255) default NULL,
  `line2` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `state` varchar(35) default NULL,
  `zip` varchar(10) default NULL,
  `plus_four` varchar(4) default NULL,
  `country` varchar(255) default NULL,
  `foreign_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB;

--
-- Table structure for table `amendments`
--

DROP TABLE IF EXISTS `amendments`;
CREATE TABLE IF NOT EXISTS `amendments` (
  `amendment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Amendment ID',
  `amendment_date` date NOT NULL COMMENT 'Amendement request date',
  `amendment_by` varchar(50) NOT NULL COMMENT 'Amendment requested from',
  `amendment_status` varchar(50) NULL COMMENT 'Amendment status accepted/rejected/null',
  `pid` int(11) NOT NULL COMMENT 'Patient ID from patient_data',
  `amendment_desc` TEXT COMMENT 'Amendment Details',
  `created_by` int(11) NOT NULL COMMENT 'references users.id for session owner',
  `modified_by` int(11) NULL COMMENT 'references users.id for session owner',
  `created_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created time',
  `modified_time` timestamp NULL COMMENT 'modified time',
  PRIMARY KEY amendments_id(`amendment_id`),
  KEY amendment_pid(`pid`)
) ENGINE = MyISAM;


--
-- Table structure for table `amendments_history`
--

DROP TABLE IF EXISTS `amendments_history`;
CREATE TABLE IF NOT EXISTS `amendments_history` (
  `amendment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Amendment ID',
  `amendment_note` TEXT COMMENT 'Amendment requested from',
  `amendment_status` VARCHAR(50) NULL COMMENT 'Amendment Request Status',
  `created_by` int(11) NOT NULL COMMENT 'references users.id for session owner',
  `created_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created time',
KEY amendment_history_id(`amendment_id`)
) ENGINE = MyISAM;


--
-- Table structure for table `array`
--

DROP TABLE IF EXISTS `array`;
CREATE TABLE `array` (
  `array_key` varchar(255) default NULL,
  `array_value` longtext
) ENGINE=InnoDB;


--
-- Table structure for table `audit_master`
--

DROP TABLE IF EXISTS `audit_master`;
CREATE TABLE `audit_master` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL COMMENT 'The Id of the user who approves or denies',
  `approval_status` tinyint(4) NOT NULL COMMENT '1-Pending,2-Approved,3-Denied,4-Appointment directly updated to calendar table,5-Cancelled appointment',
  `comments` text,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1-new patient,2-existing patient,3-change is only in the document,4-Patient upload,5-random key,10-Appointment',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

--
-- Table structure for table `audit_details`
--

DROP TABLE IF EXISTS `audit_details`;
CREATE TABLE `audit_details` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(100) NOT NULL COMMENT 'libreehr table name',
  `field_name` VARCHAR(100) NOT NULL COMMENT 'libreehr table''s field name',
  `field_value` TEXT COMMENT 'libreehr table''s field value',
  `audit_master_id` BIGINT(20) NOT NULL COMMENT 'Id of the audit_master table',
  `entry_identification` VARCHAR(255) NOT NULL DEFAULT '1' COMMENT 'Used when multiple entry occurs from the same table.1 means no multiple entry',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

--
-- Table structure for table `background_services`
--

DROP TABLE IF EXISTS `background_services`;
CREATE TABLE `background_services` (
  `name` varchar(31) NOT NULL,
  `title` varchar(127) NOT NULL COMMENT 'name for reports',
  `active` tinyint(1) NOT NULL default '0',
  `running` tinyint(1) NOT NULL default '-1',
  `next_run` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `execute_interval` int(11) NOT NULL default '0' COMMENT 'minimum number of minutes between function calls,0=manual mode',
  `function` varchar(127) NOT NULL COMMENT 'name of background service function',
  `require_once` varchar(255) default NULL COMMENT 'include file (if necessary)',
  `sort_order` int(11) NOT NULL default '100' COMMENT 'lower numbers will be run first',
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB;

--
-- Dumping data for table `background_services`
--

INSERT INTO `background_services` (`name`, `title`, `execute_interval`, `function`, `require_once`, `sort_order`) VALUES
('phimail', 'phiMail Direct Messaging Service', 5, 'phimail_check', '/library/direct_message_check.inc', 100);


--
-- Table structure for table `batchcom`
--

DROP TABLE IF EXISTS `batchcom`;
CREATE TABLE `batchcom` (
  `id` bigint(20) NOT NULL auto_increment,
  `patient_id` int(11) NOT NULL default '0',
  `sent_by` bigint(20) NOT NULL default '0',
  `msg_type` varchar(60) default NULL,
  `msg_subject` varchar(255) default NULL,
  `msg_text` mediumtext,
  `msg_date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `billing`
--

DROP TABLE IF EXISTS `billing`;
CREATE TABLE `billing` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime default NULL,
  `code_type` varchar(15) default NULL,
  `code` varchar(20) default NULL,
  `pid` int(11) default NULL,
  `provider_id` int(11) default NULL,
  `user` int(11) default NULL,
  `groupname` varchar(255) default NULL,
  `authorized` tinyint(1) default NULL,
  `encounter` int(11) default NULL,
  `code_text` longtext,
  `billed` tinyint(1) default NULL,
  `activity` tinyint(1) default NULL,
  `payer_id` int(11) default NULL,
  `bill_process` tinyint(2) NOT NULL default '0',
  `bill_date` datetime default NULL,
  `process_date` datetime default NULL,
  `process_file` varchar(255) default NULL,
  `modifier` varchar(12) default NULL,
  `units` int(11) default NULL,
  `fee` decimal(12,2) default NULL,
  `justify` varchar(255) default NULL,
  `target` varchar(30) default NULL,
  `x12_partner_id` int(11) default NULL,
  `ndc_info` varchar(255) default NULL,
  `notecodes` varchar(80) NOT NULL default '',
  `exclude_from_insurance_billing` TINYINT(1) NOT NULL DEFAULT '0',
  `external_id` VARCHAR(20) DEFAULT NULL,
  `pricelevel` varchar(31) default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `value` varchar(255) default NULL,
  `parent` int(11) NOT NULL default '0',
  `lft` int(11) NOT NULL default '0',
  `rght` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`),
  KEY `lft` (`lft`,`rght`)
) ENGINE=InnoDB;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` VALUES (1, 'Categories', '', 0, 0, 31);
INSERT INTO `categories` VALUES (2, 'Lab Report', '', 1, 1, 2);
INSERT INTO `categories` VALUES (3, 'Medical Record', '', 1, 3, 4);
INSERT INTO `categories` VALUES (4, 'Patient Information', '', 1, 5, 10);
INSERT INTO `categories` VALUES (5, 'Patient ID card', '', 4, 6, 7);
INSERT INTO `categories` VALUES (6, 'Advance Directive', '', 1, 11, 18);
INSERT INTO `categories` VALUES (7, 'Do Not Resuscitate Order', '', 6, 12, 13);
INSERT INTO `categories` VALUES (8, 'Durable Power of Attorney', '', 6, 14, 15);
INSERT INTO `categories` VALUES (9, 'Living Will', '', 6, 16, 17);
INSERT INTO `categories` VALUES (10, 'Patient Photograph', '', 4, 8, 9);
INSERT INTO `categories` VALUES (11, 'CCR', '', 1, 19, 20);
INSERT INTO `categories` VALUES (12, 'CCD', '', 1, 21, 22);
INSERT INTO `categories` VALUES (13, 'CCDA', '', 1, 23, 24);
INSERT INTO `categories` VALUES (14, 'Onsite Portal', '', 1, 25, 30);
INSERT INTO `categories` VALUES (15, 'Patient', '', 14, 26, 27);
INSERT INTO `categories` VALUES (16, 'Reviewed', '', 14, 28, 29);


--
-- Table structure for table `categories_seq`
--

DROP TABLE IF EXISTS `categories_seq`;
CREATE TABLE `categories_seq` (
  `id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Dumping data for table `categories_seq`
--

INSERT INTO `categories_seq` VALUES (16);


--
-- Table structure for table `categories_to_documents`
--

DROP TABLE IF EXISTS `categories_to_documents`;
CREATE TABLE `categories_to_documents` (
  `category_id` int(11) NOT NULL default '0',
  `document_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`category_id`,`document_id`)
) ENGINE=InnoDB;


--
-- Table structure for table `claims`
--

DROP TABLE IF EXISTS `claims`;
CREATE TABLE `claims` (
  `patient_id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `version` int(10) unsigned NOT NULL COMMENT 'Claim version, incremented in code',
  `payer_id` int(11) NOT NULL default '0',
  `status` tinyint(2) NOT NULL default '0',
  `payer_type` tinyint(4) NOT NULL default '0',
  `bill_process` tinyint(2) NOT NULL default '0',
  `bill_time` datetime default NULL,
  `process_time` datetime default NULL,
  `process_file` varchar(255) default NULL,
  `target` varchar(30) default NULL,
  `x12_partner_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`patient_id`,`encounter_id`,`version`)
) ENGINE=InnoDB;


--
-- Table structure for table `clinical_plans`
--

DROP TABLE IF EXISTS `clinical_plans`;
CREATE TABLE `clinical_plans` (
  `id` varchar(31) NOT NULL DEFAULT '' COMMENT 'Unique and maps to list_options list clinical_plans',
  `pid` bigint(20) NOT NULL DEFAULT '0' COMMENT '0 is default for all patients, while > 0 is id from patient_data table',
  `normal_flag` tinyint(1) COMMENT 'Normal Activation Flag',
  `cqm_flag` tinyint(1) COMMENT 'Clinical Quality Measure flag (unable to customize per patient)',
  `cqm_2011_flag` tinyint(1) COMMENT '2011 Clinical Quality Measure flag (unable to customize per patient)',
  `cqm_2014_flag` tinyint(1) COMMENT '2014 Clinical Quality Measure flag (unable to customize per patient)',
  `cqm_measure_group` varchar(10) NOT NULL default '' COMMENT 'Clinical Quality Measure Group Identifier',
  PRIMARY KEY  (`id`,`pid`)
) ENGINE=InnoDB ;

--
-- Clinical Quality Measure (CMQ) plans
--
-- Measure Group A: Diabetes Mellitus
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_2011_flag`, `cqm_measure_group` ) VALUES ('dm_plan_cqm', 0, 0, 1, 1, 'A');
-- Measure Group C: Chronic Kidney Disease (CKD)
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_2011_flag`, `cqm_measure_group` ) VALUES ('ckd_plan_cqm', 0, 0, 1, 1, 'C');
-- Measure Group D: Preventative Care
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_2011_flag`, `cqm_measure_group` ) VALUES ('prevent_plan_cqm', 0, 0, 1, 1, 'D');
-- Measure Group E: Perioperative Care
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_2011_flag`, `cqm_measure_group` ) VALUES ('periop_plan_cqm', 0, 0, 1, 1, 'E');
-- Measure Group F: Rheumatoid Arthritis
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_2011_flag`, `cqm_measure_group` ) VALUES ('rheum_arth_plan_cqm', 0, 0, 1, 1, 'F');
-- Measure Group G: Back Pain
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_2011_flag`, `cqm_measure_group` ) VALUES ('back_pain_plan_cqm', 0, 0, 1, 1, 'G');
-- Measure Group H: Coronary Artery Bypass Graft (CABG)
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_2011_flag`, `cqm_measure_group` ) VALUES ('cabg_plan_cqm', 0, 0, 1, 1, 'H');
--
-- Standard clinical plans
--
-- Diabetes Mellitus
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_measure_group` ) VALUES ('dm_plan', 0, 1, 0, '');
INSERT INTO `clinical_plans` ( `id`, `pid`, `normal_flag`, `cqm_flag`, `cqm_measure_group` ) VALUES ('prevent_plan', 0, 1, 0, '');

--
-- Table structure for table `clinical_rules`
--

DROP TABLE IF EXISTS `clinical_rules`;
CREATE TABLE `clinical_rules` (
  `id` varchar(35) NOT NULL DEFAULT '',
  `pid` bigint(20) NOT NULL DEFAULT '0' COMMENT '0 is default for all patients, while > 0 is id from patient_data table',
  `active_alert_flag` tinyint(1) DEFAULT NULL COMMENT 'Active Alert Widget Module flag - note not yet utilized',
  `passive_alert_flag` tinyint(1) DEFAULT NULL COMMENT 'Passive Alert Widget Module flag',
  `patient_reminder_flag` tinyint(1) DEFAULT NULL COMMENT 'Clinical Reminder Module flag',
  `release_version` varchar(255) NOT NULL DEFAULT '' COMMENT 'Clinical Rule Release Version',
  `web_reference` varchar(255) NOT NULL DEFAULT '' COMMENT 'Clinical Rule Web Reference',
  `access_control` varchar(255) NOT NULL DEFAULT 'patients:med' COMMENT 'ACO link for access control',
  `pqrs_code` varchar(35) DEFAULT NULL COMMENT 'Measure number',
  `pqrs_individual_2016_flag` tinyint(4) DEFAULT NULL COMMENT 'Is MIPS flag',
  `pqrs_group_type` varchar(2) DEFAULT 'X' COMMENT 'XML output scheme type',
  `active` tinyint(4) DEFAULT NULL COMMENT 'Is this measure turned on?');
--
-- Table structure for table `clinical_plans_rules`
--

DROP TABLE IF EXISTS `clinical_plans_rules`;
CREATE TABLE `clinical_plans_rules` (
  `plan_id` varchar(31) NOT NULL DEFAULT '' COMMENT 'Unique and maps to list_options list clinical_plans',
  `rule_id` varchar(31) NOT NULL DEFAULT '' COMMENT 'Unique and maps to list_options list clinical_rules',
  PRIMARY KEY  (`plan_id`,`rule_id`)
) ENGINE=InnoDB ;

--
-- Clinical Quality Measure (CMQ) plans to rules mappings
--
-- Measure Group A: Diabetes Mellitus
--   NQF 0059 (PQRI 1)   Diabetes: HbA1c Poor Control
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan_cqm', 'rule_dm_a1c_cqm');
--   NQF 0064 (PQRI 2)   Diabetes: LDL Management & Control
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan_cqm', 'rule_dm_ldl_cqm');
--   NQF 0061 (PQRI 3)   Diabetes: Blood Pressure Management
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan_cqm', 'rule_dm_bp_control_cqm');
--   NQF 0055 (PQRI 117) Diabetes: Eye Exam
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan_cqm', 'rule_dm_eye_cqm');
--   NQF 0056 (PQRI 163) Diabetes: Foot Exam
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan_cqm', 'rule_dm_foot_cqm');
-- Measure Group D: Preventative Care
--   NQF 0041 (PQRI 110) Influenza Immunization for Patients >= 50 Years Old
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan_cqm', 'rule_influenza_ge_50_cqm');
--   NQF 0043 (PQRI 111) Pneumonia Vaccination Status for Older Adults
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan_cqm', 'rule_pneumovacc_ge_65_cqm');
--   NQF 0421 (PQRI 128) Adult Weight Screening and Follow-Up
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan_cqm', 'rule_adult_wt_screen_fu_cqm');
--
-- Standard clinical plans to rules mappings
--
-- Diabetes Mellitus
--   Hemoglobin A1C
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan', 'rule_dm_hemo_a1c');
--   Urine Microalbumin
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan', 'rule_dm_urine_alb');
--   Eye Exam
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan', 'rule_dm_eye');
--   Foot Exam
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('dm_plan', 'rule_dm_foot');
-- Preventative Care
--   Hypertension: Blood Pressure Measurement
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_htn_bp_measure');
--   Tobacco Use Assessment
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_tob_use_assess');
--   Tobacco Cessation Intervention
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_tob_cess_inter');
--   Adult Weight Screening and Follow-Up
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_adult_wt_screen_fu');
--   Weight Assessment and Counseling for Children and Adolescents
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_wt_assess_couns_child');
--   Influenza Immunization for Patients >= 50 Years Old
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_influenza_ge_50');
--   Pneumonia Vaccination Status for Older Adults
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_pneumovacc_ge_65');
--   Cancer Screening: Mammogram
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_cs_mammo');
--   Cancer Screening: Pap Smear
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_cs_pap');
--   Cancer Screening: Colon Cancer Screening
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_cs_colon');
--   Cancer Screening: Prostate Cancer Screening
INSERT INTO `clinical_plans_rules` ( `plan_id`, `rule_id` ) VALUES ('prevent_plan', 'rule_cs_prostate');



DROP TABLE IF EXISTS `clinical_rules_log`;
CREATE TABLE `clinical_rules_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) NOT NULL DEFAULT '0',
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `category` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'An example category is clinical_reminder_widget',
  `value` TEXT NOT NULL,
  `new_value` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;



--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
CREATE TABLE `codes` (
  `id` int(11) NOT NULL auto_increment,
  `code_text` varchar(255) NOT NULL default '',
  `code_text_short` varchar(24) NOT NULL default '',
  `code` varchar(25) NOT NULL default '',
  `code_type` smallint(6) default NULL,
  `modifier` varchar(12) NOT NULL default '',
  `units` int(11) default NULL,
  `fee` decimal(12,2) default NULL,
  `superbill` varchar(31) NOT NULL default '',
  `related_code` varchar(255) NOT NULL default '',
  `taxrates` varchar(255) NOT NULL default '',
  `cyp_factor` float NOT NULL DEFAULT 0 COMMENT 'quantity representing a years supply',
  `active` TINYINT(1) DEFAULT 1 COMMENT '0 = inactive, 1 = active',
  `exclude_from_insurance_billing` TINYINT(1) DEFAULT '0' COMMENT '0 = include, 1 = exclude',
  `reportable` TINYINT(1) DEFAULT 0 COMMENT '0 = non-reportable, 1 = reportable',
  `financial_reporting` TINYINT(1) DEFAULT 0 COMMENT '0 = negative, 1 = considered important code in financial reporting',
  PRIMARY KEY  (`id`),
  KEY `code` (`code`),
  KEY `code_type` (`code_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `syndromic_surveillance`
--

DROP TABLE IF EXISTS `syndromic_surveillance`;
CREATE TABLE `syndromic_surveillance` (
  `id` bigint(20) NOT NULL auto_increment,
  `lists_id` bigint(20) NOT NULL,
  `submission_date` datetime NOT NULL,
  `filename` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY (`lists_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `value` varchar(255) default NULL,
  `parent` int(11) NOT NULL default '0',
  `lft` int(11) NOT NULL default '0',
  `rght` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`),
  KEY `lft` (`lft`,`rght`)
) ENGINE=InnoDB;


--
-- Table structure for table `config_seq`
--

DROP TABLE IF EXISTS `config_seq`;
CREATE TABLE `config_seq` (
  `id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Dumping data for table `config_seq`
--

INSERT INTO `config_seq` VALUES (0);


--
-- Table structure for table `dated_reminders`
--

DROP TABLE IF EXISTS `dated_reminders`;
CREATE TABLE `dated_reminders` (
  `dr_id` int(11) NOT NULL AUTO_INCREMENT,
  `dr_from_ID` int(11) NOT NULL,
  `dr_message_text` varchar(160) NOT NULL,
  `dr_message_sent_date` datetime NOT NULL,
  `dr_message_due_date` date NOT NULL,
  `pid` int(11) NOT NULL,
  `message_priority` tinyint(1) NOT NULL,
  `message_processed` tinyint(1) NOT NULL DEFAULT '0',
  `processed_date` timestamp NULL DEFAULT NULL,
  `dr_processed_by` int(11) NOT NULL,
  PRIMARY KEY (`dr_id`),
  KEY `dr_from_ID` (`dr_from_ID`,`dr_message_due_date`)
) ENGINE=InnoDB AUTO_INCREMENT=1;


--
-- Table structure for table `dated_reminders_link`
--

DROP TABLE IF EXISTS `dated_reminders_link`;
CREATE TABLE `dated_reminders_link` (
  `dr_link_id` int(11) NOT NULL AUTO_INCREMENT,
  `dr_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  PRIMARY KEY (`dr_link_id`),
  KEY `to_id` (`to_id`),
  KEY `dr_id` (`dr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;


--
-- Table structure for table `direct_message_log`
--

DROP TABLE IF EXISTS `direct_message_log`;
CREATE TABLE `direct_message_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `msg_type` char(1) NOT NULL COMMENT 'S=sent,R=received',
  `msg_id` varchar(127) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `create_ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` char(1) NOT NULL COMMENT 'Q=queued,D=dispatched,R=received,F=failed',
  `status_info` varchar(511) default NULL,
  `status_ts` timestamp NULL default NULL,
  `patient_id` bigint(20) default NULL,
  `user_id` bigint(20) default NULL,
  PRIMARY KEY  (`id`),
  KEY `msg_id` (`msg_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB;


--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` int(11) NOT NULL default '0',
  `type` enum('file_url','blob','web_url') default NULL,
  `size` int(11) default NULL,
  `date` datetime default NULL,
  `url` varchar(255) default NULL,
  `mimetype` varchar(255) default NULL,
  `pages` int(11) default NULL,
  `owner` int(11) default NULL,
  `revision` timestamp NOT NULL,
  `foreign_id` int(11) default NULL,
  `docdate` date default NULL,
  `hash` varchar(40) DEFAULT NULL COMMENT '40-character SHA-1 hash of document',
  `list_id` bigint(20) NOT NULL default '0',
  `couch_docid` VARCHAR(100) DEFAULT NULL,
  `couch_revid` VARCHAR(100) DEFAULT NULL,
  `storagemethod` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0->Harddisk,1->CouchDB',
  `path_depth` TINYINT DEFAULT '1' COMMENT 'Depth of path to use in url to find document. Not applicable for CouchDB.',
  `imported` TINYINT DEFAULT 0 NULL COMMENT 'Parsing status for CCR/CCD/CCDA importing',
  `encounter_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Encounter id if tagged',
  `encounter_check` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'If encounter is created while tagging',
  `audit_master_approval_status` TINYINT NOT NULL DEFAULT 1 COMMENT 'approval_status from audit_master table',
  `audit_master_id` int(11) default NULL,
  `documentationOf` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `revision` (`revision`),
  KEY `foreign_id` (`foreign_id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB;

--
-- Table structure for table `drug_inventory`
--

DROP TABLE IF EXISTS `drug_inventory`;
CREATE TABLE `drug_inventory` (
  `inventory_id` int(11) NOT NULL auto_increment,
  `drug_id` int(11) NOT NULL,
  `lot_number` varchar(20) default NULL,
  `expiration` date default NULL,
  `manufacturer` varchar(255) default NULL,
  `on_hand` int(11) NOT NULL default '0',
  `warehouse_id` varchar(31) NOT NULL DEFAULT '',
  `vendor_id` bigint(20) NOT NULL DEFAULT 0,
  `last_notify` date NOT NULL default '0000-00-00',
  `destroy_date` date default NULL,
  `destroy_method` varchar(255) default NULL,
  `destroy_witness` varchar(255) default NULL,
  `destroy_notes` varchar(255) default NULL,
  PRIMARY KEY  (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `drug_sales`
--

DROP TABLE IF EXISTS `drug_sales`;
CREATE TABLE `drug_sales` (
  `sale_id` int(11) NOT NULL auto_increment,
  `drug_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL default '0',
  `pid` int(11) NOT NULL default '0',
  `encounter` int(11) NOT NULL default '0',
  `user` varchar(255) default NULL,
  `sale_date` date NOT NULL,
  `quantity` int(11) NOT NULL default '0',
  `fee` decimal(12,2) NOT NULL default '0.00',
  `billed` tinyint(1) NOT NULL default '0' COMMENT 'indicates if the sale is posted to accounting',
  `xfer_inventory_id` int(11) NOT NULL DEFAULT 0,
  `distributor_id` bigint(20) NOT NULL DEFAULT 0 COMMENT 'references users.id',
  `notes` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`sale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `drug_templates`
--

DROP TABLE IF EXISTS `drug_templates`;
CREATE TABLE `drug_templates` (
  `drug_id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL default '',
  `dosage` varchar(10) default NULL,
  `period` int(11) NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `refills` int(11) NOT NULL default '0',
  `taxrates` varchar(255) default NULL,
  PRIMARY KEY  (`drug_id`,`selector`)
) ENGINE=InnoDB;


--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
CREATE TABLE `drugs` (
  `drug_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL DEFAULT '',
  `ndc_number` varchar(20) NOT NULL DEFAULT '',
  `on_order` int(11) NOT NULL default '0',
  `reorder_point` float NOT NULL DEFAULT 0.0,
  `max_level` float NOT NULL DEFAULT 0.0,
  `last_notify` date NOT NULL default '0000-00-00',
  `reactions` text,
  `form` int(3) NOT NULL default '0',
  `size` float unsigned NOT NULL default '0',
  `unit` int(11) NOT NULL default '0',
  `route` int(11) NOT NULL default '0',
  `substitute` int(11) NOT NULL default '0',
  `related_code` varchar(255) NOT NULL DEFAULT '' COMMENT 'may reference a related codes.code',
  `cyp_factor` float NOT NULL DEFAULT 0 COMMENT 'quantity representing a years supply',
  `active` TINYINT(1) DEFAULT 1 COMMENT '0 = inactive, 1 = active',
  `allow_combining` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = allow filling an order from multiple lots',
  `allow_multiple`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = allow multiple lots at one warehouse',
  `drug_code` varchar(25) NULL,
  PRIMARY KEY  (`drug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `eligibility_response`
--

DROP TABLE IF EXISTS `eligibility_response`;
CREATE TABLE `eligibility_response` (
  `response_id` bigint(20) NOT NULL auto_increment,
  `response_description` varchar(255) default NULL,
  `response_status` enum('A','D') NOT NULL default 'A',
  `response_vendor_id` bigint(20) default NULL,
  `response_create_date` date default NULL,
  `response_modify_date` date default NULL,
  PRIMARY KEY  (`response_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;


--
-- Table structure for table `eligibility_verification`
--

DROP TABLE IF EXISTS `eligibility_verification`;
CREATE TABLE `eligibility_verification` (
  `verification_id` bigint(20) NOT NULL auto_increment,
  `response_id` bigint(20) default NULL,
  `insurance_id` bigint(20) default NULL,
  `eligibility_check_date` datetime default NULL,
  `copay` int(11) default NULL,
  `deductible` int(11) default NULL,
  `deductiblemet` enum('Y','N') default 'Y',
  `create_date` date default NULL,
  PRIMARY KEY  (`verification_id`),
  KEY `insurance_id` (`insurance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;


--
-- Table structure for table `employer_data`
--

DROP TABLE IF EXISTS `employer_data`;
CREATE TABLE `employer_data` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `street` varchar(255) default NULL,
  `postal_code` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `state` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `date` datetime default NULL,
  `pid` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `erx_ttl_touch`
--
-- Store records last update per patient data process
--

DROP TABLE IF EXISTS `erx_ttl_touch`;
CREATE  TABLE `erx_ttl_touch` (
  `patient_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Patient record Id' ,
  `process` ENUM('allergies','medications') NOT NULL COMMENT 'NewCrop eRx SOAP process' ,
  `updated` DATETIME NOT NULL COMMENT 'Date and time of last process update for patient' ,
  PRIMARY KEY (`patient_id`, `process`)
) ENGINE = InnoDB COMMENT = 'Store records last update per patient data process' ;


--
-- Table structure for table `standardized_tables_track`
--

DROP TABLE IF EXISTS `standardized_tables_track`;
CREATE TABLE `standardized_tables_track` (
  `id` int(11) NOT NULL auto_increment,
  `imported_date` datetime default NULL,
  `name` varchar(255) NOT NULL default '' COMMENT 'name of standardized tables such as RXNORM',
  `revision_version` varchar(255) NOT NULL default '' COMMENT 'revision of standardized tables that were imported',
  `revision_date` datetime default NULL COMMENT 'revision of standardized tables that were imported',
  `file_checksum` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `facility`
--

DROP TABLE IF EXISTS `facility`;
CREATE TABLE `facility` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `alias` varchar(60) default NULL,
  `phone` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `street` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `state` varchar(50) default NULL,
  `postal_code` varchar(11) default NULL,
  `country_code` varchar(10) default NULL,
  `federal_ein` varchar(15) default NULL,
  `website` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `service_location` tinyint(1) NOT NULL default '1',
  `billing_location` tinyint(1) NOT NULL default '0',
  `accepts_assignment` tinyint(1) NOT NULL default '0',
  `pos_code` tinyint(4) default NULL,
  `x12_sender_id` varchar(25) default NULL,
  `attn` varchar(65) default NULL,
  `domain_identifier` varchar(60) default NULL,
  `facility_npi` varchar(15) default NULL,
  `tax_id_type` VARCHAR(31) NOT NULL DEFAULT '',
  `color` VARCHAR(7) NOT NULL DEFAULT '',
  `primary_business_entity` INT(10) NOT NULL DEFAULT '0' COMMENT '0-Not Set as business entity 1-Set as business entity',
  `facility_code` VARCHAR(31) default NULL,
  `inactive` TINYINT(1)  NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` VALUES (1, 'Your Clinic Name Here', 'Your Clinic Name Here', '000-000-0000', '000-000-0000', '', '', '', '', '', '', NULL, NULL, 1, 1, 0, NULL, '', '', '', '', '','#99FFFF','0','','0');


--
-- Table structure for table `facility_user_ids`
--

DROP TABLE IF EXISTS `facility_user_ids`;
CREATE TABLE  `facility_user_ids` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL,
  `facility_id` bigint(20) DEFAULT NULL,
  `field_id`    varchar(31)  NOT NULL COMMENT 'references layout_options.field_id',
  `field_value` TEXT,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`facility_id`,`field_id`)
) ENGINE=InnoDB  AUTO_INCREMENT=1 ;


--
-- Table structure for table `fee_sheet_options`
--

DROP TABLE IF EXISTS `fee_sheet_options`;
CREATE TABLE `fee_sheet_options` (
  `fs_category` varchar(63) default NULL,
  `fs_option` varchar(63) default NULL,
  `fs_codes` varchar(255) default NULL
) ENGINE=InnoDB;

--
-- Dumping data for table `fee_sheet_options`
--

INSERT INTO `fee_sheet_options` VALUES ('1New Patient', '1Brief', 'CPT4|99201|');
INSERT INTO `fee_sheet_options` VALUES ('1New Patient', '2Limited', 'CPT4|99202|');
INSERT INTO `fee_sheet_options` VALUES ('1New Patient', '3Detailed', 'CPT4|99203|');
INSERT INTO `fee_sheet_options` VALUES ('1New Patient', '4Extended', 'CPT4|99204|');
INSERT INTO `fee_sheet_options` VALUES ('1New Patient', '5Comprehensive', 'CPT4|99205|');
INSERT INTO `fee_sheet_options` VALUES ('2Established Patient', '1Brief', 'CPT4|99211|');
INSERT INTO `fee_sheet_options` VALUES ('2Established Patient', '2Limited', 'CPT4|99212|');
INSERT INTO `fee_sheet_options` VALUES ('2Established Patient', '3Detailed', 'CPT4|99213|');
INSERT INTO `fee_sheet_options` VALUES ('2Established Patient', '4Extended', 'CPT4|99214|');
INSERT INTO `fee_sheet_options` VALUES ('2Established Patient', '5Comprehensive', 'CPT4|99215|');


--
-- Table structure for table `form_dictation`
--

DROP TABLE IF EXISTS `form_dictation`;
CREATE TABLE `form_dictation` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `pid` bigint(20) default NULL,
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `authorized` tinyint(4) default NULL,
  `activity` tinyint(4) default NULL,
  `dictation` longtext,
  `additional_notes` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `form_encounter`
--

DROP TABLE IF EXISTS `form_encounter`;
CREATE TABLE `form_encounter` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `reason` longtext,
  `facility` longtext,
  `facility_id` int(11) NOT NULL default '0',
  `pid` bigint(20) default NULL,
  `encounter` bigint(20) default NULL,
  `onset_date` datetime default NULL,
  `sensitivity` varchar(30) default NULL,
  `billing_note` text,
  `pc_catid` int(11) NOT NULL default '5' COMMENT 'event category from libreehr_postcalendar_categories',
  `last_level_billed` int  NOT NULL DEFAULT 0 COMMENT '0=none, 1=ins1, 2=ins2, etc',
  `last_level_closed` int  NOT NULL DEFAULT 0 COMMENT '0=none, 1=ins1, 2=ins2, etc',
  `last_stmt_date`    date DEFAULT NULL,
  `stmt_count`        int  NOT NULL DEFAULT 0,
  `provider_id` INT(11) DEFAULT '0' COMMENT 'default and main provider for this visit',
  `supervisor_id` INT(11) DEFAULT '0' COMMENT 'supervising provider, if any, for this visit',
  `ordering_physician` INT(11) DEFAULT '0' COMMENT 'ordering provider , if any, for this visit',
  `referring_physician` INT(11) DEFAULT '0' COMMENT 'referring provider, if any, for this visit',
  `contract_physician` INT(11) DEFAULT '0' COMMENT 'contract provider, if any, for this visit',
  `invoice_refno` varchar(31) NOT NULL DEFAULT '',
  `referral_source` varchar(31) NOT NULL DEFAULT '',
  `billing_facility` INT(11) NOT NULL DEFAULT 0,
  `external_id` VARCHAR(20) DEFAULT NULL,
  `eft_number` varchar(80) DEFAULT NULL,
  `claim_number` varchar(80) DEFAULT NULL,
  `document_image` varchar(80) DEFAULT NULL,
  `seq_number` varchar(80) DEFAULT NULL,
  `coding_complete` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `pid_encounter` (`pid`, `encounter`),
  KEY `encounter_date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `form_annotate_diagram`
--

DROP TABLE IF EXISTS `form_annotate_diagram`;
CREATE TABLE `form_annotate_diagram` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT '1',
  `data` text ,
  `imagedata` varchar(255) DEFAULT 'NEW',
  `dyntitle` varchar(255) DEFAULT 'Annotated Diagram',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `form_misc_billing_options`
--

DROP TABLE IF EXISTS `form_misc_billing_options`;
CREATE TABLE `form_misc_billing_options` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `pid` bigint(20) default NULL,
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `authorized` tinyint(4) default NULL,
  `activity` tinyint(4) default NULL,
  `employment_related` tinyint(1) default NULL,
  `auto_accident` tinyint(1) default NULL,
  `accident_state` varchar(2) default NULL,
  `other_accident` tinyint(1) default NULL,
  `medicaid_referral_code` varchar(2)   default NULL,
  `epsdt_flag` tinyint(1) default NULL,
  `provider_qualifier_code` varchar(2) default NULL,
  `provider_id` int(11) default NULL,
  `outside_lab` tinyint(1) default NULL,
  `lab_amount` decimal(5,2) default NULL,
  `is_unable_to_work` tinyint(1) default NULL,
  `date_initial_treatment` date default NULL,
  `off_work_from` date default NULL,
  `off_work_to` date default NULL,
  `is_hospitalized` tinyint(1) default NULL,
  `hospitalization_date_from` date default NULL,
  `hospitalization_date_to` date default NULL,
  `medicaid_resubmission_code` varchar(10) default NULL,
  `medicaid_original_reference` varchar(15) default NULL,
  `prior_auth_number` varchar(20) default NULL,
  `comments` varchar(255) default NULL,
  `replacement_claim` tinyint(1) default 0,
  `icn_resubmission_number` varchar(35) default NULL,
  `box_14_date_qual` char(3) default NULL,
  `box_15_date_qual` char(3) default NULL,
  `onset_date` DATE NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `form_ros`
--

DROP TABLE IF EXISTS `form_ros`;
CREATE TABLE `form_ros` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `activity` int(11) NOT NULL default '1',
  `date` datetime default NULL,
  `weight_change` varchar(3) default NULL,
  `weakness` varchar(3) default NULL,
  `fatigue` varchar(3) default NULL,
  `anorexia` varchar(3) default NULL,
  `fever` varchar(3) default NULL,
  `chills` varchar(3) default NULL,
  `night_sweats` varchar(3) default NULL,
  `insomnia` varchar(3) default NULL,
  `irritability` varchar(3) default NULL,
  `heat_or_cold` varchar(3) default NULL,
  `intolerance` varchar(3) default NULL,
  `change_in_vision` varchar(3) default NULL,
  `glaucoma_history` varchar(3) default NULL,
  `eye_pain` varchar(3) default NULL,
  `irritation` varchar(3) default NULL,
  `redness` varchar(3) default NULL,
  `excessive_tearing` varchar(3) default NULL,
  `double_vision` varchar(3) default NULL,
  `blind_spots` varchar(3) default NULL,
  `photophobia` varchar(3) default NULL,
  `hearing_loss` varchar(3) default NULL,
  `discharge` varchar(3) default NULL,
  `pain` varchar(3) default NULL,
  `vertigo` varchar(3) default NULL,
  `tinnitus` varchar(3) default NULL,
  `frequent_colds` varchar(3) default NULL,
  `sore_throat` varchar(3) default NULL,
  `sinus_problems` varchar(3) default NULL,
  `post_nasal_drip` varchar(3) default NULL,
  `nosebleed` varchar(3) default NULL,
  `snoring` varchar(3) default NULL,
  `apnea` varchar(3) default NULL,
  `breast_mass` varchar(3) default NULL,
  `breast_discharge` varchar(3) default NULL,
  `biopsy` varchar(3) default NULL,
  `abnormal_mammogram` varchar(3) default NULL,
  `cough` varchar(3) default NULL,
  `sputum` varchar(3) default NULL,
  `shortness_of_breath` varchar(3) default NULL,
  `wheezing` varchar(3) default NULL,
  `hemoptsyis` varchar(3) default NULL,
  `asthma` varchar(3) default NULL,
  `copd` varchar(3) default NULL,
  `chest_pain` varchar(3) default NULL,
  `palpitation` varchar(3) default NULL,
  `syncope` varchar(3) default NULL,
  `pnd` varchar(3) default NULL,
  `doe` varchar(3) default NULL,
  `orthopnea` varchar(3) default NULL,
  `peripheal` varchar(3) default NULL,
  `edema` varchar(3) default NULL,
  `legpain_cramping` varchar(3) default NULL,
  `history_murmur` varchar(3) default NULL,
  `arrythmia` varchar(3) default NULL,
  `heart_problem` varchar(3) default NULL,
  `dysphagia` varchar(3) default NULL,
  `heartburn` varchar(3) default NULL,
  `bloating` varchar(3) default NULL,
  `belching` varchar(3) default NULL,
  `flatulence` varchar(3) default NULL,
  `nausea` varchar(3) default NULL,
  `vomiting` varchar(3) default NULL,
  `hematemesis` varchar(3) default NULL,
  `gastro_pain` varchar(3) default NULL,
  `food_intolerance` varchar(3) default NULL,
  `hepatitis` varchar(3) default NULL,
  `jaundice` varchar(3) default NULL,
  `hematochezia` varchar(3) default NULL,
  `changed_bowel` varchar(3) default NULL,
  `diarrhea` varchar(3) default NULL,
  `constipation` varchar(3) default NULL,
  `polyuria` varchar(3) default NULL,
  `polydypsia` varchar(3) default NULL,
  `dysuria` varchar(3) default NULL,
  `hematuria` varchar(3) default NULL,
  `frequency` varchar(3) default NULL,
  `urgency` varchar(3) default NULL,
  `incontinence` varchar(3) default NULL,
  `renal_stones` varchar(3) default NULL,
  `utis` varchar(3) default NULL,
  `hesitancy` varchar(3) default NULL,
  `dribbling` varchar(3) default NULL,
  `stream` varchar(3) default NULL,
  `nocturia` varchar(3) default NULL,
  `erections` varchar(3) default NULL,
  `ejaculations` varchar(3) default NULL,
  `g` varchar(3) default NULL,
  `p` varchar(3) default NULL,
  `ap` varchar(3) default NULL,
  `lc` varchar(3) default NULL,
  `mearche` varchar(3) default NULL,
  `menopause` varchar(3) default NULL,
  `lmp` varchar(3) default NULL,
  `f_frequency` varchar(3) default NULL,
  `f_flow` varchar(3) default NULL,
  `f_symptoms` varchar(3) default NULL,
  `abnormal_hair_growth` varchar(3) default NULL,
  `f_hirsutism` varchar(3) default NULL,
  `joint_pain` varchar(3) default NULL,
  `swelling` varchar(3) default NULL,
  `m_redness` varchar(3) default NULL,
  `m_warm` varchar(3) default NULL,
  `m_stiffness` varchar(3) default NULL,
  `muscle` varchar(3) default NULL,
  `m_aches` varchar(3) default NULL,
  `fms` varchar(3) default NULL,
  `arthritis` varchar(3) default NULL,
  `loc` varchar(3) default NULL,
  `seizures` varchar(3) default NULL,
  `stroke` varchar(3) default NULL,
  `tia` varchar(3) default NULL,
  `n_numbness` varchar(3) default NULL,
  `n_weakness` varchar(3) default NULL,
  `paralysis` varchar(3) default NULL,
  `intellectual_decline` varchar(3) default NULL,
  `memory_problems` varchar(3) default NULL,
  `dementia` varchar(3) default NULL,
  `n_headache` varchar(3) default NULL,
  `s_cancer` varchar(3) default NULL,
  `psoriasis` varchar(3) default NULL,
  `s_acne` varchar(3) default NULL,
  `s_other` varchar(3) default NULL,
  `s_disease` varchar(3) default NULL,
  `p_diagnosis` varchar(3) default NULL,
  `p_medication` varchar(3) default NULL,
  `depression` varchar(3) default NULL,
  `anxiety` varchar(3) default NULL,
  `social_difficulties` varchar(3) default NULL,
  `thyroid_problems` varchar(3) default NULL,
  `diabetes` varchar(3) default NULL,
  `abnormal_blood` varchar(3) default NULL,
  `anemia` varchar(3) default NULL,
  `fh_blood_problems` varchar(3) default NULL,
  `bleeding_problems` varchar(3) default NULL,
  `allergies` varchar(3) default NULL,
  `frequent_illness` varchar(3) default NULL,
  `hiv` varchar(3) default NULL,
  `hai_status` varchar(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `form_soap`
--

DROP TABLE IF EXISTS `form_soap`;
CREATE TABLE `form_soap` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `pid` bigint(20) default '0',
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `authorized` tinyint(4) default '0',
  `activity` tinyint(4) default '0',
  `subjective` text,
  `objective` text,
  `assessment` text,
  `plan` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `form_vitals`
--

DROP TABLE IF EXISTS `form_vitals`;
CREATE TABLE `form_vitals` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `pid` bigint(20) default '0',
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `authorized` tinyint(4) default '0',
  `activity` tinyint(4) default '0',
  `bps` varchar(40) default NULL,
  `bpd` varchar(40) default NULL,
  `weight` float(5,2) default '0.00',
  `height` float(5,2) default '0.00',
  `temperature` float(5,2) default '0.00',
  `temp_method` varchar(255) default NULL,
  `pulse` float(5,2) default '0.00',
  `respiration` float(5,2) default '0.00',
  `note` varchar(255) default NULL,
  `BMI` float(4,1) default '0.0',
  `BMI_status` varchar(255) default NULL,
  `waist_circ` float(5,2) default '0.00',
  `head_circ` float(4,2) default '0.00',
  `oxygen_saturation` float(5,2) default '0.00',
  `external_id` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
CREATE TABLE `forms` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `encounter` bigint(20) default NULL,
  `form_name` longtext,
  `form_id` bigint(20) default NULL,
  `pid` bigint(20) default NULL,
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `authorized` tinyint(4) default NULL,
  `deleted` tinyint(4) DEFAULT '0' NOT NULL COMMENT 'flag indicates form has been deleted',
  `formdir` longtext,
  PRIMARY KEY  (`id`),
  KEY `pid_encounter` (`pid`, `encounter`),
  KEY `form_id` (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `geo_country_reference`
--

DROP TABLE IF EXISTS `geo_country_reference`;
CREATE TABLE `geo_country_reference` (
  `countries_id` int(5) NOT NULL auto_increment,
  `countries_name` varchar(64) default NULL,
  `countries_iso_code_2` char(2) NOT NULL default '',
  `countries_iso_code_3` char(3) NOT NULL default '',
  PRIMARY KEY  (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=InnoDB AUTO_INCREMENT=240 ;

--
-- Dumping data for table `geo_country_reference`
--

INSERT INTO `geo_country_reference` (`countries_id`, `countries_name`, `countries_iso_code_2`, `countries_iso_code_3`) VALUES
(1, 'Afghanistan', 'AF', 'AFG'),
(2, 'Albania', 'AL', 'ALB'),
(3, 'Algeria', 'DZ', 'DZA'),
(4, 'American Samoa', 'AS', 'ASM'),
(5, 'Andorra', 'AD', 'AND'),
(6, 'Angola', 'AO', 'AGO'),
(7, 'Anguilla', 'AI', 'AIA'),
(8, 'Antarctica', 'AQ', 'ATA'),
(9, 'Antigua and Barbuda', 'AG', 'ATG'),
(10, 'Argentina', 'AR', 'ARG'),
(11, 'Armenia', 'AM', 'ARM'),
(12, 'Aruba', 'AW', 'ABW'),
(13, 'Australia', 'AU', 'AUS'),
(14, 'Austria', 'AT', 'AUT'),
(15, 'Azerbaijan', 'AZ', 'AZE'),
(16, 'Bahamas', 'BS', 'BHS'),
(17, 'Bahrain', 'BH', 'BHR'),
(18, 'Bangladesh', 'BD', 'BGD'),
(19, 'Barbados', 'BB', 'BRB'),
(20, 'Belarus', 'BY', 'BLR'),
(21, 'Belgium', 'BE', 'BEL'),
(22, 'Belize', 'BZ', 'BLZ'),
(23, 'Benin', 'BJ', 'BEN'),
(24, 'Bermuda', 'BM', 'BMU'),
(25, 'Bhutan', 'BT', 'BTN'),
(26, 'Bolivia', 'BO', 'BOL'),
(27, 'Bosnia and Herzegowina', 'BA', 'BIH'),
(28, 'Botswana', 'BW', 'BWA'),
(29, 'Bouvet Island', 'BV', 'BVT'),
(30, 'Brazil', 'BR', 'BRA'),
(31, 'British Indian Ocean Territory', 'IO', 'IOT'),
(32, 'Brunei Darussalam', 'BN', 'BRN'),
(33, 'Bulgaria', 'BG', 'BGR'),
(34, 'Burkina Faso', 'BF', 'BFA'),
(35, 'Burundi', 'BI', 'BDI'),
(36, 'Cambodia', 'KH', 'KHM'),
(37, 'Cameroon', 'CM', 'CMR'),
(38, 'Canada', 'CA', 'CAN'),
(39, 'Cape Verde', 'CV', 'CPV'),
(40, 'Cayman Islands', 'KY', 'CYM'),
(41, 'Central African Republic', 'CF', 'CAF'),
(42, 'Chad', 'TD', 'TCD'),
(43, 'Chile', 'CL', 'CHL'),
(44, 'China', 'CN', 'CHN'),
(45, 'Christmas Island', 'CX', 'CXR'),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK'),
(47, 'Colombia', 'CO', 'COL'),
(48, 'Comoros', 'KM', 'COM'),
(49, 'Congo', 'CG', 'COG'),
(50, 'Cook Islands', 'CK', 'COK'),
(51, 'Costa Rica', 'CR', 'CRI'),
(52, 'Cote D Ivoire', 'CI', 'CIV'),
(53, 'Croatia', 'HR', 'HRV'),
(54, 'Cuba', 'CU', 'CUB'),
(55, 'Cyprus', 'CY', 'CYP'),
(56, 'Czech Republic', 'CZ', 'CZE'),
(57, 'Denmark', 'DK', 'DNK'),
(58, 'Djibouti', 'DJ', 'DJI'),
(59, 'Dominica', 'DM', 'DMA'),
(60, 'Dominican Republic', 'DO', 'DOM'),
(61, 'East Timor', 'TP', 'TMP'),
(62, 'Ecuador', 'EC', 'ECU'),
(63, 'Egypt', 'EG', 'EGY'),
(64, 'El Salvador', 'SV', 'SLV'),
(65, 'Equatorial Guinea', 'GQ', 'GNQ'),
(66, 'Eritrea', 'ER', 'ERI'),
(67, 'Estonia', 'EE', 'EST'),
(68, 'Ethiopia', 'ET', 'ETH'),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK'),
(70, 'Faroe Islands', 'FO', 'FRO'),
(71, 'Fiji', 'FJ', 'FJI'),
(72, 'Finland', 'FI', 'FIN'),
(73, 'France', 'FR', 'FRA'),
(74, 'France, MEtropolitan', 'FX', 'FXX'),
(75, 'French Guiana', 'GF', 'GUF'),
(76, 'French Polynesia', 'PF', 'PYF'),
(77, 'French Southern Territories', 'TF', 'ATF'),
(78, 'Gabon', 'GA', 'GAB'),
(79, 'Gambia', 'GM', 'GMB'),
(80, 'Georgia', 'GE', 'GEO'),
(81, 'Germany', 'DE', 'DEU'),
(82, 'Ghana', 'GH', 'GHA'),
(83, 'Gibraltar', 'GI', 'GIB'),
(84, 'Greece', 'GR', 'GRC'),
(85, 'Greenland', 'GL', 'GRL'),
(86, 'Grenada', 'GD', 'GRD'),
(87, 'Guadeloupe', 'GP', 'GLP'),
(88, 'Guam', 'GU', 'GUM'),
(89, 'Guatemala', 'GT', 'GTM'),
(90, 'Guinea', 'GN', 'GIN'),
(91, 'Guinea-bissau', 'GW', 'GNB'),
(92, 'Guyana', 'GY', 'GUY'),
(93, 'Haiti', 'HT', 'HTI'),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD'),
(95, 'Honduras', 'HN', 'HND'),
(96, 'Hong Kong', 'HK', 'HKG'),
(97, 'Hungary', 'HU', 'HUN'),
(98, 'Iceland', 'IS', 'ISL'),
(99, 'India', 'IN', 'IND'),
(100, 'Indonesia', 'ID', 'IDN'),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN'),
(102, 'Iraq', 'IQ', 'IRQ'),
(103, 'Ireland', 'IE', 'IRL'),
(104, 'Israel', 'IL', 'ISR'),
(105, 'Italy', 'IT', 'ITA'),
(106, 'Jamaica', 'JM', 'JAM'),
(107, 'Japan', 'JP', 'JPN'),
(108, 'Jordan', 'JO', 'JOR'),
(109, 'Kazakhstan', 'KZ', 'KAZ'),
(110, 'Kenya', 'KE', 'KEN'),
(111, 'Kiribati', 'KI', 'KIR'),
(112, 'Korea, Democratic Peoples Republic of', 'KP', 'PRK'),
(113, 'Korea, Republic of', 'KR', 'KOR'),
(114, 'Kuwait', 'KW', 'KWT'),
(115, 'Kyrgyzstan', 'KG', 'KGZ'),
(116, 'Lao Peoples Democratic Republic', 'LA', 'LAO'),
(117, 'Latvia', 'LV', 'LVA'),
(118, 'Lebanon', 'LB', 'LBN'),
(119, 'Lesotho', 'LS', 'LSO'),
(120, 'Liberia', 'LR', 'LBR'),
(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY'),
(122, 'Liechtenstein', 'LI', 'LIE'),
(123, 'Lithuania', 'LT', 'LTU'),
(124, 'Luxembourg', 'LU', 'LUX'),
(125, 'Macau', 'MO', 'MAC'),
(126, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD'),
(127, 'Madagascar', 'MG', 'MDG'),
(128, 'Malawi', 'MW', 'MWI'),
(129, 'Malaysia', 'MY', 'MYS'),
(130, 'Maldives', 'MV', 'MDV'),
(131, 'Mali', 'ML', 'MLI'),
(132, 'Malta', 'MT', 'MLT'),
(133, 'Marshall Islands', 'MH', 'MHL'),
(134, 'Martinique', 'MQ', 'MTQ'),
(135, 'Mauritania', 'MR', 'MRT'),
(136, 'Mauritius', 'MU', 'MUS'),
(137, 'Mayotte', 'YT', 'MYT'),
(138, 'Mexico', 'MX', 'MEX'),
(139, 'Micronesia, Federated States of', 'FM', 'FSM'),
(140, 'Moldova, Republic of', 'MD', 'MDA'),
(141, 'Monaco', 'MC', 'MCO'),
(142, 'Mongolia', 'MN', 'MNG'),
(143, 'Montserrat', 'MS', 'MSR'),
(144, 'Morocco', 'MA', 'MAR'),
(145, 'Mozambique', 'MZ', 'MOZ'),
(146, 'Myanmar', 'MM', 'MMR'),
(147, 'Namibia', 'NA', 'NAM'),
(148, 'Nauru', 'NR', 'NRU'),
(149, 'Nepal', 'NP', 'NPL'),
(150, 'Netherlands', 'NL', 'NLD'),
(151, 'Netherlands Antilles', 'AN', 'ANT'),
(152, 'New Caledonia', 'NC', 'NCL'),
(153, 'New Zealand', 'NZ', 'NZL'),
(154, 'Nicaragua', 'NI', 'NIC'),
(155, 'Niger', 'NE', 'NER'),
(156, 'Nigeria', 'NG', 'NGA'),
(157, 'Niue', 'NU', 'NIU'),
(158, 'Norfolk Island', 'NF', 'NFK'),
(159, 'Northern Mariana Islands', 'MP', 'MNP'),
(160, 'Norway', 'NO', 'NOR'),
(161, 'Oman', 'OM', 'OMN'),
(162, 'Pakistan', 'PK', 'PAK'),
(163, 'Palau', 'PW', 'PLW'),
(164, 'Panama', 'PA', 'PAN'),
(165, 'Papua New Guinea', 'PG', 'PNG'),
(166, 'Paraguay', 'PY', 'PRY'),
(167, 'Peru', 'PE', 'PER'),
(168, 'Philippines', 'PH', 'PHL'),
(169, 'Pitcairn', 'PN', 'PCN'),
(170, 'Poland', 'PL', 'POL'),
(171, 'Portugal', 'PT', 'PRT'),
(172, 'Puerto Rico', 'PR', 'PRI'),
(173, 'Qatar', 'QA', 'QAT'),
(174, 'Reunion', 'RE', 'REU'),
(175, 'Romania', 'RO', 'ROM'),
(176, 'Russian Federation', 'RU', 'RUS'),
(177, 'Rwanda', 'RW', 'RWA'),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA'),
(179, 'Saint Lucia', 'LC', 'LCA'),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT'),
(181, 'Samoa', 'WS', 'WSM'),
(182, 'San Marino', 'SM', 'SMR'),
(183, 'Sao Tome and Principe', 'ST', 'STP'),
(184, 'Saudi Arabia', 'SA', 'SAU'),
(185, 'Senegal', 'SN', 'SEN'),
(186, 'Seychelles', 'SC', 'SYC'),
(187, 'Sierra Leone', 'SL', 'SLE'),
(188, 'Singapore', 'SG', 'SGP'),
(189, 'Slovakia (Slovak Republic)', 'SK', 'SVK'),
(190, 'Slovenia', 'SI', 'SVN'),
(191, 'Solomon Islands', 'SB', 'SLB'),
(192, 'Somalia', 'SO', 'SOM'),
(193, 'south Africa', 'ZA', 'ZAF'),
(194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS'),
(195, 'Spain', 'ES', 'ESP'),
(196, 'Sri Lanka', 'LK', 'LKA'),
(197, 'St. Helena', 'SH', 'SHN'),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM'),
(199, 'Sudan', 'SD', 'SDN'),
(200, 'Suriname', 'SR', 'SUR'),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM'),
(202, 'Swaziland', 'SZ', 'SWZ'),
(203, 'Sweden', 'SE', 'SWE'),
(204, 'Switzerland', 'CH', 'CHE'),
(205, 'Syrian Arab Republic', 'SY', 'SYR'),
(206, 'Taiwan, Province of China', 'TW', 'TWN'),
(207, 'Tajikistan', 'TJ', 'TJK'),
(208, 'Tanzania, United Republic of', 'TZ', 'TZA'),
(209, 'Thailand', 'TH', 'THA'),
(210, 'Togo', 'TG', 'TGO'),
(211, 'Tokelau', 'TK', 'TKL'),
(212, 'Tonga', 'TO', 'TON'),
(213, 'Trinidad and Tobago', 'TT', 'TTO'),
(214, 'Tunisia', 'TN', 'TUN'),
(215, 'Turkey', 'TR', 'TUR'),
(216, 'Turkmenistan', 'TM', 'TKM'),
(217, 'Turks and Caicos Islands', 'TC', 'TCA'),
(218, 'Tuvalu', 'TV', 'TUV'),
(219, 'Uganda', 'UG', 'UGA'),
(220, 'Ukraine', 'UA', 'UKR'),
(221, 'United Arab Emirates', 'AE', 'ARE'),
(222, 'United Kingdom', 'GB', 'GBR'),
(223, 'United States', 'US', 'USA'),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI'),
(225, 'Uruguay', 'UY', 'URY'),
(226, 'Uzbekistan', 'UZ', 'UZB'),
(227, 'Vanuatu', 'VU', 'VUT'),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT'),
(229, 'Venezuela', 'VE', 'VEN'),
(230, 'Viet Nam', 'VN', 'VNM'),
(231, 'Virgin Islands (British)', 'VG', 'VGB'),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR'),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF'),
(234, 'Western Sahara', 'EH', 'ESH'),
(235, 'Yemen', 'YE', 'YEM'),
(236, 'Yugoslavia', 'YU', 'YUG'),
(237, 'Zaire', 'ZR', 'ZAR'),
(238, 'Zambia', 'ZM', 'ZMB'),
(239, 'Zimbabwe', 'ZW', 'ZWE');


--
-- Table structure for table `geo_zone_reference`
--

CREATE TABLE `geo_zone_reference` (
  `zone_id` int(5) NOT NULL,
  `zone_country_id` int(5) NOT NULL DEFAULT '0',
  `zone_code` varchar(5) DEFAULT NULL,
  `zone_name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `geo_zone_reference` (`zone_id`, `zone_country_id`, `zone_code`, `zone_name`) VALUES
(1, 223, 'AL', 'Alabama'),
(2, 223, 'AK', 'Alaska'),
(3, 223, 'AS', 'American Samoa'),
(4, 223, 'AZ', 'Arizona'),
(5, 223, 'AR', 'Arkansas'),
(6, 223, 'AF', 'Armed Forces Africa'),
(7, 223, 'AA', 'Armed Forces Americas'),
(8, 223, 'AC', 'Armed Forces Canada'),
(9, 223, 'AE', 'Armed Forces Europe'),
(10, 223, 'AM', 'Armed Forces Middle East'),
(11, 223, 'AP', 'Armed Forces Pacific'),
(12, 223, 'CA', 'California'),
(13, 223, 'CO', 'Colorado'),
(14, 223, 'CT', 'Connecticut'),
(15, 223, 'DE', 'Delaware'),
(16, 223, 'DC', 'District of Columbia'),
(17, 223, 'FM', 'Federated States Of Micronesia'),
(18, 223, 'FL', 'Florida'),
(19, 223, 'GA', 'Georgia'),
(20, 223, 'GU', 'Guam'),
(21, 223, 'HI', 'Hawaii'),
(22, 223, 'ID', 'Idaho'),
(23, 223, 'IL', 'Illinois'),
(24, 223, 'IN', 'Indiana'),
(25, 223, 'IA', 'Iowa'),
(26, 223, 'KS', 'Kansas'),
(27, 223, 'KY', 'Kentucky'),
(28, 223, 'LA', 'Louisiana'),
(29, 223, 'ME', 'Maine'),
(30, 223, 'MH', 'Marshall Islands'),
(31, 223, 'MD', 'Maryland'),
(32, 223, 'MA', 'Massachusetts'),
(33, 223, 'MI', 'Michigan'),
(34, 223, 'MN', 'Minnesota'),
(35, 223, 'MS', 'Mississippi'),
(36, 223, 'MO', 'Missouri'),
(37, 223, 'MT', 'Montana'),
(38, 223, 'NE', 'Nebraska'),
(39, 223, 'NV', 'Nevada'),
(40, 223, 'NH', 'New Hampshire'),
(41, 223, 'NJ', 'New Jersey'),
(42, 223, 'NM', 'New Mexico'),
(43, 223, 'NY', 'New York'),
(44, 223, 'NC', 'North Carolina'),
(45, 223, 'ND', 'North Dakota'),
(46, 223, 'MP', 'Northern Mariana Islands'),
(47, 223, 'OH', 'Ohio'),
(48, 223, 'OK', 'Oklahoma'),
(49, 223, 'OR', 'Oregon'),
(50, 223, 'PW', 'Palau'),
(51, 223, 'PA', 'Pennsylvania'),
(52, 223, 'PR', 'Puerto Rico'),
(53, 223, 'RI', 'Rhode Island'),
(54, 223, 'SC', 'South Carolina'),
(55, 223, 'SD', 'South Dakota'),
(56, 223, 'TN', 'Tenessee'),
(57, 223, 'TX', 'Texas'),
(58, 223, 'UT', 'Utah'),
(59, 223, 'VT', 'Vermont'),
(60, 223, 'VI', 'Virgin Islands'),
(61, 223, 'VA', 'Virginia'),
(62, 223, 'WA', 'Washington'),
(63, 223, 'WV', 'West Virginia'),
(64, 223, 'WI', 'Wisconsin'),
(65, 223, 'WY', 'Wyoming'),
(66, 38, 'AB', 'Alberta'),
(67, 38, 'BC', 'British Columbia'),
(68, 38, 'MB', 'Manitoba'),
(69, 38, 'NF', 'Newfoundland'),
(70, 38, 'NB', 'New Brunswick'),
(71, 38, 'NS', 'Nova Scotia'),
(72, 38, 'NT', 'Northwest Territories'),
(73, 38, 'NU', 'Nunavut'),
(74, 38, 'ON', 'Ontario'),
(75, 38, 'PE', 'Prince Edward Island'),
(76, 38, 'QC', 'Quebec'),
(77, 38, 'SK', 'Saskatchewan'),
(78, 38, 'YT', 'Yukon Territory'),
(79, 61, 'QLD', 'Queensland'),
(80, 61, 'SA', 'South Australia'),
(81, 61, 'ACT', 'Australian Capital Territory'),
(82, 61, 'VIC', 'Victoria');


ALTER TABLE `geo_zone_reference`
  ADD PRIMARY KEY (`zone_id`);


ALTER TABLE `geo_zone_reference`
  MODIFY `zone_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;


--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` bigint(20) NOT NULL COMMENT 'This table and the associated feature should be removed.' auto_increment,
  `name` longtext,
  `user` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `history_data`
--

DROP TABLE IF EXISTS `history_data`;
CREATE TABLE `history_data` (
  `id` bigint(20) NOT NULL auto_increment,
  `coffee` longtext,
  `tobacco` longtext,
  `alcohol` longtext,
  `sleep_patterns` longtext,
  `exercise_patterns` longtext,
  `seatbelt_use` longtext,
  `counseling` longtext,
  `hazardous_activities` longtext,
  `recreational_drugs` longtext,
  `last_breast_exam` varchar(255) default NULL,
  `last_mammogram` varchar(255) default NULL,
  `last_gynocological_exam` varchar(255) default NULL,
  `last_rectal_exam` varchar(255) default NULL,
  `last_prostate_exam` varchar(255) default NULL,
  `last_physical_exam` varchar(255) default NULL,
  `last_sigmoidoscopy_colonoscopy` varchar(255) default NULL,
  `last_ecg` varchar(255) default NULL,
  `last_cardiac_echo` varchar(255) default NULL,
  `last_retinal` varchar(255) default NULL,
  `last_fluvax` varchar(255) default NULL,
  `last_pneuvax` varchar(255) default NULL,
  `last_ldl` varchar(255) default NULL,
  `last_hemoglobin` varchar(255) default NULL,
  `last_psa` varchar(255) default NULL,
  `last_exam_results` varchar(255) default NULL,
  `history_mother` longtext,
  `dc_mother` text,
  `history_father` longtext,
  `dc_father`  text,
  `history_siblings` longtext,
  `dc_siblings` text,
  `history_offspring` longtext,
  `dc_offspring` text,
  `history_spouse` longtext,
  `dc_spouse` text,
  `relatives_cancer` longtext,
  `relatives_tuberculosis` longtext,
  `relatives_diabetes` longtext,
  `relatives_high_blood_pressure` longtext,
  `relatives_heart_problems` longtext,
  `relatives_stroke` longtext,
  `relatives_epilepsy` longtext,
  `relatives_mental_illness` longtext,
  `relatives_suicide` longtext,
  `cataract_surgery` datetime default NULL,
  `tonsillectomy` datetime default NULL,
  `cholecystestomy` datetime default NULL,
  `heart_surgery` datetime default NULL,
  `hysterectomy` datetime default NULL,
  `hernia_repair` datetime default NULL,
  `hip_replacement` datetime default NULL,
  `knee_replacement` datetime default NULL,
  `appendectomy` datetime default NULL,
  `date` datetime default NULL,
  `pid` bigint(20) NOT NULL default '0',
  `name_1` varchar(255) default NULL COMMENT'These generic values should be removed',
  `value_1` varchar(255) default NULL,
  `name_2` varchar(255) default NULL,
  `value_2` varchar(255) default NULL,
  `additional_history` text,
  `exams` text,
  `risk_factors` text,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `icd10_dx_order_code`
--

DROP TABLE IF EXISTS `icd10_dx_order_code`;
CREATE TABLE `icd10_dx_order_code` (
  `dx_id`               SERIAL,
  `dx_code`             varchar(7),
  `formatted_dx_code`   varchar(10),
  `valid_for_coding`    char,
  `short_desc`          varchar(60),
  `long_desc`           varchar(300),
  `active` tinyint default 0,
  `revision` int default 0,
  KEY `formatted_dx_code` (`formatted_dx_code`),
  KEY `active` (`active`)
) ENGINE=InnoDB;


--
-- Table structure for table `icd10_pcs_order_code`
--

DROP TABLE IF EXISTS `icd10_pcs_order_code`;
CREATE TABLE `icd10_pcs_order_code` (
  `pcs_id`              SERIAL,
  `pcs_code`            varchar(7),
  `valid_for_coding`    char,
  `short_desc`          varchar(60),
  `long_desc`           varchar(300),
  `active` tinyint default 0,
  `revision` int default 0,
  KEY `pcs_code` (`pcs_code`),
  KEY `active` (`active`)
) ENGINE=InnoDB;


--
-- Table structure for table `icd10_gem_pcs_9_10`
--

DROP TABLE IF EXISTS `icd10_gem_pcs_9_10`;
CREATE TABLE `icd10_gem_pcs_9_10` (
  `map_id` SERIAL,
  `pcs_icd9_source` varchar(5) default NULL,
  `pcs_icd10_target` varchar(7) default NULL,
  `flags` varchar(5) default NULL,
  `active` tinyint default 0,
  `revision` int default 0
) ENGINE=InnoDB;


--
-- Table structure for table `icd10_gem_pcs_10_9`
--

DROP TABLE IF EXISTS `icd10_gem_pcs_10_9`;
CREATE TABLE `icd10_gem_pcs_10_9` (
  `map_id` SERIAL,
  `pcs_icd10_source` varchar(7) default NULL,
  `pcs_icd9_target` varchar(5) default NULL,
  `flags` varchar(5) default NULL,
  `active` tinyint default 0,
  `revision` int default 0
) ENGINE=InnoDB;


--
-- Table structure for table `icd10_gem_dx_9_10`
--

DROP TABLE IF EXISTS `icd10_gem_dx_9_10`;
CREATE TABLE `icd10_gem_dx_9_10` (
  `map_id` SERIAL,
  `dx_icd9_source` varchar(5) default NULL,
  `dx_icd10_target` varchar(7) default NULL,
  `flags` varchar(5) default NULL,
  `active` tinyint default 0,
  `revision` int default 0
) ENGINE=InnoDB;


--
-- Table structure for table `icd10_gem_dx_10_9`
--

DROP TABLE IF EXISTS `icd10_gem_dx_10_9`;
CREATE TABLE `icd10_gem_dx_10_9` (
  `map_id` SERIAL,
  `dx_icd10_source` varchar(7) default NULL,
  `dx_icd9_target` varchar(5) default NULL,
  `flags` varchar(5) default NULL,
  `active` tinyint default 0,
  `revision` int default 0
) ENGINE=InnoDB;


--
-- Table structure for table `icd10_reimbr_dx_9_10`
--

DROP TABLE IF EXISTS `icd10_reimbr_dx_9_10`;
CREATE TABLE `icd10_reimbr_dx_9_10` (
  `map_id` SERIAL,
  `code`        varchar(8),
  `code_cnt`    tinyint,
  `ICD9_01`     varchar(5),
  `ICD9_02`     varchar(5),
  `ICD9_03`     varchar(5),
  `ICD9_04`     varchar(5),
  `ICD9_05`     varchar(5),
  `ICD9_06`     varchar(5),
  `active` tinyint default 0,
  `revision` int default 0
) ENGINE=InnoDB;


--
-- Table structure for table `icd10_reimbr_pcs_9_10`
--

DROP TABLE IF EXISTS `icd10_reimbr_pcs_9_10`;
CREATE TABLE `icd10_reimbr_pcs_9_10` (
  `map_id`      SERIAL,
  `code`        varchar(8),
  `code_cnt`    tinyint,
  `ICD9_01`     varchar(5),
  `ICD9_02`     varchar(5),
  `ICD9_03`     varchar(5),
  `ICD9_04`     varchar(5),
  `ICD9_05`     varchar(5),
  `ICD9_06`     varchar(5),
  `active` tinyint default 0,
  `revision` int default 0
) ENGINE=InnoDB;


--
-- Table structure for table `immunizations`
--

DROP TABLE IF EXISTS `immunizations`;
CREATE TABLE `immunizations` (
  `id` bigint(20) NOT NULL auto_increment,
  `patient_id` int(11) default NULL,
  `administered_date` datetime default NULL,
  `immunization_id` int(11) default NULL,
  `cvx_code` varchar(10) default NULL,
  `manufacturer` varchar(100) default NULL,
  `lot_number` varchar(50) default NULL,
  `administered_by_id` bigint(20) default NULL,
  `administered_by` VARCHAR( 255 ) default NULL COMMENT 'Alternative to administered_by_id',
  `education_date` date default NULL,
  `vis_date` date default NULL COMMENT 'Date of VIS Statement',
  `note` text,
  `create_date` datetime default NULL,
  `update_date` timestamp NOT NULL,
  `created_by` bigint(20) default NULL,
  `updated_by` bigint(20) default NULL,
  `amount_administered` float DEFAULT NULL,
  `amount_administered_unit` varchar(50) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  `administration_site` varchar(100) DEFAULT NULL,
  `added_erroneously` tinyint(1) NOT NULL DEFAULT '0',
  `external_id` VARCHAR(20) DEFAULT NULL,
  `completion_status` VARCHAR(50) DEFAULT NULL,
  `information_source` VARCHAR(31) DEFAULT NULL,
  `refusal_reason` VARCHAR(31) DEFAULT NULL,
  `ordering_provider` INT(11) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `insurance_companies`
--

DROP TABLE IF EXISTS `insurance_companies`;
CREATE TABLE `insurance_companies` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `attn` varchar(255) default NULL,
  `cms_id` varchar(15) default NULL,
  `ins_type_code` tinyint(2) default NULL,
  `x12_receiver_id` varchar(25) default NULL,
  `x12_default_partner_id` int(11) default NULL,
  `alt_cms_id` varchar(15) NOT NULL DEFAULT '',
  `ins_inactive` tinyint(1) NOT NULL DEFAULT '0',
  `allow_print_statement` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 1 = Yes Print Statements',
  `tier` varchar(5) NOT NULL DEFAULT '',
  `ins_co_initials` varchar(10) NOT NULL DEFAULT '',
  `account_type` VARCHAR(15) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `insurance_data`
--

DROP TABLE IF EXISTS `insurance_data`;
CREATE TABLE `insurance_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `case_number` int(20) DEFAULT NULL COMMENT 'case_id in pt_case table',
  `case_info_id` int(20) DEFAULT NULL COMMENT 'pci_id in pt_case_info',
  `type` enum('primary','secondary','tertiary') DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `plan_name` varchar(255) DEFAULT NULL,
  `policy_number` varchar(255) DEFAULT NULL,
  `group_number` varchar(255) DEFAULT NULL,
  `subscriber_lname` varchar(255) DEFAULT NULL,
  `subscriber_mname` varchar(255) DEFAULT NULL,
  `subscriber_fname` varchar(255) DEFAULT NULL,
  `subscriber_relationship` varchar(255) DEFAULT NULL,
  `subscriber_ss` varchar(255) DEFAULT NULL,
  `subscriber_DOB` date DEFAULT NULL,
  `subscriber_street` varchar(255) DEFAULT NULL,
  `subscriber_postal_code` varchar(255) DEFAULT NULL,
  `subscriber_city` varchar(255) DEFAULT NULL,
  `subscriber_state` varchar(255) DEFAULT NULL,
  `subscriber_country` varchar(255) DEFAULT NULL,
  `subscriber_phone` varchar(255) DEFAULT NULL,
  `subscriber_employer` varchar(255) DEFAULT NULL,
  `subscriber_employer_street` varchar(255) DEFAULT NULL,
  `subscriber_employer_postal_code` varchar(255) DEFAULT NULL,
  `subscriber_employer_state` varchar(255) DEFAULT NULL,
  `subscriber_employer_country` varchar(255) DEFAULT NULL,
  `subscriber_employer_city` varchar(255) DEFAULT NULL,
  `copay` varchar(255) DEFAULT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `eDate` date NOT NULL DEFAULT '0000-00-00',
  `pid` bigint(20) NOT NULL DEFAULT '0',
  `subscriber_sex` varchar(25) DEFAULT NULL,
  `accept_assignment` varchar(5) NOT NULL DEFAULT 'TRUE',
  `policy_type` varchar(25) NOT NULL DEFAULT '',
  `inactive` tinyint(1) DEFAULT '0',
  `inactive_time` datetime DEFAULT NULL,
  `family_deductible` varchar(15) NULL DEFAULT 0,
  `family_deductible_met` varchar(15) NULL DEFAULT 0,
  `individual_deductible` varchar(15) NULL DEFAULT 0,
  `individual_deductible_met` varchar(15) NULL DEFAULT 0,
  `pays_at` varchar(15) NULL DEFAULT 0,
  `max_out_of_pocket` varchar(15) NULL DEFAULT 0,
  `out_of_pocket_met` varchar(15) NULL DEFAULT 0,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pid_type_date_inactivetime` (`pid`,`type`,`date`,`inactive_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `insurance_numbers`
--

DROP TABLE IF EXISTS `insurance_numbers`;
CREATE TABLE `insurance_numbers` (
  `id` int(11) NOT NULL default '0',
  `provider_id` int(11) NOT NULL default '0',
  `insurance_company_id` int(11) default NULL,
  `provider_number` varchar(20) default NULL,
  `rendering_provider_number` varchar(20) default NULL,
  `group_number` varchar(20) default NULL,
  `provider_number_type` varchar(4) default NULL,
  `rendering_provider_number_type` varchar(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `issue_encounter`
--

DROP TABLE IF EXISTS `issue_encounter`;
CREATE TABLE `issue_encounter` (
  `pid` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `encounter` int(11) NOT NULL,
  `resolved` tinyint(1) NOT NULL,
  PRIMARY KEY  (`pid`,`list_id`,`encounter`)
) ENGINE=InnoDB;


--
-- Table structure for table `issue_types`
--

DROP TABLE IF EXISTS `issue_types`;
CREATE TABLE `issue_types` (
    `active` tinyint(1) NOT NULL DEFAULT '1',
    `category` varchar(75) NOT NULL DEFAULT '',
    `type` varchar(75) NOT NULL DEFAULT '',
    `plural` varchar(75) NOT NULL DEFAULT '',
    `singular` varchar(75) NOT NULL DEFAULT '',
    `abbreviation` varchar(75) NOT NULL DEFAULT '',
    `style` smallint(6) NOT NULL DEFAULT '0',
    `force_show` smallint(6) NOT NULL DEFAULT '0',
    `ordering` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`category`,`type`)
) ENGINE=InnoDB;

--
-- Dumping data for table `issue_types`
--

INSERT INTO issue_types(`ordering`,`category`,`type`,`plural`,`singular`,`abbreviation`,`style`,`force_show`) VALUES 
('10','default','medical_problem','Medical Problems','Problem','P','0','1'),
('30','default','medication','Medications','Medication','M','0','1'),
('20','default','allergy','Allergies','Allergy','A','0','1'),
('40','default','surgery','Surgeries','Surgery','S','0','0'),
('50','default','dental','Dental Issues','Dental','D','0','0');


--
-- Table structure for table `lang_constants`
--

DROP TABLE IF EXISTS `lang_constants`;
CREATE TABLE `lang_constants` (
  `cons_id` int(11) NOT NULL auto_increment,
  `constant_name` mediumtext BINARY,
  UNIQUE KEY `cons_id` (`cons_id`),
  KEY `constant_name` (`constant_name`(100))
) ENGINE=InnoDB ;

--
-- Table structure for table `lang_definitions`
--

DROP TABLE IF EXISTS `lang_definitions`;
CREATE TABLE `lang_definitions` (
  `def_id` int(11) NOT NULL auto_increment,
  `cons_id` int(11) NOT NULL default '0',
  `lang_id` int(11) NOT NULL default '0',
  `definition` mediumtext,
  UNIQUE KEY `def_id` (`def_id`),
  KEY `cons_id` (`cons_id`)
) ENGINE=InnoDB ;

--
-- Table structure for table `lang_languages`
--

DROP TABLE IF EXISTS `lang_languages`;
CREATE TABLE `lang_languages` (
  `lang_id` int(11) NOT NULL auto_increment,
  `lang_code` char(2) NOT NULL default '',
  `lang_description` varchar(100) default NULL,
  `lang_is_rtl` TINYINT DEFAULT 0 COMMENT 'Set this to 1 for RTL languages Arabic, Farsi, Hebrew, Urdu etc.',
  UNIQUE KEY `lang_id` (`lang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lang_languages`
--

INSERT INTO `lang_languages` VALUES (1, 'en', 'English', 0);


--
-- Table structure for table `lang_custom`
--

DROP TABLE IF EXISTS `lang_custom`;
CREATE TABLE `lang_custom` (
  `lang_description` varchar(100) NOT NULL default '',
  `lang_code` char(2) NOT NULL default '',
  `constant_name` mediumtext,
  `definition` mediumtext
) ENGINE=InnoDB ;


--
-- Table structure for table `layout_options`
--

DROP TABLE IF EXISTS `layout_options`;
CREATE TABLE `layout_options` (
  `form_id` varchar(31) NOT NULL default '',
  `field_id` varchar(31) NOT NULL default '',
  `group_name` varchar(31) NOT NULL default '',
  `title` varchar(63) NOT NULL default '',
  `seq` int(11) NOT NULL default '0',
  `data_type` tinyint(3) NOT NULL default '0',
  `uor` tinyint(1) NOT NULL default '1',
  `fld_length` int(11) NOT NULL default '15',
  `max_length` int(11) NOT NULL default '0',
  `list_id` varchar(31) NOT NULL default '',
  `titlecols` tinyint(3) NOT NULL default '1',
  `datacols` tinyint(3) NOT NULL default '1',
  `default_value` varchar(255) NOT NULL default '',
  `edit_options` varchar(36) NOT NULL default '',
  `description` text,
  `fld_rows` int(11) NOT NULL default '0',
  `list_backup_id` varchar(31) NOT NULL default '',
  `source` char(1) NOT NULL default 'F' COMMENT 'F=Form, D=Demographics, H=History, E=Encounter',
  `conditions` text COMMENT 'serialized array of skip conditions',
  PRIMARY KEY  (`form_id`,`field_id`,`seq`)
) ENGINE=InnoDB;

--
-- Loading table `layout_options`.  Demographics section first.
--

INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`, `fld_rows`, `list_backup_id`, `source`, `conditions`) VALUES
('DEM', 'fname', '1Face Sheet', 'NAME',5,2,2,10,63, '',1,1, '', 'CD', 'First Name',0, '', 'F', ''),
('DEM', 'mname', '1Face Sheet', '',10,2,1,2,63, '',0,0, '', 'C', 'Middle Name',0, '', 'F', ''),
('DEM', 'lname', '1Face Sheet', '',15,2,2,10,63, '',0,0, '', 'CD', 'Last Name',0, '', 'F', ''),
('DEM', 'nickname', '1Face Sheet', 'Nick Name',16,2,1,10,63, '',1,1, '', 'CD', 'Nick Name',0, '', 'F', ''),
('DEM', 'sex', '1Face Sheet', 'Sex',20,1,2,0,0, 'sex',1,1, '', 'N', 'Sex',0, '', 'F', ''),
('DEM', 'DOB', '1Face Sheet', 'DOB',25,4,2,0,10, '',1,1, '', 'D', 'Date of Birth',0, '', 'F', ''),
('DEM', 'facility', '1Face Sheet', 'Facility', 32, 35, 1, 0, 0, '', 1, 1, '', '', '', 0, '', 'F', ''),
('DEM', 'status', '1Face Sheet', 'Marital Status',30,1,1,0,0, 'marital',1,3, '', '', 'Marital Status',0, '', 'F', ''),
('DEM', 'street', '1Face Sheet', 'Address',35,2,1,25,63, '',1,1, '', 'C', 'Street and Number',0, '', 'F', ''),
('DEM', 'city', '1Face Sheet', 'City',40,2,1,15,63, '',1,1, '', 'C', 'City Name',0, '', 'F', ''),
('DEM', 'state', '1Face Sheet', 'State',45,26,1,0,0, 'state',1,1, '', '', 'State/Locality',0, '', 'F', ''),
('DEM', 'postal_code', '1Face Sheet', 'Postal Code',50,2,1,6,63, '',1,1, '', '', 'Postal Code',0, '', 'F', ''),
('DEM', 'drivers_license', '1Face Sheet', 'License/ID',60,2,1,15,63, '',1,1, '', '', 'Drivers License or State ID',0, '', 'F', ''),
('DEM', 'phone_cell', '1Face Sheet', 'Mobile Phone',65,2,1,20,63, '',1,1, '', 'P', 'Cell Phone Number',0, '', 'F', ''),
('DEM', 'email', '1Face Sheet', 'Contact Email',70,2,1,30,95, '',1,1, '', '', 'Contact Email Address',0, '', 'F', ''),
('DEM', 'pricelevel', '1Face Sheet', 'Price Level',75,1,0,0,0, 'pricelevel',1,1, '', '', 'Discount Level',0, '', 'F', ''),
('DEM', 'billing_note', '1Face Sheet', 'Billing Note',80,2,1,60,0, '',1,3, '', '', 'Patient Level Billing Note (Collections)',0, '', 'F', ''),
('DEM', 'providerID', '2Contacts', 'Provider',5,11,1,0,0, '',1,3, '', '', 'Provider',0, '', 'F', ''),
('DEM', 'ref_providerID', '2Contacts', 'Referring Provider',10,11,1,0,0, '',1,3, '', '', 'Referring Provider',0, '', 'F', ''),
('DEM', 'pharmacy_id', '2Contacts', 'Pharmacy',15,12,1,0,0, '',1,3, '', '', 'Preferred Pharmacy',0, '', 'F', ''),
('DEM', 'phone_home', '2Contacts', 'Home Phone',20,2,1,20,63, '',1,1, '', 'P', 'Home Phone Number',0, '', 'F', ''),
('DEM', 'phone_biz', '2Contacts', 'Work Phone',25,2,1,20,63, '',1,1, '', 'P', 'Work Phone Number',0, '', 'F', ''),
('DEM', 'contact_relationship', '2Contacts', 'Emergency Contact',30,2,1,10,63, '',1,1, '', 'C', 'Emergency Contact Person',0, '', 'F', ''),
('DEM', 'phone_contact', '2Contacts', 'Emergency Phone',35,2,1,20,63, '',1,1, '', 'P', 'Emergency Contact Phone Number',0, '', 'F', ''),
('DEM', 'mothersname', '2Contacts', 'Name of Mother',40,2,1,20,63, '',1,1, '', '', '',0, '', 'F', ''),
('DEM', 'guardiansname', '2Contacts', 'Name of Guardian',45,2,1,20,63, '',1,1, '', '', '',0, '', 'F', ''),
('DEM', 'county', '2Contacts', 'County',50,26,1,0,0, 'county',1,1, '', '', 'County',0, '', 'F', ''),
('DEM', 'country_code', '2Contacts', 'Country',55,26,1,0,0, 'country',1,1, '', '', 'Country',0, '', 'F', ''),
('DEM', 'referral_source', '2Contacts', 'Referral Source',60,26,1,0,0, 'refsource',1,1, '', '', 'How did they hear about us',0, '', 'F', NULL),
('DEM', 'referrer', '2Contacts', 'Referrer',65,11,1,0,0, '',1,3, '', '', 'Person who Referred This Patient',0, '', 'F', ''),
('DEM', 'allow_patient_portal', '2Privacy', 'Allow Patient Portal',1,1,1,0,0, 'yesno',1,1, '', '', '',0, '', 'F', ''),
('DEM', 'email_direct', '2Privacy', 'Trusted Email',3,2,1,30,95, '',1,1, '', '', 'Trusted Direct Email Address',0, '', 'F', ''),
('DEM', 'hipaa_notice', '2Privacy', 'Privacy Notice Received',5,1,1,0,0, 'yesno',1,1, '', '', 'Did you receive a copy of the HIPAA Notice?',0, '', 'F', ''),
('DEM', 'hipaa_voice', '2Privacy', 'Allow Voice Message',10,1,1,0,0, 'yesno',1,1, '', '', 'Allow telephone messages?',0, '', 'F', ''),
('DEM', 'hipaa_message', '2Privacy', 'Leave Message With',15,2,1,20,63, '',1,1, '', '', 'With whom may we leave a message?',0, '', 'F', ''),
('DEM', 'hipaa_mail', '2Privacy', 'Allow Mail Message',20,1,1,0,0, 'yesno',1,1, '', '', 'Allow email messages?',0, '', 'F', ''),
('DEM', 'hipaa_allowsms', '2Privacy', 'Allow SMS',25,1,1,0,0, 'yesno',1,1, '', '', 'Allow SMS (text messages)?',0, '', 'F', ''),
('DEM', 'hipaa_allowemail', '2Privacy', 'Allow Email',30,1,1,0,0, 'yesno',1,1, '', '', 'Allow Email?',0, '', 'F', ''),
('DEM', 'allow_imm_reg_use', '2Privacy', 'Allow Immunization Registry Use',35,1,1,0,0, 'yesno',1,1, '', '', '',0, '', 'F', ''),
('DEM', 'allow_imm_info_share', '2Privacy', 'Allow Immunization Info Sharing',40,1,1,0,0, 'yesno',1,1, '', '', '',0, '', 'F', ''),
('DEM', 'allow_health_info_ex', '2Privacy', 'Allow Health Information Exchange',45,1,1,0,0, 'yesno',1,1, '', '', '',0, '', 'F', ''),
('DEM', 'vfc', '2Privacy', 'VFC',55,1,1,20,0, 'eligibility',1,1, '', '', 'Eligibility status for Vaccine for Children supplied vaccine',0, '', 'F', NULL),
('DEM', 'deceased_date', '2Privacy', 'Date Deceased',60,4,1,0,20, '',1,1, '', 'D', 'If person is deceased then enter date of death.',0, '', 'F', ''),
('DEM', 'deceased_reason', '2Privacy', 'Reason Deceased',65,2,1,30,255, '',1,1, '', '', 'Reason for Death',0, '', 'F', ''),
('DEM', 'statement_y_n', '2Privacy', 'Print Statement',70,1,1,5,0, 'yesno',1,3, '', '', 'Do Not Print a Patient Statement If NO',0, '', 'F', ''),
('DEM', 'industry', '4Employer', 'Industry',5,26,1,0,0, 'Industry',1,1, '', '', 'Industry',0, '', 'F', ''),
('DEM', 'occupation', '4Employer', 'Occupation',10,26,1,0,0, 'Occupation',1,1, '', '', 'Occupation',0, '', 'F', ''),
('DEM', 'em_name', '4Employer', 'Employer Name',15,2,1,20,63, '',1,1, '', 'C', 'Employer Name',0, '', 'F', ''),
('DEM', 'em_street', '4Employer', 'Employer Address',20,2,1,25,63, '',1,1, '', 'C', 'Street and Number',0, '', 'F', ''),
('DEM', 'em_city', '4Employer', 'City',25,2,1,15,63, '',1,1, '', 'C', 'City Name',0, '', 'F', ''),
('DEM', 'em_state', '4Employer', 'State',30,26,1,0,0, 'state',1,1, '', '', 'State/Locality',0, '', 'F', ''),
('DEM', 'em_postal_code', '4Employer', 'Postal Code',35,2,1,6,63, '',1,1, '', '', 'Postal Code',0, '', 'F', ''),
('DEM', 'em_country', '4Employer', 'Country',40,26,1,0,0, 'country',1,1, '', '', 'Country',0, '', 'F', ''),
('DEM', 'language', '5Social Statistics', 'Language',5,1,1,0,0, 'language',1,3, '', '', 'Preferred Language',0, '', 'F', ''),
('DEM', 'interpretter', '5Social Statistics', 'Interpreter',7,2,1,20,63, '',1,1, '', '', 'Interpreter needed?',0, '', 'F', ''),
('DEM', 'ethnicity', '5Social Statistics', 'Ethnicity',10,33,1,0,0, 'ethnicity',1,1, '', '', 'Ethnicity',0, 'ethrace', 'F', ''),
('DEM', 'family_size', '5Social Statistics', 'Family Size',15,2,1,20,63, '',1,1, '', '', 'Family Size',0, '', 'F', ''),
('DEM', 'financial_review', '5Social Statistics', 'Financial Review Date',20,2,1,10,20, '',1,1, '', 'D', 'Financial Review Date',0, '', 'F', ''),
('DEM', 'monthly_income', '5Social Statistics', 'Monthly Income',25,2,1,20,63, '',1,1, '', '', 'Monthly Income',0, '', 'F', ''),
('DEM', 'homeless', '5Social Statistics', 'Homeless',30,2,1,20,63, '',1,1, '', '', 'Homeless or similar?',0, '', 'F', ''),
('DEM', 'migrantseasonal', '5Social Statistics', 'Migrant/Seasonal',35,2,1,20,63, '',1,1, '', '', 'Migrant or seasonal worker?',0, '', 'F', '');


INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,`max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`) VALUES
('LBTref','refer_date'      ,'1Referral','Referral Date'                  , 1, 4,2, 0,  0,''         ,1,1,'C','D','Date of referral', 0),
('LBTref','refer_from'      ,'1Referral','Refer By'                       , 2,10,2, 0,  0,''         ,1,1,'' ,'' ,'Referral By', 0),
('LBTref','refer_external'  ,'1Referral','External Referral'              , 3, 1,1, 0,  0,'boolean'  ,1,1,'' ,'' ,'External referral?', 0),
('LBTref','refer_to'        ,'1Referral','Refer To'                       , 4,14,2, 0,  0,''         ,1,1,'' ,'' ,'Referral To', 0),
('LBTref','body'            ,'1Referral','Reason'                         , 5, 3,2,30,  0,''         ,1,1,'' ,'' ,'Reason for referral', 3),
('LBTref','refer_diag'      ,'1Referral','Referrer Diagnosis'             , 6, 2,1,30,255,''         ,1,1,'' ,'X','Referrer diagnosis', 0),
('LBTref','refer_risk_level','1Referral','Risk Level'                     , 7, 1,1, 0,  0,'risklevel',1,1,'' ,'' ,'Level of urgency', 0),
('LBTref','refer_vitals'    ,'1Referral','Include Vitals'                 , 8, 1,1, 0,  0,'boolean'  ,1,1,'' ,'' ,'Include vitals data?', 0),
('LBTref','refer_related_code','1Referral','Requested Service'            , 9,15,1,30,255,''         ,1,1,'' ,'' ,'Billing Code for Requested Service', 0),
('LBTref','reply_date'      ,'2Counter-Referral','Reply Date'             ,10, 4,1, 0,  0,''         ,1,1,'' ,'D','Date of reply', 0),
('LBTref','reply_from'      ,'2Counter-Referral','Reply From'             ,11, 2,1,30,255,''         ,1,1,'' ,'' ,'Who replied?', 0),
('LBTref','reply_init_diag' ,'2Counter-Referral','Presumed Diagnosis'     ,12, 2,1,30,255,''         ,1,1,'' ,'' ,'Presumed diagnosis by specialist', 0),
('LBTref','reply_final_diag','2Counter-Referral','Final Diagnosis'        ,13, 2,1,30,255,''         ,1,1,'' ,'' ,'Final diagnosis by specialist', 0),
('LBTref','reply_documents' ,'2Counter-Referral','Documents'              ,14, 2,1,30,255,''         ,1,1,'' ,'' ,'Where may related scanned or paper documents be found?', 0),
('LBTref','reply_findings'  ,'2Counter-Referral','Findings'               ,15, 3,1,30,  0,''         ,1,1,'' ,'' ,'Findings by specialist', 3),
('LBTref','reply_services'  ,'2Counter-Referral','Services Provided'      ,16, 3,1,30,  0,''         ,1,1,'' ,'' ,'Service provided by specialist', 3),
('LBTref','reply_recommend' ,'2Counter-Referral','Recommendations'        ,17, 3,1,30,  0,''         ,1,1,'' ,'' ,'Recommendations by specialist', 3),
('LBTref','reply_rx_refer'  ,'2Counter-Referral','Prescriptions/Referrals',18, 3,1,30,  0,''         ,1,1,'' ,'' ,'Prescriptions and/or referrals by specialist', 3),
('LBTptreq','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5),
('LBTphreq','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5),
('LBTlegal','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5),
('LBTbill' ,'body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5),
('HIS','risk_factors'       ,'1General'       ,'Risk Factors',1,21,1,0,0,'riskfactors',1,1,'','' ,'Risk Factors', 0),
('HIS','exams'            ,'1General'       ,'Exams/Tests' ,2,23,1,0,0,'exams'      ,1,1,'','' ,'Exam and test results', 0),
('HIS','history_father'   ,'2Family History','Father'                 , 1, 2,1,20,  0,'',1,1,'','' ,'', 0),
('HIS','dc_father'        ,'2Family History','Diagnosis Code'         , 2,15,1, 0,255,'',1,1,'','', '', 0),
('HIS','history_mother'   ,'2Family History','Mother'                 , 3, 2,1,20,  0,'',1,1,'','' ,'', 0),
('HIS','dc_mother'        ,'2Family History','Diagnosis Code'         , 4,15,1, 0,255,'',1,1,'','', '', 0),
('HIS','history_siblings' ,'2Family History','Siblings'               , 5, 2,1,20,  0,'',1,1,'','' ,'', 0),
('HIS','dc_siblings'      ,'2Family History','Diagnosis Code'         , 6,15,1, 0,255,'',1,1,'','', '', 0),
('HIS','history_spouse'   ,'2Family History','Spouse'                 , 7, 2,1,20,  0,'',1,1,'','' ,'', 0),
('HIS','dc_spouse'        ,'2Family History','Diagnosis Code'         , 8,15,1, 0,255,'',1,1,'','', '', 0),
('HIS','history_offspring','2Family History','Offspring'              , 9, 2,1,20,  0,'',1,1,'','' ,'', 0),
('HIS','dc_offspring'     ,'2Family History','Diagnosis Code'         ,10,15,1, 0,255,'',1,1,'','', '', 0),
('HIS','relatives_cancer'             ,'3Relatives','Cancer'             ,1, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_tuberculosis'       ,'3Relatives','Tuberculosis'       ,2, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_diabetes'           ,'3Relatives','Diabetes'           ,3, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_high_blood_pressure','3Relatives','High Blood Pressure',4, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_heart_problems'     ,'3Relatives','Heart Problems'     ,5, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_stroke'             ,'3Relatives','Stroke'             ,6, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_epilepsy'           ,'3Relatives','Epilepsy'           ,7, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_mental_illness'     ,'3Relatives','Mental Illness'     ,8, 2,1,20,0,'',1,1,'','' ,'', 0),
('HIS','relatives_suicide'            ,'3Relatives','Suicide'            ,9, 2,1,20,0,'',1,3,'','' ,'', 0),
('HIS','coffee'              ,'4Lifestyle','Coffee'              ,2,28,1,20,0,'',1,3,'','' ,'Caffeine consumption', 0),
('HIS','tobacco'             ,'4Lifestyle','Tobacco'             ,1,32,1,0,0,'smoking_status',1,3,'','' ,'Tobacco use', 0),
('HIS','alcohol'             ,'4Lifestyle','Alcohol'             ,3,28,1,20,0,'',1,3,'','' ,'Alcohol consumption', 0),
('HIS','recreational_drugs'  ,'4Lifestyle','Recreational Drugs'  ,4,28,1,20,0,'',1,3,'','' ,'Recreational drug use', 0),
('HIS','counseling'          ,'4Lifestyle','Counseling'          ,5,28,1,20,0,'',1,3,'','' ,'Counseling activities', 0),
('HIS','exercise_patterns'   ,'4Lifestyle','Exercise Patterns'   ,6,28,1,20,0,'',1,3,'','' ,'Exercise patterns', 0),
('HIS','hazardous_activities','4Lifestyle','Hazardous Activities',7,28,1,20,0,'',1,3,'','' ,'Hazardous activities', 0),
('HIS','sleep_patterns'      ,'4Lifestyle','Sleep Patterns'      ,8, 2,1,20,0,'',1,3,'','' ,'Sleep patterns', 0),
('HIS','seatbelt_use'        ,'4Lifestyle','Seatbelt Use'        ,9, 2,1,20,0,'',1,3,'','' ,'Seatbelt use', 0),
('HIS','name_1'            ,'5Other','Name/Value'        ,1, 2,1,10,255,'',1,1,'','' ,'Name 1', 0),
('HIS','value_1'           ,'5Other',''                  ,2, 2,1,10,255,'',0,0,'','' ,'Value 1', 0),
('HIS','name_2'            ,'5Other','Name/Value'        ,3, 2,1,10,255,'',1,1,'','' ,'Name 2', 0),
('HIS','value_2'           ,'5Other',''                  ,4, 2,1,10,255,'',0,0,'','' ,'Value 2', 0),
('HIS','additional_history','5Other','Additional History',5, 3,1,30,  0,'',1,3,'' ,'' ,'Additional history notes', 3),
('HIS','userarea11'        ,'5Other','User Defined Area 11',6,3,0,30,0,'',1,3,'','','User Defined', 3),
('HIS','userarea12'        ,'5Other','User Defined Area 12',7,3,0,30,0,'',1,3,'','','User Defined', 3),
('FACUSR', 'provider_id', '1General', 'Provider ID', 1, 2, 1, 15, 63, '', 1, 1, '', '', 'Provider ID at Specified Facility', 0);


--
-- Table structure for table `list_options`
--

DROP TABLE IF EXISTS `list_options`;
CREATE TABLE `list_options` (
  `list_id` varchar(50) NOT NULL default '',
  `option_id` varchar(50) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `seq` int(11) NOT NULL default '0',
  `is_default` tinyint(1) NOT NULL default '0',
  `option_value` float NOT NULL default '0',
  `mapping` varchar(31) NOT NULL DEFAULT '',
  `notes` TEXT,
  `codes` varchar(255) NOT NULL DEFAULT '',
  `toggle_setting_1` tinyint(1) NOT NULL default '0',
  `toggle_setting_2` tinyint(1) NOT NULL default '0',
  `activity` TINYINT DEFAULT 1 NOT NULL,
  `subtype` varchar(31) NOT NULL DEFAULT '',
  `list_options_icon` VARCHAR(100) NOT NULL ,
  PRIMARY KEY  (`list_id`,`option_id`)
) ENGINE=InnoDB;

--
-- Dumping data for table `list_options`
--

INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`, `subtype`, `list_options_icon`) VALUES
('abook_type', 'ccda', 'Care Coordination', 35, 0, 2, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'dist', 'Distributor', 30, 0, 3, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'emr_direct', 'EMR Direct', 105, 0, 4, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'external_org', 'External Organization', 120, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'external_provider', 'External Provider', 110, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'ord_img', 'Imaging Service', 5, 0, 3, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'ord_imm', 'Immunization Service', 10, 0, 3, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'ord_lab', 'Lab Service', 15, 0, 3, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'oth', 'Other', 95, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'spe', 'Specialist', 20, 0, 2, '', NULL, '', 0, 0, 1, '', ''),
('abook_type', 'vendor', 'Vendor', 25, 0, 3, '', NULL, '', 0, 0, 1, '', ''),
('ACLT_Tag_Colors', 'blue', 'Blue', 0, 1, 0, '', '#90caf9', '', 0, 0, 0, '', ''),
('ACLT_Tag_Colors', 'green', 'Green', 0, 0, 0, '', '#a5d6a7', '', 0, 0, 0, '', ''),
('ACLT_Tag_Colors', 'orange', 'Orange', 0, 0, 0, '', '#ffb74d', '', 0, 0, 0, '', ''),
('ACLT_Tag_Colors', 'purple', 'Purple', 0, 0, 0, '', '#b39ddb', '', 0, 0, 0, '', ''),
('ACLT_Tag_Colors', 'red', 'Red', 0, 0, 0, '', '#e57373', '', 0, 0, 0, '', ''),
('ACLT_Tag_Colors', 'yellow', 'Yellow', 0, 0, 0, '', '#fff59d', '', 0, 0, 0, '', ''),
('ACLT_Tag_Status', 'active', 'Active', 0, 1, 0, '', '', '', 0, 0, 0, '', ''),
('ACLT_Tag_Status', 'deleted', 'Deleted', 0, 0, 0, '', '', '', 0, 0, 0, '', ''),
('ACLT_Tag_Status', 'suspended', 'Suspended', 0, 0, 0, '', '', '', 0, 0, 0, '', ''),
('adjreason', 'Adm adjust', 'Adm adjust', 5, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'After hrs calls', 'After hrs calls', 10, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Bad check', 'Bad check', 15, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Bad debt', 'Bad debt', 20, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Coll w/o', 'Coll w/o', 25, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Discount', 'Discount', 30, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Hardship w/o', 'Hardship w/o', 35, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Ins adjust', 'Ins adjust', 40, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Ins bundling', 'Ins bundling', 45, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Ins overpaid', 'Ins overpaid', 50, 0, 5, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Ins refund', 'Ins refund', 55, 0, 5, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Pt overpaid', 'Pt overpaid', 60, 0, 5, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Pt refund', 'Pt refund', 65, 0, 5, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Pt released', 'Pt released', 70, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Sm debt w/o', 'Sm debt w/o', 75, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'To copay', 'To copay', 80, 0, 2, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'To ded\'ble', 'To ded\'ble', 85, 0, 3, '', NULL, '', 0, 0, 1, '', ''),
('adjreason', 'Untimely filing', 'Untimely filing', 90, 0, 1, '', NULL, '', 0, 0, 1, '', ''),
('allergy_issue_list', 'codeine', 'codeine', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('allergy_issue_list', 'iodine', 'iodine', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('allergy_issue_list', 'penicillin', 'penicillin', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('allergy_issue_list', 'sulfa', 'sulfa', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('amendment_from', 'insurance', 'Insurance', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('amendment_from', 'patient', 'Patient', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('amendment_status', 'approved', 'Approved', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('amendment_status', 'rejected', 'Rejected', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('apptstat', '!', '! Left w/o visit', 40, 0, 0, '', '0BBA34|0', '', 0, 1, 1, '', ''),
('apptstat', '#', '# Ins/fin issue', 45, 0, 0, '', 'FFFF2B|0', '', 0, 0, 1, '', ''),
('apptstat', '$', '$ Coding done', 60, 0, 0, '', 'C0FF96|0', '', 0, 0, 1, '', ''),
('apptstat', '%', '% Canceled < 24h', 65, 0, 0, '', 'BFBFBF|0', '', 0, 0, 1, '', ''),
('apptstat', '&', '& Rescheduled < 24h', 80, 0, 0, '', 'BFBFBF|0', '', 0, 0, 1, '', ''),
('apptstat', '*', '* Reminder done', 10, 0, 0, '', 'FFC9F8|0', '', 0, 0, 1, '', ''),
('apptstat', '+', '+ Chart pulled', 15, 0, 0, '', '87FF1F|0', '', 0, 0, 1, '', ''),
('apptstat', '-', '- None', 5, 0, 0, '', 'FEFDCF|0', '', 0, 0, 1, '', ''),
('apptstat', '<', '< In exam room', 50, 0, 0, '', '52D9DE|10', '', 0, 0, 1, '', ''),
('apptstat', '=', '= Rescheduled', 75, 0, 0, '', 'BFBFBF|0', '', 0, 0, 1, '', ''),
('apptstat', '>', '> Checked out', 55, 0, 0, '', 'FEFDCF|0', '', 0, 1, 1, '', ''),
('apptstat', '?', '? No show', 25, 0, 0, '', 'BFBFBF|0', '', 0, 0, 1, '', ''),
('apptstat', '@', '@ Arrived', 30, 0, 0, '', 'FF2414|10', '', 1, 0, 1, '', ''),
('apptstat', 'Deleted', 'Deleted', 85, 0, 0, '', '0F0F0F|0', '', 0, 0, 1, '', ''),
('apptstat', 'x', 'x Canceled', 20, 0, 0, '', 'BFBFBF|0', '', 0, 0, 1, '', ''),
('apptstat', '^', '^ Pending from Portal', 70, 0, 0, '', 'ADBBFF|0', '', 0, 0, 1, '', ''),
('apptstat', '~', '~ Arrived late', 35, 0, 0, '', 'FF6619|10', '', 1, 0, 1, '', ''),
('boolean', '0', 'No', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('boolean', '1', 'Yes', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('cancellation_reasons', '1', 'No reason given', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('cancellation_reasons', '2', 'Work', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('cancellation_reasons', '3', 'Sick', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('cancellation_reasons', '4', 'Weather', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('chartloc', 'fileroom', 'File Room', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'back_pain_plan_cqm', 'Back Pain', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'cabg_plan_cqm', 'Coronary Artery Bypass Graft (CABG)', 35, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'ckd_plan_cqm', 'Chronic Kidney Disease (CKD)', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'dm_plan', 'Diabetes Mellitus', 500, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'dm_plan_cqm', 'Diabetes Mellitus', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'periop_plan_cqm', 'Perioperative Care', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'prevent_plan', 'Preventative Care', 510, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'prevent_plan_cqm', 'Preventative Care', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('clinical_plans', 'rheum_arth_plan_cqm', 'Rheumatoid Arthritis', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('country', 'USA', 'USA', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'all', 'All', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'custom', 'Custom', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'last_calendar_year', 'Last Calendar Year', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'last_month', 'Last Month', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'this_calendar_year', 'This Calendar Year', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'this_month_to_date', 'This Month to Date', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'this_week_to_date', 'This Week to Date', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('date_master_criteria', 'today', 'Today', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('disclosure_type', 'disclosure-healthcareoperations', 'Health Care Operations', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('disclosure_type', 'disclosure-payment', 'Payment', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('disclosure_type', 'disclosure-treatment', 'Treatment', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_form', '0', '', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_form', '1', 'suspension', 1, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C60928', 0, 0, 1, '', ''),
('drug_form', '10', 'cream', 10, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C28944', 0, 0, 1, '', ''),
('drug_form', '11', 'ointment', 11, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C42966', 0, 0, 1, '', ''),
('drug_form', '12', 'puff', 12, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C42944', 0, 0, 1, '', ''),
('drug_form', '2', 'tablet', 2, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C42998', 0, 0, 1, '', ''),
('drug_form', '3', 'capsule', 3, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C25158', 0, 0, 1, '', ''),
('drug_form', '4', 'solution', 4, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C42986', 0, 0, 1, '', ''),
('drug_form', '5', 'tsp', 5, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C48544', 0, 0, 1, '', ''),
('drug_form', '6', 'ml', 6, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C28254', 0, 0, 1, '', ''),
('drug_form', '7', 'units', 7, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C44278', 0, 0, 1, '', ''),
('drug_form', '8', 'inhalations', 8, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C42944', 0, 0, 1, '', ''),
('drug_form', '9', 'gtts(drops)', 9, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C48491', 0, 0, 1, '', ''),
('drug_interval', '0', '', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '1', 'b.i.d.', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '10', 'a.c.', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '11', 'p.c.', 11, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '12', 'a.m.', 12, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '13', 'p.m.', 13, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '14', 'ante', 14, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '15', 'h', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '16', 'h.s.', 16, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '17', 'p.r.n.', 17, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '18', 'stat', 18, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '2', 't.i.d.', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '3', 'q.i.d.', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '4', 'q.3h', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '5', 'q.4h', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '6', 'q.5h', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '7', 'q.6h', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '8', 'q.8h', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_interval', '9', 'q.d.', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_route', '0', '', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_route', '1', 'Per Oris', 1, 0, 0, '', 'PO', 'NCI-CONCEPT-ID:C38288', 0, 0, 1, '', ''),
('drug_route', '10', 'IM', 10, 0, 0, '', 'IM', '', 0, 0, 1, '', ''),
('drug_route', '11', 'IV', 11, 0, 0, '', 'IV', '', 0, 0, 1, '', ''),
('drug_route', '12', 'Per Nostril', 12, 0, 0, '', 'NS', '', 0, 0, 1, '', ''),
('drug_route', '13', 'Both Ears', 13, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '14', 'Left Ear', 14, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '15', 'Right Ear', 15, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '2', 'Per Rectum', 2, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '3', 'To Skin', 3, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '4', 'To Affected Area', 4, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '5', 'Sublingual', 5, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '6', 'OS', 6, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '7', 'OD', 7, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '8', 'OU', 8, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', '9', 'SQ', 9, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', 'inhale', 'Inhale', 16, 0, 0, '', 'RESPIR', 'NCI-CONCEPT-ID:C38216', 0, 0, 1, '', ''),
('drug_route', 'intradermal', 'Intradermal', 16, 0, 0, '', 'ID', '', 0, 0, 1, '', ''),
('drug_route', 'intramuscular', 'Intramuscular', 20, 0, 0, '', 'IM', 'NCI-CONCEPT-ID:C28161', 0, 0, 1, '', ''),
('drug_route', 'other', 'Other/Miscellaneous', 18, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('drug_route', 'transdermal', 'Transdermal', 19, 0, 0, '', 'TD', '', 0, 0, 1, '', ''),
('drug_units', '0', '', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '1', 'mg', 1, 0, 0, '', NULL, 'NCI-CONCEPT-ID:C28253', 0, 0, 1, '', ''),
('drug_units', '2', 'mg/1cc', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '3', 'mg/2cc', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '4', 'mg/3cc', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '5', 'mg/4cc', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '6', 'mg/5cc', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '7', 'mcg', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '8', 'grams', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('drug_units', '9', 'mL', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('eligibility', 'eligible', 'Eligible', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('eligibility', 'ineligible', 'Ineligible', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethnicity', 'declne_to_specfy', 'Declined To Specify', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'aleut', 'ALEUT', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'amer_indian', 'American Indian', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'Asian', 'Asian', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'Black', 'Black', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'cambodian', 'Cambodian', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'Caucasian', 'Caucasian', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'chinese', 'Chinese', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'cs_american', 'Central/South American', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'cuban', 'Cuban', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'declne_to_specfy', 'Declined To Specify', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'eskimo', 'Eskimo', 100, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'filipino', 'Filipino', 110, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'guamanian', 'Guamanian', 120, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'hawaiian', 'Hawaiian', 130, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'Hispanic', 'Hispanic', 140, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'hmong', 'Hmong', 170, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'indian', 'Indian', 180, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'japanese', 'Japanese', 190, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'korean', 'Korean', 200, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'laotian', 'Laotian', 210, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'mexican', 'Mexican/MexAmer/Chicano', 220, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'mlt-race', 'Multiracial', 230, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'othr', 'Other', 240, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'othr_non_us', 'Hispanic - Other (Born outside US)', 160, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'othr_spec', 'Other - Specified', 250, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'othr_us', 'Hispanic - Other (Born in US)', 150, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'pac_island', 'Pacific Islander', 260, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'puerto_rican', 'Puerto Rican', 270, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'refused', 'Refused To State', 280, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'samoan', 'Samoan', 290, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'spec', 'Specified', 300, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'thai', 'Thai', 310, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'unknown', 'Unknown', 320, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'unspec', 'Unspecified', 330, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'vietnamese', 'Vietnamese', 340, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'white', 'White', 350, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ethrace', 'withheld', 'Withheld', 360, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'brs', 'Breast Exam', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'cec', 'Cardiac Echo', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'ecg', 'ECG', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'flu', 'Flu Vaccination', 11, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'gyn', 'Gynecological Exam', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'hem', 'Hemoglobin', 14, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'ldl', 'LDL', 13, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'mam', 'Mammogram', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'phy', 'Physical Exam', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'pne', 'Pneumonia Vaccination', 12, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'pro', 'Prostate Exam', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'psa', 'PSA', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'rec', 'Rectal Exam', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'ret', 'Retinal Exam', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('exams', 'sic', 'Sigmoid/Colonoscopy', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Chiropractic', 'Chiropractic', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Fitness Testing', 'Fitness Testing', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Nutritional', 'Nutritional', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Osteopathy', 'Osteopathy', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Podiatry', 'Podiatry', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Pre Participation Assessment', 'Pre Participation Assessment', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Prevention Rehab', 'Prevention Rehab', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Screening / Testing', 'Screening / Testing', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('general_issue_list', 'Strength and Conditioning', 'Strength and Conditioning', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '1', 'DTaP 1', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '10', 'DT 5', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '11', 'IPV 1', 110, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '12', 'IPV 2', 115, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '13', 'IPV 3', 120, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '14', 'IPV 4', 125, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '15', 'Hib 1', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '16', 'Hib 2', 85, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '17', 'Hib 3', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '18', 'Hib 4', 95, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '19', 'Pneumococcal Conjugate 1', 140, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '2', 'DTaP 2', 35, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '20', 'Pneumococcal Conjugate 2', 145, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '21', 'Pneumococcal Conjugate 3', 150, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '22', 'Pneumococcal Conjugate 4', 155, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '23', 'MMR 1', 130, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '24', 'MMR 2', 135, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '25', 'Varicella 1', 165, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '26', 'Varicella 2', 170, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '27', 'Hepatitis B 1', 65, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '28', 'Hepatitis B 2', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '29', 'Hepatitis B 3', 75, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '3', 'DTaP 3', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '30', 'Influenza 1', 100, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '31', 'Influenza 2', 105, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '32', 'Td', 160, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '33', 'Hepatitis A 1', 55, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '34', 'Hepatitis A 2', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '35', 'Other', 175, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '4', 'DTaP 4', 45, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '5', 'DTaP 5', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '6', 'DT 1', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '7', 'DT 2', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '8', 'DT 3', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('immunizations', '9', 'DT 4', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('Immunization_Completion_Status', 'Completed', 'completed', 10, 0, 0, '', 'CP', '', 0, 0, 1, '', ''),
('Immunization_Completion_Status', 'Not_Administered', 'Not Administered', 30, 0, 0, '', 'NA', '', 0, 0, 1, '', ''),
('Immunization_Completion_Status', 'Partially_Administered', 'Partially Administered', 40, 0, 0, '', 'PA', '', 0, 0, 1, '', ''),
('Immunization_Completion_Status', 'Refused', 'Refused', 20, 0, 0, '', 'RE', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'AB', 'Abbott Laboratories', 10, 0, 0, '', 'AB', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'ACA', 'Acambis, Inc', 20, 0, 0, '', 'ACA', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'AD', 'Adams Laboratories, Inc.', 30, 0, 0, '', 'AD', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'AKR', 'Akorn, Inc', 40, 0, 0, '', 'AKR', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'ALP', 'Alpha Therapeutic Corporation', 50, 0, 0, '', 'ALP', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'AR', 'Armour', 60, 0, 0, '', 'AR', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'AVB', 'Aventis Behring L.L.C.', 70, 0, 0, '', 'AVB', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'AVI', 'Aviron', 80, 0, 0, '', 'AVI', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'BA', 'Baxter Healthcare Corporation-inactive', 110, 0, 0, '', 'BA', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'BAH', 'Baxter Healthcare Corporation', 100, 0, 0, '', 'BAH', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'BAY', 'Bayer Corporation', 120, 0, 0, '', 'BAY', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'BP', 'Berna Products', 130, 0, 0, '', 'BP', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'BPC', 'Berna Products Corporation', 140, 0, 0, '', 'BPC', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'BRR', 'Barr Laboratories', 90, 0, 0, '', 'BRR', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'BTP', 'Biotest Pharmaceuticals Corporation', 150, 0, 0, '', 'BTP', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'CEN', 'Centeon L.L.C.', 180, 0, 0, '', 'CEN', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'CHI', 'Chiron Corporation', 190, 0, 0, '', 'CHI', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'CMP', 'Celltech Medeva Pharmaceuticals', 170, 0, 0, '', 'CMP', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'CNJ', 'Cangene Corporation', 160, 0, 0, '', 'CNJ', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'CON', 'Connaught', 200, 0, 0, '', 'CON', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'CRU', 'Crucell', 210, 0, 0, '', 'CRU', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'CSL', 'CSL Behring, Inc', 220, 0, 0, '', 'CSL', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'DVC', 'DynPort Vaccine Company, LLC', 230, 0, 0, '', 'DVC', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'EVN', 'Evans Medical Limited', 250, 0, 0, '', 'EVN', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'GEO', 'GeoVax Labs, Inc.', 260, 0, 0, '', 'GEO', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'GRE', 'Greer Laboratories, Inc.', 280, 0, 0, '', 'GRE', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'GRF', 'Grifols', 290, 0, 0, '', 'GRF', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'IAG', 'Immuno International AG', 310, 0, 0, '', 'IAG', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'IDB', 'ID Biomedical', 300, 0, 0, '', 'IDB', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'IM', 'Merieux', 410, 0, 0, '', 'IM', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'INT', 'Intercell Biomedical', 330, 0, 0, '', 'INT', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'IUS', 'Immuno-U.S., Inc.', 320, 0, 0, '', 'IUS', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'JNJ', 'Johnson and Johnson', 340, 0, 0, '', 'JNJ', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'JPN', 'The Research Foundation for Microbial Diseases of Osaka University (BIKEN)', 610, 0, 0, '', 'JPN', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'KGC', 'Korea Green Cross Corporation', 350, 0, 0, '', 'KGC', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'LED', 'Lederle', 360, 0, 0, '', 'LED', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'MA', 'Massachusetts Public Health Biologic Laboratories', 380, 0, 0, '', 'MA', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'MBL', 'Massachusetts Biologic Laboratories', 370, 0, 0, '', 'MBL', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'MED', 'MedImmune, Inc.', 390, 0, 0, '', 'MED', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'MIL', 'Miles', 420, 0, 0, '', 'MIL', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'MIP', 'Emergent BioDefense Operations Lansing', 240, 0, 0, '', 'MIP', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'MSD', 'Merck and Co., Inc.', 400, 0, 0, '', 'MSD', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'NAB', 'NABI', 430, 0, 0, '', 'NAB', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'NAV', 'North American Vaccine, Inc.', 450, 0, 0, '', 'NAV', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'NOV', 'Novartis Pharmaceutical Corporation', 460, 0, 0, '', 'NOV', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'NVX', 'Novavax, Inc.', 470, 0, 0, '', 'NVX', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'NYB', 'New York Blood Center', 440, 0, 0, '', 'NYB', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'ORT', 'Ortho-clinical Diagnostics', 490, 0, 0, '', 'ORT', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'OTC', 'Organon Teknika Corporation', 480, 0, 0, '', 'OTC', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'OTH', 'Other manufacturer', 500, 0, 0, '', 'OTH', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'PD', 'Parkedale Pharmaceuticals', 510, 0, 0, '', 'PD', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'PFR', 'Pfizer, Inc', 520, 0, 0, '', 'PFR', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'PMC', 'sanofi pasteur', 560, 0, 0, '', 'PMC', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'PRX', 'Praxis Biologics', 540, 0, 0, '', 'PRX', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'PSC', 'Protein Sciences', 550, 0, 0, '', 'PSC', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'PWJ', 'PowderJect Pharmaceuticals', 530, 0, 0, '', 'PWJ', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'SCL', 'Sclavo, Inc.', 570, 0, 0, '', 'SCL', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'SI', 'Swiss Serum and Vaccine Inst.', 590, 0, 0, '', 'SI', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'SKB', 'GlaxoSmithKline', 270, 0, 0, '', 'SKB', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'SOL', 'Solvay Pharmaceuticals', 580, 0, 0, '', 'SOL', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'TAL', 'Talecris Biotherapeutics', 600, 0, 0, '', 'TAL', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'UNK', 'Unknown manufacturer', 630, 0, 0, '', 'UNK', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'USA', 'United States Army Medical Research and Material Command', 620, 0, 0, '', 'USA', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'VXG', 'VaxGen', 640, 0, 0, '', 'VXG', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'WA', 'Wyeth-Ayerst', 660, 0, 0, '', 'WA', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'WAL', 'Wyeth', 650, 0, 0, '', 'WAL', '', 0, 0, 1, '', ''),
('Immunization_Manufacturer', 'ZLB', 'ZLB Behring', 670, 0, 0, '', 'ZLB', '', 0, 0, 1, '', ''),
('Industry', 'construction_firm', 'Construction Firm', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('Industry', 'engineering_firm', 'Engineering Firm', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('Industry', 'law_firm', 'Law Firm', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('insurance_account_type', 'BC', 'BCBS', 15, 0, 0, '', '', '', 0, 0, 1, '', ''),
('insurance_account_type', 'CL', 'COLLECTIONS', 10, 0, 0, '', '', '', 0, 0, 1, '', ''),
('insurance_account_type', 'CP', 'WORKERS COMP', 30, 0, 0, '', '', '', 0, 0, 1, '', ''),
('insurance_account_type', 'SP', 'SELF PAY', 20, 0, 0, '', '', '', 0, 0, 1, '', ''),
('insurance_payment_method', 'check_payment', 'Check Payment', 10, 0, 0, '', '', '', 0, 0, 1, '', ''),
('insurance_payment_method', 'credit_card', 'Credit Card', 20, 0, 0, '', '', '', 0, 0, 1, '', ''),
('insurance_types', 'primary', 'Primary', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('insurance_types', 'secondary', 'Secondary', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('insurance_types', 'tertiary', 'Tertiary', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('irnpool', 'main', 'Main', 1, 1, 0, '', '000001', '', 0, 0, 1, '', ''),
('issue_subtypes', 'eye', 'Eye', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('language', 'abkhazian', 'Abkhazian', 10, 0, 0, '', 'abk', '', 0, 0, 1, '', ''),
('language', 'afar', 'Afar', 20, 0, 0, '', 'aar', '', 0, 0, 1, '', ''),
('language', 'afrikaans', 'Afrikaans', 30, 0, 0, '', 'afr', '', 0, 0, 1, '', ''),
('language', 'akan', 'Akan', 40, 0, 0, '', 'aka', '', 0, 0, 1, '', ''),
('language', 'albanian', 'Albanian', 50, 0, 0, '', 'alb(B)|sqi(T)', '', 0, 0, 1, '', ''),
('language', 'amharic', 'Amharic', 60, 0, 0, '', 'amh', '', 0, 0, 1, '', ''),
('language', 'arabic', 'Arabic', 70, 0, 0, '', 'ara', '', 0, 0, 1, '', ''),
('language', 'aragonese', 'Aragonese', 80, 0, 0, '', 'arg', '', 0, 0, 1, '', ''),
('language', 'armenian', 'Armenian', 90, 0, 0, '', 'arm(B)|hye(T)', '', 0, 0, 1, '', ''),
('language', 'assamese', 'Assamese', 100, 0, 0, '', 'asm', '', 0, 0, 1, '', ''),
('language', 'avaric', 'Avaric', 110, 0, 0, '', 'ava', '', 0, 0, 1, '', ''),
('language', 'avestan', 'Avestan', 120, 0, 0, '', 'ave', '', 0, 0, 1, '', ''),
('language', 'aymara', 'Aymara', 130, 0, 0, '', 'aym', '', 0, 0, 1, '', ''),
('language', 'azerbaijani', 'Azerbaijani', 140, 0, 0, '', 'aze', '', 0, 0, 1, '', ''),
('language', 'bambara', 'Bambara', 150, 0, 0, '', 'bam', '', 0, 0, 1, '', ''),
('language', 'bashkir', 'Bashkir', 160, 0, 0, '', 'bak', '', 0, 0, 1, '', ''),
('language', 'basque', 'Basque', 170, 0, 0, '', 'baq(B)|eus(T)', '', 0, 0, 1, '', ''),
('language', 'belarusian', 'Belarusian', 180, 0, 0, '', 'bel', '', 0, 0, 1, '', ''),
('language', 'bengali', 'Bengali', 190, 0, 0, '', 'ben', '', 0, 0, 1, '', ''),
('language', 'bihari_languages', 'Bihari languages', 200, 0, 0, '', 'bih', '', 0, 0, 1, '', ''),
('language', 'bislama', 'Bislama', 210, 0, 0, '', 'bis', '', 0, 0, 1, '', ''),
('language', 'bokmal_norwegian_norwegian_bok', 'Bokmål, Norwegian; Norwegian Bokmål', 220, 0, 0, '', 'nob', '', 0, 0, 1, '', ''),
('language', 'bosnian', 'Bosnian', 230, 0, 0, '', 'bos', '', 0, 0, 1, '', ''),
('language', 'breton', 'Breton', 240, 0, 0, '', 'bre', '', 0, 0, 1, '', ''),
('language', 'bulgarian', 'Bulgarian', 250, 0, 0, '', 'bul', '', 0, 0, 1, '', ''),
('language', 'burmese', 'Burmese', 260, 0, 0, '', 'bur(B)|mya(T)', '', 0, 0, 1, '', ''),
('language', 'catalan_valencian', 'Catalan; Valencian', 270, 0, 0, '', 'cat', '', 0, 0, 1, '', ''),
('language', 'central_khmer', 'Central Khmer', 280, 0, 0, '', 'khm', '', 0, 0, 1, '', ''),
('language', 'chamorro', 'Chamorro', 290, 0, 0, '', 'cha', '', 0, 0, 1, '', ''),
('language', 'chechen', 'Chechen', 300, 0, 0, '', 'che', '', 0, 0, 1, '', ''),
('language', 'chichewa_chewa_nyanja', 'Chichewa; Chewa; Nyanja', 310, 0, 0, '', 'nya', '', 0, 0, 1, '', ''),
('language', 'chinese', 'Chinese', 320, 0, 0, '', 'chi(B)|zho(T)', '', 0, 0, 1, '', ''),
('language', 'church_slavic_old_slavonic_chu', 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 330, 0, 0, '', 'chu', '', 0, 0, 1, '', ''),
('language', 'chuvash', 'Chuvash', 340, 0, 0, '', 'chv', '', 0, 0, 1, '', ''),
('language', 'cornish', 'Cornish', 350, 0, 0, '', 'cor', '', 0, 0, 1, '', ''),
('language', 'corsican', 'Corsican', 360, 0, 0, '', 'cos', '', 0, 0, 1, '', ''),
('language', 'cree', 'Cree', 370, 0, 0, '', 'cre', '', 0, 0, 1, '', ''),
('language', 'croatian', 'Croatian', 380, 0, 0, '', 'hrv', '', 0, 0, 1, '', ''),
('language', 'czech', 'Czech', 390, 0, 0, '', 'cze(B)|ces(T)', '', 0, 0, 1, '', ''),
('language', 'danish', 'Danish', 400, 0, 0, '', 'dan', '', 0, 0, 1, '', ''),
('language', 'declne_to_specfy', 'Declined To Specify', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('language', 'divehi_dhivehi_maldivian', 'Divehi; Dhivehi; Maldivian', 410, 0, 0, '', 'div', '', 0, 0, 1, '', ''),
('language', 'dutch_flemish', 'Dutch; Flemish', 420, 0, 0, '', 'dut(B)|nld(T)', '', 0, 0, 1, '', ''),
('language', 'dzongkha', 'Dzongkha', 430, 0, 0, '', 'dzo', '', 0, 0, 1, '', ''),
('language', 'English', 'English', 440, 0, 0, '', 'eng', '', 0, 0, 1, '', ''),
('language', 'esperanto', 'Esperanto', 450, 0, 0, '', 'epo', '', 0, 0, 1, '', ''),
('language', 'estonian', 'Estonian', 460, 0, 0, '', 'est', '', 0, 0, 1, '', ''),
('language', 'ewe', 'Ewe', 470, 0, 0, '', 'ewe', '', 0, 0, 1, '', ''),
('language', 'faroese', 'Faroese', 480, 0, 0, '', 'fao', '', 0, 0, 1, '', ''),
('language', 'fijian', 'Fijian', 490, 0, 0, '', 'fij', '', 0, 0, 1, '', ''),
('language', 'finnish', 'Finnish', 500, 0, 0, '', 'fin', '', 0, 0, 1, '', ''),
('language', 'french', 'French', 510, 0, 0, '', 'fre(B)|fra(T)', '', 0, 0, 1, '', ''),
('language', 'fulah', 'Fulah', 520, 0, 0, '', 'ful', '', 0, 0, 1, '', ''),
('language', 'gaelic_scottish_gaelic', 'Gaelic; Scottish Gaelic', 530, 0, 0, '', 'gla', '', 0, 0, 1, '', ''),
('language', 'galician', 'Galician', 540, 0, 0, '', 'glg', '', 0, 0, 1, '', ''),
('language', 'ganda', 'Ganda', 550, 0, 0, '', 'lug', '', 0, 0, 1, '', ''),
('language', 'georgian', 'Georgian', 560, 0, 0, '', 'geo(B)|kat(T)', '', 0, 0, 1, '', ''),
('language', 'german', 'German', 570, 0, 0, '', 'ger(B)|deu(T)', '', 0, 0, 1, '', ''),
('language', 'greek', 'Greek, Modern (1453-)', 580, 0, 0, '', 'gre(B)|ell(T)', '', 0, 0, 1, '', ''),
('language', 'guarani', 'Guarani', 590, 0, 0, '', 'grn', '', 0, 0, 1, '', ''),
('language', 'gujarati', 'Gujarati', 600, 0, 0, '', 'guj', '', 0, 0, 1, '', ''),
('language', 'haitian_haitian_creole', 'Haitian; Haitian Creole', 610, 0, 0, '', 'hat', '', 0, 0, 1, '', ''),
('language', 'hausa', 'Hausa', 620, 0, 0, '', 'hau', '', 0, 0, 1, '', ''),
('language', 'hebrew', 'Hebrew', 630, 0, 0, '', 'heb', '', 0, 0, 1, '', ''),
('language', 'herero', 'Herero', 640, 0, 0, '', 'her', '', 0, 0, 1, '', ''),
('language', 'hindi', 'Hindi', 650, 0, 0, '', 'hin', '', 0, 0, 1, '', ''),
('language', 'hiri_motu', 'Hiri Motu', 660, 0, 0, '', 'hmo', '', 0, 0, 1, '', ''),
('language', 'hungarian', 'Hungarian', 670, 0, 0, '', 'hun', '', 0, 0, 1, '', ''),
('language', 'icelandic', 'Icelandic', 680, 0, 0, '', 'ice(B)|isl(T)', '', 0, 0, 1, '', ''),
('language', 'ido', 'Ido', 690, 0, 0, '', 'ido', '', 0, 0, 1, '', ''),
('language', 'igbo', 'Igbo', 700, 0, 0, '', 'ibo', '', 0, 0, 1, '', ''),
('language', 'indonesian', 'Indonesian', 710, 0, 0, '', 'ind', '', 0, 0, 1, '', ''),
('language', 'interlingua_international_auxi', 'Interlingua (International Auxiliary Language Association)', 720, 0, 0, '', 'ina', '', 0, 0, 1, '', ''),
('language', 'interlingue_occidental', 'Interlingue; Occidental', 730, 0, 0, '', 'ile', '', 0, 0, 1, '', ''),
('language', 'inuktitut', 'Inuktitut', 740, 0, 0, '', 'iku', '', 0, 0, 1, '', ''),
('language', 'inupiaq', 'Inupiaq', 750, 0, 0, '', 'ipk', '', 0, 0, 1, '', ''),
('language', 'irish', 'Irish', 760, 0, 0, '', 'gle', '', 0, 0, 1, '', ''),
('language', 'italian', 'Italian', 770, 0, 0, '', 'ita', '', 0, 0, 1, '', ''),
('language', 'japanese', 'Japanese', 780, 0, 0, '', 'jpn', '', 0, 0, 1, '', ''),
('language', 'javanese', 'Javanese', 790, 0, 0, '', 'jav', '', 0, 0, 1, '', ''),
('language', 'kalaallisut_greenlandic', 'Kalaallisut; Greenlandic', 800, 0, 0, '', 'kal', '', 0, 0, 1, '', ''),
('language', 'kannada', 'Kannada', 810, 0, 0, '', 'kan', '', 0, 0, 1, '', ''),
('language', 'kanuri', 'Kanuri', 820, 0, 0, '', 'kau', '', 0, 0, 1, '', ''),
('language', 'kashmiri', 'Kashmiri', 830, 0, 0, '', 'kas', '', 0, 0, 1, '', ''),
('language', 'kazakh', 'Kazakh', 840, 0, 0, '', 'kaz', '', 0, 0, 1, '', ''),
('language', 'kikuyu_gikuyu', 'Kikuyu; Gikuyu', 850, 0, 0, '', 'kik', '', 0, 0, 1, '', ''),
('language', 'kinyarwanda', 'Kinyarwanda', 860, 0, 0, '', 'kin', '', 0, 0, 1, '', ''),
('language', 'kirghiz_kyrgyz', 'Kirghiz; Kyrgyz', 870, 0, 0, '', 'kir', '', 0, 0, 1, '', ''),
('language', 'komi', 'Komi', 880, 0, 0, '', 'kom', '', 0, 0, 1, '', ''),
('language', 'kongo', 'Kongo', 890, 0, 0, '', 'kon', '', 0, 0, 1, '', ''),
('language', 'korean', 'Korean', 900, 0, 0, '', 'kor', '', 0, 0, 1, '', ''),
('language', 'kuanyama_kwanyama', 'Kuanyama; Kwanyama', 910, 0, 0, '', 'kua', '', 0, 0, 1, '', ''),
('language', 'kurdish', 'Kurdish', 920, 0, 0, '', 'kur', '', 0, 0, 1, '', ''),
('language', 'laotian', 'Lao', 930, 0, 0, '', 'lao', '', 0, 0, 1, '', ''),
('language', 'latin', 'Latin', 940, 0, 0, '', 'lat', '', 0, 0, 1, '', ''),
('language', 'latvian', 'Latvian', 950, 0, 0, '', 'lav', '', 0, 0, 1, '', ''),
('language', 'limburgan_limburger_limburgish', 'Limburgan; Limburger; Limburgish', 960, 0, 0, '', 'lim', '', 0, 0, 1, '', ''),
('language', 'lingala', 'Lingala', 970, 0, 0, '', 'lin', '', 0, 0, 1, '', ''),
('language', 'lithuanian', 'Lithuanian', 980, 0, 0, '', 'lit', '', 0, 0, 1, '', ''),
('language', 'luba-katanga', 'Luba-Katanga', 990, 0, 0, '', 'lub', '', 0, 0, 1, '', ''),
('language', 'luxembourgish_letzeburgesch', 'Luxembourgish; Letzeburgesch', 1000, 0, 0, '', 'ltz', '', 0, 0, 1, '', ''),
('language', 'macedonian', 'Macedonian', 1010, 0, 0, '', 'mac(B)|mkd(T)', '', 0, 0, 1, '', ''),
('language', 'malagasy', 'Malagasy', 1020, 0, 0, '', 'mlg', '', 0, 0, 1, '', ''),
('language', 'malay', 'Malay', 1030, 0, 0, '', 'may(B)|msa(T)', '', 0, 0, 1, '', ''),
('language', 'malayalam', 'Malayalam', 1040, 0, 0, '', 'mal', '', 0, 0, 1, '', ''),
('language', 'maltese', 'Maltese', 1050, 0, 0, '', 'mlt', '', 0, 0, 1, '', ''),
('language', 'manx', 'Manx', 1060, 0, 0, '', 'glv', '', 0, 0, 1, '', ''),
('language', 'maori', 'Maori', 1070, 0, 0, '', 'mao(B)|mri(T)', '', 0, 0, 1, '', ''),
('language', 'marathi', 'Marathi', 1080, 0, 0, '', 'mar', '', 0, 0, 1, '', ''),
('language', 'marshallese', 'Marshallese', 1090, 0, 0, '', 'mah', '', 0, 0, 1, '', ''),
('language', 'mongolian', 'Mongolian', 1100, 0, 0, '', 'mon', '', 0, 0, 1, '', ''),
('language', 'nauru', 'Nauru', 1110, 0, 0, '', 'nau', '', 0, 0, 1, '', ''),
('language', 'navajo_navaho', 'Navajo; Navaho', 1120, 0, 0, '', 'nav', '', 0, 0, 1, '', ''),
('language', 'ndebele_north_north_ndebele', 'Ndebele, North; North Ndebele', 1130, 0, 0, '', 'nde', '', 0, 0, 1, '', ''),
('language', 'ndebele_south_south_ndebele', 'Ndebele, South; South Ndebele', 1140, 0, 0, '', 'nbl', '', 0, 0, 1, '', ''),
('language', 'ndonga', 'Ndonga', 1150, 0, 0, '', 'ndo', '', 0, 0, 1, '', ''),
('language', 'nepali', 'Nepali', 1160, 0, 0, '', 'nep', '', 0, 0, 1, '', ''),
('language', 'northern_sami', 'Northern Sami', 1170, 0, 0, '', 'sme', '', 0, 0, 1, '', ''),
('language', 'norwegian', 'Norwegian', 1180, 0, 0, '', 'nor', '', 0, 0, 1, '', ''),
('language', 'norwegian_nynorsk_nynorsk_norw', 'Norwegian Nynorsk; Nynorsk, Norwegian', 1190, 0, 0, '', 'nno', '', 0, 0, 1, '', ''),
('language', 'occitan_post_1500', 'Occitan (post 1500)', 1200, 0, 0, '', 'oci', '', 0, 0, 1, '', ''),
('language', 'ojibwa', 'Ojibwa', 1210, 0, 0, '', 'oji', '', 0, 0, 1, '', ''),
('language', 'oriya', 'Oriya', 1220, 0, 0, '', 'ori', '', 0, 0, 1, '', ''),
('language', 'oromo', 'Oromo', 1230, 0, 0, '', 'orm', '', 0, 0, 1, '', ''),
('language', 'ossetian_ossetic', 'Ossetian; Ossetic', 1240, 0, 0, '', 'oss', '', 0, 0, 1, '', ''),
('language', 'pali', 'Pali', 1250, 0, 0, '', 'pli', '', 0, 0, 1, '', ''),
('language', 'persian', 'Persian', 1260, 0, 0, '', 'per(B)|fas(T)', '', 0, 0, 1, '', ''),
('language', 'polish', 'Polish', 1270, 0, 0, '', 'pol', '', 0, 0, 1, '', ''),
('language', 'portuguese', 'Portuguese', 1280, 0, 0, '', 'por', '', 0, 0, 1, '', ''),
('language', 'punjabi', 'Punjabi', 1290, 0, 0, '', 'pan', '', 0, 0, 1, '', ''),
('language', 'pushto_pashto', 'Pushto; Pashto', 1300, 0, 0, '', 'pus', '', 0, 0, 1, '', ''),
('language', 'quechua', 'Quechua', 1310, 0, 0, '', 'que', '', 0, 0, 1, '', ''),
('language', 'romanian_moldavian_moldovan', 'Romanian; Moldavian; Moldovan', 1320, 0, 0, '', 'rum(B)|ron(T)', '', 0, 0, 1, '', ''),
('language', 'romansh', 'Romansh', 1330, 0, 0, '', 'roh', '', 0, 0, 1, '', ''),
('language', 'rundi', 'Rundi', 1340, 0, 0, '', 'run', '', 0, 0, 1, '', ''),
('language', 'russian', 'Russian', 1350, 0, 0, '', 'rus', '', 0, 0, 1, '', ''),
('language', 'samoan', 'Samoan', 1360, 0, 0, '', 'smo', '', 0, 0, 1, '', ''),
('language', 'sango', 'Sango', 1370, 0, 0, '', 'sag', '', 0, 0, 1, '', ''),
('language', 'sanskrit', 'Sanskrit', 1380, 0, 0, '', 'san', '', 0, 0, 1, '', ''),
('language', 'sardinian', 'Sardinian', 1390, 0, 0, '', 'srd', '', 0, 0, 1, '', ''),
('language', 'serbian', 'Serbian', 1400, 0, 0, '', 'srp', '', 0, 0, 1, '', ''),
('language', 'shona', 'Shona', 1410, 0, 0, '', 'sna', '', 0, 0, 1, '', ''),
('language', 'sichuan_yi_nuosu', 'Sichuan Yi; Nuosu', 1420, 0, 0, '', 'iii', '', 0, 0, 1, '', ''),
('language', 'sindhi', 'Sindhi', 1430, 0, 0, '', 'snd', '', 0, 0, 1, '', ''),
('language', 'sinhala_sinhalese', 'Sinhala; Sinhalese', 1440, 0, 0, '', 'sin', '', 0, 0, 1, '', ''),
('language', 'slovak', 'Slovak', 1450, 0, 0, '', 'slo(B)|slk(T)', '', 0, 0, 1, '', ''),
('language', 'slovenian', 'Slovenian', 1460, 0, 0, '', 'slv', '', 0, 0, 1, '', ''),
('language', 'somali', 'Somali', 1470, 0, 0, '', 'som', '', 0, 0, 1, '', ''),
('language', 'sotho_southern', 'Sotho, Southern', 1480, 0, 0, '', 'sot', '', 0, 0, 1, '', ''),
('language', 'Spanish', 'Spanish', 1490, 0, 0, '', 'spa', '', 0, 0, 1, '', ''),
('language', 'sundanese', 'Sundanese', 1500, 0, 0, '', 'sun', '', 0, 0, 1, '', ''),
('language', 'swahili', 'Swahili', 1510, 0, 0, '', 'swa', '', 0, 0, 1, '', ''),
('language', 'swati', 'Swati', 1520, 0, 0, '', 'ssw', '', 0, 0, 1, '', ''),
('language', 'swedish', 'Swedish', 1530, 0, 0, '', 'swe', '', 0, 0, 1, '', ''),
('language', 'tagalog', 'Tagalog', 1540, 0, 0, '', 'tgl', '', 0, 0, 1, '', ''),
('language', 'tahitian', 'Tahitian', 1550, 0, 0, '', 'tah', '', 0, 0, 1, '', ''),
('language', 'tajik', 'Tajik', 1560, 0, 0, '', 'tgk', '', 0, 0, 1, '', ''),
('language', 'tamil', 'Tamil', 1570, 0, 0, '', 'tam', '', 0, 0, 1, '', ''),
('language', 'tatar', 'Tatar', 1580, 0, 0, '', 'tat', '', 0, 0, 1, '', ''),
('language', 'telugu', 'Telugu', 1590, 0, 0, '', 'tel', '', 0, 0, 1, '', ''),
('language', 'thai', 'Thai', 1600, 0, 0, '', 'tha', '', 0, 0, 1, '', ''),
('language', 'tibetan', 'Tibetan', 1610, 0, 0, '', 'tib(B)|bod(T)', '', 0, 0, 1, '', ''),
('language', 'tigrinya', 'Tigrinya', 1620, 0, 0, '', 'tir', '', 0, 0, 1, '', ''),
('language', 'tonga_tonga_islands', 'Tonga (Tonga Islands)', 1630, 0, 0, '', 'ton', '', 0, 0, 1, '', ''),
('language', 'tsonga', 'Tsonga', 1640, 0, 0, '', 'tso', '', 0, 0, 1, '', ''),
('language', 'tswana', 'Tswana', 1650, 0, 0, '', 'tsn', '', 0, 0, 1, '', ''),
('language', 'turkish', 'Turkish', 1660, 0, 0, '', 'tur', '', 0, 0, 1, '', ''),
('language', 'turkmen', 'Turkmen', 1670, 0, 0, '', 'tuk', '', 0, 0, 1, '', ''),
('language', 'twi', 'Twi', 1680, 0, 0, '', 'twi', '', 0, 0, 1, '', ''),
('language', 'uighur_uyghur', 'Uighur; Uyghur', 1690, 0, 0, '', 'uig', '', 0, 0, 1, '', ''),
('language', 'ukrainian', 'Ukrainian', 1700, 0, 0, '', 'ukr', '', 0, 0, 1, '', ''),
('language', 'urdu', 'Urdu', 1710, 0, 0, '', 'urd', '', 0, 0, 1, '', ''),
('language', 'uzbek', 'Uzbek', 1720, 0, 0, '', 'uzb', '', 0, 0, 1, '', ''),
('language', 'venda', 'Venda', 1730, 0, 0, '', 'ven', '', 0, 0, 1, '', ''),
('language', 'vietnamese', 'Vietnamese', 1740, 0, 0, '', 'vie', '', 0, 0, 1, '', ''),
('language', 'volapuk', 'Volapük', 1750, 0, 0, '', 'vol', '', 0, 0, 1, '', ''),
('language', 'walloon', 'Walloon', 1760, 0, 0, '', 'wln', '', 0, 0, 1, '', ''),
('language', 'welsh', 'Welsh', 1770, 0, 0, '', 'wel(B)|cym(T)', '', 0, 0, 1, '', ''),
('language', 'western_frisian', 'Western Frisian', 1780, 0, 0, '', 'fry', '', 0, 0, 1, '', ''),
('language', 'wolof', 'Wolof', 1790, 0, 0, '', 'wol', '', 0, 0, 1, '', ''),
('language', 'xhosa', 'Xhosa', 1800, 0, 0, '', 'xho', '', 0, 0, 1, '', ''),
('language', 'yiddish', 'Yiddish', 1810, 0, 0, '', 'yid', '', 0, 0, 1, '', ''),
('language', 'yoruba', 'Yoruba', 1820, 0, 0, '', 'yor', '', 0, 0, 1, '', ''),
('language', 'zhuang_chuang', 'Zhuang; Chuang', 1830, 0, 0, '', 'zha', '', 0, 0, 1, '', ''),
('language', 'zulu', 'Zulu', 1840, 0, 0, '', 'zul', '', 0, 0, 1, '', ''),
('lists', 'abook_type', 'Address Book Types', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'ACLT_Tag_Colors', 'ACLT Tag Colors', 299, 1, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'ACLT_Tag_Status', 'ACLT Tag Status', 298, 1, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'adjreason', 'Adjustment Reasons', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'allergy_issue_list', 'Allergy Issue List', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'amendment_from', 'Amendment From', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'amendment_status', 'Amendment Status', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'apptstat', 'Appointment Statuses', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'boolean', 'Boolean', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'cancellation_reasons', 'Cancellation Reasons', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'chartloc', 'Chart Storage Locations', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'clinical_plans', 'Clinical Plans', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'clinical_rules', 'Clinical Rules', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'code_types', 'Code Types', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'country', 'Country', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'county', 'County', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'date_master_criteria', 'Date Master Criteria', 33, 1, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'dental_issue_list', 'Dental Issue List', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'disclosure_type', 'Disclosure Type', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'drug_form', 'Drug Forms', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'drug_interval', 'Drug Intervals', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'drug_route', 'Drug Routes', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'drug_units', 'Drug Units', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'eligibility', 'Eligibility', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'ethnicity', 'Ethnicity', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'ethrace', 'Race/Ethnicity', 12, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'exams', 'Exams/Tests', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'feesheet', 'Fee Sheet', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'general_issue_list', 'General Issue List', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'immunizations', 'Immunizations', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'Immunization_Completion_Status', 'Immunization Completion Status', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'Immunization_Manufacturer', 'Immunization Manufacturer', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'Industry', 'Industry', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'insurance_account_type', 'Insurance Account Types', 0, 0, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'insurance_payment_method', 'Insurance Payment Method', 0, 0, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'insurance_types', 'Insurance Types', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'irnpool', 'Invoice Reference Number Pools', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'issue_subtypes', 'Issue Subtypes', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'issue_types', 'Issue Types', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'language', 'Language', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'lbfnames', 'Layout-Based Visit Forms', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'marital', 'Marital Status', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'medical_problem_issue_list', 'Medical Problem Issue List', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'medication_issue_list', 'Medication Issue List', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'message_status', 'Message Status', 45, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'msp_remit_codes', 'MSP Remit Codes', 221, 0, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'nation_notes_replace_buttons', 'Nation Notes Replace Buttons', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'newcrop_erx_role', 'NewCrop eRx Role', 221, 0, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'note_type', 'Patient Note Types', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'Occupation', 'Occupation', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'occurrence', 'Occurrence', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'order_type', 'Order Types', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'ord_priority', 'Order Priorities', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'ord_status', 'Order Statuses', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'outcome', 'Outcome', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'patient_flow_board_rooms', 'Patient Flow Board Rooms', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'payment_adjustment_code', 'Payment Adjustment Code', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'payment_date', 'Payment Date', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'payment_gateways', 'Payment Gateways', 297, 1, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'payment_ins', 'Payment Ins', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'payment_method', 'Payment Method', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'payment_sort_by', 'Payment Sort By', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'payment_status', 'Payment Status', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'payment_type', 'Payment Type', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'personal_relationship', 'Relationship', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'physician_type', 'Physician Type', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'pricelevel', 'Price Level', 11, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_body_site', 'Procedure Body Sites', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_lat', 'Procedure Lateralities', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_rep_status', 'Procedure Report Statuses', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_res_abnormal', 'Procedure Result Abnormal', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_res_bool', 'Procedure Boolean Results', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_res_status', 'Procedure Result Statuses', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_route', 'Procedure Routes', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_specimen', 'Procedure Specimen Types', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_type', 'Procedure Types', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'proc_unit', 'Procedure Units', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'provider_qualifier_code', 'Provider Qualifier Code', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'ptlistcols', 'Patient List Columns', 1, 0, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'race', 'Race', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'reaction', 'Reaction', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'refsource', 'Referral Source', 13, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'religious_affiliation', 'Religious Affiliation', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'riskfactors', 'Risk Factors', 14, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'risklevel', 'Risk Level', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_action', 'Clinical Rule Action Item', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_action_category', 'Clinical Rule Action Category', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_age_intervals', 'Clinical Rules Age Intervals', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_comparisons', 'Clinical Rules Comparisons', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_enc_types', 'Clinical Rules Encounter Types', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_filters', 'Clinical Rule Filter Methods', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_reminder_due_opt', 'Clinical Rules Reminder Due Options', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_reminder_inactive_opt', 'Clinical Rules Reminder Inactivation Options', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_reminder_intervals', 'Clinical Rules Reminder Intervals', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_reminder_methods', 'Clinical Rules Reminder Methods', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_targets', 'Clinical Rule Target Methods', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'rule_target_intervals', 'Clinical Rules Target Intervals', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'severity_ccda', 'Severity', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'sex', 'Sex', 17, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'smoking_status', 'Smoking Status', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'state', 'State', 18, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'sub_relation', 'Subscriber Relationship', 18, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'superbill', 'Service Category', 16, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'surgery_issue_list', 'Surgery Issue List', 0, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'taxrate', 'Tax Rate', 19, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'titles', 'Titles', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'transactions', 'Layout-Based Transaction Forms', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'transactions_modifiers', 'Transactions Screen Modifiers', 0, 0, 0, '', '', '', 0, 0, 1, '', ''),
('lists', 'ub_admit_source', 'UB Admit Source', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'ub_admit_type', 'UB Admit Type', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'warehouse', 'Warehouses', 21, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('lists', 'yesno', 'Yes/No', 21, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('marital', 'divorced', 'Divorced', 3, 0, 0, '', 'D', '', 0, 0, 1, '', ''),
('marital', 'domestic partner', 'Domestic Partner', 6, 0, 0, '', 'T', '', 0, 0, 1, '', ''),
('marital', 'married', 'Married', 1, 0, 0, '', 'M', '', 0, 0, 1, '', ''),
('marital', 'separated', 'Separated', 5, 0, 0, '', 'L', '', 0, 0, 1, '', ''),
('marital', 'single', 'Single', 2, 0, 0, '', 'S', '', 0, 0, 1, '', ''),
('marital', 'widowed', 'Widowed', 4, 0, 0, '', 'W', '', 0, 0, 1, '', ''),
('medical_problem_issue_list', 'asthma', 'asthma', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('medical_problem_issue_list', 'diabetes', 'diabetes', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('medical_problem_issue_list', 'HTN', 'HTN', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('medical_problem_issue_list', 'hyperlipidemia', 'hyperlipidemia', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('medication_issue_list', 'Lipitor', 'Lipitor', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('medication_issue_list', 'Metformin', 'Metformin', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('medication_issue_list', 'Norvasc', 'Norvasc', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('message_status', 'Done', 'Done', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('message_status', 'Forwarded', 'Forwarded', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('message_status', 'New', 'New', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('message_status', 'Read', 'Read', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('msp_remit_codes', '1', '1', 1, 0, 0, '', 'Deductible Amount', '', 0, 0, 1, '', ''),
('msp_remit_codes', '10', '10', 10, 0, 0, '', 'The diagnosis is inconsistent with the patient\'s gender. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '100', '100', 64, 0, 0, '', 'Payment made to patient/insured/responsible party/employer.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '101', '101', 65, 0, 0, '', 'Predetermination: anticipated payment upon completion of services or claim adjudication.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '102', '102', 66, 0, 0, '', 'Major Medical Adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '103', '103', 67, 0, 0, '', 'Provider promotional discount (e.g., Senior citizen discount).', '', 0, 0, 1, '', ''),
('msp_remit_codes', '104', '104', 68, 0, 0, '', 'Managed care withholding.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '105', '105', 69, 0, 0, '', 'Tax withholding.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '106', '106', 70, 0, 0, '', 'Patient payment option/election not in effect.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '107', '107', 71, 0, 0, '', 'The related or qualifying claim/service was not identified on this claim. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '108', '108', 72, 0, 0, '', 'Rent/purchase guidelines were not met. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '109', '109', 73, 0, 0, '', 'Claim not covered by this payer/contractor. You must send the claim to the correct payer/contractor.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '11', '11', 11, 0, 0, '', 'The diagnosis is inconsistent with the procedure. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '110', '110', 74, 0, 0, '', 'Billing date predates service date.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '111', '111', 75, 0, 0, '', 'Not covered unless the provider accepts assignment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '112', '112', 76, 0, 0, '', 'Service not furnished directly to the patient and/or not documented.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '114', '114', 77, 0, 0, '', 'Procedure/product not approved by the Food and Drug Administration.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '115', '115', 78, 0, 0, '', 'Procedure postponed, canceled, or delayed.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '116', '116', 79, 0, 0, '', 'The advance indemnification notice signed by the patient did not comply with requirements.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '117', '117', 80, 0, 0, '', 'Transportation is only covered to the closest facility that can provide the necessary care.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '118', '118', 81, 0, 0, '', 'ESRD network support adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '119', '119', 82, 0, 0, '', 'Benefit maximum for this time period or occurrence has been reached.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '12', '12', 12, 0, 0, '', 'The diagnosis is inconsistent with the provider type. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '121', '121', 83, 0, 0, '', 'Indemnification adjustment - compensation for outstanding member responsibility.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '122', '122', 84, 0, 0, '', 'Psychiatric reduction.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '125', '125', 85, 0, 0, '', 'Submission/billing error(s). At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '128', '128', 86, 0, 0, '', 'Newborn\'s services are covered in the mother\'s Allowance.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '129', '129', 87, 0, 0, '', 'Prior processing information appears incorrect. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '13', '13', 13, 0, 0, '', 'The date of death precedes the date of service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '130', '130', 88, 0, 0, '', 'Claim submission fee.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '131', '131', 89, 0, 0, '', 'Claim specific negotiated discount.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '132', '132', 90, 0, 0, '', 'Prearranged demonstration project adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '133', '133', 91, 0, 0, '', 'The disposition of this claim/service is pending further review.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '134', '134', 92, 0, 0, '', 'Technical fees removed from charges.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '135', '135', 93, 0, 0, '', 'Interim bills cannot be processed.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '136', '136', 94, 0, 0, '', 'Failure to follow prior payer\'s coverage rules. (Use Group Code OA).', '', 0, 0, 1, '', ''),
('msp_remit_codes', '137', '137', 95, 0, 0, '', 'Regulatory Surcharges, Assessments, Allowances or Health Related Taxes.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '138', '138', 96, 0, 0, '', 'Appeal procedures not followed or time limits not met.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '139', '139', 97, 0, 0, '', 'Contracted funding agreement - Subscriber is employed by the provider of services.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '14', '14', 14, 0, 0, '', 'The date of birth follows the date of service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '140', '140', 98, 0, 0, '', 'Patient/Insured health identification number and name do not match.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '141', '141', 99, 0, 0, '', 'Claim spans eligible and ineligible periods of coverage.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '142', '142', 100, 0, 0, '', 'Monthly Medicaid patient liability amount.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '143', '143', 101, 0, 0, '', 'Portion of payment deferred.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '144', '144', 102, 0, 0, '', 'Incentive adjustment, e.g. preferred product/service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '146', '146', 103, 0, 0, '', 'Diagnosis was invalid for the date(s) of service reported.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '147', '147', 104, 0, 0, '', 'Provider contracted/negotiated rate expired or not on file.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '148', '148', 105, 0, 0, '', 'Information from another provider was not provided or was insufficient/incomplete. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '149', '149', 106, 0, 0, '', 'Lifetime benefit maximum has been reached for this service/benefit category.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '15', '15', 15, 0, 0, '', 'The authorization number is missing, invalid, or does not apply to the billed services or provider.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '150', '150', 107, 0, 0, '', 'Payer deems the information submitted does not support this level of service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '151', '151', 108, 0, 0, '', 'Payment adjusted because the payer deems the information submitted does not support this many/frequency of services.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '152', '152', 109, 0, 0, '', 'Payer deems the information submitted does not support this length of service. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '153', '153', 110, 0, 0, '', 'Payer deems the information submitted does not support this dosage.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '154', '154', 111, 0, 0, '', 'Payer deems the information submitted does not support this day\'s supply.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '155', '155', 112, 0, 0, '', 'Patient refused the service/procedure.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '157', '157', 113, 0, 0, '', 'Service/procedure was provided as a result of an act of war.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '158', '158', 114, 0, 0, '', 'Service/procedure was provided outside of the United States.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '159', '159', 115, 0, 0, '', 'Service/procedure was provided as a result of terrorism.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '16', '16', 16, 0, 0, '', 'Claim/service lacks information which is needed for adjudication. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '160', '160', 116, 0, 0, '', 'Injury/illness was the result of an activity that is a benefit exclusion.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '161', '161', 117, 0, 0, '', 'Provider performance bonus', '', 0, 0, 1, '', ''),
('msp_remit_codes', '162', '162', 118, 0, 0, '', 'State-mandated Requirement for Property and Casualty, see Claim Payment Remarks Code for specific explanation.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '163', '163', 119, 0, 0, '', 'Attachment referenced on the claim was not received.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '164', '164', 120, 0, 0, '', 'Attachment referenced on the claim was not received in a timely fashion.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '165', '165', 121, 0, 0, '', 'Referral absent or exceeded.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '166', '166', 122, 0, 0, '', 'These services were submitted after this payers responsibility for processing claims under this plan ended.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '167', '167', 123, 0, 0, '', 'This (these) diagnosis(es) is (are) not covered. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '168', '168', 124, 0, 0, '', 'Service(s) have been considered under the patient\'s medical plan. Benefits are not available under this dental plan.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '169', '169', 125, 0, 0, '', 'Alternate benefit has been provided.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '170', '170', 126, 0, 0, '', 'Payment is denied when performed/billed by this type of provider. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '171', '171', 127, 0, 0, '', 'Payment is denied when performed/billed by this type of provider in this type of facility. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '172', '172', 128, 0, 0, '', 'Payment is adjusted when performed/billed by a provider of this specialty. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '173', '173', 129, 0, 0, '', 'Service was not prescribed by a physician.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '174', '174', 130, 0, 0, '', 'Service was not prescribed prior to delivery.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '175', '175', 131, 0, 0, '', 'Prescription is incomplete.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '176', '176', 132, 0, 0, '', 'Prescription is not current.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '177', '177', 133, 0, 0, '', 'Patient has not met the required eligibility requirements.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '178', '178', 134, 0, 0, '', 'Patient has not met the required spend down requirements.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '179', '179', 135, 0, 0, '', 'Patient has not met the required waiting requirements. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '18', '18', 17, 0, 0, '', 'Duplicate claim/service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '180', '180', 136, 0, 0, '', 'Patient has not met the required residency requirements.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '181', '181', 137, 0, 0, '', 'Procedure code was invalid on the date of service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '182', '182', 138, 0, 0, '', 'Procedure modifier was invalid on the date of service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '183', '183', 139, 0, 0, '', 'The referring provider is not eligible to refer the service billed. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '184', '184', 140, 0, 0, '', 'The prescribing/ordering provider is not eligible to prescribe/order the service billed. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '185', '185', 141, 0, 0, '', 'The rendering provider is not eligible to perform the service billed. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '186', '186', 142, 0, 0, '', 'Level of care change adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '187', '187', 143, 0, 0, '', 'Consumer Spending Account payments (includes but is not limited to Flexible Spending Account, Health Savings Account, Health Reimbursement Account, etc.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '188', '188', 144, 0, 0, '', 'This product/procedure is only covered when used according to FDA recommendations.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '189', '189', 145, 0, 0, '', '\'\'Not otherwise classified\' or \'unlisted\' procedure code (CPT/HCPCS) was billed when there is a specific procedure code for this procedure/service\'', '', 0, 0, 1, '', ''),
('msp_remit_codes', '19', '19', 18, 0, 0, '', 'This is a work-related injury/illness and thus the liability of the Worker\'s Compensation Carrier.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '190', '190', 146, 0, 0, '', 'Payment is included in the allowance for a Skilled Nursing Facility (SNF) qualified stay.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '191', '191', 147, 0, 0, '', 'Not a work related injury/illness and thus not the liability of the workers\' compensation carrier Note: If adjustment is at the Claim Level, the payer must send and the provider should refer to the 835 Insurance Policy Number Segment (Loop 2100 Other Clai', '', 0, 0, 1, '', ''),
('msp_remit_codes', '192', '192', 148, 0, 0, '', 'Non standard adjustment code from paper remittance. Note: This code is to be used by providers/payers providing Coordination of Benefits information to another payer in the 837 transaction only. This code is only used when the non-standard code cannot be ', '', 0, 0, 1, '', ''),
('msp_remit_codes', '193', '193', 149, 0, 0, '', 'Original payment decision is being maintained. Upon review, it was determined that this claim was processed properly.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '194', '194', 150, 0, 0, '', 'Anesthesia performed by the operating physician, the assistant surgeon or the attending physician.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '195', '195', 151, 0, 0, '', 'Refund issued to an erroneous priority payer for this claim/service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '197', '197', 152, 0, 0, '', 'Precertification/authorization/notification absent.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '198', '198', 153, 0, 0, '', 'Precertification/authorization exceeded.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '199', '199', 154, 0, 0, '', 'Revenue code and Procedure code do not match.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '2', '2', 2, 0, 0, '', 'Coinsurance Amount', '', 0, 0, 1, '', ''),
('msp_remit_codes', '20', '20', 19, 0, 0, '', 'This injury/illness is covered by the liability carrier.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '200', '200', 155, 0, 0, '', 'Expenses incurred during lapse in coverage', '', 0, 0, 1, '', ''),
('msp_remit_codes', '201', '201', 156, 0, 0, '', 'Workers Compensation case settled. Patient is responsible for amount of this claim/service through WC \'Medicare set aside arrangement\' or other agreement. (Use group code PR).', '', 0, 0, 1, '', ''),
('msp_remit_codes', '202', '202', 157, 0, 0, '', 'Non-covered personal comfort or convenience services.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '203', '203', 158, 0, 0, '', 'Discontinued or reduced service.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '204', '204', 159, 0, 0, '', 'This service/equipment/drug is not covered under the patient?s current benefit plan', '', 0, 0, 1, '', ''),
('msp_remit_codes', '205', '205', 160, 0, 0, '', 'Pharmacy discount card processing fee', '', 0, 0, 1, '', ''),
('msp_remit_codes', '206', '206', 161, 0, 0, '', 'National Provider Identifier - missing.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '207', '207', 162, 0, 0, '', 'National Provider identifier - Invalid format', '', 0, 0, 1, '', ''),
('msp_remit_codes', '208', '208', 163, 0, 0, '', 'National Provider Identifier - Not matched.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '209', '209', 164, 0, 0, '', 'Per regulatory or other agreement. The provider cannot collect this amount from the patient. However, this amount may be billed to subsequent payer. Refund to patient if collected. (Use Group code OA)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '21', '21', 20, 0, 0, '', 'This injury/illness is the liability of the no-fault carrier.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '210', '210', 165, 0, 0, '', 'Payment adjusted because pre-certification/authorization not received in a timely fashion', '', 0, 0, 1, '', ''),
('msp_remit_codes', '211', '211', 166, 0, 0, '', 'National Drug Codes (NDC) not eligible for rebate, are not covered.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '212', '212', 167, 0, 0, '', 'Administrative surcharges are not covered', '', 0, 0, 1, '', ''),
('msp_remit_codes', '213', '213', 168, 0, 0, '', 'Non-compliance with the physician self referral prohibition legislation or payer policy.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '214', '214', 169, 0, 0, '', 'Workers\' Compensation claim adjudicated as non-compensable. This Payer not liable for claim or service/treatment. Note: If adjustment is at the Claim Level, the payer must send and the provider should refer to the 835 Insurance Policy Number Segment (Loop', '', 0, 0, 1, '', ''),
('msp_remit_codes', '215', '215', 170, 0, 0, '', 'Based on subrogation of a third party settlement', '', 0, 0, 1, '', ''),
('msp_remit_codes', '216', '216', 171, 0, 0, '', 'Based on the findings of a review organization', '', 0, 0, 1, '', ''),
('msp_remit_codes', '217', '217', 172, 0, 0, '', 'Based on payer reasonable and customary fees. No maximum allowable defined by legislated fee arrangement. (Note: To be used for Workers\' Compensation only)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '218', '218', 173, 0, 0, '', 'Based on entitlement to benefits. Note: If adjustment is at the Claim Level, the payer must send and the provider should refer to the 835 Insurance Policy Number Segment (Loop 2100 Other Claim Related Information REF qualifier \'IG\') for the jurisdictional', '', 0, 0, 1, '', ''),
('msp_remit_codes', '219', '219', 174, 0, 0, '', 'Based on extent of injury. Note: If adjustment is at the Claim Level, the payer must send and the provider should refer to the 835 Insurance Policy Number Segment (Loop 2100 Other Claim Related Information REF qualifier \'IG\') for the jurisdictional regula', '', 0, 0, 1, '', ''),
('msp_remit_codes', '22', '22', 21, 0, 0, '', 'This care may be covered by another payer per coordination of benefits.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '220', '220', 175, 0, 0, '', 'The applicable fee schedule does not contain the billed code. Please resubmit a bill with the appropriate fee schedule code(s) that best describe the service(s) provided and supporting documentation if required. (Note: To be used for Workers\' Compensation', '', 0, 0, 1, '', ''),
('msp_remit_codes', '221', '221', 176, 0, 0, '', 'Workers\' Compensation claim is under investigation. Note: If adjustment is at the Claim Level, the payer must send and the provider should refer to the 835 Insurance Policy Number Segment (Loop 2100 Other Claim Related Information REF qualifier \'IG\') for ', '', 0, 0, 1, '', ''),
('msp_remit_codes', '222', '222', 177, 0, 0, '', 'Exceeds the contracted maximum number of hours/days/units by this provider for this period. This is not patient specific. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '223', '223', 178, 0, 0, '', 'Adjustment code for mandated federal, state or local law/regulation that is not already covered by another code and is mandated before a new code can be created.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '224', '224', 179, 0, 0, '', 'Patient identification compromised by identity theft. Identity verification required for processing this and future claims.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '225', '225', 180, 0, 0, '', 'Penalty or Interest Payment by Payer (Only used for plan to plan encounter reporting within the 837)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '226', '226', 181, 0, 0, '', 'Information requested from the Billing/Rendering Provider was not provided or was insufficient/incomplete. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ', '', 0, 0, 1, '', ''),
('msp_remit_codes', '227', '227', 182, 0, 0, '', 'Information requested from the patient/insured/responsible party was not provided or was insufficient/incomplete. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is ', '', 0, 0, 1, '', ''),
('msp_remit_codes', '228', '228', 183, 0, 0, '', 'Denied for failure of this provider, another provider or the subscriber to supply requested information to a previous payer for their adjudication', '', 0, 0, 1, '', ''),
('msp_remit_codes', '229', '229', 184, 0, 0, '', 'Partial charge amount not considered by Medicare due to the initial claim Type of Bill being 12X. Note: This code can only be used in the 837 transaction to convey Coordination of Benefits information when the secondary payer?s cost avoidance policy allow', '', 0, 0, 1, '', ''),
('msp_remit_codes', '23', '23', 22, 0, 0, '', 'The impact of prior payer(s) adjudication including payments and/or adjustments.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '230', '230', 185, 0, 0, '', 'No available or correlating CPT/HCPCS code to describe this service. Note: Used only by Property and Casualty.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '231', '231', 186, 0, 0, '', 'Mutually exclusive procedures cannot be done in the same day/setting. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '232', '232', 187, 0, 0, '', 'Institutional Transfer Amount. Note - Applies to institutional claims only and explains the DRG amount difference when the patient care crosses multiple institutions.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '233', '233', 188, 0, 0, '', 'Services/charges related to the treatment of a hospital-acquired condition or preventable medical error.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '234', '234', 189, 0, 0, '', 'This procedure is not paid separately. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '235', '235', 190, 0, 0, '', 'Sales Tax', '', 0, 0, 1, '', ''),
('msp_remit_codes', '236', '236', 191, 0, 0, '', 'This procedure or procedure/modifier combination is not compatible with another procedure or procedure/modifier combination provided on the same day according to the National Correct Coding Initiative.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '237', '237', 192, 0, 0, '', 'Legislated/Regulatory Penalty. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '24', '24', 23, 0, 0, '', 'Charges are covered under a capitation agreement/managed care plan.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '26', '26', 24, 0, 0, '', 'Expenses incurred prior to coverage.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '27', '27', 25, 0, 0, '', 'Expenses incurred after coverage terminated.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '29', '29', 26, 0, 0, '', 'The time limit for filing has expired.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '3', '3', 3, 0, 0, '', 'Co-payment Amount', '', 0, 0, 1, '', ''),
('msp_remit_codes', '31', '31', 27, 0, 0, '', 'Patient cannot be identified as our insured.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '32', '32', 28, 0, 0, '', 'Our records indicate that this dependent is not an eligible dependent as defined.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '33', '33', 29, 0, 0, '', 'Insured has no dependent coverage.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '34', '34', 30, 0, 0, '', 'Insured has no coverage for newborns.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '35', '35', 31, 0, 0, '', 'Lifetime benefit maximum has been reached.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '38', '38', 32, 0, 0, '', 'Services not provided or authorized by designated (network/primary care) providers.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '39', '39', 33, 0, 0, '', 'Services denied at the time authorization/pre-certification was requested.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '4', '4', 4, 0, 0, '', 'The procedure code is inconsistent with the modifier used or a required modifier is missing. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '40', '40', 34, 0, 0, '', 'Charges do not meet qualifications for emergent/urgent care. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '44', '44', 35, 0, 0, '', 'Prompt-pay discount.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '45', '45', 36, 0, 0, '', 'Charge exceeds fee schedule/maximum allowable or contracted/legislated fee arrangement. (Use Group Codes PR or CO depending upon liability).', '', 0, 0, 1, '', ''),
('msp_remit_codes', '49', '49', 37, 0, 0, '', 'These are non-covered services because this is a routine exam or screening procedure done in conjunction with a routine exam. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '50', '50', 38, 0, 0, '', 'These are non-covered services because this is not deemed a \'medical necessity\' by the payer. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '51', '51', 39, 0, 0, '', 'These are non-covered services because this is a pre-existing condition. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '53', '53', 40, 0, 0, '', 'Services by an immediate relative or a member of the same household are not covered.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '54', '54', 41, 0, 0, '', 'Multiple physicians/assistants are not covered in this case. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '55', '55', 42, 0, 0, '', 'Procedure/treatment is deemed experimental/investigational by the payer. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '56', '56', 43, 0, 0, '', 'Procedure/treatment has not been deemed \'proven to be effective\' by the payer. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '58', '58', 44, 0, 0, '', 'Treatment was deemed by the payer to have been rendered in an inappropriate or invalid place of service. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '59', '59', 45, 0, 0, '', 'Processed based on multiple or concurrent procedure rules. (For example multiple surgery or diagnostic imaging, concurrent anesthesia.) Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present', '', 0, 0, 1, '', ''),
('msp_remit_codes', '60', '60', 46, 0, 0, '', 'Charges for outpatient services are not covered when performed within a period of time prior to or after inpatient services.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '61', '61', 47, 0, 0, '', 'Penalty for failure to obtain second surgical opinion. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '66', '66', 48, 0, 0, '', 'Blood Deductible.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '69', '69', 49, 0, 0, '', 'Day outlier amount.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '70', '70', 50, 0, 0, '', 'Cost outlier - Adjustment to compensate for additional costs.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '74', '74', 51, 0, 0, '', 'Indirect Medical Education Adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '75', '75', 52, 0, 0, '', 'Direct Medical Education Adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '76', '76', 53, 0, 0, '', 'Disproportionate Share Adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '78', '78', 54, 0, 0, '', 'Non-Covered days/Room charge adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '85', '85', 55, 0, 0, '', 'Patient Interest Adjustment (Use Only Group code PR)', '', 0, 0, 1, '', ''),
('msp_remit_codes', '87', '87', 56, 0, 0, '', 'Transfer amount.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '89', '89', 57, 0, 0, '', 'Professional fees removed from charges.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '9', '9', 9, 0, 0, '', 'The diagnosis is inconsistent with the patient\'s age. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '90', '90', 58, 0, 0, '', 'Ingredient cost adjustment. Note: To be used for pharmaceuticals only.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '91', '91', 59, 0, 0, '', 'Dispensing fee adjustment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '94', '94', 60, 0, 0, '', 'Processed in Excess of charges.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '95', '95', 61, 0, 0, '', 'Plan procedures not followed.', '', 0, 0, 1, '', ''),
('msp_remit_codes', '96', '96', 62, 0, 0, '', 'Non-covered charge(s). At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.) Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 S', '', 0, 0, 1, '', ''),
('msp_remit_codes', '97', '97', 63, 0, 0, '', 'The benefit for this service is included in the payment/allowance for another service/procedure that has already been adjudicated. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'A0', 'A0', 193, 0, 0, '', 'Patient refund amount.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'A1', 'A1', 194, 0, 0, '', 'Claim/Service denied. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'A5', 'A5', 195, 0, 0, '', 'Medicare Claim PPS Capital Cost Outlier Amount.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'A6', 'A6', 196, 0, 0, '', 'Prior hospitalization or 30 day transfer requirement not met.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'A7', 'A7', 197, 0, 0, '', 'Presumptive Payment Adjustment', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'A8', 'A8', 198, 0, 0, '', 'Ungroupable DRG.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B1', 'B1', 199, 0, 0, '', 'Non-covered visits.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B10', 'B10', 200, 0, 0, '', 'Allowed amount has been reduced because a component of the basic procedure/test was paid. The beneficiary is not liable for more than the charge limit for the basic procedure/test.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B11', 'B11', 201, 0, 0, '', 'The claim/service has been transferred to the proper payer/processor for processing. Claim/service not covered by this payer/processor.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B12', 'B12', 202, 0, 0, '', 'Services not documented in patients\' medical records.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B13', 'B13', 203, 0, 0, '', 'Previously paid. Payment for this claim/service may have been provided in a previous payment.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B14', 'B14', 204, 0, 0, '', 'Only one visit or consultation per physician per day is covered.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B15', 'B15', 205, 0, 0, '', 'This service/procedure requires that a qualifying service/procedure be received and covered. The qualifying other service/procedure has not been received/adjudicated. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payme', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B16', 'B16', 206, 0, 0, '', '\'\'New Patient\' qualifications were not met.\'', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B20', 'B20', 207, 0, 0, '', 'Procedure/service was partially or fully furnished by another provider.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B22', 'B22', 208, 0, 0, '', 'This payment is adjusted based on the diagnosis.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B23', 'B23', 209, 0, 0, '', 'Procedure billed is not authorized per your Clinical Laboratory Improvement Amendment (CLIA) proficiency test.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B4', 'B4', 210, 0, 0, '', 'Late filing penalty.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B5', 'B5', 211, 0, 0, '', 'Coverage/program guidelines were not met or were exceeded.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B7', 'B7', 212, 0, 0, '', 'This provider was not certified/eligible to be paid for this procedure/service on this date of service. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B8', 'B8', 213, 0, 0, '', 'Alternative services were available, and should have been utilized. Note: Refer to the 835 Healthcare Policy Identification Segment (loop 2110 Service Payment Information REF), if present.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'B9', 'B9', 214, 0, 0, '', 'Patient is enrolled in a Hospice.', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'D23', 'D23', 215, 0, 0, '', 'This dual eligible patient is covered by Medicare Part D per Medicare Retro-Eligibility. At least one Remark Code must be provided (may be comprised of either the NCPDP Reject Reason Code, or Remittance Advice Remark Code that is not an ALERT.)', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'W1', 'W1', 216, 0, 0, '', 'Workers\' compensation jurisdictional fee schedule adjustment. Note: If adjustment is at the Claim Level, the payer must send and the provider should refer to the 835 Class of Contract Code Identification Segment (Loop 2100 Other Claim Related Information ', '', 0, 0, 1, '', ''),
('msp_remit_codes', 'W2', 'W2', 217, 0, 0, '', 'Payment reduced or denied based on workers\' compensation jurisdictional regulations or payment policies, use only if no other code is applicable. Note: If adjustment is at the Claim Level, the payer must send and the provider should refer to the 835 Insur', '', 0, 0, 1, '', ''),
('newcrop_erx_role', 'erxadmin', 'NewCrop Admin', 5, 0, 0, '', '', '', 0, 0, 1, '', ''),
('newcrop_erx_role', 'erxdoctor', 'NewCrop Doctor', 20, 0, 0, '', '', '', 0, 0, 1, '', ''),
('newcrop_erx_role', 'erxmanager', 'NewCrop Manager', 15, 0, 0, '', '', '', 0, 0, 1, '', ''),
('newcrop_erx_role', 'erxmidlevelPrescriber', 'NewCrop Midlevel Prescriber', 25, 0, 0, '', '', '', 0, 0, 1, '', ''),
('newcrop_erx_role', 'erxnurse', 'NewCrop Nurse', 10, 0, 0, '', '', '', 0, 0, 1, '', ''),
('newcrop_erx_role', 'erxsupervisingDoctor', 'NewCrop Supervising Doctor', 30, 0, 0, '', '', '', 0, 0, 1, '', ''),
('note_type', 'Bill/Collect', 'Bill/Collect', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Chart Note', 'Chart Note', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Insurance', 'Insurance', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Lab Results', 'Lab Results', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'New Document', 'New Document', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'New Orders', 'New Orders', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Other', 'Other', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Patient Reminders', 'Patient Reminders', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Pharmacy', 'Pharmacy', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Prior Auth', 'Prior Auth', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Referral', 'Referral', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Test Scheduling', 'Test Scheduling', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('note_type', 'Unassigned', 'Unassigned', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('Occupation', 'engineer', 'Engineer', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('Occupation', 'lawyer', 'Lawyer', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('Occupation', 'site_worker', 'Site Worker', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('occurrence', '0', 'Unknown or N/A', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('occurrence', '1', 'First', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('occurrence', '4', 'Chronic/Recurrent', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('occurrence', '5', 'Acute on Chronic', 35, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('occurrence', '6', 'Early Recurrence (<2 Mo)', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('occurrence', '7', 'Late Recurrence (2-12 Mo)', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('occurrence', '8', 'Delayed Recurrence (> 12 Mo)', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'enc_checkup_procedure', 'Encounter Checkup Procedure', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'imaging', 'Imaging', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'intervention', 'Intervention', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'laboratory_test', 'Laboratory Test', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'patient_characteristics', 'Patient Characteristics', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'physical_exam', 'Physical Exam', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'procedure', 'Procedure', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('order_type', 'risk_category', 'Risk Category Assessment', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ord_priority', 'high', 'High', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ord_priority', 'normal', 'Normal', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ord_status', 'canceled', 'Canceled', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ord_status', 'complete', 'Complete', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ord_status', 'pending', 'Pending', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ord_status', 'routed', 'Routed', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('outcome', '0', 'Unassigned', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('outcome', '1', 'Resolved', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('outcome', '2', 'Improved', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('outcome', '3', 'Status quo', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('outcome', '4', 'Worse', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('outcome', '5', 'Pending followup', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('patient_flow_board_rooms', '1', 'Room 1', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('patient_flow_board_rooms', '2', 'Room 2', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('patient_flow_board_rooms', '3', 'Room 3', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_adjustment_code', 'family_payment', 'Family Payment', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_adjustment_code', 'group_payment', 'Group Payment', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_adjustment_code', 'insurance_payment', 'Insurance Payment', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_adjustment_code', 'patient_payment', 'Patient Payment', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_adjustment_code', 'pre_payment', 'Pre Payment', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_date', 'date_val', 'Date', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_date', 'deposit_date', 'Deposit Date', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_date', 'post_to_date', 'Post To Date', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_gateways', 'authorize_net', 'Authorize.net', 1, 0, 0, '', '', '', 0, 0, 1, '', ''),
('payment_ins', '0', 'Pat', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_ins', '1', 'Ins1', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_ins', '2', 'Ins2', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_ins', '3', 'Ins3', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_method', 'authorize_net', 'Authorize.net', 60, 0, 0, '', '', '', 0, 0, 1, '', ''),
('payment_method', 'bank_draft', 'Bank Draft', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_method', 'cash', 'Cash', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_method', 'check_payment', 'Check Payment', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_method', 'credit_card', 'Credit Card', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_method', 'electronic', 'Electronic', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_sort_by', 'check_date', 'Check Date', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_sort_by', 'payer_id', 'Ins Code', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_sort_by', 'payment_method', 'Payment Method', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_sort_by', 'payment_type', 'Paying Entity', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_sort_by', 'pay_total', 'Amount', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_sort_by', 'reference', 'Check Number', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_sort_by', 'session_id', 'Id', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_status', 'fully_paid', 'Fully Paid', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_status', 'unapplied', 'Unapplied', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_type', 'insurance', 'Insurance', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('payment_type', 'patient', 'Patient', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('personal_relationship', 'ADOPT', 'Adopted Child', 10, 0, 0, '', 'ADOPT', '', 0, 0, 1, '', ''),
('personal_relationship', 'AUNT', 'Aunt', 20, 0, 0, '', 'AUNT', '', 0, 0, 1, '', ''),
('personal_relationship', 'CHILD', 'Child', 30, 0, 0, '', 'CHILD', '', 0, 0, 1, '', ''),
('personal_relationship', 'CHLDFOST', 'Foster Child', 80, 0, 0, '', 'CHLDFOST', '', 0, 0, 1, '', ''),
('personal_relationship', 'CHLDINLAW', 'Child in-law', 40, 0, 0, '', 'CHLDINLAW', '', 0, 0, 1, '', ''),
('personal_relationship', 'COUSN', 'Cousin', 50, 0, 0, '', 'COUSN', '', 0, 0, 1, '', ''),
('personal_relationship', 'DOMPART', 'Domestic Partner', 60, 0, 0, '', 'DOMPART', '', 0, 0, 1, '', ''),
('personal_relationship', 'FAMMEMB', 'Family Member', 70, 0, 0, '', 'FAMMEMB', '', 0, 0, 1, '', ''),
('personal_relationship', 'FRND', 'Unrelated Friend', 400, 0, 0, '', 'FRND', '', 0, 0, 1, '', ''),
('personal_relationship', 'GGRPRN', 'Great Grandparent', 120, 0, 0, '', 'GGRPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'GPARNT', 'Grandparent', 100, 0, 0, '', 'GPARNT', '', 0, 0, 1, '', ''),
('personal_relationship', 'GRNDCHILD', 'Grandchild', 90, 0, 0, '', 'GRNDCHILD', '', 0, 0, 1, '', ''),
('personal_relationship', 'GRPRN', 'Grandparent', 110, 0, 0, '', 'GRPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'HSIB', 'Half-Sibling', 130, 0, 0, '', 'HSIB', '', 0, 0, 1, '', ''),
('personal_relationship', 'MAUNT', 'MaternalAunt', 140, 0, 0, '', 'MAUNT', '', 0, 0, 1, '', ''),
('personal_relationship', 'MCOUSN', 'MaternalCousin', 150, 0, 0, '', 'MCOUSN', '', 0, 0, 1, '', ''),
('personal_relationship', 'MGGRPRN', 'MaternalGreatgrandparent', 170, 0, 0, '', 'MGGRPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'MGRPRN', 'MaternalGrandparent', 160, 0, 0, '', 'MGRPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'MUNCLE', 'MaternalUncle', 180, 0, 0, '', 'MUNCLE', '', 0, 0, 1, '', ''),
('personal_relationship', 'NBOR', 'Neighbor', 220, 0, 0, '', 'NBOR', '', 0, 0, 1, '', ''),
('personal_relationship', 'NCHILD', 'Natural Child', 190, 0, 0, '', 'NCHILD', '', 0, 0, 1, '', ''),
('personal_relationship', 'NIENEPH', 'Niece/Nephew', 230, 0, 0, '', 'NIENEPH', '', 0, 0, 1, '', ''),
('personal_relationship', 'NPRN', 'Natural Parent', 200, 0, 0, '', 'NPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'NSIB', 'Natural Sibling', 210, 0, 0, '', 'NSIB', '', 0, 0, 1, '', ''),
('personal_relationship', 'PAUNT', 'PaternalAunt', 260, 0, 0, '', 'PAUNT', '', 0, 0, 1, '', ''),
('personal_relationship', 'PCOUSN', 'PaternalCousin', 270, 0, 0, '', 'PCOUSN', '', 0, 0, 1, '', ''),
('personal_relationship', 'PGGRPRN', 'PaternalGreatgrandparent', 290, 0, 0, '', 'PGGRPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'PGRPRN', 'PaternalGrandparent', 280, 0, 0, '', 'PGRPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'PRN', 'Parent', 240, 0, 0, '', 'PRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'PRNINLAW', 'parent in-law', 250, 0, 0, '', 'PRNINLAW', '', 0, 0, 1, '', ''),
('personal_relationship', 'PUNCLE', 'PaternalUncle', 300, 0, 0, '', 'PUNCLE', '', 0, 0, 1, '', ''),
('personal_relationship', 'ROOM', 'Roommate', 310, 0, 0, '', 'ROOM', '', 0, 0, 1, '', ''),
('personal_relationship', 'SIB', 'Sibling', 320, 0, 0, '', 'SIB', '', 0, 0, 1, '', ''),
('personal_relationship', 'SIBINLAW', 'Sibling in-law', 330, 0, 0, '', 'SIBINLAW', '', 0, 0, 1, '', ''),
('personal_relationship', 'SIGOTHR', 'Significant Other', 340, 0, 0, '', 'SIGOTHR', '', 0, 0, 1, '', ''),
('personal_relationship', 'SPS', 'Spouse', 350, 0, 0, '', 'SPS', '', 0, 0, 1, '', ''),
('personal_relationship', 'STEP', 'Step Child', 360, 0, 0, '', 'STEP', '', 0, 0, 1, '', ''),
('personal_relationship', 'STPPRN', 'Step Parent', 370, 0, 0, '', 'STPPRN', '', 0, 0, 1, '', ''),
('personal_relationship', 'STPSIB', 'Step Sibling', 380, 0, 0, '', 'STPSIB', '', 0, 0, 1, '', ''),
('personal_relationship', 'UNCLE', 'Uncle', 390, 0, 0, '', 'UNCLE', '', 0, 0, 1, '', ''),
('physician_type', 'attending_physician', 'Attending physician', 10, 0, 0, '', NULL, 'SNOMED-CT:405279007', 0, 0, 1, '', ''),
('physician_type', 'audiological_physician', 'Audiological physician', 20, 0, 0, '', NULL, 'SNOMED-CT:310172001', 0, 0, 1, '', ''),
('physician_type', 'chest_physician', 'Chest physician', 30, 0, 0, '', NULL, 'SNOMED-CT:309345004', 0, 0, 1, '', ''),
('physician_type', 'community_health_physician', 'Community health physician', 40, 0, 0, '', NULL, 'SNOMED-CT:23278007', 0, 0, 1, '', ''),
('physician_type', 'consultant_physician', 'Consultant physician', 50, 0, 0, '', NULL, 'SNOMED-CT:158967008', 0, 0, 1, '', ''),
('physician_type', 'general_physician', 'General physician', 60, 0, 0, '', NULL, 'SNOMED-CT:59058001', 0, 0, 1, '', ''),
('physician_type', 'genitourinarymedicinephysician', 'Genitourinary medicine physician', 70, 0, 0, '', NULL, 'SNOMED-CT:309358003', 0, 0, 1, '', ''),
('physician_type', 'occupational_physician', 'Occupational physician', 80, 0, 0, '', NULL, 'SNOMED-CT:158973009', 0, 0, 1, '', ''),
('physician_type', 'palliative_care_physician', 'Palliative care physician', 90, 0, 0, '', NULL, 'SNOMED-CT:309359006', 0, 0, 1, '', ''),
('physician_type', 'physician', 'Physician', 100, 0, 0, '', NULL, 'SNOMED-CT:309343006', 0, 0, 1, '', ''),
('physician_type', 'public_health_physician', 'Public health physician', 110, 0, 0, '', NULL, 'SNOMED-CT:56466003', 0, 0, 1, '', ''),
('physician_type', 'rehabilitation_physician', 'Rehabilitation physician', 120, 0, 0, '', NULL, 'SNOMED-CT:309360001', 0, 0, 1, '', ''),
('physician_type', 'resident_physician', 'Resident physician', 130, 0, 0, '', NULL, 'SNOMED-CT:405277009', 0, 0, 1, '', ''),
('physician_type', 'specialized_physician', 'Specialized physician', 140, 0, 0, '', NULL, 'SNOMED-CT:69280009', 0, 0, 1, '', ''),
('physician_type', 'thoracic_physician', 'Thoracic physician', 150, 0, 0, '', NULL, 'SNOMED-CT:309346003', 0, 0, 1, '', ''),
('pricelevel', 'standard', 'Standard', 1, 1, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_body_site', 'arm', 'Arm', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_body_site', 'buttock', 'Buttock', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_body_site', 'oth', 'Other', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_lat', 'bilat', 'Bilateral', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_lat', 'left', 'Left', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_lat', 'right', 'Right', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_rep_status', 'cancel', 'Canceled', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_rep_status', 'correct', 'Corrected', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_rep_status', 'error', 'Error', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_rep_status', 'final', 'Final', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_rep_status', 'prelim', 'Preliminary', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_rep_status', 'review', 'Reviewed', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_abnormal', 'high', 'High', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_abnormal', 'low', 'Low', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_abnormal', 'no', 'No', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_abnormal', 'vhigh', 'Above upper panic limits', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_abnormal', 'vlow', 'Below lower panic limits', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_abnormal', 'yes', 'Yes', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_bool', 'neg', 'Negative', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_bool', 'pos', 'Positive', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_status', 'cancel', 'Canceled', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_status', 'correct', 'Corrected', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_status', 'error', 'Error', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_status', 'final', 'Final', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_status', 'incomplete', 'Incomplete', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_res_status', 'prelim', 'Preliminary', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_route', 'inj', 'Injection', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_route', 'oral', 'Oral', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_route', 'oth', 'Other', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_specimen', 'blood', 'Blood', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_specimen', 'oth', 'Other', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_specimen', 'saliva', 'Saliva', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_specimen', 'urine', 'Urine', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_type', 'grp', 'Group', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_type', 'ord', 'Procedure Order', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_type', 'rec', 'Recommendation', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_type', 'res', 'Discrete Result', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'bool', 'Boolean', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'cu_mm', 'CU.MM', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'days', 'Days', 600, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'fl', 'FL', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'gm_dl', 'GM/DL', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'g_dl', 'G/DL', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'hmol_l', 'HMOL/L', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'iu_l', 'IU/L', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'mg_dl', 'MG/DL', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'mil_cu_mm', 'Mil/CU.MM', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'months', 'Months', 620, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'oth', 'Other', 990, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'percent', 'Percent', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'percentile', 'Percentile', 100, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'pg', 'PG', 110, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'ratio', 'Ratio', 120, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'thous_cu_mm', 'Thous/CU.MM', 130, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'units', 'Units', 140, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'units_l', 'Units/L', 150, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('proc_unit', 'weeks', 'Weeks', 610, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('provider_qualifier_code', 'dk', 'DK', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('provider_qualifier_code', 'dn', 'DN', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('provider_qualifier_code', 'dq', 'DQ', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ptlistcols', 'DOB', 'Date of Birth', 50, 0, 3, '', '', '', 0, 0, 1, '', ''),
('ptlistcols', 'fname', 'First Name', 20, 0, 3, '', '', '', 0, 0, 1, '', ''),
('ptlistcols', 'lname', 'Last Name', 10, 0, 3, '', '', '', 0, 0, 1, '', ''),
('ptlistcols', 'phone_home', 'Home Phone', 30, 0, 3, '', '', '', 0, 0, 1, '', ''),
('ptlistcols', 'pid', 'Patient ID', 70, 0, 3, '', '', '', 0, 0, 1, '', ''),
('race', 'other_race', 'Other Race', 5770, 0, 0, '', '2131-1', '', 0, 0, 0, '', ''),
('reaction', 'hives', 'Hives', 20, 0, 0, '', NULL, 'SNOMED-CT:247472004', 0, 0, 1, '', ''),
('reaction', 'nausea', 'Nausea', 30, 0, 0, '', NULL, 'SNOMED-CT:422587007', 0, 0, 1, '', ''),
('reaction', 'shortness_of_breath', 'Shortness of Breath', 40, 0, 0, '', NULL, 'SNOMED-CT:267036007', 0, 0, 1, '', ''),
('reaction', 'unassigned', 'Unassigned', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Coupon', 'Coupon', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Direct Mail', 'Direct Mail', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Employee', 'Employee', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Newspaper', 'Newspaper', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Other', 'Other', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Patient', 'Patient', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Radio', 'Radio', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Referral Card', 'Referral Card', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'T.V.', 'T.V.', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('refsource', 'Walk-In', 'Walk-In', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('religious_affiliation', 'atheist', 'NONE (non-theist, atheist)', 75, 0, 0, '', '1007', '', 0, 0, 1, '', ''),
('religious_affiliation', 'occult', 'Occult', 495, 0, 0, '', '1035', '', 0, 0, 1, '', ''),
('religious_affiliation', 'orthodox', 'Orthodox', 505, 0, 0, '', '1036', '', 0, 0, 1, '', ''),
('religious_affiliation', 'paganism', 'Paganism', 515, 0, 0, '', '1037', '', 0, 0, 1, '', ''),
('religious_affiliation', 'reformed', 'Reformed', 575, 0, 0, '', '1079', '', 0, 0, 1, '', ''),
('religious_affiliation', 'unitarian_universalist', 'Unitarian Universalist', 715, 0, 0, '', '1081', '', 0, 0, 1, '', ''),
('religious_affiliation', 'veda', 'Veda', 765, 0, 0, '', '1055', '', 0, 0, 1, '', ''),
('religious_affiliation', 'voodoo', 'Voodoo', 775, 0, 0, '', '1056', '', 0, 0, 1, '', ''),
('religious_affiliation', 'wicca', 'Wicca', 785, 0, 0, '', '1057', '', 0, 0, 1, '', ''),
('religious_affiliation', 'zen_buddhism', 'Zen Buddhism', 805, 0, 0, '', '1059', '', 0, 0, 1, '', ''),
('religious_affiliation', 'zoroastrianism', 'Zoroastrianism', 815, 0, 0, '', '1060', '', 0, 0, 1, '', ''),
('riskfactors', 'all', 'Allergies', 14, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'ast', 'Asthma', 16, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'br', 'Breast Disease', 12, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'cl', 'Contact Lenses', 18, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'coc', 'Contraceptive Complication (specify)', 19, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'db', 'Diabetes', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'dpr', 'Depression', 13, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'ep', 'Epilepsy', 17, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'fib', 'Fibroids', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'gb', 'Gall Bladder Condition', 11, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'hd', 'Heart Disease', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'hep', 'Hepatitis', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'ht', 'Hypertension', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'inf', 'Infertility', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'mig', 'Severe Migraine', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'oth', 'Other (specify)', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'pid', 'PID (Pelvic Inflammatory Disease)', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'sc', 'Sickle Cell', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'str', 'Thrombosis/Stroke', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('riskfactors', 'vv', 'Varicose Veins', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('risklevel', 'high', 'High', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('risklevel', 'low', 'Low', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('risklevel', 'medium', 'Medium', 2, 1, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_appointment', 'Appointment', 160, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_bmi', 'BMI', 43, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_bp', 'Blood Pressure', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_colon_cancer_screen', 'Colon Cancer Screening', 130, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_exercise', 'Exercise', 47, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_eye', 'Opthalmic', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_foot', 'Podiatric', 100, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_hemo_a1c', 'Hemoglobin A1C', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_influvacc', 'Influenza Vaccine', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_lab_inr', 'INR', 150, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_mammo', 'Mammogram', 110, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_nutrition', 'Nutrition', 45, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_pap', 'Pap Smear', 120, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_penicillin_allergy', 'Penicillin Allergy', 157, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_pneumovacc', 'Pneumococcal Vaccine', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_prostate_cancer_screen', 'Prostate Cancer Screening', 140, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_soc_sec', 'Social Security Number', 155, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_tobacco', 'Tobacco', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_urine_alb', 'Urine Microalbumin', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action', 'act_wt', 'Weight', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action_category', 'act_cat_assess', 'Assessment', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action_category', 'act_cat_edu', 'Education', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action_category', 'act_cat_exam', 'Examination', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action_category', 'act_cat_inter', 'Intervention', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action_category', 'act_cat_measure', 'Measurement', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action_category', 'act_cat_remind', 'Reminder', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_action_category', 'act_cat_treat', 'Treatment', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_age_intervals', 'month', 'Month', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_age_intervals', 'year', 'Year', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_comparisons', 'EXIST', 'Exist', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_comparisons', 'ge', 'Greater Than or Equal To', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_comparisons', 'gt', 'Greater Than', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_comparisons', 'le', 'Less Than or Equal To', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_comparisons', 'lt', 'Less Than', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_acute_inp_or_ed', 'encounter acute inpatient or ED', 130, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_hea_and_beh', 'encounter health and behavior assessment', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_influenza', 'encounter influenza', 150, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_nonac_inp_out_or_opth', 'Encounter: encounter non-acute inpt, outpatient, or ophthalmology', 140, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_nurs_discharge', 'encounter nursing discharge', 130, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_nurs_fac', 'encounter nursing facility', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_occ_ther', 'encounter occupational therapy', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_off_vis', 'encounter office visit', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_outpatient', 'encounter outpatient', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_out_pcp_obgyn', 'encounter outpatient w/PCP & obgyn', 110, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_pregnancy', 'encounter pregnancy', 120, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_pre_ind_counsel', 'encounter preventive medicine - individual counseling', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_pre_med_group_counsel', 'encounter preventive medicine group counseling', 90, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_pre_med_other_serv', 'encounter preventive medicine other services', 100, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_pre_med_ser_18_older', 'encounter preventive medicine services 18 and older', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_pre_med_ser_40_older', 'encounter preventive medicine 40 and older', 75, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_enc_types', 'enc_psych_and_psych', 'encounter psychiatric & psychologic', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_filters', 'filt_age_max', 'Maximum Age', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_filters', 'filt_age_min', 'Minimum Age', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_filters', 'filt_database', 'Database', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_filters', 'filt_diagnosis', 'Diagnosis', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_filters', 'filt_lists', 'Lists', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_filters', 'filt_proc', 'Procedure', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_filters', 'filt_sex', 'Gender', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_due_opt', 'due', 'Due', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_due_opt', 'not_due', 'Not Due', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_due_opt', 'past_due', 'Past Due', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_due_opt', 'soon_due', 'Due Soon', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_inactive_opt', 'auto', 'Automatic', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_inactive_opt', 'due_status_update', 'Due Status Update', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_inactive_opt', 'manual', 'Manual', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_intervals', 'month', 'Month', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_intervals', 'week', 'Week', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_methods', 'clinical_reminder_post', 'Soon Due Interval (Clinical Reminders)', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_methods', 'clinical_reminder_pre', 'Past Due Interval (Clinical Reminders)', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_methods', 'patient_reminder_post', 'Soon Due Interval (Patient Reminders)', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_reminder_methods', 'patient_reminder_pre', 'Past Due Interval (Patient Reminders)', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_targets', 'target_appt', 'Appointment', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_targets', 'target_database', 'Database', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_targets', 'target_interval', 'Interval', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_targets', 'target_proc', 'Procedure', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'day', 'Day', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'flu_season', 'Flu Season', 80, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'hour', 'Hour', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'minute', 'Minute', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'month', 'Month', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'second', 'Second', 70, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'week', 'Week', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('rule_target_intervals', 'year', 'Year', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('severity_ccda', 'fatal', 'Fatal', 80, 0, 0, '', NULL, 'SNOMED-CT:399166001', 0, 0, 1, '', ''),
('severity_ccda', 'life_threatening_severity', 'Life threatening severity', 70, 0, 0, '', NULL, 'SNOMED-CT:442452003', 0, 0, 1, '', ''),
('severity_ccda', 'mild', 'Mild', 20, 0, 0, '', NULL, 'SNOMED-CT:255604002', 0, 0, 1, '', ''),
('severity_ccda', 'mild_to_moderate', 'Mild to moderate', 30, 0, 0, '', NULL, 'SNOMED-CT:371923003', 0, 0, 1, '', ''),
('severity_ccda', 'moderate', 'Moderate', 40, 0, 0, '', NULL, 'SNOMED-CT:6736007', 0, 0, 1, '', ''),
('severity_ccda', 'moderate_to_severe', 'Moderate to severe', 50, 0, 0, '', NULL, 'SNOMED-CT:371924009', 0, 0, 1, '', ''),
('severity_ccda', 'severe', 'Severe', 60, 0, 0, '', NULL, 'SNOMED-CT:24484000', 0, 0, 1, '', ''),
('severity_ccda', 'unassigned', 'Unassigned', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('sex', 'Female', 'Female', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('sex', 'Male', 'Male', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('smoking_status', '1', 'Current every day smoker', 10, 0, 0, '', NULL, 'SNOMED-CT:449868002', 0, 0, 1, '', ''),
('smoking_status', '15', 'Heavy tobacco smoker', 70, 0, 0, '', NULL, 'SNOMED-CT:428071000124103', 0, 0, 1, '', ''),
('smoking_status', '16', 'Light tobacco smoker', 80, 0, 0, '', NULL, 'SNOMED-CT:428061000124105', 0, 0, 1, '', ''),
('smoking_status', '2', 'Current some day smoker', 20, 0, 0, '', NULL, 'SNOMED-CT:428041000124106', 0, 0, 1, '', ''),
('smoking_status', '3', 'Former smoker', 30, 0, 0, '', NULL, 'SNOMED-CT:8517006', 0, 0, 1, '', ''),
('smoking_status', '4', 'Never smoker', 40, 0, 0, '', NULL, 'SNOMED-CT:266919005', 0, 0, 1, '', ''),
('smoking_status', '5', 'Smoker, current status unknown', 50, 0, 0, '', NULL, 'SNOMED-CT:77176002', 0, 0, 1, '', ''),
('smoking_status', '9', 'Unknown if ever smoked', 60, 0, 0, '', NULL, 'SNOMED-CT:266927001', 0, 0, 1, '', ''),
('state', 'AK', 'Alaska', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'AL', 'Alabama', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'AR', 'Arkansas', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'AZ', 'Arizona', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'CA', 'California', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'CO', 'Colorado', 6, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'CT', 'Connecticut', 7, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'DC', 'District of Columbia', 9, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'DE', 'Delaware', 8, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'FL', 'Florida', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'GA', 'Georgia', 11, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'HI', 'Hawaii', 12, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'IA', 'Iowa', 16, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'ID', 'Idaho', 13, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'IL', 'Illinois', 14, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'IN', 'Indiana', 15, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'KS', 'Kansas', 17, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'KY', 'Kentucky', 18, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'LA', 'Louisiana', 19, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'MA', 'Massachusetts', 22, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'MD', 'Maryland', 21, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'ME', 'Maine', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'MI', 'Michigan', 23, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'MN', 'Minnesota', 24, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'MO', 'Missouri', 26, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'MS', 'Mississippi', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'MT', 'Montana', 27, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'NC', 'North Carolina', 34, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'ND', 'North Dakota', 35, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'NE', 'Nebraska', 28, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'NH', 'New Hampshire', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'NJ', 'New Jersey', 31, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'NM', 'New Mexico', 32, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'NV', 'Nevada', 29, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'NY', 'New York', 33, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'OH', 'Ohio', 36, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'OK', 'Oklahoma', 37, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'OR', 'Oregon', 38, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'PA', 'Pennsylvania', 39, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'RI', 'Rhode Island', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'SC', 'South Carolina', 41, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'SD', 'South Dakota', 42, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'TN', 'Tennessee', 43, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'TX', 'Texas', 44, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'UT', 'Utah', 45, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'VA', 'Virginia', 47, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'VT', 'Vermont', 46, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'WA', 'Washington', 48, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'WI', 'Wisconsin', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'WV', 'West Virginia', 49, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('state', 'WY', 'Wyoming', 51, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('sub_relation', 'child', 'Child', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('sub_relation', 'other', 'Other', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('sub_relation', 'self', 'Self', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('sub_relation', 'spouse', 'Spouse', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('surgery_issue_list', 'appendectomy', 'appendectomy', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('surgery_issue_list', 'cholecystectomy', 'cholecystectomy', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('surgery_issue_list', 'tonsillectomy', 'tonsillectomy', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('titles', 'Dr.', 'Dr.', 4, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('titles', 'Mr.', 'Mr.', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('titles', 'Mrs.', 'Mrs.', 2, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('titles', 'Ms.', 'Ms.', 3, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('transactions', 'LBTbill', 'Billing', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('transactions', 'LBTlegal', 'Legal', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('transactions', 'LBTphreq', 'Physician Request', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('transactions', 'LBTptreq', 'Patient Request', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('transactions', 'LBTref', 'Referral', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('transactions_modifiers', '59', '59', 20, 0, 0, '', '', '', 0, 0, 1, '', ''),
('transactions_modifiers', 'GP', 'GP', 10, 0, 0, '', '', '', 0, 0, 1, '', ''),
('transactions_modifiers', 'KX', 'KX', 30, 0, 0, '', '', '', 0, 0, 1, '', ''),
('ub_admit_source', '1', 'Physician Referral', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '2', 'Clinic Referral', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '3', 'HMO Referral', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '4', 'Transfer from Hospital', 25, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '5', 'Transfer from SNF', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '6', 'Transfer From Another Health Care Facility', 35, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '7', 'Emergency Room', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '8', 'Court/Law Enforcement', 45, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_source', '9', 'Information Not Available', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_type', '1', 'Emergency', 10, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_type', '2', 'Urgent', 20, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_type', '3', 'Elective', 30, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_type', '4', 'Newborn', 40, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_type', '5', 'Trauma', 50, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('ub_admit_type', '9', 'Information Not Available', 60, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('userlist1', 'sample', 'Sample', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('userlist2', 'sample', 'Sample', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('userlist3', 'sample', 'Sample', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('userlist4', 'sample', 'Sample', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('userlist5', 'sample', 'Sample', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('userlist6', 'sample', 'Sample', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('userlist7', 'sample', 'Sample', 1, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('warehouse', 'onsite', 'On Site', 5, 0, 0, '', NULL, '', 0, 0, 1, '', ''),
('yesno', 'NO', 'NO', 1, 0, 0, '', 'N', '', 0, 0, 1, '', ''),
('yesno', 'YES', 'YES', 2, 0, 0, '', 'Y', '', 0, 0, 1, '', '');

-- --------------------------------------------------------
--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE `lists` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `type` varchar(255) default NULL,
  `subtype` varchar(31) NOT NULL DEFAULT '',
  `title` varchar(255) default NULL,
  `begdate` date default NULL,
  `enddate` date default NULL,
  `returndate` date default NULL,
  `occurrence` int(11) default '0',
  `classification` int(11) default '0',
  `referredby` varchar(255) default NULL,
  `extrainfo` varchar(255) default NULL,
  `diagnosis` varchar(255) default NULL,
  `activity` tinyint(4) default NULL,
  `comments` longtext,
  `pid` bigint(20) default NULL,
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `outcome` int(11) NOT NULL default '0',
  `destination` varchar(255) default NULL,
  `reinjury_id` bigint(20)  NOT NULL DEFAULT 0,
  `injury_part` varchar(31) NOT NULL DEFAULT '',
  `injury_type` varchar(31) NOT NULL DEFAULT '',
  `injury_grade` varchar(31) NOT NULL DEFAULT '',
  `reaction` varchar(255) NOT NULL DEFAULT '',
  `external_allergyid` INT(11) DEFAULT NULL,
  `erx_source` ENUM('0','1') DEFAULT '0' NOT NULL  COMMENT '0-LibreEHR 1-External',
  `erx_uploaded` ENUM('0','1') DEFAULT '0' NOT NULL  COMMENT '0-Pending NewCrop upload 1-Uploaded TO NewCrop',
  `modifydate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `severity_al` VARCHAR( 50 ) DEFAULT NULL,
  `external_id` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `lists_touch`
--

DROP TABLE IF EXISTS `lists_touch`;
CREATE TABLE `lists_touch` (
  `pid` bigint(20) NOT NULL default '0',
  `type` varchar(255) NOT NULL default '',
  `date` datetime default NULL,
  PRIMARY KEY  (`pid`,`type`)
) ENGINE=InnoDB ;


--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `event` varchar(255) default NULL,
  `category` varchar(255) default NULL,
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `comments` longtext,
  `user_notes` longtext,
  `patient_id` bigint(20) default NULL,
  `success` tinyint(1) default 1,
  `checksum` longtext,
  `crt_user` varchar(255) default NULL,
  `log_from` VARCHAR(20) DEFAULT 'LibreEHR',
  `menu_item_id` INT(11) DEFAULT NULL,
  `ccda_doc_id` INT(11) DEFAULT NULL COMMENT 'CCDA document id from ccda',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL default '0',
  `foreign_id` int(11) NOT NULL default '0',
  `note` varchar(255) default NULL,
  `owner` int(11) default NULL,
  `date` datetime default NULL,
  `revision` timestamp NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `foreign_id` (`owner`),
  KEY `foreign_id_2` (`foreign_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB;



--
-- Table structure for table `onsite_documents`
--

DROP TABLE IF EXISTS `onsite_documents`;
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

--
-- Table structure for table `onsite_mail`
--

DROP TABLE IF EXISTS `onsite_mail`;
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

--
-- Table structure for table `onsite_messages`
--

DROP TABLE IF EXISTS `onsite_messages`;
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

--
-- Table structure for table `onsite_online`
--

DROP TABLE IF EXISTS `onsite_online`;
CREATE TABLE `onsite_online` (
  `hash` varchar(32) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `last_update` datetime NOT NULL,
  `username` varchar(64) NOT NULL,
  `userid` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB;

--
-- Table structure for table `onsite_portal_activity`
--

DROP TABLE IF EXISTS `onsite_portal_activity`;
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

--
-- Table structure for table `onsite_signatures`
--

DROP TABLE IF EXISTS `onsite_signatures`;
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

--
--

-- Table structure for table `libreehr_postcalendar_categories`
--

DROP TABLE IF EXISTS `libreehr_postcalendar_categories`;
CREATE TABLE `libreehr_postcalendar_categories` (
  `pc_catid` int(11) unsigned NOT NULL auto_increment,
  `pc_catname` varchar(100) default NULL,
  `pc_catcolor` varchar(50) default NULL,
  `pc_catdesc` text,
  `pc_recurrtype` int(1) NOT NULL default '0',
  `pc_enddate` date default NULL,
  `pc_recurrspec` text,
  `pc_recurrfreq` int(3) NOT NULL default '0',
  `pc_duration` bigint(20) NOT NULL default '0',
  `pc_end_date_flag` tinyint(1) NOT NULL default '0',
  `pc_end_date_type` int(2) default NULL,
  `pc_end_date_freq` int(11) NOT NULL default '0',
  `pc_end_all_day` tinyint(1) NOT NULL default '0',
  `pc_dailylimit` int(2) NOT NULL default '0',
  `pc_cattype` INT( 11 ) NOT NULL COMMENT 'Used in grouping categories',
  `pc_active` tinyint(1) NOT NULL default 1,
  `pc_seq` int(11) NOT NULL default '0',
  `pc_categories_icon` text NOT NULL,
  `pc_icon_color` varchar(15) NULL,
  `pc_icon_bg_color` varchar(15) NULL,
  PRIMARY KEY  (`pc_catid`),
  KEY `basic_cat` (`pc_catname`,`pc_catcolor`)
) ENGINE=InnoDB AUTO_INCREMENT=11 ;


--
-- Dumping data for table `libreehr_postcalendar_categories`
-- Not including inserts for `pc_categories_icon`, `pc_icon_color`, `pc_icon_bg_color`
--

INSERT INTO `libreehr_postcalendar_categories` (`pc_catid`,`pc_catname`, `pc_catcolor`, `pc_catdesc`,
  `pc_recurrtype`, `pc_enddate`, `pc_recurrspec`,`pc_recurrfreq`,`pc_duration`, `pc_end_date_flag`, `pc_end_date_type`, 
  `pc_end_date_freq`, `pc_end_all_day`, `pc_dailylimit`,`pc_cattype`, `pc_active`, `pc_seq`) VALUES
 (1, 'No Show', '#DDDDDD', 'Reserved to define when an event did not occur as specified.', 0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 0, 0, 0, 0, 0, 0, 0,1,1),
 (2, 'In Office', '#99CCFF', 'Reserved todefine when a provider may haveavailable appointments after.', 1, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"1";s:22:"event_repeat_freq_type";s:1:"4";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 0, 1, 3, 2, 0, 0, 1,1,2),
 (3, 'Out Of Office', '#99FFFF', 'Reserved to define when a provider may not have available appointments after.', 1, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"1";s:22:"event_repeat_freq_type";s:1:"4";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 0, 1, 3, 2, 0, 0, 1,1,3),
 (4, 'Vacation', '#EFEFEF', 'Reserved for use to define Scheduled Vacation Time', 0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 0, 0, 0, 0, 1, 0, 1,1,4),
 (5, 'Office Visit', '#FFFFCC', 'Normal Office Visit', 0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 900, 0, 0, 0, 0, 0,0,1,5),
 (6, 'Holidays','#9676DB','Clinic holiday',0,NULL,'a:5:{s:17:"event_repeat_freq";s:1:"1";s:22:"event_repeat_freq_type";s:1:"4";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}',0,86400,1,3,2,0,0,2,1,6),
 (7, 'Closed','#2374AB','Clinic closed',0,NULL,'a:5:{s:17:"event_repeat_freq";s:1:"1";s:22:"event_repeat_freq_type";s:1:"4";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}',0,86400,1,3,2,0,0,2,1,7),
 (8, 'Lunch', '#FFFF33', 'Lunch', 1, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"1";s:22:"event_repeat_freq_type";s:1:"4";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 3600, 0, 3, 2, 0, 0, 1,1,8),
 (9, 'Established Patient', '#CCFF33', '', 0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 900, 0, 0, 0, 0, 0, 0,1,9),
 (10,'New Patient', '#CCFFFF', '', 0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 1800, 0, 0, 0, 0, 0, 0,1,10),
 (11,'Reserved','#FF7777','Reserved',1,NULL,'a:5:{s:17:\"event_repeat_freq\";s:1:\"1\";s:22:\"event_repeat_freq_type\";s:1:\"4\";s:19:\"event_repeat_on_num\";s:1:\"1\";s:19:\"event_repeat_on_day\";s:1:\"0\";s:20:\"event_repeat_on_freq\";s:1:\"0\";}',0,900,0,3,2,0,0, 1,1,11),
 (12, 'Health and Behavioral Assessment', '#C7C7C7', 'Health and Behavioral Assessment', 0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 900, 0, 0, 0, 0, 0,0,1,12),
 (13, 'Preventive Care Services',  '#CCCCFF', 'Preventive Care Services',  0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 900, 0, 0, 0, 0, 0,0,1,13),
 (14, 'Ophthalmological Services', '#F89219', 'Ophthalmological Services', 0, NULL, 'a:5:{s:17:"event_repeat_freq";s:1:"0";s:22:"event_repeat_freq_type";s:1:"0";s:19:"event_repeat_on_num";s:1:"1";s:19:"event_repeat_on_day";s:1:"0";s:20:"event_repeat_on_freq";s:1:"0";}', 0, 900, 0, 0, 0, 0, 0,0,1,14);


--
-- Table structure for table `libreehr_postcalendar_events`
--

DROP TABLE IF EXISTS `libreehr_postcalendar_events`;
CREATE TABLE `libreehr_postcalendar_events` (
  `pc_eid` int(11) unsigned NOT NULL auto_increment,
  `pc_catid` int(11) NOT NULL default '0',
  `pc_multiple` int(10) unsigned NOT NULL,
  `pc_aid` varchar(30) default NULL,
  `pc_pid` varchar(11) default NULL,
  `pc_title` varchar(150) default NULL,
  `pc_time` datetime default NULL,
  `pc_hometext` text,
  `pc_comments` int(11) default '0',
  `pc_counter` mediumint(8) unsigned default '0',
  `pc_topic` int(3) NOT NULL default '1',
  `pc_informant` varchar(20) default NULL,
  `pc_eventDate` date NOT NULL default '0000-00-00',
  `pc_endDate` date NOT NULL default '0000-00-00',
  `pc_duration` bigint(20) NOT NULL default '0',
  `pc_recurrtype` int(1) NOT NULL default '0',
  `pc_recurrspec` text,
  `pc_recurrfreq` int(3) NOT NULL default '0',
  `pc_startTime` time default NULL,
  `pc_endTime` time default NULL,
  `pc_alldayevent` int(1) NOT NULL default '0',
  `pc_location` text,
  `pc_conttel` varchar(50) default NULL,
  `pc_contname` varchar(50) default NULL,
  `pc_contemail` varchar(255) default NULL,
  `pc_website` varchar(255) default NULL,
  `pc_fee` varchar(50) default NULL,
  `pc_eventstatus` int(11) NOT NULL default '0',
  `pc_sharing` int(11) NOT NULL default '0',
  `pc_language` varchar(30) default NULL,
  `pc_apptstatus` varchar(15) NOT NULL default '-',
  `pc_prefcatid` int(11) NOT NULL default '0',
  `pc_facility` smallint(6) NOT NULL default '0' COMMENT 'facility id for this event',
  `pc_sendalertsms` VARCHAR(3) NOT NULL DEFAULT 'NO',
  `pc_sendalertemail` VARCHAR( 3 ) NOT NULL DEFAULT 'NO',
  `pc_billing_location` SMALLINT (6) NOT NULL DEFAULT '0',
  `pc_room` varchar(20) NOT NULL DEFAULT '',
  `cancel_reason` text,
  `case_number` VARCHAR(50) DEFAULT NULL,
  `case_body_part` VARCHAR(50) DEFAULT NULL,
  `prior_auth` VARCHAR(50) DEFAULT NULL,
  `prior_auth_2` VARCHAR(50) DEFAULT NULL,
  `bodypart` VARCHAR(120) NOT NULL,
  `bodypart_2` VARCHAR(120) NOT NULL,
  PRIMARY KEY  (`pc_eid`),
  KEY `basic_event` (`pc_catid`,`pc_aid`,`pc_eventDate`,`pc_endDate`,`pc_eventstatus`,`pc_sharing`,`pc_topic`),
  KEY `pc_eventDate` (`pc_eventDate`)
) ENGINE=InnoDB;


--
-- Table structure for table `libreehr_session_info`
--

DROP TABLE IF EXISTS `libreehr_session_info`;
CREATE TABLE `libreehr_session_info` (
  `pn_sessid` varchar(32) NOT NULL default '',
  `pn_ipaddr` varchar(20) default NULL,
  `pn_firstused` int(11) NOT NULL default '0',
  `pn_lastused` int(11) NOT NULL default '0',
  `pn_uid` int(11) NOT NULL default '0',
  `pn_vars` blob,
  PRIMARY KEY  (`pn_sessid`)
) ENGINE=InnoDB;


--
-- Table structure for table `patient_access_onsite`
--

DROP TABLE IF EXISTS `patient_access_onsite`;
CREATE TABLE `patient_access_onsite`(
  `id` INT NOT NULL AUTO_INCREMENT ,
  `pid` INT(11),
  `portal_username` VARCHAR(100) ,
  `portal_pwd` VARCHAR(100) ,
  `portal_pwd_status` TINYINT DEFAULT '1' COMMENT '0=>Password Created Through Demographics by The provider or staff. Patient Should Change it at first time it.1=>Pwd updated or created by patient itself',
  `portal_salt` VARCHAR(100) ,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1;


--
-- Table structure for table `patient_data`
--

DROP TABLE IF EXISTS `patient_data`;
CREATE TABLE `patient_data` (
  `id` bigint(20) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `language` varchar(255) NOT NULL default '',
  `financial` varchar(255) NOT NULL default '',
  `fname` varchar(255) NOT NULL default '',
  `lname` varchar(255) NOT NULL default '',
  `mname` varchar(255) NOT NULL default '',
  `nickname` text,
  `DOB` date default NULL,
  `facility` int(11) default NULL,
  `street` varchar(255) NOT NULL default '',
  `postal_code` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `state` varchar(255) NOT NULL default '',
  `country_code` varchar(255) NOT NULL default '',
  `drivers_license` varchar(255) NOT NULL default '',
  `ss` varchar(255) NOT NULL default '',
  `occupation` longtext,
  `phone_home` varchar(255) NOT NULL default '',
  `phone_biz` varchar(255) NOT NULL default '',
  `phone_contact` varchar(255) NOT NULL default '',
  `phone_cell` varchar(255) NOT NULL default '',
  `pharmacy_id` int(11) NOT NULL default '0',
  `status` varchar(255) NOT NULL default '',
  `contact_relationship` varchar(255) NOT NULL default '',
  `date` datetime default NULL,
  `sex` varchar(255) NOT NULL default '',
  `referrer` varchar(255) NOT NULL default '',
  `referrerID` varchar(255) NOT NULL default '',
  `providerID` int(11) default NULL,
  `ref_providerID` int(11) default NULL,
  `email` varchar(255) NOT NULL default '',
  `email_direct` varchar(255) NOT NULL default '',
  `ethnoracial` varchar(255) NOT NULL default '',
  `race` varchar(255) NOT NULL default '',
  `ethnicity` varchar(255) NOT NULL default '',
  `religion` varchar(40) NOT NULL default '',
  `interpretter` varchar(255) NOT NULL default '',
  `migrantseasonal` varchar(255) NOT NULL default '',
  `family_size` varchar(255) NOT NULL default '',
  `monthly_income` varchar(255) NOT NULL default '',
  `billing_note` text,
  `homeless` varchar(255) NOT NULL default '',
  `financial_review` datetime default NULL,
  `pubpid` varchar(255) NOT NULL default '',
  `pid` bigint(20) NOT NULL default '0',
  `hipaa_mail` varchar(3) NOT NULL default '',
  `hipaa_voice` varchar(3) NOT NULL default '',
  `hipaa_notice` varchar(3) NOT NULL default '',
  `hipaa_message` varchar(20) NOT NULL default '',
  `hipaa_allowsms` VARCHAR(3) NOT NULL DEFAULT 'NO',
  `hipaa_allowemail` VARCHAR(3) NOT NULL DEFAULT 'NO',
  `squad` varchar(32) NOT NULL default '',
  `fitness` int(11) NOT NULL default '0',
  `referral_source` varchar(30) NOT NULL default '',
  `pricelevel` varchar(255) NOT NULL default 'standard',
  `regdate`     date DEFAULT NULL COMMENT 'Registration Date',
  `contrastart` date DEFAULT NULL COMMENT 'Date contraceptives initially used',
  `completed_ad` VARCHAR(3) NOT NULL DEFAULT 'NO',
  `ad_reviewed` date DEFAULT NULL,
  `vfc` varchar(255) NOT NULL DEFAULT '',
  `mothersname` varchar(255) NOT NULL DEFAULT '',
  `guardiansname` TEXT,
  `allow_imm_reg_use` varchar(255) NOT NULL DEFAULT '',
  `allow_imm_info_share` varchar(255) NOT NULL DEFAULT '',
  `allow_health_info_ex` varchar(255) NOT NULL DEFAULT '',
  `allow_patient_portal` varchar(31) NOT NULL DEFAULT '',
  `deceased_date` datetime default NULL,
  `deceased_reason` varchar(255) NOT NULL default '',
  `soap_import_status` TINYINT(4) DEFAULT NULL COMMENT '1-Prescription Press 2-Prescription Import 3-Allergy Press 4-Allergy Import',
  `care_team` int(11) DEFAULT NULL,
  `county` varchar(40) NOT NULL default '',
  `statement_y_n` TEXT,
  `industry` TEXT,
  `picture_url` varchar(2000) NOT NULL default '',
  UNIQUE KEY `pid` (`pid`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `patient_reminders`
--

DROP TABLE IF EXISTS `patient_reminders`;
CREATE TABLE `patient_reminders` (
  `id` bigint(20) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL default 1 COMMENT '1 if active and 0 if not active',
  `date_inactivated` datetime DEFAULT NULL,
  `reason_inactivated` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_reminder_inactive_opt',
  `due_status` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_reminder_due_opt',
  `pid` bigint(20) NOT NULL COMMENT 'id from patient_data table',
  `category` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the category item in the rule_action_item table',
  `item` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the item column in the rule_action_item table',
  `date_created` datetime DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  `voice_status` tinyint(1) NOT NULL default 0 COMMENT '0 if not sent and 1 if sent',
  `sms_status` tinyint(1) NOT NULL default 0 COMMENT '0 if not sent and 1 if sent',
  `email_status` tinyint(1) NOT NULL default 0 COMMENT '0 if not sent and 1 if sent',
  `mail_status` tinyint(1) NOT NULL default 0 COMMENT '0 if not sent and 1 if sent',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY (`category`,`item`)
) ENGINE=InnoDB AUTO_INCREMENT=1;


--
-- Table structure for table `patient_tracker`
--

DROP TABLE IF EXISTS `patient_tracker`;
CREATE TABLE IF NOT EXISTS `patient_tracker` (
  `id`                     bigint(20)   NOT NULL auto_increment,
  `date`                   datetime     DEFAULT NULL,
  `apptdate`               date         DEFAULT NULL,
  `appttime`               time         DEFAULT NULL,
  `eid`                    bigint(20)   NOT NULL default '0',
  `pid`                    bigint(20)   NOT NULL default '0',
  `original_user`          varchar(255) NOT NULL default '' COMMENT 'This is the user that created the original record',
  `encounter`              bigint(20)   NOT NULL default '0',
  `lastseq`                varchar(4)   NOT NULL default '' COMMENT 'The element file should contain this number of elements',
  `random_drug_test`       TINYINT(1)   DEFAULT NULL COMMENT 'NULL if not randomized. If randomized, 0 is no, 1 is yes',
  `drug_screen_completed`  TINYINT(1)   NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY (`eid`),
  KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

--
-- Table structure for table `patient_tracker_element`
--

DROP TABLE IF EXISTS `patient_tracker_element`;
CREATE TABLE IF NOT EXISTS `patient_tracker_element` (
  `pt_tracker_id`      bigint(20)   NOT NULL default '0' COMMENT 'maps to id column in patient_tracker table',
  `start_datetime`     datetime     DEFAULT NULL,
  `room`               varchar(20)  NOT NULL default '',
  `status`             varchar(31)  NOT NULL default '',
  `seq`                varchar(4)   NOT NULL default '' COMMENT 'This is a numerical sequence for this pt_tracker_id events',
  `user`               varchar(255) NOT NULL default '' COMMENT 'This is the user that created this element',
  `reason`             varchar(255) NOT NULL default '' COMMENT 'This is the reason for cancellation',
  KEY  (`pt_tracker_id`,`seq`)
) ENGINE=InnoDB;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL auto_increment,
  `pid` bigint(20) NOT NULL default '0',
  `dtime` datetime NOT NULL,
  `encounter` bigint(20) NOT NULL default '0',
  `user` varchar(255) default NULL,
  `method` varchar(255) default NULL,
  `source` varchar(255) default NULL,
  `amount1` decimal(12,2) NOT NULL default '0.00',
  `amount2` decimal(12,2) NOT NULL default '0.00',
  `posted1` decimal(12,2) NOT NULL default '0.00',
  `posted2` decimal(12,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `payment_gateway_details`
--
DROP TABLE IF EXISTS `payment_gateway_details`;
CREATE TABLE `payment_gateway_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) DEFAULT NULL,
  `login_id` varchar(255) DEFAULT NULL,
  `transaction_key` varchar(255) DEFAULT NULL,
  `md5` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `pharmacies`
--

DROP TABLE IF EXISTS `pharmacies`;
CREATE TABLE `pharmacies` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `transmit_method` int(11) NOT NULL default '1',
  `email` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `phone_numbers`
--

DROP TABLE IF EXISTS `phone_numbers`;
CREATE TABLE `phone_numbers` (
  `id` int(11) NOT NULL default '0',
  `country_code` varchar(5) default NULL,
  `area_code` char(3) default NULL,
  `prefix` char(3) default NULL,
  `number` varchar(4) default NULL,
  `type` int(11) default NULL,
  `foreign_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB;


--
-- Table structure for table `pnotes`
--

DROP TABLE IF EXISTS `pnotes`;
CREATE TABLE `pnotes` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `body` longtext,
  `pid` bigint(20) default NULL,
  `user` varchar(255) default NULL,
  `groupname` varchar(255) default NULL,
  `activity` tinyint(4) default NULL,
  `authorized` tinyint(4) default NULL,
  `title` varchar(255) default NULL,
  `assigned_to` varchar(255) default NULL,
  `deleted` tinyint(4) default 0 COMMENT 'flag indicates note is deleted',
  `message_status` VARCHAR(20) NOT NULL DEFAULT 'New',
  `portal_relation` VARCHAR(100) NULL,
  `is_msg_encrypted` TINYINT(2) DEFAULT '0' COMMENT 'Whether messsage encrypted 0-Not encrypted, 1-Encrypted',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL auto_increment,
  `patient_id` int(11) default NULL,
  `filled_by_id` int(11) default NULL,
  `pharmacy_id` int(11) default NULL,
  `date_added` date default NULL,
  `date_modified` date default NULL,
  `provider_id` int(11) default NULL,
  `encounter` int(11) default NULL,
  `start_date` date default NULL,
  `drug` varchar(150) default NULL,
  `drug_id` int(11) NOT NULL default '0',
  `rxnorm_drugcode` INT(11) DEFAULT NULL,
  `form` int(3) default NULL,
  `dosage` varchar(100) default NULL,
  `quantity` varchar(31) default NULL,
  `size` varchar(16) DEFAULT NULL,
  `unit` int(11) default NULL,
  `route` int(11) default NULL,
  `interval` int(11) default NULL,
  `substitute` int(11) default NULL,
  `refills` int(11) default NULL,
  `per_refill` int(11) default NULL,
  `filled_date` date default NULL,
  `medication` int(11) default NULL,
  `note` text,
  `active` int(11) NOT NULL default '1',
  `datetime` DATETIME DEFAULT NULL,
  `user` VARCHAR(50) DEFAULT NULL,
  `site` VARCHAR(50) DEFAULT NULL,
  `prescriptionguid` VARCHAR(50) DEFAULT NULL,
  `erx_source` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0-LibreEHR 1-External',
  `erx_uploaded` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0-Pending NewCrop upload 1-Uploaded to NewCrop',
  `drug_info_erx` TEXT,
  `external_id` VARCHAR(20) DEFAULT NULL,
  `end_date` date default NULL,
  `indication` text,
  `prn` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
CREATE TABLE `prices` (
  `pr_id` varchar(11) NOT NULL default '',
  `pr_selector` varchar(255) NOT NULL default '' COMMENT 'template selector for drugs, empty for codes',
  `pr_level` varchar(31) NOT NULL default '',
  `pr_price` decimal(12,2) NOT NULL default '0.00' COMMENT 'price in local currency',
  PRIMARY KEY  (`pr_id`,`pr_selector`,`pr_level`)
) ENGINE=InnoDB;


--
-- Table structure for table `registry`
--

DROP TABLE IF EXISTS `registry`;
CREATE TABLE `registry` (
  `name` varchar(255) default NULL,
  `state` tinyint(4) default NULL,
  `directory` varchar(255) default NULL,
  `id` bigint(20) NOT NULL auto_increment,
  `sql_run` tinyint(4) default NULL,
  `unpackaged` tinyint(4) default NULL,
  `date` datetime default NULL,
  `priority` int(11) default '0',
  `category` varchar(255) default NULL,
  `nickname` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 ;

--
-- Dumping data for table `registry`
--

INSERT INTO `registry` VALUES ('New Encounter Form', 1, 'patient_encounter', 1, 1, 1, '2003-09-14 15:16:45', 0, 'Administrative', '');
INSERT INTO `registry` VALUES ('Review of Systems Checks', 1, 'reviewofs', 9, 1, 1, '2003-09-14 15:16:45', 0, 'Clinical', '');
INSERT INTO `registry` VALUES ('Speech Dictation', 1, 'dictation', 10, 1, 1, '2003-09-14 15:16:45', 0, 'Clinical', '');
INSERT INTO `registry` VALUES ('SOAP', 1, 'soap', 11, 1, 1, '2005-03-03 00:16:35', 0, 'Clinical', '');
INSERT INTO `registry` VALUES ('Vitals', 1, 'vitals', 12, 1, 1, '2005-03-03 00:16:34', 0, 'Clinical', '');
INSERT INTO `registry` VALUES ('Review Of Systems', 1, 'ros', 13, 1, 1, '2005-03-03 00:16:30', 0, 'Clinical', '');
INSERT INTO `registry` VALUES ('Fee Sheet', 1, 'fee_sheet', 14, 1, 1, '2007-07-28 00:00:00', 0, 'Administrative', '');
INSERT INTO `registry` VALUES ('Misc Billing Options HCFA', 1, 'misc_billing_options', 15, 1, 1, '2007-07-28 00:00:00', 0, 'Administrative', '');
INSERT INTO `registry` VALUES ('Procedure Order', 1, 'procedure_order', 16, 1, 1, '2010-02-25 00:00:00', 0, 'Administrative', '');
INSERT INTO `registry` VALUES ('Annotatable Diagrams', 1, 'annotate_diagram', 17, 1, 1, '2017-02-02 00:00:00', 0, 'Clinical', '');
INSERT INTO `registry` VALUES ('Clinical Instructions', 1, 'clinical_instructions', 20, 1, 1, '2015-09-09 00:00:00', 0, 'Clinical', '');


--
-- Table structure for table `report_itemized`
-- (goal is optimize insert performance, so only one key)

DROP TABLE IF EXISTS `report_itemized`;
CREATE TABLE `report_itemized` (
  `report_id` bigint(20) NOT NULL,
  `itemized_test_id` smallint(6) NOT NULL,
  `numerator_label` varchar(25) NOT NULL DEFAULT '' COMMENT 'Only used in special cases',
  `pass` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 is fail, 1 is pass, 2 is excluded,9 is off',
  `pid` bigint(20) NOT NULL,
  KEY (`report_id`,`itemized_test_id`,`numerator_label`,`pass`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `report_results`
--

DROP TABLE IF EXISTS `report_results`;
CREATE TABLE `report_results` (
  `report_id` bigint(20) NOT NULL,
  `field_id` varchar(31) NOT NULL default '',
  `field_value` longtext,
  PRIMARY KEY (`report_id`,`field_id`)
) ENGINE=InnoDB;


--
-- Table structure for table `rule_action`
--

DROP TABLE IF EXISTS `rule_action`;
CREATE TABLE `rule_action` (
  `id` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the id column in the clinical_rules table',
  `group_id` bigint(20) NOT NULL DEFAULT 1 COMMENT 'Contains group id to identify collection of targets in a rule',
  `category` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the category item in the rule_action_item table',
  `item` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the item column in the rule_action_item table',
  KEY  (`id`)
) ENGINE=InnoDB ;

--
-- Standard clinical rule actions
--
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_htn_bp_measure', 1, 'act_cat_measure', 'act_bp');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_tob_use_assess', 1, 'act_cat_assess', 'act_tobacco');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_tob_cess_inter', 1, 'act_cat_inter', 'act_tobacco');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_adult_wt_screen_fu', 1, 'act_cat_measure', 'act_wt');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_wt_assess_couns_child', 1, 'act_cat_measure', 'act_wt');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_wt_assess_couns_child', 2, 'act_cat_edu', 'act_wt');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_wt_assess_couns_child', 3, 'act_cat_edu', 'act_nutrition');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_wt_assess_couns_child', 4, 'act_cat_edu', 'act_exercise');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_wt_assess_couns_child', 5, 'act_cat_measure', 'act_bmi');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_influenza_ge_50', 1, 'act_cat_treat', 'act_influvacc');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_pneumovacc_ge_65', 1, 'act_cat_treat', 'act_pneumovacc');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_dm_hemo_a1c', 1, 'act_cat_measure', 'act_hemo_a1c');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_dm_urine_alb', 1, 'act_cat_measure', 'act_urine_alb');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_dm_eye', 1, 'act_cat_exam', 'act_eye');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_dm_foot', 1, 'act_cat_exam', 'act_foot');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_cs_mammo', 1, 'act_cat_measure', 'act_mammo');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_cs_pap', 1, 'act_cat_exam', 'act_pap');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_cs_colon', 1, 'act_cat_assess', 'act_colon_cancer_screen');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_cs_prostate', 1, 'act_cat_assess', 'act_prostate_cancer_screen');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_inr_monitor', 1, 'act_cat_measure', 'act_lab_inr');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_socsec_entry', 1, 'act_cat_assess', 'act_soc_sec');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_penicillin_allergy', 1, 'act_cat_assess', 'act_penicillin_allergy');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_blood_pressure', 1, 'act_cat_measure', 'act_bp');
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_inr_measure', 1, 'act_cat_measure', 'act_lab_inr');


--
-- Table structure for table `rule_action_item`
--

DROP TABLE IF EXISTS `rule_action_item`;
CREATE TABLE `rule_action_item` (
  `category` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_action_category',
  `item` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_action',
  `clin_rem_link` varchar(255) NOT NULL DEFAULT '' COMMENT 'Custom html link in clinical reminder widget',
  `reminder_message` TEXT COMMENT 'Custom message in patient reminder',
  `custom_flag` tinyint(1) NOT NULL default 0 COMMENT '1 indexed to rule_patient_data, 0 indexed within main schema',
  PRIMARY KEY  (`category`,`item`)
) ENGINE=InnoDB ;

INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_measure', 'act_bp', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_assess', 'act_tobacco', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_inter', 'act_tobacco', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_measure', 'act_wt', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_edu', 'act_wt', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_measure', 'act_bmi', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_edu', 'act_nutrition', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_edu', 'act_exercise', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_treat', 'act_influvacc', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_treat', 'act_pneumovacc', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_measure', 'act_hemo_a1c', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_measure', 'act_urine_alb', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_exam', 'act_eye', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_exam', 'act_foot', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_measure', 'act_mammo', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_exam', 'act_pap', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_assess', 'act_colon_cancer_screen', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_assess', 'act_prostate_cancer_screen', '', '', 1);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_measure', 'act_lab_inr', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_assess', 'act_soc_sec', '', '', 0);
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_assess', 'act_penicillin_allergy', '', '', 1);


--
-- Table structure for table `rule_filter`
--

DROP TABLE IF EXISTS `rule_filter`;
CREATE TABLE `rule_filter` (
  `id` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the id column in the clinical_rules table',
  `include_flag` tinyint(1) NOT NULL default 0 COMMENT '0 is exclude and 1 is include',
  `required_flag` tinyint(1) NOT NULL default 0 COMMENT '0 is required and 1 is optional',
  `method` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_filters',
  `method_detail` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options lists rule__intervals',
  `value` varchar(255) NOT NULL DEFAULT '',
  KEY  (`id`)
) ENGINE=InnoDB ;

--
-- Standard clinical rule filters
--
-- Hypertension: Blood Pressure Measurement
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_htn_bp_measure', 1, 0, 'filt_lists', 'medical_problem', 'CUSTOM::HTN');

-- Tobacco Use Assessment
-- no filters
-- Tobacco Cessation Intervention
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_cess_inter', 1, 1, 'filt_database', '', 'LIFESTYLE::tobacco::current');
-- Adult Weight Screening and Follow-Up
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_adult_wt_screen_fu', 1, 1, 'filt_age_min', 'year', '18');
-- Weight Assessment and Counseling for Children and Adolescents
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_wt_assess_couns_child', 1, 1, 'filt_age_max', 'year', '18');
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_wt_assess_couns_child', 1, 1, 'filt_age_min', 'year', '2');
-- Influenza Immunization for Patients >= 50 Years Old
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_influenza_ge_50', 1, 1, 'filt_age_min', 'year', '50');
-- Pneumonia Vaccination Status for Older Adults
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_pneumovacc_ge_65', 1, 1, 'filt_age_min', 'year', '65');
-- Diabetes: Hemoglobin A1C
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_hemo_a1c', 1, 0, 'filt_lists', 'medical_problem', 'CUSTOM::diabetes');

-- Diabetes: Urine Microalbumin
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_urine_alb', 1, 0, 'filt_lists', 'medical_problem', 'CUSTOM::diabetes');

-- Diabetes: Eye Exam
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_eye', 1, 0, 'filt_lists', 'medical_problem', 'CUSTOM::diabetes');

-- Diabetes: Foot Exam
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_foot', 1, 0, 'filt_lists', 'medical_problem', 'CUSTOM::diabetes');

-- Cancer Screening: Mammogram
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_mammo', 1, 1, 'filt_age_min', 'year', '40');
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_mammo', 1, 1, 'filt_sex', '', 'Female');
-- Cancer Screening: Pap Smear
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_pap', 1, 1, 'filt_age_min', 'year', '18');
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_pap', 1, 1, 'filt_sex', '', 'Female');
-- Cancer Screening: Colon Cancer Screening
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_colon', 1, 1, 'filt_age_min', 'year', '50');
-- Cancer Screening: Prostate Cancer Screening
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_prostate', 1, 1, 'filt_age_min', 'year', '50');
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_prostate', 1, 1, 'filt_sex', '', 'Male');
--
-- Rule filters to specifically demonstrate passing of NIST criteria
--
-- Coumadin Management - INR Monitoring
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_monitor', 1, 0, 'filt_lists', 'medication', 'coumadin');
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_monitor', 1, 0, 'filt_lists', 'medication', 'warfarin');
-- Penicillin Allergy Assessment
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 1, 0, 'filt_lists', 'allergy', 'penicillin');


--
-- Table structure for table `rule_patient_data`
--

DROP TABLE IF EXISTS `rule_patient_data`;
CREATE TABLE `rule_patient_data` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) NOT NULL,
  `category` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the category item in the rule_action_item table',
  `item` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the item column in the rule_action_item table',
  `complete` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list yesno',
  `result` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY (`pid`),
  KEY (`category`,`item`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `rule_reminder`
--

DROP TABLE IF EXISTS `rule_reminder`;
CREATE TABLE `rule_reminder` (
  `id` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the id column in the clinical_rules table',
  `method` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_reminder_methods',
  `method_detail` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_reminder_intervals',
  `value` varchar(255) NOT NULL DEFAULT '',
  KEY  (`id`)
) ENGINE=InnoDB ;

-- Hypertension: Blood Pressure Measurement
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_htn_bp_measure', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_htn_bp_measure', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_htn_bp_measure', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_htn_bp_measure', 'patient_reminder_post', 'month', '1');
-- Tobacco Use Assessment
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_use_assess', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_use_assess', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_use_assess', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_use_assess', 'patient_reminder_post', 'month', '1');
-- Tobacco Cessation Intervention
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_cess_inter', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_cess_inter', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_cess_inter', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_tob_cess_inter', 'patient_reminder_post', 'month', '1');
-- Adult Weight Screening and Follow-Up
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_adult_wt_screen_fu', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_adult_wt_screen_fu', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_adult_wt_screen_fu', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_adult_wt_screen_fu', 'patient_reminder_post', 'month', '1');
-- Weight Assessment and Counseling for Children and Adolescents
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_wt_assess_couns_child', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_wt_assess_couns_child', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_wt_assess_couns_child', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_wt_assess_couns_child', 'patient_reminder_post', 'month', '1');
-- Influenza Immunization for Patients >= 50 Years Old
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_influenza_ge_50', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_influenza_ge_50', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_influenza_ge_50', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_influenza_ge_50', 'patient_reminder_post', 'month', '1');
-- Pneumonia Vaccination Status for Older Adults
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_pneumovacc_ge_65', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_pneumovacc_ge_65', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_pneumovacc_ge_65', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_pneumovacc_ge_65', 'patient_reminder_post', 'month', '1');
-- Diabetes: Hemoglobin A1C
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_hemo_a1c', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_hemo_a1c', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_hemo_a1c', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_hemo_a1c', 'patient_reminder_post', 'month', '1');
-- Diabetes: Urine Microalbumin
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_urine_alb', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_urine_alb', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_urine_alb', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_urine_alb', 'patient_reminder_post', 'month', '1');
-- Diabetes: Eye Exam
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_eye', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_eye', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_eye', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_eye', 'patient_reminder_post', 'month', '1');
-- Diabetes: Foot Exam
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_foot', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_foot', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_foot', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_dm_foot', 'patient_reminder_post', 'month', '1');
-- Cancer Screening: Mammogram
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_mammo', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_mammo', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_mammo', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_mammo', 'patient_reminder_post', 'month', '1');
-- Cancer Screening: Pap Smear
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_pap', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_pap', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_pap', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_pap', 'patient_reminder_post', 'month', '1');
-- Cancer Screening: Colon Cancer Screening
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_colon', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_colon', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_colon', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_colon', 'patient_reminder_post', 'month', '1');
-- Cancer Screening: Prostate Cancer Screening
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_prostate', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_prostate', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_prostate', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_cs_prostate', 'patient_reminder_post', 'month', '1');
-- Coumadin Management - INR Monitoring
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_monitor', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_monitor', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_monitor', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_monitor', 'patient_reminder_post', 'month', '1');
-- Penicillin Allergy Assessment
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'patient_reminder_post', 'month', '1');
-- Blood Pressure Measurement
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'patient_reminder_post', 'month', '1');
-- INR Measurement
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'patient_reminder_post', 'month', '1');


--
-- Table structure for table `rule_target`
--

DROP TABLE IF EXISTS `rule_target`;
CREATE TABLE `rule_target` (
  `id` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to the id column in the clinical_rules table',
  `group_id` bigint(20) NOT NULL DEFAULT 1 COMMENT 'Contains group id to identify collection of targets in a rule',
  `include_flag` tinyint(1) NOT NULL default 0 COMMENT '0 is exclude and 1 is include',
  `required_flag` tinyint(1) NOT NULL default 0 COMMENT '0 is required and 1 is optional',
  `method` varchar(31) NOT NULL DEFAULT '' COMMENT 'Maps to list_options list rule_targets',
  `value` varchar(255) NOT NULL DEFAULT '' COMMENT 'Data is dependent on the method',
  `interval` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Only used in interval entries',
  KEY  (`id`)
) ENGINE=InnoDB ;

--
-- Standard clinical rule targets
--
-- Hypertension: Blood Pressure Measurement
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_htn_bp_measure', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_htn_bp_measure', 1, 1, 1, 'target_database', '::form_vitals::bps::::::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_htn_bp_measure', 1, 1, 1, 'target_database', '::form_vitals::bpd::::::ge::1', 0);
-- Tobacco Use Assessment
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_tob_use_assess', 1, 1, 1, 'target_database', 'LIFESTYLE::tobacco::', 0);
-- Tobacco Cessation Intervention
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_tob_cess_inter', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_tob_cess_inter', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_inter::act_tobacco::YES::ge::1', 0);
-- Adult Weight Screening and Follow-Up
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_adult_wt_screen_fu', 1, 1, 1, 'target_database', '::form_vitals::weight::::::ge::1', 0);
-- Weight Assessment and Counseling for Children and Adolescents
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 1, 1, 1, 'target_database', '::form_vitals::weight::::::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 1, 1, 1, 'target_interval', 'year', 3);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 2, 1, 1, 'target_database', 'CUSTOM::act_cat_edu::act_wt::YES::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 2, 1, 1, 'target_interval', 'year', 3);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 3, 1, 1, 'target_database', 'CUSTOM::act_cat_edu::act_nutrition::YES::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 3, 1, 1, 'target_interval', 'year', 3);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 4, 1, 1, 'target_database', 'CUSTOM::act_cat_edu::act_exercise::YES::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 4, 1, 1, 'target_interval', 'year', 3);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 5, 1, 1, 'target_database', '::form_vitals::BMI::::::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_wt_assess_couns_child', 5, 1, 1, 'target_interval', 'year', 3);
-- Influenza Immunization for Patients >= 50 Years Old
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 1, 'target_interval', 'flu_season', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::15::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::16::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::88::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::111::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::125::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::126::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::127::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::128::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::135::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::140::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::141::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_influenza_ge_50', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::144::ge::1', 0);
-- Pneumonia Vaccination Status for Older Adults
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_pneumovacc_ge_65', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::33::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_pneumovacc_ge_65', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::100::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_pneumovacc_ge_65', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::109::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_pneumovacc_ge_65', 1, 1, 0, 'target_database', '::immunizations::cvx_code::eq::133::ge::1', 0);
-- Diabetes: Hemoglobin A1C
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_hemo_a1c', 1, 1, 1, 'target_interval', 'month', 3);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_hemo_a1c', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_measure::act_hemo_a1c::YES::ge::1', 0);
-- Diabetes: Urine Microalbumin
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_urine_alb', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_urine_alb', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_measure::act_urine_alb::YES::ge::1', 0);
-- Diabetes: Eye Exam
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_eye', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_eye', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_exam::act_eye::YES::ge::1', 0);
-- Diabetes: Foot Exam
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_foot', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_dm_foot', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_exam::act_foot::YES::ge::1', 0);
-- Cancer Screening: Mammogram
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_cs_mammo', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_cs_mammo', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_measure::act_mammo::YES::ge::1', 0);
-- Cancer Screening: Pap Smear
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_cs_pap', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_cs_pap', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_exam::act_pap::YES::ge::1', 0);
-- Cancer Screening: Colon Cancer Screening
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_cs_colon', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_assess::act_colon_cancer_screen::YES::ge::1', 0);
-- Cancer Screening: Prostate Cancer Screening
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_cs_prostate', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_assess::act_prostate_cancer_screen::YES::ge::1', 0);
--
-- Rule targets to specifically demonstrate passing of NIST criteria
--
-- Coumadin Management - INR Monitoring
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_inr_monitor', 1, 1, 1, 'target_interval', 'week', 3);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_inr_monitor', 1, 1, 1, 'target_proc', 'INR::CPT4:85610::::::ge::1', 0);
-- Data entry - Social security number.
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_socsec_entry', 1, 1, 1, 'target_database', '::patient_data::ss::::::ge::1', 0);
-- Penicillin allergy assessment.
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_penicillin_allergy', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_penicillin_allergy', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_assess::act_penicillin_allergy::YES::ge::1', 0);
-- Blood Pressure Measurement
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_blood_pressure', 1, 1, 1, 'target_database', '::form_vitals::bps::::::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_blood_pressure', 1, 1, 1, 'target_database', '::form_vitals::bpd::::::ge::1', 0);
-- INR Measurement
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_inr_measure', 1, 1, 1, 'target_proc', 'INR::CPT4:85610::::::ge::1', 0);


--
-- Table structure for table `sequences`
--

DROP TABLE IF EXISTS `sequences`;
CREATE TABLE `sequences` (
  `id` int(11) unsigned NOT NULL default '0'
) ENGINE=InnoDB;

--
-- Dumping data for table `sequences`
--

INSERT INTO `sequences` VALUES (1);


--
-- Table structure for table `supported_external_dataloads`
--

DROP TABLE IF EXISTS `supported_external_dataloads`;
CREATE TABLE `supported_external_dataloads` (
  `load_id` SERIAL,
  `load_type` varchar(24) NOT NULL DEFAULT '',
  `load_source` varchar(24) NOT NULL DEFAULT 'CMS',
  `load_release_date` date NOT NULL,
  `load_filename` varchar(256) NOT NULL DEFAULT '',
  `load_checksum` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB;

--
-- Dumping data for table `supported_external_dataloads`
--

INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES
('ICD10', 'CMS', '2018-10-01', '2019-ICD-10-CM-Code-Descriptions.zip', 'b23e0128eb2dce0cb007c31638a8dc00');
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES
('ICD10', 'CMS', '2018-10-01', '2019-ICD-10-PCS-Order-File.zip', 'eb545fe61ada9efad0ad97a669f8671f');

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id`                      bigint(20)   NOT NULL auto_increment,
  `date`                    datetime     default NULL,
  `title`                   varchar(255) NOT NULL DEFAULT '',
  `pid`                     bigint(20)   default NULL,
  `user`                    varchar(255) NOT NULL DEFAULT '',
  `groupname`               varchar(255) NOT NULL DEFAULT '',
  `authorized`              tinyint(4)   default NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL auto_increment,
  `username` varchar(255) default NULL,
  `password` longtext,
  `authorized` tinyint(4) default NULL,
  `info` longtext,
  `source` tinyint(4) default NULL,
  `fname` varchar(255) default NULL,
  `mname` varchar(255) default NULL,
  `lname` varchar(255) default NULL,
  `federaltaxid` varchar(255) default NULL,
  `federaldrugid` varchar(255) default NULL,
  `upin` varchar(255) default NULL,
  `facility` varchar(255) default NULL,
  `facility_id` int(11) NOT NULL default '0',
  `fullscreen_page` text NOT NULL,
  `fullscreen_enable` int(11) NOT NULL default '0',
  `menu_role` varchar(100) NOT NULL default "Default Role",
  `see_auth` int(11) NOT NULL default '1',
  `active` tinyint(1) NOT NULL default '1',
  `npi` varchar(15) default NULL,
  `title` varchar(30) default NULL,
  `specialty` varchar(255) default NULL,
  `billname` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `email_direct` varchar(255) NOT NULL default '',
  `url` varchar(255) default NULL,
  `assistant` varchar(255) default NULL,
  `organization` varchar(255) default NULL,
  `valedictory` varchar(255) default NULL,
  `street` varchar(60) default NULL,
  `streetb` varchar(60) default NULL,
  `city` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `zip` varchar(20) default NULL,
  `street2` varchar(60) default NULL,
  `streetb2` varchar(60) default NULL,
  `city2` varchar(30) default NULL,
  `state2` varchar(30) default NULL,
  `zip2` varchar(20) default NULL,
  `phone` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `phonew1` varchar(30) default NULL,
  `phonew2` varchar(30) default NULL,
  `phonecell` varchar(30) default NULL,
  `notes` text,
  `cal_ui` tinyint(4) NOT NULL default '1',
  `taxonomy` varchar(30) NOT NULL DEFAULT '207Q00000X',
  `calendar` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = appears in calendar',
  `abook_type` varchar(31) NOT NULL DEFAULT '',
  `pwd_expiration_date` date default NULL,
  `pwd_history1` longtext,
  `pwd_history2` longtext,
  `default_warehouse` varchar(31) NOT NULL DEFAULT '',
  `irnpool` varchar(31) NOT NULL DEFAULT '',
  `state_license_number` VARCHAR(25) DEFAULT NULL,
  `newcrop_user_role` VARCHAR(30) DEFAULT NULL,
  `cpoe` tinyint(1) NULL DEFAULT NULL,
  `physician_type` VARCHAR(50) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `picture_url` varchar(2000) NOT NULL default '',
  `locked` TINYINT(1) NOT NULL DEFAULT '0',
  `login_attempts` INT(2) NOT NULL DEFAULT '0',
  `last_login` timestamp,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP comment 'needed for laravel',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment 'needed for laravel',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--
-- NOTE THIS IS DONE AFTER INSTALLATION WHERE THE sql/official_additional_users.sql script is called durig setup
--  (so these inserts can be found in the sql/official_additional_users.sql script)



--
-- Table structure for table `user_secure`
--
DROP TABLE IF EXISTS `users_secure`;
CREATE TABLE `users_secure` (
  `id` bigint(20) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255),
  `salt` varchar(255),
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `password_history1` varchar(255),
  `salt_history1` varchar(255),
  `password_history2` varchar(255),
  `salt_history2` varchar(255),
  PRIMARY KEY (`id`),
  UNIQUE KEY `USERNAME_ID` (`id`,`username`)
) ENGINE=InnoDB;


--
-- Table structure for table `user_settings`
--
DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE `user_settings` (
  `setting_user`  bigint(20)   NOT NULL DEFAULT 0,
  `setting_label` varchar(63)  NOT NULL,
  `setting_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`setting_user`, `setting_label`)
) ENGINE=InnoDB;

--
-- Dumping data for table `user_settings`
--

INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'allergy_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'appointments_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'billing_ps_expand', '0');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'clinical_reminders_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'demographics_ps_expand', '0');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'dental_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'directives_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'disclosures_ps_expand', '0');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'immunizations_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'insurance_ps_expand', '0');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'medical_problem_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'medication_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'patient_reminders_ps_expand', '0');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'pnotes_ps_expand', '0');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'prescriptions_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'surgery_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'vitals_ps_expand', '1');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (0, 'gacl_protect', '0');
INSERT INTO user_settings ( setting_user, setting_label, setting_value ) VALUES (1, 'gacl_protect', '1');


--
-- Table structure for table `x12_partners`
--

DROP TABLE IF EXISTS `x12_partners`;
CREATE TABLE `x12_partners` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `id_number` varchar(255) default NULL,
  `x12_sender_id` varchar(255) default NULL,
  `x12_receiver_id` varchar(255) default NULL,
  `x12_version` varchar(255) default NULL,
  `processing_format` enum('standard','medi-cal','cms','proxymed') default NULL,
  `x12_isa01` VARCHAR( 2 ) NOT NULL DEFAULT '00' COMMENT 'User logon Required Indicator',
  `x12_isa02` VARCHAR( 10 ) NOT NULL DEFAULT '          ' COMMENT 'User Logon',
  `x12_isa03` VARCHAR( 2 ) NOT NULL DEFAULT '00' COMMENT 'User password required Indicator',
  `x12_isa04` VARCHAR( 10 ) NOT NULL DEFAULT '          ' COMMENT 'User Password',
  `x12_isa05` char(2)     NOT NULL DEFAULT 'ZZ',
  `x12_isa07` char(2)     NOT NULL DEFAULT 'ZZ',
  `x12_isa14` char(1)     NOT NULL DEFAULT '0',
  `x12_isa15` char(1)     NOT NULL DEFAULT 'P',
  `x12_gs02`  varchar(15) NOT NULL DEFAULT '',
  `x12_per06` varchar(80) NOT NULL DEFAULT '',
  `x12_gs03`  varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------------
-- Table structure for table `automatic_notification`
--

DROP TABLE IF EXISTS `automatic_notification`;
CREATE TABLE `automatic_notification` (
  `notification_id` int(5) NOT NULL auto_increment,
  `sms_gateway_type` varchar(255) NOT NULL,
  `next_app_date` date NOT NULL,
  `next_app_time` varchar(10) NOT NULL,
  `provider_name` varchar(100) NOT NULL,
  `message` text,
  `email_sender` varchar(100) NOT NULL,
  `email_subject` varchar(100) NOT NULL,
  `type` enum('SMS','Email') NOT NULL default 'SMS',
  `notification_sent_date` datetime NOT NULL,
  PRIMARY KEY  (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 ;

--
-- Dumping data for table `automatic_notification`
--

INSERT INTO `automatic_notification` (`notification_id`, `sms_gateway_type`, `next_app_date`, `next_app_time`, `provider_name`, `message`, `email_sender`, `email_subject`, `type`, `notification_sent_date`) VALUES (1, 'CLICKATELL', '0000-00-00', ':', 'EMR GROUP 1 .. SMS', 'Welcome to EMR GROUP 1.. SMS', '', '', 'SMS', '0000-00-00 00:00:00'),
(2, '', '2007-10-02', '05:50', 'EMR GROUP', 'Welcome to EMR GROUP . Email', 'EMR Group', 'Welcome to EMR GROUP', 'Email', '2007-09-30 00:00:00');


--
-- Table structure for table `notification_log`
--

DROP TABLE IF EXISTS `notification_log`;
CREATE TABLE `notification_log` (
  `iLogId` int(11) NOT NULL auto_increment,
  `pid` int(7) NOT NULL,
  `pc_eid` int(11) unsigned NULL,
  `sms_gateway_type` varchar(50) NOT NULL,
  `smsgateway_info` varchar(255) NOT NULL,
  `message` text,
  `email_sender` varchar(255) NOT NULL,
  `email_subject` varchar(255) NOT NULL,
  `type` enum('SMS','Email') NOT NULL,
  `patient_info` text,
  `pc_eventDate` date NOT NULL,
  `pc_endDate` date NOT NULL,
  `pc_startTime` time NOT NULL,
  `pc_endTime` time NOT NULL,
  `dSentDateTime` datetime NOT NULL,
  PRIMARY KEY  (`iLogId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 ;


--
-- Table structure for table `notification_settings`
--

DROP TABLE IF EXISTS `notification_settings`;
CREATE TABLE `notification_settings` (
  `SettingsId` int(3) NOT NULL auto_increment,
  `Send_SMS_Before_Hours` int(3) NOT NULL,
  `Send_Email_Before_Hours` int(3) NOT NULL,
  `SMS_gateway_username` varchar(100) NOT NULL,
  `SMS_gateway_password` varchar(100) NOT NULL,
  `SMS_gateway_apikey` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY  (`SettingsId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`SettingsId`, `Send_SMS_Before_Hours`, `Send_Email_Before_Hours`, `SMS_gateway_username`, `SMS_gateway_password`, `SMS_gateway_apikey`, `type`) VALUES (1, 150, 150, 'sms username', 'sms password', 'sms api key', 'SMS/Email Settings');

-- -------------------------------------------------------------------
DROP TABLE IF EXISTS `chart_tracker`;
CREATE TABLE `chart_tracker` (
  `ct_pid`            int(11)       NOT NULL,
  `ct_when`           datetime      NOT NULL,
  `ct_userid`         bigint(20)    NOT NULL DEFAULT 0,
  `ct_location`       varchar(31)   NOT NULL DEFAULT '',
  PRIMARY KEY (ct_pid, ct_when)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ar_session`;
CREATE TABLE `ar_session` (
  `session_id`     int unsigned  NOT NULL AUTO_INCREMENT,
  `payer_id`       int(11)       NOT NULL            COMMENT '0=pt else references insurance_companies.id',
  `user_id`        int(11)       NOT NULL            COMMENT 'references users.id for session owner',
  `closed`         tinyint(1)    NOT NULL DEFAULT 0  COMMENT '0=no, 1=yes',
  `reference`      varchar(255)  NOT NULL DEFAULT '' COMMENT 'check or EOB number',
  `check_date`     date          DEFAULT NULL,
  `deposit_date`   date          DEFAULT NULL,
  `pay_total`      decimal(12,2) NOT NULL DEFAULT 0,
  `created_time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified_time` datetime NOT NULL,
  `global_amount` decimal( 12, 2 ) NOT NULL ,
  `payment_type` varchar( 50 ) NOT NULL ,
  `description` text,
  `adjustment_code` varchar( 50 ) NOT NULL ,
  `post_to_date` date NOT NULL ,
  `patient_id` int( 11 ) NOT NULL ,
  `payment_method` varchar( 25 ) NOT NULL,
  PRIMARY KEY (session_id),
  KEY user_closed (user_id, closed),
  KEY deposit_date (deposit_date)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ar_activity`;
CREATE TABLE `ar_activity` (
  `pid`            int(11)       NOT NULL,
  `encounter`      int(11)       NOT NULL,
  `sequence_no`    int unsigned  NOT NULL AUTO_INCREMENT,
  `billing_id`     INT(11) NOT NULL,
  `code_type`    varchar(12)   NOT NULL DEFAULT '',
  `code`           varchar(20)   NOT NULL            COMMENT 'empty means claim level',
  `modifier`       varchar(12)   NOT NULL DEFAULT '',
  `payer_type`     int           NOT NULL            COMMENT '0=pt, 1=ins1, 2=ins2, etc',
  `post_time`      datetime      NOT NULL,
  `post_user`      int(11)       NOT NULL            COMMENT 'references users.id',
  `session_id`     int unsigned  NOT NULL            COMMENT 'references ar_session.session_id',
  `memo`           varchar(255)  NOT NULL DEFAULT '' COMMENT 'adjustment reasons go here',
  `pay_amount`     decimal(12,2) NOT NULL DEFAULT 0  COMMENT 'either pay or adj will always be 0',
  `adj_amount`     decimal(12,2) NOT NULL DEFAULT 0,
  `modified_time` datetime NOT NULL,
  `follow_up` char(1) NOT NULL,
  `follow_up_note` text,
  `account_code` varchar(15) NOT NULL,
  `reason_code` varchar(255) DEFAULT NULL COMMENT 'Use as needed to show the primary payer adjustment reason code',
  `unapplied`      TINYINT(1) NOT NULL DEFAULT '0',
  `date_closed`    date COMMENT 'Date closed',
  `ready_to_bill` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (sequence_no, pid, encounter),
  KEY session_id (session_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `users_facility`;
CREATE TABLE `users_facility` (
  `tablename` varchar(64) NOT NULL,
  `table_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  PRIMARY KEY (`tablename`,`table_id`,`facility_id`)
) ENGINE=InnoDB COMMENT='joins users or patient_data to facility table';

DROP TABLE IF EXISTS `lbf_data`;
CREATE TABLE `lbf_data` (
  `form_id`     int(11)      NOT NULL AUTO_INCREMENT COMMENT 'references forms.form_id',
  `field_id`    varchar(31)  NOT NULL COMMENT 'references layout_options.field_id',
  `field_value` TEXT,
  PRIMARY KEY (`form_id`,`field_id`)
) ENGINE=InnoDB COMMENT='contains all data from layout-based forms';

DROP TABLE IF EXISTS `lbt_data`;
CREATE TABLE `lbt_data` (
  `form_id`     bigint(20)   NOT NULL COMMENT 'references transactions.id',
  `field_id`    varchar(31)  NOT NULL COMMENT 'references layout_options.field_id',
  `field_value` TEXT,
  PRIMARY KEY (`form_id`,`field_id`)
) ENGINE=InnoDB COMMENT='contains all data from layout-based transactions';

DROP TABLE IF EXISTS `gprelations`;
CREATE TABLE `gprelations` (
  `type1` int(2)     NOT NULL,
  `id1`   bigint(20) NOT NULL,
  `type2` int(2)     NOT NULL,
  `id2`   bigint(20) NOT NULL,
  PRIMARY KEY (type1,id1,type2,id2),
  KEY key2  (type2,id2)
) ENGINE=InnoDB COMMENT='general purpose relations';

DROP TABLE IF EXISTS `procedure_providers`;
CREATE TABLE `procedure_providers` (
  `ppid`         bigint(20)   NOT NULL auto_increment,
  `name`         varchar(255) NOT NULL DEFAULT '',
  `npi`          varchar(15)  NOT NULL DEFAULT '',
  `send_app_id`  varchar(255) NOT NULL DEFAULT ''  COMMENT 'Sending application ID (MSH-3.1)',
  `send_fac_id`  varchar(255) NOT NULL DEFAULT ''  COMMENT 'Sending facility ID (MSH-4.1)',
  `recv_app_id`  varchar(255) NOT NULL DEFAULT ''  COMMENT 'Receiving application ID (MSH-5.1)',
  `recv_fac_id`  varchar(255) NOT NULL DEFAULT ''  COMMENT 'Receiving facility ID (MSH-6.1)',
  `DorP`         char(1)      NOT NULL DEFAULT 'D' COMMENT 'Debugging or Production (MSH-11)',
  `direction`    char(1)      NOT NULL DEFAULT 'B' COMMENT 'Bidirectional or Results-only',
  `protocol`     varchar(15)  NOT NULL DEFAULT 'DL',
  `remote_host`  varchar(255) NOT NULL DEFAULT '',
  `login`        varchar(255) NOT NULL DEFAULT '',
  `password`     varchar(255) NOT NULL DEFAULT '',
  `orders_path`  varchar(255) NOT NULL DEFAULT '',
  `results_path` varchar(255) NOT NULL DEFAULT '',
  `notes`        text,
  `lab_director` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ppid`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `procedure_type`;
CREATE TABLE `procedure_type` (
  `procedure_type_id`   bigint(20)   NOT NULL AUTO_INCREMENT,
  `parent`              bigint(20)   NOT NULL DEFAULT 0  COMMENT 'references procedure_type.procedure_type_id',
  `name`                varchar(63)  NOT NULL DEFAULT '' COMMENT 'name for this category, procedure or result type',
  `lab_id`              bigint(20)   NOT NULL DEFAULT 0  COMMENT 'references procedure_providers.ppid, 0 means default to parent',
  `procedure_code`      varchar(31)  NOT NULL DEFAULT '' COMMENT 'code identifying this procedure',
  `procedure_type`      varchar(31)  NOT NULL DEFAULT '' COMMENT 'see list proc_type',
  `body_site`           varchar(31)  NOT NULL DEFAULT '' COMMENT 'where to do injection, e.g. arm, buttok',
  `specimen`            varchar(31)  NOT NULL DEFAULT '' COMMENT 'blood, urine, saliva, etc.',
  `route_admin`         varchar(31)  NOT NULL DEFAULT '' COMMENT 'oral, injection',
  `laterality`          varchar(31)  NOT NULL DEFAULT '' COMMENT 'left, right, ...',
  `description`         varchar(255) NOT NULL DEFAULT '' COMMENT 'descriptive text for procedure_code',
  `standard_code`       varchar(255) NOT NULL DEFAULT '' COMMENT 'industry standard code type and code (e.g. CPT4:12345)',
  `related_code`        varchar(255) NOT NULL DEFAULT '' COMMENT 'suggested code(s) for followup services if result is abnormal',
  `units`               varchar(31)  NOT NULL DEFAULT '' COMMENT 'default for procedure_result.units',
  `range`               varchar(255) NOT NULL DEFAULT '' COMMENT 'default for procedure_result.range',
  `seq`                 int(11)      NOT NULL default 0  COMMENT 'sequence number for ordering',
  `activity`            tinyint(1)   NOT NULL default 1  COMMENT '1=active, 0=inactive',
  `notes`               varchar(255) NOT NULL default '' COMMENT 'additional notes to enhance description',
  PRIMARY KEY (`procedure_type_id`),
  KEY parent (parent)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `procedure_questions`;
CREATE TABLE `procedure_questions` (
  `lab_id`              bigint(20)   NOT NULL DEFAULT 0   COMMENT 'references procedure_providers.ppid to identify the lab',
  `procedure_code`      varchar(31)  NOT NULL DEFAULT ''  COMMENT 'references procedure_type.procedure_code to identify this order type',
  `question_code`       varchar(31)  NOT NULL DEFAULT ''  COMMENT 'code identifying this question',
  `seq`                 int(11)      NOT NULL default 0   COMMENT 'sequence number for ordering',
  `question_text`       varchar(255) NOT NULL DEFAULT ''  COMMENT 'descriptive text for question_code',
  `required`            tinyint(1)   NOT NULL DEFAULT 0   COMMENT '1 = required, 0 = not',
  `maxsize`             int          NOT NULL DEFAULT 0   COMMENT 'maximum length if text input field',
  `fldtype`             char(1)      NOT NULL DEFAULT 'T' COMMENT 'Text, Number, Select, Multiselect, Date, Gestational-age',
  `options`             text                              COMMENT 'choices for fldtype S and T',
  `tips`                varchar(255) NOT NULL DEFAULT ''  COMMENT 'Additional instructions for answering the question',
  `activity`            tinyint(1)   NOT NULL DEFAULT 1   COMMENT '1 = active, 0 = inactive',
  PRIMARY KEY (`lab_id`, `procedure_code`, `question_code`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `procedure_order`;
CREATE TABLE `procedure_order` (
  `procedure_order_id`     bigint(20)   NOT NULL AUTO_INCREMENT,
  `provider_id`            bigint(20)   NOT NULL DEFAULT 0  COMMENT 'references users.id, the ordering provider',
  `patient_id`             bigint(20)   NOT NULL            COMMENT 'references patient_data.pid',
  `encounter_id`           bigint(20)   NOT NULL DEFAULT 0  COMMENT 'references form_encounter.encounter',
  `date_collected`         datetime     DEFAULT NULL        COMMENT 'time specimen collected',
  `date_ordered`           date         DEFAULT NULL,
  `order_priority`         varchar(31)  NOT NULL DEFAULT '',
  `order_status`           varchar(31)  NOT NULL DEFAULT '' COMMENT 'pending,routed,complete,canceled',
  `patient_instructions`   text,
  `activity`               tinyint(1)   NOT NULL DEFAULT 1  COMMENT '0 if deleted',
  `control_id`             varchar(255) NOT NULL DEFAULT '' COMMENT 'This is the CONTROL ID that is sent back from lab',
  `lab_id`                 bigint(20)   NOT NULL DEFAULT 0  COMMENT 'references procedure_providers.ppid',
  `specimen_type`          varchar(31)  NOT NULL DEFAULT '' COMMENT 'from the Specimen_Type list',
  `specimen_location`      varchar(31)  NOT NULL DEFAULT '' COMMENT 'from the Specimen_Location list',
  `specimen_volume`        varchar(30)  NOT NULL DEFAULT '' COMMENT 'from a text input field',
  `date_transmitted`       datetime     DEFAULT NULL        COMMENT 'time of order transmission, null if unsent',
  `clinical_hx`            varchar(255) NOT NULL DEFAULT '' COMMENT 'clinical history text that may be relevant to the order',
  `external_id` VARCHAR(20) DEFAULT NULL,
  `history_order` enum('0','1') DEFAULT '0' COMMENT 'references order is added for history purpose only.',
  PRIMARY KEY (`procedure_order_id`),
  KEY datepid (date_ordered, patient_id),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `procedure_order_code`;
CREATE TABLE `procedure_order_code` (
  `procedure_order_id`  bigint(20)  NOT NULL                COMMENT 'references procedure_order.procedure_order_id',
  `procedure_order_seq` int(11)     NOT NULL AUTO_INCREMENT COMMENT 'supports multiple tests per order',
  `procedure_code`      varchar(31) NOT NULL DEFAULT ''     COMMENT 'like procedure_type.procedure_code',
  `procedure_name`      varchar(255) NOT NULL DEFAULT ''    COMMENT 'descriptive name of the procedure code',
  `procedure_source`    char(1)     NOT NULL DEFAULT '1'    COMMENT '1=original order, 2=added after order sent',
  `diagnoses`           text                                COMMENT 'diagnoses and maybe other coding (e.g. ICD9:111.11)',
  `do_not_send`         tinyint(1)  NOT NULL DEFAULT '0'    COMMENT '0 = normal, 1 = do not transmit to lab',
  `procedure_order_title` varchar( 255 ) NULL DEFAULT NULL,
  PRIMARY KEY (`procedure_order_seq`,`procedure_order_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `procedure_answers`;
CREATE TABLE `procedure_answers` (
  `procedure_order_id`  bigint(20)   NOT NULL DEFAULT 0  COMMENT 'references procedure_order.procedure_order_id',
  `procedure_order_seq` int(11)      NOT NULL DEFAULT 0  COMMENT 'references procedure_order_code.procedure_order_seq',
  `question_code`       varchar(31)  NOT NULL DEFAULT '' COMMENT 'references procedure_questions.question_code',
  `answer_seq`          int(11)      NOT NULL AUTO_INCREMENT COMMENT 'supports multiple-choice questions',
  `answer`              varchar(255) NOT NULL DEFAULT '' COMMENT 'answer data',
  PRIMARY KEY (`answer_seq`,`procedure_order_id`, `procedure_order_seq`, `question_code`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `procedure_report`;
CREATE TABLE `procedure_report` (
  `procedure_report_id` bigint(20)     NOT NULL AUTO_INCREMENT,
  `procedure_order_id`  bigint(20)     DEFAULT NULL   COMMENT 'references procedure_order.procedure_order_id',
  `procedure_order_seq` int(11)        NOT NULL DEFAULT 1  COMMENT 'references procedure_order_code.procedure_order_seq',
  `date_collected`      datetime       DEFAULT NULL,
  `date_collected_tz`   varchar(5)     DEFAULT ''          COMMENT '+-hhmm offset from UTC',
  `date_report`         datetime       DEFAULT NULL,
  `date_report_tz`      varchar(5)     DEFAULT ''          COMMENT '+-hhmm offset from UTC',
  `source`              bigint(20)     NOT NULL DEFAULT 0  COMMENT 'references users.id, who entered this data',
  `specimen_num`        varchar(63)    NOT NULL DEFAULT '',
  `report_status`       varchar(31)    NOT NULL DEFAULT '' COMMENT 'received,complete,error',
  `review_status`       varchar(31)    NOT NULL DEFAULT 'received' COMMENT 'pending review status: received,reviewed',
  `report_notes`        text           COMMENT 'notes from the lab',
  PRIMARY KEY (`procedure_report_id`),
  KEY procedure_order_id (procedure_order_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `procedure_result`;
CREATE TABLE `procedure_result` (
  `procedure_result_id` bigint(20)   NOT NULL AUTO_INCREMENT,
  `procedure_report_id` bigint(20)   NOT NULL            COMMENT 'references procedure_report.procedure_report_id',
  `result_data_type`    char(1)      NOT NULL DEFAULT 'S' COMMENT 'N=Numeric, S=String, F=Formatted, E=External, L=Long text as first line of comments',
  `result_code`         varchar(31)  NOT NULL DEFAULT '' COMMENT 'LOINC code, might match a procedure_type.procedure_code',
  `result_text`         varchar(255) NOT NULL DEFAULT '' COMMENT 'Description of result_code',
  `date`                datetime     DEFAULT NULL        COMMENT 'lab-provided date specific to this result',
  `facility`            varchar(255) NOT NULL DEFAULT '' COMMENT 'lab-provided testing facility ID',
  `units`               varchar(31)  NOT NULL DEFAULT '',
  `result`              varchar(255) NOT NULL DEFAULT '',
  `range`               varchar(255) NOT NULL DEFAULT '',
  `abnormal`            varchar(31)  NOT NULL DEFAULT '' COMMENT 'no,yes,high,low',
  `comments`            text                             COMMENT 'comments from the lab',
  `document_id`         bigint(20)   NOT NULL DEFAULT 0  COMMENT 'references documents.id if this result is a document',
  `result_status`       varchar(31)  NOT NULL DEFAULT '' COMMENT 'preliminary, cannot be done, final, corrected, incompete...etc.',
  PRIMARY KEY (`procedure_result_id`),
  KEY procedure_report_id (procedure_report_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `globals`;
CREATE TABLE `globals` (
  `gl_name`             varchar(63)    NOT NULL,
  `gl_index`            int(11)        NOT NULL DEFAULT 0,
  `gl_value`            varchar(255)   NOT NULL DEFAULT '',
  PRIMARY KEY (`gl_name`, `gl_index`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `code_types`;
CREATE TABLE `code_types` (
  `ct_key`      varchar(15) NOT NULL           COMMENT 'short alphanumeric name',
  `ct_id`       int(11)     UNIQUE NOT NULL    COMMENT 'numeric identifier',
  `ct_seq`      int(11)     NOT NULL DEFAULT 0 COMMENT 'sort order',
  `ct_mod`      int(11)     NOT NULL DEFAULT 0 COMMENT 'length of modifier field',
  `ct_just`     varchar(15) NOT NULL DEFAULT ''COMMENT 'ct_key of justify type, if any',
  `ct_mask`     varchar(9)  NOT NULL DEFAULT ''COMMENT 'formatting mask for code values',
  `ct_fee`      tinyint(1)  NOT NULL default 0 COMMENT '1 if fees are used',
  `ct_rel`      tinyint(1)  NOT NULL default 0 COMMENT '1 if can relate to other code types',
  `ct_nofs`     tinyint(1)  NOT NULL default 0 COMMENT '1 if to be hidden in the fee sheet',
  `ct_diag`     tinyint(1)  NOT NULL default 0 COMMENT '1 if this is a diagnosis type',
  `ct_active`   tinyint(1)  NOT NULL default 1 COMMENT '1 if this is active',
  `ct_label`    varchar(31) NOT NULL default '' COMMENT 'label of this code type',
  `ct_external` tinyint(1)  NOT NULL default 0 COMMENT '0 if stored codes in codes tables, 1 or greater if codes stored in external tables',
  `ct_claim`    tinyint(1)  NOT NULL default 0 COMMENT '1 if this is used in claims',
  `ct_proc`     tinyint(1)  NOT NULL default 0 COMMENT '1 if this is a procedure type',
  `ct_term`     tinyint(1)  NOT NULL default 0 COMMENT '1 if this is a clinical term',
  `ct_problem`  tinyint(1)  NOT NULL default 0 COMMENT '1 if this code type is used as a medical problem',
  `ct_drug`     tinyint(1)  NOT NULL default 0 COMMENT '1 if this code type is used as a medication',
  PRIMARY KEY (ct_key)
) ENGINE=InnoDB;

INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag, ct_active, ct_label, ct_external, ct_claim, ct_proc, ct_term, ct_problem ) VALUES 
('CPT4' , 1, 2, 12, 'ICD10', 1, 0, 0, 0, 1, 'CPT4 Procedure/Service', 0, 1, 1, 0, 0),
('HCPCS', 3, 3, 12, 'ICD10', 1, 0, 0, 0, 1, 'HCPCS Procedure/Service', 0, 1, 1, 0, 0),
('CVX'  , 100, 100, 0, '', 0, 0, 1, 0, 1, 'CVX Immunization', 0, 0, 0, 0, 0),
('ICD10' , 102, 102, 0, '', 0, 0, 0, 1, 1, 'ICD10 Diagnosis', 1, 1, 0, 0, 1),
('SNOMED' , 103, 103, 0, '', 0, 0, 0, 1, 0, 'SNOMED Diagnosis', 2, 1, 0, 0, 1),
('CPT2' , 104, 104, 0, '', 0, 0, 0, 0, 0, 'CPT2 Performance Measures', 0, 1, 0, 0, 0),
('ICD10-PCS' , 106, 106, 12, 'ICD10', 1, 0, 0, 0, 0, 'ICD10 Procedure/Service', 6, 1, 1, 0, 0),
('SNOMED-CT' , 107, 107, 0, '', 0, 0, 1, 0, 0, 'SNOMED Clinical Term', 7, 0, 0, 1, 0),
('SNOMED-PR' , 108, 108, 0, 'SNOMED', 1, 0, 0, 0, 0, 'SNOMED Procedure', 9, 1, 1, 0, 0),
('LOINC', 110, 110, 0, '', 0, 0, 1, 0, 1, 'LOINC', 0, 0, 0, 0, 0),
('PHIN Questions', 111, 111, 0, '', 0, 0, 1, 0, 1, 'PHIN Questions', 0, 0, 0, 0, 0);
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag, ct_active, ct_label, ct_external, ct_claim, ct_proc, ct_term, ct_problem, ct_drug ) VALUES ('RXCUI', 109, 109, 0, '', 0, 0, 1, 0, 0, 'RXCUI Medication', 0, 0, 0, 0, 0, 1);--
-- Table structure for table `extended_log`
--

DROP TABLE IF EXISTS `extended_log`;
CREATE TABLE `extended_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime default NULL,
  `event` varchar(255) default NULL,
  `user` varchar(255) default NULL,
  `recipient` varchar(255) default NULL,
  `description` longtext,
  `patient_id` bigint(20) default NULL,
  PRIMARY KEY  (`id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `v_major`     int(11)     NOT NULL DEFAULT 0,
  `v_minor`     int(11)     NOT NULL DEFAULT 0,
  `v_patch`     int(11)     NOT NULL DEFAULT 0,
  `v_realpatch` int(11)     NOT NULL DEFAULT 0,
  `v_tag`       varchar(31) NOT NULL DEFAULT '',
  `v_database`  int(11)     NOT NULL DEFAULT 0,
  `v_acl`       int(11)     NOT NULL DEFAULT 0
) ENGINE=InnoDB;
INSERT INTO version (v_major, v_minor, v_patch, v_realpatch, v_tag, v_database, v_acl) VALUES (0, 0, 0, 0, '', 0, 0);


DROP TABLE IF EXISTS `product_warehouse`;
CREATE TABLE `product_warehouse` (
  `pw_drug_id`   int(11) NOT NULL,
  `pw_warehouse` varchar(31) NOT NULL,
  `pw_min_level` float       DEFAULT 0,
  `pw_max_level` float       DEFAULT 0,
  PRIMARY KEY  (`pw_drug_id`,`pw_warehouse`)
) ENGINE=InnoDB;

--
-- Table structure for table `misc_address_book`
--

DROP TABLE IF EXISTS `misc_address_book`;
CREATE TABLE `misc_address_book` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `street` varchar(60) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


--
-- Table structure for table `esign_signatures`
--

DROP TABLE IF EXISTS `esign_signatures`;
CREATE TABLE `esign_signatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT 'Table row ID for signature',
  `table` varchar(255) NOT NULL COMMENT 'table name for the signature',
  `uid` int(11) NOT NULL COMMENT 'user id for the signing user',
  `datetime` datetime NOT NULL COMMENT 'datetime of the signature action',
  `is_lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sig, lock or amendment',
  `amendment` text COMMENT 'amendment text, if any',
  `hash` varchar(255) NOT NULL COMMENT 'hash of signed data',
  `signature_hash` varchar(255) NOT NULL COMMENT 'hash of signature itself',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `table` (`table`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `log_comment_encrypt`
--

DROP TABLE IF EXISTS `log_comment_encrypt`;
CREATE TABLE IF NOT EXISTS `log_comment_encrypt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `encrypt` enum('Yes','No') NOT NULL DEFAULT 'No',
  `checksum` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `shared_attributes`;
CREATE TABLE `shared_attributes` (
  `pid`          bigint(20)   NOT NULL,
  `encounter`    bigint(20)   NOT NULL COMMENT '0 if patient attribute, else encounter attribute',
  `field_id`     varchar(31)  NOT NULL COMMENT 'references layout_options.field_id',
  `last_update`  datetime     NOT NULL COMMENT 'time of last update',
  `user_id`      bigint(20)   NOT NULL COMMENT 'user who last updated',
  `field_value`  TEXT,
  PRIMARY KEY (`pid`, `encounter`, `field_id`)
);

--
-- Table structure for table `ccda_components`
--
DROP TABLE IF EXISTS `ccda_components`;
CREATE TABLE `ccda_components` (
  `ccda_components_id` int(11) NOT NULL AUTO_INCREMENT,
  `ccda_components_field` varchar(100) DEFAULT NULL,
  `ccda_components_name` varchar(100) DEFAULT NULL,
  `ccda_type` int(11) NOT NULL COMMENT '0=>sections,1=>components',
  PRIMARY KEY (ccda_components_id)
) ENGINE=InnoDB AUTO_INCREMENT=23 ;
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name, ccda_type) values 
('1','progress_note','Progress Notes',0),
('2','consultation_note','Consultation Note',0),
('3','continuity_care_document','Continuity Care Document',0),
('4','diagnostic_image_reporting','Diagnostic Image Reporting',0),
('5','discharge_summary','Discharge Summary',0),
('6','history_physical_note','History and Physical Note',0),
('7','operative_note','Operative Note',0),
('8','procedure_note','Procedure Note',0),
('9','unstructured_document','Unstructured Document',0),
('10','allergies','Allergies',1),
('11','medications','Medications',1),
('12','problems','Problems',1),
('13','immunizations','Immunizations',1),
('14','procedures','Procedures',1),
('15','results','Results',1),
('16','plan_of_care','Plan Of Care',1),
('17','vitals','Vitals',1),
('18','social_history','Social History',1),
('19','encounters','Encounters',1),
('20','functional_status','Functional Status',1),
('21','referral','Reason for Referral',1),
('22','instructions','Instructions',1);

-- --------------------------------------------------------

--
-- Table structure for table `ccda_sections`
--

CREATE TABLE `ccda_sections` (
  `ccda_sections_id` int(11) NOT NULL,
  `ccda_components_id` int(11) DEFAULT NULL,
  `ccda_sections_field` varchar(100) DEFAULT NULL,
  `ccda_sections_name` varchar(100) DEFAULT NULL,
  `ccda_sections_req_mapping` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ccda_sections`
--

INSERT INTO `ccda_sections` (`ccda_sections_id`, `ccda_components_id`, `ccda_sections_field`, `ccda_sections_name`, `ccda_sections_req_mapping`) VALUES
(1, 1, 'assessment_plan', 'Assessment and Plan', 1),
(2, 2, 'assessment_plan', 'Assessment and Plan', 1),
(3, 2, 'history_of_present_illness', 'History of Present Illness', 1),
(4, 2, 'physical_exam', 'Physical Exam', 1),
(5, 2, 'reason_of_visit', 'Reason for Referral/Reason for Visit', 1),
(6, 3, 'allergies', 'Allergies', 0),
(7, 3, 'medications', 'Medications', 0),
(8, 3, 'problem_list', 'Problem List', 0),
(9, 3, 'procedures', 'Procedures', 0),
(10, 3, 'results', 'Results', 0),
(11, 4, 'report', 'Report', 0),
(12, 5, 'allergies', 'Allergies', 0),
(13, 5, 'hospital_course', 'Hospital Course', 0),
(14, 5, 'hospital_discharge_diagnosis', 'Hospital Discharge Diagnosis', 0),
(15, 5, 'hospital_discharge_medications', 'Hospital Discharge Medications', 0),
(16, 5, 'plan_of_care', 'Plan of Care', 1),
(17, 6, 'allergies', 'Allergies', 0),
(19, 6, 'chief_complaint', 'Chief Complaint / Reason for Visit', 1),
(21, 6, 'family_history', 'Family History', 1),
(22, 6, 'general_status', 'General Status', 1),
(23, 6, 'hpi_past_med', 'History of Past Illness (Past Medical History)', 1),
(24, 6, 'hpi', 'History of Present Illness', 1),
(25, 6, 'medications', 'Medications', 0),
(26, 6, 'physical_exam', 'Physical Exam', 1),
(28, 6, 'results', 'Results', 0),
(29, 6, 'review_of_systems', 'Review of Systems', 1),
(30, 6, 'social_history', 'Social History', 1),
(31, 6, 'vital_signs', 'Vital Signs', 0),
(32, 7, 'anesthesia', 'Anesthesia', 1),
(33, 7, 'complications', 'Complications', 1),
(34, 7, 'post_operative_diagnosis', 'Post Operative Diagnosis', 0),
(35, 7, 'pre_operative_diagnosis', 'Pre Operative Diagnosis', 0),
(36, 7, 'procedure_estimated_blood_loss', 'Procedure Estimated Blood Loss', 0),
(37, 7, 'procedure_findings', 'Procedure Findings', 0),
(38, 7, 'procedure_specimens_taken', 'Procedure Specimens Taken', 0),
(39, 7, 'procedure_description', 'Procedure Description', 1),
(40, 8, 'assessment_plan', 'Assessment and Plan', 1),
(41, 8, 'complications', 'Complications', 1),
(42, 8, 'postprocedure_diagnosis', 'Postprocedure Diagnosis', 0),
(43, 8, 'procedure_description', 'Procedure Description', 0),
(44, 8, 'procedure_indications', 'Procedure Indications', 0),
(45, 9, 'unstructured_doc', 'Document', 0);

--
-- Indexes for table `ccda_sections`
--
ALTER TABLE `ccda_sections`
  ADD PRIMARY KEY (`ccda_sections_id`);

--
-- AUTO_INCREMENT for table `ccda_sections`
--
ALTER TABLE `ccda_sections`
  MODIFY `ccda_sections_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- Table structure for table `ccda_field_mapping`
--
DROP TABLE IF EXISTS `ccda_field_mapping`;
CREATE TABLE `ccda_field_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) DEFAULT NULL,
  `ccda_field` varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `ccda`
--
DROP TABLE IF EXISTS `ccda`;
CREATE TABLE `ccda` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pid` BIGINT(20) DEFAULT NULL,
  `encounter` BIGINT(20) DEFAULT NULL,
  `ccda_data` MEDIUMTEXT,
  `time` VARCHAR(50) DEFAULT NULL,
  `status` SMALLINT(6) DEFAULT NULL,
  `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` VARCHAR(50) null,
  `couch_docid` VARCHAR(100) NULL,
  `couch_revid` VARCHAR(100) NULL,
  `view` tinyint(4) NOT NULL DEFAULT '0',
  `transfer` tinyint(4) NOT NULL DEFAULT '0',
  `emr_transfer` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY unique_key (pid,encounter,time)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `ccda_table_mapping`
--
DROP TABLE IF EXISTS `ccda_table_mapping`;
CREATE TABLE `ccda_table_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ccda_component` varchar(100) DEFAULT NULL,
  `ccda_component_section` varchar(100) DEFAULT NULL,
  `form_dir` varchar(100) DEFAULT NULL,
  `form_type` smallint(6) DEFAULT NULL,
  `form_table` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;

--
-- Table structure for table `external_procedures`
--
DROP TABLE IF EXISTS `external_procedures`;
CREATE TABLE `external_procedures` (
  `ep_id` int(11) NOT NULL AUTO_INCREMENT,
  `ep_date` date DEFAULT NULL,
  `ep_code_type` varchar(20) DEFAULT NULL,
  `ep_code` varchar(9) DEFAULT NULL,
  `ep_pid` int(11) DEFAULT NULL,
  `ep_encounter` int(11) DEFAULT NULL,
  `ep_code_text` longtext,
  `ep_facility_id` varchar(255) DEFAULT NULL,
  `ep_external_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ep_id`)
) ENGINE=InnoDB;

--
-- Table structure for table `external_encounters`
--
DROP TABLE IF EXISTS `external_encounters`;
CREATE TABLE `external_encounters` (
  `ee_id` int(11) NOT NULL AUTO_INCREMENT,
  `ee_date` date DEFAULT NULL,
  `ee_pid` int(11) DEFAULT NULL,
  `ee_provider_id` varchar(255) DEFAULT NULL,
  `ee_facility_id` varchar(255) DEFAULT NULL,
  `ee_encounter_diagnosis` varchar(255) DEFAULT NULL,
  `ee_external_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ee_id`)
) ENGINE=InnoDB;

--
-- Table structure for table `form_care_plan`
--
DROP TABLE IF EXISTS `form_care_plan`;
CREATE TABLE `form_care_plan` (
  `id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `codetext` text,
  `description` text,
  `external_id` varchar(30) DEFAULT NULL
) ENGINE=InnoDB;

--
-- Table structure for table `form_functional_cognitive_status`
--
DROP TABLE IF EXISTS `form_functional_cognitive_status`;
CREATE TABLE `form_functional_cognitive_status` (
  `id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `codetext` text,
  `description` text,
  `external_id` varchar(30) DEFAULT NULL
) ENGINE=InnoDB;

--
-- Table structure for table `form_observation`
--
DROP TABLE IF EXISTS `form_observation`;
CREATE TABLE `form_observation` (
  `id` bigint(20) NOT NULL,
  `date` DATE DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `ob_value` varchar(255),
  `ob_unit` varchar(255),
  `description` varchar(255),
  `code_type` varchar(255),
  `table_code` varchar(255)
) ENGINE=InnoDB;

--
-- Table structure for table `form_clinical_instructions`
--
DROP TABLE IF EXISTS `form_clinical_instructions`;
CREATE TABLE `form_clinical_instructions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `instruction` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activity` TINYINT DEFAULT 1 NULL,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;
--
-- Table structure for table `updater_users`
--
DROP TABLE IF EXISTS `updater_users`;
CREATE TABLE `updater_users` (
  `authUserId` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB;


--
-- Table structure for table `updater_settings`
--
DROP TABLE IF EXISTS `updater_settings`;
CREATE TABLE `updater_settings` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB;


--
-- Table structure for table `updater_user_mode_download_entry`
--
DROP TABLE IF EXISTS `updater_user_mode_download_entry`;
CREATE TABLE `updater_user_mode_download_entry` (
  `filename` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `old_name` varchar(255) NOT NULL
) ENGINE=InnoDB;

--
-- Table structure for table `updater_user_mode_backup_entry`
--
DROP TABLE IF EXISTS `updater_user_mode_backup_entry`;
CREATE TABLE `updater_user_mode_backup_entry` (
  `filename` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `old_name` varchar(255) NOT NULL
) ENGINE=InnoDB;

--
-- Table structure for table `lims_analysisrequests`
--

CREATE TABLE IF NOT EXISTS `lims_analysisrequests` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `procedure_order_id` int(11) NOT NULL COMMENT 'references procedure_order.procedure_order_id ',
 `analysisrequest_id` varchar(80) NOT NULL COMMENT 'refers to analysis request id in the lims',
 `status` text NOT NULL COMMENT 'received, processing, complete',
 PRIMARY KEY (`id`),
 UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `cases_to_documents`
--

DROP TABLE IF EXISTS cases_to_documents;
 
 CREATE TABLE IF NOT EXISTS `cases_to_documents` (
 `case_id` int(11) NOT NULL DEFAULT '0',
 `document_id` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`case_id`,`document_id`),
 KEY `FK_categories_to_documents_documents` (`document_id`),
 CONSTRAINT `cases_to_documents_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#IfNotTable transactions_log

CREATE TABLE IF NOT EXISTS `transactions_log` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date` datetime NOT NULL,
 `description` varchar(255) NOT NULL COMMENT 'Ex: Charges added to superbill',
 `encounter` int(11) NOT NULL,
 `change_made` varchar(255) NOT NULL COMMENT 'the change from one payment amount to another. ex: $10 to $20',
 `billing_id` int(11) NOT NULL,
 `pid` int(11) NOT NULL,
 `user_id` int(11) NOT NULL COMMENT 'authorized user who effects the change',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
#EndIf

