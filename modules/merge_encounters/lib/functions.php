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

function getElementIfExist(&$array, $key, $default = null) {
	if(array_key_exists($key, $array)) {
		return $array[$key];
	} else {
		return $default;
	}
}

function includeService($service) {
	$service = realpath(__DIR__.'/../service').'/'.$service.'.php';

	if(file_exists($service)) {
		include($service);
	}
}

function pathToResource($resourceName, $version = false) {
	$path = './resource/';

	if(file_exists(__DIR__.'/.'.$path.$resourceName)) {
		return $path.$resourceName.(
			$version
				? '?v='.(
					$version === true
						? time()
						: $version
				)
				: ''
		);
	}
}

function getDatabaseConnection() {
	global $sqlconf;

	try {
		$databaseConnection = new PDO('mysql:host='.$sqlconf['host'].';dbname='.$sqlconf['dbase'], $sqlconf['login'], $sqlconf['pass']);
		$databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $exception) {
		echo 'ERROR: ' . $exception->getMessage();
	}

	return $databaseConnection;
}
