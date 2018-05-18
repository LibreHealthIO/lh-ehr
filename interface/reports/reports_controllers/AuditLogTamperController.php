<?php
/*
 * These functions are common functions used in the Audit Log Tamper reports.
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
 *
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

include_once("../globals.php");
include_once("$srcdir/log.inc");
require_once("$srcdir/headers.inc.php");
include_once("$srcdir/formdata.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../library/report_functions.php");
$DateFormat = DateFormatRead(true);
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$err_message=0;
if ($_GET["start_date"])
$start_date = $_GET['start_date'];

if ($_GET["end_date"])
$end_date = $_GET['end_date'];

if ($_GET["form_patient"])
$form_patient = $_GET['form_patient'];

/*
 * Start date should not be greater than end date - Date Validation
 */
if ($start_date && $end_date)
{
    if($start_date > $end_date){
        echo "<table><tr class='alert'><td colspan=7>"; echo xlt('Start Date should not be greater than End Date');
        echo "</td></tr></table>";
        $err_message=1;
    }
}

$form_user = $_REQUEST['form_user'];
$form_pid = $_REQUEST['form_pid'];
if ($form_patient == '' ) $form_pid = '';

$get_sdate=$start_date ? $start_date : date("Y-m-d H:i:s");
$get_edate=$end_date ? $end_date : date("Y-m-d H:i:s");

/*
 * This function is responsible for showing the results of the search
 * @params: None
 * @return: None
 */
function showResults() {
	$eventname = $_GET['eventname'];
  	$type_event = $_GET['type_event'];
  	echo "<input type=hidden name=event value="; echo attr($eventname)."-".attr($type_event).">";
  	$type_event = "update";
  	$tevent="";
  	$gev="";
  	if($eventname != "" && $type_event != "") {
      	$getevent=$eventname."-".$type_event;
  	}

    if(($eventname == "") && ($type_event != "")){
      	$tevent=$type_event;
  	}else if($type_event =="" && $eventname != ""){
      	$gev=$eventname;
  	}else if ($eventname == ""){
      	$gev = "";
  	}else{
      	$gev = $getevent;
  	}

  	$dispArr = array();
  	$icnt = 1;
  	if ($ret = getEvents(array('sdate' => $get_sdate,'edate' => $get_edate, 'user' => $form_user, 'patient' => $form_pid, 'sortby' => $_GET['sortby'], 'levent' =>$gev, 'tevent' =>$tevent))) {
	    foreach ($ret as $iter) {
	      	//translate comments
	      	$patterns = array ('/^success/','/^failure/','/ encounter/');
	      	$replace = array ( xl('success'), xl('failure'), xl('encounter','',' '));

	      	$dispCheck = false;
	      	$log_id = $iter['id'];
	      	$commentEncrStatus = "No";
	      	$logEncryptData = logCommentEncryptData($log_id);

	      	if(count($logEncryptData) > 0){
	          	$commentEncrStatus = $logEncryptData['encrypt'];
	          	$checkSumOld = $logEncryptData['checksum'];
	          	$concatLogColumns = $iter['date'].$iter['event'].$iter['user'].$iter['groupname'].$iter['comments'].$iter['patient_id'].$iter['success'].$iter['checksum'].$iter['crt_user'];
	          	$checkSumNew = sha1($concatLogColumns);

	          	if($checkSumOld != $checkSumNew){
	              	$dispCheck = true;
	          	}else{
	              	$dispCheck = false;
	              	continue;
	          	}
	      	}else{
	          	continue;
	      	}

	      	if($commentEncrStatus == "Yes"){
	          	$decrypt_comment =  trim(aes256Decrypt($iter["comments"]));
	          	$trans_comments = preg_replace($patterns, $replace, $decrypt_comment);
	      	}else{
	          	$comments = trim($iter["comments"]);
	          	$trans_comments = preg_replace($patterns, $replace, $comments);
	      	}

	      	//Alter Checksum value records only display here
	      	if($dispCheck){
	          	$dispArr[] = $icnt++;

		        echo '<TR class="oneresult">
		            <TD class="text tamperColor">'; echo oeFormatShortDate(substr($iter["date"], 0, 10)) . substr($iter["date"], 10) . '</TD>
		            <TD class="text tamperColor">'; echo text($iter["user"]) .'</TD>
		            <TD class="text tamperColor">'; echo text($iter["patient_id"]) . '</TD>
		            <TD class="text tamperColor">'; echo text($trans_comments) . '</TD>';
		       if($check_sum) {
			            echo '<TD class="text tamperColor">'; echo text($checkSumNew) . '</TD>
			            <TD class="text tamperColor">'; echo text($checkSumOld) .'</TD>';
		       }
		        echo '</TR>';
	        }
	    }
    }

    if( count($dispArr) == 0 ){
      	echo '<TR class="oneresult">';
          		$colspan = 4;
          		if($check_sum) $colspan=6;
        	echo '<TD class="text" colspan="'; echo $colspan; echo '" align="center">';
          		 	echo xlt('No audit log tampering detected in the selected date range.');
        	echo '</TD>
      	</TR>';
    }else {
      	echo "<script type=\"text/javascript\">$('#display_tamper').css('display', 'block');</script>";
    }
}

?>
