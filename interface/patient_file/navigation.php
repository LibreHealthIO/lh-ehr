<?php
 include_once("../globals.php");
 include_once("$srcdir/acl.inc");

 $ie_auth = ((acl_check('encounters','notes','','write') ||
              acl_check('encounters','notes_a','','write')) &&
             acl_check('patients','med','','write'));
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('Navigation'); ?></title>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="../../library/dialog.js"></script>
<script language="JavaScript">
// This is invoked to pop up some window when a popup item is selected.
function selpopup(selobj) {
 var i = selobj.selectedIndex;
 var opt = selobj.options[i];
 if (i > 0) {
  var width  = 750;
  var height = 550;
  if (opt.text == 'Export' || opt.text == 'Import') {
   width  = 500;
   height = 400;
  }
  else if (opt.text == 'Refer') {
   width  = 700;
   height = 500;
  }
  dlgopen(opt.value, '_blank', width, height);
 }
 selobj.selectedIndex = 0;
}
</script>
</head>

<body class="body_nav">

<div id="nav_topmenu">
<form>
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
 <tr>
  <td align="center" valign="middle">
   <a href="javascript:top.restoreSession();parent.Title.location.href='<?php echo $rootdir;?>/patient_file/summary/summary_title.php';parent.Main.location.href='<?php echo $rootdir;?>/patient_file/summary/patient_summary.php'" target="Main" class="menu"><?php echo xlt('Summary'); ?></a>
  </td>
  <td align="center" valign="middle">
   <a href="javascript:top.restoreSession();parent.Title.location.href='<?php echo $rootdir;?>/patient_file/history/history_title.php';parent.Main.location.href='<?php echo $rootdir;?>/patient_file/history/patient_history.php'" target="Main" class="menu"><?php echo xlt('History'); ?></a>
  </td>
  <td align="center" valign="middle">
   <a href="javascript:top.restoreSession();parent.Title.location.href='<?php echo $rootdir;?>/patient_file/encounter/encounter_title.php';parent.Main.location.href='<?php echo $rootdir;?>/patient_file/encounter/patient_encounter.php?mode=new'" target="Main" class="menu"><?php echo xlt('Encounter'); ?></a>
  </td>
  <td align="center" valign="middle">
   <a href="javascript:top.restoreSession();parent.Title.location.href='<?php echo $rootdir;?>/patient_file/transaction/transaction_title.php';parent.Main.location.href='<?php echo $rootdir;?>/patient_file/transaction/patient_transaction.php'" target="Main" class="menu"><?php echo xlt('Transaction'); ?></a>
  </td>
  <td align="center" valign="middle">
   <a href="<?php echo $GLOBALS['web_root'];?>/controller.php?document&list&patient_id=<?php echo attr($pid); ?>"
    target="Main" class="menu" onclick="top.restoreSession()"><?php echo xlt('Documents'); ?></a>
  </td>
  <td align="center" valign="middle">
   <a href="javascript:top.restoreSession();parent.Title.location.href='<?php echo $rootdir;?>/patient_file/report/report_title.php';parent.Main.location.href='<?php echo $rootdir;?>/patient_file/report/patient_report.php'" target="Main" class="menu"><?php echo xlt('Report'); ?></a>
  </td>
  <td align="center" align="right" valign="middle">
   <a href="../main/main_screen.php" target="_top" class="logout" onclick="top.restoreSession()"><?php echo xlt('Close'); ?></a>&nbsp;&nbsp;
  </td>
  <td align="right" valign="middle">
    <select onchange='selpopup(this)'>
     <option value=''><?php echo xlt('Popups'); ?></option>
<?php if ($ie_auth) { ?>
     <option value='problem_encounter.php'><?php echo xlt('Issues'); ?></option>
<?php } ?>
     <option value='../../custom/export_xml.php'><?php echo xlt('Export'); ?></option>
     <option value='../../custom/import_xml.php'><?php echo xlt('Import'); ?></option>
     <option value='../reports/appointments_report.php?patient=<?php echo attr($pid); ?>'><?php echo xlt('Appts'); ?></option>
<?php if (file_exists("$webserver_root/custom/refer.php")) { ?>
     <option value='../../custom/refer.php'><?php echo xlt('Refer'); ?></option>
<?php } ?>
<?php if (file_exists("$webserver_root/custom/fee_sheet_codes.php")) { ?>
 <option value='printed_fee_sheet.php'><?php echo xlt('Superbill'); ?></option>
<?php } ?>
<?php if ($GLOBALS['inhouse_pharmacy']) { ?>
     <option value='front_payment.php'><?php echo xlt('Prepay'); ?></option>
     <option value='pos_checkout.php'><?php echo xlt('Checkout'); ?></option>
<?php } else { ?>
     <option value='front_payment.php'><?php echo xlt('Payment'); ?></option>
<?php } ?>
     <option value='letter.php'><?php echo xlt('Letter'); ?></option>
    </select>
  </td>
 </tr>
</table>
</form>
</div>

</body>
</html>
