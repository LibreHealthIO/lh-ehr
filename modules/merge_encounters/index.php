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

$sanitize_all_escapes  = true;
$fake_register_globals = false;

require_once('../../globals.php');
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
