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

#IfTable openemr_module_vars
RENAME TABLE `openemr_module_vars` TO `libreehr_module_vars`;
#EndIf

#IfTable openemr_modules
RENAME TABLE `openemr_modules` TO `libreehr_modules`;
#EndIf

#IfTable openemr_postcalendar_categories
RENAME TABLE `openemr_postcalendar_categories` TO `libreehr_postcalendar_categories`;
#EndIf

#IfTable openemr_postcalendar_events
RENAME TABLE `openemr_postcalendar_events` TO `libreehr_postcalendar_events`;
#EndIf

#IfTable openemr_postcalendar_limits
RENAME TABLE `openemr_postcalendar_limits` TO `libreehr_postcalendar_limits`;
#EndIf

#IfTable openemr_postcalendar_topics
RENAME TABLE `openemr_postcalendar_topics` TO `libreehr_postcalendar_topics`;
#EndIf

#IfTable openemr_session_info
RENAME TABLE `openemr_session_info` TO `libreehr_session_info`;
#EndIf

#IfTable openemr_module_vars
RENAME TABLE `openemr_module_vars` TO `libreehr_module_vars`;
#EndIf

#IfNotIndex form_encounter encounter_date
    CREATE INDEX encounter_date on form_encounter (`date`);
#EndIf

#IfNotColumnType prescriptions size varchar(16)
ALTER TABLE `prescriptions` CHANGE `size` `size` varchar(16) DEFAULT NULL;
#EndIf

#IfNotRow globals gl_name erx_newcrop_path
UPDATE `globals` SET `gl_name` = 'erx_newcrop_path' WHERE `gl_name` = 'erx_path_production';
#EndIf

#IfNotRow globals gl_name erx_newcrop_path_soap
UPDATE `globals` SET `gl_name` = 'erx_newcrop_path_soap' WHERE `gl_name` = 'erx_path_soap_production';
#EndIf

#IfNotRow globals gl_name erx_account_partner_name
UPDATE `globals` SET `gl_name` = 'erx_account_partner_name' WHERE `gl_name` = 'partner_name_production';
#EndIf

#IfNotRow globals gl_name erx_account_name
UPDATE `globals` SET `gl_name` = 'erx_account_name' WHERE `gl_name` = 'erx_name_production';
#EndIf

#IfNotRow globals gl_name erx_account_password
UPDATE `globals` SET `gl_name` = 'erx_account_password' WHERE `gl_name` = 'erx_password_production';
#EndIf

#IfNotColumnType lang_custom constant_name mediumtext
ALTER TABLE `lang_custom` CHANGE `constant_name` `constant_name` mediumtext NOT NULL default '';
#EndIf

#IfNotTable patient_tracker
CREATE TABLE `patient_tracker` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1;
#EndIf

#IfNotTable patient_tracker_element
CREATE TABLE `patient_tracker_element` (
  `pt_tracker_id`      bigint(20)   NOT NULL default '0' COMMENT 'maps to id column in patient_tracker table',
  `start_datetime`     datetime     DEFAULT NULL,
  `room`               varchar(20)  NOT NULL default '',
  `status`             varchar(31)  NOT NULL default '',
  `seq`                varchar(4)   NOT NULL default '' COMMENT 'This is a numerical sequence for this pt_tracker_id events',
  `user`               varchar(255) NOT NULL default '' COMMENT 'This is the user that created this element',
  KEY  (`pt_tracker_id`,`seq`)
) ENGINE=MyISAM;
#EndIf

#IfMissingColumn libreehr_postcalendar_events pc_room
ALTER TABLE `libreehr_postcalendar_events` ADD `pc_room` varchar(20) NOT NULL DEFAULT '' ;
#EndIf

#IfMissingColumn list_options toggle_setting_1
ALTER TABLE `list_options` ADD COLUMN `toggle_setting_1` tinyint(1) NOT NULL default '0';
UPDATE `list_options` SET `notes`='FF2414|10' , `toggle_setting_1`='1' WHERE `option_id`='@' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FF6619|10' , `toggle_setting_1`='1' WHERE `option_id`='~' AND `list_id` = 'apptstat';
#EndIf
 
#IfMissingColumn list_options toggle_setting_2
ALTER TABLE `list_options` ADD COLUMN `toggle_setting_2` tinyint(1) NOT NULL DEFAULT '0';
UPDATE `list_options` SET `notes`='0BBA34|0' , `toggle_setting_2`='1' WHERE `option_id`='!' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FEFDCF|0' , `toggle_setting_2`='1' WHERE `option_id`='>' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FEFDCF|0' WHERE `option_id`='-' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FFC9F8|0' WHERE `option_id`='*' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='87FF1F|0' WHERE `option_id`='+' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='BFBFBF|0' WHERE `option_id`='x' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='BFBFBF|0' WHERE `option_id`='?' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='FFFF2B|0' WHERE `option_id`='#' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='52D9DE|10' WHERE `option_id`='<' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='C0FF96|0' WHERE `option_id`='$' AND `list_id` = 'apptstat';
UPDATE `list_options` SET `notes`='BFBFBF|0' WHERE `option_id`='%' AND `list_id` = 'apptstat';
#EndIf

#IfNotRow2D list_options list_id lists option_id patient_flow_board_rooms
INSERT INTO list_options (list_id,option_id,title) VALUES ('lists','patient_flow_board_rooms','Patient Flow Board Rooms');
INSERT INTO list_options (list_id,option_id,title,seq) VALUES ('patient_flow_board_rooms', '1', 'Room 1', 10);
INSERT INTO list_options (list_id,option_id,title,seq) VALUES ('patient_flow_board_rooms', '2', 'Room 2', 20);
INSERT INTO list_options (list_id,option_id,title,seq) VALUES ('patient_flow_board_rooms', '3', 'Room 3', 30);
#EndIf

#IfMissingColumn clinical_rules developer
ALTER TABLE  `clinical_rules` ADD  `developer` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Clinical Rule Developer';
#EndIf

#IfMissingColumn clinical_rules funding_source
ALTER TABLE  `clinical_rules` ADD  `funding_source` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Clinical Rule Funding Source';
#EndIf

#IfMissingColumn clinical_rules release_version
ALTER TABLE  `clinical_rules` ADD  `release_version` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Clinical Rule Release Version';
#EndIf

#IfNotRow2D list_options list_id proc_res_abnormal option_id vhigh
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('proc_res_abnormal', 'vhigh', 'Above upper panic limits', 50);
#EndIf

