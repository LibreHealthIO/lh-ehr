<?php

return array(
// NEW Query
	'NEW' => array(
		'TCMServicePlan' => 'SELECT
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

	// TCMServicePlans Past Query
		'PastTCMServicePlans' => 'SELECT DISTINCT
	`form_TCMServicePlan`.`Id`,
	`form_TCMServicePlan`.`TCMAssessmentId` AS `AssessmentId`,
	`form_TCMServicePlan`.`Type`,
	`form_TCMServicePlan`.`pid` AS `ClientId`,
	`form_TCMServicePlan`.`ClientName`,
	`patient_data`.`DOB` AS `ClientBirth`,
	`form_TCMServicePlan`.`MedicaidId`,
	`form_TCMServicePlan`.`user` AS `CaseManagerId`,
	`form_TCMServicePlan`.`CaseManagerName`,
	`form_TCMServicePlan`.`CaseManagerSupervisorId`,
	`form_TCMServicePlan`.`CaseManagerSupervisorName`,
	`form_encounter`.`date` AS `ReportDate`
FROM `form_TCMServicePlan`
	JOIN `patient_data` ON `patient_data`.`pid` = `form_TCMServicePlan`.`pid`
	JOIN `forms` ON `forms`.`formdir` = \'TCMServicePlan\'
		AND `forms`.`form_id` = `form_TCMServicePlan`.`Id`
	JOIN `form_encounter` ON `form_encounter`.`encounter` = `forms`.`encounter`
WHERE
	`form_TCMServicePlan`.`pid` = :patientId
ORDER BY
	`form_encounter`.`date` ASC;',

	// TCMAssessments Past Query
		'PastTCMAssessments' => 'SELECT DISTINCT
	`form_TCMAssessment`.`Id`,
	`form_TCMAssessment`.`Type`,
	`form_TCMAssessment`.`pid` AS `ClientId`,
	`form_TCMAssessment`.`ClientName`,
	`patient_data`.`DOB` AS `ClientBirth`,
	`form_TCMAssessment`.`MedicaidId`,
	`form_TCMAssessment`.`user` AS `CaseManagerId`,
	`form_TCMAssessment`.`CaseManagerName`,
	`form_TCMAssessment`.`CaseManagerSupervisorId`,
	`form_TCMAssessment`.`CaseManagerSupervisorName`,
	`form_encounter`.`date` AS `ReportDate`
FROM `form_TCMAssessment`
	JOIN `patient_data` ON `patient_data`.`pid` = `form_TCMAssessment`.`pid`
	JOIN `forms` ON `forms`.`formdir` = \'TCMAssessment\'
		AND `forms`.`form_id` = `form_TCMAssessment`.`Id`
	JOIN `form_encounter` ON `form_encounter`.`encounter` = `forms`.`encounter`
WHERE
	`form_TCMAssessment`.`pid` = :patientId
ORDER BY
	`form_encounter`.`date` ASC;',

	// TCMAssessment Generate From Query
		'GenerateFromTCMAssessment' => 'SELECT
	`TCMAFunctionalTypes`.`Label` AS `Type`,
	`TCMAFunctional`.`TypeId`,
	`TCMAFunctional`.`NeedSummary` AS `Need`,
	`TCMAFunctional`.`ActivityRecommendations` AS `Activity`
FROM `TCMAFunctional`
	JOIN `TCMAFunctionalTypes` ON `TCMAFunctional`.`TypeId` = `TCMAFunctionalTypes`.`Id`
WHERE `TCMAssessmentId` = :assessmentId
	AND `GoalWillingness` =  \'1\'',
	),

// READ Queries
	'READ' => array(
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
	`form_TCMServicePlan`.`DiagnosisCgas`,
	`form_TCMServicePlan`.`DiagnosisAgent`,
	`form_TCMServicePlan`.`CurrentServiceNeeds`,
	`form_TCMServicePlan`.`DischargePlan`,
	`form_TCMServicePlan`.`ManagerNote`,
	`form_TCMServicePlan`.`FinalizedDate`
FROM `form_TCMServicePlan`
	JOIN `patient_data` ON `patient_data`.`pid` = `form_TCMServicePlan`.`pid`
WHERE
	`form_TCMServicePlan`.`Id` = :servicePlanId;',

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
	),

// CREATE Queries
	'CREATE' => array(
	// Service Plan Diagnosis Query
		'TCMSPDiagnosis' => 'INSERT
INTO `TCMSPDiagnosis`
SET
	`TCMServicePlanId` = :TCMServicePlanId,
	`ListsId` = :ListsId,
	`ICD` = :ICD,
	`Axis` = :Axis,
	`Code` = :Code,
	`Description` = :Description;',

	// Behavioral Health Problems Query
		'BehavioralHealthProblems' => 'INSERT
INTO `BehavioralHealthProblems`
SET
	`PlanType` = \'TCMServicePlan\',
	`PlanId` = :TCMServicePlanId,
	`Area` = :Area,
	`AreaId` = :AreaId,
	`Problem` = :Problem,
	`Activities` = :Activities;',

	// Behavioral Health Goals Query
		'BehavioralHealthGoals' => 'INSERT
INTO `BehavioralHealthGoals`
SET
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId,
	`Goal` = :Goal;',

	// Behavioral Health Objectives Query
		'BehavioralHealthObjectives' => 'INSERT
INTO `BehavioralHealthObjectives`
SET
	`BehavioralHealthGoalId` = :BehavioralHealthGoalId,
	`Objective` = :Objective,
	`TargetDate` = :TargetDate,
	`Status` = :Status,
	`ProgressRate` = :ProgressRate;',

	// Behavioral Health Agents Query
		'BehavioralHealthAgents' => 'INSERT
INTO `BehavioralHealthAgents`
SET
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId,
	`Type` = :Type,
	`TypeOther` = :TypeOther,
	`Agency` = :Agency,
	`Agent` = :Agent;',
	),

// DELETE Queries
	'DELETE' => array(
	// Service Plan Diagnosis Query
		'TCMSPDiagnosis' => 'DELETE
FROM `TCMSPDiagnosis`
WHERE
	`TCMServicePlanId` = :TCMServicePlanId
	AND `Id` = :Id;',

	// Behavioral Health Problems Query
		'BehavioralHealthProblems' => 'DELETE
FROM `BehavioralHealthProblems`
WHERE
	`PlanType` = \'TCMServicePlan\'
	AND `PlanId` = :TCMServicePlanId
	AND `Id` = :Id;',

	// Behavioral Health Problems Objectives Query
		'BehavioralHealthProblemsObjectives' => 'DELETE
FROM `BehavioralHealthObjectives`
USING `BehavioralHealthObjectives` INNER JOIN `BehavioralHealthGoals`
WHERE
	`BehavioralHealthGoals`.`Id` = `BehavioralHealthObjectives`.`BehavioralHealthGoalId`
	AND `BehavioralHealthGoals`.`BehavioralHealthProblemId` = :BehavioralHealthProblemId;',

	// Behavioral Health Problems Goals Query
		'BehavioralHealthProblemsGoals' => 'DELETE
FROM `BehavioralHealthGoals`
WHERE
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId;',

	// Behavioral Health Problems Agents Query
		'BehavioralHealthProblemsAgents' => 'DELETE
FROM `BehavioralHealthAgents`
WHERE
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId;',

	// Behavioral Health Goals Query
		'BehavioralHealthGoals' => 'DELETE
FROM `BehavioralHealthGoals`
WHERE
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId
	AND `Id` = :Id;',

	// Behavioral Health Goals Objectives Query
		'BehavioralHealthGoalsObjectives' => 'DELETE
FROM `BehavioralHealthObjectives`
WHERE
	`BehavioralHealthGoalId` = :BehavioralHealthGoalId;',

	// Behavioral Health Objectives Query
		'BehavioralHealthObjectives' => 'DELETE
FROM `BehavioralHealthObjectives`
WHERE
	`BehavioralHealthGoalId` = :BehavioralHealthGoalId
	AND `Id` = :Id;',

	// Behavioral Health Agents Query
		'BehavioralHealthAgents' => 'DELETE
FROM `BehavioralHealthAgents`
WHERE
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId
	AND `Id` = :Id;',
	),

// UPDATE Queries
	'UPDATE' => array(
	// Service Plan Current Service Needs Query
		'TCMServicePlanCurrentServiceNeeds' => 'UPDATE form_TCMServicePlan
SET
	`CurrentServiceNeeds` = :CurrentServiceNeeds
WHERE `Id` = :Id;',

	// Service Plan Query
		'TCMServicePlan' => 'UPDATE `form_TCMServicePlan`
SET
	`DateWritten` = :DateWritten,
	`DateComplete` = :DateComplete,
	`DiagnosisSource` = :DiagnosisSource,
	`DiagnosisCgas` = :DiagnosisCgas,
	`DiagnosisAgent` = :DiagnosisAgent,
	`CurrentServiceNeeds` = :CurrentServiceNeeds,
	`DischargePlan` = :DischargePlan,
	`ManagerNote` = :ManagerNote
WHERE
	`Id` = :Id
	AND `FinalizedDate` IS NULL;',

	// Service Plan Finalize Query
		'TCMServicePlanFinalize' => 'UPDATE `form_TCMServicePlan`
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
		'TCMServicePlanUnfinalize' => 'UPDATE `form_TCMServicePlan`
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

	// Service Plan Diagnosis Query
		'TCMSPDiagnosis' => 'UPDATE `TCMSPDiagnosis`
SET
	`ListsId` = :ListsId,
	`ICD` = :ICD,
	`Axis` = :Axis,
	`Code` = :Code,
	`Description` = :Description
WHERE
	`TCMServicePlanId` = :TCMServicePlanId
	AND `Id` = :Id;',

	// Behavioral Health Problems Query
		'BehavioralHealthProblems' => 'UPDATE `BehavioralHealthProblems`
SET
	`Area` = :Area,
	`AreaId` = :AreaId,
	`Problem` = :Problem,
	`Activities` = :Activities
WHERE
	`PlanType` = \'TCMServicePlan\'
	AND `PlanId` = :TCMServicePlanId
	AND `Id` = :Id;',

	// Behavioral Health Goals Query
		'BehavioralHealthGoals' => 'UPDATE `BehavioralHealthGoals`
SET
	`Goal` = :Goal
WHERE
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId
	AND `Id` = :Id;',

	// Behavioral Health Objectives Query
		'BehavioralHealthObjectives' => 'UPDATE `BehavioralHealthObjectives`
SET
	`Objective` = :Objective,
	`TargetDate` = :TargetDate,
	`Status` = :Status,
	`ProgressRate` = :ProgressRate
WHERE
	`BehavioralHealthGoalId` = :BehavioralHealthGoalId
	AND `Id` = :Id;',

	// Behavioral Health Agents Query
		'BehavioralHealthAgents' => 'UPDATE `BehavioralHealthAgents`
SET
	`Type` = :Type,
	`TypeOther` = :TypeOther,
	`Agency` = :Agency,
	`Agent` = :Agent
WHERE
	`BehavioralHealthProblemId` = :BehavioralHealthProblemId
	AND `Id` = :Id;',
	),
);