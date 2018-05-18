<?php
/*
 * Insurance Allocation report
 *
 * This module shows relative insurance usage by unique patients
 * that are seen within a given time period.  Each patient that had
 * a visit is counted only once, regardless of how many visits.
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io> 
 * 
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
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link http://librehealth.io 
 */

require_once "reports_controllers/InsuranceAllocationController.php";

if ($_POST['form_csvexport']) {
  csvexport('insurance_allocation'); // CSV headers. (TRK)
  // CSV headers:
  if (true) {
    echo '"Insurance",';
    echo '"Charges",';
    echo '"Adjustments",';
    echo '"Payments",';
    echo '"Visits",';
    echo '"Patients",';
    echo '"Pt Pct"' . "\n";
  }
}
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Patient Insurance Distribution','e'); ?></title>
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/calendar.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

</script>

<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
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

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Patient Insurance Distribution','e'); ?></span>

<?php reportParametersDaterange(); #TRK ?>

<form name='theform' method='post' action='insurance_allocation_report.php' id='theform'>
<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
 <tr>
  <td width='410px'>
    <div style='float:left'>
      <table class='text'>
          <tr>
            <?php // Show From and To dates fields. (TRK)
              showFromAndToDates(); ?>
          </tr>
      </table>
    </div>
  </td>
  <?php // Show submit, print, export buttons. (TRK) 
    showSubmitPrintButtons('form_csvexport'); ?>
 </tr>
</table>

</div> <!-- end parameters -->
</form>

<div id="report_results">
<?php
} // end not export
if ($_POST['form_refresh'] || $_POST['form_csvexport']) { ?>
<table>
  <thead>
    <th align='left'> <?php xl('Primary Insurance','e'); ?> </th>
    <th align='right'> <?php xl('Charges','e'); ?> </th>
    <th align='right'> <?php xl('Adjustments','e'); ?> </th>
    <th align='right'> <?php xl('Payments','e'); ?> </th>
    <th align='right'> <?php xl('Visits','e'); ?> </th>
    <th align='right'> <?php xl('Patients','e'); ?> </th>
    <th align='right'> <?php xl('Pt %','e'); ?> </th>
  </thead>
  <tbody>
    <?php // Prepare and show results. (TRK)
      prepareAndShowResults();
  } // end if

  if (! $_POST['form_csvexport']) {
  ?>

  </tbody>
</table>
</div> <!-- end of results -->

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
