<?php
/*
 * Adjustments report
 *
 * This Shows the total for selected adjustment codes over a date range.
 *
 * Copyright (C) 2017-2018 Terry Hill <teryhill@librehealth.io>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 */

 require_once("../globals.php");
 require_once "$srcdir/patient.inc";
 require_once "$srcdir/acl.inc";
 require_once "$srcdir/options.inc.php";
 require_once "$srcdir/formatting.inc.php";
 $DateFormat = DateFormatRead();
 $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);


 $from_date = fixDate($_POST['form_from_date']);
 $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));


if ($_POST['form_csvexport']) {
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=adjustment_codes.csv");
  header("Content-Description: File Transfer");
  // CSV headers:
  if (true) {
    echo '"Adjustment Code",';
    echo '"Total",'. "\n";
  }
}
else {
?>
<html>
<head>
<title><?php echo xlt('Adjustments'); ?></title>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/overlib_mini.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>

<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

</script>

<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">

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

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Adjustments'); ?></span>

<div id="report_parameters_daterange">
    <?php date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_from_date'])))
    . " &nbsp; to &nbsp; ". date("d F Y", strtotime(oeFormatDateForPrintReport($_POST['form_to_date']))); ?>
</div>

<form name='theform' method='post' action='adjustments.php' id='theform'>

<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

<table>
 <tr>
  <td width='100%'>
    <div style='float:left'>

    <table class='text'>
        <tr>
            <td class='label'>
               <?php echo xlt('From'); ?>:
            </td>
            <td>
               <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo $form_from_date ?>'>
            </td>
            <td class='label'>
               <?php echo xlt('To'); ?>:
            </td>
            <td>
               <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo $form_to_date ?>'>
            </td>
               <td class='label'><?php echo xlt('Adjustment Code'); # Adjustment code drop down creation ?>;
            </td>
            <td>
               <?php generate_form_field(array('data_type'=>1,'field_id'=>'adjreason','list_id'=>'adjreason','empty_title'=>'All'),$_POST['form_adjreason']);?>
            </td>

        </tr>
    </table>

    </div>

  </td>
  <td align='left' valign='middle' height="100%">
    <table style='border-left:1px solid; width:80%; height:100%' >
        <tr>
            <td>
                <div style='margin-left:5px'>
                    <a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php echo xlt('Submit'); ?>
                    </span>
                    </a>

                    <?php if ($_POST['form_refresh']) { ?>
                    <a href='#' class='css_button' id='printbutton'>
                        <span>
                            <?php echo xlt('Print'); ?>
                        </span>
                    </a>
                    <a href='#' class='css_button' onclick='$("#form_csvexport").attr("value","true"); $("#theform").submit();'>
                    <span>
                        <?php echo xlt('Export to CSV'); ?>
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

</form>
</div> <!-- end parameters -->

<div id="report_results">
<table>

 <thead>
  <th align='left'> <?php echo xlt('Adjustment Code'); ?> </th>
  <th align='right'> <?php echo xlt('Total'); ?> </th>
 </thead>
 <tbody>
<?php
} // end not export
if ($_POST['form_refresh'] || $_POST['form_csvexport'])
{
  $adj_reason = $_POST['form_adjreason'];
  $from_date = fixDate($_POST['form_from_date']);
  $to_date   = fixDate($_POST['form_to_date'], date('Y-m-d'));

  if ($adj_reason =='') 
  {
      $query = " Select   ar_activity.memo, Sum(ar_activity.adj_amount) As adj_amount " .
    " From ar_activity" .
    " Where ar_activity.post_time >= '$from_date' ".
    " And ar_activity.post_time <= '$to_date' ".
    " Group By ar_activity.memo ";
  } else {

    $query = " Select   ar_activity.memo, Sum(ar_activity.adj_amount) As adj_amount " .
  " From ar_activity" .
  " Where ar_activity.post_time >= '$from_date' ".
  " And ar_activity.post_time <= '$to_date' ".
  " And ar_activity.memo = '$adj_reason' " .
  " Group By ar_activity.memo ";
  }


  $res = sqlStatement($query);
  while ($row = sqlFetchArray($res))
  {
    $adj_name = $row['memo'];
    $total = $row['adj_amount'];


    if ($_POST['form_csvexport'])
    {
        echo '"' . $adj_name                                           . '",';
        echo '"' . oeFormatMoney($total)                               . '"' . "\n";

    } else {
?>
 <tr>
  <td align='left'>
   <?php echo $adj_name ?>
  </td>
  <td align='right'>
   <?php echo oeFormatMoney($total) ?>
  </td>
 </tr>
<?php
         } // end not export
  }
} // end if

if (! $_POST['form_csvexport']) {
?>

</tbody>
</table>
</div> <!-- end of results -->

</body>

<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.datetimepicker.full.min.js"></script>

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
