<?php
/** 
* 
* Copyright (C) 2016 Sherwin Gaddis <sherwingaddis@gmail.com>
* Copyright (C) 2016-2018 Nilesh Prasad <prasadnilesh96@gmail.com
*
* LICENSE: This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 3
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
*
* LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
* See the Mozilla Public License for more details.
* If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
*
* @package LibreHealth EHR
* @author  Nilesh Prasad <prasadnilesh96@gmail.com
* @author  Sherwin Gaddis <sherwingaddis@gmail.com>
* @link    http://librehealth.io
*/

include_once("../../globals.php");
include_once("$srcdir/api.inc");

require ("C_FormPriorAuth.class.php");
$c = new C_FormPriorAuth();
echo $c->default_action_process($_POST);

global $pid, $encounter;
$owner = $_SESSION["authUser"];

//Added by Sherwin to link the to forms together to produce the desired result
//
//Copy this form date into the misc_billing_options table 
// in order to populate the x12 file with the proper data. 
//
/*
$sql = "SELECT date, prior_auth_number FROM form_prior_auth WHERE pid = $pid ORDER BY id DESC LIMIT 1 ";
$fetch = sqlStatement($sql);
$res = sqlFetchArray($fetch);

$date = $res['date'] ; 
$pa = $res['prior_auth_number'];
 
 $go_sql = "INSERT INTO `form_misc_billing_options` (`id`, `date`, `pid`, `user`, `groupname`, `authorized`, `activity`, `employment_related`, `auto_accident`, `accident_state`, `other_accident`, `outside_lab`, `lab_amount`, `is_unable_to_work`, `date_initial_treatment`, `off_work_from`, `off_work_to`, `is_hospitalized`, `hospitalization_date_from`, `hospitalization_date_to`, `medicaid_resubmission_code`, `medicaid_original_reference`, `prior_auth_number`, `comments`, `replacement_claim`, `box_14_date_qual`, `box_15_date_qual`) VALUES ('', '$date', '$pid', '$owner', 'IBH', '0', '1', '0', '0', '', '0', '0', '0.00', '0', '0000-00-00', '0000-00-00', '0000-00-00', '0', '0000-00-00', '0000-00-00', '', '', '$pa', '', '0', '431', '454');";

 sqlStatement($go_sql);
 
 //Get the ID that was just saved
 $sql = "SELECT id FROM form_misc_billing_options WHERE date LIKE '$date' and pid = '$pid'";
 
 $fetch = sqlStatement($sql);
 $res = sqlFetchArray($fetch);
 $f_id = $res['id'];
 
 $form = "INSERT INTO `forms` (`id`, `date`, `encounter`, `form_name`, `form_id`, `pid`, `user`, `groupname`, `authorized`, `deleted`, `formdir`) VALUES (NULL, '$date', '$encounter', 'Misc Billing Options', '$f_id', '$pid', '$owner', 'IBH', '1', '0', 'misc_billing_options')";
 
 //Insert Misc Billing form info into table to similate filling out form
 sqlStatement($form);
 
 
 //file_put_contents("sql.txt", $txt);  //troubleshooting
*/

@formJump();
?>
