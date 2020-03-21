<?php
/*
 *
 * This report lists destroyed drug lots within a specified date
 * range.
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io> 
 * Copyright (C) 2006-2015 Rod Roark <rod@sunsetsystems.com>
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
  // This report lists destroyed drug lots within a specified date
 // range.

 require_once("../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("../drugs/drugs.inc.php");
 require_once("$srcdir/formatting.inc.php");
 require_once("../../library/report_functions.php");

$DateFormat = DateFormatRead(true);
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 $from_date  = fixDate($_POST['form_from_date'], date($DateFormat));
 $to_date    = fixDate($_POST['form_to_date']  , date($DateFormat));
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Destroyed Drugs','e'); ?></title>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>

<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });
</script>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

<center>

<h2><?php xl('Destroyed Drugs','e'); ?></h2>

<form name='theform' method='post' action='destroyed_drugs_report.php'>

<table border='0' cellpadding='3'>
 <tr>
  <td>
   <?php // Show From and To dates fields. (TRK)
      showFromAndToDates(); ?>
   &nbsp;
   <input type='submit' name='form_refresh' value=<?php xl('Refresh','e'); ?>>
   &nbsp;
   <input type='button' value='<?php echo xla('Print'); ?>' id='printbutton' />
  </td>
 </tr>
 <tr>
  <td height="1">
  </td>
 </tr>
</table>

<table border='0' cellpadding='1' cellspacing='2' width='98%'>
 <tr bgcolor="#dddddd">
  <td class='dehead'>
   <?php xl('Drug Name','e'); ?>
  </td>
  <td class='dehead'>
   <?php xl('NDC','e'); ?>
  </td>
  <td class='dehead'>
   <?php xl('Lot','e'); ?>
  </td>
  <td class='dehead'>
   <?php xl('Qty','e'); ?>
  </td>
  <td class='dehead'>
   <?php xl('Date Destroyed','e'); ?>
  </td>
  <td class='dehead'>
   <?php xl('Method','e'); ?>
  </td>
  <td class='dehead'>
   <?php xl('Witness','e'); ?>
  </td>
  <td class='dehead'>
   <?php xl('Notes','e'); ?>
  </td>
 </tr>
<?php

 if ($_POST['form_refresh']) {
  $where = "i.destroy_date >= '$from_date' AND " .
   "i.destroy_date <= '$to_date'";

  $query = "SELECT i.inventory_id, i.lot_number, i.on_hand, i.drug_id, " .
   "i.destroy_date, i.destroy_method, i.destroy_witness, i.destroy_notes, " .
   "d.name, d.ndc_number " .
   "FROM drug_inventory AS i " .
   "LEFT OUTER JOIN drugs AS d ON d.drug_id = i.drug_id " .
   "WHERE $where " .
   "ORDER BY d.name, i.drug_id, i.destroy_date, i.lot_number";

  // echo "<!-- $query -->\n"; // debugging
  $res = sqlStatement($query);

  $last_drug_id = 0;
  while ($row = sqlFetchArray($res)) {
   $drug_name       = $row['name'];
   $ndc_number      = $row['ndc_number'];
   if ($row['drug_id'] == $last_drug_id) {
    $drug_name  = '&nbsp;';
    $ndc_number = '&nbsp;';
   }
?>
 <tr>
  <td class='detail'>
   <?php echo $drug_name ?>
  </td>
  <td class='detail'>
   <?php echo $ndc_number ?>
  </td>
  <td class='detail'>
   <a href='../drugs/destroy_lot.php?drug=<?php echo $row['drug_id'] ?>&lot=<?php echo $row['inventory_id'] ?>'
    style='color:#0000ff' target='_blank'>
   <?php echo $row['lot_number'] ?>
   </a>
  </td>
  <td class='detail'>
   <?php echo $row['on_hand'] ?>
  </td>
  <td class='detail'>
   <?php echo oeFormatShortDate($row['destroy_date']) ?>
  </td>
  <td class='detail'>
   <?php echo $row['destroy_method'] ?>
  </td>
  <td class='detail'>
   <?php echo $row['destroy_witness'] ?>
  </td>
  <td class='detail'>
   <?php echo $row['destroy_notes'] ?>
  </td>
 </tr>
<?php
   $last_drug_id = $row['drug_id'];
  } // end while
 } // end if
?>

</table>

</form>

</center>

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
     $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });
</script>
</body>
</html>
