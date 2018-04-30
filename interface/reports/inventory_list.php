<?php
 // Copyright (C) 2008-2010 Rod Roark <rod@sunsetsystems.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

require_once "reports_controllers/InventoryListController.php";

?>
<html>

<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<title><?php  xl('Inventory List','e'); ?></title>

<style>
  /* specifically include & exclude from printing */
  @media print {
    #report_parameters {visibility: hidden; display: none;}
    #report_parameters_daterange {visibility: visible; display: inline;}
    #report_results {margin-top: 30px;}
  }
  /* specifically exclude some from the screen */
  @media screen {
     #report_parameters_daterange {visibility: hidden; display: none;}
  }

  body { font-family:sans-serif; font-size:10pt; font-weight:normal }

  tr.head   { font-size:10pt; background-color:#cccccc; text-align:center; }
  tr.detail { font-size:10pt; }
  a, a:visited, a:hover { color:#0000cc; }
</style>

<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>

<script language="JavaScript">

  $(document).ready(function() {
    var win = top.printLogSetup ? top : opener.top;
    win.printLogSetup(document.getElementById('printbutton'));
  });

  function mysubmit(action) {
    var f = document.forms[0];
    f.form_action.value = action;
    top.restoreSession();
    f.submit();
  }

</script>

</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class='body_top'>

<center>

<h2><?php echo htmlspecialchars(xl('Inventory List'))?></h2>

<form method='post' action='inventory_list.php' name='theform'>

<div id="report_parameters">
<!-- form_action is set to "submit" at form submit time -->
<input type='hidden' name='form_action' value='' />
<table>
 <tr>
  <td width='50%'>
   <table class='text'>
    <tr>
     <td nowrap>
      <?php echo htmlspecialchars(xl('For the past')); ?>
      <input type="input" name="form_days" size='3' value="<?php echo $form_days; ?>" />
      <?php echo htmlspecialchars(xl('days')); ?>
     </td>
    </tr>
   </table>
  </td>
  <td align='left' valign='middle'>
   <table style='border-left:1px solid; width:100%; height:100%'>
    <tr>
     <td valign='middle'>
      <a href='#' class='css_button cp-submit' onclick='mysubmit("submit")' style='margin-left:1em'>
       <span><?php echo htmlspecialchars(xl('Submit'), ENT_NOQUOTES); ?></span>
      </a>
<?php if ($form_action) { ?>
      <a href='#' class='css_button cp-ouput' id='printbutton' style='margin-left:1em'>
       <span><?php echo htmlspecialchars(xl('Print'), ENT_NOQUOTES); ?></span>
      </a>
<?php } ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</div>

<?php if ($form_action) { // if submit ?>

<div id="report_results">
<table border='0' cellpadding='1' cellspacing='2' width='98%'>
 <thead style='display:table-header-group'>
  <tr class='head'>
   <th><?php  xl('Name','e'); ?></th>
   <th><?php  xl('NDC','e'); ?></th>
   <th><?php  xl('Form','e'); ?></th>
   <th align='right'><?php echo htmlspecialchars(xl('QOH')); ?></th>
   <th align='right'><?php echo htmlspecialchars(xl('Reorder')); ?></th>
   <th align='right'><?php echo htmlspecialchars(xl('Avg Monthly')); ?></th>
   <th align='right'><?php echo htmlspecialchars(xl('Stock Months')); ?></th>
   <th><?php echo htmlspecialchars(xl('Warnings')); ?></th>
  </tr>
 </thead>
 <tbody>
<?php
$encount = 0;
  prepareAndShowResults(); // prepare and show results. (TRK)
?>
 </tbody>
</table>

<?php } // end if submit ?>

</form>
</center>
</body>
</html>
