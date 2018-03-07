<?php

/**
 * Description of TCMAssessment
 *
 * @author Sam Likins
 */
class TCMAssessment {

	public $formDirectory;
	public $sqlStatments;

	public $rootDirectory;
	public $encounterId;
	public $patientId;

	public $caseManagerId;

	public $assessmentId;
	public $formData;
	public $formErrors = array();

	public function __construct($formDirectory) {
		global $rootdir, $encounter, $pid;

		$this->formDirectory = $formDirectory;

		$this->rootDirectory = $rootdir;
		$this->encounterId = $encounter;
		$this->patientId = $pid;
		$this->caseManager = $_SESSION['authUser'];
		$this->caseManagerId = $_SESSION['authUserID'];
		
	}

	public function loadExternal($fileName, $dataArray = null, $extractType = EXTR_OVERWRITE, $prefix = null) {
		if(!is_string($fileName))
			throw new \UnexpectedValueException('Provided fileName must be a string.');
		
		$fileName = $this->formDirectory.DIRECTORY_SEPARATOR.$fileName;
		if(!is_readable($fileName))
			throw new \InvalidArgumentException('Provided fileName is missing or not readable.');

		if(is_array($dataArray))
			extract($dataArray, $extractType, $prefix);
			
		return include($fileName);
	}

	public function loadSqlStatements($force = false) {
		if($this->sqlStatments === null || $force)
			$this->sqlStatments = $this->loadExternal('sql/assessment.config.php');
	}

	public function filterData($data, $keys, $map = false) {
		$data = array_intersect_key($data, array_flip($keys));

		if($map === true) {
			$mapped = array();
			foreach($keys as $to => $from)
				if(array_key_exists($from, $data))
					$mapped[$to] = $data[$from];
		} else {
			$mapped = $data;
		}

		return $mapped;
	}

	protected function reverseKeySort($a, $b) {
		$alen = strlen($a); $blen = strlen($b);
		return ($alen === $blen ? 0 : ($alen < $blen ? 1 : -1));
	}

	public function sqlStatement($query, $fields = null) {
		if(is_array($fields)) {
			uksort($fields, array($this, 'reverseKeySort'));

			foreach($fields as $fieldName => $fieldValue) {
				if($fieldValue === null)
					$fieldValue = 'NULL';
				elseif(!is_numeric($fieldValue))
					$fieldValue = '\''.mysql_real_escape_string($fieldValue).'\'';
				$query = str_replace(':'.$fieldName, $fieldValue, $query);
			}
		}

		// sqlStatementNoLog from library/sql.inc
		return sqlStatementNoLog($query);
	}

	public function sqlFetchArray($query, $fields = null) {
		$statment = $this->sqlStatement($query, $fields);

		return sqlFetchArray($statment);
	}

	public function sqlFetchMultiArray($query, $fields = null) {
		// sqlStatement from library/sql.inc
		$statment = $this->sqlStatement($query, $fields);
		$resultSet = array();

		// sqlFetchArray from library/sql.inc
		while($result = sqlFetchArray($statment))
			$resultSet[] = $result;

		return $resultSet;
	}

	// This is how it's done threwout the system (doesn't work)!
	public function getMysqlInsertId() {
//		if($GLOBALS['lastidado'] > 0)
//			return $GLOBALS['lastidado'];
//		else
			return mysql_insert_id($GLOBALS['dbh']);
	}

	public function sqlNewAssessment() {
		if(!is_numeric($this->patientId))
			throw new \UnexpectedValueException('Patient Id must be an integer.');
		if(!is_numeric($this->caseManagerId))
			throw new \UnexpectedValueException('Case Manager Id must be an integer.');

		$this->loadSqlStatements();
		// sqlFetchArray and sqlStatement from library/sql.inc
		$this->formData = $this->sqlFetchArray(
			$this->sqlStatments['NEW'],
			array(
				'patientId' => $this->patientId,
				'caseManagerId' => $this->caseManagerId,
			)
		);
	}

	public function sqlTypes() {
		$this->loadSqlStatements();

		// sqlFetchArray and sqlStatement from library/sql.inc
		$this->formData['SafetyTypes'] = $this->sqlFetchMultiArray($this->sqlStatments['READ']['TCMASafetyTypes']);
		$this->formData['CurrentServicesTypes'] = $this->sqlFetchMultiArray($this->sqlStatments['READ']['TCMACurrentServicesTypes']);
		$this->formData['FunctionalTypes'] = $this->sqlFetchMultiArray($this->sqlStatments['READ']['TCMAFunctionalTypes']);
	}

