<?php
/*
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io> 
 * Copyright (C) 2005-2010 Rod Roark <rod@sunsetsystems.com>
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
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR 
 * @author Rod Roark <rod@sunsetsystems.com> 
 * 
 * @link http://librehealth.io 
 *
 * This report cross-references appointments with encounters.
 * For a given date, show a line for each appointment with the
 * matching encounter, and also for each encounter that has no
 * matching appointment.  This helps to catch these errors:
 *
 * Appointments with no encounter
 * Encounters with no appointment
 * Codes not justified
 * Codes not authorized
 * Procedure codes without a fee
 * Fees assigned to diagnoses (instead of procedures)
 * Encounters not billed
 *
 * For decent performance the following indexes are highly recommended:
 *   libreehr_postcalendar_events.pc_eventDate
 *   forms.encounter
 *   billing.pid_encounter
 */

require_once "reports_controllers/ApptEncounterController.php";

?>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
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
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<title><?php  xl('Appointments and Encounters','e'); ?></title>

<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

</script>

</head>

<body class="body_top">

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Appointments and Encounters','e'); ?></span>

<?php reportParametersDaterange(); #TRK ?>

<form method='post' id='theform' action='appt_encounter_report.php'>

<div id="report_parameters">

<table>
 <tr>
  <td width='630px'>
    <div style='float:left'>

    <table class='text'>
        <tr>
            <td class='label'>
                <?php xl('Facility','e'); ?>:
            </td>
            <td>
              <?php // Build a drop-down list of facilities. (TRK)
                dropDownFacilities();
              ?>
            </td>
            <?php // Show From and To dates fields. (TRK)
              showFromAndToDates(); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
               <input type='checkbox' name='form_details'
                value='1'<?php if ($_POST['form_details']) echo " checked"; ?>><?php xl('Details','e') ?>
            </td>
        </tr>
    </table>

    </div>

  </td>
  <?php // Show submit, print and export to CSV buttons. (TRK)
    showSubmitPrintButtons(); ?>
 </tr>
</table>

</div> <!-- end apptenc_report_parameters -->

<?php
  if ($_POST['form_refresh'] ) { // start form_refresh
?>
    <div id="report_results">
      <table>
        <thead>
          <th> &nbsp;<?php  xl('Practitioner','e'); ?> </th>
          <th> &nbsp;<?php  xl('Date/Appt','e'); ?> </th>
          <th> &nbsp;<?php  xl('Patient','e'); ?> </th>
          <th> &nbsp;<?php  xl('ID','e'); ?> </th>
          <th align='right'> <?php  xl('Chart','e'); ?>&nbsp; </th>
          <th align='right'> <?php  xl('Encounter','e'); ?>&nbsp; </th>
          <th align='right'> <?php  xl('Charges','e'); ?>&nbsp; </th>
          <th align='right'> <?php  xl('Copays','e'); ?>&nbsp; </th>
          <th> <?php  xl('Billed','e'); ?> </th>
          <th> &nbsp;<?php  xl('Error','e'); ?> </th>
        </thead>
        <tbody>
          <?php
            if ($res) { // start of res
              displayResult($res);
            } // end of res
          ?>
        </tbody>
      </table>
    </div> <!-- end the apptenc_report_results -->
<?php 
  } // end of form_refresh
else { ?> 
<div class='text'>
    <?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php } ?>

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

</form>
<script>
<?php if ($alertmsg) { echo " alert('$alertmsg');\n"; } ?>
</script>
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
