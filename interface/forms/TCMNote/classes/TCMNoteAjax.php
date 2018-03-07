<?php

/**
 * Description of TCMNoteAjax
 *
 * @author Wayne Robinson, following Sam's example
 */
class TCMNoteAjax {

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
	 * @var \TCMNote 
	 */
	public $tcmNote;

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
		$this->tcmNote->formData = array_replace_recursive(
			$this->tcmNote->formData,
			$this->tcmNote->loadExternal('sql/testData.config.php')
		);
	}

	public function recordNew() {
		$this->tcmNote->sqlNewNote();
		return $this->jsonResponse(
			'success',
			null,
			$this->tcmNote->formData
		);
	}

	public function recordCreate() {
		$this->tcmNote->formData = $this->formData;
		$record = $this->tcmNote->saveNewNote($this->userAuthorized);
		return $this->jsonResponse(
			'success',
			null,
			array(
				'record' => $record,
			)
		);
	}

	public function recordRead() {
		$this->tcmNote->sqlViewNote();
		if($this->testState)
			$this->useTestData();

		return $this->jsonResponse(
			'success',
			null,
			$this->tcmNote->formData
		);
	}

	public function recordUpdate() {
		$this->tcmNote->formData =& $this->formData;

		$response = array(
			'status' => 'success',
			'message' => null,
			'data' => null,
		);

		try{
			$this->tcmNote->saveNote();
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
			$this->tcmNote->finalizeNote();
			$response['data']['finalized'] = $this->tcmNote->formData;
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
			$this->tcmNote->unfinalizeNote();
			$response['data']['finalized'] = $this->tcmNote->formData;
		} catch (Exception $e) {
			$response['status'] = 'fail';
			$response['message'] = $e->getMessage();
		}

		return $this->jsonResponse($response['status'], $response['message'], $response['data']);
	}

}