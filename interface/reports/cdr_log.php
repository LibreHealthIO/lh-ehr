<?php
/**
 * CDR trigger log report.
 *
 * Copyright (C) 2015 Brady Miller <brady@sparmy.com>
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
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
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://librehealth.io
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../globals.php");
require_once("../../library/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/clinical_rules.php";
require_once($GLOBALS['srcdir']."/formatting.inc.php");
$DateFormat = DateFormatRead(true);
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
?>

<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<title><?php echo xlt('Alerts Log'); ?></title>

<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>


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

<span class='title'><?php echo xlt('Alerts Log'); ?></span>

<form method='post' name='theform' id='theform' action='cdr_log.php?search=1' onsubmit='return top.restoreSession()'>

<div id="report_parameters">

<table>
 <tr>
  <td width='470px'>
    <div style='float:left'>

    <table class='text'>

                   <tr>
                      <td class='label'>
                         <?php echo xlt('Begin Date'); ?>:
                      </td>
                      <td>
                         <input type='text' name='form_begin_date' id='form_begin_date' size='20'
                                value='<?php echo htmlspecialchars(oeFormatShortDate(attr($_POST['form_begin_date']))); ?>' />
                      </td>
                   </tr>

                <tr>
                        <td class='label'>
                              <?php echo xlt('End Date'); ?>:
                        </td>
                        <td>
                           <input type='text' name='form_end_date' id='form_end_date' size='20'
                                  value='<?php echo htmlspecialchars(oeFormatShortDate(attr($_POST['form_end_date']))); ?>'/>
                        </td>
                </tr>
    </table>
    </div>

  </td>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid; width:100%; height:100%' >
        <tr>
            <td>
                <div style='margin-left:15px'>
                    <a id='search_button' href='#' class='css_button' onclick='top.restoreSession(); $("#theform").submit()'>
                    <span>
                        <?php echo xlt('Search'); ?>
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

<?php if ($_GET['search'] == 1) { ?>

 <div id="report_results">
 <table>

 <thead>
  <th align='center'>
   <?php echo xlt('Date'); ?>
  </th>

  <th align='center'>
   <?php echo xlt('Patient ID'); ?>
  </th>

  <th align='center'>
   <?php echo xlt('User ID'); ?>
  </th>

  <th align='center'>
   <?php echo xlt('Category'); ?>
  </th>

  <th align='center'>
   <?php echo xlt('All Alerts'); ?>
  </th>

  <th align='center'>
   <?php echo xlt('New Alerts'); ?>
  </th>

 </thead>
 <tbody>  <!-- added for better print-ability -->
 <?php
 $res = listingCDRReminderLog($_POST['form_begin_date'],$_POST['form_end_date']);

 while ($row = sqlFetchArray($res)) {
  //Create category title
  if ($row['category'] == 'clinical_reminder_widget') {
   $category_title = xl("Passive Alert");
  }
  else if ($row['category'] == 'active_reminder_popup') {
   $category_title = xl("Active Alert");
  }
  else if ($row['category'] == 'allergy_alert') {
   $category_title = xl("Allergy Warning");
  }
  else {
   $category_title = $row['category'];
  }
  //Prepare the targets
  $all_alerts = json_decode($row['value'], true);
  if (!empty($row['new_value'])) {
   $new_alerts = json_decode($row['new_value'], true);
  }
?>
  <tr>
    <td><?= date($DateFormat, strtotime(text($row['date']))); ?></td>
    <td><?php echo text($row['pid']); ?></td>
    <td><?php echo text($row['uid']); ?></td>
    <td><?php echo text($category_title); ?></td>
    <td>
     <?php
      //list off all targets with rule information shown when hover
      foreach ($all_alerts as $targetInfo => $alert) {
       if ( ($row['category'] == 'clinical_reminder_widget') || ($row['category'] == 'active_reminder_popup') ) {
        $rule_title = getListItemTitle("clinical_rules",$alert['rule_id']);
        $catAndTarget = explode(':',$targetInfo);
        $category = $catAndTarget[0];
        $target = $catAndTarget[1];
        echo "<span title='" .attr($rule_title) . "'>" .
             generate_display_field(array('data_type'=>'1','list_id'=>'rule_action_category'),$category) .
             ": " . generate_display_field(array('data_type'=>'1','list_id'=>'rule_action'),$target) .
             " (" . generate_display_field(array('data_type'=>'1','list_id'=>'rule_reminder_due_opt'),$alert['due_status']) . ")" .
             "<span><br>";
       }
       else { // $row['category'] == 'allergy_alert'
         echo $alert . "<br>";
       }
      }
     ?>
    </td>
    <td>
     <?php
     if (!empty($row['new_value'])) {
      //list new targets with rule information shown when hover
      foreach ($new_alerts as $targetInfo => $alert) {
       if ( ($row['category'] == 'clinical_reminder_widget') || ($row['category'] == 'active_reminder_popup') ) {
        $rule_title = getListItemTitle("clinical_rules",$alert['rule_id']);
        $catAndTarget = explode(':',$targetInfo);
        $category = $catAndTarget[0];
        $target = $catAndTarget[1];
        echo "<span title='" .attr($rule_title) . "'>" .
             generate_display_field(array('data_type'=>'1','list_id'=>'rule_action_category'),$category) .
             ": " . generate_display_field(array('data_type'=>'1','list_id'=>'rule_action'),$target) .
             " (" . generate_display_field(array('data_type'=>'1','list_id'=>'rule_reminder_due_opt'),$alert['due_status']) . ")" .
             "<span><br>";
       }
       else { // $row['category'] == 'allergy_alert'
        echo $alert . "<br>";
       }
      }
     }
     else {
      echo "&nbsp;";
     }
     ?>
    </td>
  </tr>

 <?php
 } // $row = sqlFetchArray($res) while
 ?>
 </tbody>
 </table>
 </div>  <!-- end of search results -->

<?php } // end of if search button clicked ?>

</form>

</body>

<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_begin_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_end_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>

</html>

