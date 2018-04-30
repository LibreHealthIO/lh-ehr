<?php
/*
 * These functions are common functions used in Immunization report.
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

include_once("../globals.php");
require_once("$srcdir/headers.inc.php");
include_once("../../library/patient.inc");
include_once("../../library/acl.inc");
require_once("../../library/formatting.inc.php");
require_once("../../library/report_functions.php");

$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$from_date = fixDate($_POST['form_from_date']);
$to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

/* This prepares and displays results for Patient Insurance Allocation report
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */
function prepareAndShowResults() {
	$from_date = fixDate($_POST['form_from_date']);
  	$to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

    $query = " Select b.pid, b.encounter, Sum(b.fee) As charges, " .
  		" (Select Sum(a.adj_amount) From ar_activity As a " .
  		" Where a.pid = b.pid And a.encounter = b.encounter) As adjustments, " .
  		" (Select Sum(a.pay_amount) From ar_activity As a " .
	  	" Where a.pid = b.pid And a.encounter = b.encounter) As payments, " .
	 	" Max(fe.date) As date " .
	  	" From form_encounter As fe, billing As b " .
	  	" Where b.pid = fe.pid And b.encounter = fe.encounter And " .
	  	" fe.date >= '$from_date' And fe.date <= '$to_date' And " .
	 	" b.code_type != 'COPAY' And b.activity > 0 And b.fee != 0 " .
	  	" Group By b.pid, b.encounter " .
	  	" Order By b.pid, b.encounter";

  	$res = sqlStatement($query);
  	$insarr = array();
  	$prev_pid = 0;
  	$patcount = 0;

  	while ($row = sqlFetchArray($res)) {
	    $patient_id = $row['pid'];
	    $encounter_date = $row['date'];
	    $irow = sqlQuery("SELECT insurance_companies.name " .
	      "FROM insurance_data, insurance_companies WHERE " .
	      "insurance_data.pid = $patient_id AND " .
	      "insurance_data.type = 'primary' AND " .
	      "insurance_data.date <= '$encounter_date' AND " .
	      "insurance_companies.id = insurance_data.provider " .
	      "ORDER BY insurance_data.date DESC LIMIT 1");
	    $plan = $irow['name'] ? $irow['name'] : '-- No Insurance --';
	    $insarr[$plan]['visits'] += 1;
	    $insarr[$plan]['charges'] += sprintf('%0.2f', $row['charges']);
	    $insarr[$plan]['adjustments'] += sprintf('%0.2f', $row['adjustments']);
	    $insarr[$plan]['payments'] += sprintf('%0.2f', $row['payments']);
	    if ($patient_id != $prev_pid) {
	      	++$patcount;
	      	$insarr[$plan]['patients'] += 1;
	      	$prev_pid = $patient_id;
	    }
  	}

  	ksort($insarr);

  	if(empty($insarr)) {
  		echo "<p style='color:red; text-align:center;'>No results to display.</p>";
  	} else {
  		while (list($key, $val) = each($insarr)) {
		    if ($_POST['form_csvexport']) {
		        echo '"' . $key                                                . '",';
		        echo '"' . oeFormatMoney($val['charges'])                      . '",';
		        echo '"' . oeFormatMoney($val['adjustments'])                  . '",';
		        echo '"' . oeFormatMoney($val['payments'])                     . '",';
		        echo '"' . $val['visits']                                      . '",';
		        echo '"' . $val['patients']                                    . '",';
		        echo '"' . sprintf("%.1f", $val['patients'] * 100 / $patcount) . '"' . "\n";
		    } else {
				echo "<tr>";
					echo "<td>"; echo $key; echo "</td>";
				  	echo "<td align='right'>";
				   		echo oeFormatMoney($val['charges']);
				  	echo "</td>";
				  	echo "<td align='right'>";
				   		echo oeFormatMoney($val['adjustments']);
				  	echo "</td>";
				  	echo "<td align='right'>";
				   		echo oeFormatMoney($val['payments']);
				  	echo "</td>";
				  	echo "<td align='right'>";
				   		echo $val['visits'];
				  	echo "</td>";
				  	echo "<td align='right'>";
				   		echo $val['patients'];
				  	echo "</td>";
				  	echo "<td align='right'>";
				   		printf("%.1f", $val['patients'] * 100 / $patcount);
				  	echo "</td>";
				echo "</tr>";
			} // end not export
  		} // end while
  	}
}

?>
