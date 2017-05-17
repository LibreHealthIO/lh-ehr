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

$primary = getElementIfExist($_POST, 'primary');
$ids = getElementIfExist($_POST, 'ids');
$encounters = getElementIfExist($_POST, 'encounters');

$databaseConnection = getDatabaseConnection();

$jsonHandler = new JsonHandler();

$jsonHandler->setData([
	'Primary Encounter Updated' => [],
	'Secondary Encounters Deleted' => [],
	'Secondary Form Encounters Deleted' => [],
	'Secondary Forms Update' => [],
	'Secondary Billing Update' => [],
	'Secondary Documents Update' => []
]);


// Update primary `form_encounter`.`reason`
try {
	$statement = $databaseConnection->prepare(
		'UPDATE `form_encounter` '.
		'SET `form_encounter`.`reason` = ? '.
		'WHERE `form_encounter`.`id` = ?;'
	);

	$statement->execute([
		$primary['reason'],
		$primary['id']
	]);

	$jsonHandler->getData()['Primary Encounter Updated'] = $statement->rowCount();
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}


// Build IN clause
$whereIn = 'IN('.implode(', ', array_fill(0, count($ids), '?')).');';


// Delete secondary `form_encounter` records
try {
	$statement = $databaseConnection->prepare(
		'DELETE FROM `form_encounter` '.
		'WHERE `form_encounter`.`id` '.$whereIn
	);

	$statement->execute($ids);

	$jsonHandler->getData()['Secondary Encounters Deleted'] = $statement->rowCount();
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}


// Delete secondary `form` 'New Patient Encounter' records
try {
	$statement = $databaseConnection->prepare(
		'DELETE FROM `forms` '.
		'WHERE `forms`.`form_name` = "New Patient Encounter" '.
		'AND `forms`.`form_id` '.$whereIn
	);

	$statement->execute($ids);

	$jsonHandler->getData()['Secondary Form Encounters Deleted'] = $statement->rowCount();
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}


// Perpend Primary encounter to Secondary encounters
array_unshift($encounters, $primary['encounter']);


// Update secondary `forms` records
try {
	$statement = $databaseConnection->prepare(
		'UPDATE `forms` '.
		'SET `forms`.`encounter` = ? '.
		'WHERE `forms`.`encounter` '.$whereIn
	);

	$statement->execute($encounters);

	$jsonHandler->getData()['Secondary Forms Update'] = $statement->rowCount();
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}


// Update secondary `billing` records
try {
	$statement = $databaseConnection->prepare(
		'UPDATE `billing` '.
		'SET `billing`.`encounter` = ? '.
		'WHERE `billing`.`encounter` '.$whereIn
	);

	$statement->execute($encounters);

	$jsonHandler->getData()['Secondary Billing Update'] = $statement->rowCount();
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}


// Update secondary `documents` records
try {
	$statement = $databaseConnection->prepare(
		'UPDATE `documents` '.
		'SET `documents`.`encounter_id` = ? '.
		'WHERE `documents`.`encounter_id` '.$whereIn
	);

	$statement->execute($encounters);

	$jsonHandler->getData()['Secondary Documents Update'] = $statement->rowCount();
} catch(PDOException $exception) {
	$jsonHandler->addError($exception->getMessage());

	$jsonHandler->setStatus(JSON_STATUS_FAIL);
}


if($jsonHandler->getStatus() !== JSON_STATUS_FAIL) {
	$jsonHandler->setStatus(JSON_STATUS_SUCCESS);
}

echo $jsonHandler->response();