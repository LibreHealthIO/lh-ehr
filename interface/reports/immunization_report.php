<?php
/*
 * Immunization report
 * This report lists  patient immunizations for a given date range.
 *
 * Copyright (C) 2011 Ensoftek Inc.
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
 * @author Ensoftek Inc.
 * @link http://librehealth.io
 */

require_once "reports_controllers/ImmunizationController.php";

?>

<html>
<head>
<?php html_header_show();?>
<title><?php xl('Immunization Registry','e'); ?></title>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script language="JavaScript">
<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
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

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Immunization Registry','e'); ?></span>

<?php reportParametersDaterange(); #TRK ?>

<form name='theform' id='theform' method='post' action='immunization_report.php'
onsubmit='return top.restoreSession()'>
<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_get_hl7' id='form_get_hl7' value=''/>
<table>
 <tr>
  <td width='410px'>
    <div style='float:left'>
      <table class='text'>
        <tr>
          <td class='label'>
            <?php xl('Codes','e'); ?>:
          </td>
          <td>
            <?php
             // Build a drop-down list of codes.
             //
             $query1 = "select id, concat('CVX:',code) as name from codes ".
               " left join code_types ct on codes.code_type = ct.ct_id ".
               " where ct.ct_key='CVX' ORDER BY name";
             $cres = sqlStatement($query1);
             echo "   <select multiple='multiple' size='3' name='form_code[]'>\n";
             //echo "    <option value=''>-- " . xl('All Codes') . " --\n";
             while ($crow = sqlFetchArray($cres)) {
              $codeid = $crow['id'];
              echo "    <option value='$codeid'";
              if (in_array($codeid, $form_code)) echo " selected";
              echo ">" . $crow['name'] . "\n";
             }
             echo "   </select>\n";
            ?>
          </td>
          <?php // Show From and To dates fields. (TRK)
            showFromAndToDates(); ?>
        </tr>
      </table>
    </div>
  </td>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid; width:100%; height:100%' >
      <tr>
        <td>
          <div style='margin-left:15px'>
            <a href='#' class='css_button cp-misc'
            onclick='
            $("#form_refresh").attr("value","true");
            $("#form_get_hl7").attr("value","false");
            $("#theform").submit();
            '>
            <span>
              <?php xl('Refresh','e'); ?>
            </span>
            </a>
            <?php if ($_POST['form_refresh']) { ?>
              <a href='#' class='css_button cp-output' id='printbutton'>
                <span>
                  <?php xl('Print','e'); ?>
                </span>
              </a>
              <a href='#' class='css_button cp-output' onclick=
              "if(confirm('<?php xl('This step will generate a file which you have to save for future use. The file cannot be generated again. Do you want to proceed?','e'); ?>')) {
                     $('#form_get_hl7').attr('value','true');
                     $('#theform').submit();
              }">
                <span>
                  <?php xl('Get HL7','e'); ?>
                </span>
              </a>
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
 if ($_POST['form_refresh']) {
?>
<div id="report_results">
<table>
 <thead align="left">
  <th> <?php xl('Patient ID','e'); ?> </th>
  <th> <?php xl('Patient Name','e'); ?> </th>
  <th> <?php xl('Immunization Code','e'); ?> </th>
  <th> <?php xl('Immunization Title','e'); ?> </th>
  <th> <?php xl('Immunization Date','e'); ?> </th>
 </thead>
 <tbody>
<?php
  $total = 0;
  //echo "<p> DEBUG query: $query </p>\n"; // debugging
  $res = sqlStatement($query);


  while ($row = sqlFetchArray($res)) {
?>
 <tr>
  <td>
  <?php echo htmlspecialchars($row['patientid']) ?>
  </td>
  <td>
   <?php echo htmlspecialchars($row['patientname']) ?>
  </td>
  <td>
   <?php echo htmlspecialchars($row['cvx_code']) ?>
  </td>
  <td>
   <?php echo htmlspecialchars($row['immunizationtitle']) ?>
  </td>
  <td>
   <?= date(DateFormatRead(true), strtotime(htmlspecialchars($row['immunizationdate']))); ?>
  </td>
 </tr>
<?php
   ++$total;
  }
?>
 <tr class="report_totals">
  <td colspan='9'>
   <?php xl('Total Number of Immunizations','e'); ?>
   :
   <?php echo $total ?>
  </td>
 </tr>

</tbody>
</table>
</div> <!-- end of results -->
<?php } else { ?>
<div class='text'>
  <?php echo xl('Click Refresh to view all results, or please input search criteria above to view specific results.', 'e' ); ?>
</div>
<?php } ?>
</form>

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

</body>
</html>
