<?php
/*
 * These functions are common functions used in the Appointments and Encounters reports.
 * They have pulled out and placed in this file. This is done to prepare the for building a
 * report generator.
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

require_once("../globals.php");
require_once("../../library/report_functions.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once("../../custom/code_types.inc.php");
require_once("$srcdir/billing.inc");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$errmsg  = "";
$alertmsg = ''; // not used yet but maybe later
$grand_total_charges    = 0;
$grand_total_copays     = 0;
$grand_total_encounters = 0;

$form_facility  = isset($_POST['form_facility']) ? $_POST['form_facility'] : '';
$from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$to_date = fixDate($_POST['form_to_date'], date('Y-m-d'));
if ($_POST['form_refresh']) {
	$from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
	  $to_date = fixDate($_POST['form_to_date'], "");

	// MySQL doesn't grok full outer joins so we do it the hard way.
	//
	$query = "( " .
	   	"SELECT " .
	   	"e.pc_eventDate, e.pc_startTime, " .
	   	"fe.encounter, fe.date AS encdate, " .
	   	"f.authorized, " .
	   	"p.fname, p.lname, p.pid, " .
	   	"CONCAT( u.lname, ', ', u.fname ) AS docname " .
	   	"FROM libreehr_postcalendar_events AS e " .
	   	"LEFT OUTER JOIN form_encounter AS fe " .
	   	"ON fe.date = e.pc_eventDate AND fe.pid = e.pc_pid " .
	   	"LEFT OUTER JOIN forms AS f ON f.pid = fe.pid AND f.encounter = fe.encounter AND f.formdir = 'patient_encounter' " .
	   	"LEFT OUTER JOIN patient_data AS p ON p.pid = e.pc_pid " .
	   	// "LEFT OUTER JOIN users AS u ON BINARY u.username = BINARY f.user WHERE ";
	   	"LEFT OUTER JOIN users AS u ON u.id = fe.provider_id WHERE ";
	if ($to_date) {
	   	$query .= "e.pc_eventDate >= '$from_date' AND e.pc_eventDate <= '$to_date' ";
	} else {
	   	$query .= "e.pc_eventDate = '$from_date' ";
	}
	if ($form_facility !== '') {
	   	$query .= "AND e.pc_facility = '" . add_escape_custom($form_facility) . "' ";
	}
	// $query .= "AND ( e.pc_catid = 5 OR e.pc_catid = 9 OR e.pc_catid = 10 ) " .
	$query .= "AND e.pc_pid != '' AND e.pc_apptstatus != '?' " .
	   	") UNION ( " .
	   	"SELECT " .
	   	"e.pc_eventDate, e.pc_startTime, " .
	   	"fe.encounter, fe.date AS encdate, " .
	   	"f.authorized, " .
	   	"p.fname, p.lname, p.pid, " .
	   	"CONCAT( u.lname, ', ', u.fname ) AS docname " .
	   	"FROM form_encounter AS fe " .
	   	"LEFT OUTER JOIN libreehr_postcalendar_events AS e " .
	   	"ON fe.date = e.pc_eventDate AND fe.pid = e.pc_pid AND " .
	   	// "( e.pc_catid = 5 OR e.pc_catid = 9 OR e.pc_catid = 10 ) " .
	   	"e.pc_pid != '' AND e.pc_apptstatus != '?' " .
	   	"LEFT OUTER JOIN forms AS f ON f.pid = fe.pid AND f.encounter = fe.encounter AND f.formdir = 'patient_encounter' " .
	   	"LEFT OUTER JOIN patient_data AS p ON p.pid = fe.pid " .
	   	// "LEFT OUTER JOIN users AS u ON BINARY u.username = BINARY f.user WHERE ";
	   	"LEFT OUTER JOIN users AS u ON u.id = fe.provider_id WHERE ";
	if ($to_date) {
	   	// $query .= "LEFT(fe.date, 10) >= '$from_date' AND LEFT(fe.date, 10) <= '$to_date' ";
	   	$query .= "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59' ";
	} else {
	   	// $query .= "LEFT(fe.date, 10) = '$from_date' ";
	   	$query .= "fe.date >= '$from_date 00:00:00' AND fe.date <= '$from_date 23:59:59' ";
	}
	if ($form_facility !== '') {
	   	$query .= "AND fe.facility_id = '" . add_escape_custom($form_facility) . "' ";
	}
	$query .= ") ORDER BY docname, IFNULL(pc_eventDate, encdate), pc_startTime";

	$res = sqlStatement($query);
}

/* Attribution: 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * and 2005-2010 Rod Roark <rod@sunsetsystems.com>*/
function postError($msg) {
  	global $errmsg;
  	if ($errmsg) $errmsg .= '<br />';
  	$errmsg .= $msg;
}

