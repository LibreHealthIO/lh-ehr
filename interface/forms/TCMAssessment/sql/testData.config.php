<?php

return array (
	'Assessment' => array (
		'Type' => 'INITIAL',
		'CaseManagerId' => 'Sammy',
		'CaseManagerName' => 'Sam Likins ',
		'CaseManagerSupervisorId' => 'Art',
		'CaseManagerSupervisorName' => 'Art Eaton',
		'ClientId' => '10',
		'ClientName' => 'ADRIAN B. BAKER',
		'ClientBirth' => '1940-05-24',
		'MedicaidId' => '186186361',
		'Encounter' => array (
			'ReportDate' => '2013-03-09 00:00:00',
			'ProblemsReason' => 'TCM Assessment Test',
		),
		'Sources' => array (
			array (
				'Type' => 'Form 104A3',
				'Date' => '2013-02-01',
				'Links' => array (
				),
			),
			array (
				'Type' => 'IDC 73B4',
				'Date' => '2013-02-04',
				'Links' => array (
				),
			),
			array (
				'Type' => 'Parent Conferance',
				'Date' => '2013-02-06',
				'Links' => array (
				),
			),
		),
		'FamilyHistory' => 'Test Family History',
		'VocationalHistory' => 'Test Vocational History',
		'LegalDependencyHistory' => 'Test Legal Dependency History',
		'SignificantRelationships' => 'Test Significant Relationships',
		'PotentialStrengths' => array (
			'Client' => 'Test Current Potential Strengths Client',
			'Parent' => 'Test Current Potential Strengths Client Via Parent',
			'Family' => 'Test Current Potential Strengths Family',
		),
		'ResourcesAvailable' => 'Test Resources Available',
		'Safety' => array (
			'Other' => 'Test Safety Assessment Other',
			'Description' => 'Test Safety Assessment Description',
			'Plan' => 'Test Safety Assessment Plan',
			'Types' => array (
				array(
					'TypeId' => '2',
				),
				array(
					'TypeId' => '6',
				),
				array(
					'TypeId' => '8',
				),
				array(
					'TypeId' => '4',
				),
			),
		),
		'TreatmentHistory' => array (
			array (
				'Provider' => 'ABC INC',
				'Dates' => '2012-09-27',
				'Type' => 'Type A',
			),
			array (
				'Provider' => 'XYZ ORG',
				'Dates' => '2013-03-21',
				'Type' => 'Type ZZ',
			),
		),
		'MedicalHistory' => array (
			array (
				'Type' => 'Type F1',
				'Provider' => 'ABC INC',
				'LastExam' => '2012-09-27',
				'Findings' => 'Crainial Distrophy',
				'KnownConditions' => 'Tight-minded',
			),
			array (
				'Type' => 'Type MT',
				'Provider' => 'XYZ ORG',
				'LastExam' => '2013-03-21',
				'Findings' => 'None',
				'KnownConditions' => 'None',
			),
		),
		'Medications' => array (
			array (
				'Name' => 'Asparin',
				'Dosage' => '1 every 6 to 8 hours',
				'Started' => '2012-02-21',
				'Ended' => '',
				'Effects' => 'None',
			),
			array (
				'Name' => 'Clodipan',
				'Dosage' => '1 every 12 hours',
				'Started' => '2012-08-14',
				'Ended' => '2013-01-16',
				'Effects' => 'None',
			),
			array (
				'Name' => 'Sylophil',
				'Dosage' => '2 every 4 hours',
				'Started' => '2012-12-21',
				'Ended' => '',
				'Effects' => 'None',
			),
			array (
				'Name' => 'Mozopan',
				'Dosage' => '1 every 24 hours',
				'Started' => '2013-01-09',
				'Ended' => '2013-03-14',
				'Effects' => 'None',
			),
		),
		'EducationalHistory' => array (
			array (
				'School' => 'Elington Elementery',
				'Grades' => '3-5',
				'Type' => 'EH',
				'Plan' => 'Services',
				'Performance' => 'Average',
				'Behavior' => 'On-Task',
			),
			array (
				'School' => 'Walton Junior High',
				'Grades' => '6-8',
				'Type' => 'SED',
				'Plan' => 'Testing',
				'Performance' => 'Poor',
				'Behavior' => 'Defiant',
			),
			array (
				'School' => 'Salvior High School',
				'Grades' => '9-12',
				'Type' => 'EMH',
				'Plan' => 'SLD',
				'Performance' => 'Needs Improvment',
				'Behavior' => 'Disruptive',
			),
		),
		'SubstanceAbuse' => array (
			array (
				'Substance' => 'Alcohol',
				'Frequency' => '2-3 16oz bottles per day',
			),
			array (
				'Substance' => 'Coffee',
				'Frequency' => '6-10 12oz cups per day',
			),
		),
		'CurrentServices' => array (
			array(
				'Provider' => 'Provider A',
				'DateBegin' => '2012-06-19',
				'Type' => '0',
				'TypeOther' => 'Other A',
				'Effectiveness' => 'Efft X',
			),
			array(
				'Provider' => 'Provider Z',
				'DateBegin' => '2012-03-12',
				'Type' => '0',
				'TypeOther' => 'Other 1',
				'Effectiveness' => 'Efft G',
			),
			array(
				'Provider' => 'Provider C',
				'DateBegin' => '2012-03-12',
				'Type' => '2',
				'Effectiveness' => 'Efft Z',
			),
		),
		'Functional' => array (
			array(
				'TypeId' => '2',
				'ServiceNeeds' => 'some',
				'Willingness' => false,
			),
			array(
				'TypeId' => '3',
				'ServiceNeeds' => 'immediate',
				'Willingness' => true,
			),
			array(
				'TypeId' => '4',
				'ServiceNeeds' => 'no',
				'Willingness' => false,
			),
		),
	),
);