#IfNotRow2D list_options list_id proc_res_abnormal option_id vlow
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`) VALUES ('proc_res_abnormal', 'vlow', 'Below lower panic limits', 60);
#EndIf

#IfNotRow code_types ct_key LOINC
DROP TABLE IF EXISTS `temp_table_one`;
CREATE TABLE `temp_table_one` (`id` int(11) NOT NULL DEFAULT '0',`seq` int(11) NOT NULL DEFAULT '0') ENGINE=MyISAM;
INSERT INTO `temp_table_one` (`id`, `seq`) VALUES (
  IF(((SELECT MAX(`ct_id` ) FROM `code_types`) >= 100), ((SELECT MAX(`ct_id` ) FROM `code_types`) + 1), 100),
  IF(((SELECT MAX(`ct_seq`) FROM `code_types`) >= 100), ((SELECT MAX(`ct_seq`) FROM `code_types`) + 1), 100));
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag, ct_active, ct_label, ct_external, ct_claim, ct_proc, ct_term, ct_problem ) VALUES ('LOINC', (SELECT MAX(`id`) FROM `temp_table_one`), (SELECT MAX(`seq`) FROM `temp_table_one`), 0, '', 0, 0, 1, 0, 1, 'LOINC', 0, 0, 0, 0, 0);
DROP TABLE `temp_table_one`;
#EndIf

#IfNotRow code_types ct_key PHIN Questions
DROP TABLE IF EXISTS `temp_table_one`;
CREATE TABLE `temp_table_one` (`id` int(11) NOT NULL DEFAULT '0',`seq` int(11) NOT NULL DEFAULT '0') ENGINE=MyISAM;
INSERT INTO `temp_table_one` (`id`, `seq`) VALUES (
  IF(((SELECT MAX(`ct_id` ) FROM `code_types`) >= 100), ((SELECT MAX(`ct_id` ) FROM `code_types`) + 1), 100),
  IF(((SELECT MAX(`ct_seq`) FROM `code_types`) >= 100), ((SELECT MAX(`ct_seq`) FROM `code_types`) + 1), 100));
INSERT INTO code_types (ct_key, ct_id, ct_seq, ct_mod, ct_just, ct_fee, ct_rel, ct_nofs, ct_diag, ct_active, ct_label, ct_external, ct_claim, ct_proc, ct_term, ct_problem ) VALUES ('PHIN Questions', (SELECT MAX(`id`) FROM `temp_table_one`), (SELECT MAX(`seq`) FROM `temp_table_one`), 0, '', 0, 0, 1, 0, 1, 'PHIN Questions', 0, 0, 0, 0, 0);
DROP TABLE `temp_table_one`;
#EndIf

#IfMissingColumn list_options activity
ALTER TABLE `list_options` ADD COLUMN `activity` TINYINT DEFAULT 1 NOT NULL;
#EndIf

#IfNotTable ccda_components
CREATE TABLE ccda_components (
  ccda_components_id int(11) NOT NULL AUTO_INCREMENT,
  ccda_components_field varchar(100) DEFAULT NULL,
  ccda_components_name varchar(100) DEFAULT NULL,
  PRIMARY KEY (ccda_components_id)
) ENGINE=InnoDB AUTO_INCREMENT=10 ;
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('1','progress_note','Progress Notes');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('2','consultation_note','Consultation Note');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('3','continuity_care_document','Continuity Care Document');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('4','diagnostic_image_reporting','Diagnostic Image Reporting');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('5','discharge_summary','Discharge Summary');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('6','history_physical_note','History and Physical Note');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('7','operative_note','Operative Note');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('8','procedure_note','Procedure Note');
insert into ccda_components (ccda_components_id, ccda_components_field, ccda_components_name) values('9','unstructured_document','Unstructured Document');
#EndIf

#IfNotTable ccda_sections
CREATE TABLE ccda_sections (
  ccda_sections_id int(11) NOT NULL AUTO_INCREMENT,
  ccda_components_id int(11) DEFAULT NULL,
  ccda_sections_field varchar(100) DEFAULT NULL,
  ccda_sections_name varchar(100) DEFAULT NULL,
  ccda_sections_req_mapping tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (ccda_sections_id)
) ENGINE=InnoDB AUTO_INCREMENT=46;
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('1','1','assessment_plan','Assessment and Plan','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('2','2','assessment_plan','Assessment and Plan','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('3','2','history_of_present_illness','History of Present Illness','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('4','2','physical_exam','Physical Exam','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('5','2','reason_of_visit','Reason for Referral/Reason for Visit','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('6','3','allergies','Allergies','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('7','3','medications','Medications','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('8','3','problem_list','Problem List','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('9','3','procedures','Procedures','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('10','3','results','Results','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('11','4','report','Report','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('12','5','allergies','Allergies','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('13','5','hospital_course','Hospital Course','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('14','5','hospital_discharge_diagnosis','Hospital Discharge Diagnosis','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('15','5','hospital_discharge_medications','Hospital Discharge Medications','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('16','5','plan_of_care','Plan of Care','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('17','6','allergies','Allergies','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('19','6','chief_complaint','Chief Complaint / Reason for Visit','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('21','6','family_history','Family History','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('22','6','general_status','General Status','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('23','6','hpi_past_med','History of Past Illness (Past Medical History)','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('24','6','hpi','History of Present Illness','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('25','6','medications','Medications','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('26','6','physical_exam','Physical Exam','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('28','6','results','Results','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('29','6','review_of_systems','Review of Systems','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('30','6','social_history','Social History','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('31','6','vital_signs','Vital Signs','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('32','7','anesthesia','Anesthesia','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('33','7','complications','Complications','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('34','7','post_operative_diagnosis','Post Operative Diagnosis','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('35','7','pre_operative_diagnosis','Pre Operative Diagnosis','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('36','7','procedure_estimated_blood_loss','Procedure Estimated Blood Loss','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('37','7','procedure_findings','Procedure Findings','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('38','7','procedure_specimens_taken','Procedure Specimens Taken','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('39','7','procedure_description','Procedure Description','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('40','8','assessment_plan','Assessment and Plan','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('41','8','complications','Complications','1');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('42','8','postprocedure_diagnosis','Postprocedure Diagnosis','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('43','8','procedure_description','Procedure Description','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('44','8','procedure_indications','Procedure Indications','0');
insert into ccda_sections (ccda_sections_id, ccda_components_id, ccda_sections_field, ccda_sections_name, ccda_sections_req_mapping) values('45','9','unstructured_doc','Document','0');
#EndIf

#IfNotTable ccda_table_mapping
CREATE TABLE ccda_table_mapping (
  id int(11) NOT NULL AUTO_INCREMENT,
  ccda_component varchar(100) DEFAULT NULL,
  ccda_component_section varchar(100) DEFAULT NULL,
  form_dir varchar(100) DEFAULT NULL,
  form_type smallint(6) DEFAULT NULL,
  form_table varchar(100) DEFAULT NULL,
  user_id int(11) DEFAULT NULL,
  deleted tinyint(4) NOT NULL DEFAULT '0',
  timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable ccda_field_mapping
CREATE TABLE ccda_field_mapping (
  id int(11) NOT NULL AUTO_INCREMENT,
  table_id int(11) DEFAULT NULL,
  ccda_field varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotTable ccda
CREATE TABLE ccda (
  id INT(11) NOT NULL AUTO_INCREMENT,
  pid BIGINT(20) DEFAULT NULL,
  encounter BIGINT(20) DEFAULT NULL,
  ccda_data MEDIUMTEXT,
  time VARCHAR(50) DEFAULT NULL,
  status SMALLINT(6) DEFAULT NULL,
  updated_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  user_id VARCHAR(50) null,
  couch_docid VARCHAR(100) NULL,
  couch_revid VARCHAR(100) NULL,
  `view` tinyint(4) NOT NULL DEFAULT '0',
  `transfer` tinyint(4) NOT NULL DEFAULT '0',
  `type` VARCHAR(15),
  `emr_transfer` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY unique_key (pid,encounter,time)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;
#EndIf

#IfNotRow2D list_options list_id lists option_id personal_relationship
INSERT INTO list_options(list_id,option_id,title) VALUES ('lists','personal_relationship','Relationship');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','ADOPT','Adopted Child','ADOPT','10');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','AUNT','Aunt','AUNT','20');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','CHILD','Child','CHILD','30');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','CHLDINLAW','Child in-law','CHLDINLAW','40');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','COUSN','Cousin','COUSN','50');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','DOMPART','Domestic Partner','DOMPART','60');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','FAMMEMB','Family Member','FAMMEMB','70');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','CHLDFOST','Foster Child','CHLDFOST','80');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','GRNDCHILD','Grandchild','GRNDCHILD','90');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','GPARNT','Grandparent','GPARNT','100');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','GRPRN','Grandparent','GRPRN','110');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','GGRPRN','Great Grandparent','GGRPRN','120');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','HSIB','Half-Sibling','HSIB','130');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','MAUNT','MaternalAunt','MAUNT','140');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','MCOUSN','MaternalCousin','MCOUSN','150');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','MGRPRN','MaternalGrandparent','MGRPRN','160');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','MGGRPRN','MaternalGreatgrandparent','MGGRPRN','170');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','MUNCLE','MaternalUncle','MUNCLE','180');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','NCHILD','Natural Child','NCHILD','190');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','NPRN','Natural Parent','NPRN','200');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','NSIB','Natural Sibling','NSIB','210');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','NBOR','Neighbor','NBOR','220');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','NIENEPH','Niece/Nephew','NIENEPH','230');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','PRN','Parent','PRN','240');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','PRNINLAW','parent in-law','PRNINLAW','250');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','PAUNT','PaternalAunt','PAUNT','260');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','PCOUSN','PaternalCousin','PCOUSN','270');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','PGRPRN','PaternalGrandparent','PGRPRN','280');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','PGGRPRN','PaternalGreatgrandparent','PGGRPRN','290');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','PUNCLE','PaternalUncle','PUNCLE','300');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','ROOM','Roommate','ROOM','310');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','SIB','Sibling','SIB','320');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','SIBINLAW','Sibling in-law','SIBINLAW','330');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','SIGOTHR','Significant Other','SIGOTHR','340');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','SPS','Spouse','SPS','350');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','STEP','Step Child','STEP','360');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','STPPRN','Step Parent','STPPRN','370');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','STPSIB','Step Sibling','STPSIB','380');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','UNCLE','Uncle','UNCLE','390');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('personal_relationship','FRND','Unrelated Friend','FRND','400');
#EndIf


#IfMissingColumn lists severity_al
ALTER TABLE lists ADD COLUMN severity_al VARCHAR(50) DEFAULT NULL;
#EndIf

#IfNotRow2D list_options list_id lists option_id severity_ccda
INSERT INTO list_options (list_id, option_id, title) VALUES ('lists','severity_ccda','Severity');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','unassigned','Unassigned','','10');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','mild','Mild','SNOMED-CT:255604002','20');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','mild_to_moderate','Mild to moderate','SNOMED-CT:371923003','30');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','moderate','Moderate','SNOMED-CT:6736007','40');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','moderate_to_severe','Moderate to severe','SNOMED-CT:371924009','50');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','severe','Severe','SNOMED-CT:24484000','60');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','life_threatening_severity','Life threatening severity','SNOMED-CT:442452003','70');
INSERT INTO list_options (list_id, option_id, title, codes, seq) values ('severity_ccda','fatal','Fatal','SNOMED-CT:399166001','80');
#EndIf

#IfNotRow3D list_options list_id drug_route notes PO title Per Oris
UPDATE list_options SET list_options.notes = 'PO' WHERE list_options.list_id = 'drug_route' AND title = 'Per Oris';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title Per Rectum
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'Per Rectum';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title To Skin
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'To Skin';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title To Affected Area
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'To Affected Area';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title Sublingual
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'Sublingual';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title OS
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'OS';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title OD
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'OD';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title OU
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'OU';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title SQ
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'SQ';
#EndIf

#IfNotRow3D list_options list_id drug_route notes IM title IM
UPDATE list_options SET list_options.notes = 'IM' WHERE list_options.list_id = 'drug_route' AND title = 'IM';
#EndIf

#IfNotRow3D list_options list_id drug_route notes IV title IV
UPDATE list_options SET list_options.notes = 'IV' WHERE list_options.list_id = 'drug_route' AND title = 'IV';
#EndIf

#IfNotRow3D list_options list_id drug_route notes NS title Per Nostril
UPDATE list_options SET list_options.notes = 'NS' WHERE list_options.list_id = 'drug_route' AND title = 'Per Nostril';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title Both Ears
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'Both Ears';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title Left Ear
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'Left Ear';
#EndIf

#IfNotRow3D list_options list_id drug_route notes OTH title Right Ear
UPDATE list_options SET list_options.notes = 'OTH' WHERE list_options.list_id = 'drug_route' AND title = 'Right Ear';
#EndIf

#IfNotRow2Dx2 list_options list_id drug_route title intradermal title Intradermal
INSERT INTO list_options ( list_id, option_id, title, seq,  notes ) VALUES ('drug_route', 'intradermal', 'Intradermal', 20, 'ID');
#EndIf

#IfNotRow2Dx2 list_options list_id drug_route title other title Other/Miscellaneous
INSERT INTO list_options ( list_id, option_id, title, seq, notes ) VALUES ('drug_route', 'other', 'Other/Miscellaneous', 30, 'OTH');
#EndIf

#IfNotRow2Dx2 list_options list_id drug_route title transdermal title Transdermal
INSERT INTO list_options ( list_id, option_id, title, seq, notes ) VALUES ('drug_route', 'transdermal', 'Transdermal', 40, 'TD');
#EndIf

#IfNotRow2D list_options list_id lists option_id physician_type
INSERT INTO list_options (list_id,option_id,title) VALUES ('lists','physician_type','Physician Type');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','attending_physician','SNOMED-CT:405279007','Attending physician', '10');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','audiological_physician','SNOMED-CT:310172001','Audiological physician', '20');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','chest_physician','SNOMED-CT:309345004','Chest physician', '30');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','community_health_physician','SNOMED-CT:23278007','Community health physician', '40');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','consultant_physician','SNOMED-CT:158967008','Consultant physician', '50');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','general_physician','SNOMED-CT:59058001','General physician', '60');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','genitourinarymedicinephysician','SNOMED-CT:309358003','Genitourinary medicine physician', '70');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','occupational_physician','SNOMED-CT:158973009','Occupational physician', '80');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','palliative_care_physician','SNOMED-CT:309359006','Palliative care physician', '90');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','physician','SNOMED-CT:309343006','Physician', '100');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','public_health_physician','SNOMED-CT:56466003','Public health physician', '110');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','rehabilitation_physician','SNOMED-CT:309360001','Rehabilitation physician', '120');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','resident_physician','SNOMED-CT:405277009','Resident physician', '130');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','specialized_physician','SNOMED-CT:69280009','Specialized physician', '140');
INSERT INTO list_options (list_id, option_id, codes,title, seq) VALUES ('physician_type','thoracic_physician','SNOMED-CT:309346003','Thoracic physician', '150');
#EndIf

#IfNotRow3D list_options list_id marital option_id married notes M
update list_options set notes = 'M' where list_id = 'marital' and option_id = 'married';
#EndIf

#IfNotRow3D list_options list_id marital option_id single notes S
update list_options set notes = 'S' where list_id = 'marital' and option_id = 'single';
#EndIf

#IfNotRow3D list_options list_id marital option_id divorced notes D
update list_options set notes = 'D' where list_id = 'marital' and option_id = 'divorced';
#EndIf

#IfNotRow3D list_options list_id marital option_id widowed notes W
update list_options set notes = 'W' where list_id = 'marital' and option_id = 'widowed';
#EndIf

#IfNotRow3D list_options list_id marital option_id separated notes L
update list_options set notes = 'L' where list_id = 'marital' and option_id = 'separated';
update list_options set notes = 'T' where list_id = 'marital' and option_id = 'domestic partner';
#EndIf

#IfMissingColumn users physician_type
ALTER TABLE users ADD COLUMN physician_type VARCHAR(50) DEFAULT NULL;
#EndIf

#IfMissingColumn facility facility_code
ALTER TABLE facility ADD COLUMN facility_code VARCHAR(31) default NULL;
#EndIf

#IfMissingColumn documents audit_master_approval_status
ALTER TABLE documents ADD COLUMN audit_master_approval_status TINYINT DEFAULT 1 NOT NULL COMMENT 'approval_status from audit_master table';
#EndIf

#IfMissingColumn documents audit_master_id
ALTER TABLE documents ADD COLUMN  audit_master_id int(11) default NULL;
#EndIf


#IfNotRow categories name CCDA
INSERT INTO categories (id, name, value, parent, lft, rght) select (select MAX(id) from categories) + 1, 'CCDA', '', 1, rght, rght + 1 from categories where name = 'Categories';
UPDATE categories SET rght = rght + 2 WHERE name = 'Categories';
UPDATE categories_seq SET id = (select MAX(id) from categories);
#Endif

#IfNotRow2D list_options list_id abook_type option_id ccda
INSERT INTO list_options (list_id, option_id, title, seq, option_value) VALUES ('abook_type', 'ccda', 'Care Coordination', 35, 2);
#EndIf

#IfNotRow2D list_options list_id abook_type option_id emr_direct
INSERT INTO list_options (list_id, option_id, title , seq, option_value) VALUES ('abook_type', 'emr_direct', 'EMR Direct' ,105, 4);
#EndIf

#IfNotRow2D list_options list_id abook_type option_id external_provider
INSERT INTO list_options (list_id, option_id, title , seq, option_value) VALUES ('abook_type', 'external_provider', 'External Provider' ,110, 1);
#EndIf

#IfNotRow2D list_options list_id abook_type option_id external_org
INSERT INTO list_options (list_id, option_id, title , seq, option_value) VALUES ('abook_type', 'external_org', 'External Organization' ,120, 1);
#EndIf

#IfMissingColumn immunizations external_id
ALTER TABLE `immunizations` ADD COLUMN `external_id` VARCHAR(20) DEFAULT NULL;
#EndIf

#IfMissingColumn prescriptions external_id
ALTER TABLE `prescriptions` ADD COLUMN `external_id` VARCHAR(20) DEFAULT NULL;
#EndIf

#IfMissingColumn lists external_id
ALTER TABLE `lists` ADD COLUMN `external_id` VARCHAR(20) DEFAULT NULL;
#EndIf

#IfMissingColumn form_vitals external_id
ALTER TABLE `form_vitals` ADD COLUMN `external_id` VARCHAR(20) DEFAULT NULL;
#EndIf

#IfMissingColumn form_encounter external_id
ALTER TABLE `form_encounter` ADD COLUMN `external_id` VARCHAR(20) DEFAULT NULL;
#EndIf

#IfMissingColumn billing external_id
ALTER TABLE `billing` ADD COLUMN `external_id` VARCHAR(20) DEFAULT NULL;
#EndIf

#IfMissingColumn procedure_order external_id
ALTER TABLE `procedure_order` ADD COLUMN `external_id` VARCHAR(20) DEFAULT NULL;
#EndIf

#IfMissingColumn patient_data industry
SET @group_name = (SELECT group_name FROM layout_options WHERE field_id='occupation' AND form_id='DEM');
SET @backup_group_name = (SELECT group_name FROM layout_options WHERE field_id='DOB' AND form_id='DEM');
SET @seq = (SELECT MAX(seq) FROM layout_options WHERE group_name = IFNULL(@group_name,@backup_group_name) AND form_id='DEM');
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`) VALUES ('DEM', 'industry', IFNULL(@group_name,@backup_group_name), 'Industry', @seq+1, 1, 1, 0, 0, 'Industry', 1, 1, '', '', 'Industry' ) ;
ALTER TABLE patient_data ADD COLUMN industry TEXT NOT NULL;
#EndIf

