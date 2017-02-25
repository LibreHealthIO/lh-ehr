<?php
/*
 * Destroy Lot
 *
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io> 
 * Copyright (C) 2006 Rod Roark <rod@sunsetsystems.com>
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

 $sanitize_all_escapes  = true;
 $fake_register_globals = false;

 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 require_once("drugs.inc.php");
 require_once("$srcdir/formdata.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");
require_once($GLOBALS['srcdir']."/formatting.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

 function QuotedOrNull($fld) {
  if ($fld) return "'".add_escape_custom($fld)."'";
  return "NULL";
 }

 $drug_id = $_REQUEST['drug'];
 $lot_id  = $_REQUEST['lot'];
 $info_msg = "";

 if (!acl_check('admin', 'drugs')) die(xlt('Not authorized'));
 if (!$drug_id) die(xlt('Drug ID missing!'));
 if (!$lot_id ) die(xlt('Lot ID missing!'));
?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo xlt('Destroy Lot') ?></title>
<link rel="stylesheet" href='<?php  echo $css_header ?>' type='text/css'>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<style>
td { font-size:10pt; }
</style>
<script type="text/javascript" src="<?php echo $webroot ?>/interface/main/tabs/js/include_opener.js"></script>

<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>

</head>

<body class="body_top">
<?php
 // If we are saving, then save and close the window.
 //
 if ($_POST['form_save']) {
  sqlStatement("UPDATE drug_inventory SET " .
   "destroy_date = "     . QuotedOrNull($_POST['form_date']) . ", "  .
   "destroy_method = '"  . add_escape_custom($_POST['form_method'])    . "', " .
   "destroy_witness = '" . add_escape_custom($_POST['form_witness'])   . "', " .
   "destroy_notes = '"   . add_escape_custom($_POST['form_notes'])     . "' "  .
   "WHERE drug_id = ? AND inventory_id = ?", array($drug_id,$lot_id) );

  // Close this window and redisplay the updated list of drugs.
  //
  echo "<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('".addslashes($info_msg)."');\n";
  echo " window.close();\n";
  echo " if (opener.refreshme) opener.refreshme();\n";
  echo "</script></body></html>\n";
  exit();
 }

 $row = sqlQuery("SELECT * FROM drug_inventory WHERE drug_id = ? " .
  "AND inventory_id = ?", array($drug_id,$lot_id));
?>

<form method='post' name='theform' action='destroy_lot.php?drug=<?php echo attr($drug_id) ?>&lot=<?php echo attr($lot_id) ?>'>
<center>

<table border='0' width='100%'>

 <tr>
  <td valign='top' width='1%' nowrap><b><?php echo xlt('Lot Number'); ?>:</b></td>
  <td>
   <?php echo text($row['lot_number']) ?>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Manufacturer'); ?>:</b></td>
  <td>
   <?php echo text($row['manufacturer']) ?>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Quantity On Hand'); ?>:</b></td>
  <td>
   <?php echo text($row['on_hand']) ?>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Expiration Date'); ?>:</b></td>
  <td>
   <?php echo text($row['expiration']) ?>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Date Destroyed'); ?>:</b></td>
  <td>
   <input type='text' size='10' name='form_date' id='form_date'
    value='<?php echo $row['destroy_date'] ? htmlspecialchars(oeFormatShortDate(attr($row['destroy_date']))) : htmlspecialchars(oeFormatShortDate(date("Y-m-d"))); ?>'/>
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Method of Destruction'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_method' maxlength='250'
    value='<?php echo text($row['destroy_method']) ?>' style='width:100%' />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Witness'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_witness' maxlength='250'
    value='<?php echo text($row['destroy_witness']) ?>' style='width:100%' />
  </td>
 </tr>

 <tr>
  <td valign='top' nowrap><b><?php echo xlt('Notes'); ?>:</b></td>
  <td>
   <input type='text' size='40' name='form_notes' maxlength='250'
    value='<?php echo text($row['destroy_notes']) ?>' style='width:100%' />
  </td>
 </tr>

</table>

<p>
<input type='submit' name='form_save' value='<?php echo xla('Submit') ;?>' />

&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />
</p>

</center>
</form>
<script>
 $(function() {
     $("#form_date").datetimepicker({
      timepicker: false,
      format: "<?= $DateFormat; ?>"
     });
  $.datetimepicker.setLocale('<?= $DateLocale;?>');
 });
</script>
</body>
</html>
