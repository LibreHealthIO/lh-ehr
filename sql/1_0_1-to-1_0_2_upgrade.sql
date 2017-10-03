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

