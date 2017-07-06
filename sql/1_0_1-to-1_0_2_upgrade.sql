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

--
-- Table structure for table `medex_icons`
--

#IfNotTable medex_icons
CREATE TABLE `medex_icons` (
  `i_UID` int(11) NOT NULL AUTO_INCREMENT,
  `msg_type` varchar(50) NOT NULL,
  `msg_status` varchar(10) NOT NULL,
  `i_description` varchar(255) NOT NULL,
  `i_html` text,
  `i_blob` longtext NOT NULL,
  PRIMARY KEY (`i_UID`)
) ENGINE=InnoDB;

--
-- Dumping data for table `medex_icons`
--

INSERT INTO `medex_icons` (`i_UID`, `msg_type`, `msg_status`, `i_description`, `i_html`, `i_blob`) VALUES
(1, 'SMS', 'ALLOWED', 'Message type allowed', '<i title="SMS is possible." class="fa fa-commenting-o fa-fw"></i>', ''),
(2, 'SMS', 'NotAllowed', 'Message type not allowed', '<span class="fa-stack" title="SMS not possible"><i class="fa fa-commenting-o fa-stack-1x fa-fw"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>', ''),
(3, 'SMS', 'SCHEDULED', 'SMS: scheduled', '<span class="btn scheduled" title="SMS scheduled"><i class="fa fa-commenting-o fa-fw"></i></span>', ''),
(4, 'SMS', 'SENT', 'SMS Sent, not read - in process', '<span class="btn" title="SMS Sent - in process" style="padding:5px;background-color:yellow;color:black;"><i class="fa fa-commenting-o fa-fw"></i></span>', ''),
(5, 'SMS', 'READ', 'SMS delivered/read', '<span class="btn" title="SMS Sent and Read - waiting for response" aria-label="SMS Delivered" style="padding:5px;background-color:#146abd;"><i class="fa fa-commenting-o fa-flip-horizontal fa-fw" aria-hidden="true"></i></span>', ''),
(6, 'SMS', 'FAILURE', 'SMS delivery failed', '<span class="btn" title="SMS Failed to be delivered" style="padding:5px;background-color:red;"><i class="fa fa-commenting-o fa-inverse fa-fw"></i></span>', ''),
(7, 'SMS', 'CONFIRMED', 'Confirmed', '<span class="btn" title="Confirmed by SMS" style="padding:5px;background-color:green;"><i class="fa fa-commenting-o fa-inverse fa-fw"></i></span>', ''),
(8, 'SMS', 'CALL', 'Callback Requested by Patient', '<span class="btn btn-success" style="padding:5px;background-color: red;" title="Patient requests Office Call">\r\n<i class="fa fa-flag fa-fw"></i></span>\r\n', ''),
(9, 'SMS', 'EXTRA', 'Extra text sent along...', '<span class="fa-stack fa-lg"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-terminal fa-stack-1x fa-fw fa-inverse"></i></span>', ''),
(10, 'SMS', 'STOP', 'Optout of SMS please', '<span class="btn btn-danger" title="OptOut of SMS Messaging. Demographics updated." aria-label=''Optout SMS''><i class="fa fa-commenting" aria-hidden="true"> STOP</i></span>', ''),
(11, 'AVM', 'ALLOWED', 'Message type allowed', '<span title="Automated Voice Messages are possible" class="fa fa-phone fa-fw"></span>', ''),
(12, 'AVM', 'NotAllowed', 'Message type not allowed', '<span class="fa-stack" title="Automated Voice Messages are not allowed"><i class="fa fa-phone fa-fw fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>', ''),
(13, 'AVM', 'SCHEDULED', 'AVM: scheduled', '<span class="btn scheduled" title="AVM scheduled"><i class="fa fa-phone fa-fw"></i></span>', ''),
(14, 'AVM', 'SENT', 'AVM in process', '<span class="btn" title="AVM in process, no response" style="padding:5px;background-color:yellow;color:black;"><i class="fa fa-volume-control-phone fa-fw"></i></span>', ''),
(15, 'AVM', 'FAILURE', 'AVM: Message failed', '<span class="btn" title="AVM: Failed.  Check patient''s phone numbers." style="padding:5px;background-color:red;"><i class="fa fa-phone fa-inverse fa-fw"></i></span>', ''),
(16, 'AVM', 'CONFIRMED', 'Confirmed', '<span class="btn" title="Confirmed by AVM" style="padding:5px;background-color:green;"><i class="fa fa-phone fa-inverse fa-fw"></i></span>', ''),
(17, 'AVM', 'CALL', 'Callback Requested by Patient', '<span class="btn btn-success" style="padding:5px;background-color: red;" title="Patient requests Office Call">\r\n<i class="fa fa-flag fa-fw"></i></span>\r\n', ''),
(18, 'AVM', 'Other', 'Extra text sent along...', '<span class="fa-stack fa-lg"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-terminal fa-fw fa-stack-1x fa-inverse"></i></span>', ''),
(19, 'AVM', 'STOP', 'Optout of AVM please', '<span class="btn btn-danger" title="OptOut of Voice Messaging. Demographics updated." aria-label=‚ÄúOptout AVM‚Äù><i class="fa fa-phone" aria-hidden="true"> STOP</i></span>', ''),
(20, 'EMAIL', 'ALLOWED', 'EMAIL:  allowed', '<span title="EMAIL is possible" class="fa fa-envelope-o fa-fw"></span>', ''),
(21, 'EMAIL', 'NotAllowed', 'EMAIL: not allowed', '<span class="fa-stack" title="EMAIL is not possible"><i class="fa fa-envelope-o fa-fw fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>', ''),
(22, 'EMAIL', 'SCHEDULED', 'EMAIL: scheduled', '<span class=''btn scheduled'' title=''EMAIL scheduled''><i class="fa fa-envelope-o fa-fw"></i></span>', ''),
(23, 'EMAIL', 'SENT', 'EMAIL: sent', '<span class="btn" style="padding:5px;background-color:yellow;color:black;" title="EMAIL Message sent, not opened"><i class="fa fa-envelope-o fa-fw"></i></span>', ''),
(24, 'EMAIL', 'READ', 'EMAIL was opened/read', '<a class="btn" style="padding:5px;background-color:#146abd;" title="E-Mail was read/opened by patient" aria-label="Confirmed via email"><i class="fa fa-envelope-o fa-inverse fa-fw" aria-hidden="true"></i></a>', ''),
(25, 'EMAIL', 'FAILURE', 'EMAIL Message failed', '<span class="btn" title="EMAIL: Failed.  Check patient\'s email address." style="padding:5px;background-color:red;"><i class="fa fa-envelope-o fa-inverse fa-fw"></i></span>', ''),
(26, 'EMAIL', 'CONFIRMED', 'EMAIL Confirmed', '<a class="btn btn-success" style="padding:5px;background-color: green;" title="Confirmed by E-Mail" aria-label="Confirmed via email"><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i></a>', ''),
(27, 'EMAIL', 'CALL', 'Callback Requested by Patient', '<span class="btn btn-success" style="padding:5px;background-color: red;" title="Patient requests Office Call">\r\n<i class="fa fa-flag fa-fw"></i></span>\r\n', ''),
(28, 'EMAIL', 'Other', 'Extra text sent along...', '<span class="fa-stack fa-lg"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-terminal fa-fw fa-stack-1x fa-inverse fa-fw"></i></span>', ''),
(29, 'EMAIL', 'STOP', 'Optout of EMAIL please', '<span class="btn btn-danger" title="OptOut of EMAIL Messaging. Demographics updated." aria-label=‚ÄúOptout EMAIL‚Äù><i class="fa fa-envelope-o" aria-hidden="true"> STOP</i></span>', ''),
(30, 'POSTCARD', 'SENT', 'POSTCARD sent', '<span class="btn" title="Postcard Sent - in process" style="padding:5px;background-color:yellow;color:black"><i class="fa fa-image fa-fw"></i></span>', ''),
(31, 'POSTCARD', 'READ', 'e-POSTCARD delivered', '<a class="btn" style="padding:5px;background-color:#146abd;" title="e-Postcard was delivered" aria-label="Postcard Delivered"><i class="fa fa-image fa-fw" aria-hidden="true"></i></a>', ''),
(32, 'POSTCARD', 'FAILURE', 'e-POSTCARD failed', '<span class="fa-stack fa-lg" title="Delivery Failure - check Address for this patient"><i class="fa fa-image fa-fw fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>', ''),
(33, 'POSTCARD', 'SCHEDULED', 'Postcard Campaign Event is scheduled.', '<span class="btn scheduled" title="Postcard Campaign Event is scheduled."><i class="fa fa-image fa-fw"></i></span>', ''),
(36, 'AVM', 'READ', 'AVM delivered', '<span class="btn" title="AVM completed - waiting for manual response" aria-label="AVM Delivered" style="padding:5px;background-color:#146abd;"><i class="fa fa-phone fa-fw" aria-hidden="true"></i></span>', ''),
(37, 'SMS', 'CALLED', 'Callback Completed', '<span class="btn btn-success" style="padding:5px;background-color:#146abd;" title="Patient requests Office Call: COMPLETED">\r\n<i class="fa fa-flag fa-fw"></i></span>\r\n', '');
#Endif

