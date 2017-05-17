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
