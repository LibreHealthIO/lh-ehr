<?php

/**
 * Description of TCMNote
 *
 * @author Wayne Robinson, per Sam Likins' example
 */
class TCMNote {

	public $formDirectory;
	public $sqlStatements;

	public $rootDirectory;
	public $encounterId;
	public $patientId;

	public $caseManagerId;

	public $noteId;
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
		if($this->sqlStatements === null || $force)
			$this->sqlStatements = $this->loadExternal('sql/note.config.php');
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
		$statement = $this->sqlStatement($query, $fields);

		// sqlFetchArray from library/sql.inc
		return sqlFetchArray($statement);
	}

	public function sqlFetchMultiArray($query, $fields = null) {
		$statement = $this->sqlStatement($query, $fields);
		$resultSet = array();
    
		// sqlFetchArray from library/sql.inc
		while($result = sqlFetchArray($statement))
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


  protected function fetchProblemsSubinfo(&$problemArray, $idFieldName) {
  
    // Fetch info that's subordinate to a problem (can be on current notes or from referenced service plan)

    foreach($problemArray as &$problem) {
      $problem['Goals'] = $this->sqlFetchMultiArray(
        $this->sqlStatements['READ']['BehavioralHealthGoals'],
        array(
          'behavioralHealthProblemId' => $problem[$idFieldName],
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

/* @todo: delete
      $problem['Agents'] = $this->sqlFetchMultiArray(
        $this->sqlStatements['READ']['BehavioralHealthAgents'],
        array(
          'behavioralHealthProblemId' => $problem['Id'],
        )
      );
*/      
    }
  }  


	public function sqlNewNote() {
		if(!is_numeric($this->patientId))
			throw new \UnexpectedValueException('Patient Id must be an integer.');
		if(!is_numeric($this->caseManagerId))
			throw new \UnexpectedValueException('Case Manager Id must be an integer.');
      
		$this->loadSqlStatements();
    
    $newNote = $this->sqlFetchArray(
			$this->sqlStatements['NEW']['TCMNote'],
			array(
				'patientId' => $this->patientId,
				'caseManagerId' => $this->caseManagerId,
			)
		);
    
    $servicePlan = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMServicePlanMostRecentFinalized'],
			array(
				'patientId' => $this->patientId,
			)
		);
		
/* Removed: don't use data from non-finalized service plan.  wkr20170526 @todo delete *
    if(empty($servicePlan)) {  // no finalized plan, use most recent unfinalized
      $servicePlan = $this->sqlFetchArray(
        $this->sqlStatements['READ']['TCMServicePlanMostRecent'],
        array(
          'patientId' => $this->patientId,
        )
      );
    }	
/* */

// error_log( "servicePlan = " . print_r($servicePlan, true));		

	
	if( $servicePlan['ClientName'] != '') {
		$patName = $servicePlan['ClientName'];
error_log(" USING NAME FROM SERVICE PLAN ");		
	} else {
		$pn = getPatientData($this->patientId, "fname,lname");
		$patName = $pn['fname'] . " " . $pn['lname'];
error_log(" USING PATIENT DATA NAME ");		
	}

    // Create prototype Note from Service Plan info
	$this->formData['Note']['pid'] = $this->patientId;
	$this->formData['Note']['TCMServicePlanId'] = $servicePlan['Id'];
	$this->formData['Note']['TCMServicePlanFinalizedDate'] = $servicePlan['FinalizedDate'];
	$this->formData['Note']['ClientName'] = $patName;
    $this->formData['Note']['DateWritten'] = $servicePlan['date'];   
    $this->formData['Note']['CaseManagerId'] = $this->caseManagerId;
    $this->formData['Note']['CaseManagerName'] = $newNote['CaseManagerName'];
    $this->formData['Note']['CaseManagerSupervisorId'] = $newNote['CaseManagerSupervisorId'];
    $this->formData['Note']['CaseManagerSupervisorName'] = $newNote['CaseManagerSupervisorName'];
    
    // Populate empty arrays to keep our view happy (allows more UI code reuse between new.phtml and view.phtml)
    $this->formData['Note']['Problems'] =  array();
    $this->formData['Note']['Activities'] = array();
    $this->formData['ServicePlanProblems'] =  array();
    
		$this->formData['ServicePlanProblems'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthProblems'],
			array(
        'planId' => $servicePlan['Id']
      )
		);  
    $this->fetchProblemsSubinfo($this->formData['ServicePlanProblems'], 'Id');

/* @todo: delete
		$this->formData['FunctionalTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMAFunctionalTypes']
		);

		$this->formData['AgentsTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthAgentsTypes']
		);
*/    
    $this->formData['ActivityTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMNActivityTypes']     
		);
    
    $this->formData['ContactTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMNContactTypes']     
		);
    
    $this->formData['LocationTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMNLocationTypes']     
		);
    
	}

	public function sqlViewNote() {
		if(!is_numeric($this->noteId))
			throw new \UnexpectedValueException('Note Id must be an integer.');

		$this->loadSqlStatements();

		$this->formData['User'] = $this->caseManager;
		$this->formData['Note'] = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMNote'],
			array(
				'noteId' => $this->noteId,
			)
		);

		$noteData =& $this->formData['Note'];
    
    $this->servicePlanId = $noteData['TCMServicePlanId'];
    
    $servicePlan = $this->sqlFetchArray(
      $this->sqlStatements['READ']['TCMServicePlan'],
      array(
        'servicePlanId' => $this->servicePlanId,
      )
    );
  
    $noteData['TCMServicePlanFinalizedDate'] = $servicePlan['FinalizedDate'];

    $noteData['Activities'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthProgressActivities'],
			array(
				'noteId' => $this->noteId,
			)
		);
    
		$noteData['Problems'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthProgressNotesWithProblems'],
			array(
				'noteId' => $this->noteId,
			)
		);
    $this->fetchProblemsSubinfo($noteData['Problems'], 'ProblemId');
    
    // Fetch referenced service plan info to permit user adding sections from it when editing notes
  
		$this->formData['ServicePlanProblems'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthProblems'],
			array(
        'planId' => $this->servicePlanId,
      )
		);  
    $this->fetchProblemsSubinfo($this->formData['ServicePlanProblems'], 'Id');

