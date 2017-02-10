<?php
/*
 * Patient List By Referrer report
 * This report lists patients that were seen within a given date
 * range, or all patients if no date range is entered, by the
 * referring provider selected.
 *
 * Copyright (C) 2017 Terry Hill <teryhill@librehealth.io> 
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package LibreEHR
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link http://librehealth.io
 */


 require_once("../globals.php");
 require_once("$srcdir/patient.inc");
 require_once("$srcdir/formatting.inc.php");
 require_once("$srcdir/options.inc.php");

 $DateFormat = DateFormatRead();
 $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 
// Prepare a string for CSV export.
function qescape($str) {
  $str = str_replace('\\', '\\\\', $str);
  return str_replace('"', '\\"', $str);
}

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
  header("Content-Disposition: attachment; filename=patient_list_by_referrer.csv");
  header("Content-Description: File Transfer");
}
else {
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('Patient List By Referrer'); ?></title>
<!--<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>-->
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js"></script>

<script language="JavaScript">

$(document).ready(function() {
  oeFixedHeaderSetup(document.getElementById('mymaintable'));
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

<!-- Required for the popup date selectors 
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>-->

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Patient List By Referrer'); ?></span>

<div id="report_parameters_daterange">
<?php echo date("d F Y", strtotime($form_from_date)) ." &nbsp; to &nbsp; ". date("d F Y", strtotime($form_to_date)); ?>
</div>

<form name='theform' id='theform' method='post' action='patient_list_by_referrer.php'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
 <tr>
  <td width='60%'>
    <div style='float:left'>

    <table class='text'>
      <tr>
        <td>
            <span class=text><?php echo xlt('Referring Provider'); ?>: </span>
            <td>
              <?php  # Build a drop-down list of providers. # Added (TLH)
                 genProviderSelect('form_provider', '-- '.xl("All").' --',$_POST['form_provider'], false, true);
              ?>
            </td>&nbsp;&nbsp;
        </td>
        <td class='label'>
          <?php echo xlt('Visits From'); ?>:
        </td>
        <td>
          <input type='text' name='form_from_date' id="form_from_date" size='10'
                 value='<?php echo htmlspecialchars(oeFormatShortDate(attr($form_from_date))) ?>' />
        </td>
        <td class='label'>
          <?php echo xlt('To'); ?>:
        </td>
        <td>
          <input type='text' name='form_to_date' id="form_to_date" size='10'
                 value='<?php echo htmlspecialchars(oeFormatShortDate(attr($form_to_date))) ?>' />
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
                    <a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php echo xlt('Submit'); ?>
                    </span>
                    </a>
                    <a href='#' class='css_button' onclick='$("#form_csvexport").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php echo xlt('Export to CSV'); ?>
                    </span>
                    </a>
                    <?php if ($_POST['form_refresh']) { ?>
                    <a href='#' id='printbutton' class='css_button'>
                        <span>
                            <?php echo xlt('Print'); ?>
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
    echo '"' . xl('Last') . '",';
    echo '"' . xl('First') . '",';
    echo '"' . xl('Middle') . '",';
    echo '"' . xl('DOB') . '",';
    echo '"' . xl('SS') . '",';
    echo '"' . xl('Street') . '",';
    echo '"' . xl('City') . '",';
    echo '"' . xl('State') . '",';
    echo '"' . xl('Zip') . '",';
    echo '"' . xl('Home Phone') . '",';
    echo '"' . xl('Work Phone') . '",';
    echo '"' . xl('Ins 1') . '",';
    echo '"' . xl('Addr 1') . '",';
    echo '"' . xl('City 1') . '",';
    echo '"' . xl('ST 1') . '",';
    echo '"' . xl('Zip 1') . '",';
    echo '"' . xl('Ins 2') . '",';
    echo '"' . xl('Addr 2') . '",';
    echo '"' . xl('City 2') . '",';
    echo '"' . xl('ST 2') . '",';
    echo '"' . xl('Zip 2') . '"' . "\n";
  }
  else {
?>

<div id="report_results">
<table id='mymaintable'>
 <thead>
  <th> <?php echo xlt('Patient'); ?> </th>
  <th> <?php echo xlt('DOB'); ?> </th>
  <th> <?php echo xlt('SS'); ?> </th>
  <th> <?php echo xlt('Street'); ?> </th>
  <th> <?php echo xlt('City'); ?> </th>
  <th> <?php echo xlt('State'); ?> </th>
  <th> <?php echo xlt('Zip'); ?> </th>
  <th> <?php echo xlt('Home Phone'); ?> </th>
  <th> <?php echo xlt('Work Phone'); ?> </th>
  <th> <?php echo xlt('Insurance 1'); ?> </th>
  <th> <?php echo xlt('Insurance 2'); ?> </th>
 </thead>
 <tbody>
<?php
  } // end not export
  $totalpts = 0;

  if ($form_provider) {
   if ($where) $where .= " AND ";
    $where .= "p.ref_providerid = '$form_provider' ";
  }
    
  if (! $where) {
   $where = "1 = 1";
  }
  
  $query = "SELECT " .
   "p.fname, p.mname, p.lname, p.street, p.city, p.state, p.DOB, p.ss, " .
   "p.postal_code, p.phone_home, p.phone_biz, p.pid, p.pubpid, p.ref_providerID, " .
   "count(e.date) AS ecount, max(e.date) AS edate, " .
   "i1.date AS idate1, i2.date AS idate2, a1.line1 AS aline1, a1.city AS acity1, a1.zip AS azip1, a1.state AS astate1, " .
   "c1.name AS cname1, c2.name AS cname2, a2.line1 AS aline2, a2.city AS acity2, a2.zip AS azip2, a2.state AS astate2 " .
   "FROM patient_data AS p ";
  if (!empty($from_date)) {
   $query .= "JOIN form_encounter AS e ON " .
   "e.pid = p.pid AND " .
   "e.date >= '$from_date 00:00:00' AND " .
   "e.date <= '$to_date 23:59:59' ";
  }
  else {
    $query .= "LEFT OUTER JOIN form_encounter AS e ON " .
    "e.pid = p.pid ";
  }
  $query .=
   "LEFT OUTER JOIN insurance_data AS i1 ON " .
   "i1.pid = p.pid AND i1.type = 'primary' " .
   "LEFT OUTER JOIN insurance_companies AS c1 ON " .
   "c1.id = i1.provider " .
   "LEFT OUTER JOIN addresses AS a1 ON " .
   "c1.id = a1.foreign_id " .
   "LEFT OUTER JOIN insurance_data AS i2 ON " .
   "i2.pid = p.pid AND i2.type = 'secondary' " .
   "LEFT OUTER JOIN insurance_companies AS c2 ON " .
   "c2.id = i2.provider " .
   "LEFT OUTER JOIN addresses AS a2 ON " .
   "c2.id = a2.foreign_id " .
   "WHERE $where " .
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
    echo '"' . qescape($row['lname']) . '",';
    echo '"' . qescape($row['fname']) . '",';
    echo '"' . qescape($row['mname']) . '",';
    echo '"' . qescape($row['DOB']) . '",';
    echo '"' . qescape($row['ss']) . '",';
    echo '"' . qescape($row['street']) . '",';
    echo '"' . qescape($row['city']) . '",';
    echo '"' . qescape($row['state']) . '",';
    echo '"' . qescape($row['postal_code']) . '",';
    echo '"' . qescape($row['phone_home']) . '",';
    echo '"' . qescape($row['phone_biz']) . '",';
    echo '"' . qescape($row['cname1']) . '",';
    echo '"' . qescape($row['aline1']) . '",';
    echo '"' . qescape($row['acity1']) . '",';
    echo '"' . qescape($row['astate1']) . '",';
    echo '"' . qescape($row['azip1']) . '",';
    echo '"' . qescape($row['cname2']) . '",';
    echo '"' . qescape($row['aline2']) . '",';
    echo '"' . qescape($row['acity2']) . '",';
    echo '"' . qescape($row['astate2']) . '",';
    echo '"' . qescape($row['azip2']) . '"' . "\n";
   }
   else {
?>
 <tr>
  <td>
    <?php echo htmlspecialchars( $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'] ) ?>
  </td>
  <td>
   <?php echo $row['DOB'] ?>
  </td>
  <td>
   <?php echo $row['ss'] ?>
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
   <?php echo $row['phone_home'] ?>
  </td>
  <td>
   <?php echo $row['phone_biz'] ?>
  </td>
  <td>
   <?php if(strlen($row['cname1']) > 0 ){ echo $row['cname1']. " , " . $row['aline1']. " , " . $row['acity1']. " " .$row['astate1']. " , " .$row['azip1'] ;} ?>
  </td>
  <td>
   <?php if(strlen($row['cname2']) > 0 ){ echo $row['cname2']. " , " . $row['aline2']. " , " . $row['acity2']. " " .$row['astate2']. " , " .$row['azip2'] ;} ?>
  </td>
 </tr>
<?php
   } // end not export
   ++$totalpts;
  } // end while
  if (!$_POST['form_csvexport']) {
?>

 <tr class="report_totals">
  <td colspan='11'>
   <?php echo xlt('Total Number of Patients'); ?>
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
     <?php echo xlt('Please input search criteria above, and click Submit to view results.' ); ?>
</div>
<?php
}

if (!$_POST['form_csvexport']) {
?>

</form>
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
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>
</html>
<?php
} // end not export
?>
