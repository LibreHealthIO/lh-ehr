Documentation References:
	libreehr	Directory the LibreHealthEHR root is in
	$srcdir		Path to libreehr/library directory

	Information on:
	- globals.php
		- Location: libreehr/interface/globals.php
		- Include: include('../../globals.php');
	- api.inc
		- Location: libreehr/library/api.inc
		- Include: include($srcdir.'/api.inc');
	- auth.inc
		- Location: libreehr/library/auth.inc
		- Include: include($srcdir.'/auth.inc');
	- formdata.inc.php
		- Location: libreehr/library/formdata.inc.php
		- Include: include($srcdir.'/formdata.inc.php');
	- htmlspecialchars.inc.php
		- Location: libreehr/library/htmlspecialchars.inc.php
		- Include: include($srcdir.'/htmlspecialchars.inc.php');
	- patient.inc
		- Location: libreehr/library/patient.inc
		- Include: include($srcdir.'/patient.inc');
	- sql.inc
		- Location: libreehr/library/sql.inc
		- Include: include($srcdir.'/sql.inc');



### FORM FIELD NAMEs & TABLE FIELDs ###

Table: form_TCMAssessment
assessment-id										Id
assessment-type										Type
assessment-case-manager-id							user
assessment-case-manager-name						CaseManagerName
assessment-supervisor-id							CaseManagerSupervisorId
assessment-supervisor-name							CaseManagerSupervisorName
assessment-admit-date								AdmitDate
assessment-visit-dates								HomeVisitDates
client-id											pid
client-name											ClientName
client-medicaid-id									MedicaidId
personal-family-history								FamilyHistory
substance-abuse-no-known							SubstanceAbuseNonKnown
substance-abuse-not-applicable						!! REMOVE THIS FIELD !!
vocational-history									VocationalHistory
legal-dependency-history							LegalDependencyHistory
significant-relationships							SignificantRelationships
potential-strengths-client-report					CurrentPotentialStrengthsClient
potential-strengths-parent-report					CurrentPotentialStrengthsClientViaParent
potential-strengths-family-strength					CurrentPotentialStrengthsFamily
resources-available									ResourcesAvailable
safety-assessment-other								SafetyAssessmentOther
safety-assessment-description						SafetyAssessmentDescription
safety-assessment-plan								SafetyAssessmentPlan

Table: patient_data
client-birth										DOB

Table: form_encounter
assessment-report-date								date
presenting-reason									reason

Table: TCMASourcesLink
personal-family-source								TCMAssessmentField
treatment-history-source							TCMAssessmentField
medical-history-source								TCMAssessmentField
current-medications-source							TCMAssessmentField
past-medications-source								TCMAssessmentField
educational-history-source							TCMAssessmentField
substance-abuse-source								TCMAssessmentField
vocational-history-source							TCMAssessmentField
legal-dependency-history-source						TCMAssessmnetField

Table: TCMASources
sources-id-#										Id
sources-type-#										Type
sources-date-#										Date

Table: TCMATreatmentHistory
treatment-history-id-#								Id
treatment-history-provider-#						Provider
treatment-history-dates-#							Dates
treatment-history-type-#							Type

Table: TCMAMedicalHistory
medical-history-id-#								Id
medical-history-type-#								Type
medical-history-provider-#							Provider
medical-history-last-exam-#							LastExam
medical-history-findings-#							Findings
medical-history-conditions-#						KnownConditions

Table: ?? ??
current-medications-medication-#					
current-medications-dosage-#						
current-medications-started-#						
current-medications-effects-#						

Table: ?? ??
past-medications-medication-#						
past-medications-dosage-#							
past-medications-started-#							
past-medications-effects-#							

Table: TCMAEducationHistory
educational-history-id-#							Id
educational-history-school-#						School
educational-history-grades-#						Grades
educational-history-type-#							Type
educational-history-plan-#							IEP504Plan
educational-history-performance-#					Performance
educational-history-behavior-#						Behavior

Table: TCMASubstanceAbuse
substance-abuse-id-#								Id
substance-abuse-substance-#							Substance
substance-abuse-frequency-#							Frequency

Table: TCMASafetyTypes
safety-assessment-type-id-#							Id
safety-assessment-type-label-#						Label

Table: TCMASafety
safety-assessment-type-#							TypeId

Table: TCMACurrentServicesTypes
current-services-type-id-#							Id
current-services-type-label-#						Label

Table: TCMACurrentServices
current-services-id-#								Id
current-services-provider-#							Provider
current-services-begin-#							DateBegin
current-services-type-#								TypeId
current-services-effectiveness-#					Effectiveness

Table: TCMAFunctionalTypes
functional-assessment-type-id-#						Id
functional-assessment-type-label-#					Label
functional-assessment-type-description-#			Description

Table: TCMAFunctional
functional-assessment-id-#							Id
functional-assessment-type-#						TypeId
functional-assessment-service-needs-#				ServiceNeeds
functional-assessment-willingness-#					GoalWillingness
functional-assessment-need-summary-#				NeedSummary
functional-assessment-activity-recommendations-#	ActivityRecommendations



### Assessment Data Types ###

SafetyAssessmentTypes[]
	Id
	Label
	Priority
	Disabled
CurrentServicesTypes[]
	Id
	Label
	Priority
	Disabled
FunctionalAssessmentTypes[]
	Id
	Label
	Description
	Priority
	Disabled


### Assessment Data Set ###

Id
Type
CaseManagerId
CaseManagerName
CaseManagerSupervisorId
CaseManagerSupervisorName
ClientId
ClientName
ClientBirth
MedicaidId
AdmitDate
HomeVisitDates
Encounter
	ReportDate
	ProblemsReason
Sources[]
	Id
	Type
	Date
	Links[]
		Id
		Field
FamilyHistory
MedicalHistory[]
	Id
	Type
	Provider
	LastExam
	Findings
	KnownConditions
Medications[]
	Id

TreatmentHistory[]
	Id
	Provider
	Dates
	Type
EducationalHistory[]
	Id
	School
	Grades
	Type
	Plan
	Performance
	Behavior
SubstanceAbuse[]
	Id
	Substance
	Frequency
VocationalHistory
LegalDependencyHistory
SignificantRelationships
PotentialStrengths
	Client
	Parent
	Family
ResourcesAvailable
SafetyAssessment
	Other
	Description
	Plan
	Types[]
		Id
		TypeId		{ SafetyAssessmentTypes.Id }
CurrentServices[]
	Id
	Provider
	DateBegin
	TypeId			{ CurrentServicesType.Id }
	Effectiveness
FunctionalAssessment[]
	Id
	TypeId			{ FunctionalAssessmentType.Id }
	ServiceNeeds
	Willingness
	NeedSummary
	ActivityRecommendations