/* @todo: delete
		$this->formData['FunctionalTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMAFunctionalTypes']
		);

		$this->formData['AgentsTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['BehavioralHealthAgentsTypes']
		);
*/    
    $this->formData['ActivityTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMNActivityTypes']     
		);
    
    $this->formData['ContactTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMNContactTypes']     
		);
    
    $this->formData['LocationTypes'] = $this->sqlFetchMultiArray(
			$this->sqlStatements['READ']['TCMNLocationTypes']     
		);    
	}  

	public function sqlReportNote() {
		if(!is_numeric($this->noteId))
			throw new \UnexpectedValueException('Note Id must be an integer.');

		$this->loadSqlStatements();

		$note = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMNote'],
			array(
				'noteId' => $this->noteId,
			)
		);

		$this->formData['Note'] = $this->filterData(
			$note,
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

	public function saveNewNote($userAuthorized = 0) {
		// formSubmit from library/api.inc
    
		$this->loadSqlStatements();
    
		$this->noteId = formSubmit(
			'form_TCMNote',
			$this->filterData(
				$this->formData,
				array(
					'TCMServicePlanId',
					'CaseManagerName',
					'CaseManagerSupervisorId',
					'CaseManagerSupervisorName',
					'ClientName',
          'DateWritten',
          'ManagerNote'
				),
				true
			),
			null,
			$userAuthorized
		);

		addForm(
			$this->encounterId,
			'Targeted Case Management Notes',
			$this->noteId,
			'TCMNote',
			$this->patientId,
			$userAuthorized
		);   

    // Set new note's Id in form for subordinate save methods
    $this->formData->Id = $this->noteId;

		$this->sqlSaveProblemNotes(); 

		$this->sqlSaveActivities(); 
    
		return $this->noteId;
	}

	public function sqlSaveNote() {
		if(!is_numeric($this->noteId))
			throw new \UnexpectedValueException('Note Id must be an integer.');

		$this->loadSqlStatements();

		$this->sqlStatement(
			$this->sqlStatements['UPDATE']['TCMNote'],
			array(
				'Id' => $this->formData->Id,
				'ManagerNote' => $this->formData->ManagerNote,
			)
		);

		$this->sqlSaveProblemNotes(); 

		$this->sqlSaveActivities(); 
	}

	public function sqlSaveProblemNotes() {
		if(property_exists($this->formData, 'Problems')
			&& is_array($this->formData->Problems)
		) {
			foreach($this->formData->Problems as $record) {

        // CREATE
				if( !(property_exists($record, 'Id') && $record->Id > 0)) {
                
        
					$this->sqlStatement(
						$this->sqlStatements['CREATE']['BehavioralHealthProgressNotes'],
						array(
							'TCMNoteId'=> $this->formData->Id,
              'ProblemId' => $record->ProblemId,
							'Note' => $record->ProgressNotes,
						)
					);
          
					$record->Id = $this->getMysqlInsertId();
				} elseif(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthProgressNotes'],   
						array(
							'Id' => $record->Id,
						)
					);
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatements['UPDATE']['BehavioralHealthProgressNotes'],
						array(
							'Id' => $record->Id,
							'TCMNoteId' => $this->formData->Id,
							'Note' => $record->ProgressNotes,
						)
					);
				}
			}
		}
	}

