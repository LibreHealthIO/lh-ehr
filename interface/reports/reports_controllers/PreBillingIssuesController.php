<?php
/*
 * These functions are common functions used in Pre Billing Issues report.
 * They have been pulled out and placed in this file. This is done to prepare
 * the for building a report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 */
  $fake_register_globals=false;
  $sanitize_all_escapes=true;

  require_once("../globals.php");
  require_once("$srcdir/formatting.inc.php");
  require_once("$srcdir/formatting.inc.php");
  require_once("$srcdir/formdata.inc.php");
  require_once("$srcdir/patient.inc");
  require_once("$srcdir/headers.inc.php");
  require_once("../../library/report_functions.php");
  require("api/PreBillingIssuesAPI.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////
// render page - main
//////////////////////////////////////////////////////////////////////////////////////////////////////////

  $form_from_date  = fixDate($_POST['form_from_date'], date('Y-m-01'));
  $form_to_date    = fixDate($_POST['form_to_date'], date('Y-m-d'));

  function computeReport() {
      $preBillingIssuesAPI = new PreBillingIssuesAPI();
      $reportData = array();
      $reportData['encountersMissingProvider'] = $preBillingIssuesAPI->findEncountersMissingProvider();
      $reportData['patientInsuranceMissingSubscriberFields'] = $preBillingIssuesAPI->findPatientInsuranceMissingSubscriberFields();
      $reportData['patientInsuranceMissingSubscriberRelationship'] = $preBillingIssuesAPI->findPatientInsuranceMissingSubscriberRelationship();
      $reportData['patientInsuranceMissingInsuranceFields'] = $preBillingIssuesAPI->findPatientInsuranceMissingInsuranceFields();
      return $reportData;
  }

  ?>