/* Attribution: 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * and 2005-2010 Rod Roark <rod@sunsetsystems.com>*/
function bucks($amount) {
 	if ($amount) echo oeFormatMoney($amount);
}

/* Attribution: 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * and 2005-2010 Rod Roark <rod@sunsetsystems.com>*/
function endDoctor(&$docrow) {
	global $grand_total_charges, $grand_total_copays, $grand_total_encounters;
	if (!$docrow['docname']) return;

	echo " <tr class='report_totals'>\n";
	echo "  <td colspan='5'>\n";
	echo "   &nbsp;" . xl('Totals for','','',' ') . $docrow['docname'] . "\n";
	echo "  </td>\n";
	echo "  <td align='right'>\n";
	echo "   &nbsp;" . $docrow['encounters'] . "&nbsp;\n";
	echo "  </td>\n";
	echo "  <td align='right'>\n";
	echo "   &nbsp;"; bucks($docrow['charges']); echo "&nbsp;\n";
	echo "  </td>\n";
	echo "  <td align='right'>\n";
	echo "   &nbsp;"; bucks($docrow['copays']); echo "&nbsp;\n";
	echo "  </td>\n";
	echo "  <td colspan='2'>\n";
	echo "   &nbsp;\n";
	echo "  </td>\n";
	echo " </tr>\n";

	$grand_total_charges     += $docrow['charges'];
	$grand_total_copays      += $docrow['copays'];
	$grand_total_encounters  += $docrow['encounters'];

	$docrow['charges']     = 0;
	$docrow['copays']      = 0;
	$docrow['encounters']  = 0;

	$totals = array('grand_total_charges' => $grand_total_charges,
	  				'grand_total_copays' => $grand_total_copays,
	  				'grand_total_encounters' => $grand_total_encounters);
	return $totals;
}

/* This function is for displaying the appointment and encou
 * @params: $res--> Valid response from query
 * @return: void - Simply echo HTML encoded string
 * @author: Tigpezeghe Rodrige .K
 */