#IfNotRow2D list_options list_id lists option_id Industry
INSERT INTO `list_options` (`list_id`, `option_id`, `title`) VALUES('lists','Industry','Industry');
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('Industry', 'law_firm', 'Law Firm', 10);
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('Industry', 'engineering_firm', 'Engineering Firm', 20);
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('Industry', 'construction_firm', 'Construction Firm', 30);
#EndIf

#IfNotListOccupation
#EndIf

#IfNotRow2D list_options list_id Occupation option_id lawyer
SET @max_list_id = (SELECT MAX(seq) FROM list_options WHERE list_id='Occupation');
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('Occupation', 'lawyer', 'Lawyer', IFNULL(@max_list_id,0) + 10);
#EndIf

#IfNotRow2D list_options list_id Occupation option_id engineer
SET @max_list_id = (SELECT MAX(seq) FROM list_options WHERE list_id='Occupation');
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('Occupation', 'engineer', 'Engineer', (@max_list_id+10));
#EndIf

#IfNotRow2D list_options list_id Occupation option_id site_worker
SET @max_list_id = (SELECT MAX(seq) FROM list_options WHERE list_id='Occupation');
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('Occupation', 'site_worker', 'Site Worker', (@max_list_id+10));
#EndIf

#IfNotRow3D layout_options field_id occupation form_id DEM data_type 26
UPDATE layout_options SET list_id='Occupation', data_type='26', fld_length='0', max_length='0', edit_options='' WHERE field_id='occupation' AND form_id='DEM';
#EndIf

#IfMissingColumn patient_access_offsite portal_relation
ALTER TABLE patient_access_offsite ADD COLUMN portal_relation VARCHAR(100) NULL;
#EndIf

