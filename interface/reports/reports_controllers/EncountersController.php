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
$form_details   = $_POST['form_details'] ? true : false;
$form_new_patients = $_POST['form_new_patients'] ? true : false;
$form_esigned = $_POST['form_esigned'] ? true : false;
$form_not_esigned = $_POST['form_not_esigned'] ? true : false;
$form_encounter_esigned = $_POST['form_encounter_esigned'] ? true : false;

// Get the info.
//
$esign_fields = '';
$esign_joins = '';

if ($form_encounter_esigned) {
    $esign_fields = ", es.table, es.tid ";
    $esign_joins = "LEFT OUTER JOIN esign_signatures AS es ON es.tid = fe.encounter ";
}

if ($form_esigned) {
    $esign_fields = ", es.table, es.tid ";
    $esign_joins = "LEFT OUTER JOIN esign_signatures AS es ON es.tid = fe.encounter ";
}

if ($form_not_esigned) {
	$esign_fields = ", es.table, es.tid ";
    $esign_joins = "LEFT JOIN esign_signatures AS es on es.tid = fe.encounter ";
}

$query = "SELECT " .
  "fe.encounter, fe.date, fe.reason, " .
  "f.formdir, f.form_name, " .
  "p.fname, p.mname, p.lname, p.pid, p.pubpid, " .
  "u.lname AS ulname, u.fname AS ufname, u.mname AS umname " .
  "$esign_fields" .
  "FROM ( form_encounter AS fe, forms AS f ) " .
  "LEFT OUTER JOIN patient_data AS p ON p.pid = fe.pid " .
  "LEFT JOIN users AS u ON u.id = fe.provider_id " .
  "$esign_joins" .
  "WHERE f.pid = fe.pid AND f.encounter = fe.encounter AND f.formdir = 'patient_encounter' ";
if ($form_to_date) {
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

if ($form_new_patients) {
  	$query .= "AND fe.date = (SELECT MIN(fe2.date) FROM form_encounter AS fe2 WHERE fe2.pid = fe.pid) ";
}

if ($form_encounter_esigned) {
  	$query .= "AND es.tid = fe.encounter AND es.table = 'form_encounter' ";
}

if ($form_esigned) {
  	$query .= "AND es.tid = fe.encounter ";
}

if ($form_not_esigned) {
	$query .= "AND es.tid IS NULL ";
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
			$docname = '';
            if (!empty($row['ulname']) || !empty($row['ufname'])) {
                $docname = $row['ulname'];
                if (!empty($row['ufname']) || !empty($row['umname']))
                  $docname .= ', ' . $row['ufname'] . ' ' . $row['umname'];
            }

            $errmsg  = "";
            if ($form_details) {
                // Fetch all other forms for this encounter.
                $encnames = '';
                $encarr = getFormByEncounter($patient_id, $row['encounter'],
                  "formdir, user, form_name, form_id");
                if($encarr!='') {
                  	foreach ($encarr as $enc) {
                   		if ($enc['formdir'] == 'patient_encounter') continue;
                    	if ($encnames) $encnames .= '<br />';
                    	$encnames .= text($enc['form_name']); // need to html escape it here for output below
                  	}
                }

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

                echo "<tr bgcolor='"; echo $bgcolor; echo "'>";
                  	echo "<td>"; echo text($docname); echo "&nbsp;</td>";
                  	echo "<td>"; echo text(oeFormatShortDate(substr($row['date'], 0, 10))); echo "&nbsp;</td>";
                  	echo "<td>"; echo text($row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']); echo "&nbsp;</td>";
                  	echo "<td>"; echo text($row['pid']); echo "&nbsp;</td>";
                  	echo "<td>"; echo text($status); echo "&nbsp;</td>";
                  	echo "<td>"; echo text($row['reason']); echo "&nbsp;</td>";
                 	echo "<td>"; echo text($row['encounter']); echo "&nbsp;</td>";
                  	echo "<td>"; echo $encnames; /*since this variable contains html, have already html escaped it above*/ echo "&nbsp;</td>";
                  	echo "<td>"; echo text($coded); echo "</td>";
                echo "</tr>";
            } else {
                if ($docname != $lastdocname) {
                	show_doc_total($lastdocname, $doc_encounters);
                    $doc_encounters = 0;
                }
                ++$doc_encounters;
            }
            $lastdocname = $docname;
        }
            if (!$form_details) show_doc_total($lastdocname, $doc_encounters);
    }
}

?>
