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

$sanitize_all_escapes  = true;
$fake_register_globals = false;

require_once('../../interface/globals.php');
require_once($srcdir.'/acl.inc');

if(!acl_check('admin', 'super')) {
	die(xlt('Not authorized'));
} else {
	define('MERGE_ENCOUNTERS', true);
}

require_once(__DIR__.'/lib/functions.php');

$service = getElementIfExist($_GET, 'service', null);

switch($service) {
	case 'json-duplicates':
		includeService('JSON-Duplicates');
		break;

	case 'json-encounters':
		includeService('JSON-Encounters');
		break;

	case 'json-merge':
		includeService('JSON-Merge');
		break;

	case null:
		includeService('Screen');
		break;
}
