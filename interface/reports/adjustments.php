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

require_once "reports_controllers/AdjustmentsController.php";

if ($_POST['form_csvexport']) {
    csvexport('adjustment_codes'); // CSV headers. (TRK)
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

  <link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/jquery.datetimepicker.css">
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

  <?php reportParametersDaterange(); #TRK ?>

  <form name='theform' method='post' action='adjustments.php' id='theform'>

  <div id="report_parameters">
  <input type='hidden' name='form_refresh' id='form_refresh' value=''/>
  <input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>

  <table>
    <tr>
      <td width='50%'>
        <div style='float:left'>

        <table class='text'>
            <tr>
                <?php // Show From and To dates fields. (TRK)
                  showFromAndToDates(); ?>
                <td class='label'>
                  <?php echo xlt('Adjustment Code'); # Adjustment code drop down creation ?>:
                </td>
                <td>
                  <?php generate_form_field(array('data_type'=>1,'field_id'=>'adjreason','list_id'=>'adjreason','empty_title'=>'All'),$_POST['form_adjreason']);?>
                </td>
            </tr>
        </table>

        </div>

      </td>
      <?php // Show submit, print and export to CSV buttons. (TRK)
        showSubmitPrintButtons('form_csvexport'); ?>
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
  formRefreshOrCsvexport(); // Pulling out php code. (TRK)
} // end if

if (! $_POST['form_csvexport']) {
?>

</tbody>
</table>
</div> <!-- end of results -->

</body>

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
        $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });
</script>
</html>
<?php
} // end not export
?>
