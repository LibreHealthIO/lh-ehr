<?php
/*
 * Patient Billing Encounter by Carrier report. (/interface/reports/encounters_report_carrier.php
 *
 * This report shows past encounters with filtering and sorting,
 * Added filtering to show encounters by primary insurance, encounter billing note, encounter total and Insurance name.
 *
 * Copyright (C) 2017 Terry Hill <teryhill@librehealth.io>
 *
 * LICENSE: This Source Code is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 *
 */

require_once "reports_controllers/EncountersCarrierController.php";

?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('Patient Billing Encounter by Carrier Report'); ?></title>
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

<?php
   include_css_library("tablesorter-master/dist/css/theme.blue.min.css");
   include_css_library("jquery-datetimepicker/jquery.datetimepicker.css");
   include_js_library("jquery-min-3-1-1/index.js");
   include_js_library("jquery-datetimepicker/jquery.datetimepicker.full.min.js");
   include_js_library("tablesorter-master/dist/js/jquery.tablesorter.min.js");
   include_js_library("tablesorter-master/dist/js/jquery.tablesorter.widgets.min.js");
?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/report_helper.js"></script>
<script LANGUAGE="JavaScript">
 $(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

 $(document).ready(function()
    {
        $("#mymaintable").tablesorter();
    }
);

 function refreshme() {
  document.forms[0].submit();
 }

</script>

</head>
<body class="body_top">
<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Patient Billing Encounter by Carrier'); ?></span>

<div id="report_parameters_daterange">
    <?php date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_from_date'])))
    . " &nbsp; to &nbsp; ". date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_to_date']))); ?>
</div>

<form method='post' name='theform' id='theform' action='encounters_report_carrier.php'>

<div id="report_parameters">
<table>
 <tr>
  <td width='550px'>
    <div style='float:left'>

    <table class='text'>
        <tr>
            <td class='label'>
                <?php echo xlt('Facility'); ?>:
            </td>
            <td>
            <?php dropdown_facility($form_facility, 'form_facility', true); ?>
            </td>
            <td class='label'>
               <?php echo xlt('Provider'); ?>:
            </td>
            <td>
              <?php // Build a drop-down list of providers.
                dropDownProviders();
              ?>
            </td>
            </tr>
            <tr>
            <td class='label'>
            <?php echo xlt('Primary Insurance'); ?>:
            </td>
            <td>
            <?php  # added dropdown for payors (TLH)
                dropDownPayors();
            ?>
            </td>
            <td class='label'>
            <?php echo xlt('Status'); ?>:
            </td>
            <td>
              <select name='form_category'>
              <?php
                foreach (array(xl('All'), xl('Open'), xl('Closed'), xl('Empty'), xl('Mixed')) as $value) {
                  echo "    <option value='$value'";
                  if ($_POST['form_category'] == $value) echo " selected";
                  $chckstatus = $_POST['form_category'];
                  echo ">$value</option>\n";
               }
             ?>
            </select>
            </td>
        </tr>
        <tr>
            <?php showFromAndToDates(); ?>
        </tr>
    </table>

    </div>

  </td>
  <?php showSubmitPrintButtons(); ?>
 </tr>
</table>

</div> <!-- end report_parameters -->

<?php
 if ($_POST['form_refresh']) {
?>
<div id="report_results">
<table id='mymaintable' class="tablesorter">
 <thead>
<?php if ($form_details) { ?>
  <th><?php echo xlt('Provider'); ?></th>
  <th><?php echo xlt('Date'); ?></th>
  <th><?php echo xlt('Patient'); ?></th>
  <th><?php echo xlt('ID'); ?></th>
  <th><?php echo xlt('Status'); ?></th>
  <th><?php echo xlt('ENC Reason'); ?></th>
  <th><?php echo xlt('ENC Billing Note'); ?></th>
  <th><?php echo xlt('Primary Ins')?></th>
  <th><?php echo xlt('Encounter'); ?></th>
  <th><?php echo xlt('Coding'); ?></th>
  <th><?php echo xlt('Total'); ?></th>
<?php } else { ?>
  <th><?php echo xlt('Provider'); ?></td>
  <th><?php echo xlt('Encounters'); ?></td>
<?php } ?>
 </thead>
 <tbody>
<?php // Show the results. (TRK)
  showResults(); ?>
</tbody>
</table>
</div>  <!-- end encresults -->
<?php } else { ?>
<div class='text'>
    <?php echo xlt('Please input search criteria above, and click Submit to view results.' ); ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo xlt('Column headers can be clicked to change sort order') ?>
</div>
<?php } ?>

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

</form>
</body>

<script language='JavaScript'>

<?php if ($alertmsg) { echo " alert('$alertmsg');\n"; } ?>

</script>
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