#IfMissingColumn pnotes portal_relation
ALTER TABLE pnotes ADD COLUMN `portal_relation` VARCHAR(100) NULL;
#EndIf

#IfMissingColumn pnotes is_msg_encrypted
ALTER TABLE pnotes ADD is_msg_encrypted TINYINT(2) DEFAULT '0' COMMENT 'Whether messsage encrypted 0-Not encrypted, 1-Encrypted'; 
#EndIf

#IfMissingColumn log log_from
ALTER TABLE `log` ADD `log_from` VARCHAR(20) DEFAULT 'open-emr'; 
#EndIf

#IfMissingColumn log menu_item_id
ALTER TABLE `log` ADD `menu_item_id` INT(11) DEFAULT NULL;
#EndIf

#IfNotTable patient_portal_menu
CREATE TABLE `patient_portal_menu` (
  `patient_portal_menu_id` INT(11) NOT NULL AUTO_INCREMENT,
  `patient_portal_menu_group_id` INT(11) DEFAULT NULL,
  `menu_name` VARCHAR(40) DEFAULT NULL,
  `menu_order` SMALLINT(4) DEFAULT NULL,
  `menu_status` TINYINT(2) DEFAULT '1',
  PRIMARY KEY (`patient_portal_menu_id`)
) ENGINE=INNODB AUTO_INCREMENT=14;

INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (1,1,'Dashboard',3,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (2,1,'My Profile',6,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (3,1,'Appointments',9,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (4,1,'Documents',12,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (5,1,'Med Records',15,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (6,1,'My Account',18,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (7,1,'Mailbox',21,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (8,1,'Password',24,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (9,1,'View Log',27,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (10,1,'Logout',30,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (11,1,'View Health Information',33,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (12,1,'Download Health Information',36,1);
INSERT  INTO `patient_portal_menu`(`patient_portal_menu_id`,`patient_portal_menu_group_id`,`menu_name`,`menu_order`,`menu_status`) VALUES (13,1,'Transmit Health Information',39,1);
#Endif

#IfMissingColumn log ccda_doc_id
ALTER TABLE `log` ADD `ccda_doc_id` INT(11) DEFAULT NULL COMMENT 'CCDA document id from ccda';
#Endif

#IfNotListReaction
#EndIf

#IfNotRow2D list_options list_id reaction option_id unassigned
INSERT INTO list_options ( list_id, option_id, title, seq ) VALUES ('reaction', 'unassigned', 'Unassigned', 0);
#EndIf

#IfNotRow2D list_options list_id reaction option_id hives
SET @max_list_id = (SELECT MAX(seq) FROM list_options WHERE list_id='reaction');
INSERT INTO list_options ( list_id, option_id, title, seq, codes ) VALUES ('reaction', 'hives', 'Hives', (@max_list_id+10), 'SNOMED-CT:247472004');
#EndIf

#IfNotRow2D list_options list_id reaction option_id nausea
SET @max_list_id = (SELECT MAX(seq) FROM list_options WHERE list_id='reaction');
INSERT INTO list_options ( list_id, option_id, title, seq, codes ) VALUES ('reaction', 'nausea', 'Nausea', (@max_list_id+10), 'SNOMED-CT:422587007');
#EndIf

#IfNotListImmunizationManufacturer
#EndIf

#IfNotRow2D list_options list_id lists option_id Immunization_Manufacturer
INSERT INTO list_options (list_id, option_id, title) VALUES ('lists','Immunization_Manufacturer','Immunization Manufacturer');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','AB','Abbott Laboratories','AB','10');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','ACA','Acambis, Inc','ACA','20');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','AD','Adams Laboratories, Inc.','AD','30');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','AKR','Akorn, Inc','AKR','40');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','ALP','Alpha Therapeutic Corporation','ALP','50');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','AR','Armour','AR','60');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','AVB','Aventis Behring L.L.C.','AVB','70');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','AVI','Aviron','AVI','80');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','BRR','Barr Laboratories','BRR','90');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','BAH','Baxter Healthcare Corporation','BAH','100');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','BA','Baxter Healthcare Corporation-inactive','BA','110');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','BAY','Bayer Corporation','BAY','120');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','BP','Berna Products','BP','130');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','BPC','Berna Products Corporation','BPC','140');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','BTP','Biotest Pharmaceuticals Corporation','BTP','150');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','CNJ','Cangene Corporation','CNJ','160');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','CMP','Celltech Medeva Pharmaceuticals','CMP','170');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','CEN','Centeon L.L.C.','CEN','180');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','CHI','Chiron Corporation','CHI','190');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','CON','Connaught','CON','200');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','CRU','Crucell','CRU','210');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','CSL','CSL Behring, Inc','CSL','220');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','DVC','DynPort Vaccine Company, LLC','DVC','230');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','MIP','Emergent BioDefense Operations Lansing','MIP','240');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','EVN','Evans Medical Limited','EVN','250');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','GEO','GeoVax Labs, Inc.','GEO','260');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','SKB','GlaxoSmithKline','SKB','270');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','GRE','Greer Laboratories, Inc.','GRE','280');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','GRF','Grifols','GRF','290');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','IDB','ID Biomedical','IDB','300');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','IAG','Immuno International AG','IAG','310');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','IUS','Immuno-U.S., Inc.','IUS','320');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','INT','Intercell Biomedical','INT','330');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','JNJ','Johnson and Johnson','JNJ','340');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','KGC','Korea Green Cross Corporation','KGC','350');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','LED','Lederle','LED','360');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','MBL','Massachusetts Biologic Laboratories','MBL','370');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','MA','Massachusetts Public Health Biologic Laboratories','MA','380');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','MED','MedImmune, Inc.','MED','390');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','MSD','Merck and Co., Inc.','MSD','400');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','IM','Merieux','IM','410');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','MIL','Miles','MIL','420');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','NAB','NABI','NAB','430');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','NYB','New York Blood Center','NYB','440');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','NAV','North American Vaccine, Inc.','NAV','450');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','NOV','Novartis Pharmaceutical Corporation','NOV','460');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','NVX','Novavax, Inc.','NVX','470');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','OTC','Organon Teknika Corporation','OTC','480');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','ORT','Ortho-clinical Diagnostics','ORT','490');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','OTH','Other manufacturer','OTH','500');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','PD','Parkedale Pharmaceuticals','PD','510');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','PFR','Pfizer, Inc','PFR','520');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','PWJ','PowderJect Pharmaceuticals','PWJ','530');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','PRX','Praxis Biologics','PRX','540');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','PSC','Protein Sciences','PSC','550');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','PMC','sanofi pasteur','PMC','560');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','SCL','Sclavo, Inc.','SCL','570');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','SOL','Solvay Pharmaceuticals','SOL','580');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','SI','Swiss Serum and Vaccine Inst.','SI','590');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','TAL','Talecris Biotherapeutics','TAL','600');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','JPN','The Research Foundation for Microbial Diseases of Osaka University (BIKEN)','JPN','610');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','USA','United States Army Medical Research and Material Command','USA','620');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','UNK','Unknown manufacturer','UNK','630');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','VXG','VaxGen','VXG','640');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','WAL','Wyeth','WAL','650');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','WA','Wyeth-Ayerst','WA','660');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Manufacturer','ZLB','ZLB Behring','ZLB','670');
#EndIf

#IfNotRow2D list_options list_id lists option_id Immunization_Completion_Status
INSERT INTO list_options (list_id, option_id, title) VALUES ('lists','Immunization_Completion_Status','Immunization Completion Status');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Completion_Status','Completed','completed','CP', '10');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Completion_Status','Refused','Refused','RE', '20');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Completion_Status','Not_Administered','Not Administered','NA', '30');
INSERT INTO list_options (list_id, option_id, title, notes, seq) VALUES ('Immunization_Completion_Status','Partially_Administered','Partially Administered','PA', '40');
#EndIf

#IfMissingColumn immunizations completion_status
ALTER TABLE immunizations ADD COLUMN `completion_status` VARCHAR(50) DEFAULT NULL;
#EndIf

#IfMissingColumn prescriptions indication
ALTER TABLE prescriptions ADD COLUMN `indication` text;
#EndIf

#IfMissingColumn prescriptions end_date
ALTER TABLE prescriptions ADD COLUMN `end_date` date default NULL;
#EndIf

#IfNotTable external_procedures
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
#EndIf

#IfNotTable external_encounters
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
#EndIf

#IfMissingColumn prescriptions prn
ALTER TABLE prescriptions ADD COLUMN `prn` VARCHAR(30) DEFAULT NULL;
#EndIf

