<?php
/*
 * Report Results
 *
 * Generic script to list stored reports. Part of the module to allow the tracking,
 * storing, and viewing of reports.
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2012 Brady Miller <brady@sparmy.com>
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
 * @author  Art Eaton <art@suncoastconnection.com>  (MIPS/MACRA Refactor)
 * @author  Bryan Lee <bryan@suncoastconnection.com>
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://librehealth.io
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once ('mips_headers.inc.php');
require_once ('clinical_rules.php');
/** Current format of date  */
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
?>

<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<title><?php echo htmlspecialchars( xl('Report Results/History'), ENT_NOQUOTES); ?></title>

<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../assets/js/jquery-min-3-1-1/index.js"></script>
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

<span class='title'><?php echo htmlspecialchars( xl('Report History/Results'), ENT_NOQUOTES); ?></span>

<form method='post' name='theform' id='theform' action='report_results.php' onsubmit='return top.restoreSession()'>

<div id="report_parameters">

<table>
 <tr>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid; width:100%; height:100%' >
        <tr>
            <td>
                <div style='margin-left:15px'>
                     <a id='refresh_button' href='#' class='css_button' onclick='top.restoreSession(); $("#theform").submit()'>
                     <span>
                             <?php echo htmlspecialchars( xl('Refresh'), ENT_NOQUOTES); ?>
                     </span>
                     </a>
                </div>
            </td>
        </tr>
    </table>
  </td>
 </tr>
</table>

</div>  <!-- end of search parameters -->

<br>



<div id="report_results">
<table>

 <thead>
  <th align='left'>
   <?php echo htmlspecialchars( xl('Delete'), ENT_NOQUOTES); ?>
  </th>
  <th align='left'>
   <?php echo htmlspecialchars( xl('Rename'), ENT_NOQUOTES); ?>
  </th>
  <th align='center'>
   <?php echo htmlspecialchars( xl('Title'), ENT_NOQUOTES); ?>
  </th>

  <th align='center'>
   <?php echo htmlspecialchars( xl('Date'), ENT_NOQUOTES); ?>
  </th>

  <th align='center'>
   <?php echo htmlspecialchars( xl('Status'), ENT_NOQUOTES); ?>
  </th>

 </thead>
 <tbody>  <!-- added for better print-ability -->
<?php

 $res = listingReportDatabase(prepareDateBeforeSave(""), prepareDateBeforeSave(""));
 while ($row = sqlFetchArray($res)) {

  // Figure out the title
  if ($row['title'] ) {
      $type_title = $row['title'];   
    }
    else if ($row['pat_prov_rel'] == "encounter"){
        $type_title = $row['provider'];
    }
  else {
    $type_title = xlt("Group Report");
    }
  //Add the link
    $link="clinical_measures.php?report_id=" . attr($row["report_id"]) . "&back=list";  
?>
 <tr>
      <td>
         <a id='delete_button' href='#' class='css_button_small'
           onclick='deleteReport(<?php echo htmlspecialchars( $row["report_id"] ); ?>); top.restoreSession(); $("#theform").submit()' >
         <span>Delete</span></a>
      </td>
      <td>
         <a id='rename_button' href='#' class='css_button_small'
           onclick='renameReport(<?php echo htmlspecialchars( $row["report_id"] ) ?>); top.restoreSession(); $("#theform").submit()' >
         <span>Rename</span></a>
      </td>
    <?php if ($row["progress"] == "complete") { ?>
      <td align='center'><a href='<?php echo $link; ?>' onclick='top.restoreSession()'><?php echo text($type_title); ?></a></td>
    <?php } else { ?>
      <td align='center'><?php echo text($type_title); ?></td>
    <?php } ?>
    <td align='center'><?= date(DateFormatRead(true), strtotime($row["date_report"])); ?></td>
    <?php if ($row["progress"] == "complete") { ?>
      <td align='center'><?php echo xlt("Complete") . " (" . xlt("Processing Time") . ": " . text($row['report_time_processing']) . " " . xlt("Minutes") . ")"; ?></td>
    <?php } else { ?>
      <td align='center'><?php echo xlt("Pending") . " (" . text($row["progress_items"]) . " / " . text($row["total_items"]) . " " . xlt("Patients Processed") . ")"; ?></td>
    <?php } ?>

 </tr>

<?php
 } // $row = sqlFetchArray($res) while
?>
</tbody>
</table>
</div>  <!-- end of search results -->

</form>

</body>

<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_begin_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_end_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>
<!--  Stuff for deleting and renaming reports -->
<script type='text/javascript'>
function manageReport(report_id,action,newname){
        $.ajax({
                type: 'POST',
                url: '<?php echo $GLOBALS['webroot']; ?>/modules/MIPS/PQRSReportManager.php',
                dataType: 'text',
                data: {
                        action: action,
                        report_id: report_id,
                        report_new_name: newname,
                },
                success: function(data, status, xHR) {
                        if(data == 'SUCCESS') {
                                console.log('Update succeeded.');
                        } else {
                                console.log('Update failed: '+ data);
                        }
                },
                error: function(xHR, status, error) {
                        console.log('Status: ' + status + ', Error: ' + error);
                }
        });
}

function deleteReport(report_id){
    var answer = confirm('Are you sure you want to delete this report?');
    if (answer == true) {
        console.log('Delete Report -- report_id: ' + report_id );
    manageReport(report_id,'DELETE','deleted');
    }
}

function renameReport(report_id){
    var newname = prompt('New name for this report?');
    if (newname == "")  {
        confirm('You didn\'t supply a new name.');
    } else {
        console.log('Rename Report -- report_id: ' + report_id + ', New Name: ' + newname);
    manageReport(report_id,'RENAME',newname);

    }
top.restoreSession();  $("#theform").submit();
}

</script>

</html>

