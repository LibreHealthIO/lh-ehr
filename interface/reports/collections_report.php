<?php
/*
 * Collections report
 *
 * (TLH) Added payor,provider,fixed cvs download to included selected fields
 * (TLH) Added ability to download selected invoices only or all for patient
 *
 * Copyright (C) 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
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
 * @package LibreHealth EHR 
 * @author Rod Roark <rod@sunsetsystems.com> 
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link http://librehealth.io
 */
 
require_once "reports_controllers/CollectionsController.php";

// In the case of CSV export only, a download will be forced.
if ($_POST['form_csvexport']) {
  csvexport('collections_report'); // CSV headers. (TRK)
}
else {
?>
<html>
<head>
<?php if (function_exists('html_header_show')) html_header_show(); ?>
<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<title><?php echo xlt('Collections Report')?></title>
<style type="text/css">

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

<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

function checkAll(checked) {
 var f = document.forms[0];
 for (var i = 0; i < f.elements.length; ++i) {
  var ename = f.elements[i].name;
  if (ename.indexOf('form_cb[') == 0)
   f.elements[i].checked = checked;
 }
}

</script>

</head>

<body class="body_top">

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Collections'); ?></span>

<form method='post' action='collections_report.php' enctype='multipart/form-data' id='theform'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_export' id='form_export' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
  <tr>
    <td width='650px'>
      <div style='float:left'>
        <table class='text'>
          <tr>
            <td class='label'>
              <table>
                <tr>
                  <td><?php echo xlt('Displayed Columns') ?>:</td>
                </tr>
                <tr>
                  <td>
                    <label><input type='checkbox' name='form_cb_ssn'<?php if ($form_cb_ssn) echo 'checked'; ?>>
                      <?php echo xlt('SSN') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_dob'<?php if ($form_cb_dob) echo 'checked'; ?>>
                    <?php echo xlt('DOB') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_pid'<?php if ($form_cb_pid) echo 'checked'; ?>>
                           <?php echo xlt('ID') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_policy'<?php if ($form_cb_policy) echo 'checked'; ?>>
                    <?php echo xlt('Policy') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_phone'<?php if ($form_cb_phone) echo 'checked'; ?>>
                    <?php echo xlt('Phone') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_city'<?php if ($form_cb_city) echo 'checked'; ?>>
                    <?php echo xlt('City') ?>&nbsp;</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label><input type='checkbox' name='form_cb_ins1'<?php if ($form_cb_ins1) echo 'checked'; ?>>
                      <?php echo xlt('Primary Ins') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_referrer'<?php if ($form_cb_referrer) echo 'checked'; ?>>
                    <?php echo xlt('Referrer') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_adate'<?php if ($form_cb_adate) echo 'checked'; ?>>
                    <?php echo xlt('Act Date') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_idays'<?php if ($form_cb_idays) echo 'checked'; ?>>
                    <?php echo xlt('Inactive Days') ?>&nbsp;</label>
                  </td>
                  <td>
                    <label><input type='checkbox' name='form_cb_err'<?php if ($form_cb_err) echo 'checked'; ?>>
                    <?php echo xlt('Errors') ?></label>
                  </td>
                </tr>
              </table>
            </td>
          </tr>        
          <td>
            <table>
              <tr>
                <?php // Show from and to dates. (TRK)  
                  showFromAndToDates(); ?>
                <td>
                  <select name='form_category'>
                  <?php
                    foreach (array('Open' => xl('Open'),'Due Pt' => xl('Due Pt'),'Due Ins' => xl('Due Ins'),'Ins Summary' => xl('Ins Summary'),'Credits' => xl('Credits'),'All' => xl('All')) as $key => $value) {
                       echo "    <option value='" . attr($key) . "'";
                      if ($_POST['form_category'] == $key) echo " selected";
                      echo ">" . text($value) . "</option>\n";
                    }
                  ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td class='label'>
                  <?php echo xlt('Facility'); ?>:
                </td>
                <td>
                  <?php dropdown_facility($form_facility, 'form_facility', false); ?>
                </td>                        
                <td class='label'>
                  <?php echo xlt('Payor'); ?>:
                </td>
                <td>
                  <?php  # added dropdown for payors (TLH)
                    dropDownPayors();
                  ?>            
                </td>
              </tr>
              <tr>
                <td class='label'>
                  <?php echo xlt('Age By') ?>:
                </td>
                <td>
                  <select name='form_ageby'>
                  <?php
                    foreach (array( 'Service Date'=>xl('Service Date'), 'Last Activity Date'=>xl('Last Activity Date')) as $key => $value) {
                        echo "    <option value='" . attr($key) . "'";
                        if ($_POST['form_ageby'] == $value) echo " selected";
                        echo ">" . text($value) . "</option>\n";
                    }
                  ?>
                  </select>
                </td>                        
                <td class='label'>
                  <?php echo xlt('Provider') ?>:
                </td>
                <td>
                  <?php # Build a drop-down list of providers.
                    # Added (TLH)
                    dropDownProviders(); ?>
                </td>
              </tr>
              <tr>                   
                <td class='label'>
                  <?php echo xlt('Aging Columns') ?>:
                </td>
                <td>
                  <input type='text' name='form_age_cols' size='2' value='<?php echo attr($form_age_cols); ?>' />
                </td>
                <td class='label'>
                  <?php echo xlt('Days/Col') ?>:
                </td>
                <td>
                  <input type='text' name='form_age_inc' size='3' value='<?php echo attr($form_age_inc); ?>' />
                </td>
              </tr>
            </table>
          </td>
        </table>
      </div>
    </td>
    <?php // Show print and export buttons. (TRK)
      showSubmitPrintButtons('form_csvexport'); ?>
  </tr>
</table>
</div>

<?php

} // end not form_csvexport

if ($_POST['form_refresh'] || $_POST['form_export'] || $_POST['form_csvexport']) {
  prepareAndShowResults(); // Prepare and show results. (TRK)
}
 // end if form_refresh


if (!$_POST['form_csvexport']) {
  if (!$_POST['form_export']) {
?>

<div style='float;margin-top:5px'>

<a href='javascript:;' class='css_button'  onclick='checkAll(true)'><span><?php echo xlt('Select All'); ?></span></a>
<a href='javascript:;' class='css_button'  onclick='checkAll(false)'><span><?php echo xlt('Clear All'); ?></span></a>
<a href='javascript:;' class='css_button' onclick='$("#form_csvexport").attr("value","true"); $("#theform").submit();'>
    <span><?php echo xlt('Export Selected as CSV'); ?></span>
</a>
<a href='javascript:;' class='css_button' onclick='$("#form_export").attr("value","true"); $("#theform").submit();'>
    <span><?php echo xlt('Export Selected to Collections'); ?></span>
</a>
</div>

<div style='float:left'>
<label><input type='checkbox' name='form_individual' value='1' /> <?php echo xlt('Export Individual Invoices') ?>&nbsp;&nbsp;</label>
</div>

<div style='float:left'>
<label><input type='checkbox' name='form_without' value='1' /> <?php echo xlt('Without Update') ?></label>
</div>

<?php
  } // end not export
?>
</form>
</center>
<script language="JavaScript">
<?php
  if ($alertmsg) {
    echo "alert('" . addslashes($alertmsg) . "');\n";
  }
?>
</script>
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
} // end not form_csvexport
?>