function displayResult($res) {
	$docrow = array('docname' => '', 'charges' => 0, 'copays' => 0, 'encounters' => 0);

  while ($row = sqlFetchArray($res)) { // start of row
    $patient_id = $row['pid'];
    $encounter  = $row['encounter'];
    $docname    = $row['docname'] ? $row['docname'] : xl('Unknown');

    if ($docname != $docrow['docname']) {
      endDoctor($docrow);
    }

    $errmsg  = "";
    $billed  = "Y";
    $charges = 0;
    $copays  = 0;
    $gcac_related_visit = false;

    // Scan the billing items for status and fee total.
    //
    $query = "SELECT code_type, code, modifier, authorized, billed, fee, justify " .
              "FROM billing WHERE " .
              "pid = '$patient_id' AND encounter = '$encounter' AND activity = 1";
    $bres = sqlStatement($query);

    while ($brow = sqlFetchArray($bres)) {
      $code_type = $brow['code_type'];
      if ($code_types[$code_type]['fee'] && !$brow['billed'])
        $billed = "";
      if (!$GLOBALS['simplified_demographics'] && !$brow['authorized'])
        postError(xl('Needs Auth'));
      if ($code_types[$code_type]['just']) {
        if (! $brow['justify']) postError(xl('Needs Justify'));
      }
      if ($code_types[$code_type]['fee']) {
        $charges += $brow['fee'];
        if ($brow['fee'] == 0 && !$GLOBALS['ippf_specific']) postError(xl('Missing Fee'));
      } else {
        if ($brow['fee'] != 0) postError(xl('Fee is not allowed'));
      }

      // Custom logic for IPPF to determine if a GCAC issue applies.
      if ($GLOBALS['ippf_specific']) {
        if (!empty($code_types[$code_type]['fee'])) {
          $query = "SELECT related_code FROM codes WHERE code_type = '" .
                    $code_types[$code_type]['id'] . "' AND " .
                    "code = '" . $brow['code'] . "' AND ";
          if ($brow['modifier']) {
            $query .= "modifier = '" . $brow['modifier'] . "'";
          } else {
            $query .= "(modifier IS NULL OR modifier = '')";
          }
          $query .= " LIMIT 1";
          $tmp = sqlQuery($query);
          $relcodes = explode(';', $tmp['related_code']);
          foreach ($relcodes as $codestring) {
            if ($codestring === '') continue;
            list($codetype, $code) = explode(':', $codestring);
            if ($codetype !== 'IPPF') continue;
            if (preg_match('/^25222/', $code)) $gcac_related_visit = true;
          }
        }
      } // End IPPF stuff
    } // end while
               
    $copays -= getPatientCopay($patient_id,$encounter);

    // The following is removed, perhaps temporarily, because gcac reporting
    // no longer depends on gcac issues.  -- Rod 2009-08-11
    /******************************************************************
    // More custom code for IPPF.  Generates an error message if a
    // GCAC issue is required but is not linked to this visit.
    if (!$errmsg && $gcac_related_visit) {
      $grow = sqlQuery("SELECT l.id, l.title, l.begdate, ie.pid " .
              "FROM lists AS l " .
              "LEFT JOIN issue_encounter AS ie ON ie.pid = l.pid AND " .
              "ie.encounter = '$encounter' AND ie.list_id = l.id " .
              "WHERE l.pid = '$patient_id' AND " .
              "l.activity = 1 AND l.type = 'ippf_gcac' " .
              "ORDER BY ie.pid DESC, l.begdate DESC LIMIT 1");
      // Note that reverse-ordering by ie.pid is a trick for sorting
      // issues linked to the encounter (non-null values) first.
      if (empty($grow['pid'])) { // if there is no linked GCAC issue
        if (empty($grow)) { // no GCAC issue exists
          $errmsg = "GCAC issue does not exist";
        } else { // there is one but none is linked
          $errmsg = "GCAC issue is not linked";
        }
      }
    }
    ******************************************************************/
    if ($gcac_related_visit) {
      $grow = sqlQuery("SELECT COUNT(*) AS count FROM forms " .
              "WHERE pid = '$patient_id' AND encounter = '$encounter' AND " .
              "deleted = 0 AND formdir = 'LBFgcac'");
      if (empty($grow['count'])) { // if there is no gcac form
        postError(xl('GCAC visit form is missing'));
      }
    } // end if
    /*****************************************************************/

    if (!$billed) postError($GLOBALS['simplified_demographics'] ?
      xl('Not checked out') : xl('Not billed'));

    if (!$encounter) postError(xl('No visit'));

    if (! $charges) $billed = "";

    $docrow['charges'] += $charges;
    $docrow['copays']  += $copays;
    if ($encounter) ++$docrow['encounters'];

    if ($_POST['form_details']) {
      echo "<tr>
        <td>&nbsp;"; echo ($docname == $docrow['docname']) ? "" : $docname; echo"</td>
        <td>&nbsp;";
          /*****************************************************************
          if ($to_date) {
            echo $row['pc_eventDate'] . '<br>';
            echo substr($row['pc_startTime'], 0, 5);
          }
          *****************************************************************/
        if (empty($row['pc_eventDate'])) {
          echo oeFormatShortDate(substr($row['encdate'], 0, 10));
        } else {
          echo oeFormatShortDate($row['pc_eventDate']) . ' ' . substr($row['pc_startTime'], 0, 5);
        }
        echo "</td>
        <td>&nbsp;"; echo $row['fname'] . " " . $row['lname']; echo"</td>
        <td>&nbsp;"; echo $row['pid']; echo"</td>
        <td align='right'>"; echo $row['pid']; echo "&nbsp;</td>
        <td align='right'>";  echo $encounter; echo "&nbsp;</td>
        <td align='right'>";  bucks($charges); echo "&nbsp;</td>
        <td align='right'>";  bucks($copays); echo "&nbsp;</td>
        <td>";  echo $billed; echo "</td>
        <td style='color:#cc0000'>"; echo $errmsg; echo "&nbsp;</td>
      </tr>";
    } // end of details line

    $docrow['docname'] = $docname;
  } // end of row

  $totals = endDoctor($docrow);
    echo " <tr class='report_totals'>\n";
    echo "  <td colspan='5'>\n";
    echo "   &nbsp;"; echo xl('Grand Totals'); echo "\n";
    echo "  </td>\n";
    echo "  <td align='right'>\n";
    echo "   &nbsp;"; echo $totals['grand_total_encounters']; echo "&nbsp;\n";
    echo "  </td>\n";
    echo "  <td align='right'>\n";
    echo "   &nbsp;"; bucks($totals['grand_total_charges']); echo "&nbsp;\n";
    echo "  </td>\n";
    echo "  <td align='right'>\n";
    echo "   &nbsp;"; bucks($totals['grand_total_copays']); echo "&nbsp;\n";
    echo "  </td>\n";
    echo "  <td colspan='2'>\n";
    echo "   &nbsp;\n";
    echo "  </td>\n";
    echo " </tr>\n";
}

?>
