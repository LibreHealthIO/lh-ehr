<?php
/*
 * Patient List report
 *
 * This report lists patients that were seen within a given date
 * range, or all patients if no date range is entered.
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
 * @author Rod Roark <rod@sunsetsystems.com>
 * @link http://librehealth.io 
 */

 require_once("../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/formatting.inc.php");
  require_once("$srcdir/options.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

// Prepare a string for CSV export.
function qescape($str)
{
  $str = str_replace('\\', '\\\\', $str);
  return str_replace('"', '\\"', $str);
}

 // $from_date = fixDate($_POST['form_from_date'], date('Y-01-01'));
 // $to_date   = fixDate($_POST['form_to_date'], date('Y-12-31'));
 $from_date = fixDate($_POST['form_from_date'], '');
 $to_date   = fixDate($_POST['form_to_date'], '');
 if (empty($to_date) && !empty($from_date)) $to_date = date('Y-12-31');
 if (empty($from_date) && !empty($to_date)) $from_date = date('Y-01-01');

$form_provider = empty($_POST['form_provider']) ? 0 : intval($_POST['form_provider']);

// In the case of CSV export only, a download will be forced.
if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=patient_list.csv");
  header("Content-Description: File Transfer");
}
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php xl('Patient List','e'); ?></title>
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script> <!-- Including jquery plugin to handle uncaught ReferenceError $  -->
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<script language="JavaScript">
	$(document).ready(function() {
 		top.printLogSetup(document.getElementById('printbutton'));
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

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Patient List','e'); ?></span>

<div id="report_parameters_daterange">
    <?php date("d F Y", strtotime(oeFormatDateForPrintReport($form_from_date)))
    . " &nbsp; to &nbsp; ". date("d F Y", strtotime(oeFormatDateForPrintReport($form_to_date))); ?>
</div>

<form name='theform' id='theform' method='post' action='patient_list.php'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
 <tr>
  <td width='60%'>
    <div style='float:left'>

    <table class='text'>
        <tr>
      <td class='label'>
        <?php xl('Provider','e'); ?>:
      </td>
      <td>
          <?php
         generate_form_field(array('data_type' => 10, 'field_id' => 'provider',
           'empty_title' => '-- All --'), $_POST['form_provider']);
          ?>
      </td>
            <td class='label'>
               <?php xl('Visits From','e'); ?>:
            </td>
            <td>
                                    <input type='text' name='form_from_date' id="form_from_date"
                                           size='10' value='<?= $form_from_date; ?>' title='yyyy-mm-dd'>
            </td>
            <td class='label'>
               <?php xl('To','e'); ?>:
            </td>
            <td>
                                    <input type='text' name='form_to_date' id="form_to_date" size='10'
                                           value='<?= $form_to_date; ?>' title='yyyy-mm-dd'>
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
                                    <a href='#' class='css_button'
                                       onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php xl('Submit','e'); ?>
                    </span>
                    </a>
                                    <a href='#' class='css_button'
                                       onclick='$("#form_csvexport").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php xl('Export to CSV','e'); ?>
                    </span>
                    </a>
                    <?php if ($_POST['form_refresh']) { ?>
                    <a href='#' id='printbutton' class='css_button'>
                        <span>
                            <?php xl('Print','e'); ?>
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
} // end not form_csvexport

if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
  if ($_POST['form_csvexport']) {
    // CSV headers:
    echo '"' . xl('Last Visit') . '",';
    echo '"' . xl('First') . '",';
    echo '"' . xl('Last') . '",';
    echo '"' . xl('Middle') . '",';
    echo '"' . xl('ID') . '",';
    echo '"' . xl('Street') . '",';
    echo '"' . xl('City') . '",';
    echo '"' . xl('State') . '",';
    echo '"' . xl('Zip') . '",';
    echo '"' . xl('Home Phone') . '",';
    echo '"' . xl('Work Phone') . '"' . "\n";
  }
  else {
?>

<div id="report_results">
<table>
 <thead>
  <th> <?php xl('Last Visit','e'); ?> </th>
  <th> <?php xl('Patient','e'); ?> </th>
  <th> <?php xl('ID','e'); ?> </th>
  <th> <?php xl('Street','e'); ?> </th>
  <th> <?php xl('City','e'); ?> </th>
  <th> <?php xl('State','e'); ?> </th>
  <th> <?php xl('Zip','e'); ?> </th>
  <th> <?php xl('Primary Insurance','e'); ?> </th>
  <th> <?php xl('Secondary Insurance','e'); ?> </th>
 </thead>
 <tbody>
<?php
  } // end not export
  $totalpts = 0;
  $query = "SELECT " .
   "p.fname, p.mname, p.lname, p.street, p.city, p.state, " .
   "p.postal_code, p.phone_home, p.phone_biz, p.pid, " .
   "count(e.date) AS ecount, max(e.date) AS edate, " .
   "i1.date AS idate1, i2.date AS idate2, " .
   "c1.name AS cname1, c2.name AS cname2 " .
   "FROM patient_data AS p ";
  if (!empty($from_date)) {
   $query .= "JOIN form_encounter AS e ON " .
   "e.pid = p.pid AND " .
   "e.date >= '$from_date 00:00:00' AND " .
   "e.date <= '$to_date 23:59:59' ";
   if ($form_provider) {
    $query .= "AND e.provider_id = '$form_provider' ";
   }
  }
  else {
   if ($form_provider) {
    $query .= "JOIN form_encounter AS e ON " .
    "e.pid = p.pid AND e.provider_id = '$form_provider' ";
   }
   else {
    $query .= "LEFT OUTER JOIN form_encounter AS e ON " .
    "e.pid = p.pid ";
   }
  }
  $query .=
   "LEFT OUTER JOIN insurance_data AS i1 ON " .
   "i1.pid = p.pid AND i1.type = 'primary' " .
   "LEFT OUTER JOIN insurance_companies AS c1 ON " .
   "c1.id = i1.provider " .
   "LEFT OUTER JOIN insurance_data AS i2 ON " .
   "i2.pid = p.pid AND i2.type = 'secondary' " .
   "LEFT OUTER JOIN insurance_companies AS c2 ON " .
   "c2.id = i2.provider " .
   "GROUP BY p.lname, p.fname, p.mname, p.pid, i1.date, i2.date " .
   "ORDER BY p.lname, p.fname, p.mname, p.pid, i1.date DESC, i2.date DESC";
  $res = sqlStatement($query);

  $prevpid = 0;
  while ($row = sqlFetchArray($res)) {
   if ($row['pid'] == $prevpid) continue;
   $prevpid = $row['pid'];
   $age = '';
   if ($row['DOB']) {
    $dob = $row['DOB'];
    $tdy = $row['edate'] ? $row['edate'] : date('Y-m-d');
    $ageInMonths = (substr($tdy,0,4)*12) + substr($tdy,5,2) -
                   (substr($dob,0,4)*12) - substr($dob,5,2);
    $dayDiff = substr($tdy,8,2) - substr($dob,8,2);
    if ($dayDiff < 0) --$ageInMonths;
    $age = intval($ageInMonths/12);
   }

   if ($_POST['form_csvexport']) {
    echo '"' . oeFormatShortDate(substr($row['edate'], 0, 10)) . '",';
    echo '"' . qescape($row['lname']) . '",';
    echo '"' . qescape($row['fname']) . '",';
    echo '"' . qescape($row['mname']) . '",';
    echo '"' . qescape($row['pid']) . '",';
    echo '"' . qescape($row['street']) . '",';
    echo '"' . qescape($row['city']) . '",';
    echo '"' . qescape($row['state']) . '",';
    echo '"' . qescape($row['postal_code']) . '",';
    echo '"' . qescape($row['cname1']) . '",';
    echo '"' . qescape($row['cname2']) . '"' . "\n";
   }
   else {
?>
 <tr>
  <td>
   <?php echo oeFormatShortDate(substr($row['edate'], 0, 10)) ?>
  </td>
  <td>
    <?php echo htmlspecialchars( $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] ) ?>
  </td>
  <td>
   <?php echo $row['pid'] ?>
  </td>
  <td>
   <?php echo $row['street'] ?>
  </td>
  <td>
   <?php echo $row['city'] ?>
  </td>
  <td>
   <?php echo $row['state'] ?>
  </td>
  <td>
   <?php echo $row['postal_code'] ?>
  </td>
  <td>
   <?php echo $row['cname1'] ?>
  </td>
  <td>
   <?php echo $row['cname2'] ?>
  </td>
 </tr>
<?php
   } // end not export
   ++$totalpts;
  } // end while
  if (!$_POST['form_csvexport']) {
?>

 <tr class="report_totals">
  <td colspan='9'>
   <?php xl('Total Number of Patients','e'); ?>
   :
   <?php echo $totalpts ?>
  </td>
 </tr>

</tbody>
</table>
</div> <!-- end of results -->
<?php
  } // end not export
} // end if refresh or export

if (!$_POST['form_refresh'] && !$_POST['form_csvexport']) {
?>
<div class='text'>
    <?php echo xl('Please input search criteria above, and click Submit to view results.', 'e' ); ?>
</div>
<?php
}

if (!$_POST['form_csvexport']) {
?>

</form>
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
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>
</html>
<?php
} // end not export
?>