--
-- Table structure for table `medex_outgoing`
--

#IfNotTable medex_outgoing
CREATE TABLE `medex_outgoing` (
  `msg_uid` int(11) NOT NULL AUTO_INCREMENT,
  `msg_pc_eid` varchar(11) NOT NULL,
  `campaign_uid` int(11) NOT NULL DEFAULT '0',
  `msg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `msg_type` varchar(50) NOT NULL,
  `msg_reply` varchar(50) DEFAULT NULL,
  `msg_extra_text` text,
  PRIMARY KEY (`msg_uid`),
  UNIQUE KEY `msg_eid` (`msg_uid`,`msg_pc_eid`)
) ENGINE=InnoDB;
#Endif

--
-- Table structure for table `medex_prefs`
--

#IfNotTable medex_prefs
CREATE TABLE `medex_prefs` (
  `MedEx_id` int(11) DEFAULT '0',
  `ME_username` varchar(100) DEFAULT NULL,
  `ME_api_key` text,
  `ME_facilities` varchar(50) DEFAULT NULL,
  `ME_providers` varchar(100) DEFAULT NULL,
  `ME_hipaa_default_override` varchar(3) DEFAULT NULL,
  `PHONE_country_code` int(4) NOT NULL DEFAULT '1',
  `MSGS_default_yes` varchar(3) DEFAULT NULL,
  `POSTCARDS_local` varchar(3) DEFAULT NULL,
  `POSTCARDS_remote` varchar(3) DEFAULT NULL,
  `LABELS_local` varchar(3) DEFAULT NULL,
  `LABELS_choice` varchar(50) DEFAULT NULL,
  `combine_time` tinyint(4) DEFAULT NULL,
  `notify` varchar(100) DEFAULT NULL,
  `postcard_top` varchar(255) DEFAULT NULL,
  `MedEx_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `ME_username` (`ME_username`)
) ENGINE=InnoDB;
#Endif

