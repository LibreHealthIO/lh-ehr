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

if(!defined('MERGE_ENCOUNTERS') || MERGE_ENCOUNTERS !== true) {
	die('Not authorized');
}

include(__DIR__.'/../lib/JsonHandler.php');

$databaseConnection = getDatabaseConnection();

$jsonHandler = new JsonHandler();


// Retrieve duplicate Encounters grouped by pid & date

try {
	$statement = $databaseConnection->query(
		'SELECT `pid`, date(`date`) AS `date`, count(*) AS `count` FROM `form_encounter` GROUP BY date(`date`), `pid` HAVING count(*) > 1;'
	);

	$statement->setFetchMode(PDO::FETCH_ASSOC);
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}

$jsonHandler->setData($statement->fetchAll());


if($jsonHandler->getStatus() !== JSON_STATUS_FAIL) {
	$jsonHandler->setStatus(JSON_STATUS_SUCCESS);
}

echo $jsonHandler->response();