	public function sqlViewAssessment() {
		if(!is_numeric($this->assessmentId))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		$this->loadSqlStatements();

		$assessment = $this->sqlFetchArray(
			$this->sqlStatments['READ']['TCMAssessment'],
			array(
				'encounterId' => $this->encounterId,
				'assessmentId' => $this->assessmentId,
			)
		);

		$this->formData['User'] = $this->caseManager;
		$this->formData['Assessment'] = $this->filterData(
			$assessment,
			array(
				'Id',
				'Type',
				'CaseManagerId',
				'CaseManagerName',
				'CaseManagerSupervisorId',
				'CaseManagerSupervisorName',
				'ClientId',
				'ClientName',
				'ClientBirth',
				'MedicaidId',
				'AdmitDate',
				'HomeVisitDates',
				'ManagerNote',
				'MedicalHistoryNotes',
				'CaseManagerSignatureDate',
				'CaseManagerSupervisorSignatureDate',
				'TreatmentHistoryNotes',
				'MedicationsCurrentNotes',
				'MedicationsPastNotes',
				'EducationalHistoryNotes',
				'SubstanceAbuseNotes',
				'CurrentCircumstances',
			)
		);
		
		$formData =& $this->formData['Assessment'];
		
		$formData['Encounter'] = $this->filterData(
			$assessment,
			array(
				'ReportDate',
				'ProblemsReason',
			)
		);

		$formData['Sources'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMASources'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		foreach($formData['Sources'] as &$source) {
			$source['Links'] = $this->sqlFetchMultiArray(
				$this->sqlStatments['READ']['TCMASourcesLink'],
				array(
					'sourceId' => $source['Id'],
				)
			);
		}

		$formData['FamilyHistory'] = $assessment['FamilyHistory'];

		$formData['VocationalHistory'] = $assessment['VocationalHistory'];

		$formData['LegalDependencyHistory'] = $assessment['LegalDependencyHistory'];

		$formData['SignificantRelationships'] = $assessment['SignificantRelationships'];

		$formData['PotentialStrengths'] = $this->filterData(
			$assessment,
			array(
				'Client' => 'PotentialStrengthsClient',
				'Parent' => 'PotentialStrengthsParent',
				'Family' => 'PotentialStrengthsFamily',
			),
			true
		);

		$formData['ResourcesAvailable'] = $assessment['ResourcesAvailable'];

		$formData['Safety'] = $this->filterData(
			$assessment,
			array(
				'Other' => 'SafetyAssessmentOther',
				'Description' => 'SafetyAssessmentDescription',
				'Plan' => 'SafetyAssessmentPlan',
			),
			true
		);

		$formData['Safety']['Types'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMASafety'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		$formData['TreatmentHistory'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMATreatmentHistory'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		$formData['MedicalHistory'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMAMedicalHistory'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		$formData['Medications'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['Medications'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		$formData['EducationalHistory'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMAEducationalHistory'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		$formData['SubstanceAbuse'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMASubstanceAbuse'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		$formData['CurrentServices'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMACurrentServices'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		$formData['Functional'] = $this->sqlFetchMultiArray(
			$this->sqlStatments['READ']['TCMAFunctional'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);
		
	}

	public function sqlReportAssessment() {
		if(!is_numeric($this->assessmentId))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		$this->loadSqlStatements();

		$assessment = $this->sqlFetchArray(
			$this->sqlStatments['READ']['TCMAssessment'],
			array(
				'encounterId' => $this->encounterId,
				'assessmentId' => $this->assessmentId,
			)
		);

		$this->formData['Assessment'] = $this->filterData(
			$assessment,
			array(
				'Id',
				'Type',
				'CaseManagerId',
				'CaseManagerName',
				'CaseManagerSupervisorId',
				'CaseManagerSupervisorName',
				'ClientId',
				'ClientName',
				'ClientBirth',
				'MedicaidId',
				'AdmitDate',
				'HomeVisitDates',
			)
		);
		$this->formData['Assessment']['Encounter'] = $this->filterData(
			$assessment,
			array(
				'ReportDate',
				'ProblemsReason',
			)
		);

	}

	public function saveNewAssessment($userAuthorized = 0) {
		// formSubmit from library/api.inc
		$recordId = formSubmit(
			'form_TCMAssessment',
			$this->filterData(
				$this->formData,
				array(
					'CaseManagerName',
					'CaseManagerSupervisorId',
					'CaseManagerSupervisorName',
					'ClientName',
					'MedicaidId',
					'Type',
				)
			),
			null,
			$userAuthorized
		);

		addForm(
			$this->encounterId,
			'Targeted Case Management Assessment',
			$recordId,
			'TCMAssessment',
			$this->patientId,
			$userAuthorized
		);

		return $recordId;
	}

	public function sqlSaveAssessment() {
		if(!is_numeric($this->assessmentId))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		$this->loadSqlStatements();

		$signatures = $this->sqlFetchArray(
			$this->sqlStatments['READ']['TCMAssessmentSignatures'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);

		if($signatures['CaseManagerSupervisorSignatureDate'])
			throw new \UnexpectedValueException('Case Manager & Supervisor have already signed the Assessment.');
		elseif($signatures['CaseManagerSignatureDate'])
			throw new \UnexpectedValueException('Case Manager has already signed the Assessment.');

		$this->sqlStatement(
			$this->sqlStatments['UPDATE']['TCMAssessment'],
			array(
				'Id' => $this->formData->Id,
				'AdmitDate' => $this->formData->AdmitDate,
				'HomeVisitDates' => $this->formData->HomeVisitDates,
				'ManagerNote' => $this->formData->ManagerNote,
				'FamilyHistory' => $this->formData->FamilyHistory,
				'VocationalHistory' => $this->formData->VocationalHistory,
				'LegalDependencyHistory' => $this->formData->LegalDependencyHistory,
				'SignificantRelationships' => $this->formData->SignificantRelationships,
				'PotentialStrengthsClient' => $this->formData->PotentialStrengths->Client,
				'PotentialStrengthsParent' => $this->formData->PotentialStrengths->Parent,
				'PotentialStrengthsFamily' => $this->formData->PotentialStrengths->Family,
				'ResourcesAvailable' => $this->formData->ResourcesAvailable,
				'SafetyAssessmentOther' => $this->formData->Safety->Other,
				'SafetyAssessmentDescription' => $this->formData->Safety->Description,
				'SafetyAssessmentPlan' => $this->formData->Safety->Plan,
				'MedicalHistoryNotes' => $this->formData->MedicalHistoryNotes,
				// wkr20170529
				'TreatmentHistoryNotes' => $this->formData->TreatmentHistoryNotes,
				'MedicationsCurrentNotes' => $this->formData->MedicationsCurrentNotes,
				'MedicationsPastNotes' => $this->formData->MedicationsPastNotes,
				'EducationalHistoryNotes' => $this->formData->EducationalHistoryNotes,
				'SubstanceAbuseNotes' => $this->formData->SubstanceAbuseNotes,
				'CurrentCircumstances' => $this->formData->CurrentCircumstances,
			)
		);
		$this->sqlSaveEncounter();
		$this->sqlSaveCurrentServices();
		$this->sqlSaveEducationalHistory();
		$this->sqlSaveFunctional();
		$this->sqlSaveMedicalHistory();
		$this->sqlSaveMedications();
		$this->sqlSaveSafety();
		$this->sqlSaveSources();
		$this->sqlSaveSubstanceAbuse();
		$this->sqlSaveTreatmentHistory();
	}

	public function sqlSaveEncounter() {
		if(property_exists($this->formData, 'Encounter')
			&& is_object($this->formData->Encounter)
		) {
			$this->sqlStatement(
				$this->sqlStatments['UPDATE']['FormEncounter'], 
				array(
					'EncounterId' => $this->encounterId,
					'ProblemReason' => $this->formData->Encounter->ProblemReason,
				)
			);
		}
	}

	public function sqlSaveCurrentServices() {
		if(property_exists($this->formData, 'CurrentServices')
			&& is_array($this->formData->CurrentServices)
		) {
			foreach($this->formData->CurrentServices as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMACurrentServices'],
						array(
							'TCMAssessmentId' => $this->formData->Id,
							'Provider' => $record->Provider,
							'DateBegin' => $record->DateBegin,
							'TypeId' => $record->Type,
							'TypeOther' => $record->TypeOther,
							'Effectiveness' => $record->Effectiveness,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMACurrentServices'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMACurrentServices'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'Provider' => $record->Provider,
							'DateBegin' => $record->DateBegin,
							'TypeId' => $record->Type,
							'TypeOther' => $record->TypeOther,
							'Effectiveness' => $record->Effectiveness,
						)
					);
				}
			}
		}
	}

	public function sqlSaveEducationalHistory() {
		if(property_exists($this->formData, 'EducationalHistory')
			&& is_array($this->formData->EducationalHistory)
		) {
			foreach($this->formData->EducationalHistory as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMAEducationalHistory'],
						array(
							'TCMAssessmentId' => $this->formData->Id,
							'School' => $record->School,
							'Grades' => $record->Grades,
							'Type' => $record->Type,
							'Plan' => $record->Plan,
							'Performance' => $record->Performance,
							'Behavior' => $record->Behavior,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMAEducationalHistory'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMAEducationalHistory'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'School' => $record->School,
							'Grades' => $record->Grades,
							'Type' => $record->Type,
							'Plan' => $record->Plan,
							'Performance' => $record->Performance,
							'Behavior' => $record->Behavior,
						)
					);
				}
			}
		}
	}

	public function sqlSaveFunctional() {
		if(property_exists($this->formData, 'Functional')
			&& is_array($this->formData->Functional)
		) {
			foreach($this->formData->Functional as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMAFunctional'],
						array(
							'TCMAssessmentId' => $this->formData->Id,
							'TypeId' => $record->TypeId,
							'ServiceNeeds' => $record->ServiceNeeds,
							'Willingness' => $record->Willingness,
							'NeedSummary' => $record->NeedSummary,
							'ActivityRecommendations' => $record->ActivityRecommendations,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMAFunctional'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMAFunctional'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'TypeId' => $record->TypeId,
							'ServiceNeeds' => $record->ServiceNeeds,
							'Willingness' => $record->Willingness,
							'NeedSummary' => $record->NeedSummary,
							'ActivityRecommendations' => $record->ActivityRecommendations,
						)
					);
				}
			}
		}
	}

	public function sqlSaveMedicalHistory() {
		if(property_exists($this->formData, 'MedicalHistory')
			&& is_array($this->formData->MedicalHistory)
		) {
			foreach($this->formData->MedicalHistory as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMAMedicalHistory'],
						array(
							'TCMAssessmentId' => $this->formData->Id,
							'Type' => $record->Type,
							'Provider' => $record->Provider,
							'LastExam' => $record->LastExam,
							'Findings' => $record->Findings,
							'KnownConditions' => $record->KnownConditions,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMAMedicalHistory'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMAMedicalHistory'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'Type' => $record->Type,
							'Provider' => $record->Provider,
							'LastExam' => $record->LastExam,
							'Findings' => $record->Findings,
							'KnownConditions' => $record->KnownConditions,
						)
					);
				}
			}
		}
	}

	public function sqlSaveMedications() {
		if(property_exists($this->formData, 'Medications')
			&& is_array($this->formData->Medications)
		) {
			foreach($this->formData->Medications as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['Medications'],
						array(
							'ClientId' => $this->formData->ClientId,
							'Date' => $record->Date,
							'Title' => $record->Title,
							'BeginDate' => $record->BeginDate,
							'EndDate' => $record->EndDate,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['Medications'],
						array(
							'ClientId' => $this->formData->ClientId,
							'Id' => $record->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['Medications'],
						array(
							'Id' => $record->Id,
							'ClientId' => $this->formData->ClientId,
							'Date' => $record->Date,
							'Title' => $record->Title,
							'BeginDate' => $record->BeginDate,
							'EndDate' => $record->EndDate,
						)
					);
				}
			}
		}
	}

	public function sqlSaveSafety() {
		if(property_exists($this->formData, 'Safety')
			&& property_exists($this->formData->Safety, 'Types')
			&& is_array($this->formData->Safety->Types)
		) {
			foreach($this->formData->Safety->Types as $record) {
				if(!(property_exists($record, 'Id')
					&& $record->Id > 0
				)) {
					// CREATE
					if($record->Selected === true) {
						$this->sqlStatement(
							$this->sqlStatments['CREATE']['TCMASafety'],
							array(
								'TCMAssessmentId' => $this->formData->Id,
								'TypeId' => $record->TypeId,
							)
						);
					}
				} elseif(property_exists($record, '_destroy')
					 && $record->_destroy === true
				) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMASafety'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMASafety'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'TypeId' => $record->TypeId,
						)
					);
				}
			}
		}
	}

	public function sqlSaveSources() {
		if(property_exists($this->formData, 'Sources')
			&& is_array($this->formData->Sources)
		) {
			foreach($this->formData->Sources as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMASources'],
						array(
							'TCMAssessmentId' => $this->formData->Id,
							'Type' => $record->Type,
							'Date' => $record->Date,
						)
					);
					$record->Id = $this->getMysqlInsertId();
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMASources'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
					// Remove All Sources Links
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMASourcesLinks'],
						array(
							'TCMSourcesId' => $record->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMASources'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'Type' => $record->Type,
							'Date' => $record->Date,
						)
					);
				}
				$this->sqlSaveSourcesLinks($record);
			}
		}
	}

	public function sqlSaveSourcesLinks($source) {
		if(property_exists($source, 'Links')
			&& is_array($source->Links)
		) {
			foreach($source->Links as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMASourcesLink'],
						array(
							'TCMASourcesId' => $source->Id,
							'Field' => $record->Field,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMASourcesLink'],
						array(
							'Id' => $record->Id,
							'TCMASourcesId' => $source->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMASourcesLink'],
						array(
							'Id' => $record->Id,
							'TCMASourcesId' => $source->Id,
							'Field' => $record->Field,
						)
					);
				}
			}
		}
	}

	public function sqlSaveSubstanceAbuse() {
		if(property_exists($this->formData, 'SubstanceAbuse')
			&& is_array($this->formData->SubstanceAbuse)
		) {
			foreach($this->formData->SubstanceAbuse as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMASubstanceAbuse'],
						array(
							'TCMAssessmentId' => $this->formData->Id,
							'Substance' => $record->Substance,
							'Frequency' => $record->Frequency,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMASubstanceAbuse'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMASubstanceAbuse'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'Substance' => $record->Substance,
							'Frequency' => $record->Frequency,
						)
					);
				}
			}
		}
	}

	public function sqlSaveTreatmentHistory() {
		if(property_exists($this->formData, 'TreatmentHistory')
			&& is_array($this->formData->TreatmentHistory)
		) {
			foreach($this->formData->TreatmentHistory as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatments['CREATE']['TCMATreatmentHistory'],
						array(
							'TCMAssessmentId' => $this->formData->Id,
							'Provider' => $record->Provider,
							'Dates' => $record->Dates,
							'Type' => $record->Type,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatments['DELETE']['TCMATreatmentHistory'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatments['UPDATE']['TCMATreatmentHistory'],
						array(
							'Id' => $record->Id,
							'TCMAssessmentId' => $this->formData->Id,
							'Provider' => $record->Provider,
							'Dates' => $record->Dates,
							'Type' => $record->Type,
						)
					);
				}
			}
		}
	}

	public function checkSaveAssessment() {
		if(!property_exists($this->formData, 'Id'))
			throw new \UnexpectedValueException('Assessment Id is missing from save request.');

		if(!is_numeric($this->formData->Id))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		if($this->formData->Id != $this->assessmentId)
			throw new \UnexpectedValueException('Assessment Id is invalid.');
	}

	public function saveViewAssessment() {
		$this->checkSaveAssessment();
		$this->sqlSaveAssessment();
	}

	public function signAssessmentManager() {
		if(!is_numeric($this->assessmentId))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		$this->loadSqlStatements();

		$this->sqlStatement(
			$this->sqlStatments['UPDATE']['TCMAssessmentSignatureCaseManager'],
			array(
				'Id' => $this->assessmentId,
				'CaseManagerId' => $this->caseManager,
			)
		);

		$this->formData = $this->sqlFetchArray(
			$this->sqlStatments['READ']['TCMAssessmentSignatures'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);
	}

	public function signAssessmentSupervisor() {
		if(!is_numeric($this->assessmentId))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		$this->loadSqlStatements();

		$this->sqlStatement(
			$this->sqlStatments['UPDATE']['TCMAssessmentSignatureCaseManagerSupervisor'],
			array(
				'Id' => $this->assessmentId,
				'CaseManagerSupervisorId' => $this->caseManager,
			)
		);

		$this->formData = $this->sqlFetchArray(
			$this->sqlStatments['READ']['TCMAssessmentSignatures'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);
	}

	public function signAssessmentRevert() {
		if(!is_numeric($this->assessmentId))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		$this->loadSqlStatements();

		$this->sqlStatement(
			$this->sqlStatments['UPDATE']['TCMAssessmentSignatureRevert'],
			array(
				'Id' => $this->assessmentId,
				'CaseManagerSupervisorId' => $this->caseManager,
			)
		);

		$this->formData = $this->sqlFetchArray(
			$this->sqlStatments['READ']['TCMAssessmentSignatures'],
			array(
				'assessmentId' => $this->assessmentId,
			)
		);
	}

	public function displayNewAssessment() {
		$this->loadExternal('templates/new.phtml');
	}

	public function displayViewAssessment() {
		$this->loadExternal('templates/view.phtml');
	}

	public function displayReportAssessment() {
		$this->loadExternal('templates/report.phtml');
	}

	public function displayFullReportAssessment() {
		$this->loadExternal('templates/full-report.phtml');
	}

}