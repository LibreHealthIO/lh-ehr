<?php
/*
 *  save.php for the saving of information from the Enhanced Prior Authorization
 *
 *  This program saves data from the enhanced_prior_authorization Form
 *
 * @copyright Copyright (C) 2018 Terry Hill <teryhill@librehealth.io>
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package LibreHealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
require_once("../../globals.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");

if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}

$id = formData('id','G') + 0;


$pid = $_POST["pid"];
if (strtotime($_POST["auth_to"]) <= strtotime(date("Y-m-d"))) {
    $_POST["archived"] = '1';
    error_log("auth todate: ".$_POST["auth_to"], 0);
    error_log("date: ".date("Y-m-d"), 0);
}
$archived = $_POST["archived"];

  if (empty($id)) {

      $sets = "pid = {$_SESSION["pid"]},
      groupname = '" . $_SESSION["authProvider"] . "',
      user = '" . $_SESSION["authUser"] . "',
      authorized = $userauthorized, activity=1, date = NOW(),
      prior_auth_number = '" . formData("prior_auth_number") . "',
      not_req           = '" . formData("not_req") . "',
      comments          = '" . formData("comments") . "',
      ddesc             = '" . formData("desc") . "',
      case_number       = '" . formData("case_num") . "',
      auth_for          = '" . formData("auth_for") . "',
      auth_from         = '" . prepareDateBeforeSave(formData("auth_from")) . "',
      auth_to           = '" . prepareDateBeforeSave(formData("auth_to")) . "',
      units             = '" . formData("units") . "',
      auth_length       = '" . formData("auth_length")  . "',
      dollar            = '" . formData("dollar") . "',
      auth_contact      = '" . formData("auth_contact") . "',
      auth_phone        = '" . formData("auth_phone") . "',
      code1             = '" . formData("code1") . "',
      code2             = '" . formData("code2") . "',
      code3             = '" . formData("code3") . "',
      code4             = '" . formData("code4") . "',
      code5             = '" . formData("code5") . "',
      code6             = '" . formData("code6") . "',
      code7             = '" . formData("code7") . "',
      used              = '" . formData("used") . "',
      archived          = '" . formData("archived") . "',
      override          = '" . formData("override") . "'";

  $newid = sqlInsert("INSERT INTO form_enhanced_prior_auth SET $sets");
  addForm($encounter, "Enhanced Prior Authorization Form", $newid, "enhanced_prior_auth", $pid, $userauthorized);
}
else {
      $sets = "pid = {$_SESSION["pid"]},
      groupname = '" . $_SESSION["authProvider"] . "',
      user = '" . $_SESSION["authUser"] . "',
      authorized = $userauthorized, activity=1, date = NOW(),
      prior_auth_number = '" . formData("prior_auth_number") . "',
      not_req           = '" . formData("not_req") . "',
      comments          = '" . formData("comments") . "',
      ddesc             = '" . formData("desc") . "',
      case_number       = '" . formData("case_num") . "',
      auth_for          = '" . formData("auth_for") . "',
      auth_from         = '" . prepareDateBeforeSave(formData("auth_from")) . "',
      auth_to           = '" . prepareDateBeforeSave(formData("auth_to")) . "',
      units             = '" . formData("units") . "',
      auth_length       = '" . formData("auth_length")  . "',
      dollar            = '" . formData("dollar") . "',
      auth_contact      = '" . formData("auth_contact") . "',
      auth_phone        = '" . formData("auth_phone") . "',
      code1             = '" . formData("code1") . "',
      code2             = '" . formData("code2") . "',
      code3             = '" . formData("code3") . "',
      code4             = '" . formData("code4") . "',
      code5             = '" . formData("code5") . "',
      code6             = '" . formData("code6") . "',
      code7             = '" . formData("code7") . "',
      used              = '" . formData("used") . "',
      archived          = '" . formData("archived") . "',
      override          = '" . formData("override") . "'";

  sqlStatement("UPDATE form_enhanced_prior_auth SET $sets WHERE id = $id");
  if ($archived){ // check archived and if it is then archive all instances of that auth.
    sqlStatement("UPDATE form_enhanced_prior_auth set archived = '1' WHERE prior_auth_number = $auth_number_update");
  }
}

formHeader("Redirecting....");
formJump();
formFooter();
?>
