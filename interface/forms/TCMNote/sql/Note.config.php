<?php

return array(
// NEW Query
	'NEW' => array(
		'TCMNote' => 'SELECT
	`users`.`username` AS `CaseManagerId`,
	CONCAT_WS(
		" ",
		`users`.`fname`,
		`users`.`mname`,
		`users`.`lname`,
		`users`.`valedictory`
	) AS `CaseManagerName`,
	`usersSuper`.`username` AS `CaseManagerSupervisorId`,
	CONCAT_WS(
		" ",
		`usersSuper`.`fname`,
		`usersSuper`.`mname`,
		`usersSuper`.`lname`,
		`usersSuper`.`valedictory`
	) AS `CaseManagerSupervisorName`,
	`patient_data`.`pid` AS `ClientId`,
	CONCAT_WS(
		" ",
		`patient_data`.`fname`,
		`patient_data`.`mname`,
		`patient_data`.`lname`
	) AS `ClientName`,
	`patient_data`.`DOB` AS `ClientBirth`,
	`insurance_data`.`policy_number` AS `MedicaidId`
FROM `patient_data`
	JOIN `insurance_data` ON `insurance_data`.`pid` = `patient_data`.`pid`,
	`users`
	LEFT JOIN `users` AS `usersSuper` ON `usersSuper`.`id` = `users`.`c_super`
WHERE
	`insurance_data`.`type` = "primary"
	AND `patient_data`.`pid` = :patientId
	AND `users`.`id` = :caseManagerId;',
  
 
  
	),

// READ Queries
	'READ' => array(

	// TCMServicePlans Past Query
		'TCMServicePlanMostRecentFinalized' => 'SELECT 
	`form_TCMServicePlan`.`Id`,
	`form_TCMServicePlan`.`TCMAssessmentId` AS `AssessmentId`,
	`form_TCMServicePlan`.`Type`,
	`form_TCMServicePlan`.`pid` AS `ClientId`,
	`form_TCMServicePlan`.`ClientName`,
	`form_TCMServicePlan`.`FinalizedDate`,
  `patient_data`.`DOB` AS `ClientBirth`,
	`form_TCMServicePlan`.`MedicaidId`,
	`form_TCMServicePlan`.`user` AS `CaseManagerId`,
	`form_TCMServicePlan`.`CaseManagerName`,
	`form_TCMServicePlan`.`CaseManagerSupervisorId`,
	`form_TCMServicePlan`.`CaseManagerSupervisorName`,
	`form_TCMServicePlan`.`date` AS `date`
FROM `form_TCMServicePlan`
	JOIN `patient_data` ON `patient_data`.`pid` = `form_TCMServicePlan`.`pid`
	JOIN `forms` ON `forms`.`formdir` = \'TCMServicePlan\'
		AND `forms`.`form_id` = `form_TCMServicePlan`.`Id`
	JOIN `form_encounter` ON `form_encounter`.`encounter` = `forms`.`encounter`
WHERE
	`form_TCMServicePlan`.`pid` = :patientId
    AND `form_TCMServicePlan`.`FinalizedDate` <= NOW()  	
    AND `form_TCMServicePlan`.`FinalizedDate` >= DATE_SUB(NOW(), INTERVAL 180 DAY) 	
AND `form_TCMServicePlan`.`FinalizedDate` = 
		(SELECT MAX(`FinalizedDate`) FROM `form_TCMServicePlan` WHERE `form_TCMServicePlan`.`pid` = :patientId)
LIMIT 1;',


		'TCMServicePlanMostRecent' => 'SELECT 
	`form_TCMServicePlan`.`Id`,
	`form_TCMServicePlan`.`TCMAssessmentId` AS `AssessmentId`,
	`form_TCMServicePlan`.`Type`,
	`form_TCMServicePlan`.`pid` AS `ClientId`,
	`form_TCMServicePlan`.`ClientName`,
	`form_TCMServicePlan`.`FinalizedDate`,
  `patient_data`.`DOB` AS `ClientBirth`,
	`form_TCMServicePlan`.`MedicaidId`,
	`form_TCMServicePlan`.`user` AS `CaseManagerId`,
	`form_TCMServicePlan`.`CaseManagerName`,
	`form_TCMServicePlan`.`CaseManagerSupervisorId`,
	`form_TCMServicePlan`.`CaseManagerSupervisorName`,
	`form_TCMServicePlan`.`date` AS `date`
FROM `form_TCMServicePlan`
	JOIN `patient_data` ON `patient_data`.`pid` = `form_TCMServicePlan`.`pid`
	JOIN `forms` ON `forms`.`formdir` = \'TCMServicePlan\'
		AND `forms`.`form_id` = `form_TCMServicePlan`.`Id`
	JOIN `form_encounter` ON `form_encounter`.`encounter` = `forms`.`encounter`
WHERE
	`form_TCMServicePlan`.`pid` = :patientId
AND `form_TCMServicePlan`.`date` = (SELECT MAX(`date`) FROM `form_TCMServicePlan` 
    WHERE `form_TCMServicePlan`.`pid` = :patientId)
LIMIT 1;',
	// Service Plan Query
		'TCMServicePlan' => 'SELECT
	`form_TCMServicePlan`.`Id`,
	`form_TCMServicePlan`.`TCMAssessmentId` AS `AssessmentId`,
	`form_TCMServicePlan`.`TCMServicePlanId` AS `ServicePlanId`,
	`form_TCMServicePlan`.`Type`,
	`form_TCMServicePlan`.`pid` AS `ClientId`,
	`form_TCMServicePlan`.`ClientName`,
	`patient_data`.`DOB` AS `ClientBirth`,
	`form_TCMServicePlan`.`MedicaidId`,
	`form_TCMServicePlan`.`DateWritten`,
	`form_TCMServicePlan`.`DateComplete`,
	`form_TCMServicePlan`.`user` AS `CaseManagerId`,
	`form_TCMServicePlan`.`CaseManagerName`,
	`form_TCMServicePlan`.`CaseManagerSupervisorId`,
	`form_TCMServicePlan`.`CaseManagerSupervisorName`,
	`form_TCMServicePlan`.`DiagnosisSource`,
	`form_TCMServicePlan`.`CurrentServiceNeeds`,
	`form_TCMServicePlan`.`DischargePlan`,
	`form_TCMServicePlan`.`ManagerNote`,
	`form_TCMServicePlan`.`FinalizedDate`
FROM `form_TCMServicePlan`
	JOIN `patient_data` ON `patient_data`.`pid` = `form_TCMServicePlan`.`pid`
WHERE
	`form_TCMServicePlan`.`Id` = :servicePlanId;',

/* @todo: delete  
	// Service Plan Diagnosis Query
		'TCMSPDiagnosis' => 'SELECT
	`TCMSPDiagnosis`.`Id`,
	`TCMSPDiagnosis`.`ListsId`,
	`TCMSPDiagnosis`.`ICD`,
	`TCMSPDiagnosis`.`Axis`,
	`TCMSPDiagnosis`.`Code`,
	`TCMSPDiagnosis`.`Description`
FROM `TCMSPDiagnosis`
WHERE
	`TCMSPDiagnosis`.`TCMServicePlanId` = :servicePlanId;',
*/

	// Behavioral Health Problems Query
		'BehavioralHealthProblems' => 'SELECT
	`BehavioralHealthProblems`.`Id`,
	`BehavioralHealthProblems`.`Area`,
	`BehavioralHealthProblems`.`AreaId`,
	`BehavioralHealthProblems`.`Problem`,
	`BehavioralHealthProblems`.`Activities`
FROM `BehavioralHealthProblems`
WHERE
	`BehavioralHealthProblems`.`PlanType` = \'TCMServicePlan\'
	AND `BehavioralHealthProblems`.`PlanId` = :planId;',

	// Behavioral Health Goals Query
		'BehavioralHealthGoals' => 'SELECT
	`BehavioralHealthGoals`.`Id`,
	`BehavioralHealthGoals`.`Goal`
FROM `BehavioralHealthGoals`
WHERE
	`BehavioralHealthGoals`.`BehavioralHealthProblemId` = :behavioralHealthProblemId;',

	// Behavioral Health Objectives Query
		'BehavioralHealthObjectives' => 'SELECT
	`BehavioralHealthObjectives`.`Id`,
	`BehavioralHealthObjectives`.`Objective`,
	`BehavioralHealthObjectives`.`TargetDate`,
	`BehavioralHealthObjectives`.`ProgressRate`,
	`BehavioralHealthObjectives`.`Status`
FROM `BehavioralHealthObjectives`
WHERE
	`BehavioralHealthObjectives`.`BehavioralHealthGoalId` = :behavioralHealthGoalId;',

/* @todo: delete 
	// Behavioral Health Agents Query
		'BehavioralHealthAgents' => 'SELECT
	`BehavioralHealthAgents`.`Id`,
	`BehavioralHealthAgents`.`Type`,
	`BehavioralHealthAgents`.`TypeOther`,
	`BehavioralHealthAgents`.`Agency`,
	`BehavioralHealthAgents`.`Agent`
FROM `BehavioralHealthAgents`
WHERE
	`BehavioralHealthAgents`.`BehavioralHealthProblemId` = :behavioralHealthProblemId;',

	// Behavioral Health Agents Types Query
		'BehavioralHealthAgentsTypes' => 'SELECT
	`BehavioralHealthAgentsTypes`.`Id`,
	`BehavioralHealthAgentsTypes`.`Label`,
	`BehavioralHealthAgentsTypes`.`Priority`,
	`BehavioralHealthAgentsTypes`.`Disabled`
FROM `BehavioralHealthAgentsTypes`
WHERE
	`BehavioralHealthAgentsTypes`.`Disabled` = \'0\'
ORDER BY
	`BehavioralHealthAgentsTypes`.`Priority` ASC;',

	// Assessment Functional Types Query
		'TCMAFunctionalTypes' => 'SELECT
	`TCMAFunctionalTypes`.`Id`,
	`TCMAFunctionalTypes`.`Label`,
	`TCMAFunctionalTypes`.`Description`,
	`TCMAFunctionalTypes`.`Priority`,
	`TCMAFunctionalTypes`.`Disabled`
FROM `TCMAFunctionalTypes`
WHERE
	`TCMAFunctionalTypes`.`Disabled` = \'0\'
ORDER BY
	`TCMAFunctionalTypes`.`Priority` ASC;',
*/  
  // Note Query
    'TCMNote' => 'SELECT
      `form_tcmnote`.`Id`,
      `form_tcmnote`.`pid`,
      `form_tcmnote`.`TCMServicePlanId`,
      `form_tcmnote`.`ClientName`,
      `form_tcmnote`.`DateWritten`,
      `form_tcmnote`.`FinalizedDate`,
      `form_tcmnote`.`user`,
      `form_tcmnote`.`CaseManagerName`,
      `form_tcmnote`.`groupname`,
      `form_tcmnote`.`CaseManagerSupervisorId`,
      `form_tcmnote`.`CaseManagerSupervisorName`,
      `form_tcmnote`.`ManagerNote`
FROM form_tcmnote
WHERE `form_tcmnote`.`id` = :noteId',

  // TCM Progress Notes Query, including problem details from BehavioralHealthProblems
    'BehavioralHealthProgressNotesWithProblems' => 'SELECT
      `BehavioralHealthProgressNotes`.`Id`,
      `BehavioralHealthProgressNotes`.`ProblemId`,
      `BehavioralHealthProgressNotes`.`Note`,
      `BehavioralHealthProblems`.`Area`,
      `BehavioralHealthProblems`.`Problem`,
      `BehavioralHealthProblems`.`Activities`      
FROM `BehavioralHealthProgressNotes`
JOIN `BehavioralHealthProblems` 
  ON `BehavioralHealthProblems`.`Id` = `BehavioralHealthProgressNotes`.`ProblemId`
WHERE `BehavioralHealthProgressNotes`.`TCMNoteId` = :noteId',

  // Activities Query
  'BehavioralHealthProgressActivities' => 'SELECT
    `BehavioralHealthProgressActivities`.`Id`,
    `BehavioralHealthProgressActivities`.`ActivityTypeId`,
    `BehavioralHealthProgressActivities`.`StartTime`,
    `BehavioralHealthProgressActivities`.`EndTime`,
    `BehavioralHealthProgressActivities`.`LocationTypeId`,
    `BehavioralHealthProgressActivities`.`ContactTypeId`,
    `BehavioralHealthProgressActivities`.`Description`,
    `TCMNActivityTypes`.`Label` AS `ActivityType`
FROM `BehavioralHealthProgressActivities`
JOIN  `TCMNActivityTypes` 
  ON `TCMNActivityTypes`.`Id` = `BehavioralHealthProgressActivities`.`ActivityTypeId`
WHERE `BehavioralHealthProgressActivities`.`TCMNoteId` = :noteId
ORDER BY
    StartTime',

  // Activity Types Query
	'TCMNActivityTypes' => 'SELECT
	`TCMNActivityTypes`.`Id`,
	`TCMNActivityTypes`.`Label`,
	`TCMNActivityTypes`.`Priority`,
	`TCMNActivityTypes`.`Disabled`
FROM `TCMNActivityTypes`
WHERE
	`TCMNActivityTypes`.`Disabled` = \'0\'
ORDER BY
	`TCMNActivityTypes`.`Priority` ASC;',
  
  // Contact Types Query
	'TCMNContactTypes' => 'SELECT
	`TCMNContactTypes`.`Id`,
	`TCMNContactTypes`.`Label`,
	`TCMNContactTypes`.`Priority`,
	`TCMNContactTypes`.`Disabled`
FROM `TCMNContactTypes`
WHERE
	`TCMNContactTypes`.`Disabled` = \'0\'
ORDER BY
	`TCMNContactTypes`.`Priority` ASC;',  
  
  // Location Types Query
	'TCMNLocationTypes' => 'SELECT
	`TCMNLocationTypes`.`Id`,
	`TCMNLocationTypes`.`Label`,
	`TCMNLocationTypes`.`Priority`,
	`TCMNLocationTypes`.`Disabled`
FROM `TCMNLocationTypes`
WHERE
	`TCMNLocationTypes`.`Disabled` = \'0\'
ORDER BY
	`TCMNLocationTypes`.`Priority` ASC;',    
      
	),

  // CREATE queries

	'CREATE' => array(
  
    'BehavioralHealthProgressNotes' => 'INSERT
INTO `BehavioralHealthProgressNotes`
SET
  `TCMNoteId` = :TCMNoteId,
  `ProblemId` = :ProblemId,
  `Note` = :Note;',
  
    'BehavioralHealthProgressActivities' => 'INSERT
INTO `BehavioralHealthProgressActivities`
SET
  `TCMNoteId` = :TCMNoteId,
  `StartTime` = :StartTime,
  `EndTime` = :EndTime,
  `ActivityTypeId` = :ActivityTypeId,
  `LocationTypeId` = :LocationTypeId,
  `ContactTypeId` = :ContactTypeId,
  `Description` = :Description',
  ),
  

// DELETE Queries
	'DELETE' => array(
	//
		'BehavioralHealthProgressNotes' => 'DELETE
FROM `BehavioralHealthProgressNotes`
WHERE
	`Id` = :Id;',
  
    'BehavioralHealthProgressActivities' => 'DELETE
FROM `BehavioralHealthProgressActivities`
WHERE
  `Id` = :Id;',

	),

// UPDATE Queries
	'UPDATE' => array(
	// 
		'BehavioralHealthProgressNotes' => 'UPDATE `BehavioralHealthProgressNotes`
SET
	`Note` = :Note
WHERE `Id` = :Id;',

  'BehavioralHealthProgressActivities' => 'UPDATE `BehavioralHealthProgressActivities`
SET
  `StartTime` = :StartTime,
  `EndTime` = :EndTime,
  `ActivityTypeId` = :ActivityTypeId,
  `LocationTypeId` = :LocationTypeId,
  `ContactTypeId` = :ContactTypeId,
  `Description` = :Description
WHERE 
  `Id` = :Id',
  
    'TCMNote' => 'UPDATE `form_tcmnote`
SET
  `form_tcmnote`.`ManagerNote` = :ManagerNote
WHERE `Id` = :Id',

 
		'TCMNoteFinalize' => 'UPDATE `form_TCMNote`
SET
	`FinalizedDate` = NOW()
WHERE
	`Id` = :Id
	AND (
		`CaseManagerSupervisorId` = :CaseManagerId
		OR (
			`CaseManagerSupervisorId` IS NULL
			AND `user` = :CaseManagerId
		)
	)
	AND `FinalizedDate` IS NULL;',

	// Service Plan Finalize Query
		'TCMNoteUnfinalize' => 'UPDATE `form_TCMNote`
SET
	`FinalizedDate` = NULL
WHERE
	`Id` = :Id
	AND (
		`CaseManagerSupervisorId` = :CaseManagerId
		OR (
			`CaseManagerSupervisorId` IS NULL
			AND `user` = :CaseManagerId
		)
	)
	AND `FinalizedDate` IS NOT NULL',  

	),
);
