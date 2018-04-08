<?php
/*
 * Superbill Report
 * 
 * Copyright (C) 2017 Terry Hill <teryhill@librehealth.io> 
 * No other copyright information in the previous header
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
 * @author  Brady Miller <brady@sparmy.com>
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link    http://librehealth.io
 */

require_once "reports_controllers/CustomReportRangeController.php";

?>
<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<style>

@media print {
    .title {
        visibility: hidden;
    }
    .pagebreak {
        page-break-after: always;
        border: none;
        visibility: hidden;
    }

    #superbill_description {
        visibility: hidden;
    }

    #report_parameters {
        visibility: hidden;
    }
    #superbill_results {
       margin-top: -30px;
    }
}

@media screen {
    .title {
        visibility: visible;
    }
    #superbill_description {
        visibility: visible;
    }
    .pagebreak {
        width: 100%;
        border: 2px dashed black;
    }
    #report_parameters {
        visibility: visible;
    }
}
#superbill_description {
   margin: 10px;
}
#superbill_startingdate {
    margin: 10px;
}
#superbill_endingdate {
    margin: 10px;
}

#superbill_patientdata {
}
#superbill_patientdata h1 {
    font-weight: bold;
    font-size: 1.2em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_insurancedata {
    margin-top: 10px;
}
#superbill_insurancedata h1 {
    font-weight: bold;
    font-size: 1.2em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_insurancedata h2 {
    font-weight: bold;
    font-size: 1.0em;
    margin: 0px;
    padding: 0px;
    width: 100%;
    background-color: #eee;
}
#superbill_billingdata {
    margin-top: 10px;
}
#superbill_billingdata h1 {
    font-weight: bold;
    font-size: 1.2em;
    margin: 0px;
    padding: 5px;
    width: 100%;
    background-color: #eee;
    border: 1px solid black;
}
#superbill_signature {
}
#superbill_logo {
}
</style>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

<script language="Javascript">
 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

// CapMinds :: invokes  find-patient popup.
 function sel_patient() {
  dlgopen('<?php echo $GLOBALS["web_root"]; ?>/modules/calendar/find_patient_popup.php', '_blank', 500, 400);
 }

// CapMinds :: callback by the find-patient popup.
 function setpatient(pid, lname, fname, dob) {
  var f = document.theform;
  f.form_patient.value = lname + ', ' + fname;
  f.form_pid.value = pid;

 }
</script>
</head>

<body class="body_top">

<span class='title'><?php echo xlt('Reports'); ?> - <?php echo xlt('Superbill'); ?></span>

<div id="superbill_description" class='text'>
    <?php echo xlt('Superbills, sometimes referred to as Encounter Forms or Routing Slips, are an essential part of most medical practices.'); ?>
</div>

<div id="report_parameters">
    <form method="post" name="theform" id='theform' action="custom_report_range.php">
        <input type='hidden' name='form_refresh' id='form_refresh' value=''/>
        <table>
            <tr>
                <td width='650px'>
                    <div style='float:left'>
                        <table class='text'>
                            <tr>
                                <?php // Show from and to dates. (TRK)
                                    showFromAndToDates(); ?>
                                <td>
                                    &nbsp;&nbsp;<span class='text'><?php echo xlt('Patient'); ?>: </span>
                                </td>
                                <td>
                                    <input type='text' size='20' name='form_patient' style='width:100%;cursor:pointer;cursor:hand' value='<?php echo attr($form_patient) ? attr($form_patient) : xla('Click To Select'); ?>' onclick='sel_patient()' title='<?php echo xla('Click to select patient'); ?>' />
                                    <input type='hidden' name='form_pid' value='<?php echo attr($form_pid); ?>' />
                                </td>
                            </tr>
                            <tr><td>
                            </tr>
                        </table>
                    </div>
                </td>
                <?php // Show print and export buttons. (TRK)
                    showSubmitPrintButtons('form_csvexport'); ?>
            </tr>
        </table>
    </form>
</div> <!-- end of parameters -->

<div id="superbill_results">
    <?php // Display the results from query
        showResults(); ?>
</div>

    </body>

<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });

</script>
</html>
