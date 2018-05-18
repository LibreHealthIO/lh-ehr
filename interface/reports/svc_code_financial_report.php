<?php
/*
 * Financial Summary by Service Code
 *
 * This is a summary of service code charge/pay/adjust and balance,
 * with the ability to pick "important" codes to either highlight or
 * limit to list to. Important codes can be configured in
 * Administration->Service section by assigning code with
 * 'Service Reporting'.
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
 * @author  Visolve
 * @author Rod Roark <rod@sunsetsystems.com>
 * @link    http://librehealth.io
 */

require_once "reports_controllers/SvcCodeFinancialController.php";

  $filename = 'svc_financial_report_' . attr($from_date) . "--" . attr($to_date);

  if ($_POST['form_csvexport']) {
    csvexport($filename); // Export to CSV. (TRK)
    // CSV headers:
    } // end export
  else {
?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
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
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
</style>

<title><?php echo xlt('Financial Summary by Service Code') ?></title>

<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

</script>

</head>
<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">
<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Financial Summary by Service Code'); ?></span>
<form method='post' action='svc_code_financial_report.php' id='theform'>
<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<table>
 <tr>
  <td width='70%'>
    <div style='float:left'>
    <table class='text'>
        <tr>
            <td class='label'>
                <?php echo xlt('Facility'); ?>:
            </td>
            <td>
            <?php dropdown_facility($form_facility, 'form_facility', true); ?>
            </td>
                        <td><?php echo xlt('Provider'); ?>:</td>
                <td>
                  <?php // Build a drop-down list of providers.
                    dropDownProviders();
                  ?>
                </td>
        </tr>
        <tr>
          <?php // Show From and To dates fields. (TRK)
            showFromAndToDates(); ?>
          <td>
            <input type='checkbox' name='form_details'<?php  if ($_POST['form_details']) echo ' checked'; ?>>
            <?php echo xlt('Important Codes'); ?>
          </td>
        </tr>
    </table>
    </div>
  </td>
  <?php   // Show print, submit and export buttons. (TRk)
    showSubmitPrintButtons('form_csvexport'); ?>
 </tr>
</table>
</div> <!-- end of parameters -->

<?php
}
   // end not export

  if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
    prepareAndShowResults(); // Prepare and show results. (TRK)
  } // End form_re and form_csvexport

  if (! $_POST['form_csvexport']) {
       if ( $_POST['form_refresh'] && count($print) != 1)
    {
        echo "<span style='font-size:10pt;'>";
                echo xlt('No matches found. Try search again.');
                echo "</span>";
        echo '<script>document.getElementById("report_results").style.display="none";</script>';
        echo '<script>document.getElementById("controls").style.display="none";</script>';
        }

if (!$_POST['form_refresh'] && !$_POST['form_csvexport']) { ?>
<div class='text'>
    <?php echo xlt('Please input search criteria above, and click Submit to view results.' ); ?>
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
<script language="Javascript">
 top.restoreSession();
</script>
</html>
<?php
  } // End not csv export
?>
