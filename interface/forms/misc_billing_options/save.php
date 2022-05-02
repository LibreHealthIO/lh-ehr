<?php
/*
 *  save.php for the saving of information from the misc_billing_form
 *
 *  This program saves data from the misc_billing_form
 *  The changes to this file as of November 16 2016 to add needed items to the HCFA Print and Electronic Transmission
 *  are covered under the terms of the Mozilla Public License, v. 2.0
 *  2018-05-16 -- Added the log_form_misc_billing_options table to track changes to form. (TRK)
 *
 * @copyright Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 * @copyright Copyright (C) 2017-2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
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
 * @package LibreHealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @author Tigpezeghe Rodrige <tigrodrige@gmail.com>
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

if ($_POST["off_work_from"] == "0000-00-00" || $_POST["off_work_from"] == "")
    { $_POST["is_unable_to_work"] = "0"; $_POST["off_work_to"] = "";}
    else {$_POST["is_unable_to_work"] = "1";}

if ($_POST["hospitalization_date_from"] == "0000-00-00" || $_POST["hospitalization_date_from"] == "")
    { $_POST["is_hospitalized"] = "0"; $_POST["hospitalization_date_to"] = "";}
    else {$_POST["is_hospitalized"] = "1";}

$id = formData('id','G') + 0;

$sets = "pid = {$_SESSION["pid"]},
  groupname = '" . $_SESSION["authProvider"] . "',
  user = '" . $_SESSION["authUser"] . "',
  authorized = $userauthorized,
  activity = 1,
  date = NOW(),
  employment_related          = '" . formData("employment_related") . "',
  auto_accident               = '" . formData("auto_accident") . "',
  accident_state              = '" . formData("accident_state") . "',
  other_accident              = '" . formData("other_accident") . "',
  outside_lab                 = '" . formData("outside_lab") . "',
  medicaid_referral_code      = '" . formData("medicaid_referral_code") . "',
  epsdt_flag                  = '" . formData("epsdt_flag") . "',
  provider_id                 = '" . formData("provider_id")  . "',
  provider_qualifier_code     = '" . formData("provider_qualifier_code") . "',
  lab_amount                  = '" . formData("lab_amount") . "',
  is_unable_to_work           = '" . formData("is_unable_to_work") . "',
  onset_date                  = '" . prepareDateBeforeSave(formData("onset_date")) . "',
  date_initial_treatment      = '" . prepareDateBeforeSave(formData("date_initial_treatment")) . "',
  off_work_from               = '" . prepareDateBeforeSave(formData("off_work_from")) . "',
  off_work_to                 = '" . prepareDateBeforeSave(formData("off_work_to")) . "',
  is_hospitalized             = '" . formData("is_hospitalized") . "',
  hospitalization_date_from   = '" . prepareDateBeforeSave(formData("hospitalization_date_from")) . "',
  hospitalization_date_to     = '" . prepareDateBeforeSave(formData("hospitalization_date_to")) . "',
  medicaid_resubmission_code  = '" . formData("medicaid_resubmission_code") . "',
  medicaid_original_reference = '" . formData("medicaid_original_reference") . "',
  prior_auth_number           = '" . formData("prior_auth_number") . "',
  replacement_claim           = '" . formData("replacement_claim") . "',
  icn_resubmission_number     = '" . formData("icn_resubmission_number") . "',
  box_14_date_qual            = '" . formData("box_14_date_qual") . "',
  box_15_date_qual            = '" . formData("box_15_date_qual") . "',
  comments                    = '" . formData("comments") . "'";

if (empty($id)) {
  $newid = sqlInsert("INSERT INTO form_misc_billing_options SET $sets");
  addForm($encounter, "Misc Billing Options", $newid, "misc_billing_options", $pid, $userauthorized);
}
else {
  sqlStatement("UPDATE form_misc_billing_options SET $sets WHERE id = $id");

  // The two lines below log the changes made to the form in the log_form_misc_billing_options table. (TRK)
  $sets = $sets . ",form_misc_billing_id = $id, encounter_id = $encounter";
  sqlInsert("INSERT INTO log_form_misc_billing_options SET $sets");
}

formHeader("Redirecting....");
formJump();
formFooter();
?>
