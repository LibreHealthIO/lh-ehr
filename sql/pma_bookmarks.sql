--
-- Table structure for table `pma_bookmark`
--

DROP TABLE IF EXISTS `pma_bookmark`;
CREATE TABLE `pma_bookmark` (
  `id` int(11) NOT NULL auto_increment,
  `dbase` varchar(255) default NULL,
  `user` varchar(255) default NULL,
  `label` varchar(255) default NULL,
  `query` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM COMMENT='Bookmarks' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `pma_bookmark`
--

INSERT INTO `pma_bookmark` VALUES (2, 'libreehr', 'libreehr', 'Aggregate Race Statistics', 'SELECT ethnoracial as "Race/Ethnicity", count(*) as Count FROM  `patient_data` WHERE 1 group by ethnoracial');
INSERT INTO `pma_bookmark` VALUES (9, 'libreehr', 'libreehr', 'Search by Code', 'SELECT  b.code, concat(pd.fname," ", pd.lname) as "Patient Name", concat(u.fname," ", u.lname) as "Provider Name", en.reason as "Encounter Desc.", en.date\r\nFROM billing as b\r\nLEFT JOIN users AS u ON b.user = u.id\r\nLEFT JOIN patient_data as pd on b.pid = pd.pid\r\nLEFT JOIN form_encounter as en on b.encounter = en.encounter and b.pid = en.pid\r\nWHERE 1 /* and b.code like ''%[VARIABLE]%'' */ ORDER BY b.code');
INSERT INTO `pma_bookmark` VALUES (8, 'libreehr', 'libreehr', 'Count No Shows By Provider since Interval ago', 'SELECT concat( u.fname,  " ", u.lname )  AS  "Provider Name", u.id AS  "Provider ID", count(  DISTINCT ev.pc_eid )  AS  "Number of No Shows"/* , concat(DATE_FORMAT(NOW(),''%Y-%m-%d''), '' and '',DATE_FORMAT(DATE_ADD(now(), INTERVAL [VARIABLE]),''%Y-%m-%d'') ) as "Between Dates" */ FROM  `libreehr_postcalendar_events`  AS ev LEFT  JOIN users AS u ON ev.pc_aid = u.id WHERE ev.pc_catid =1/* and ( ev.pc_eventDate >= DATE_SUB(now(), INTERVAL [VARIABLE]) )  */\r\nGROUP  BY u.id;');
INSERT INTO `pma_bookmark` VALUES (6, 'libreehr', 'libreehr', 'Appointments By Race/Ethnicity from today plus interval', 'SELECT  count(pd.ethnoracial) as "Number of Appointments", pd.ethnoracial AS  "Race/Ethnicity" /* , concat(DATE_FORMAT(NOW(),''%Y-%m-%d''), '' and '',DATE_FORMAT(DATE_ADD(now(), INTERVAL [VARIABLE]),''%Y-%m-%d'') ) as "Between Dates" */ FROM libreehr_postcalendar_events AS ev LEFT  JOIN   `patient_data`  AS pd ON  pd.pid = ev.pc_pid where ev.pc_eventstatus=1 and ev.pc_catid = 5 and ev.pc_eventDate >= now()  /* and ( ev.pc_eventDate <= DATE_ADD(now(), INTERVAL [VARIABLE]) )  */ group by pd.ethnoracial');

-- --------------------------------------------------------

--
-- Table structure for table `pma_column_info`
--

DROP TABLE IF EXISTS `pma_column_info`;
CREATE TABLE `pma_column_info` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `db_name` varchar(64) default NULL,
  `table_name` varchar(64) default NULL,
  `column_name` varchar(64) default NULL,
  `comment` varchar(255) default NULL,
  `mimetype` varchar(255) default NULL,
  `transformation` varchar(255) default NULL,
  `transformation_options` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`)
) ENGINE=MyISAM COMMENT='Column Information for phpMyAdmin' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pma_history`
--

DROP TABLE IF EXISTS `pma_history`;
CREATE TABLE `pma_history` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(64) default NULL,
  `db` varchar(64) default NULL,
  `table` varchar(64) default NULL,
  `timevalue` timestamp NOT NULL,
  `sqlquery` text,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`,`db`,`table`,`timevalue`)
) ENGINE=MyISAM COMMENT='SQL history' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pma_pdf_pages`
--

DROP TABLE IF EXISTS `pma_pdf_pages`;
CREATE TABLE `pma_pdf_pages` (
  `db_name` varchar(64) default NULL,
  `page_nr` int(10) unsigned NOT NULL auto_increment,
  `page_descr` varchar(50) default NULL,
  PRIMARY KEY  (`page_nr`),
  KEY `db_name` (`db_name`)
) ENGINE=MyISAM COMMENT='PDF Relationpages for PMA' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pma_relation`
--

DROP TABLE IF EXISTS `pma_relation`;
CREATE TABLE `pma_relation` (
  `master_db` varchar(64) NOT NULL default '',
  `master_table` varchar(64) NOT NULL default '',
  `master_field` varchar(64) NOT NULL default '',
  `foreign_db` varchar(64) default NULL,
  `foreign_table` varchar(64) default NULL,
  `foreign_field` varchar(64) default NULL,
  PRIMARY KEY  (`master_db`,`master_table`,`master_field`),
  KEY `foreign_field` (`foreign_db`,`foreign_table`)
) ENGINE=MyISAM COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma_table_coords`
--

DROP TABLE IF EXISTS `pma_table_coords`;
CREATE TABLE `pma_table_coords` (
  `db_name` varchar(64) NOT NULL default '',
  `table_name` varchar(64) NOT NULL default '',
  `pdf_page_number` int(11) NOT NULL default '0',
  `x` float unsigned NOT NULL default '0',
  `y` float unsigned NOT NULL default '0',
  PRIMARY KEY  (`db_name`,`table_name`,`pdf_page_number`)
) ENGINE=MyISAM COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma_table_info`
--

DROP TABLE IF EXISTS `pma_table_info`;
CREATE TABLE `pma_table_info` (
  `db_name` varchar(64) NOT NULL default '',
  `table_name` varchar(64) NOT NULL default '',
  `display_field` varchar(64) default NULL,
  PRIMARY KEY  (`db_name`,`table_name`)
) ENGINE=MyISAM COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

