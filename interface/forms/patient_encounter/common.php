<?php
/**
 * Common script for the encounter form (new and view) scripts.
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
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author  Brady Miller <brady@sparmy.com>
 * @author  Terry Hill <teryhill@librehealth.io>
 * @link    http://librehealth.io
 */

require_once("$srcdir/options.inc.php");
require_once("$srcdir/headers.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$months = array("01","02","03","04","05","06","07","08","09","10","11","12");
$days = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14",
  "15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
$thisyear = date("Y");
$years = array($thisyear-1, $thisyear, $thisyear+1, $thisyear+2);

if ($viewmode) {
  $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
  $result = sqlQuery("SELECT * FROM form_encounter WHERE id = ?", array($id));
  $encounter = $result['encounter'];
  if ($result['sensitivity'] && !acl_check('sensitivities', $result['sensitivity'])) {
    echo "<body>\n<html>\n";
    echo "<p>" . xlt('You are not authorized to see this encounter.') . "</p>\n";
    echo "</body>\n</html>\n";
    exit();
  }
}

// Sort comparison for sensitivities by their order attribute.
function sensitivity_compare($a, $b) {
  return ($a[2] < $b[2]) ? -1 : 1;
}

// get issues
$ires = sqlStatement("SELECT id, type, title, begdate FROM lists WHERE " .
  "pid = ? AND enddate IS NULL " .
  "ORDER BY type, begdate", array($pid));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php 
  html_header_show();
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker","fancybox"));
?>
<title><?php echo xlt('Patient Encounter'); ?></title>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/overlib_mini.js"></script>

<?php include_once("{$GLOBALS['srcdir']}/ajax/facility_ajax_jav.inc.php"); ?>
<script language="JavaScript">

 // Process click on issue title.
 function newissue() {
  dlgopen('../../patient_file/summary/add_edit_issue.php', '_blank', 800, 600);
  return false;
 }

 // callback from add_edit_issue.php:
 function refreshIssue(issue, title) {
  var s = document.forms[0]['issues[]'];
  s.options[s.options.length] = new Option(title, issue, true, true);
 }

 function saveClicked() {
  var f = document.forms[0];

  var category = document.forms[0].pc_catid.value;
  if ( category == '_blank' ) {
   alert("<?php echo xls('You must select a visit category'); ?>");
   return;
  }

  top.restoreSession();
  f.submit();
 }

$(document).ready(function(){
  enable_big_modals();
});
function bill_loc(){
var pid=<?php echo attr($pid);?>;
var dte=document.getElementById('form_date').value;
var facility=document.forms[0].facility_id.value;
ajax_bill_loc(pid,dte,facility);
}

// Handler for Cancel clicked when creating a new encounter.
// Show demographics or encounters list depending on what frame we're in.
function cancelClicked() {
 if (window.name == 'RBot') {
  parent.left_nav.setRadio(window.name, 'ens');
  parent.left_nav.loadFrame('ens1', window.name, 'patient_file/history/encounters.php');
 }
 else {
  parent.left_nav.setRadio(window.name, 'dem');
  parent.left_nav.loadFrame('dem1', window.name, 'patient_file/summary/demographics.php');
 }
 return false;
}

</script>
</head>

<?php if ($viewmode) { ?>
<body class="body_top">
<?php } else { ?>
<body class="body_top" onload="javascript:document.new_encounter.reason.focus();">
<?php } ?>

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<form method='post' action="<?php echo $rootdir ?>/forms/patient_encounter/save.php" name='new_encounter'>

<div style='float:left'>
<?php if ($viewmode) { ?>
<input type=hidden name='mode' value='update'>
<input type=hidden name='id' value='<?php echo (isset($_GET["id"])) ? attr($_GET["id"]) : '' ?>'>
<span class=title><?php echo xlt('Patient Encounter Form'); ?></span>
<?php } else { ?>
<input type='hidden' name='mode' value='new'>
<span class='title'><?php echo xlt('New Encounter Form'); ?></span>
<?php } ?>
</div>

<div>
    <div style = 'float:left; margin-left:8px;margin-top:-3px'>
      <a href="javascript:saveClicked();" class="css_button link_submit cp-submit"><span><?php echo xlt('Save'); ?></span></a>
      <?php if ($viewmode || !isset($_GET["autoloaded"]) || $_GET["autoloaded"] != "1") { ?>
    </div>

    <div style = 'float:left; margin-top:-3px'>
      <a href="<?php echo "$rootdir/patient_file/encounter/encounter_top.php"; ?>"
        class="css_button link_submit" onClick="top.restoreSession()"><span><?php echo xlt('Cancel'); ?></span></a>
  <?php } else { // not $viewmode ?>
      <a href="" class="css_button link_submit cp-negative" onClick="return cancelClicked()">
      <span><?php echo xlt('Cancel'); ?></span></a>
  <?php } // end not $viewmode ?>
    </div>
 </div>

<br> <br>

<div>

 <div class="row">
  <div class="col-xs-12 col-sm-4 col-lg-4 ">
    <?php echo xlt('Consultation Brief Description'); ?>:
    <textarea class="form-control input-sm" style="resize:none" name='reason' cols='40' rows='12' wrap='virtual'>
      <?php echo $viewmode ? text($result['reason']) : text($GLOBALS['default_chief_complaint']); ?>
    </textarea>
    <hr>  
  </div>
  <div class="col-xs-12 col-sm-4 col-lg-4 ">
   <table>

    <tr>
     <td class='bold'><?php echo xlt('Visit Category:'); ?></td>
     <td class='text'>
      <select style="width:150px" name='pc_catid' id='pc_catid'>
      <option value='_blank'><?php echo xlt('Select One'); ?></option>
        <?php
        $cres = sqlStatement("SELECT pc_catid, pc_catname " .
          "FROM libreehr_postcalendar_categories ORDER BY pc_catname");
        while ($crow = sqlFetchArray($cres)) {
          $catid = $crow['pc_catid'];
          if ($catid < 9 && $catid != 5) continue;
          echo "<option value='" . attr($catid) . "'";
          if ($viewmode && $crow['pc_catid'] == $result['pc_catid']) echo " selected";
          echo ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
        }
        ?>
      </select>
     </td>
    </tr>

    <tr>
     <td class='bold'><?php echo xlt('Place of Service:'); ?></td>
     <td class='text'>
      <select style="width:150px" name='facility_id' onChange="bill_loc()">
        <?php

        if ($viewmode) {
          $def_facility = $result['facility_id'];
        } else {
          $dres = sqlStatement("select facility_id from users where username = ?", array($_SESSION['authUser']));
          $drow = sqlFetchArray($dres);
          $def_facility = $drow['facility_id'];
        }
        $fres = sqlStatement("select * from facility where service_location != 0 order by name");
        if ($fres) {
          $fresult = array();
          for ($iter = 0; $frow = sqlFetchArray($fres); $iter++)
            $fresult[$iter] = $frow;
          foreach($fresult as $iter) {
        ?>
              <option value="<?php echo attr($iter['id']); ?>" <?php if ($def_facility == $iter['id']) echo "selected";?>><?php echo text($iter['name']); ?></option>
        <?php
          }
        }
        ?>
      </select>
     </td>
    </tr>
    <tr>
        <td class='bold'><?php echo xlt('Billing Facility'); ?>:</td>
        <td class='text'>
            <div id="ajaxdiv">
            <?php
            billing_facility('billing_facility',$result['billing_facility']);
            ?>
            </div>
        </td>
     </tr>
    <tr>
<?php
 $sensitivities = acl_get_sensitivities();
 if ($sensitivities && count($sensitivities)) {
  usort($sensitivities, "sensitivity_compare");
?>
     <td class='bold'><?php echo xlt('Sensitivity:'); ?></td>
     <td class='text'>
      <select style="width:150px" name='form_sensitivity'>
<?php
  foreach ($sensitivities as $value) {
   // Omit sensitivities to which this user does not have access.
   if (acl_check('sensitivities', $value[1])) {
    echo "       <option value='" . attr($value[1]) . "'";
    if ($viewmode && $result['sensitivity'] == $value[1]) echo " selected";
    echo ">" . xlt($value[3]) . "</option>\n";
   }
  }
  echo "       <option value=''";
  if ($viewmode && !$result['sensitivity']) echo " selected";
  echo ">" . xlt('None'). "</option>\n";
?>
      </select>
     </td>
<?php
 } else {
?>
     <td colspan='2'><!-- sensitivities not used --></td>
<?php
 }
?>
    </tr>

    <tr<?php if (!$GLOBALS['gbl_visit_referral_source']) echo " style='visibility:hidden;'"; ?>>
     <td class='bold' ><?php echo xlt('Referral Source'); ?>:</div>
     <td class='text'>
<?php
  echo generate_select_list('form_referral_source', 'refsource', $viewmode ? $result['referral_source'] : '', '');
?>
     </td>
    </div>

    <tr>
     <td class='bold' ><?php echo xlt('Date of Service:'); ?></td>
     <td class='text' >
       <input type='text' size='10' name='form_date' id='form_date' <?php echo $disabled ?>
              value='<?php echo $viewmode ? oeFormatShortDate(substr($result['date'], 0, 10)) : date($DateFormat); ?>'
              title='<?php echo xla('Date of Service'); ?>'/>
     </td>
    </tr>

    <tr<?php if ($GLOBALS['ippf_specific']) echo " style='visibility:hidden;'"; ?>>
     <td class='bold' ><?php echo xlt('Onset/hosp. date:'); ?></td>
     <td class='text' ><!-- default is blank so that while generating claim the date is blank. -->
      <input type='text' size='10' name='form_onset_date' id='form_onset_date' 
             value='<?php echo $viewmode && $result['onset_date']!='0000-00-00 00:00:00' ? oeFormatShortDate(substr($result['onset_date'], 0, 10)) : ''; ?>'
             title='<?php echo xla('Date of onset or hospitalization'); ?>'/>
     </td>
    </tr>

    <tr>
     <td class='text' colspan='2' style='padding-top:1em'>
     </td>
    </tr>

   </table>

  </div>

  <div class="col-xs-12 col-sm-4 col-lg-4 " >
    <div>
   <?php echo xlt('Issues (Injuries/Medical/Allergy)'); ?>
    </div>
    <div>
      <a href="../../patient_file/summary/add_edit_issue.php" class="css_button_small link_submit iframe cp-positive"
       onclick="top.restoreSession()"><span><?php echo xlt('Add'); ?></span></a>
    </div>
    <div>
    <select multiple class="form-control input-sm" name='issues[]' size='8' 
      title='<?php echo xla('Hold down [Ctrl] for multiple selections or to unselect'); ?>'>
        <?php
        while ($irow = sqlFetchArray($ires)) {
          $list_id = $irow['id'];
          $tcode = $irow['type'];
          if ($ISSUE_TYPES[$tcode]) $tcode = $ISSUE_TYPES[$tcode][2];
          echo "    <option value='" . attr($list_id) . "'";
          if ($viewmode) {
            $perow = sqlQuery("SELECT count(*) AS count FROM issue_encounter WHERE " .
              "pid = ? AND encounter = ? AND list_id = ?", array($pid,$encounter,$list_id));
            if ($perow['count']) echo " selected";
          }
          else {
            // For new encounters the invoker may pass an issue ID.
            if (!empty($_REQUEST['issue']) && $_REQUEST['issue'] == $list_id) echo " selected";
          }
          echo ">" . text($tcode) . ": " . text($irow['begdate']) . " " .
            text(substr($irow['title'], 0, 40)) . "</option>\n";
        }
        ?>
   </select>
   </div>

   <p><i><?php echo xlt('To link this encounter/consult to an existing issue, click the '
   . 'desired issue above to highlight it and then click [Save]. '
   . 'Hold down [Ctrl] button to select multiple issues.'); ?>
   </i></p>

 </div> 

</div>

</form>

</body>

<script>
    $(function() {
        $("#form_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_onset_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>

<script language="javascript">
<?php
if (!$viewmode) { ?>
 function duplicateVisit(enc, datestr) {
    if (!confirm('<?php echo xl("A visit already exists for this patient today. Click Cancel to open it, or OK to proceed with creating a new one.") ?>')) {
            // User pressed the cancel button, so re-direct to today's encounter
            top.restoreSession();
            parent.left_nav.setEncounter(datestr, enc, window.name);
            parent.left_nav.setRadio(window.name, 'enc');
            parent.left_nav.loadFrame('enc2', window.name, 'patient_file/encounter/encounter_top.php?set_encounter=' + enc);
            return;
        }
        // otherwise just continue normally
    }    
<?php

  // Search for an encounter from today
  $erow = sqlQuery("SELECT fe.encounter, fe.date " .
    "FROM form_encounter AS fe, forms AS f WHERE " .
    "fe.pid = ? " . 
    " AND fe.date >= ? " . 
    " AND fe.date <= ? " .
    " AND " .
    "f.formdir = 'patient_encounter' AND f.form_id = fe.id AND f.deleted = 0 " .
    "ORDER BY fe.encounter DESC LIMIT 1",array($pid,date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')));

  if (!empty($erow['encounter'])) {
    // If there is an encounter from today then present the duplicate visit dialog
    echo "duplicateVisit('" . $erow['encounter'] . "', '" .
      oeFormatShortDate(substr($erow['date'], 0, 10)) . "');\n";
  }
}
?>
</script>
<?php 
  /*
   Making fancybox compatible with jquery 3.1.1 as $.browser is removed in jquery 1.9
  */
  resolveFancyboxCompatibility();
?>
</html>
