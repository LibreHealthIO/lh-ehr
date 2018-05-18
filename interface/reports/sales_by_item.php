<?php
/**
 * This is a report of sales by item description.
 *
 * Copyright (C) 2015-2017 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
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
 * @package LibreHealth EHR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @author  Terry Hill <teryhill@librehealth.io>
 * @link    http://librehealth.io
 */

require_once "reports_controllers/SalesByItemController.php";

  if ($_POST['form_csvexport']) {
    csvexport('sales_by_item'); // (TRK)
    // CSV headers:
    if ($_POST['form_details']) {
      echo '"Category",';
      echo '"Item",';
      echo '"Date",';
      if($GLOBALS['sales_report_invoice'] == 1 || $GLOBALS['sales_report_invoice'] == 2 ) {
       echo '"Name",';
      }
      if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {
        echo '"Invoice",';
      }
      if($GLOBALS['sales_report_invoice'] == 1) {
         echo '"ID",';
      }
      echo '"Qty",';
      echo '"Amount"' . "\n";
    }
    else {
      echo '"Category",';
      echo '"Item",';
      echo '"Qty",';
      echo '"Total"' . "\n";
    }
  } // end export
  else {
?>
<html>
<head>
<?php html_header_show();?>
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
    #report_results {
       margin-top: 30px;
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

<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../../library/js/report_helper.js"></script>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<title><?php echo xlt('Sales by Item') ?></title>


<script language="JavaScript">
 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });
</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Sales by Item'); ?></span>

<form method='post' action='sales_by_item.php' id='theform'>

<div id="report_parameters">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<table>
 <tr>
  <td width='630px'>
    <div style='float:left'>
    <table class='text'>
        <tr>
            <td class='label'>
                <?php echo xlt('Facility'); ?>:
            </td>
            <td>
            <?php dropdown_facility($form_facility, 'form_facility', true); ?>
            </td>
            <?php // Show From and To dates fields. (TRK)
              showFromAndToDates(); ?>
        </tr>
    </table>
    <table class='text'>
        <tr>
          <td class='label'>
            <?php echo xlt('Provider'); ?>:
          </td>
          <td>
            <?php
                if (acl_check('acct', 'rep_a')) {
                    // Build a drop-down list of providers.
                    dropDownProviders();
                    } else {
                    echo "<input type='hidden' name='form_provider' value='" . attr($_SESSION['authUserID']) . "'>";
                    }
            ?>
            &nbsp;
          </td>
          <td>
               <label><input type='checkbox' name='form_details'<?php  if ($form_details) echo ' checked'; ?>>
               <?php echo xlt('Details'); ?></label>
          </td>
        </tr>
    </table>
  </div>

  </td>
  <?php   // Show print, submit and export buttons. (TRk)
    showSubmitPrintButtons('form_csvexport'); ?>
 </tr>
</table>

</div> <!-- end of parameters -->

<?php
 if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
?>
<div id="report_results">
<table >
 <thead>
  <th>
   <?php echo xlt('Category'); ?>
  </th>
  <th>
   <?php echo xlt('Item'); ?>
  </th>
  <th>
   <?php if ($form_details) echo xlt('Date'); ?>
  </th>
    <?php if($GLOBALS['sales_report_invoice'] == 2) {?>
  <th>
   &nbsp;
  </th>
  <?php } ?>
  <th>
   <?php
   if($GLOBALS['sales_report_invoice'] == 0) {
    if ($form_details) echo ' ';
   ?>
  </th>
  <th>
   <?php
   if ($form_details) echo xlt('Invoice');  }
    if($GLOBALS['sales_report_invoice'] == 1 || $GLOBALS['sales_report_invoice'] == 2 ) {
     if ($form_details) echo xlt('Name');
    } ?>
  </th>
  <th>
   <?php
   if($GLOBALS['sales_report_invoice'] == 2) {
    if ($form_details) echo xlt('Invoice');
   }
   if($GLOBALS['sales_report_invoice'] == 1) {
    if ($form_details) echo xlt('ID');
    }
   ?>
  </th>
  <th align="right">
   <?php echo xlt('Qty'); ?>
  </th>
  <th align="right">
   <?php echo xlt('Amount'); ?>
  </th>
 </thead>
