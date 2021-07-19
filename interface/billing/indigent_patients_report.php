<?php
/*
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2005-2015 Rod Roark <rod@sunsetsystems.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Rod Roark <rod@sunsetsystems.com>
 *
 * @link http://librehealth.io
 *
 * This is the Indigent Patients Report.  It displays a summary of
 * encounters within the specified time period for patients without
 * insurance.
 */

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/formatting.inc.php");

$alertmsg = '';
require_once($srcdir."/formatting.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

function bucks($amount) {
  if ($amount) return oeFormatMoney($amount);
  return "";
}

$form_from_date = fixDate($_POST['form_from_date'], date("Y-01-01"));
$form_to_date   = fixDate($_POST['form_to_date'], date("Y-m-d"));

?>
<html>
<head>
<?php html_header_show(); ?>
<style type="text/css">

/* specifically include & exclude from printing */
@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results table {
       margin-top: 0px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
</style><link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
<title><?php echo xlt('Indigent Patients Report'); ?></title>

<script type="text/javascript" src="../../library/report_validation.js"></script>

<?php
  call_required_libraries(array("jquery-min-3-1-1", "iziModalToast"));
?>

<script language="JavaScript">
 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

 function validateInput() {
  return validateFromAndToDates();
 }
</script>

</head>

<body class="body_top">

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Indigent Patients'); ?></span>

<form method='post' action='indigent_patients_report.php' id='theform' onsubmit='return validateInput()'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

<table>
 <tr>
  <td width='410px'>
    <div style='float:left'>

    <table class='text'>
        <tr>
            <td class='label'>
               <?php echo xlt('Visits From'); ?>:
            </td>
            <td>
               <input type='text' name='form_from_date' id="form_from_date" size='10'
                      value='<?php echo htmlspecialchars(oeFormatShortDate($form_from_date)) ?>'>
            </td>
            <td class='label'>
               <?php echo xlt('To'); ?>:
            </td>
            <td>
               <input type='text' name='form_to_date' id="form_to_date" size='10'
                      value='<?php echo htmlspecialchars(oeFormatShortDate($form_to_date)) ?>'>
            </td>
        </tr>
    </table>

    </div>

  </td>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid; width:100%; height:100%' >
        <tr>
            <td>
                <div style='margin-left:15px'>
                    <a href='#' class='css_button cp-submit' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php echo xlt('Submit'); ?>
                    </span>
                    </a>

                    <?php if ($_POST['form_refresh']) { ?>
                    <a href='#' class='css_button cp-output' id='printbutton'>
                        <span>
                            <?php echo xlt('Print'); ?>
                        </span>
                    </a>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
  </td>
 </tr>
</table>
</div> <!-- end of parameters -->

<div id="report_results">
<table>

 <thead bgcolor="#dddddd">
  <th>
   &nbsp;<?php echo xlt('Patient'); ?>
  </th>
  <th>
   &nbsp;<?php echo xlt('SSN'); ?>
  </th>
  <th>
   &nbsp;<?php echo xlt('Invoice'); ?>
  </th>
  <th>
   &nbsp;<?php echo xlt('Svc Date'); ?>
  </th>
  <th>
   &nbsp;<?php echo xlt('Due Date')?>
  </th>
  <th align="right">
   <?php echo xlt('Amount'); ?>&nbsp;
  </th>
  <th align="right">
   <?php echo xlt('Paid'); ?>&nbsp;
  </th>
  <th align="right">
   <?php echo xlt('Balance'); ?>&nbsp;
  </th>
 </thead>

<?php
  if ($_POST['form_refresh']) {

    $where = "";

    if ($form_from_date) {
      $where .= " AND e.date >= '$form_from_date'";
    }
    if ($form_to_date) {
      $where .= " AND e.date <= '$form_to_date'";
    }

    $rez = sqlStatement("SELECT " .
      "e.date, e.encounter, p.pid, p.lname, p.fname, p.mname, p.ss " .
      "FROM form_encounter AS e, patient_data AS p, insurance_data AS i " .
      "WHERE p.pid = e.pid AND i.pid = e.pid AND i.type = 'primary' " .
      "AND i.provider = ''$where " .
      "ORDER BY p.lname, p.fname, p.mname, p.pid, e.date"
    );

    $total_amount = 0;
    $total_paid   = 0;

    for ($irow = 0; $row = sqlFetchArray($rez); ++$irow) {
      $patient_id = $row['pid'];
      $encounter_id = $row['encounter'];
      $invnumber = $row['pid'] . "." . $row['encounter'];
        $inv_duedate = '';
        $arow = sqlQuery("SELECT SUM(fee) AS amount FROM drug_sales WHERE " .
          "pid = ? AND encounter = ?", array($patient_id, $encounter_id));
        $inv_amount = $arow['amount'];
        $arow = sqlQuery("SELECT SUM(fee) AS amount FROM billing WHERE " .
          "pid = ? AND encounter = ? AND " .
          "activity = 1 AND code_type != 'COPAY'", array($patient_id, $encounter_id));
        $inv_amount += $arow['amount'];
        $arow = sqlQuery("SELECT SUM(fee) AS amount FROM billing WHERE " .
          "pid = ? AND encounter = ? AND " .
          "activity = 1 AND code_type = 'COPAY'", array($patient_id, $encounter_id));
        $inv_paid = 0 - $arow['amount'];
        $arow = sqlQuery("SELECT SUM(pay_amount) AS pay, " .
          "sum(adj_amount) AS adj FROM ar_activity WHERE " .
          "pid = ? AND encounter = ?", array($patient_id, $encounter_id));
        $inv_paid   += $arow['pay'];
        $inv_amount -= $arow['adj'];
      $total_amount += bucks($inv_amount);
      $total_paid   += bucks($inv_paid);

      $bgcolor = (($irow & 1) ? "#ffdddd" : "#ddddff");
?>
 <tr bgcolor='<?php  echo attr($bgcolor); ?>'>
  <td class="detail">
   &nbsp;<?php  echo text($row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname']); ?>
  </td>
  <td class="detail">
   &nbsp;<?php  echo text($row['ss']); ?>
  </td>
  <td class="detail">
   &nbsp;<?php  echo text($invnumber); ?></a>
  </td>
  <td class="detail">
   &nbsp;<?php text(date(DateFormatRead(true), strtotime(substr($row['date'], 0, 10)))); ?>
  </td>
  <td class="detail">
   &nbsp;<?php text(date(DateFormatRead(true), strtotime($inv_duedate))); ?>
  </td>
  <td class="detail" align="right">
   <?php  echo text(bucks($inv_amount)); ?>&nbsp;
  </td>
  <td class="detail" align="right">
   <?php  echo text(bucks($inv_paid)); ?>&nbsp;
  </td>
  <td class="detail" align="right">
   <?php  echo text(bucks($inv_amount - $inv_paid)); ?>&nbsp;
  </td>
 </tr>
<?php
    }
?>
 <tr bgcolor='#dddddd'>
  <td class="detail">
   &nbsp;<?php echo xlt('Totals'); ?>
  </td>
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail" align="right">
   <?php  echo text(bucks($total_amount)); ?>&nbsp;
  </td>
  <td class="detail" align="right">
   <?php  echo text(bucks($total_paid)); ?>&nbsp;
  </td>
  <td class="detail" align="right">
   <?php  echo text(bucks($total_amount - $total_paid)); ?>&nbsp;
  </td>
 </tr>
<?php
  }
?>

</table>
</div>

</form>
<script>
<?php
    if ($alertmsg) {
        echo "alert('$alertmsg');\n";
    }
?>
</script>
</body>

<!-- stuff for the popup calendar -->
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>

</html>
