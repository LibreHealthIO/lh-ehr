<?php
/**
 * Author: Sam Likins <sam.likins@wsi-services.com>
 * Copyright: Copyright (c) 2016, WSI-Services
 *
 * License: http://opensource.org/licenses/gpl-3.0.html
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
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