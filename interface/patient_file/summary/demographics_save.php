<?php
/*
 *  Demographics_save.php
 *
 *  This program demographics_save.php is the Patient summary screen data save program.
 *
 * @copyright Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 * No previous copyright information. This is an original OpenEMR program.
 *
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
 * along with this program. If not, see http://opensource.org/licenses/gpl-license.php.
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreEHR
 * @author Terry Hill <teryhill@librehealth.io>
 * No other authors listed in original program header.
 * @link http://www.libreehr.org
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

include_once("../../globals.php");
include_once("$srcdir/patient.inc");
include_once("$srcdir/acl.inc");
include_once("$srcdir/options.inc.php");
include_once("$srcdir/formdata.inc.php");

// Check authorization.
if ($pid) {
  if ( !acl_check('patients','demo','','write') )
    die(xl('Updating demographics is not authorized.'));
} else {
  if (!acl_check('patients','demo','',array('write','addonly') ))
    die(xl('Adding demographics is not authorized.'));
}

foreach ($_POST as $key => $val) {
  if ($val == "MM/DD/YYYY") {
    $_POST[$key] = "";
  }
}

// Update patient_data and employer_data:
//
$newdata = array();
$newdata['patient_data']['id'] = $_POST['db_id'];
$fres = sqlStatement("SELECT * FROM layout_options " .
  "WHERE form_id = 'DEM' AND uor > 0 AND field_id != '' " .
  "ORDER BY group_name, seq");
while ($frow = sqlFetchArray($fres)) {
  $data_type = $frow['data_type'];
  $field_id  = $frow['field_id'];
  // $value  = '';
  $colname = $field_id;
  $table = 'patient_data';
  if (strpos($field_id, 'em_') === 0) {
    $colname = substr($field_id, 3);
    $table = 'employer_data';
  }

  // if (isset($_POST["form_$field_id"])) $value = $_POST["form_$field_id"];
  $value = get_layout_form_value($frow);

  $newdata[$table][$colname] = $value;
}
updatePatientData($pid, $newdata['patient_data']);
updateEmployerData($pid, $newdata['employer_data']);

$total_insurances = $_REQUEST['total_insurances'];
for($i = 0;$i<$total_insurances;$i++){
$idob = fixDate(formData("i".$i."subscriber_DOB"));
$idate = fixDate(formData("i".$i."effective_date"), date('Y-m-d'));
$edate = fixDate(formData("i".$i."termination_date"), date('Y-m-d'));
$type = formData("i".$i."insurance_type");
$inactive = formData('i'.$i.'inactive_value');
$inactive_time = 0;

if($edate != '0000-00-00') {
    $inactive_time = 1;
    $inactive = 1;
    error_log("eDate: ".$edate, 0);
    error_log("inactive time: ".$inactive_time, 0);
    error_log("inactive: ".$inactive, 0);
}

newInsuranceData(
  $pid,
  $type,
  formData("i".$i."provider"),
  formData("i".$i."policy_number"),
  formData("i".$i."group_number"),
  formData("i".$i."plan_name"),
  formData("i".$i."subscriber_lname"),
  formData("i".$i."subscriber_mname"),
  formData("i".$i."subscriber_fname"),
  formData("form_i".$i."subscriber_relationship"),
  formData("i".$i."subscriber_ss"),
  $idob,
  formData("i".$i."subscriber_street"),
  formData("i".$i."subscriber_postal_code"),
  formData("i".$i."subscriber_city"),
  formData("form_i".$i."subscriber_state"),
  formData("form_i".$i."subscriber_country"),
  formData("i".$i."subscriber_phone"),
  formData("i".$i."subscriber_employer"),
  formData("i".$i."subscriber_employer_street"),
  formData("i".$i."subscriber_employer_city"),
  formData("i".$i."subscriber_employer_postal_code"),
  formData("form_i".$i."subscriber_employer_state"),
  formData("form_i".$i."subscriber_employer_country"),
  formData('i'.$i.'copay'),
  formData('form_i'.$i.'subscriber_sex'),
  $idate,
  $edate,
  formData('i'.$i.'accept_assignment'),
  formData('i'.$i.'policy_type'),
  $inactive,
  $inactive_time
);

  if(isset($inactive)&&$inactive==1){
    addActiveInsurance($pid,$edate,$type);
  }
}

if ($GLOBALS['concurrent_layout']) {
 include_once("demographics.php");
} else {
 include_once("patient_summary.php");
}
?>
