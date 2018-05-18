<?php
/*
 * Patient List By Referrer report
 * This report lists patients that were seen within a given date
 * range, or all patients if no date range is entered, by the
 * referring provider selected.
 *
 * Copyright (C) 2017 Terry Hill <teryhill@librehealth.io> 
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package LibreEHR
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link http://librehealth.io
 */

 require_once "reports_controllers/PatientListByReferrerController.php";

// In the case of CSV export only, a download will be forced.
if ($_POST['form_csvexport']) {
  csvexport('patient_list_by_referrer'); // CSV headers. (TRK)
}
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('Patient List By Referrer'); ?></title>
<!--<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>-->
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js"></script>

<script language="JavaScript">

$(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
  top.printLogSetup(document.getElementById('printbutton'));
});

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
        margin-bottom: 10px;
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
    #report_results {
        width: 100%;
    }
}

</style>

</head>

<body class="body_top">

<!-- Required for the popup date selectors 
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>-->

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Patient List By Referrer'); ?></span>

<?php reportParametersDaterange(); #TRK ?>

<form name='theform' id='theform' method='post' action='patient_list_by_referrer.php'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
 <tr>
  <td width='60%'>
    <div style='float:left'>

    <table class='text'>
      <tr>
        <td>
            <span class=text><?php echo xlt('Referring Provider'); ?>: </span>
            <td>
              <?php  # Build a drop-down list of providers. # Added (TLH)
                 genProviderSelect('form_provider', '-- '.xl("All providers").' --',$_POST['form_provider'], false, true);
              ?>
            </td>&nbsp;&nbsp;
        </td>
        <?php // Show From and To dates fields. (TRK) 
          showFromAndToDates(); ?>
      </tr>
    </table>

    </div>

  </td>
  <?php  // Show print, submit and export buttons. (TRk)
    showSubmitPrintButtons('form_csvexport'); ?>
 </tr>
</table>
</div> <!-- end of parameters -->

<?php
} // end not form_csvexport

if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  if ($_POST['form_csvexport']) {
    // CSV headers:
    echo '"' . xl('Last') . '",';
    echo '"' . xl('First') . '",';
    echo '"' . xl('Middle') . '",';
    echo '"' . xl('DOB') . '",';
    echo '"' . xl('SS') . '",';
    echo '"' . xl('Street') . '",';
    echo '"' . xl('City') . '",';
    echo '"' . xl('State') . '",';
    echo '"' . xl('Zip') . '",';
    echo '"' . xl('Home Phone') . '",';
    echo '"' . xl('Work Phone') . '",';
    echo '"' . xl('Ins 1') . '",';
    echo '"' . xl('Addr 1') . '",';
    echo '"' . xl('City 1') . '",';
    echo '"' . xl('ST 1') . '",';
    echo '"' . xl('Zip 1') . '",';
    echo '"' . xl('Ins 2') . '",';
    echo '"' . xl('Addr 2') . '",';
    echo '"' . xl('City 2') . '",';
    echo '"' . xl('ST 2') . '",';
    echo '"' . xl('Zip 2') . '"' . "\n";
  }
  else {
?>

<div id="report_results">
<table id='mymaintable'>
 <thead>
  <th> <?php echo xlt('Patient'); ?> </th>
  <th> <?php echo xlt('DOB'); ?> </th>
  <th> <?php echo xlt('SS'); ?> </th>
  <th> <?php echo xlt('Street'); ?> </th>
  <th> <?php echo xlt('City'); ?> </th>
  <th> <?php echo xlt('State'); ?> </th>
  <th> <?php echo xlt('Zip'); ?> </th>
  <th> <?php echo xlt('Home Phone'); ?> </th>
  <th> <?php echo xlt('Work Phone'); ?> </th>
  <th> <?php echo xlt('Insurance 1'); ?> </th>
  <th> <?php echo xlt('Insurance 2'); ?> </th>
 </thead>
 <tbody>
<?php
  } // end not export
  $totalpts = prepareAndShowResults(); // Prepare and show results. (TRK)
  if (!$_POST['form_csvexport']) {
?>
 <tr class="report_totals">
  <td colspan='11'>
   <?php echo xlt('Total Number of Patients'); ?>
   :
   <?php echo $totalpts ?>
  </td>
 </tr>

</tbody>
</table>
</div> <!-- end of results -->
<?php
  } // end not export
} // end if refresh or export

if (!$_POST['form_refresh'] && !$_POST['form_csvexport']) {
?>
<div class='text'>
     <?php echo xlt('Please input search criteria above, and click Submit to view results.' ); ?>
</div>
<?php
}

if (!$_POST['form_csvexport']) {
?>

</form>
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
        $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });
</script>
</html>
<?php
} // end not export
?>
