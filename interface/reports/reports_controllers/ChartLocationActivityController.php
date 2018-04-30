<?php
/*
 * These functions are common functions used in Alerts Log.
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

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../../library/report_functions.php");

$form_patient_id = trim($_POST['form_patient_id']);

/* This function checks for patient, and gets the patient's data
 * @params: None
 * @return: None
 * */
function checkPatientId() {
	$curr_pid = $pid;
	$ptrow = array();
	if (!empty($form_patient_id)) {
	  	$query = "SELECT pid, fname, mname, lname FROM patient_data WHERE " .
	    	"pid = ? ORDER BY pid LIMIT 1";
	  	$ptrow = sqlQuery($query,array($form_patient_id));
	  	if (empty($ptrow)) {
	    	$curr_pid = 0;
	    	echo "<font color='red'>" . xlt('Chart ID') . " '" . text($form_patient_id) . "' " . xlt('not found!') . "</font><br />&nbsp;<br />";
	  	}
	  	else {
	    	$curr_pid = $ptrow['pid'];
	  	}
	}
	else if (!empty($curr_pid)) {
	  	$query = "SELECT pid, fname, mname, lname FROM patient_data WHERE " .
	    	"pid = ?";
	  	$ptrow = sqlQuery($query,array($curr_pid));
	  	$form_patient_id = $ptrow['pid'];
	}
	if (!empty($ptrow)) {
	  	echo '<span class="title">' . text(xl('for','','',' '));
	  	echo text($ptrow['lname']) . ', ' . text($ptrow['fname']) . ' ' . text($ptrow['mname']) . ' ';
	  	echo "(" . text($ptrow['pid']) . ")";
	  	echo "</span>\n";
	}
}

/* This function shows results for the search
 * @params: None
 * @return: None
 * */
function showResults() {
	if ($_POST['form_refresh'] || !empty($ptrow) ) {
		echo "<div id='report_results'>
				<table>
				 <thead>
				  <th>"; echo xlt('Time'); echo "</th>
				  <th>"; echo xlt('Destination'); echo "</th>
				 </thead>
				 <tbody>";
		$row = array();
		if (!empty($ptrow)) {
		 	$query = "SELECT ct.ct_when, ct.ct_userid, ct.ct_location, " .
		    	"u.username, u.fname, u.mname, u.lname " .
		    	"FROM chart_tracker AS ct " .
		    	"LEFT OUTER JOIN users AS u ON u.id = ct.ct_userid " .
		    	"WHERE ct.ct_pid = ? " .
		    	"ORDER BY ct.ct_when DESC";
		  	$res = sqlStatement($query,array($curr_pid));

		  	while ($row = sqlFetchArray($res)) {
				echo "<tr>
			  			<td>";
			    echo text(oeFormatShortDate(substr($row['ct_when'], 0, 10))) . text(substr($row['ct_when'], 10));
			 	  echo "</td>
			  			<td>";
			    if (!empty($row['ct_location'])) {
			      echo generate_display_field(array('data_type'=>'1','list_id'=>'chartloc'),$row['ct_location']);
			    }
			    else if (!empty($row['ct_userid'])) {
			      echo text($row['lname']) . ', ' . text($row['fname']) . ' ' . text($row['mname']);
			    }
			  	  echo "</td>
			 		</tr>";
			} // end while
	 	} // end if
		echo "</tbody>
		</table>
		</div>"; //<!-- end of results -->
		} else {
			echo "<div class='text'>";
			 	 echo xlt('Please input search criteria above, and click Submit to view results.');
			echo "</div>";
		}
}

?>
