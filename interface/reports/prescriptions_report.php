<?php
/*
 * Prescriptions report
 *
 * This report lists prescriptions and their dispensations according
 * to various input selection criteria.
 *
 * Fix drug name search to work in a broader sense - tony@mi-squared.com 2010
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
 * @author Tony McCormick <tony@mi-squared.com>
 * @link http://librehealth.io
 */
 // Copyright (C) 2006-2015 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

 // This report lists prescriptions and their dispensations according
 // to various input selection criteria.
 //
 // Fix drug name search to work in a broader sense - tony@mi-squared.com 2010

require_once "reports_controllers/PrescriptionsController.php";

?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Prescriptions and Dispensations','e'); ?></title>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

<script language="JavaScript">


 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

 // The OnClick handler for receipt display.
 function show_receipt(payid) {
  // dlgopen('../patient_file/front_payment.php?receipt=1&payid=' + payid, '_blank', 550, 400);
  return false;
 }

</script>

<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
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

</style>
</head>

<body class="body_top">

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Prescriptions and Dispensations','e'); ?></span>

<?php reportParametersDaterange(); #TRK ?>

<form name='theform' id='theform' method='post' action='prescriptions_report.php'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<table>
 <tr>
  <td width='640px'>
    <div style='float:left'>

    <table class='text'>
        <tr>
            <td class='label'>
                <?php xl('Facility','e'); ?>:
            </td>
            <td>
            <?php dropdown_facility(strip_escape_custom($form_facility), 'form_facility', true); ?>
            </td>
            <?php // Show From and To dates fields. (TRK)
              showFromAndToDates(); ?>
        </tr>
        <tr>
            <td class='label'>
               <?php xl('Patient ID','e'); ?>:
            </td>
            <td>
               <input type='text' name='form_patient_id' size='10' maxlength='20' value='<?php echo $form_patient_id ?>'
                title=<?php xl('Optional numeric patient ID','e','\'','\''); ?> />
            </td>
            <td class='label'>
               <?php xl('Drug','e'); ?>:
            </td>
            <td>
               <input type='text' name='form_drug_name' size='10' maxlength='250' value='<?php echo $form_drug_name ?>'
                title=<?php xl('Optional drug name, use % as a wildcard','e','\'','\''); ?> />
            </td>
            <td class='label'>
               <?php xl('Lot','e'); ?>:
            </td>
            <td>
               <input type='text' name='form_lot_number' size='10' maxlength='20' value='<?php echo $form_lot_number ?>'
                title=<?php xl('Optional lot number, use % as a wildcard','e','\'','\''); ?> />
            </td>
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
  <th> <?php xl('Patient','e'); ?> </th>
  <th> <?php xl('ID','e'); ?> </th>
  <th> <?php xl('RX','e'); ?> </th>
  <th> <?php xl('Drug Name','e'); ?> </th>
  <th> <?php xl('NDC','e'); ?> </th>
  <th> <?php xl('Units','e'); ?> </th>
  <th> <?php xl('Refills','e'); ?> </th>
  <th> <?php xl('Instructed','e'); ?> </th>
  <th> <?php xl('Reactions','e'); ?> </th>
  <th> <?php xl('Dispensed','e'); ?> </th>
  <th> <?php xl('Qty','e'); ?> </th>
  <th> <?php xl('Manufacturer','e'); ?> </th>
  <th> <?php xl('Lot','e'); ?> </th>
 </thead>
 <tbody>
<?php
 if ($_POST['form_refresh']) {
  prepareAndShowResults(); // prepare and show results. (TRK)
 } // end if
?>
</tbody>
</table>
</div> <!-- end of results -->
<?php } else { ?>
<div class='text'>
    <?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php } ?>
</form>
</body>


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
