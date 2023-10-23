<?php
/*
 * Stats Full
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2005-2014 Rod Roark <rod@sunsetsystems.com>
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
 * @author Rod Roark <rod@sunsetsystems.com>
 * @link http://librehealth.io
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once('../../globals.php');
require_once($GLOBALS['srcdir'].'/lists.inc');
require_once($GLOBALS['srcdir'].'/acl.inc');
require_once($GLOBALS['fileroot'].'/custom/code_types.inc.php');
require_once($GLOBALS['srcdir'].'/options.inc.php');
require_once($GLOBALS['srcdir'].'/headers.inc.php');


 // Check authorization.
 if (acl_check('patients','med')) {
  $tmp = getPatientData($pid, "squad");
  if ($tmp['squad'] && ! acl_check('squads', $tmp['squad']))
   die(htmlspecialchars( xl('Not authorized'), ENT_NOQUOTES) );
 }
 else {
  die(htmlspecialchars( xl('Not authorized'), ENT_NOQUOTES) );
 }

 // Collect parameter(s)
 $category = empty($_REQUEST['category']) ? '' : $_REQUEST['category'];

// Get patient's preferred language for the patient education URL.
$tmp = getPatientData($pid, 'language');
$language = $tmp['language'];
?>
<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<span class="title" style="display: none;">Issues</span>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/dialog.js"></script>
    <?php call_required_libraries(array("jquery-min-3-1-1" , "font-awesome" , "iziModalToast")); ?>

<script language="JavaScript">

    //variables to store titles and subtitles
    var iziTitle = "";
    var iziSubTitle = "";

    $(".fa-refresh").click(function () {
        top.restoreSession();
        location.reload();
    });
    // callback from add_edit_issue.php:
    function refreshIssue(issue, title) {
        top.restoreSession();
        location.reload();
    }

    //takes 3 parameters, the id of issue to be edited, the category(allergy, mediacal etc) and type(whether new or edit)
    function dopclick(id, category , type) {
        <?php if (acl_check('patients', 'med', '', 'write')): ?>
        if (category == 0) category = '';
        iziTitle = "<?php echo xlt('Add New Issue'); ?>";
        iziSubTitle = "<?php echo xlt('Add Relevant Patient Issues'); ?>";
        initIziLink('add_edit_issue.php?issue=' + encodeURIComponent(id) + '&thistype=' + encodeURIComponent(category), 850, 400,category, type);
        <?php else: ?>
        alert("<?php echo addslashes(xl('You are not authorized to add/edit issues')); ?>");
        <?php endif; ?>
    }
  
        //function to remove underscore
    function titleFormat(str){
        var newstr = str.replace(/_/g, " ");
        return newstr.toUpperCase();
    }
    // function to open izi-modal
    function  initIziLink(link , width , height, category, type) {

        category = titleFormat(category);
      
        $("#izi-iframe").iziModal({
            title: '<b style="color: white">'+category+'</b>',
            subtitle: type+ " Issue",
            headerColor: '#88A0B9',
            closeOnEscape: true,
            fullscreen:true,
            overlayClose: false,
            closeButton: true,
            theme: 'light',  // light
            iframe: true,
            width:width,
            focusInput: true,
            padding:2,
            iframeHeight: height,
            iframeURL:link,
            onClosed: function () {
                location.reload();
            }
        });
        setTimeout(function () {
            call_izi();
        },500);

    }

    function call_izi() {
        $("#izi-iframe").iziModal('open');
    }

    // Process click on number of encounters.
    function doeclick(id) {
        iziTitle = "<?php echo xlt('Add Ecounter/Issue'); ?>";
        iziSubTitle = "<?php echo xlt('Add Relevant Encounter/Issues about Patient'); ?>";
        initIziLink('../problem_encounter.php?issue=' + id, 1250, 460 , iziTitle , iziSubTitle);
    }

    // Add Encounter button is clicked.
    function newEncounter() {
        var f = document.forms[0];
        top.restoreSession();
        location.href = '../../forms/patient_encounter/new.php?autoloaded=1&calenc=';
    }

</script>

</head>

<body class="body_top">

<div id="izi-iframe"></div><!-- to initialize the izimodal -->

<br>
<div style="text-align:center" class="buttons">
  <a href="demographics.php" class="css_button" onclick="top.restoreSession()">
    <span><?php echo htmlspecialchars(xl('Back To Patient'),ENT_NOQUOTES);?></span>
  </a>
</div>
<br>
<br>

<div id='patient_stats'>

<form method='post' action='stats_full.php' onsubmit='return top.restoreSession()'>

<table>

<?php
$encount = 0;
$lasttype = "";
$first = 1; // flag for first section
foreach ($ISSUE_TYPES as $focustype => $focustitles) {

  if ($category) {
    // Only show this category
    if ($focustype != $category) continue;
  }

  if ($first) {
    $first = 0;
  }
  else {
    echo "</table>";
  }

  // Show header
  $disptype = $focustitles[0];
  if(($focustype=='allergy' || $focustype=='medication') && $GLOBALS['erx_enable'])
  echo "<a href='../../eRx.php?page=medentry' class='css_button_small' onclick='top.restoreSession()' ><span>" . htmlspecialchars( xl('Add'), ENT_NOQUOTES) . "</span></a>\n";
  else
  echo "<a href='javascript:;' class='css_button_small' onclick='dopclick(0,\"" . htmlspecialchars($focustype,ENT_QUOTES)  . "\", \"Add\")'><span>" . htmlspecialchars( xl('Add'), ENT_NOQUOTES) . "</span></a>\n";
  echo "  <span class='title'>" . htmlspecialchars($disptype,ENT_NOQUOTES) . "</span>\n";
  // echo " <table style='margin-bottom:1em;text-align:center'>";
  echo " <table style='margin-bottom:1em;'>";
  ?>
  <tr class='head'>
    <th style='text-align:left'><?php echo xlt('Title'); ?></th>
    <th style='text-align:left'><?php echo xlt('Begin'); ?></th>
    <th style='text-align:left'><?php echo xlt('End'); ?></th>
    <th style='text-align:left'><?php echo xlt('Coding (click for education)'); ?></th>
    <th style='text-align:left'><?php echo xlt('Status'); ?></th>
    <th style='text-align:left'><?php echo xlt('Occurrence'); ?></th>
    <?php if ($focustype == "allergy") { ?>
      <th style='text-align:left'><?php echo xlt('Reaction'); ?></th>
    <?php } ?>
    <th style='text-align:left'><?php echo xlt('Referred By'); ?></th>
    <th style='text-align:left'><?php echo xlt('Modify Date'); ?></th>
    <th style='text-align:left'><?php echo xlt('Comments'); ?></th>
    <th><?php echo xlt('Enc'); ?></th>
    </tr>
  <?php

  // collect issues
  $condition = '';
  if($GLOBALS['erx_enable'] && $GLOBALS['erx_medication_display'] && $focustype=='medication')
   $condition .= "and erx_uploaded != '1' ";
  $pres = sqlStatement("SELECT * FROM lists WHERE pid = ? AND type = ? $condition" .
   "ORDER BY begdate", array($pid,$focustype) );

  // if no issues (will place a 'None' text vs. toggle algorithm here)
  if (sqlNumRows($pres) < 1) {
    if ( getListTouch($pid,$focustype) ) {
      // Data entry has happened to this type, so can display an explicit None.
      echo "<tr><td class='text'><b>" . xlt("None") . "</b></td></tr>";
    }
    else {
      // Data entry has not happened to this type, so can show the none selection option.
      echo "<tr><td class='text'><input type='checkbox' class='noneCheck' name='" .
        attr($focustype) . "' value='none' /><b>" . xlt("None") . "</b></td></tr>";
    }
  }

  // display issues
  while ($row = sqlFetchArray($pres)) {

    $rowid = $row['id'];

    $disptitle = trim($row['title']) ? $row['title'] : "[Missing Title]";

    $ierow = sqlQuery("SELECT count(*) AS count FROM issue_encounter WHERE " .
      "list_id = ?", array($rowid) );

    // encount is used to toggle the color of the table-row output below
    ++$encount;
    $bgclass = (($encount & 1) ? "bg1" : "bg2");

    $colorstyle = empty($row['enddate']) ? "style='color:red'" : "";

    // look up the diag codes
    $codetext = "";
    if ($row['diagnosis'] != "") {
        $diags = explode(";", $row['diagnosis']);
        foreach ($diags as $diag) {
            $codedesc = lookup_code_descriptions($diag);
            list($codetype, $code) = explode(':', $diag);
            if ($codetext) $codetext .= "<br />";
            $codetext .= "<a href='javascript:educlick(\"$codetype\",\"$code\")' $colorstyle>" .
              text($diag . " (" . $codedesc . ")") . "</a>";
        }
    }

    // calculate the status
    if ($row['outcome'] == "1" && $row['enddate'] != NULL) {
      // Resolved
      $statusCompute = generate_display_field(array('data_type'=>'1','list_id'=>'outcome'), $row['outcome']);
    }
    else if($row['enddate'] == NULL) {
      $statusCompute = xlt("Active");
    }
    else {
      $statusCompute = xlt("Inactive");
    }
    $click_class='statrow';
    if($row['erx_source']==1 && $focustype=='allergy') $click_class='';
    elseif($row['erx_uploaded']==1 && $focustype=='medication') $click_class='';

    echo " <tr class='$bgclass detail' $colorstyle>\n";
    echo "  <td style='text-align:left' data-text='$disptitle' class='$click_class' id='$rowid'>" . text($disptitle) . "</td>\n";
    if ($row['begdate'] == '') echo "  <td>" . 'N/A' . "&nbsp;</td>\n";
    else echo "  <td>" . text(date(DateFormatRead(true), strtotime($row['begdate']))) . "&nbsp;</td>\n";
    if ($row['enddate'] == '') echo "  <td>" . 'N/A' . "&nbsp;</td>\n";
    else echo "  <td>" . text(date(DateFormatRead(true), strtotime($row['enddate']))) . "&nbsp;</td>\n";
    // both codetext and statusCompute have already been escaped above with htmlspecialchars)
    echo "  <td>" . $codetext . "</td>\n";
    echo "  <td>" . $statusCompute . "&nbsp;</td>\n";
    echo "  <td class='nowrap'>";
    echo generate_display_field(array('data_type'=>'1','list_id'=>'occurrence'), $row['occurrence']);
    echo "</td>\n";
    if ($focustype == "allergy") {
      echo "  <td>";
        echo generate_display_field(array('data_type'=>'1','list_id'=>'reaction'), $row['reaction']);
      echo "</td>\n";
    }
    echo "  <td>" . text($row['referredby']) . "</td>\n";
    echo "  <td>" . text($row['modifydate']) . "</td>\n";
    echo "  <td>" . text($row['comments']) . "</td>\n";
    echo "  <td id='e_$rowid' class='noclick center' title='" . xla('View related encounters') . "'>";
    echo "  <input type='button' value='" . attr($ierow['count']) . "' class='editenc' id='" . attr($rowid) . "' />";
    echo "  </td>";
    echo " </tr>\n";
  }
}
echo "</table>";
?>

</table>

</form>
</div> <!-- end patient_stats -->

</body>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $(".statrow")
        .mouseover(function() { $(this).toggleClass("highlight"); })
        .mouseout(function() { $(this).toggleClass("highlight"); })
        .click(function() { dopclick(this.id, this.innerHTML, 'Edit')});

    $(".editenc").click(function(event) { doeclick(this.id); });
    $("#newencounter").click(function() { newEncounter(); });
    $("#history").click(function() { GotoHistory(); });
    $("#back").click(function() { GoBack(); });

    $(".noneCheck").click(function() {
      top.restoreSession();
      $.post( "../../../library/ajax/lists_touch.php", { type: this.name, patient_id: <?php echo htmlspecialchars($pid,ENT_QUOTES); ?>, token: "<?php echo $_SESSION['token'];?>" });
      $(this).hide();
    });
});

var GotoHistory = function() {
    top.restoreSession();
    location.href='../history/history_full.php';
}

var GoBack = function () {
    top.restoreSession();
    location.href='demographics.php';
}

</script>

</html>
