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