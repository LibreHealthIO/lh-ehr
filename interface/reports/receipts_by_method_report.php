<?php
/*
 * Receipts by Method report
 *
 * This is a report of receipts by payer or payment method.
 *
 * The payer option means an insurance company name or "Patient".
 *
 * The payment method option is most useful for sites using
 * pos_checkout.php (e.g. weight loss clinics) because this plugs
 * a payment method like Cash, Check, VISA, etc. into the "source".
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

require_once "reports_controllers/ReceiptsByMethodController.php";

?>
<html>
<head>

<?php if (function_exists('html_header_show')) html_header_show(); ?>
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
    #report_results {
       margin-top: 30px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
</style>

<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

// This is for callback by the find-code popup.
// Erases the current entry
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 var s = f.form_proc_codefull.value;
 if (code) {
  s = codetype + ':' + code;
 } else {
  s = '';
 }
 f.form_proc_codefull.value = s;
}

// This invokes the find-code popup.
function sel_procedure() {
 dlgopen('../patient_file/encounter/find_code_popup.php?codetype=<?php echo attr(collect_codetypes("procedure","csv")) ?>', '_blank', 500, 400);
}

</script>

<title><?xl('Receipts Summary','e')?></title>
</head>

<body class="body_top">

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Receipts Summary','e'); ?></span>

<form method='post' action='receipts_by_method_report.php' id='theform'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

<table>
 <tr>
  <td width='630px'>
    <div style='float:left'>

    <table class='text'>
        <tr>
            <td class='label'>
               <?php xl('Report by','e'); ?>
            </td>
            <td>
                <?php
                echo "   <select name='form_report_by'>\n";
                foreach (array(1 => 'Payer', 2 => 'Payment Method', 3 => 'Check Number') as $key => $value) {
                  echo "    <option value='$key'";
                  if ($key == $form_report_by) echo ' selected';
                  echo ">" . xl($value) . "</option>\n";
                }
                echo "   </select>&nbsp;\n"; ?>
            </td>

            <td>
            <?php dropdown_facility(strip_escape_custom($form_facility), 'form_facility', false); ?>
            </td>

            <td>
               <?php if (!$GLOBALS['simplified_demographics']) echo '&nbsp;' . xl('Procedure/Service') . ':'; ?>
            </td>
            <td>
               <input type='text' name='form_proc_codefull' size='12' value='<?php echo $form_proc_codefull; ?>' onclick='sel_procedure()'
                title='<?php xl('Click to select optional procedure code','e'); ?>'
                <?php if ($GLOBALS['simplified_demographics']) echo "style='display:none'"; ?> />
                                <br>
               &nbsp;<input type='checkbox' name='form_details' value='1'<?php if ($_POST['form_details']) echo " checked"; ?> /><?xl('Details','e')?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
               <select name='form_use_edate'>
                <option value='0'><?php xl('Payment Date','e'); ?></option>
                <option value='1'<?php if ($form_use_edate) echo ' selected' ?>><?php xl('Invoice Date','e'); ?></option>
               </select>
            </td>
            <?php // Show From and To dates fields. (TRK)
              showFromAndToDates(); ?>
        </tr>
    </table>

    </div>

  </td>
  <?php   // Show print, submit and export buttons. (TRk)
    showSubmitPrintButtons(); ?>
 </tr>
</table>

</div> <!-- end of parameters -->

<?php
 if ($_POST['form_refresh']) {
?>
<div id="report_results">

<table>

 <thead>
  <th>
   <?xl('Method','e')?>
  </th>
  <th>
   <?xl('Date','e')?>
  </th>
  <th>
   <?xl('Invoice','e')?>
  </th>
<?php if ($showing_ppd) { ?>
  <th>
   <?xl('Patient','e')?>
  </th>
  <th>
   <?xl('Policy','e')?>
  </th>
  <th>
   <?xl('DOS','e')?>
  </th>
<?php } ?>
  <th>
   <?xl('Procedure','e')?>
  </th>
  <th align="right">
   <?xl('Adjustments','e')?>
  </th>
  <th align="right">
   <?xl('Payments','e')?>
  </th>
 </thead>
<?php
if ($_POST['form_refresh']) {
  prepareAndShowResults(); // Prepare and show results. (TRK)
} // end form refresh
?>

</table>
</div>
<?php } else { ?>
<div class='text'>
    <?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php } ?>

</form>
</body>

<!-- stuff for the popup calendar -->
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
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
        $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });
</script>

</html>
