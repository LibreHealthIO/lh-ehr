<?php
/**
 * This is a report to create a patient ledger of charges with payments
 * applied.
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2015 Rich Genandt <rgenandt@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreHealth EHR
 * @author  WMT
 * @author  Terry Hill <teryhill@librehealth.io>
 * @link    http://librehealth.io
 */

require_once "reports_controllers/PatientLedgerController.php";

$filename = "svc_financial_report_".attr($from_date)."--".attr($to_date);

if ($_REQUEST['form_csvexport']) {
  csvexport($filename); // CSV headers. (TRK)
} else {
?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript">
var pt_name;
var pt_id;
function checkSubmit() {
    var pat = document.forms[0].elements['form_patient'].value;
    if(!pat || pat == 0) {
        alert('<?php echo xls('A Patient Must Be Selected to Generate This Report') ?>');
        return false;
    }
    document.forms[0].elements['form_refresh'].value = true;
    document.forms[0].elements['form_csvexport'].value = '';
    document.forms[0].submit();
}
function setpatient(pid, lname, fname, dob) {
  document.forms[0].elements['form_patient'].value = lname + ', ' + fname;
  document.forms[0].elements['form_pid'].value = pid;
  document.forms[0].elements['form_dob'].value = dob;
}
function sel_patient() {
    dlgopen('<?php echo $GLOBALS["web_root"]; ?>/modules/calendar/find_patient_popup.php?pflag=0', '_blank', 500, 400);
}
</script>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<?php html_header_show();?>
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
  #report_header {
    visibility: visible;
    display: inline;
  }
  #title {
    visibility: hidden;
    display: none;
  }
}
/* specifically exclude some from the screen */
@media screen {
  #report_parameters_daterange {
    visibility: hidden;
    display: none;
  }
  #report_header {
    visibility: hidden;
    display: none;
  }
  #title {
    visibility: visible;
    display: inline;
  }
}
</style>

<title><?php echo xlt('Patient Ledger by Date') ?></title>

<script language="JavaScript">
 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });
</script>

</head>
<body class="body_top">
<?php if($type_form == '0') { ?>
<span class='title' id='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Patient Ledger by Date'); ?></span>
<?php }else{ ?>
<span class='title' id='title'><?php echo xlt('Patient Ledger'); ?></span>
<?php } ?>
<form method='post' action='pat_ledger.php?form=<?php echo attr($type_form);?>&patient_id=<?php echo attr($form_pid);?>' id='theform'>
<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<table>
 <tr>
  <?php if($type_form == '1') { ?>
    <td width='35%'>
  <?php }else{ ?>
  <td width='70%'>
  <?php } ?>
    <div style='float:left'>
    <table class='text'>
        <tr>
        <?php if($type_form == '0') { ?>
            <td class='label'>
                <?php echo xlt('Facility'); ?>:
            </td>
            <td>
            <?php dropdown_facility($form_facility, 'form_facility', true); ?>
            </td>
      <td><?php echo xlt('Provider'); ?>:</td>
      <td>
        <?php // Build a dropdown list of providers. (TRK)
          dropDownProviders();
        ?>
      </td>
        </tr><tr>
<?php }
      // Show From and To dates fields. (TRK)
      showFromAndToDates();
      if($type_form == '0') { ?>
      <td><span class='label'><?php echo xlt('Patient'); ?>:&nbsp;&nbsp;</span></td>
      <td>
        <input type='text' size='20' name='form_patient' style='width:100%;cursor:pointer;cursor:hand' id='form_patient' value='<?php echo attr($form_patient) ? attr($form_patient) : xla('Click To Select'); ?>' onclick='sel_patient()' title='<?php echo xla('Click to select patient'); ?>' />
        <?php }else{ ?>
        <input type='hidden' name='form_patient' value='<?php echo attr($form_patient); ?>' />
        <?php } ?>
        <input type='hidden' name='form_pid' value='<?php echo attr($form_pid); ?>' />
        <input type='hidden' name='form_dob' value='<?php echo attr($form_dob); ?>' />

      </td>
        </tr>
    </table>
    </div>
  </td>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid; width:70%; height:100%' >
        <tr>
            <td>
                <div style='margin-left:15px'>
                    <a href='#' class='css_button cp-submit' onclick="checkSubmit();" >
                    <span><?php echo xlt('Submit'); ?></span></a>

            <?php if ($_REQUEST['form_refresh'] || $_REQUEST['form_csvexport']) { ?>
                    <div id="controls">
                    <a href='#' class='css_button cp-output' id='printbutton'>
                         <span><?php echo xlt('Print Ledger'); ?></span></a>
                    <?php if($type_form == '1') { ?>
                    <a href="../patient_file/summary/demographics.php" class="css_button cp-misc" onclick="top.restoreSession()">
                         <span><?php echo xlt('Back To Patient');?></span></a>
                    <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
  </td>
 </tr>
</table>
</div> <!-- end of parameters -->

<?php
} // end not export
  $from_date = $from_date . ' 00:00:00';
  $to_date = $to_date . ' 23:59:59';
if ($_REQUEST['form_refresh'] || $_REQUEST['form_csvexport']) {
  prepareAndShowResults(); // Prepare and show results. (TRK)
}
if (! $_REQUEST['form_csvexport']) {
  if ( $_REQUEST['form_refresh'] && $orow <= 0) {
    echo "<span style='color:red;'>";
    echo xlt('No data to display. Select patient and try search again.');
    echo "</span>";
    echo '<script>document.getElementById("report_table").style.display="none";</script>';
    echo '<script>document.getElementById("controls").style.display="none";</script>';
  }

if (!$_REQUEST['form_refresh'] && !$_REQUEST['form_csvexport']) { ?>
<div class='text'>
    <?php echo xlt('Please input search criteria above, and click Submit to view results.' ); ?>
</div>
<?php } ?>
</form>
</body>

<!-- stuff for the popup calendar -->

<script language="Javascript">
 top.restoreSession();
</script>
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
} // End not csv export
?>
