<?php
/*
 * These functions are common functions used in Encounters report.
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
require_once $GLOBALS['srcdir'].'/headers.inc.php';
require_once("../../library/report_functions.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/billing.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$alertmsg = ''; // not used yet but maybe later

$from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$to_date = fixDate($_POST['form_to_date'], date('Y-m-d'));
$form_provider  = $_POST['form_provider'];
$form_facility  = $_POST['form_facility'];
$form_details   = true;
// Get the info.
//
$query = "SELECT " .
  "fe.encounter, fe.date, fe.reason, fe.billing_note, " .
  "f.formdir, f.form_name, " .
  "p.fname, p.mname, p.lname, p.pid, p.pubpid, " .
  "i1.date AS idate1, i1.eDate AS iedate1, i2.date AS idate2, i2.eDate AS iedate2, " .
  "c1.name AS cname1, c2.name AS cname2, " .
  "( SELECT SUM(b.fee) FROM billing AS b WHERE " .
  "b.pid = f.pid AND b.encounter = f.encounter AND " .
  "b.activity = 1 AND b.code_type != 'COPAY' ) AS charges, " .
  "u.lname AS ulname, u.fname AS ufname, u.mname AS umname " .
  "FROM ( form_encounter AS fe, forms AS f ) " .
  "LEFT OUTER JOIN patient_data AS p ON p.pid = fe.pid " .
  "LEFT OUTER JOIN insurance_data AS i1 ON " .
  "i1.pid = p.pid AND i1.type = 'primary' AND i1.inactive != 1 " .
  "LEFT OUTER JOIN insurance_companies AS c1 ON " .
  "c1.id = i1.provider " .
  "LEFT OUTER JOIN insurance_data AS i2 ON " .
  "i2.pid = p.pid AND i2.type = 'secondary' AND i2.inactive != 1 " .
  "LEFT OUTER JOIN insurance_companies AS c2 ON " .
  "c2.id = i2.provider " .
  "LEFT JOIN users AS u ON u.id = fe.provider_id " .
  "WHERE f.pid = fe.pid AND f.encounter = fe.encounter AND f.formdir = 'patient_encounter' ";

if ($to_date) {
	$query .= "AND fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59' ";
} else {
	$query .= "AND fe.date >= '$from_date 00:00:00' AND fe.date <= '$from_date 23:59:59' ";
}

if ($form_provider) {
 	$query .= "AND fe.provider_id = '$form_provider' ";
}

if ($form_facility) {
 	$query .= "AND fe.facility_id = '$form_facility' ";
}

$res = sqlStatement($query);

/*
 * This function displays the results.
 * @params: None
 * @return: Echo HTML
 * @author: Tigpezeghe Rodrige <tigrodrige@gmail.com>
 */
function showResults() {
	global $form_details, $res;
	if ($res) {
	  	$lastdocname = "";
	  	$doc_encounters = 0;

	  	while ($row = sqlFetchArray($res)) {
		    $patient_id = $row['pid'];
		    if ($form_payer_id) {
		     	if ($ins_co_name <> $row['cname1']) continue;
		    }
		    $docname = '';
		    if (!empty($row['ulname']) || !empty($row['ufname'])) {
		      	$docname = $row['ulname'];
		      	if (!empty($row['ufname']) || !empty($row['umname']))
		        	$docname .= ', ' . $row['ufname'] . ' ' . $row['umname'];
		    }

		    $errmsg  = "";
		    if ($form_details) {
		      	// Fetch coding and compute billing status.
		      	$coded = "";
		      	$billed_count = 0;
		      	$unbilled_count = 0;
		      	if ($billres = getBillingByEncounter($row['pid'], $row['encounter'],
		        	"code_type, code, code_text, billed")) {
			        foreach ($billres as $billrow) {
			          	// $title = addslashes($billrow['code_text']);
			          	if ($billrow['code_type'] != 'COPAY' && $billrow['code_type'] != 'TAX') {
			           	 	$coded .= $billrow['code'] . ', ';
			            	if ($billrow['billed']) ++$billed_count; else ++$unbilled_count;
			          	}
			        }
			        $coded = substr($coded, 0, strlen($coded) - 2);
		      	}

		      	// Figure product sales into billing status.
		      	$sres = sqlStatement("SELECT billed FROM drug_sales " .
		        	"WHERE pid = '{$row['pid']}' AND encounter = '{$row['encounter']}'");
		      	while ($srow = sqlFetchArray($sres)) {
		        	if ($srow['billed']) ++$billed_count; else ++$unbilled_count;
		      	}

		      	// Compute billing status.
		      	if ($billed_count && $unbilled_count) $status = xl('Mixed' );
		      	else if ($billed_count              ) $status = xl('Closed');
		      	else if ($unbilled_count            ) $status = xl('Open'  );
		      	else                                  $status = xl('Empty' );

		    	if ($form_category && $chckstatus !='All' ) {
		     		if ($chckstatus <> $status) continue;
		    	}

		 		echo "<tr bgcolor='"; echo $bgcolor; echo "'>";
		  			echo "<td>"; echo text($docname); echo "&nbsp;</td>";
				   	echo "<td>"; echo text(oeFormatShortDate(substr($row['date'], 0, 10))); echo "&nbsp;</td>";
				   	echo "<td>"; echo text($row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']); echo "&nbsp;</td>";
				   	echo "<td>"; echo text($row['pid']); echo "&nbsp;</td>";
				   	echo "<td>"; echo text($status); echo "&nbsp;</td>";
				  	echo "<td>"; echo text($row['reason']); echo "&nbsp;</td>";
				  	echo "<td>"; echo text($row['billing_note']); echo "&nbsp;</td>";
				  	echo "<td>"; echo text($row['cname1']); echo "&nbsp;</td>";
				   	echo "<td>"; echo text($row['encounter']); echo "&nbsp;</td>";
				   	echo "<td>"; echo text($coded); echo "</td>";
				   	echo "<td>"; echo text($row['charges']); echo "</td>";
		 		echo "</tr>";
		    } #else #{
		     # if ($docname != $lastdocname) {
		     #   show_doc_total($lastdocname, $doc_encounters);
		     #   $doc_encounters = 0;
		     # }
		     # ++$doc_encounters;
		    #}
		    $lastdocname = $docname;
	    }
	}
}

?>