public function sqlSaveActivities() {
		if(property_exists($this->formData, 'Activities')
			&& is_array($this->formData->Activities)
		) {
			foreach($this->formData->Activities as $record) {

      if(property_exists($record, '_destroy') && $record->_destroy === true) {
					// DELETE
					$this->sqlStatement(
						$this->sqlStatements['DELETE']['BehavioralHealthProgressActivities'],
						array(
							'Id' => $record->Id,
						)
					);
				} 
        elseif( !(property_exists($record, 'Id') && $record->Id > 0)) {
					// CREATE
					$this->sqlStatement(
						$this->sqlStatements['CREATE']['BehavioralHealthProgressActivities'],
						array(
							'TCMNoteId' => $this->formData->Id,
              'ActivityTypeId' => $record->ActivityTypeId,
              'StartTime' => $record->StartTime,
              'EndTime' => $record->EndTime,
              'LocationTypeId' => $record->LocationTypeId,
              'ContactTypeId' => $record->ContactTypeId,
              'Description' => $record->Description,
 						)
					);
         
					$record->Id = $this->getMysqlInsertId();
				} else {
					// UPDATE
					$this->sqlStatement(
						$this->sqlStatements['UPDATE']['BehavioralHealthProgressActivities'],
						array(
							'Id' => $record->Id,
							'StartTime' => $record->StartTime,
							'EndTime' => $record->EndTime,
              'ActivityTypeId' => $record->ActivityTypeId,
              'LocationTypeId' => $record->LocationTypeId,
              'ContactTypeId' => $record->ContactTypeId,              
							'Description' => $record->Description,  // no other fields can change, they are derived from service plan
						)
					);
				}
			}
		}
	}
  
	public function checkSaveNote() {
		if(!property_exists($this->formData, 'Id'))
			throw new \UnexpectedValueException('Note Id is missing from save request.');

		if(!is_numeric($this->formData->Id))
			throw new \UnexpectedValueException('Note must be an integer.');

		if($this->formData->Id != $this->noteId)
			throw new \UnexpectedValueException('Note Id is invalid.');

	}

	public function saveNote() {
		$this->checkSaveNote();
		$this->sqlSaveNote();
	}

	public function finalizeNote() {
		if(!is_numeric($this->noteId))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		$this->loadSqlStatements();
		$this->sqlStatement(
			$this->sqlStatements['UPDATE']['TCMNoteFinalize'],
			array(
				'Id' => $this->noteId,
				'CaseManagerId' => $this->caseManager,
			)
		);

		$this->formData = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMNote'],
			array(
				'noteId' => $this->noteId,
			)
		);
	}

	public function unfinalizeNote() {
		if(!is_numeric($this->noteId))
			throw new \UnexpectedValueException('Service Plan Id must be an integer.');

		$this->loadSqlStatements();

		$this->sqlStatement(
			$this->sqlStatements['UPDATE']['TCMNoteUnfinalize'],
			array(
				'Id' => $this->noteId,
				'CaseManagerId' => $this->caseManager,
			)
		);

		$this->formData = $this->sqlFetchArray(
			$this->sqlStatements['READ']['TCMNote'],
			array(
				'noteId' => $this->noteId,
			)
		);
	}

	public function displayNewNote() {
		$this->loadExternal('templates/new.phtml');
	}

	public function displayViewNote() {
		$this->loadExternal('templates/view.phtml');
	}

	public function displayReportNote() {
		$this->loadExternal('templates/report.phtml');
	}

	public function displayFullReportNote() {
		$this->loadExternal('templates/full-report.phtml');
	}

}