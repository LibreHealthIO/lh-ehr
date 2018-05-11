<?php
/*
 * Pending Orders
 *
 * Copyright (C) 2017 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2010-2013 Rod Roark <rod@sunsetsystems.com>
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
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Rod Roark <rod@sunsetsystems.com>
 * @link http://librehealth.io
 */

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/headers.inc.php";
require_once "$srcdir/formdata.inc.php";
require_once($GLOBALS['srcdir']."/formatting.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

function thisLineItem($row) {
  $provname = $row['provider_lname'];
  if (!empty($row['provider_fname'])) {
    $provname .= ', ' . $row['provider_fname'];
    if (!empty($row['provider_mname'])) {
      $provname .= ' ' . $row['provider_mname'];
    }
  }

  if ($_POST['form_csvexport']) {
    echo '"' . addslashes($row['patient_name'  ]) . '",';
    echo '"' . addslashes($row['pid'        ]) . '",';
    echo '"' . addslashes(oeFormatShortDate($row['date_ordered'  ])) . '",';
    echo '"' . addslashes($row['organization'  ]) . '",';
    echo '"' . addslashes($provname             ) . '",';
    echo '"' . addslashes($row['priority_name' ]) . '",';
    echo '"' . addslashes($row['status_name'   ]) . '"' . "\n";
  }
  else {
?>
 <tr>
  <td class="detail"><?php echo $row['patient_name'  ]; ?></td>
  <td class="detail"><?php echo $row['pid'        ]; ?></td>
  <td class="detail"><?php echo oeFormatShortDate($row['date_ordered'  ]); ?></td>
  <td class="detail"><?php echo $row['organization'  ]; ?></td>
  <td class="detail"><?php echo $provname; ?></td>
  <td class="detail"><?php echo $row['priority_name' ]; ?></td>
  <td class="detail"><?php echo $row['status_name'   ]; ?></td>
 </tr>
<?php
  } // End not csv export
}

if (! acl_check('acct', 'rep')) die(xl("Unauthorized access."));

$form_from_date = fixDate($_POST['form_from_date'], date('Y-m-d'));
$form_to_date   = fixDate($_POST['form_to_date']  , date('Y-m-d'));
$form_facility  = $_POST['form_facility'];

if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=pending_orders.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  echo '"' . xl('Patient') . '",';
  echo '"' . xl('ID') . '",';
  echo '"' . xl('Ordered') . '",';
  echo '"' . xl('From') . '",';
  echo '"' . xl('Procedure') . '",';
  echo '"' . xl('Provider') . '",';
  echo '"' . xl('Priority') . '",';
  echo '"' . xl('Status') . '"' . "\n";
}
else { // not export
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Pending Orders','e') ?></title>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>
<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<center>

<h2><?php xl('Pending Orders','e')?></h2>

<form method='post' action='pending_orders.php'>

<table border='0' cellpadding='3'>

 <tr>
  <td>
   <?php dropdown_facility(strip_escape_custom($form_facility), 'form_facility', false); ?>
   &nbsp;<?xl('From:','e')?>
   <input type='text' name='form_from_date' id="form_from_date" size='10'
          value='<?= htmlspecialchars(oeFormatShortDate($form_from_date));  ?>'>
   &nbsp;To:
   <input type='text' name='form_to_date' id="form_to_date" size='10'
          value='<?= htmlspecialchars(oeFormatShortDate($form_to_date)); ?>'/>
   &nbsp;
   <input type='submit' class="cp-misc" name='form_refresh' value="<?php xl('Refresh','e') ?>">
   &nbsp;
   <input type='submit' class="cp-output" name='form_csvexport' value="<?php xl('Export to CSV','e') ?>">
   &nbsp;
   <input type='button' class="cp-output" value='<?php echo xla('Print'); ?>' id='printbutton' />
  </td>
 </tr>

 <tr>
  <td height="1">
  </td>
 </tr>

</table>

<table border='0' cellpadding='1' cellspacing='2' width='98%'>
 <tr bgcolor="#dddddd">
  <td class="dehead"><?php xl('Patient','e'  ) ?></td>
  <td class="dehead"><?php xl('ID','e'       ) ?></td>
  <td class="dehead"><?php xl('Ordered','e'  ) ?></td>
  <td class="dehead"><?php xl('From','e'     ) ?></td>
  <td class="dehead"><?php xl('Provider','e' ) ?></td>
  <td class="dehead"><?php xl('Priority','e' ) ?></td>
  <td class="dehead"><?php xl('Status','e'   ) ?></td>
 </tr>
<?php
} // end not export

// If generating a report.
//
if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  $from_date = $form_from_date;
  $to_date   = $form_to_date;

  $query = "SELECT po.patient_id, po.date_ordered, " .
    "pd.pid, " .
    "CONCAT(pd.lname, ', ', pd.fname, ' ', pd.mname) AS patient_name, " .
    "u1.lname AS provider_lname, u1.fname AS provider_fname, u1.mname AS provider_mname, " .
    "pp.name AS organization, " .
    "lop.title AS priority_name, " .
    "los.title AS status_name, " .
    "pr.procedure_report_id, pr.date_report, pr.report_status " .
    "FROM procedure_order AS po " .
    "JOIN form_encounter AS fe ON fe.pid = po.patient_id AND fe.encounter = po.encounter_id " .
    "JOIN patient_data AS pd ON pd.pid = po.patient_id " .
    "LEFT JOIN users AS u1 ON u1.id = po.provider_id " .
    "LEFT JOIN procedure_providers AS pp ON pp.ppid = po.lab_id " .
    "LEFT JOIN list_options AS lop ON lop.list_id = 'ord_priority' AND lop.option_id = po.order_priority " .
    "LEFT JOIN list_options AS los ON los.list_id = 'ord_status' AND los.option_id = po.order_status " .
    "LEFT JOIN procedure_report AS pr ON pr.procedure_order_id = po.procedure_order_id " .
    "WHERE " .
    "po.date_ordered >= '$from_date' AND po.date_ordered <= '$to_date' AND " .
    "( pr.report_status IS NULL OR pr.report_status = 'prelim' )";

  // TBD: What if preliminary and final reports for the same order?

  if ($form_facility) {
    $query .= " AND fe.facility_id = '$form_facility'";
  }
  $query .= " ORDER BY pd.lname, pd.fname, pd.mname, po.patient_id, " .
    "po.date_ordered, po.procedure_order_id";

  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res)) {
    thisLineItem($row);
  }

} // end report generation

if (! $_POST['form_csvexport']) {
?>

</table>
</form>
</center>
</body>

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
<?php
} // End not csv export
?>
