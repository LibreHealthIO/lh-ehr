<?php

/**
 * Description of TCMServicePlanAjax
 *
 * @author samuelelliot
 */
class TCMServicePlanAjax {

	/**
	 * Valid Action types with associated method name
	 * @var array 
	 */
	public $actionTypes = array(
		'new' => 'recordNew',
		'create' => 'recordCreate',
		'read' => 'recordRead',
		'update' => 'recordUpdate',
		'finalize' => 'recordFinalize',
		'unfinalize' => 'recordUnfinalize',
	);

	/**
	 *
	 * @var \TCMServicePlan 
	 */
	public $tcmServicePlan;

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
		$this->tcmServicePlan->formData = array_replace_recursive(
			$this->tcmServicePlan->formData,
			$this->tcmServicePlan->loadExternal('sql/testData.config.php')
		);
	}

	public function recordNew() {
		$this->tcmServicePlan->sqlNewServicePlan();
		return $this->jsonResponse(
			'success',
			null,
			$this->tcmServicePlan->formData
		);
	}

	public function recordCreate() {
		$this->tcmServicePlan->formData = $this->formData;
		$record = $this->tcmServicePlan->saveNewServicePlan($this->userAuthorized);
		return $this->jsonResponse(
			'success',
			null,
			array(
				'record' => $record,
			)
		);
	}

	public function recordRead() {
		$this->tcmServicePlan->sqlViewServicePlan();
		if($this->testState)
			$this->useTestData();

		return $this->jsonResponse(
			'success',
			null,
			$this->tcmServicePlan->formData
		);
	}

	public function recordUpdate() {
		$this->tcmServicePlan->formData =& $this->formData;

		$response = array(
			'status' => 'success',
			'message' => null,
			'data' => null,
		);

		try{
			$this->tcmServicePlan->saveViewServicePlan();
		} catch (Exception $e) {
			$response['status'] = 'fail';
			$response['message'] = $e->getMessage();
		}

		return $this->jsonResponse($response['status'], $response['message'], $response['data']);
	}

	public function recordFinalize() {
		$response = array(
			'status' => 'success',
			'message' => null,
			'data' => null,
		);

		try{
			$this->tcmServicePlan->finalizeServicePlan();
			$response['data']['finalized'] = $this->tcmServicePlan->formData;
		} catch (Exception $e) {
			$response['status'] = 'fail';
			$response['message'] = $e->getMessage();
		}

		return $this->jsonResponse($response['status'], $response['message'], $response['data']);
	}

	public function recordUnfinalize() {
		$response = array(
			'status' => 'success',
			'message' => null,
			'data' => null,
		);

		try{
			$this->tcmServicePlan->unfinalizeServicePlan();
			$response['data']['finalized'] = $this->tcmServicePlan->formData;
		} catch (Exception $e) {
			$response['status'] = 'fail';
			$response['message'] = $e->getMessage();
		}

		return $this->jsonResponse($response['status'], $response['message'], $response['data']);
	}

}