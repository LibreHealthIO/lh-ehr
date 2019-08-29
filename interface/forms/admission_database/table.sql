CREATE TABLE IF NOT EXISTS `form_pediatric_PEWS`(
`id` 								bigint(20) 		NOT NULL auto_increment,
`date` 								datetime 				default NULL,
`pid`								bigint(20) 				default NULL,
`encounter` 						varchar(100)		 	DEFAULT NULL,
`user` 								varchar(100) 			default NULL,
`groupname` 						varchar(100) 			default NULL,
`authorized` 						tinyint(4) 				default NULL,
`activity` 							tinyint(4) 				default NULL,
`PEWS_behavior` 					text					default ''NULL,															
`PEWS_cardiovascular` 				text					default ''NULL,	
`PEWS_Respiratiory` 				text					default ''NULL,	
`PEWS_Total_Score` 					text					default ''NULL,	
`Interventions` 					text					default ''NULL,	
`Disposition` 						text					default ''NULL,																														
PRIMARY KEY (id)
)ENGINE=InnoDB;