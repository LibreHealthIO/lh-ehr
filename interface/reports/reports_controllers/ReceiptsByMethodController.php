<?php
/*
 * These functions are common functions used in Receipt by method report.
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
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/headers.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/formdata.inc.php");
 require_once("../../custom/code_types.inc.php");
 require_once("../../library/report_functions.php");

 /** Current format date */
 $DateFormat = DateFormatRead();
 $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 // This controls whether we show pt name, policy number and DOS.
 $showing_ppd = true;

 $insarray = array();

 function thisLineItem($patient_id, $encounter_id, $memo, $transdate,
   $rowmethod, $rowpayamount, $rowadjamount, $payer_type=0, $irnumber='')
 {
   global $form_report_by, $insarray, $grandpaytotal, $grandadjtotal;

   if ($form_report_by != '1') { // reporting by method or check number
     showLineItem($patient_id, $encounter_id, $memo, $transdate,
       $rowmethod, $rowpayamount, $rowadjamount, $payer_type, $irnumber);
     return;
   }

   // Reporting by payer.
   //
   if ($_POST['form_details']) { // details are wanted
     // Save everything for later sorting.
     $insarray[] = array($patient_id, $encounter_id, $memo, $transdate,
       $rowmethod, $rowpayamount, $rowadjamount, $payer_type, $irnumber);
   }
   else { // details not wanted
     if (empty($insarray[$rowmethod])) $insarray[$rowmethod] = array(0, 0);
     $insarray[$rowmethod][0] += $rowpayamount;
     $insarray[$rowmethod][1] += $rowadjamount;
     $grandpaytotal  += $rowpayamount;
     $grandadjtotal  += $rowadjamount;
   }
 }

 function showLineItem($patient_id, $encounter_id, $memo, $transdate,
   $rowmethod, $rowpayamount, $rowadjamount, $payer_type=0, $irnumber='')
 {
   global $paymethod, $paymethodleft, $methodpaytotal, $methodadjtotal,
     $grandpaytotal, $grandadjtotal, $showing_ppd;

   if (! $rowmethod) $rowmethod = 'Unknown';

   $invnumber = $irnumber ? $irnumber : "$patient_id.$encounter_id";

   if ($paymethod != $rowmethod) {
     if ($paymethod) {
       // Print method total.

  echo '<tr bgcolor="#ddddff">';
   echo '<td class="detail" colspan="'; echo $showing_ppd ? 7 : 4; echo '">';
    echo xl('Total for '); echo $paymethod;
   echo '</td>';
   echo '<td align="right">';
    bucks($methodadjtotal);
   echo '</td>';
   echo '<td align="right">';
     bucks($methodpaytotal);
   echo '</td>';
  echo '</tr>';
     }
     $methodpaytotal = 0;
     $methodadjtotal  = 0;
     $paymethod = $rowmethod;
     $paymethodleft = $paymethod;
   }

   if ($_POST['form_details']) {

  echo '<tr>';
   echo '<td class="detail">';
    echo $paymethodleft; $paymethodleft = "&nbsp;";
   echo '</td>';
   echo '<td>';
    echo oeFormatShortDate($transdate);
   echo '</td>';
   echo '<td class="detail">';
    echo $invnumber;
   echo '</td>';

     if ($showing_ppd) {
       $pferow = sqlQuery("SELECT p.fname, p.mname, p.lname, fe.date " .
         "FROM patient_data AS p, form_encounter AS fe WHERE " .
         "p.pid = '$patient_id' AND fe.pid = p.pid AND " .
         "fe.encounter = '$encounter_id' LIMIT 1");
       $dos = substr($pferow['date'], 0, 10);

       echo "  <td class='dehead'>\n";
       echo "   " . $pferow['lname'] . ", " . $pferow['fname'] . " " . $pferow['mname'];
       echo "  </td>\n";

       echo "  <td class='dehead'>\n";
       if ($payer_type) {
         $ptarr = array(1 => 'primary', 2 => 'secondary', 3 => 'tertiary');
         $insrow = getInsuranceDataByDate($patient_id, $dos,
           $ptarr[$payer_type], "policy_number");
         echo "   " . $insrow['policy_number'];
       }
       echo "  </td>\n";

       echo "  <td class='dehead'>\n";
       echo "   " . oeFormatShortDate($dos) . "\n";
       echo "  </td>\n";
     }

   echo '<td>';
    echo $memo;
   echo '</td>';
   echo '<td align="right">';
    bucks($rowadjamount);
   echo '</td>';
   echo '<td align="right">';
    bucks($rowpayamount);
   echo '</td>';
  echo '</tr>';
   }
   $methodpaytotal += $rowpayamount;
   $grandpaytotal  += $rowpayamount;
   $methodadjtotal += $rowadjamount;
   $grandadjtotal  += $rowadjamount;
 }

 // This is called by usort() when reporting by payer with details.
 // Sorts by payer/date/patient/encounter/memo.
 function payerCmp($a, $b) {
   foreach (array(4,3,0,1,2,7) as $i) {
     if ($a[$i] < $b[$i]) return -1;
     if ($a[$i] > $b[$i]) return  1;
   }
   return 0;
 }

 if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));


 $from_date = fixDate($_POST['form_from_date'], date(DateFormatRead(true)));
 $to_date   = fixDate($_POST['form_to_date']  , date(DateFormatRead(true)));
 $form_use_edate = $_POST['form_use_edate'];
 $form_facility  = $_POST['form_facility'];
 $form_report_by = $_POST['form_report_by'];
 $form_proc_codefull = trim($_POST['form_proc_codefull']);
 // Parse the code type and the code from <code_type>:<code>
 $tmp_code_array = explode(':',$form_proc_codefull);
 $form_proc_codetype = $tmp_code_array[0];
 $form_proc_code = $tmp_code_array[1];

 /* This function prepares the results to be displayed
  * @parameters: void
  * @return: void
  * */