--
-- Table structure for table `medex_recalls`
--
#IfNotTable medex_recalls
CREATE TABLE `medex_recalls` (
  `r_ID` int(11) NOT NULL AUTO_INCREMENT,
  `r_PRACTID` int(11) NOT NULL,
  `r_pid` int(11) NOT NULL COMMENT 'PatientID from pat_data',
  `r_eventDate` date NOT NULL COMMENT 'Date of Appt or Recall',
  `r_facility` int(11) NOT NULL,
  `r_provider` int(11) NOT NULL,
  `r_reason` varchar(255) DEFAULT NULL,
  `r_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`r_ID`),
  UNIQUE KEY `r_PRACTID` (`r_PRACTID`,`r_pid`)
) ENGINE=InnoDB;
#Endif

--
-- Dumping data for table `background_services`
--

#IfNotRow background_services name MedEx
INSERT INTO `background_services` (`name`, `title`, `active`, `running`, `next_run`, `execute_interval`, `function`, `require_once`, `sort_order`) VALUES
('MedEx', 'MedEx Messaging Service', 29, 0, '2017-05-09 17:39:10', 60, 'start_MedEx', '/library/MedEx/medex_background.php', 100);
#Endif

#IfNotRow2D list_options list_id apptstat option_id AVM
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`, `subtype`) VALUES
('apptstat', 'AVM', 'AVM Confirmed', 110, 0, 0, '', 'F0FFE8|0', '', 0, 0, 1, '');
#Endif

#IfNotRow2D list_options list_id apptstat option_id CALL
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`, `subtype`) VALUES
('apptstat', 'CALL', 'Callback requested', 130, 0, 0, '', 'FFDBE2|5', '', 0, 0, 1, '');
#Endif

#IfNotRow2D list_options list_id apptstat option_id SMS
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`, `subtype`) VALUES
('apptstat', 'SMS', 'SMS Confirmed', 90, 0, 0, '', 'F0FFE8|0', '', 0, 0, 1, '');
#Endif

#IfNotRow2D list_options list_id apptstat option_id EMAIL
INSERT INTO `list_options` (`list_id`, `option_id`, `title`, `seq`, `is_default`, `option_value`, `mapping`, `notes`, `codes`, `toggle_setting_1`, `toggle_setting_2`, `activity`, `subtype`) VALUES
('apptstat', 'EMAIL', 'EMAIL Confimed', 20, 0, 0, '', 'FFEBE3|0', '', 0, 0, 1, '');
#Endif 