#IfMissingColumn patient_data care_team
SET @group_name = (SELECT group_name FROM layout_options WHERE field_id='ref_providerID' AND form_id='DEM');
SET @backup_group_name = (SELECT group_name FROM layout_options WHERE field_id='DOB' AND form_id='DEM');
SET @seq = (SELECT MAX(seq) FROM layout_options WHERE group_name = IFNULL(@group_name,@backup_group_name) AND form_id='DEM');
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`) VALUES ('DEM', 'care_team', IFNULL(@group_name,@backup_group_name), 'Care Team', @seq+1, 11, 1, 0, 0, '', 1, 1, '', '', '' ) ;
alter table patient_data add column care_team int(11) DEFAULT NULL;
#EndIf

#IfNotTable form_observation
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
SET @seq = (SELECT MAX(id) FROM registry);
INSERT INTO `registry` (`name`,`state`,`directory`,`id`,`sql_run`,`unpackaged`,`date`,`priority`,`category`,`nickname`) VALUES ('Observation', 1, 'observation', @seq+1, 1, 1, '2015-09-09 00:00:00', 0, 'Clinical', '');
#EndIf

#IfNotTable form_care_plan
CREATE TABLE `form_care_plan` (
  `id` bigint(20) NOT NULL,
  `date` DATE DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `codetext` text,
  `description` text,
  `external_id` VARCHAR(30) DEFAULT NULL
) ENGINE=InnoDB;
SET @seq = (SELECT MAX(id) FROM registry);
INSERT INTO `registry` (`name`,`state`,`directory`,`id`,`sql_run`,`unpackaged`,`date`,`priority`,`category`,`nickname`) VALUES ('Care Plan', 1, 'care_plan', @seq+1, 1, 1, '2015-09-09 00:00:00', 0, 'Clinical', '');
#EndIf

#IfNotTable form_functional_cognitive_status
CREATE TABLE `form_functional_cognitive_status` (
  `id` bigint(20) NOT NULL,
  `date` DATE DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `codetext` text,
  `description` text,
  `external_id` VARCHAR(30) DEFAULT NULL
) ENGINE=InnoDB;
SET @seq = (SELECT MAX(id) FROM registry);
INSERT INTO `registry` (`name`,`state`,`directory`,`id`,`sql_run`,`unpackaged`,`date`,`priority`,`category`,`nickname`) VALUES ('Functional and Cognitive Status', 1, 'functional_cognitive_status', @seq+1, 1, 1, '2015-09-09 00:00:00', 0, 'Clinical', '');
#EndIf

UPDATE `clinical_rules` SET `cqm_2014_flag` = 1 WHERE `id` = 'rule_htn_bp_measure_cqm' AND `pid` = 0;
UPDATE `clinical_rules` SET `cqm_2014_flag` = 1 WHERE `id` = 'rule_adult_wt_screen_fu_cqm' AND `pid` = 0;
UPDATE `clinical_rules` SET `cqm_2014_flag` = 1 WHERE `id` = 'rule_wt_assess_couns_child_cqm' AND `pid` = 0;
UPDATE `clinical_rules` SET `cqm_2014_flag` = 1 WHERE `id` = 'rule_pneumovacc_ge_65_cqm' AND `pid` = 0;

#IfMissingColumn clinical_rules amc_2014_stage1_flag
        ALTER TABLE `clinical_rules` ADD COLUMN `amc_2014_stage1_flag` tinyint(1) COMMENT '2014 Stage 1 - Automated Measure Calculation flag for (unable to customize per patient)';

        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–4' WHERE `clinical_rules`.`id` = 'problem_list_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–5' WHERE `clinical_rules`.`id` = 'med_list_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–6' WHERE `clinical_rules`.`id` = 'med_allergy_list_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–7' WHERE `clinical_rules`.`id` = 'cpoe_med_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–9' WHERE `clinical_rules`.`id` = 'record_dem_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–11' WHERE `clinical_rules`.`id` = 'record_smoke_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–12' WHERE `clinical_rules`.`id` = 'lab_result_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–13' WHERE `clinical_rules`.`id` = 'send_reminder_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–15' WHERE `clinical_rules`.`id` = 'provide_sum_pat_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = '1', `amc_code_2014` = '170.314(g)(1)/(2)–16' WHERE `clinical_rules`.`id` = 'patient_edu_amc' AND `clinical_rules`.`pid` =0;
#EndIf

#IfMissingColumn clinical_rules amc_2014_stage2_flag
        ALTER TABLE `clinical_rules` ADD COLUMN `amc_2014_stage2_flag` tinyint(1) COMMENT '2014 Stage 2 - Automated Measure Calculation flag for (unable to customize per patient)';

        UPDATE `clinical_rules` SET `amc_2014_stage2_flag` = '1' WHERE `clinical_rules`.`id` = 'record_smoke_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage2_flag` = '1' WHERE `clinical_rules`.`id` = 'lab_result_amc' AND `clinical_rules`.`pid` =0;
        UPDATE `clinical_rules` SET `amc_2014_stage2_flag` = '1' WHERE `clinical_rules`.`id` = 'record_dem_amc' AND `clinical_rules`.`pid` =0;
#EndIf