function prepareAndShowResults() {

  global $paymethod, $paymethodleft, $methodpaytotal, $methodadjtotal,
    $grandpaytotal, $grandadjtotal, $showing_ppd, $from_date, $to_date,
    $form_proc_code, $form_proc_codetype, $form_facility, $form_use_edate,
    $form_report_by, $insarray;

  $from_date = $from_date;
  $to_date   = $to_date;

  $paymethod   = "";
  $paymethodleft = "";
  $methodpaytotal = 0;
  $grandpaytotal  = 0;
  $methodadjtotal  = 0;
  $grandadjtotal  = 0;


    // Get co-pays using the encounter date as the pay date.  These will
    // always be considered patient payments.  Ignored if selecting by
    // billing code.
    //
    if (!$form_proc_code || !$form_proc_codetype) {
      $query = "SELECT b.fee, b.pid, b.encounter, b.code_type, " .
        "fe.date, fe.facility_id, fe.invoice_refno " .
        "FROM billing AS b " .
        "JOIN form_encounter AS fe ON fe.pid = b.pid AND fe.encounter = b.encounter " .
        "WHERE b.code_type = 'COPAY' AND b.activity = 1 AND b.fee != 0 AND " .
        "fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59'";
      // If a facility was specified.
      if ($form_facility) $query .= " AND fe.facility_id = '$form_facility'";
      $query .= " ORDER BY fe.date, b.pid, b.encounter, fe.id";
      //
      $res = sqlStatement($query);
      while ($row = sqlFetchArray($res)) {
        $rowmethod = $form_report_by == 1 ? 'Patient' : 'Co-Pay';
        thisLineItem($row['pid'], $row['encounter'], $row['code_text'],
          substr($row['date'], 0, 10), $rowmethod, 0 - $row['fee'], 0, 0, $row['invoice_refno']);
      }
    } // end if not form_proc_code

    // Get all other payments and adjustments and their dates, corresponding
    // payers and check reference data, and the encounter dates separately.
    //
    $query = "SELECT a.pid, a.encounter, a.post_time, a.pay_amount, " .
      "a.adj_amount, a.memo, a.session_id, a.code, a.payer_type, fe.id, fe.date, " .
      "fe.invoice_refno, s.deposit_date, s.payer_id, s.reference, i.name " .
      "FROM ar_activity AS a " .
      "JOIN form_encounter AS fe ON fe.pid = a.pid AND fe.encounter = a.encounter " .
      "JOIN forms AS f ON f.pid = a.pid AND f.encounter = a.encounter AND f.formdir = 'patient_encounter' " .
      "LEFT JOIN ar_session AS s ON s.session_id = a.session_id " .
      "LEFT JOIN insurance_companies AS i ON i.id = s.payer_id " .
      "WHERE ( a.pay_amount != 0 OR a.adj_amount != 0 )";
    //
    if ($form_use_edate) {
      $query .= " AND fe.date >= '$from_date 00:00:00' AND fe.date <= '$to_date 23:59:59'";
    } else {
      $query .= " AND ( ( s.deposit_date IS NOT NULL AND " .
        "s.deposit_date >= '$from_date' AND s.deposit_date <= '$to_date' ) OR " .
        "( s.deposit_date IS NULL AND a.post_time >= '$from_date 00:00:00' AND " .
        "a.post_time <= '$to_date 23:59:59' ) )";
    }
    // If a procedure code was specified.
    if ($form_proc_code && $form_proc_codetype) {
      // if a code_type is entered into the ar_activity table, then use it. If it is not entered in, then do not use it.
      $query .= " AND ( a.code_type = '$form_proc_codetype' OR a.code_type = '' ) AND a.code LIKE '$form_proc_code%'";
    }
    // If a facility was specified.
    if ($form_facility) $query .= " AND fe.facility_id = '$form_facility'";
    //
    if ($form_use_edate) {
      $query .= " ORDER BY s.reference, fe.date, a.pid, a.encounter, fe.id";
    } else {
      $query .= " ORDER BY s.reference, s.deposit_date, a.post_time, a.pid, a.encounter, fe.id";
    }
    //
    $res = sqlStatement($query);
    while ($row = sqlFetchArray($res)) {
      if ($form_use_edate) {
        $thedate = substr($row['date'], 0, 10);
      } else if (!empty($row['deposit_date'])) {
        $thedate = $row['deposit_date'];
      } else {
        $thedate = substr($row['post_time'], 0, 10);
      }
      // Compute reporting key: insurance company name or payment method.
      if ($form_report_by == '1') {
        if (empty($row['payer_id'])) {
          $rowmethod = '';
        } else {
          if (empty($row['name'])) $rowmethod = xl('Unnamed insurance company');
          else $rowmethod = $row['name'];
        }
      }
      else {
        if (empty($row['session_id'])) {
          $rowmethod = trim($row['memo']);
        } else {
          $rowmethod = trim($row['reference']);
        }
        if ($form_report_by != '3') {
          // Extract only the first word as the payment method because any
          // following text will be some petty detail like a check number.
          $rowmethod = substr($rowmethod, 0, strcspn($rowmethod, ' /'));
        }
      }
      //
      thisLineItem($row['pid'], $row['encounter'], $row['code'], $thedate,
        $rowmethod, $row['pay_amount'], $row['adj_amount'], $row['payer_type'],
        $row['invoice_refno']);
    }

  // Not payer summary.
  if ($form_report_by != '1' || $_POST['form_details']) {

    if ($form_report_by == '1') { // by payer with details
      // Sort and dump saved info, and consolidate items with all key
      // fields being the same.
      usort($insarray, 'payerCmp');
      $b = array();
      foreach ($insarray as $a) {
        if (empty($a[4])) $a[4] = xl('Patient');
        if (empty($b)) {
          $b = $a;
        }
        else {
          $match = true;
          foreach (array(4,3,0,1,2,7) as $i) if ($a[$i] != $b[$i]) $match = false;
          if ($match) {
            $b[5] += $a[5];
            $b[6] += $a[6];
          } else {
            showLineItem($b[0], $b[1], $b[2], $b[3], $b[4], $b[5], $b[6], $b[7], $b[8]);
            $b = $a;
          }
        }
      }
      if (!empty($b)) {
        showLineItem($b[0], $b[1], $b[2], $b[3], $b[4], $b[5], $b[6], $b[7], $b[8]);
      }
    } // end by payer with details

    // Print last method total.
 echo '<tr bgcolor="#ddddff">';
  echo '<td class="detail" colspan="'; echo $showing_ppd ? 7 : 4; echo '">';
   echo xl('Total for ') . $paymethod;
  echo '</td>';
  echo '<td align="right">';
   bucks($methodadjtotal);
  echo '</td>';
  echo '<td align="right">';
   bucks($methodpaytotal);
  echo '</td>';
 echo '</tr>';
  }

  // Payer summary: need to sort and then print it all.
  else {
    ksort($insarray);
    foreach ($insarray as $key => $value) {
      if (empty($key)) $key = xl('Patient');
 echo '<tr bgcolor="#ddddff">';
  echo '<td class="detail" colspan="'; echo $showing_ppd ? 7 : 4; echo '">';
   echo $key;
  echo '</td>';
  echo '<td align="right">';
   bucks($value[1]);
  echo '</td>';
  echo '<td align="right">';
   bucks($value[0]);
  echo '</td>';
 echo '</tr>';
    } // end foreach
  } // end payer summary
 echo '<tr bgcolor="#ffdddd">';
  echo '<td class="detail" colspan="'; echo $showing_ppd ? 7 : 4; echo'">';
    xl('Grand Total','e');
  echo '</td>';
  echo '<td align="right">';
   bucks($grandadjtotal);
  echo '</td>';
  echo '<td align="right">';
   bucks($grandpaytotal);
  echo '</td>';
 echo '</tr>';

}
?>
