<?php
/**
 * 
 * Copyright (c) 2016 Sam Likins WSI-Services
 * Copyright (c) 2016 SunCoast Connection
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package Librehealth EHR 
 * @author Sam Likins <sam.likins@wsi-services.com>
 * @link http://suncoastconnection.com
 * @link http://librehealth.io
 *  
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 * 
 */

const JSON_STATUS_OK = 1;
const JSON_STATUS_SUCCESS = 2;
const JSON_STATUS_FAIL = 3;

// JSON Handler

class JsonHandler {

	protected static $jsonStatusMessages = [
		JSON_STATUS_OK => 'OK',
		JSON_STATUS_SUCCESS => 'SUCCESS',
		JSON_STATUS_FAIL => 'FAIL',
	];

	public static function encode($value, $options = 0) {
		$result = json_encode($value, $options);

		if($result)  {
			return $result;
		}

		throw new RuntimeException(json_last_error_msg(), json_last_error());
	}

	public static function decode($json, $assoc = false) {
		$result = json_decode($json, $assoc);

		if($result) {
			return $result;
		}

		throw new RuntimeException(json_last_error_msg(), json_last_error());
	}

	protected $status;

	protected $errors = [];

	protected $data;

	public function __construct($status = JSON_STATUS_OK) {
		$this->setStatus($status);
	}

	public function setStatus($status) {
		if(array_key_exists($status, static::$jsonStatusMessages)) {
			$this->status = $status;
		}
	}

	public function getStatus() {
		return $this->status;
	}

	public function addError($error) {
		$this->errors[] = $error;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function &getData() {
		return $this->data;
	}

	public function response($options = 0) {
		try {
			if(array_key_exists($this->getStatus(), static::$jsonStatusMessages)) {
				$status = static::$jsonStatusMessages[$this->getStatus()];
			} else {
				$status = static::$jsonStatusMessages[JSON_STATUS_FAIL];
			}

			$return = [
				'status' => $status,
				'errors' => $this->getErrors(),
				'data' => $this->getData()
			];

			array_walk_recursive($return, function(&$item) {
				$item = utf8_encode($item);
			});

			$return = static::encode($return, $options);

			header('Content-type: application/json;charset=utf-8');
		} catch (RuntimeException $exception) {
			$return = $exception->getMessage();
		}

		echo $return;
	}

}