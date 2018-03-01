<?php

/**
 * Description of TCMServicePlan
 *
 * @author Sam Likins
 */
class TCMServicePlan {

	public $formDirectory;
	public $sqlStatements;

	public $rootDirectory;
	public $encounterId;
	public $patientId;

	public $caseManagerId;

	public $servicePlanId;
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
			throw new \UnexpectedValueException('Provided fileNamePlan must be a string.');

		$fileName = $this->formDirectory.DIRECTORY_SEPARATOR.$fileName;
		if(!is_readable($fileName))
			throw new \InvalidArgumentException('Provided fileName is missing or not readable.');

		if(is_array($dataArray))
			extract($dataArray, $extractType, $prefix);

		return include($fileName);
	}

	public function loadSqlStatements($force = false) {
		if($this->sqlStatements === null || $force)
			$this->sqlStatements = $this->loadExternal('sql/servicePlan.config.php');
	}

	public function filterData($data, $keys, $map = false) {
		$data = array_intersect_key((array) $data, array_flip($keys));

		if($map === true) {
			$mapped = array();
			foreach($keys as $to => $from)
				if(array_key_exists($from, $data))
					$mapped[is_int($to) ? $from : $to] = $data[$from];
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

		// sqlFetchArray from library/sql.inc
		return sqlFetchArray($statment);
	}

	public function sqlFetchMultiArray($query, $fields = null) {
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

	// Stop-gap solution, bad form (dosn't work when two forms are being saved)!
//	public function getInsertId($table) {
//		$id = $this->sqlFetchArray(
//'SELECT `AUTO_INCREMENT`
//FROM `information_schema`.`TABLES`
//WHERE `information_schema`.`TABLES`.`TABLE_SCHEMA` = :databaseName
//	AND `information_schema`.`TABLES`.`TABLE_NAME` = :tableName;',
//			array(
//				'databaseName' => $GLOBALS['sqlconf']['dbase'],
//				'tableName' => $table,
//			)
//		);
//		return $id['AUTO_INCREMENT'];
//	}

	public function sqlNewServicePlan() {
		if(!is_numeric($this->patientId))
			throw new \UnexpectedValueException('Patient Id must be an integer.');
		if(!is_numeric($this->caseManagerId))
			throw new \UnexpectedValueException('Case Manager Id must be an integer.');

		$this->loadSqlStatements();

		$this->formData['ServicePlan'] = $this->sqlFetchArray(
			$this->sqlStatements['NEW']['TCMServicePlan'],
			array(
				'patientId' => $this->patientId,
				'caseManagerId' => $this->caseManagerId,
			)
		);
		$this->formData['ServicePlans'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['NEW']['PastTCMServicePlans'],
			array(
				'patientId' => $this->patientId,
			)
		);
		$this->formData['Assessments'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['NEW']['PastTCMAssessments'],
			array(
				'patientId' => $this->patientId,
			)
		);
	}

	public function sqlViewServicePlan() {
		if(!is_numeric($this->servicePlanId))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		$this->loadSqlStatements();

		$this->formData['User'] = $this->caseManager;
		$this->formData['ServicePlan'] = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMServicePlan'],
			array(
				'servicePlanId' => $this->servicePlanId,
			)
		);

		$formData =& $this->formData['ServicePlan'];

		$formData['Diagnosis'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMSPDiagnosis'],
			array(
				'servicePlanId' => $this->servicePlanId,
			)
		);

		$formData['Problems'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthProblems'],
			array(
				'planId' => $this->servicePlanId,
			)
		);

		foreach($formData['Problems'] as &$problem) {
			$problem['Goals'] = $this->sqlFetchMultiArray(
				$this->sqlStatements['READ']['BehavioralHealthGoals'],
				array(
					'behavioralHealthProblemId' => $problem['Id'],
				)
			);

			foreach($problem['Goals'] as &$goals) {
				$goals['Objectives'] = $this->sqlFetchMultiArray(
					$this->sqlStatements['READ']['BehavioralHealthObjectives'],
					array(
						'behavioralHealthGoalId' => $goals['Id'],
					)
				);
			}

			$problem['Agents'] = $this->sqlFetchMultiArray(
				$this->sqlStatements['READ']['BehavioralHealthAgents'],
				array(
					'behavioralHealthProblemId' => $problem['Id'],
				)
			);
		}

		$this->formData['FunctionalTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMAFunctionalTypes']
		);

		$this->formData['AgentsTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthAgentsTypes']
		);

	}

	public function sqlReportServicePlan() {
		if(!is_numeric($this->servicePlanId))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		$this->loadSqlStatements();

		$servicePlan = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMServicePlan'],
			array(
				'servicePlanId' => $this->servicePlanId,
			)
		);

		$this->formData['ServicePlan'] = $this->filterData(
			$servicePlan,
			array(
				'Id',
				'Type',
				'CaseManagerName',
				'CaseManagerSupervisorName',
				'DateWritten',
				'DateComplete',
				'FinalizedDate',
			)
		);
	}

	public function saveNewServicePlan($userAuthorized = 0) {
		// formSubmit from library/api.inc
		$this->servicePlanId = formSubmit(
			'form_TCMServicePlan',
			$this->filterData(
				$this->formData,
				array(
					'TCMAssessmentId' => 'Assessment',
					'TCMServicePlanId' => 'ServicePlan',
					'CaseManagerName',
					'CaseManagerSupervisorId',
					'CaseManagerSupervisorName',
					'ClientName',
					'MedicaidId',
					'Type',
				),
				true
			) + array(
				'DischargePlan' => '1.The service plan will be reviewed with the client/family for current status of each objective, referrals/resources provided and need for any additional services on a monthly basis.
2. '
			),
			null,
			$userAuthorized
		);

		addForm(
			$this->encounterId,
			'Targeted Case Management Service Plan',
			$this->servicePlanId,
			'TCMServicePlan',
			$this->patientId,
			$userAuthorized
		);

		if(property_exists($this->formData, 'ServicePlan')
			&& $this->formData->ServicePlan > 0
		)
			$this->generateFromServicePlan($this->formData->ServicePlan);
		elseif(property_exists($this->formData, 'Assessment')
			&& $this->formData->Assessment > 0
		)
			$this->generateFromAssessment($this->formData->Assessment);

		return $this->servicePlanId;
	}

	public function generateFromAssessment($assessmentId) {
		if(!is_numeric($assessmentId))
			throw new \UnexpectedValueException('Assessment Id must be an integer.');

		$this->loadSqlStatements();

		$problems = $this->sqlFetchMultiArray(
			$this->sqlStatements['NEW']['GenerateFromTCMAssessment'],
			array(
				'assessmentId' => $assessmentId,
			)
		);

		foreach($problems as $problem) {
			$this->sqlStatement(
				$this->sqlStatements['CREATE']['BehavioralHealthProblems'],
				array(
					'TCMServicePlanId' => $this->servicePlanId,
					'Area' => $problem['Type'],
					'AreaId' => $problem['TypeId'],
					'Problem' => $problem['Need'],
					'Activities' => $problem['Activity'],
				)
			);
			$currentServiceNeeds[] = $problem['Type'].PHP_EOL.$problem['Need'];
		}

		$this->sqlStatement(
			$this->sqlStatements['UPDATE']['TCMServicePlanCurrentServiceNeeds'],
			array(
				'Id' => $this->servicePlanId,
				'CurrentServiceNeeds' => implode(PHP_EOL, $currentServiceNeeds),
			)
		);
	}

	public function generateFromServicePlan($servicePlanId) {
		// Store Created Service Plan Id
		$newServicePlanId = $this->servicePlanId;
		// Store Sent Data
		$newServicePlan = (array) $this->formData;
		$this->formData = array();

		// Set Referanced Service Plan Id
		$this->servicePlanId = $servicePlanId;
		// Retrieve Referanced Service Plan Data
		$this->sqlViewServicePlan();

		// Overwrite Referanced Data with Created Data
		$this->formData = array_merge($newServicePlan, $this->formData['ServicePlan']);

		// Force Full Array to Objects
		$this->formData = json_decode(json_encode($this->formData));
		$this->formData->Id = $newServicePlanId;

		// Remove old record Id
		foreach($this->formData->Diagnosis as &$diagnosis)
			$diagnosis->Id = null;

		foreach($this->formData->Problems as &$problem) {
			$problem->Id = null;

			foreach($problem->Goals as &$goal) {
				$goal->Id = null;

				foreach($goal->Objectives as &$objective)
					$objective->Id = null;
			}

			foreach($problem->Agents as &$agent)
				$agent->Id = null;
		}

		// Restore Created Service Plan Id
		$this->servicePlanId = $newServicePlanId;

		// Save Referanced Service Plan Data with New Service Plan
		$this->sqlSaveServicePlan();
	}

	public function sqlSaveServicePlan() {
		if(!is_numeric($this->servicePlanId))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		$this->loadSqlStatements();

		$servicePlan = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMServicePlan'],
			array(
				'servicePlanId' => $this->servicePlanId,
			)
		);

		if($servicePlan['FinalizedDate'])
			throw new \UnexpectedValueException('Form has already been finalized.');

		$this->sqlStatement(
			$this->sqlStatements['UPDATE']['TCMServicePlan'],
			array(
				'Id' => $this->formData->Id,
				'DateWritten' => $this->formData->DateWritten,
				'DateComplete' => $this->formData->DateComplete,
				'DiagnosisSource' => $this->formData->DiagnosisSource,
				'DiagnosisCgas' => $this->formData->DiagnosisCgas,
				'DiagnosisAgent' => $this->formData->DiagnosisAgent,
				'CurrentServiceNeeds' => $this->formData->CurrentServiceNeeds,
				'DischargePlan' => $this->formData->DischargePlan,
				'ManagerNote' => $this->formData->ManagerNote,
			)
		);
		$this->sqlSaveDiagnosis();
		$this->sqlSaveProblems();
	}

	public function sqlSaveDiagnosis() {
		if(property_exists($this->formData, 'Diagnosis')
			&& is_array($this->formData->Diagnosis)
		) {
			foreach($this->formData->Diagnosis as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatements['CREATE']['TCMSPDiagnosis'],
						array(
							'TCMServicePlanId' => $this->formData->Id,
							'ListsId' => $record->ListsId,
							'ICD' => $record->ICD,
							'Axis' => $record->Axis,
							'Code' => $record->Code,
							'Description' => $record->Description,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['TCMSPDiagnosis'],
						array(
							'Id' => $record->Id,
							'TCMServicePlanId' => $this->formData->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatements['UPDATE']['TCMSPDiagnosis'],
						array(
							'Id' => $record->Id,
							'TCMServicePlanId' => $this->formData->Id,
							'ListsId' => $record->ListsId,
							'ICD' => $record->ICD,
							'Axis' => $record->Axis,
							'Code' => $record->Code,
							'Description' => $record->Description,
						)
					);
				}
			}
		}
	}

	public function sqlSaveProblems() {
		if(property_exists($this->formData, 'Problems')
			&& is_array($this->formData->Problems)
		) {
			foreach($this->formData->Problems as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatements['CREATE']['BehavioralHealthProblems'],
						array(
							'TCMServicePlanId' => $this->formData->Id,
							'Area' => $record->Area,
							'AreaId' => $record->AreaId,
							'Problem' => $record->Problem,
							'Activities' => $record->Activities,
						)
					);
					$record->Id = $this->getMysqlInsertId();
//					$record->Id = $this->getInsertId('BehavioralHealthProblems');
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthProblems'],
						array(
							'Id' => $record->Id,
							'TCMServicePlanId' => $this->formData->Id,
						)
					);
					// Remove All Problems Objectives
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthProblemsObjectives'],
						array(
							'BehavioralHealthProblemId' => $record->Id,
						)
					);
					// Remove All Problems Goals
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthProblemsGoals'],
						array(
							'BehavioralHealthProblemId' => $record->Id,
						)
					);
					unset($record->Goals);
					// Remove All Problems Agents
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthProblemsAgents'],
						array(
							'BehavioralHealthProblemId' => $record->Id,
						)
					);
					unset($record->Agents);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatements['UPDATE']['BehavioralHealthProblems'],
						array(
							'Id' => $record->Id,
							'TCMServicePlanId' => $this->formData->Id,
							'Area' => $record->Area,
							'AreaId' => $record->AreaId,
							'Problem' => $record->Problem,
							'Activities' => $record->Activities,
						)
					);
				}
				$this->sqlSaveProblemsGoals($record);
				$this->sqlSaveProblemsAgents($record);
			}
		}
	}

	public function sqlSaveProblemsGoals($problem) {
		if(property_exists($problem, 'Goals')
			&& is_array($problem->Goals)
		) {
			foreach($problem->Goals as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatements['CREATE']['BehavioralHealthGoals'],
						array(
							'BehavioralHealthProblemId' => $problem->Id,
							'Goal' => $record->Goal,
						)
					);
					$record->Id = $this->getMysqlInsertId();
//					$problem->Id = $this->getInsertId('BehavioralHealthGoals');
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthGoals'],
						array(
							'Id' => $record->Id,
							'BehavioralHealthProblemId' => $problem->Id,
						)
					);
					// Remove All Problems Goals Objectives
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthGoalsObjectives'],
						array(
							'BehavioralHealthGoalId' => $record->Id,
						)
					);
					unset($record->Objectives);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatements['UPDATE']['BehavioralHealthGoals'],
						array(
							'Id' => $record->Id,
							'BehavioralHealthProblemId' => $problem->Id,
							'Goal' => $record->Goal,
						)
					);
				}
				$this->sqlSaveProblemsGoalsObjectives($record);
			}
		}
	}

	public function sqlSaveProblemsGoalsObjectives($goal) {
		if(property_exists($goal, 'Objectives')
			&& is_array($goal->Objectives)
		) {
			foreach($goal->Objectives as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatements['CREATE']['BehavioralHealthObjectives'],
						array(
							'BehavioralHealthGoalId' => $goal->Id,
							'Objective' => $record->Objective,
							'TargetDate' => $record->TargetDate,
							'Status' => $record->Status,
							'ProgressRate' => $record->ProgressRate,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthObjectives'],
						array(
							'Id' => $record->Id,
							'BehavioralHealthGoalId' => $goal->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatements['UPDATE']['BehavioralHealthObjectives'],
						array(
							'Id' => $record->Id,
							'BehavioralHealthGoalId' => $goal->Id,
							'Objective' => $record->Objective,
							'TargetDate' => $record->TargetDate,
							'Status' => $record->Status,
							'ProgressRate' => $record->ProgressRate,
						)
					);
				}
			}
		}
	}

	public function sqlSaveProblemsAgents($problem) {
		if(property_exists($problem, 'Agents')
			&& is_array($problem->Agents)
		) {
			foreach($problem->Agents as $record) {
				if(!(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatements['CREATE']['BehavioralHealthAgents'],
						array(
							'BehavioralHealthProblemId' => $problem->Id,
							'Type' => $record->Type,
							'TypeOther' => $record->TypeOther,
							'Agency' => $record->Agency,
							'Agent' => $record->Agent,
						)
					);
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthAgents'],
						array(
							'Id' => $record->Id,
							'BehavioralHealthProblemId' => $problem->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatements['UPDATE']['BehavioralHealthAgents'],
						array(
							'Id' => $record->Id,
							'BehavioralHealthProblemId' => $problem->Id,
							'Type' => $record->Type,
							'TypeOther' => $record->TypeOther,
							'Agency' => $record->Agency,
							'Agent' => $record->Agent,
						)
					);
				}
			}
		}
	}

	public function checkSaveServicePlan() {
		if(!property_exists($this->formData, 'Id'))
			throw new \UnexpectedValueException('Service Plan Id is missing from save request.');

		if(!is_numeric($this->formData->Id))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		if($this->formData->Id != $this->servicePlanId)
			throw new \UnexpectedValueException('Service Plan Id is invalid.');
	}

	public function saveViewServicePlan() {
		$this->checkSaveServicePlan();
		$this->sqlSaveServicePlan();
	}

	public function finalizeServicePlan() {
		if(!is_numeric($this->servicePlanId))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		$this->loadSqlStatements();

		$this->sqlStatement(
			$this->sqlStatements['UPDATE']['TCMServicePlanFinalize'],
			array(
				'Id' => $this->servicePlanId,
				'CaseManagerId' => $this->caseManager,
			)
		);

		$this->formData = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMServicePlan'],
			array(
				'servicePlanId' => $this->servicePlanId,
			)
		);
	}

	public function unfinalizeServicePlan() {
		if(!is_numeric($this->servicePlanId))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		$this->loadSqlStatements();

		$this->sqlStatement(
			$this->sqlStatements['UPDATE']['TCMServicePlanUnfinalize'],
			array(
				'Id' => $this->servicePlanId,
				'CaseManagerId' => $this->caseManager,
			)
		);

		$this->formData = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMServicePlan'],
			array(
				'servicePlanId' => $this->servicePlanId,
			)
		);
	}

	public function displayNewServicePlan() {
		$this->loadExternal('templates/new.phtml');
	}

	public function displayViewServicePlan() {
		$this->loadExternal('templates/view.phtml');
	}

	public function displayReportServicePlan() {
		$this->loadExternal('templates/report.phtml');
	}

	public function displayFullReportServicePlan() {
		$this->loadExternal('templates/full-report.phtml');
	}

}