<?php
/** 
* 
* Copyright (C) 2014-2017 Terry Hill <teryhill@librehealth.io> 
* 
* LICENSE: This Source Code is subject to the terms of the Mozilla Public License, v. 2.0.
* See the Mozilla Public License for more details.
* If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*
* @package LibreHealth EHR
* @author Terry Hill <teryhill@librehealth.io>
* @link http://librehealth.io
*
* Please help the overall project by sending changes you make to the authors and to the LibreHealth EHR community.
*
*/
require_once("../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");

if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}

if ($_POST["hospitalization_date_from"] == "0000-00-00" || $_POST["hospitalization_date_from"] == "") 
	{ $_POST["is_hospitalized"] = "0"; $_POST["hospitalization_date_to"] = "";} 
	else {$_POST["is_hospitalized"] = "1";}
	
$id = formData('id','H') + 0;

$sets = "pid = {$_SESSION["pid"]},
  groupname = '" . $_SESSION["authProvider"] . "',
  user = '" . $_SESSION["authUser"] . "',
  authorized = $userauthorized, activity=1, date = NOW(),
  admission_hour                = '" . formData("admission_hour") . "',
  auto_accident                 = '" . formData("auto_accident") . "',
  accident_state                = '" . formData("accident_state") . "',
  accident_date                 = '" . formData("accident_date") . "',
  type_of_bill                  = '" . formData("type_of_bill") . "',
  admit_type                    = '" . formData("admit_type") . "',
  admit_source                  = '" . formData("admit_source") . "',
  discharge_hour                = '" . formData("discharge_hour") . "',
  discharge_status              = '" . formData("discharge_status") . "',
  condition_code_1              = '" . formData("condition_code_1") . "',
  condition_code_2              = '" . formData("condition_code_2") . "',
  condition_code_3              = '" . formData("condition_code_3") . "',
  condition_code_4              = '" . formData("condition_code_4") . "',
  condition_code_5              = '" . formData("condition_code_5") . "',
  condition_code_6              = '" . formData("condition_code_6") . "',
  condition_code_7              = '" . formData("condition_code_7") . "',
  condition_code_8              = '" . formData("condition_code_8") . "',
  condition_code_9              = '" . formData("condition_code_9") . "',
  condition_code_10             = '" . formData("condition_code_10") . "',
  condition_code_11             = '" . formData("condition_code_11") . "',
  occurrence_date_1             = '" . formData("occurrence_date_1") . "',
  occurrence_date_2             = '" . formData("occurrence_date_2") . "',
  occurrence_date_3             = '" . formData("occurrence_date_3") . "',
  occurrence_date_4             = '" . formData("occurrence_date_4") . "',
  occurrence_code_1             = '" . formData("occurrence_code_1") . "',
  occurrence_code_2             = '" . formData("occurrence_code_2") . "',
  occurrence_code_3             = '" . formData("occurrence_code_3") . "',
  occurrence_code_4             = '" . formData("occurrence_code_4") . "',
  occurrence_date_1A            = '" . formData("occurrence_date_1A") . "',
  occurrence_date_2A            = '" . formData("occurrence_date_2A") . "',
  occurrence_date_3A            = '" . formData("occurrence_date_3A") . "',
  occurrence_date_4A            = '" . formData("occurrence_date_4A") . "',
  occurrence_code_1A            = '" . formData("occurrence_code_1A") . "',
  occurrence_code_2A            = '" . formData("occurrence_code_2A") . "',
  occurrence_code_3A            = '" . formData("occurrence_code_3A") . "',
  occurrence_code_4A            = '" . formData("occurrence_code_4A") . "',
  hospitalization_date_from     = '" . formData("hospitalization_date_from") . "',
  hospitalization_date_to       = '" . formData("hospitalization_date_to") . "',
  is_hospitalized               = '" . formData("is_hospitalized") . "',
  occurrence_span_date_from_1   = '" . formData("occurrence_span_date_from_1") . "',
  occurrence_span_date_to_1     = '" . formData("occurrence_span_date_to_1") . "',
  occurrence_span_date_from_2   = '" . formData("occurrence_span_date_from_2") . "',
  occurrence_span_date_to_2     = '" . formData("occurrence_span_date_to_2") . "',
  occurrence_span_code_1        = '" . formData("occurrence_span_code_1") . "',
  occurrence_span_code_2        = '" . formData("occurrence_span_code_2") . "', 
  occurrence_span_date_from_1A  = '" . formData("occurrence_span_date_from_1A") . "',
  occurrence_span_date_to_1A    = '" . formData("occurrence_span_date_to_1A") . "',
  occurrence_span_date_from_2A  = '" . formData("occurrence_span_date_from_2A") . "',
  occurrence_span_date_to_2A    = '" . formData("occurrence_span_date_to_2A") . "',
  occurrence_span_code_1A       = '" . formData("occurrence_span_code_1A") . "',
  occurrence_span_code_2A       = '" . formData("occurrence_span_code_2A") . "',
  value_code_1a                 = '" . formData("value_code_1a") . "',
  value_code_2a                 = '" . formData("value_code_2a") . "', 
  value_code_3a                 = '" . formData("value_code_3a") . "',
  value_code_amount_1a          = '" . formData("value_code_amount_1a") . "',
  value_code_amount_2a          = '" . formData("value_code_amount_2a") . "', 
  value_code_amount_3a          = '" . formData("value_code_amount_3a") . "',
  value_code_1b                 = '" . formData("value_code_1b") . "',
  value_code_2b                 = '" . formData("value_code_2b") . "', 
  value_code_3b                 = '" . formData("value_code_3b") . "',
  value_code_amount_1b          = '" . formData("value_code_amount_1b") . "',
  value_code_amount_2b          = '" . formData("value_code_amount_2b") . "', 
  value_code_amount_3b          = '" . formData("value_code_amount_3b") . "',
  value_code_1c                 = '" . formData("value_code_1c") . "',
  value_code_2c                 = '" . formData("value_code_2c") . "', 
  value_code_3c                 = '" . formData("value_code_3c") . "',
  value_code_amount_1c          = '" . formData("value_code_amount_1c") . "',
  value_code_amount_2c          = '" . formData("value_code_amount_2c") . "', 
  value_code_amount_3c          = '" . formData("value_code_amount_3c") . "', 
  value_code_1d                 = '" . formData("value_code_1d") . "',
  value_code_2d                 = '" . formData("value_code_2d") . "', 
  value_code_3d                 = '" . formData("value_code_3d") . "',
  value_code_amount_1d          = '" . formData("value_code_amount_1d") . "',
  value_code_amount_2d          = '" . formData("value_code_amount_2d") . "', 
  value_code_amount_3d          = '" . formData("value_code_amount_3d") . "',
  prior_auth_number             = '" . formData("prior_auth_number") . "',
  prior_auth_number_1           = '" . formData("prior_auth_number_1") . "',
  prior_auth_number_2           = '" . formData("prior_auth_number_2") . "',
  attending_id                  = '" . formData("attending_id")  . "',
  operating_id                  = '" . formData("operating_id")  . "',
  other_1_id                    = '" . formData("other_1_id") . "',
  other_2_id                    = '" . formData("other_2_id") . "'";


  
if (empty($id)) {
  $newid = sqlInsert("INSERT INTO form_ub04_billing_options SET $sets");
  addForm($encounter, "UB04 Billing Options", $newid, "UB04_billing_options", $pid, $userauthorized);
}
else {
  sqlStatement("UPDATE form_ub04_billing_options SET $sets WHERE id = $id");
}

formHeader("Redirecting....");
formJump();
formFooter();
?>             
  
