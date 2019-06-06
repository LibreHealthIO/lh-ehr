CREATE TABLE IF NOT EXISTS `form_nursing_assessment`(
`id` 								bigint(20) 		NOT NULL auto_increment,
`date` 								datetime 				default NULL,
`pid`								bigint(20) 				default NULL,
`encounter` 						varchar(100)		 	DEFAULT NULL,
`user` 								varchar(100) 			default NULL,
`groupname` 						varchar(100) 			default NULL,
`authorized` 						tinyint(4) 				default NULL,
`activity` 							tinyint(4) 				default NULL,
`ass_comments` 				text					default NULL,
`PN_reason` 			text					default NULL,
`PN_role` 			text					default NULL,
`PN_method` 			varchar(100)			default NULL,
`response` 							varchar(100)			default NULL,
`V_temperature` 						FLOAT(5,2)    			default 0,
`V_source` 						varchar(100)			default NULL,
`pulse`								FLOAT(5,2)      		default 0,
`V_RespiratoryRate`					FLOAT(5,2)      		default 0,
`SpO2`								FLOAT(5,2)      		default 0,
`FiO2`								FLOAT(5,2)      		default 0,
`V_roomAir` 							boolean					default NULL,
`V_deliveryMethod`				varchar(100)			default NULL,
`V_flowRate`						text					default NULL,
`V_bp`					text					default NULL,
`V_bpMean`							varchar(100)			default NULL,
`V_bpLocation`						varchar(100)			default NULL,
`V_bpPosition`				varchar(100)			default NULL,
`BG_level`				text					default NULL,
`BG_interventions`				varchar(100)			default NULL,
`pt_behavior`					varchar(100)			default NULL,
`pt_support`					varchar(100)			default NULL,
`pt_response`					varchar(100)			default NULL,
`ADL_location`					varchar(100)			default NULL,
`ADL_activity`					varchar(100)			default NULL,
`ADL_position`					varchar(100)			default NULL,
`ADL_care`			varchar(100)			default NULL,
`ADL_bath`					varchar(100)			default NULL,
`ADL_bed`						varchar(100)			default NULL,
`ADL_assistance`				varchar(100)			default NULL,
`ADL_mobilisation`						varchar(100)			default NULL,
`ADL_scd`					varchar(100)			default NULL,
`Pain_scale`						varchar(100)			default NULL,
`Pain_gaol`					varchar(100)			default NULL,
`Pain_rating`						tinyint(4),
`Pain_location`						varchar(100)			default NULL,
`Pain_description`					varchar(100)			default NULL,
`Pain_frequency`					varchar(100)			default NULL,
`Pain_interventions`				varchar(100)			default NULL,	
`Pain_medSideEffects`			varchar(100)			default NULL,
`pasero_sedation_score`				varchar(100)			default NULL,
`repiratory_pattern`				varchar(100)			default NULL,
`thermoregulation`				varchar(100)			default NULL,
`NM_location`						varchar(100)			default NULL,
`NM_limbMovnt`					varchar(100)			default NULL,
`NM_strength`					varchar(100)			default NULL,
`NM_sensation`						varchar(100)			default NULL,
`GCS_eyeOpening`					tinyint(4),
`GCS_bestVerbal`					tinyint(4),
`GCS_bestMotor`					tinyint(4),
`GCS_total`					tinyint(4),
`GCS_remarks`			varchar(100)			default NULL,
`NE_Orientaion`					varchar(100)			default NULL,
`NE_cry`							varchar(100)			default NULL,
`NE_pr_bilat`		varchar(100)			default NULL,
`NE_pr_left`			varchar(100)			default NULL,
`NE_pr_right`		varchar(100)			default NULL,
`NE_oculomotor`						varchar(100)			default NULL,
`NE_fontanel`						varchar(100)			default NULL,
`NE_reflexes`						tinyint(4),
`NE_seizure_activity`				boolean					default NULL,				
`NE_monitoring`				varchar(100)			default NULL,
`NE_collar`						varchar(100)			default NULL,
`psy_Orientaion`					varchar(100)			default NULL,
`psy_mood`					varchar(100)			default NULL,
`psy_memory`			varchar(100)			default NULL,
`psy_appearance`					varchar(100)			default NULL,
`psy_behaviour`						varchar(100)			default NULL,
`psy_eyeContact`					varchar(100)			default NULL,
`psy_thought`						varchar(100)			default NULL,
`pys_judgment`				varchar(100)			default NULL,
`pys_interactions`		varchar(100)			default NULL,
`psy_riskPrecaution`		varchar(100)			default NULL,
`Psy_escalated_behavior`			varchar(100)			default NULL,
`cardio_heartSounds`				varchar(100)			default NULL,
`cardio_PMI`						varchar(100)			default NULL,
`cardio_rhythm`						varchar(100)			default NULL,
`cardio_arrythmia`					varchar(100)			default NULL,
`cardio_arryfreq`		varchar(100)			default NULL,
`cardio_interventions`				varchar(100)			default NULL,
`cardio_crc`			varchar(100)			default NULL,
`cardio_crp`		varchar(100)			default NULL,
`cardio_crRUE`				varchar(100)			default NULL,
`cardio_crLUE`				varchar(100)			default NULL,
`cardio_crRLE`				varchar(100)			default NULL,
`cardio_crLLE`				varchar(100)			default NULL,
`cardio_centralColor`				varchar(100)			default NULL,
`cardio_PeripheralColor`			varchar(100)			default NULL,
`cardio_colorRUE`				varchar(100)			default NULL,
`cardio_colorLUE`				varchar(100)			default NULL,
`cardio_colorRLE`				varchar(100)			default NULL,
`cardio_colorLLE`				varchar(100)			default NULL,
`cardio_centralPulses`				varchar(100)			default NULL,
`cardio_pheripheralPulses`			varchar(100)			default NULL,
`cardio_pulseRUE`					varchar(100)			default NULL,
`cardio_pulseLUE`					varchar(100)			default NULL,
`cardio_pulseRUE`					varchar(100)			default NULL,
`cardio_pulseLLE`					varchar(100)			default NULL,
`resp_pattern`			varchar(100)			default NULL,
`resp_retractions`					varchar(100)			default NULL,
`resp_cough`							varchar(100)			default NULL,
`resp_appearance`						varchar(100)			default NULL,
`resp_bsallLobes`		varchar(100)			default NULL,
`resp_bsRUL`				varchar(100)			default NULL,
`resp_bsRML`				varchar(100)			default NULL,
`resp_bsRLL`				varchar(100)			default NULL,
`resp_bsLUL`				varchar(100)			default NULL,
`resp_bsLLL`				varchar(100)			default NULL,
`resp_interventions`		varchar(100)			default NULL,
`resp_suctiontype`				varchar(100)			default NULL,
`resp_oralSec`				varchar(100)			default NULL,
`resp_nasalSec`				varchar(100)			default NULL,
`resp_airwaySec`				varchar(100)			default NULL,
`GI_abdAsssess`					varchar(100)			default NULL,
`GI_girth`						FLOAT(5,2)				default 0,
`GI_liverPos`					varchar(100)			default NULL,
`GI_symptoms`						varchar(100)			default NULL,
`GI_interventions`					varchar(100)			default NULL,
`GI_bsAllQuad`		varchar(100)			default NULL,
`GI_bsRUG`							varchar(100)			default NULL,
`GI_bsRLG`							varchar(100)			default NULL,
`GI_bsLUG`							varchar(100)			default NULL,
`GI_bsLLG`							varchar(100)			default NULL,
`GI_epigastric`						varchar(100)			default NULL,
`GU_symptoms`						varchar(100)			default NULL,
`GU_interventions`					varchar(100)			default NULL,
`GU_bladderScan`	FLOAT(5,2)				default 0,
`GU_drainage`				varchar(100)			default NULL,
`GU_genitalia`				text					default NULL,
`msk_dlocation`				text					default NULL,
`msk_glocation`			text					default NULL,
`msk_gcircum`			tinyint(4),
`msk_muscle`					varchar(100)			default NULL,
`msk_motion`						varchar(100)			default NULL,
`msk_appearance`					varchar(100)			default NULL,
`msk_support`				varchar(100)			default NULL,
`msk_weight`				varchar(100)			default NULL,
`msk_degree`					text					default NULL,
`msk_state`					boolean					default NULL,
`msk_traction`						FLOAT(5,2)				default 0,
`msk_assessment`			varchar(100)			default NULL,
`msk_cap`					FLOAT(5,2)				default 0,
`msk_sensation`				varchar(100)			default NULL,
`msk_extColorT`			varchar(100)			default NULL,
`msk_intervention`		varchar(100)			default NULL,
`skin_assessment`					varchar(100)			default NULL,
`skin_location`					text					default NULL,
`skin_description`					varchar(100)			default NULL,
`skin_rlocation`						text					default NULL,
`skin_rdescription`					varchar(100)			default NULL,
`skin_lassessement`				text					default NULL,
`skin_NBlocation`				text					default NULL,
`skin_NBdescription`			varchar(100)			default NULL,
`skin_wlocation`					text					default NULL,
`wound_description`					varchar(100)			default NULL,
`skin_appearance`					varchar(100)			default NULL,
`skin_TfirstNoticed`			text					default NULL,
`skin_DfirstNoticed`			text					default NULL,
`skin_intervention`				varchar(100)			default NULL,
`skin_dressing`					varchar(100)			default NULL,
`skin_drainage`					varchar(100)			default NULL,
`skin_treatment`			varchar(100)			default NULL,
`skin_DfirstdressingApp`  text					default NULL,
`skin_comment`				text					default NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB;