<?php
// Copyright (C) 2008-2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This reports checkins and checkouts for a specified patient's chart.
require_once "reports_controllers/ChartLocationActivityController.php";
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo xlt('Chart Location Activity'); ?></title>

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

<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script language="JavaScript">
 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });
</script>

</head>

<body class="body_top">

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Chart Location Activity'); ?></span>

<?php // check the patient, and if row exists. (TRK)
  checkPatientId(); ?>

<div id="report_parameters_daterange">
</div>

<form name='theform' id='theform' method='post' action='chart_location_activity.php'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<table>
 <tr>
  <td width='200px'>
	<div style='float:left'>

	<table class='text'>
		<tr>
			<td class='label'>
			   <?php echo xlt('Patient ID'); ?>:
			</td>
			<td>
			   <input type='text' name='form_patient_id' size='10' maxlength='31' value='<?php echo attr($form_patient_id) ?>'
				title='<?php echo xla('Patient ID'); ?>' />
			</td>
		</tr>
	</table>

	</div>

  </td>
  <?php // Show buttons to print and export. (TRK)
    showSubmitPrintButtons(); ?>
 </tr>
</table>

</div> <!-- end of parameters -->

<?php // Show results for search. (TRK)
  showResults(); ?>

</form>
</body>
</html>
