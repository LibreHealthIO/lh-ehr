CREATE TABLE IF NOT EXISTS `form_patient_care_plan` (
  `id` bigint(20) NOT NULL ,
  `date` DATE DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `issue` tinyint(4) ,
  `Key_issue` varchar(255) DEFAULT ' ',
  `Interventions` text ,
  `Outcome` text,
  `Goal` text  , 
  `Progress` text ,
  `status` varchar(1) DEFAULT '1'
) ENGINE=InnoDB;