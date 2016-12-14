<?php
/**
 *
 * Drug Screen Complete Update Database
 *
 * Copyright (C) 2015-2017 Terry Hill <teryhill@librehealth.io>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreEHR
 * 
 * @author  Terry Hill <teryhill@librehealth.io>
 * @link    http://www.libreehr.org
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once("../../interface/globals.php");

$drugval = '0';
if ($_POST['testcomplete'] =='true') {
	$drugval = '1';
}

$tracker_id = $_POST['trackerid'];
  if($tracker_id != 0) 
  {  
           sqlStatement("UPDATE patient_tracker SET " .
			   "drug_screen_completed = ? " .
               "WHERE id =? ", array($drugval,$tracker_id));
  }             
