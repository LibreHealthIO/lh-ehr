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

if(!defined('MERGE_ENCOUNTERS') || MERGE_ENCOUNTERS !== true) {
	die('Not authorized');
}

include(__DIR__.'/../lib/JsonHandler.php');

$pid = getElementIfExist($_POST, 'pid');
$date = getElementIfExist($_POST, 'date');

$databaseConnection = getDatabaseConnection();

$jsonHandler = new JsonHandler();

$jsonHandler->setData([
	'encounters' => [],
	'documents' => [],
	'billing' => [],
	'forms' => []
]);

// Retrieve Form Encounters by pid & date

try {
	$formEncounters = $databaseConnection->prepare(
		'SELECT * FROM `form_encounter` WHERE `pid` = :pid AND date(`date`) = :date;'
	);

	$formEncounters->execute([
		'pid' => $pid,
		'date' => $date
	]);

	$formEncounters->setFetchMode(PDO::FETCH_ASSOC);
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}

$jsonHandler->getData()['encounters'] = $formEncounters->fetchAll();


// Generate encounter IDs list

$placeholderValues = [];
array_walk($jsonHandler->getData()['encounters'],
	function($encounter) use(&$placeholderValues) {
		if(false === array_search($encounter['encounter'], $placeholderValues)) {
			$placeholderValues[] = $encounter['encounter'];
		}
	}
);


// Build WHERE IN clause

$whereEncounterIn = 'IN('.implode(', ', array_fill(0, count($placeholderValues), '?')).');';


// Retrieve Documents by pid & encounter IDs

try {
	$documents = $databaseConnection->prepare(
		'SELECT * FROM `documents` WHERE `encounter_id` '.$whereEncounterIn
	);

	$documents->execute($placeholderValues);

	$documents->setFetchMode(PDO::FETCH_ASSOC);
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}

$jsonHandler->getData()['documents'] = $documents->fetchAll();


// Perpend PID to placeholder values

array_unshift($placeholderValues, $pid);


// Retrieve Billing by pid & encounter IDs

try {
	$billing = $databaseConnection->prepare(
		'SELECT * FROM `billing` WHERE `pid` = ? AND `encounter` '.$whereEncounterIn
	);

	$billing->execute($placeholderValues);

	$billing->setFetchMode(PDO::FETCH_ASSOC);
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}

$jsonHandler->getData()['billing'] = $billing->fetchAll();


// Retrieve Forms by pid & encounter IDs

try {
	$forms = $databaseConnection->prepare(
		'SELECT * FROM `forms` WHERE `form_name` != \'New Patient Encounter\' AND `pid` = ? AND `encounter` '.$whereEncounterIn
	);

	$forms->execute($placeholderValues);

	$forms->setFetchMode(PDO::FETCH_ASSOC);
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}

$jsonHandler->getData()['forms'] = $forms->fetchAll();


if($jsonHandler->getStatus() !== JSON_STATUS_FAIL) {
	$jsonHandler->setStatus(JSON_STATUS_SUCCESS);
}

echo $jsonHandler->response();