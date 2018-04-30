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

 $sanitize_all_escapes=true;
 $fake_register_globals=false;

 require_once("../globals.php");
 require_once("../../library/report_functions.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/headers.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once "$srcdir/options.inc.php";
 require_once "$srcdir/formdata.inc.php";
 require_once "$srcdir/appointments.inc.php";
 /** Current format date */
 $DateFormat = DateFormatRead();
 $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 $grand_total_units  = 0;
 $grand_total_amt_billed  = 0;
 $grand_total_amt_paid  = 0;
 $grand_total_amt_adjustment  = 0;
 $grand_total_amt_balance  = 0;

if (! acl_check('acct', 'rep')) die(xlt("Unauthorized access."));

if (isset($_POST['form_from_date']) && isset($_POST['form_to_date']) && !empty($_POST['form_to_date']) && $_POST['form_from_date']) {
     $from_date = fixDate($_POST['form_from_date'], date(DateFormatRead(true)));
     $to_date   = fixDate($_POST['form_to_date']  , date(DateFormatRead(true)));
}
   $form_facility  = $_POST['form_facility'];
   $form_provider  = $_POST['form_provider'];

/* This function prepares the results to be displayed
 * @parameters: void
 * @return: void
 * */
function prepareAndShowResults() {
  $rows = array();
  $from_date = $from_date;
  $to_date   = $to_date;
  $sqlBindArray = array();
  $query = "select b.code,sum(b.units) as units,sum(b.fee) as billed,sum(ar_act.paid) as PaidAmount, " .
      "sum(ar_act.adjust) as AdjustAmount,(sum(b.fee)-(sum(ar_act.paid)+sum(ar_act.adjust))) as Balance, " .
      "c.financial_reporting " .
      "FROM form_encounter as fe " .
      "JOIN billing as b on b.pid=fe.pid and b.encounter=fe.encounter " .
      "JOIN (select pid,encounter,code,sum(pay_amount) as paid,sum(adj_amount) as adjust from ar_activity group by pid,encounter,code) as ar_act " .
      "ON ar_act.pid=b.pid and ar_act.encounter=b.encounter and ar_act.code=b.code " .
      "LEFT OUTER JOIN codes AS c ON c.code = b.code " .
      "INNER JOIN code_types AS ct ON ct.ct_key = b.code_type AND ct.ct_fee = '1' " .
      "WHERE b.code_type != 'COPAY' AND b.activity = 1 /* AND b.fee != 0 */ AND " .
      "fe.date >=  ? AND fe.date <= ?";
 array_push($sqlBindArray,"$from_date 00:00:00","$to_date 23:59:59");
  // If a facility was specified.
    if ($form_facility) {
      $query .= " AND fe.facility_id = ?";
     array_push($sqlBindArray,$form_facility);
    }
  // If a provider was specified.
    if ($form_provider) {
      $query .= " AND b.provider_id = ?";
      array_push($sqlBindArray,$form_provider);
    }
    // If selected important codes
    if($_POST['form_details']) {
      $query .= " AND c.financial_reporting = '1'";
    }
    $query .= " GROUP BY b.code ORDER BY b.code, fe.date, fe.id ";
    $res = sqlStatement($query,$sqlBindArray);
    $grand_total_units  = 0;
    $grand_total_amt_billed  = 0;
    $grand_total_amt_paid  = 0;
    $grand_total_amt_adjustment  = 0;
    $grand_total_amt_balance  = 0;

    while ($erow = sqlFetchArray($res)) {
      $row = array();
      $row['pid'] = $erow['pid'];
      $row['provider_id'] = $erow['provider_id'];
      $row['Procedure codes'] = $erow['code'];
      $row['Units'] = $erow['units'];
      $row['Amt Billed'] = $erow['billed'];
      $row['Paid Amt'] = $erow['PaidAmount'];
      $row['Adjustment Amt'] = $erow['AdjustAmount'];
      $row['Balance Amt'] = $erow['Balance'];
      $row['financial_reporting'] = $erow['financial_reporting'];
      $rows[$erow['pid'] . '|' . $erow['code'] . '|' . $erow['units']] = $row;
    }

    if ($_POST['form_csvexport']) {
      // CSV headers:
      if (true) {
        echo '"Procedure codes",';
        echo '"Units",';
        echo '"Amt Billed",';
        echo '"Paid Amt",';
        echo '"Adjustment Amt",';
        echo '"Balance Amt",' . "\n";
      }
    } else {
        echo '<div id="report_results">';
        echo '<table >';
        echo '<thead>';
        echo '<th>';
         echo xlt('Procedure Codes');
        echo '</th>';
        echo '<th >';
         echo xlt('Units');
        echo '</th>';
        echo '<th>';
         echo xlt('Amt Billed');
        echo '</th>';
        echo '<th>';
         echo xlt('Paid Amt');
        echo '</th>';
        echo '<th >';
         echo xlt('Adjustment Amt');
        echo '</th>';
        echo '<th >';
         echo xlt('Balance Amt');
        echo '</th>';
        echo '</thead>';
    }
   $orow = -1;

   foreach ($rows as $key => $row) {
$print = '';
$csv = '';

if($row['financial_reporting']){ $bgcolor = "#FFFFDD";  }else { $bgcolor = "#FFDDDD";  }
$print = "<tr bgcolor='$bgcolor'><td class='detail'>".text($row['Procedure codes'])."</td><td class='detail'>".text($row['Units'])."</td><td class='detail'>".text(oeFormatMoney($row['Amt Billed']))."</td><td class='detail'>".text(oeFormatMoney($row['Paid Amt']))."</td><td class='detail'>".text(oeFormatMoney($row['Adjustment Amt']))."</td><td class='detail'>".text(oeFormatMoney($row['Balance Amt']))."</td>";

$csv = '"' . text($row['Procedure codes']) . '","' . text($row['Units']) . '","' . text(oeFormatMoney($row['Amt Billed'])) . '","' . text(oeFormatMoney($row['Paid Amt'])) . '","' . text(oeFormatMoney($row['Adjustment Amt'])) . '","' . text(oeFormatMoney($row['Balance Amt'])) . '"' . "\n";

$bgcolor = ((++$orow & 1) ? "#ffdddd" : "#ddddff");
                              $grand_total_units  += $row['Units'];
                                              $grand_total_amt_billed  += $row['Amt Billed'];
                                              $grand_total_amt_paid  += $row['Paid Amt'];
                                              $grand_total_amt_adjustment  += $row['Adjustment Amt'];
                                              $grand_total_amt_balance  += $row['Balance Amt'];

      if ($_POST['form_csvexport']) { echo $csv; }
  else { echo $print;
}
   }
     if (!$_POST['form_csvexport']) {
       echo "<tr bgcolor='#ffffff'>\n";
       echo " <td class='detail'>" . xlt("Grand Total") . "</td>\n";
       echo " <td class='detail'>" . text($grand_total_units) . "</td>\n";
       echo " <td class='detail'>" .
       text(oeFormatMoney($grand_total_amt_billed)) . "</td>\n";
       echo " <td class='detail'>" .
       text(oeFormatMoney($grand_total_amt_paid)) . "</td>\n";
       echo " <td class='detail'>" .
       text(oeFormatMoney($grand_total_amt_adjustment)) . "</td>\n";
       echo " <td class='detail'>" .
       text(oeFormatMoney($grand_total_amt_balance)) . "</td>\n";
       echo " </tr>\n";
      echo '</table>';
    echo '</div>';
    }
}

?>
