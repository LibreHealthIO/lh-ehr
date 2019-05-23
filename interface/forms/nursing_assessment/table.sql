CREATE TABLE IF NOT EXISTS `form_nursing_assessment`(
`id` 						bigint(20) NOT NULL auto_increment,
`date` 						datetime default NULL,
`pid`						bigint(20) default NULL,
`encounter` 				varchar(255) DEFAULT NULL,
`user` 						varchar(255) default NULL,
`groupname` 				varchar(255) default NULL,
`authorized` 				tinyint(4) default NULL,
`activity` 					tinyint(4) default NULL,
`remarks_or_comments` 		longtext,
`reason_for_communication` 	longtext,
`provider_name_or_role` 	longtext,
`reason_for_communication` 	longtext,
`Method_of_communication` 	varchar(255),
`response` 					varchar(255),
`temperature` 				FLOAT(5,2)    default 0,
`temp_source` 				varchar(255),
`pulse`						FLOAT(5,2)      default 0,
`respiratory_rate`			FLOAT(5,2)      default 0,
`SpO2`						FLOAT(5,2)      default 0,
`FiO2`						FLOAT(5,2)      default 0,
`room_air` 					boolean,
`O2_delivery_method`		varchar(255),
`O2_flow_rate`				longtext,
`blood_pressure`			longtext,
`BP_mean`					varchar(255),
`BP_location`				varchar(255),
`BP_patient_position`		varchar(255),
`blood_glucose_level`		longtext,
`related_interventions`		varchar(255),
`patient_behavior`			varchar(255),
`patient_support`			varchar(255),
`patient_response`			varchar(255),
`patient_location`			varchar(255),
`patient_activity`			varchar(255),
`patient_position`			varchar(255),
`care_elements_performed`	varchar(255),
`bath_or_shower`			varchar(255),
`bed_position`				varchar(255),
`ADL_assistance_evel`		varchar(255),
`mobilization`				varchar(255),
`SCDs_or_ted_hose`			varchar(255),
`Pain Scale`				varchar(255),
`Patient's Pain Goal`		varchar(255),
`Pain rating`				tinyint(4),
`Pain location`				varchar(255),
`Pain description`			varchar(255),
`Frequency`					varchar(255),
`Interventions`				varchar(255),	
`Medication side effects`	varchar(255),
`Pasero Sedation Score `	varchar(255),
`respiratory_pattern`
`thermoregulation_type`
`location`
`limb_movements`
`motor_strength`
`sensation`
`eye_opening`
`best_verbal`
`total_score`
`comments_or_remarks`
`orientation`
`cry`
`pupil_reaction_or_bilat`
`pupil_reaction_or_left`
`pupil_reaction_or_right`
`oculomotor`
`fontanel`
`reflexes`
`seizure_activity`
`neuro_monitoring`
`c-collar`
`orientation`
`mood/affect`
`concentration/memory`
`appearance`
`behavior`
`eye_contact`
`thought`
`insight_judgement`
`patient_caregiver_interactions`
`ongoing_risk_precautions`
`escalated_behavior`
`cardio_heart_sounds`
`cardio_PMI`
`cardio_rhythm`
`cardio_arrythmia`
`cardio_arrythmia_frequency`
`cardio_interventions`
`cardio_cap_refill_central`
`cardio_cap_refill_peripheral`
`cardio_cap_refill_RUE`
`cardio_cap_refill_LUE`
`cardio_cap_refill_RLE`
`cardio_cap_refill_LLE`
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
``
PRIMARY KEY (id)
)ENGINE=InnoDB;