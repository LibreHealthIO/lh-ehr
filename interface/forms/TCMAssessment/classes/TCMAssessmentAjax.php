<?php

/**
 * Description of TCMAssessmentAjax
 *
 * @author samuelelliot
 */
class TCMAssessmentAjax {

	/**
	 * Valid Action types with associated method name
	 * @var array 
	 */
	public $actionTypes = array(
		'new' => 'recordNew',
		'create' => 'recordCreate',
		'read' => 'recordRead',
		'update' => 'recordUpdate',
		'signature' => 'recordSign',
		'revert' => 'recordSignRevert',
	);

	/**
	 *
	 * @var \TCMAssessment 
	 */
	public $tcmAssessment;

	/**
	 *
	 * @var string 
	 */
	public $action;

	/**
	 *
	 * @var integer 
	 */
	public $userAuthorized = 0;

	/**
	 *
	 * @var array 
	 */
	public $formData;

	/**
	 *
	 * @var boolean 
	 */
	public $testState = false;

	public function jsonResponse($status, $message = null, $data = null) {
		$return = array('status' => $status);
		if($message !== null)
			$return['message'] = $message;
		if($data !== null)
			$return['data'] = $data;

		return json_encode($return);
	}

	public function response() {
		if(!array_key_exists($this->action, $this->actionTypes))
			return $this->jsonResponse('fail', 'AJAX Call: Missing or invalid action type.');

		return $this->{$this->actionTypes[$this->action]}();
	}

	public function useTestData() {
		$this->tcmAssessment->formData = array_replace_recursive(
			$this->tcmAssessment->formData,
			$this->tcmAssessment->loadExternal('sql/testData.config.php')
		);
	}

	public function recordNew() {
		$this->tcmAssessment->sqlNewAssessment();
		return $this->jsonResponse(
			'success',
			null,
			$this->tcmAssessment->formData
		);
	}

	public function recordCreate() {
		$this->tcmAssessment->sqlNewAssessment();
		$this->tcmAssessment->formData += $this->formData;
		$record = $this->tcmAssessment->saveNewAssessment($this->userAuthorized);
		return $this->jsonResponse(
			'success',
			null,
			array(
				'record' => $record,
			)
		);
	}

	public function recordRead() {
		$this->tcmAssessment->sqlTypes();
		$this->tcmAssessment->sqlViewAssessment();
		if($this->testState)
			$this->useTestData();

		return $this->jsonResponse(
			'success',
			null,
			$this->tcmAssessment->formData
		);
	}

	public function recordUpdate() {
		$this->formData = json_decode(file_get_contents('php://input'));
		$this->tcmAssessment->formData =& $this->formData;

		$response = array(
			'status' => 'success',
			'message' => null,
			'data' => null,
		);

		try{
			$this->tcmAssessment->saveViewAssessment();
		} catch (Exception $e) {
			$response['status'] = 'fail';
			$response['message'] = $e->getMessage();
		}

		return $this->jsonResponse($response['status'], $response['message'], $response['data']);
	}

	public function recordSign() {
		$response = array(
			'status' => 'success',
			'message' => null,
			'data' => null,
		);

		try{
			if(!array_key_exists('sign', $_GET))
				throw new LogicException('Signer not specified.');

			switch ($_GET['sign']) {
				case 'manager':
					$this->tcmAssessment->signAssessmentManager();
					break;
				case 'supervisor':
					$this->tcmAssessment->signAssessmentSupervisor();
					break;
				default:
					throw new LogicException('Signer `'.$_GET['sign'].'` not recognized.');
			}

			$response['data']['signatures'] = $this->tcmAssessment->formData;
		} catch (Exception $e) {
			$response['status'] = 'fail';
			$response['message'] = $e->getMessage();
		}

		return $this->jsonResponse($response['status'], $response['message'], $response['data']);
	}

	public function recordSignRevert() {
		$response = array(
			'status' => 'success',
			'message' => null,
			'data' => null,
		);

		try{
			$this->tcmAssessment->signAssessmentRevert();
			$response['data']['signatures'] = $this->tcmAssessment->formData;
		} catch (Exception $e) {
			$response['status'] = 'fail';
			$response['message'] = $e->getMessage();
		}

		return $this->jsonResponse($response['status'], $response['message'], $response['data']);
	}

}