#IfNotRow clinical_rules id rule_children_pharyngitis_cqm
INSERT INTO `clinical_rules` (`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`) VALUES ('rule_children_pharyngitis_cqm', 0, 0, 0, 1, '0002', '', 0, '', 0, 0, 0, '', 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_children_pharyngitis_cqm
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('clinical_rules', 'rule_children_pharyngitis_cqm', 'Appropriate Testing for Children with Pharyngitis (CQM)', 502, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id rule_fall_screening_cqm
INSERT INTO `clinical_rules` (`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`) VALUES ('rule_fall_screening_cqm', 0, 0, 0, 1, '0101', '', 0, '', 0, 0, 0, '', 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_fall_screening_cqm
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('clinical_rules', 'rule_fall_screening_cqm', 'Falls: Screening, Risk-Assessment, and Plan of Care to Prevent Future Falls (CQM)', 504, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id rule_pain_intensity_cqm
INSERT INTO `clinical_rules` (`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`) VALUES ('rule_pain_intensity_cqm', 0, 0, 0, 1, '0384', '', 0, '', 0, 0, 0, '', 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_pain_intensity_cqm
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('clinical_rules', 'rule_pain_intensity_cqm', 'Oncology: Medical and Radiation – Pain Intensity Quantified (CQM)', 506, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id rule_child_immun_stat_2014_cqm
INSERT INTO `clinical_rules` (`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES ('rule_child_immun_stat_2014_cqm', 0, 0, 0, 1, '0038', '', 0, '', 0, 0, 0, '', 0, 1, 0, 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_child_immun_stat_2014_cqm
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('clinical_rules', 'rule_child_immun_stat_2014_cqm', 'Childhood immunization Status (CQM)', 250, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id rule_tob_use_2014_cqm
INSERT INTO `clinical_rules` (`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES ('rule_tob_use_2014_cqm', 0, 0, 0, 1, '0028', '', 0, '', 0, 0, 0, '', 0, 1, 0, 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_tob_use_2014_cqm
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES ('clinical_rules', 'rule_tob_use_2014_cqm', 'Preventive Care and Screening: Tobacco Use: Screening and Cessation Intervention (CQM)', 210, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id image_results_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('image_results_amc', 0, 0, 0, 0, '', '', 1, '', 0, 0, 1, '170.314(g)(1)/(2)–20', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id image_results_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'image_results_amc', 'Image Results', 3000, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id family_health_history_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('family_health_history_amc', 0, 0, 0, 0, '', '', 1, '', 0, 0, 1, '170.314(g)(1)/(2)–21', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id family_health_history_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'family_health_history_amc', 'Family Health History', 3100, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id electronic_notes_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('electronic_notes_amc', 0, 0, 0, 0, '', '', 1, '', 0, 0, 1, '170.314(g)(1)/(2)–22', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id electronic_notes_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'electronic_notes_amc', 'Electronic Notes', 3200, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id secure_messaging_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('secure_messaging_amc', 0, 0, 0, 0, '', '', 1, '', 0, 0, 1, '170.314(g)(1)/(2)', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id secure_messaging_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'secure_messaging_amc', 'Secure Electronic Messaging', 3400, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id view_download_transmit_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('view_download_transmit_amc', 0, 0, 0, 0, '', '', 1, '', 0, 0, 1, '170.314(g)(1)/(2)–14', 0, 0, 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id view_download_transmit_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'view_download_transmit_amc', 'View, Download, Transmit (VDT)  (Measure B)', 3500, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id cpoe_radiology_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('cpoe_radiology_amc', 0, 0, 0, 0, '', '', 1, '170.304(a)', 0, 0, 1, '170.314(g)(1)/(2)–7', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id cpoe_radiology_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'cpoe_radiology_amc', 'Use CPOE for radiology orders.', 46, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id cpoe_proc_orders_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('cpoe_proc_orders_amc', 0, 0, 0, 0, '', '', 1, '170.304(a)', 0, 0, 1, '170.314(g)(1)/(2)–7', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id cpoe_proc_orders_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'cpoe_proc_orders_amc', 'Use CPOE for procedure orders.', 47, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id send_reminder_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('send_reminder_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.304(d)', 0, 0, 1, '170.314(g)(1)/(2)–13', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id send_reminder_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'send_reminder_stage2_amc', 'Send reminders to patients per patient preference for preventive/follow up care.', 60, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id cpoe_med_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('cpoe_med_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.304(a)', 0, 0, 1, '170.314(g)(1)/(2)–7', 0, 0, 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id cpoe_med_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'cpoe_med_stage2_amc', 'Use CPOE for medication orders.(Alternative)', 47, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id patient_edu_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('patient_edu_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.302(m)', 0, 0, 1, '170.314(g)(1)/(2)–16', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id patient_edu_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'patient_edu_stage2_amc', 'Use certified EHR technology to identify patient-specific education resources and provide those resources to the patient if appropriate(New).', 40, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id record_vitals_1_stage1_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('record_vitals_1_stage1_amc', 0, 0, 0, 0, '', '', 1, '170.302(f)', 0, 0, 1, '170.314(g)(1)/(2)–10', 0, 0, 0, 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id record_vitals_1_stage1_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'record_vitals_1_stage1_amc', 'Record and chart changes in vital signs (SET 1).', 20, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id record_vitals_2_stage1_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('record_vitals_2_stage1_amc', 0, 0, 0, 0, '', '', 1, '170.302(f)', 0, 0, 1, '170.314(g)(1)/(2)–10', 0, 0, 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id record_vitals_2_stage1_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'record_vitals_2_stage1_amc', 'Record and chart changes in vital signs (BP out of scope).', 20, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id record_vitals_3_stage1_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('record_vitals_3_stage1_amc', 0, 0, 0, 0, '', '', 1, '170.302(f)', 0, 0, 1, '170.314(g)(1)/(2)–10', 0, 0, 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id record_vitals_3_stage1_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'record_vitals_3_stage1_amc', 'Record and chart changes in vital signs (Height / Weight out of scope).', 20, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id record_vitals_4_stage1_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('record_vitals_4_stage1_amc', 0, 0, 0, 0, '', '', 1, '170.302(f)', 0, 0, 1, '170.314(g)(1)/(2)–10', 0, 0, 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id record_vitals_4_stage1_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'record_vitals_4_stage1_amc', 'Record and chart changes in vital signs ( Height / Weight / BP with in scope ).', 20, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id record_vitals_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('record_vitals_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.302(f)', 0, 0, 1, '170.314(g)(1)/(2)–10', 0, 0, 0, 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id record_vitals_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'record_vitals_stage2_amc', 'Record and chart changes in vital signs (New).', 20, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id provide_sum_pat_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('provide_sum_pat_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.304(h)', 0, 0, 1, '170.314(g)(1)/(2)–15', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id provide_sum_pat_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'provide_sum_pat_stage2_amc', 'Provide clinical summaries for patients for each office visit (New).', 75, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id vdt_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('vdt_stage2_amc', 0, 0, 0, 0, '', '', 1, '', 0, 0, 1, '170.314(g)(1)/(2)–14', 0, 0, 1, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id vdt_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'vdt_stage2_amc', 'View, Download, Transmit (VDT) (Measure A)', 3500, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id send_sum_stage1_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('send_sum_stage1_amc', 0, 0, 0, 0, '', '', 1, '170.304(i)', 0, 0, 1, '170.314(g)(1)/(2)–18', 0, 0, 1, 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id send_sum_stage1_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'send_sum_stage1_amc', 'The EP, eligible hospital or CAH who transitions their patient to another setting of care or provider of care or refers their patient to another provider of care should provide summary of care record for each transition of care or referral.', 80, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id send_sum_1_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('send_sum_1_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.304(i)', 0, 0, 1, '170.314(g)(1)/(2)–18', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id send_sum_1_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'send_sum_1_stage2_amc', 'The EP, eligible hospital or CAH who transitions their patient to another setting of care or provider of care or refers their patient to another provider of care should provide summary of care record for each transition of care or referral (Measure A).', 80, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id send_sum_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('send_sum_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.304(i)', 0, 0, 1, '170.314(g)(1)/(2)–18', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id send_sum_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'send_sum_stage2_amc', 'The EP, eligible hospital or CAH who transitions their patient to another setting of care or provider of care or refers their patient to another provider of care should provide summary of care record for each transition of care or referral (Measure B).', 80, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id e_prescribe_stage1_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('e_prescribe_stage1_amc', 0, 0, 0, 0, '', '', 1, '170.304(b)', 0, 0, 1, '170.314(g)(1)/(2)–8', 0, 0, 1, 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id e_prescribe_stage1_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'e_prescribe_stage1_amc', 'Generate and transmit permissible prescriptions electronically (Not including controlled substances).', 50, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id e_prescribe_1_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('e_prescribe_1_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.304(b)', 0, 0, 1, '170.314(g)(1)/(2)–8', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id e_prescribe_1_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'e_prescribe_1_stage2_amc', 'Generate and transmit permissible prescriptions electronically (All Prescriptions).', 50, 0, 0, '', '', '', 0, 0);
#EndIf

#IfNotRow clinical_rules id e_prescribe_2_stage2_amc
	INSERT INTO `clinical_rules` 
	(`id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `amc_2011_flag`, `amc_2014_flag`, `amc_code_2014`, `cqm_2011_flag`, `cqm_2014_flag`, `amc_2014_stage1_flag`, `amc_2014_stage2_flag`) VALUES
	('e_prescribe_2_stage2_amc', 0, 0, 0, 0, '', '', 1, '170.304(b)', 0, 0, 1, '170.314(g)(1)/(2)–8', 0, 0, 0, 1);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id e_prescribe_2_stage2_amc
	INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`) VALUES
	('clinical_rules', 'e_prescribe_2_stage2_amc', 'Generate and transmit permissible prescriptions electronically (Not including controlled substances).', 50, 0, 0, '', '', '', 0, 0);
#EndIf

#IfMissingColumn users cpoe
	ALTER TABLE `users` ADD `cpoe` tinyint(1) NULL DEFAULT NULL;
#EndIf

#IfMissingColumn procedure_order_code procedure_order_title
	ALTER TABLE  `procedure_order_code` ADD  `procedure_order_title` varchar( 255 ) NULL DEFAULT NULL;
#EndIf

#IfMissingColumn procedure_providers lab_director
	ALTER TABLE `procedure_providers` ADD `lab_director` bigint(20) NOT NULL DEFAULT '0';
#EndIf

#IfNotRow2D list_options list_id lists option_id order_type
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('lists','order_type','Order Types', 1,0);
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('order_type','procedure','Procedure',10,0);
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('order_type','intervention','Intervention',20,0);
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('order_type','laboratory_test','Laboratory Test',30,0);
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('order_type','physical_exam','Physical Exam',40,0);
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('order_type','risk_category','Risk Category Assessment',50,0);
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('order_type','patient_characteristics','Patient Characteristics',60,0);
	INSERT INTO list_options ( list_id, option_id, title, seq, is_default ) VALUES ('order_type','imaging','Imaging',70,0);
#EndIf


#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2015-10-01 load_filename 2016-PCS-Long-Abbrev-Titles.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2015-10-01', '2016-PCS-Long-Abbrev-Titles.zip', 'd5ea519d0257db0ed7deb0406a4d0503');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2015-10-01 load_filename 2016-General-Equivalence-Mappings.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2015-10-01', '2016-General-Equivalence-Mappings.zip', '3324a45b6040be7e48ab770a0d3ca695');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2015-10-01 load_filename 2016-Code-Descriptions-in-Tabular-Order.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2015-10-01', '2016-Code-Descriptions-in-Tabular-Order.zip', '518a47fe9e268e4fb72fecf633d15f17');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2015-10-01 load_filename 2016-ProcedureGEMs.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2015-10-01', '2016-ProcedureGEMs.zip', '45a8d9da18d8aed57f0c6ea91e3e8fe4');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2015-10-01 load_filename Reimbursement_Mapping_dx_2016.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2015-10-01', 'Reimbursement_Mapping_dx_2016.zip', '1b53b512e10c1fdf7ae4cfd1baa8dfbb');
#EndIf

#IfNotRow4D supported_external_dataloads load_type ICD10 load_source CMS load_release_date 2015-10-01 load_filename Reimbursement_Mapping_pr_2016.zip
INSERT INTO `supported_external_dataloads` (`load_type`, `load_source`, `load_release_date`, `load_filename`, `load_checksum`) VALUES ('ICD10', 'CMS', '2015-10-01', 'Reimbursement_Mapping_pr_2016.zip', '3c780dd103d116aa57980decfddd4f19');
#EndIf

#IfNotRow2D list_options list_id transactions option_id LBTref
UPDATE list_options SET title = 'Layout-Based Transaction Forms', seq = 9 WHERE list_id = 'lists' AND option_id = 'transactions';
UPDATE list_options SET option_id = 'LBTref'   WHERE list_id = 'transactions' AND option_id = 'Referral';
UPDATE list_options SET option_id = 'LBTptreq' WHERE list_id = 'transactions' AND option_id = 'Patient Request';
UPDATE list_options SET option_id = 'LBTphreq' WHERE list_id = 'transactions' AND option_id = 'Physician Request';
UPDATE list_options SET option_id = 'LBTlegal' WHERE list_id = 'transactions' AND option_id = 'Legal';
UPDATE list_options SET option_id = 'LBTbill'  WHERE list_id = 'transactions' AND option_id = 'Billing';
UPDATE transactions SET title     = 'LBTref'   WHERE title = 'Referral';
UPDATE transactions SET title     = 'LBTptreq' WHERE title = 'Patient Request';
UPDATE transactions SET title     = 'LBTphreq' WHERE title = 'Physician Request';
UPDATE transactions SET title     = 'LBTlegal' WHERE title = 'Legal';
UPDATE transactions SET title     = 'LBTbill'  WHERE title = 'Billing';
UPDATE layout_options SET form_id = 'LBTref'   WHERE form_id = 'REF';

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTptreq','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTphreq','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTlegal','body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);

INSERT INTO `layout_options` (`form_id`,`field_id`,`group_name`,`title`,`seq`,`data_type`,`uor`,`fld_length`,
  `max_length`,`list_id`,`titlecols`,`datacols`,`default_value`,`edit_options`,`description`,`fld_rows`)
  VALUES ('LBTbill' ,'body','1','Details',10,3,2,30,0,'',1,3,'','','Content',5);
#EndIf

#IfNotTable lbt_data
CREATE TABLE `lbt_data` (
  `form_id`     bigint(20)   NOT NULL COMMENT 'references transactions.id',
  `field_id`    varchar(31)  NOT NULL COMMENT 'references layout_options.field_id',
  `field_value` TEXT         NOT NULL,
  PRIMARY KEY (`form_id`,`field_id`)
) ENGINE=MyISAM COMMENT='contains all data from layout-based transactions';
#EndIf

#IfColumn transactions                                body
INSERT INTO lbt_data SELECT id, 'body'              , body               FROM transactions WHERE body               != '';
ALTER TABLE transactions DROP COLUMN                  body;
#EndIf
#IfColumn transactions                                refer_date
INSERT INTO lbt_data SELECT id, 'refer_date'        , refer_date         FROM transactions WHERE refer_date         IS NOT NULL;
ALTER TABLE transactions DROP COLUMN                  refer_date;
#EndIf
#IfColumn transactions                                refer_from
INSERT INTO lbt_data SELECT id, 'refer_from'        , refer_from         FROM transactions WHERE refer_from         != 0;
ALTER TABLE transactions DROP COLUMN                  refer_from;
#EndIf
#IfColumn transactions                                refer_to
INSERT INTO lbt_data SELECT id, 'refer_to'          , refer_to           FROM transactions WHERE refer_to           != 0;
ALTER TABLE transactions DROP COLUMN                  refer_to;
#EndIf
#IfColumn transactions                                refer_diag
INSERT INTO lbt_data SELECT id, 'refer_diag'        , refer_diag         FROM transactions WHERE refer_diag         != '';
ALTER TABLE transactions DROP COLUMN                  refer_diag;
#EndIf
#IfColumn transactions                                refer_risk_level
INSERT INTO lbt_data SELECT id, 'refer_risk_level'  , refer_risk_level   FROM transactions WHERE refer_risk_level   != '';
ALTER TABLE transactions DROP COLUMN                  refer_risk_level;
#EndIf
#IfColumn transactions                                refer_vitals
INSERT INTO lbt_data SELECT id, 'refer_vitals'      , refer_vitals       FROM transactions WHERE refer_vitals       != 0;
ALTER TABLE transactions DROP COLUMN                  refer_vitals;
#EndIf
#IfColumn transactions                                refer_external
INSERT INTO lbt_data SELECT id, 'refer_external'    , refer_external     FROM transactions WHERE refer_external     != 0;
ALTER TABLE transactions DROP COLUMN                  refer_external;
#EndIf
#IfColumn transactions                                refer_related_code
INSERT INTO lbt_data SELECT id, 'refer_related_code', refer_related_code FROM transactions WHERE refer_related_code != '';
ALTER TABLE transactions DROP COLUMN                  refer_related_code;
#EndIf
#IfColumn transactions                                refer_reply_date
INSERT INTO lbt_data SELECT id, 'refer_reply_date'  , refer_reply_date   FROM transactions WHERE refer_reply_date   IS NOT NULL;
ALTER TABLE transactions DROP COLUMN                  refer_reply_date;
#EndIf
#IfColumn transactions                                reply_date
INSERT INTO lbt_data SELECT id, 'reply_date'        , reply_date         FROM transactions WHERE reply_date         IS NOT NULL;
ALTER TABLE transactions DROP COLUMN                  reply_date;
#EndIf
#IfColumn transactions                                reply_from
INSERT INTO lbt_data SELECT id, 'reply_from'        , reply_from         FROM transactions WHERE reply_from         != '';
ALTER TABLE transactions DROP COLUMN                  reply_from;
#EndIf
#IfColumn transactions                                reply_init_diag
INSERT INTO lbt_data SELECT id, 'reply_init_diag'   , reply_init_diag    FROM transactions WHERE reply_init_diag    != '';
ALTER TABLE transactions DROP COLUMN                  reply_init_diag;
#EndIf
#IfColumn transactions                                reply_final_diag
INSERT INTO lbt_data SELECT id, 'reply_final_diag'  , reply_final_diag   FROM transactions WHERE reply_final_diag   != '';
ALTER TABLE transactions DROP COLUMN                  reply_final_diag;
#EndIf
#IfColumn transactions                                reply_documents
INSERT INTO lbt_data SELECT id, 'reply_documents'   , reply_documents    FROM transactions WHERE reply_documents    != '';
ALTER TABLE transactions DROP COLUMN                  reply_documents;
#EndIf
#IfColumn transactions                                reply_findings
INSERT INTO lbt_data SELECT id, 'reply_findings'    , reply_findings     FROM transactions WHERE reply_findings     != '';
ALTER TABLE transactions DROP COLUMN                  reply_findings;
#EndIf
#IfColumn transactions                                reply_services
INSERT INTO lbt_data SELECT id, 'reply_services'    , reply_services     FROM transactions WHERE reply_services     != '';
ALTER TABLE transactions DROP COLUMN                  reply_services;
#EndIf
#IfColumn transactions                                reply_recommend
INSERT INTO lbt_data SELECT id, 'reply_recommend'   , reply_recommend    FROM transactions WHERE reply_recommend    != '';
ALTER TABLE transactions DROP COLUMN                  reply_recommend;
#EndIf
#IfColumn transactions                                reply_rx_refer
INSERT INTO lbt_data SELECT id, 'reply_rx_refer'    , reply_rx_refer     FROM transactions WHERE reply_rx_refer     != '';
ALTER TABLE transactions DROP COLUMN                  reply_rx_refer;
#EndIf
#IfColumn transactions                                reply_related_code
INSERT INTO lbt_data SELECT id, 'reply_related_code', reply_related_code FROM transactions WHERE reply_related_code != '';
ALTER TABLE transactions DROP COLUMN                  reply_related_code;
#EndIf

#IfRow2D clinical_rules id secure_messaging_amc amc_code_2014 170.314(g)(1)/(2)
UPDATE `clinical_rules` SET `amc_code_2014` = '170.314(g)(1)/(2)-19' WHERE `id` = 'secure_messaging_amc' AND `amc_code_2014` = '170.314(g)(1)/(2)'; 
#EndIf

#IfMissingColumn documents documentationOf
ALTER TABLE `documents` ADD `documentationOf` varchar(255) DEFAULT NULL;
#EndIf

#IfMissingColumn ccda_components ccda_type
ALTER TABLE `ccda_components` ADD ccda_type int(11) NOT NULL COMMENT '0=>sections,1=>components';
#EndIf

#IfNotRow2D ccda_components ccda_components_field allergies ccda_components_name Allergies
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('allergies','Allergies',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field medications ccda_components_name Medications
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('medications','Medications',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field problems ccda_components_name Problems
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('problems','Problems',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field immunizations ccda_components_name Immunizations
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('immunizations','Immunizations',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field procedures ccda_components_name Procedures
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('procedures','Procedures',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field results ccda_components_name Results
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('results','Results',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field plan_of_care ccda_components_name Plan Of Care
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('plan_of_care','Plan Of Care',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field vitals ccda_components_name Vitals
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('vitals','Vitals',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field social_history ccda_components_name Social History
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('social_history','Social History',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field encounters ccda_components_name Encounters
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('encounters','Encounters',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field functional_status ccda_components_name Functional Status
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('functional_status','Functional Status',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field referral ccda_components_name Reason for Referral
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('referral','Reason for Referral',1);
#EndIf

#IfNotRow2D ccda_components ccda_components_field instructions ccda_components_name Instructions
INSERT INTO ccda_components (ccda_components_field, ccda_components_name, ccda_type) VALUES ('instructions','Instructions',1);
#EndIf

#IfNotTable form_clinical_instructions
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
INSERT INTO `registry` (`name`,`state`,`directory`,`sql_run`,`unpackaged`,`date`,`priority`,`category`,`nickname`) VALUES ('Clinical Instructions', 1, 'clinical_instructions', 1, 1, '2015-09-09 00:00:00', 0, 'Clinical', '');
#EndIf

#IfMissingColumn clinical_rules web_reference
ALTER TABLE  `clinical_rules` ADD  `web_reference` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Clinical Rule Web Reference';
#EndIf

#IfNotTable clinical_rules_log
CREATE TABLE `clinical_rules_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) NOT NULL DEFAULT '0',
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `category` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'An example category is clinical_reminder_widget',
  `value` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `category` (`category`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
#EndIf

#IfMissingColumn clinical_rules access_control
ALTER TABLE `clinical_rules` ADD `access_control` VARCHAR(255) NOT NULL DEFAULT 'patients:med' COMMENT 'ACO link for access control';
#EndIf

#IfNotRow clinical_rules id rule_socsec_entry
INSERT INTO `clinical_rules` ( `id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag`, `access_control` ) VALUES ('rule_socsec_entry', 0, 0, 0, 0, '', '', 0, '', 0, 'admin:practice');
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_socsec_entry
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('clinical_rules', 'rule_socsec_entry', 'Data Entry - Social Security Number', 1500, 0);
#EndIf

#IfNotRow2D list_options list_id rule_action option_id act_soc_sec
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('rule_action' ,'act_soc_sec', 'Social Security Number', 155, 0);
#EndIf

#IfNotRow rule_action id rule_socsec_entry
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_socsec_entry', 1, 'act_cat_assess', 'act_soc_sec');
#EndIf

#IfNotRow2D rule_action_item category act_cat_assess item act_soc_sec
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_assess', 'act_soc_sec', '', '', 0);
#EndIf

#IfNotRow rule_reminder id rule_socsec_entry
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_socsec_entry', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_socsec_entry', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_socsec_entry', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_socsec_entry', 'patient_reminder_post', 'month', '1');
#EndIf

#IfNotRow rule_target id rule_socsec_entry
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_socsec_entry', 1, 1, 1, 'target_database', '::patient_data::ss::::::ge::1', 0);
#EndIf

#IfRow2D list_options list_id clinical_rules option_id e_prescribe_stage1_amc
UPDATE `list_options` SET `title` = 'Generate and transmit permissible prescriptions electronically (Not including controlled substances).'  WHERE list_id = 'clinical_rules' AND option_id = 'e_prescribe_stage1_amc';
#EndIf

#IfRow2D list_options list_id clinical_rules option_id e_prescribe_2_stage2_amc 
UPDATE `list_options` SET `title` = 'Generate and transmit permissible prescriptions electronically (Not including controlled substances).'  WHERE list_id = 'clinical_rules' AND option_id = 'e_prescribe_2_stage2_amc';
#EndIf

#IfNotRow clinical_rules id rule_penicillin_allergy
INSERT INTO `clinical_rules` ( `id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag` ) VALUES ('rule_penicillin_allergy', 0, 0, 0, 0, '', '', 0, '', 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_penicillin_allergy
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('clinical_rules', 'rule_penicillin_allergy', 'Assess Penicillin Allergy', 1600, 0);
#EndIf

#IfNotRow2D list_options list_id rule_action option_id act_penicillin_allergy
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('rule_action' ,'act_penicillin_allergy', 'Penicillin Allergy', 157, 0);
#EndIf

#IfNotRow rule_action id rule_penicillin_allergy
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_penicillin_allergy', 1, 'act_cat_assess', 'act_penicillin_allergy');
#EndIf

#IfNotRow2D rule_action_item category act_cat_assess item act_penicillin_allergy
INSERT INTO `rule_action_item` ( `category`, `item`, `clin_rem_link`, `reminder_message`, `custom_flag` ) VALUES ('act_cat_assess', 'act_penicillin_allergy', '', '', 1);
#EndIf

#IfNotRow rule_reminder id rule_penicillin_allergy
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 'patient_reminder_post', 'month', '1');
#EndIf

#IfNotRow rule_filter id rule_penicillin_allergy
INSERT INTO `rule_filter` ( `id`, `include_flag`, `required_flag`, `method`, `method_detail`, `value` ) VALUES ('rule_penicillin_allergy', 1, 0, 'filt_lists', 'allergy', 'penicillin');
#EndIf

#IfNotRow rule_target id rule_penicillin_allergy
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_penicillin_allergy', 1, 1, 1, 'target_interval', 'year', 1);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_penicillin_allergy', 1, 1, 1, 'target_database', 'CUSTOM::act_cat_assess::act_penicillin_allergy::YES::ge::1', 0);
#EndIf

#IfMissingColumn clinical_rules_log new_value
ALTER TABLE  `clinical_rules_log` ADD `new_value` TEXT NOT NULL;
#EndIf

#IfNotColumnType procedure_report date_report datetime
ALTER TABLE `procedure_report` CHANGE `date_report` `date_report` datetime DEFAULT NULL;
#EndIf


#IfNotRow clinical_rules id rule_blood_pressure
INSERT INTO `clinical_rules` ( `id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag` ) VALUES ('rule_blood_pressure', 0, 0, 0, 0, '', '', 0, '', 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_blood_pressure
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('clinical_rules', 'rule_blood_pressure', 'Measure Blood Pressure', 1610, 0);
#EndIf

#IfNotRow rule_action id rule_blood_pressure
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_blood_pressure', 1, 'act_cat_measure', 'act_bp');
#EndIf

#IfNotRow rule_reminder id rule_blood_pressure
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_blood_pressure', 'patient_reminder_post', 'month', '1');
#EndIf

#IfNotRow rule_target id rule_blood_pressure
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_blood_pressure', 1, 1, 1, 'target_database', '::form_vitals::bps::::::ge::1', 0);
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_blood_pressure', 1, 1, 1, 'target_database', '::form_vitals::bpd::::::ge::1', 0);
#EndIf

#IfNotRow clinical_rules id rule_inr_measure
INSERT INTO `clinical_rules` ( `id`, `pid`, `active_alert_flag`, `passive_alert_flag`, `cqm_flag`, `cqm_nqf_code`, `cqm_pqri_code`, `amc_flag`, `amc_code`, `patient_reminder_flag` ) VALUES ('rule_inr_measure', 0, 0, 0, 0, '', '', 0, '', 0);
#EndIf

#IfNotRow2D list_options list_id clinical_rules option_id rule_inr_measure
INSERT INTO `list_options` ( `list_id`, `option_id`, `title`, `seq`, `is_default` ) VALUES ('clinical_rules', 'rule_inr_measure', 'Measure INR', 1620, 0);
#EndIf

#IfNotRow rule_action id rule_inr_measure
INSERT INTO `rule_action` ( `id`, `group_id`, `category`, `item` ) VALUES ('rule_inr_measure', 1, 'act_cat_measure', 'act_lab_inr');
#EndIf

#IfNotRow rule_reminder id rule_inr_measure
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'clinical_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'clinical_reminder_post', 'month', '1');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'patient_reminder_pre', 'week', '2');
INSERT INTO `rule_reminder` ( `id`, `method`, `method_detail`, `value` ) VALUES ('rule_inr_measure', 'patient_reminder_post', 'month', '1');
#EndIf

#IfNotRow rule_target id rule_inr_measure
INSERT INTO `rule_target` ( `id`, `group_id`, `include_flag`, `required_flag`, `method`, `value`, `interval` ) VALUES ('rule_inr_measure', 1, 1, 1, 'target_proc', 'INR::CPT4:85610::::::ge::1', 0);
#EndIf

#IfMissingColumn patient_data billing_note
SET @group_name = (SELECT group_name FROM layout_options WHERE field_id='lname' AND form_id='DEM');
SET @backup_group_name = (SELECT group_name FROM layout_options WHERE field_id='DOB' AND form_id='DEM');
SET @seq = (SELECT MAX(seq) FROM layout_options WHERE group_name = IFNULL(@group_name,@backup_group_name) AND form_id='DEM');
INSERT INTO `layout_options` (`form_id`, `field_id`, `group_name`, `title`, `seq`, `data_type`, `uor`, `fld_length`, `max_length`, `list_id`, `titlecols`, `datacols`, `default_value`, `edit_options`, `description`) VALUES ('DEM', 'billing_note', IFNULL(@group_name,@backup_group_name), 'Billing Note', @seq+1, 2, 1, 60, 0, '', 1, 3, '', '', 'Patient Level Billing Note (Collections)' ) ;
ALTER TABLE patient_data ADD COLUMN billing_note text NOT NULL default '';
UPDATE `patient_data` SET `billing_note` = `genericval2` WHERE `genericname2` = 'Billing';
UPDATE `patient_data` SET `genericval2` = '', `genericname2` = '' WHERE `genericname2` = 'Billing';
#EndIf

#IfMissingColumn lang_languages lang_is_rtl
ALTER TABLE `lang_languages` ADD COLUMN `lang_is_rtl` TINYINT DEFAULT 0;
UPDATE `lang_languages` SET `lang_is_rtl`=1 WHERE `lang_code` IN ('he','ar') OR `lang_description` IN('Hebrew','Arabic');
#EndIf

#IfMissingColumn procedure_report date_collected_tz
ALTER TABLE `procedure_report` ADD COLUMN `date_collected_tz` varchar(5) DEFAULT '' COMMENT '+-hhmm offset from UTC';
#EndIf

#IfMissingColumn procedure_report date_report_tz
ALTER TABLE `procedure_report` ADD COLUMN `date_report_tz` varchar(5) DEFAULT '' COMMENT '+-hhmm offset from UTC';
#EndIf

UPDATE `clinical_rules` SET `cqm_2014_flag` = 1 WHERE `id` = 'rule_influenza_ge_50_cqm' AND `pid` = 0;
UPDATE `clinical_rules` SET `cqm_2014_flag` = 1 WHERE `id` = 'rule_dm_a1c_cqm' AND `pid` = 0;

#IfMissingColumn lists subtype
ALTER TABLE `lists` ADD COLUMN `subtype` varchar(31) NOT NULL DEFAULT '';
#EndIf

#IfMissingColumn list_options subtype
ALTER TABLE `list_options` ADD COLUMN `subtype` varchar(31) NOT NULL DEFAULT '';
#EndIf

#IfNotRow2D list_options list_id lists option_id issue_subtypes
INSERT INTO list_options (list_id,option_id,title) VALUES ('lists','issue_subtypes','Issue Subtypes');
INSERT INTO list_options (list_id, option_id,title, seq) VALUES ('issue_subtypes', 'eye', 'Eye',10);
#EndIf

#IfTable clinical_rules
UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = 1, `amc_2014_stage2_flag` = 1 WHERE `id` = 'med_reconc_amc' AND `pid` = 0;
UPDATE `clinical_rules` SET `amc_2014_stage1_flag` = 1, `amc_2014_stage2_flag` = 1 WHERE `id` = 'med_reconc_amc' AND `pid` = 0;
#EndIf

#IfColumn transactions                                
 ALTER TABLE `users` DROP COLUMN `ssi_relayhealth`;
#EndIf