<?php
  } // end not export
}

  if ($_POST['form_refresh'] || $_POST['form_csvexport']) {
    $from_date = $form_from_date . ' 00:00:00';
    $to_date = $form_to_date . ' 23:59:59';
    $category = "";
    $catleft = "";
    $cattotal = 0;
    $catqty = 0;
    $product = "";
    $productleft = "";
    $producttotal = 0;
    $productqty = 0;
    $grandtotal = 0;
    $grandqty = 0;

      $query = prepareQuery(); // Prepare query. (TRK)

      $res = sqlStatement($query,$sqlBindArray);
      while ($row = sqlFetchArray($res)) {
        thisLineItem($row['pid'], $row['encounter'], xl('Products'), $row['name'],
          substr($row['date'], 0, 10), $row['quantity'], $row['fee'], $row['invoice_refno']);
      }

    if ($_POST['form_csvexport']) {
      if (! $_POST['form_details']) {
        echo '"' . display_desc($product) . '",';
        echo '"' . $productqty            . '",';
        echo '"'; bucks($producttotal); echo '"' . "\n";
      }
    }
    else {
?>

 <tr bgcolor="#ddddff">
  <td class="detail">
   <?php echo text(display_desc($catleft)); $catleft = " "; ?>
  </td>
  <td class="detail" colspan="3">
   <?php if ($_POST['form_details']) echo xlt('Total for') . ' '; echo text(display_desc($product)); ?>
  </td>
  <?php if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {?>
  <td>
   &nbsp;
  </td>
  <?php } ?>
  <td align="right">
   &nbsp;
  </td>
  <td align="right">
   <?php echo text($productqty); ?>
  </td>
  <td align="right">
   <?php text(bucks($producttotal)); ?>
  </td>
 </tr>

 <tr bgcolor="#ffdddd">
  <td class="detail">
   &nbsp;
  </td>
  <td class="detail" colspan="3"><b>
   <?php echo xlt('Total for category') . ' '; echo text(display_desc($category)); ?>
  </b></td>
  <?php if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {?>
  <td>
   &nbsp;
  </td>
  <?php } ?>
  <td align="right">
   &nbsp;
  </td>
  <td align="right"><b>
   <?php echo text($catqty); ?>
  </b></td>
  <td align="right"><b>
   <?php text(bucks($cattotal)); ?>
  </b></td>
 </tr>

 <tr>
  <td class="detail" colspan="4"><b>
   <?php echo xlt('Grand Total'); ?>
  </b></td>
  <?php if($GLOBALS['sales_report_invoice'] == 0 || $GLOBALS['sales_report_invoice'] == 2) {?>
  <td>
   &nbsp;
  </td>
  <?php } ?>
  <td align="right">
   &nbsp;
  </td>
  <td align="right"><b>
   <?php echo text($grandqty); ?>
  </b></td>
  <td align="right"><b>
   <?php text(bucks($grandtotal)); ?>
  </b></td>
 </tr>
 <?php $report_from_date = oeFormatShortDate($from_date)  ;
       $report_to_date = oeFormatShortDate($to_date)  ;
 ?>
<div align='right'><span class='title' ><?php echo xlt('Report Date'). ' '; ?><?php echo text($report_from_date);?> - <?php echo text($report_to_date);?></span></div>
<?php

    } // End not csv export
  }

  if (! $_POST['form_csvexport']) {
      if($_POST['form_refresh']){
?>

</table>
</div> <!-- report results -->
<?php } else { ?>
<div class='text'>
    <?php echo xlt('Please input search criteria above, and click Submit to view results.' ); ?>
</div>
<?php } ?>

</form>

</body>

<!-- stuff for the popup calendar -->
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
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
<?php
  } // End not csv export
?>
