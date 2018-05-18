<?php
/*
 * These functions are common functions used in Patient List report.
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

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/options.inc.php");
require_once("../../library/report_functions.php");

$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

// Prepare a string for CSV export.
function qescape($str) {
	$str = str_replace('\\', '\\\\', $str);
	return str_replace('"', '\\"', $str);
}

// $from_date = fixDate($_POST['form_from_date'], date('Y-01-01'));
// $to_date   = fixDate($_POST['form_to_date'], date('Y-12-31'));
$from_date = fixDate($_POST['form_from_date'], '');
$to_date   = fixDate($_POST['form_to_date'], '');
if (empty($to_date) && !empty($from_date)) $to_date = date('Y-12-31');
if (empty($from_date) && !empty($to_date)) $from_date = date('Y-01-01');

$form_provider = empty($_POST['form_provider']) ? 0 : intval($_POST['form_provider']);

/* This prepares and displays results for Patient List report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function prepareAndShowResults() {
	$totalpts = 0;
  	$query = "SELECT " .
	   "p.fname, p.mname, p.lname, p.street, p.city, p.state, " .
	   "p.postal_code, p.phone_home, p.phone_biz, p.pid, " .
	   "count(e.date) AS ecount, max(e.date) AS edate, " .
	   "i1.date AS idate1, i2.date AS idate2, " .
	   "c1.name AS cname1, c2.name AS cname2 " .
	   "FROM patient_data AS p ";

  	if (!empty($from_date)) {
   		$query .= "JOIN form_encounter AS e ON " .
	   	"e.pid = p.pid AND " .
	   	"e.date >= '$from_date 00:00:00' AND " .
	   	"e.date <= '$to_date 23:59:59' ";
   		if ($form_provider) {
    		$query .= "AND e.provider_id = '$form_provider' ";
   		}
  	} else {
   		if ($form_provider) {
    		$query .= "JOIN form_encounter AS e ON " .
    			"e.pid = p.pid AND e.provider_id = '$form_provider' ";
   		} else {
    		$query .= "LEFT OUTER JOIN form_encounter AS e ON " .
    			"e.pid = p.pid ";
   		}
  	}

  	$query .=
	   "LEFT OUTER JOIN insurance_data AS i1 ON " .
	   "i1.pid = p.pid AND i1.type = 'primary' " .
	   "LEFT OUTER JOIN insurance_companies AS c1 ON " .
	   "c1.id = i1.provider " .
	   "LEFT OUTER JOIN insurance_data AS i2 ON " .
	   "i2.pid = p.pid AND i2.type = 'secondary' " .
	   "LEFT OUTER JOIN insurance_companies AS c2 ON " .
	   "c2.id = i2.provider " .
	   "GROUP BY p.lname, p.fname, p.mname, p.pid, i1.date, i2.date " .
	   "ORDER BY p.lname, p.fname, p.mname, p.pid, i1.date DESC, i2.date DESC";

  	$res = sqlStatement($query);

  	$prevpid = 0;
  	while ($row = sqlFetchArray($res)) {
	   	if ($row['pid'] == $prevpid) continue;
	   	$prevpid = $row['pid'];
	   	$age = '';
	   	if ($row['DOB']) {
		    $dob = $row['DOB'];
		    $tdy = $row['edate'] ? $row['edate'] : date('Y-m-d');
		    $ageInMonths = (substr($tdy,0,4)*12) + substr($tdy,5,2) -
		                   (substr($dob,0,4)*12) - substr($dob,5,2);
		    $dayDiff = substr($tdy,8,2) - substr($dob,8,2);
		    if ($dayDiff < 0) --$ageInMonths;
		    $age = intval($ageInMonths/12);
	   	}

	   	if ($_POST['form_csvexport']) {
		    echo '"' . oeFormatShortDate(substr($row['edate'], 0, 10)) . '",';
		    echo '"' . qescape($row['lname']) . '",';
		    echo '"' . qescape($row['fname']) . '",';
		    echo '"' . qescape($row['mname']) . '",';
		    echo '"' . qescape($row['pid']) . '",';
		    echo '"' . qescape($row['street']) . '",';
		    echo '"' . qescape($row['city']) . '",';
		    echo '"' . qescape($row['state']) . '",';
		    echo '"' . qescape($row['postal_code']) . '",';
		    echo '"' . qescape($row['cname1']) . '",';
		    echo '"' . qescape($row['cname2']) . '"' . "\n";
	   	} else {
		 	echo '<tr>';
			  	echo '<td>';
			   		echo oeFormatShortDate(substr($row['edate'], 0, 10));
			  	echo '</td>';
			  	echo '<td>';
			    	;echo htmlspecialchars( $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] );
			  	echo '</td>';
			  	echo '<td>';
			   		echo $row['pid'];
			  	echo '</td>';
			  	echo '<td>';
			   		echo $row['street'];
			  	echo '</td>';
			  	echo '<td>';
			   		echo $row['city'];
			  	echo '</td>';
			  	echo '<td>';
			   		echo $row['state'];
			  	echo '</td>';
			  	echo '<td>';
			   		echo $row['postal_code'];
			  	echo '</td>';
			  	echo '<td>';
			   		echo $row['cname1'];
			  	echo '</td>';
			  	echo '<td>';
			   		echo $row['cname2'];
			  	echo '</td>';
			echo '</tr>';
	   	} // end not export
	   ++$totalpts;
  	} // end while

  	return $totalpts;
}

?>
