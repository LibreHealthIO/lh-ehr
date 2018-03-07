<?php

return array(
// NEW Query
	'NEW' => 'SELECT
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

// READ Queries
	'READ' => array(
	// Assessment Query
		'TCMAssessment' => 'SELECT
	`form_TCMAssessment`.`Id`,
	`form_TCMAssessment`.`Type`,
	`form_TCMAssessment`.`user` AS `CaseManagerId`,
	`form_TCMAssessment`.`CaseManagerName`,
	`form_TCMAssessment`.`CaseManagerSupervisorId`,
	`form_TCMAssessment`.`CaseManagerSupervisorName`,
	`form_TCMAssessment`.`pid` AS `ClientId`,
	`form_TCMAssessment`.`ClientName`,
	`patient_data`.`DOB` AS `ClientBirth`,
	`form_TCMAssessment`.`MedicaidId`,
	`form_TCMAssessment`.`AdmitDate`,
	`form_TCMAssessment`.`HomeVisitDates`,
	`form_encounter`.`date` AS `ReportDate`,
	`form_encounter`.`reason` AS `ProblemsReason`,
	`form_TCMAssessment`.`FamilyHistory`,
	`form_TCMAssessment`.`VocationalHistory`,
	`form_TCMAssessment`.`LegalDependencyHistory`,
	`form_TCMAssessment`.`SignificantRelationships`,
	`form_TCMAssessment`.`CurrentPotentialStrengthsClient` AS `PotentialStrengthsClient`,
	`form_TCMAssessment`.`CurrentPotentialStrengthsClientViaParent` AS `PotentialStrengthsParent`,
	`form_TCMAssessment`.`CurrentPotentialStrengthsFamily` AS `PotentialStrengthsFamily`,
	`form_TCMAssessment`.`ResourcesAvailable`,
	`form_TCMAssessment`.`SafetyAssessmentOther`,
	`form_TCMAssessment`.`SafetyAssessmentDescription`,
	`form_TCMAssessment`.`SafetyAssessmentPlan`,
	`form_TCMAssessment`.`MedicalHistoryNotes`,
	`form_TCMAssessment`.`ManagerNote`,
	`form_TCMAssessment`.`CaseManagerSignatureDate`,
	`form_TCMAssessment`.`CaseManagerSupervisorSignatureDate`,
	`form_TCMAssessment`.`TreatmentHistoryNotes`,
	`form_TCMAssessment`.`MedicationsCurrentNotes`,
	`form_TCMAssessment`.`MedicationsPastNotes`,
	`form_TCMAssessment`.`EducationalHistoryNotes`,
	`form_TCMAssessment`.`SubstanceAbuseNotes`,
	`form_TCMAssessment`.`CurrentCircumstances`
FROM `form_TCMAssessment`
	JOIN `patient_data` ON `patient_data`.`pid` = `form_TCMAssessment`.`pid`
	JOIN `form_encounter` ON `form_encounter`.`encounter` = :encounterId
WHERE
	`form_TCMAssessment`.`id` = :assessmentId;',

	// Assessment Signatures
		'TCMAssessmentSignatures' => 'SELECT
	`form_TCMAssessment`.`user` AS `CaseManagerId`,
	`form_TCMAssessment`.`CaseManagerSignatureDate`,
	`form_TCMAssessment`.`CaseManagerSupervisorId`,
	`form_TCMAssessment`.`CaseManagerSupervisorSignatureDate`
FROM  `form_TCMAssessment`
WHERE
	`Id` = :assessmentId;',

	// Assessment Sources Query
		'TCMASources' => 'SELECT
	`TCMASources`.`Id`,
	`TCMASources`.`Type`,
	`TCMASources`.`Date`
FROM `TCMASources`
WHERE
	`TCMASources`.`TCMAssessmentId` = :assessmentId;',

	// Assessment Sources Link Query
		'TCMASourcesLink' => 'SELECT
	`TCMASourcesLink`.`Id`,
	`TCMASourcesLink`.`TCMAssessmentField` AS `Field`
FROM `TCMASourcesLink`
WHERE
	`TCMASourcesLink`.`TCMASourcesId` = :sourceId;',

	// Assessment Treatment History Query
		'TCMATreatmentHistory' => 'SELECT
	`TCMATreatmentHistory`.`Id`,
	`TCMATreatmentHistory`.`Provider`,
	`TCMATreatmentHistory`.`Dates`,
	`TCMATreatmentHistory`.`Type`
FROM `TCMATreatmentHistory`
WHERE
	`TCMATreatmentHistory`.`TCMAssessmentId` = :assessmentId;',

	// Assessment Medical History Query
		'TCMAMedicalHistory' => 'SELECT
	`TCMAMedicalHistory`.`Id`,
	`TCMAMedicalHistory`.`Type`,
	`TCMAMedicalHistory`.`Provider`,
	`TCMAMedicalHistory`.`LastExam`,
	`TCMAMedicalHistory`.`Findings`,
	`TCMAMedicalHistory`.`KnownConditions`
FROM `TCMAMedicalHistory`
WHERE
	`TCMAMedicalHistory`.`TCMAssessmentId` = :assessmentId;',

	// Medications Query
		'Medications' => 'SELECT
	`lists`.`id` AS `Id`,
	`lists`.`date` AS `Date`,
	`lists`.`title` AS `Title`,
	`lists`.`begdate` AS `BeginDate`,
	`lists`.`enddate` AS `EndDate`,
	`lists`.`activity` AS `Activity`
FROM `form_TCMAssessment`
	JOIN `lists` ON `lists`.`pid` = `form_TCMAssessment`.`pid`
WHERE
	`lists`.`type` = \'medication\'
	AND `form_TCMAssessment`.`Id` = :assessmentId;',

	// Assessment Education History Query
		'TCMAEducationalHistory' => 'SELECT
	`TCMAEducationalHistory`.`Id`,
	`TCMAEducationalHistory`.`School`,
	`TCMAEducationalHistory`.`Grades`,
	`TCMAEducationalHistory`.`Type`,
	`TCMAEducationalHistory`.`IEP504Plan` AS `Plan`,
	`TCMAEducationalHistory`.`Performance`,
	`TCMAEducationalHistory`.`Behavior`
FROM `TCMAEducationalHistory`
WHERE
	`TCMAEducationalHistory`.`TCMAssessmentId` = :assessmentId;',

	// Assessment Substance Abuse Query
		'TCMASubstanceAbuse' => 'SELECT
	`TCMASubstanceAbuse`.`Id`,
	`TCMASubstanceAbuse`.`Substance`,
	`TCMASubstanceAbuse`.`Frequency`
FROM `TCMASubstanceAbuse`
WHERE
	`TCMASubstanceAbuse`.`TCMAssessmentId` = :assessmentId;',

	// Assessment Safety Query
		'TCMASafety' => 'SELECT
	`TCMASafety`.`Id`,
	`TCMASafety`.`TypeId`
FROM `TCMASafety`
WHERE
	`TCMASafety`.`TCMAssessmentId` = :assessmentId;',

	// Assessment Safety Types Query
		'TCMASafetyTypes' => 'SELECT
	`TCMASafetyTypes`.`Id`,
	`TCMASafetyTypes`.`Label`,
	`TCMASafetyTypes`.`Priority`,
	`TCMASafetyTypes`.`Disabled`
FROM `TCMASafetyTypes`
WHERE
	`TCMASafetyTypes`.`Disabled` = \'0\'
ORDER BY
	`TCMASafetyTypes`.`Priority` ASC;',

	// Assessment Current Services Query
		'TCMACurrentServices' => 'SELECT
	`TCMACurrentServices`.`Id`,
	`TCMACurrentServices`.`Provider`,
	`TCMACurrentServices`.`DateBegin`,
	`TCMACurrentServices`.`TypeId` as `Type`,
	`TCMACurrentServices`.`TypeOther`,
	`TCMACurrentServices`.`Effectiveness`
FROM `TCMACurrentServices`
WHERE
	`TCMACurrentServices`.`TCMAssessmentId` = :assessmentId;',

	// Assessment Current Services Types Query
		'TCMACurrentServicesTypes' => 'SELECT
	`TCMACurrentServicesTypes`.`Id`,
	`TCMACurrentServicesTypes`.`Label`,
	`TCMACurrentServicesTypes`.`Priority`,
	`TCMACurrentServicesTypes`.`Disabled`
FROM `TCMACurrentServicesTypes`
WHERE
	`TCMACurrentServicesTypes`.`Disabled` = \'0\'
ORDER BY
	`TCMACurrentServicesTypes`.`Priority` ASC;',

	// Assessment Functional Query
		'TCMAFunctional' => 'SELECT
	`TCMAFunctional`.`Id`,
	`TCMAFunctional`.`TypeId`,
	`TCMAFunctional`.`ServiceNeeds`,
	`TCMAFunctional`.`GoalWillingness` AS `Willingness`,
	`TCMAFunctional`.`NeedSummary`,
	`TCMAFunctional`.`ActivityRecommendations`
FROM `TCMAFunctional`
WHERE
	`TCMAFunctional`.`TCMAssessmentId` = :assessmentId;',

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
	// Assessment Sources Query
		'TCMASources' => 'INSERT
INTO `TCMASources`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`Type` = :Type,
	`Date` = :Date;',

	// Assessment Sources Link Query
		'TCMASourcesLink' => 'INSERT
INTO `TCMASourcesLink`
SET
	`TCMASourcesId` = :TCMASourcesId,
	`TCMAssessmentField` = :Field;',

	// Assessment Treatment History Query
		'TCMATreatmentHistory' => 'INSERT
INTO `TCMATreatmentHistory`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`Provider` = :Provider,
	`Dates` = :Dates,
	`Type` = :Type;',

	// Assessment Medical History Query
		'TCMAMedicalHistory' => 'INSERT
INTO `TCMAMedicalHistory`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`Type` = :Type,
	`Provider` = :Provider,
	`LastExam` = :LastExam,
	`Findings` = :Findings,
	`KnownConditions` = :KnownConditions;',

	// Assessment Medications Query
		'Medications' => 'INSERT
INTO `lists`
SET
	`activity` = 1,
	`type` = \'medication\',
	`pid` = :ClientId,
	`date` = NOW(),
	`title` = :Title,
	`begdate` = :BeginDate,
	`enddate` = :EndDate;',

	// Assessment Education History Query
		'TCMAEducationalHistory' => 'INSERT
INTO `TCMAEducationalHistory`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`School` = :School,
	`Grades` = :Grades,
	`Type` = :Type,
	`IEP504Plan` = :Plan,
	`Performance` = :Performance,
	`Behavior` = :Behavior;',

	// Assessment Substance Abuse Query
		'TCMASubstanceAbuse' => 'INSERT
INTO `TCMASubstanceAbuse`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`Substance` = :Substance,
	`Frequency` = :Frequency;',

	// Assessment Safety Query
		'TCMASafety' => 'INSERT
INTO `TCMASafety`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`TypeId` = :TypeId;',

	// Assessment Current Services Query
		'TCMACurrentServices' => 'INSERT
INTO `TCMACurrentServices`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`Provider` = :Provider,
	`DateBegin` = :DateBegin,
	`TypeId` = :TypeId,
	`TypeOther` = :TypeOther,
	`Effectiveness` = :Effectiveness;',

	// Assessment Functional Query
		'TCMAFunctional' => 'INSERT
INTO `TCMAFunctional`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`TypeId` = :TypeId,
	`ServiceNeeds` = :ServiceNeeds,
	`GoalWillingness` = :Willingness,
	`NeedSummary` = :NeedSummary,
	`ActivityRecommendations` = :ActivityRecommendations;',
	),

// DELETE Queries
	'DELETE' => array(
	// Assessment Sources Query
		'TCMASources' => 'DELETE
FROM `TCMASources`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Sources Link Query
		'TCMASourcesLinks' => 'DELETE
FROM `TCMASourcesLink`
WHERE
	`TCMSourcesId` = :TCMSourcesId;',

	// Assessment Sources Link Query
		'TCMASourcesLink' => 'DELETE
FROM `TCMASourcesLink`
WHERE
	`TCMASourcesId` = :TCMASourcesId
	AND `Id` = :Id;',

	// Assessment Treatment History Query
		'TCMATreatmentHistory' => 'DELETE
FROM `TCMATreatmentHistory`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Medical History Query
		'TCMAMedicalHistory' => 'DELETE
FROM `TCMAMedicalHistory`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Medical History Query
		'Medications' => 'DELETE
FROM `lists`
WHERE
	`type` = \'medication\'
	AND `pid` = :ClientId
	AND `id` = :Id;',

	// Assessment Education History Query
		'TCMAEducationalHistory' => 'DELETE
FROM `TCMAEducationalHistory`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Substance Abuse Query
		'TCMASubstanceAbuse' => 'DELETE
FROM `TCMASubstanceAbuse`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Safety Query
		'TCMASafety' => 'DELETE
FROM `TCMASafety`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Current Services Query
		'TCMACurrentServices' => 'DELETE
FROM `TCMACurrentServices`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Functional Query
		'TCMAFunctional' => 'DELETE
FROM `TCMAFunctional`
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',
	),

// UPDATE Queries
	'UPDATE' => array(
	// Assessment Query
		'TCMAssessment' => 'UPDATE `form_TCMAssessment`
SET
	`AdmitDate` = :AdmitDate,
	`HomeVisitDates` = :HomeVisitDates,
	`FamilyHistory` = :FamilyHistory,
	`VocationalHistory` = :VocationalHistory,
	`LegalDependencyHistory` = :LegalDependencyHistory,
	`SignificantRelationships` = :SignificantRelationships,
	`CurrentPotentialStrengthsClient` = :PotentialStrengthsClient,
	`CurrentPotentialStrengthsClientViaParent` = :PotentialStrengthsParent,
	`CurrentPotentialStrengthsFamily` = :PotentialStrengthsFamily,
	`ResourcesAvailable` = :ResourcesAvailable,
	`SafetyAssessmentOther` = :SafetyAssessmentOther,
	`SafetyAssessmentDescription` = :SafetyAssessmentDescription,
	`SafetyAssessmentPlan` = :SafetyAssessmentPlan,
	`MedicalHistoryNotes` = :MedicalHistoryNotes,
	`ManagerNote` = :ManagerNote,
	`TreatmentHistoryNotes` = :TreatmentHistoryNotes,
	`MedicationsCurrentNotes` = :MedicationsCurrentNotes,
	`MedicationsPastNotes` = :MedicationsPastNotes,
	`EducationalHistoryNotes` = :EducationalHistoryNotes,
	`SubstanceAbuseNotes` = :SubstanceAbuseNotes,
	`CurrentCircumstances` = :CurrentCircumstances
WHERE
	`Id` = :Id
	AND `CaseManagerSignatureDate` IS NULL
	AND `CaseManagerSupervisorSignatureDate` IS NULL;',

	// Assessment Signature - Case Manager
		'TCMAssessmentSignatureCaseManager' => 'UPDATE `form_TCMAssessment`
SET
	`CaseManagerSignatureDate` = NOW()
WHERE
	`Id` = :Id
	AND `user` = :CaseManagerId
	AND `CaseManagerSignatureDate` IS NULL;',

	// Assessment Signature - Case Manager Supervisor
		'TCMAssessmentSignatureCaseManagerSupervisor' => 'UPDATE `form_TCMAssessment`
SET
	`CaseManagerSupervisorSignatureDate` = NOW()
WHERE
	`Id` = :Id
	AND `CaseManagerSupervisorId` = :CaseManagerSupervisorId
	AND `CaseManagerSignatureDate` IS NOT NULL
	AND `CaseManagerSupervisorSignatureDate` IS NULL;',

	// Assessment Signature - Revert
		'TCMAssessmentSignatureRevert' => 'UPDATE `form_TCMAssessment`
SET
	`CaseManagerSignatureDate` = NULL,
	`CaseManagerSupervisorSignatureDate` = NULL
WHERE
	`Id` = :Id
	AND `CaseManagerSupervisorId` = :CaseManagerSupervisorId;',

	// Form Encounter Query
		'FormEncounter' => 'UPDATE `form_encounter`
SET
	`date` = NOW(),
	`reason` = :ProblemReason
WHERE
	`encounter` = :EncounterId;',

	// Assessment Sources Query
		'TCMASources' => 'UPDATE `TCMASources`
SET
	`Type` = :Type,
	`Date` = :Date
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Sources Link Query
		'TCMASourcesLink' => 'UPDATE `TCMASourcesLink`
SET
	`TCMAssessmentField` = :Field
WHERE
	`TCMASourcesId` = :TCMASourcesId
	AND `Id` = :Id;',

	// Assessment Treatment History Query
		'TCMATreatmentHistory' => 'UPDATE `TCMATreatmentHistory`
SET
	`Provider` = :Provider,
	`Dates` = :Dates,
	`Type` = :Type
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Medical History Query
		'TCMAMedicalHistory' => 'UPDATE `TCMAMedicalHistory`
SET
	`Type` = :Type,
	`Provider` = :Provider,
	`LastExam` = :LastExam,
	`Findings` = :Findings,
	`KnownConditions` = :KnownConditions
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Medications Query
		'Medications' => 'UPDATE `lists`
SET
	`date` = :Date,
	`title` = :Title,
	`begdate` = :BeginDate,
	`enddate` = :EndDate
WHERE
	`pid` = :ClientId
	AND `id` = :Id;',

	// Assessment Education History Query
		'TCMAEducationalHistory' => 'UPDATE `TCMAEducationalHistory`
SET
	`School` = :School,
	`Grades` = :Grades,
	`Type` = :Type,
	`IEP504Plan` = :Plan,
	`Performance` = :Performance,
	`Behavior` = :Behavior
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Substance Abuse Query
		'TCMASubstanceAbuse' => 'UPDATE `TCMASubstanceAbuse`
SET
	`Substance` = :Substance,
	`Frequency` = :Frequency
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Safety Query
		'TCMASafety' => 'UPDATE `TCMASafety`
SET
	`TypeId` = :TypeId
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Current Services Query
		'TCMACurrentServices' => 'UPDATE `TCMACurrentServices`
SET
	`Provider` = :Provider,
	`DateBegin` = :DateBegin,
	`TypeId` = :TypeId,
	`TypeOther` = :TypeOther,
	`Effectiveness` = :Effectiveness
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',

	// Assessment Functional Query
		'TCMAFunctional' => 'UPDATE `TCMAFunctional`
SET
	`TCMAssessmentId` = :TCMAssessmentId,
	`TypeId` = :TypeId,
	`ServiceNeeds` = :ServiceNeeds,
	`GoalWillingness` = :Willingness,
	`NeedSummary` = :NeedSummary,
	`ActivityRecommendations` = :ActivityRecommendations
WHERE
	`TCMAssessmentId` = :TCMAssessmentId
	AND `Id` = :Id;',
	),
);