<?php
/*
 * These functions are common functions used in Unique seen patients report.
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
 require_once("../../library/report_functions.php");

 /** Current format date */
 $DateFormat = DateFormatRead();
 $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 $from_date = fixDate($_POST['form_from_date'], date($DateFormat));
 $to_date   = fixDate($_POST['form_to_date'], date($DateFormat));

function prepareAndShowResults() {
  $totalpts = 0;

  $query = "SELECT " .
   "p.pid, p.fname, p.mname, p.lname, p.DOB, p.sex, p.ethnoracial, " .
   "p.street, p.city, p.state, p.postal_code, " .
   "count(e.date) AS ecount, max(e.date) AS edate, " .
   "i1.date AS idate1, i2.date AS idate2, " .
   "c1.name AS cname1, c2.name AS cname2 " .
   "FROM patient_data AS p " .
   "JOIN form_encounter AS e ON " .
   "e.pid = p.pid AND " .
   "e.date >= '$from_date 00:00:00' AND " .
   "e.date <= '$to_date 23:59:59' " .
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
    $tdy = $row['edate'];
    $ageInMonths = (substr($tdy,0,4)*12) + substr($tdy,5,2) -
                   (substr($dob,0,4)*12) - substr($dob,5,2);
    $dayDiff = substr($tdy,8,2) - substr($dob,8,2);
    if ($dayDiff < 0) --$ageInMonths;
    $age = intval($ageInMonths/12);
   }

   if ($_POST['form_labels']) {
    echo '"' . $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] . '","' .
      $row['street'] . '","' . $row['city'] . '","' . $row['state'] . '","' .
      $row['postal_code'] . '"' . "\n";
   }
   else { // not labels
 echo '<tr>';
  echo '<td>';
   echo oeFormatShortDate(substr($row['edate'], 0, 10));
  echo '</td>';
  echo '<td>';
   echo $row['lname'] . ',' . $row['fname'] . ' ' . $row['mname'];
  echo '</td>';
  echo '<td style="text-align:center">';
    echo $row['ecount'];
  echo '</td>';
  echo '<td>';
   echo $age;
  echo '</td>';
  echo '<td>';
    echo $row['sex'];
  echo '</td>';
  echo '<td>';
    echo $row['ethnoracial'];
  echo '</td>';
  echo '<td>';
   echo $row['cname1'];
  echo '</td>';
  echo '<td>';
   echo $row['cname2'];
  echo '</td>';
 echo '</tr>';
   } // end not labels
   ++$totalpts;
  }

  if (!$_POST['form_labels']) {
 echo '<tr class="report_totals">';
  echo '<td colspan="2">';
    xl('Total Number of Patients','e');
  echo '</td>';
  echo '<td style="padding-left: 20px;">';
   echo $totalpts;
  echo '</td>';
  echo '<td colspan="5">&nbsp;</td>';
 echo '</tr>';
  } // end not labels
}

?>
