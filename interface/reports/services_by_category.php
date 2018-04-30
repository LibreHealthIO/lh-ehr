<?php
// Copyright (C) 2008-2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once "reports_controllers/ServiceByCategoryController.php";

?>
<html>
<head>
<?php html_header_show(); ?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
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
<title><?php xl('Services by Category','e'); ?></title>

<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script language="JavaScript">
 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });
</script>

</head>

<body class="body_top">

<span class='title'><?php xl('Report','e'); ?> - <?php xl('Services by Category','e'); ?></span>

<form method='post' action='services_by_category.php' name='theform' id='theform'>

<div id="report_parameters">

<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

<table>
 <tr>
  <td width='280px'>
	<div style='float:left'>

	<table class='text'>
		<tr>
			<td>
			   <select name='filter'>
				<option value='0'><?php xl('All','e'); ?></option>
			<?php
			foreach ($code_types as $key => $value) {
			  echo "<option value='" . $value['id'] . "'";
			  if ($value['id'] == $filter) echo " selected";
			  echo ">$key</option>\n";
			}
			?>
			   </select>
			</td>
			<td>
			   <input type='checkbox' name='include_uncat' value='1'<?php if (!empty($_REQUEST['include_uncat'])) echo " checked"; ?> />
			   <?php xl('Include Uncategorized','e'); ?>
			</td>
		</tr>
	</table>

	</div>

  </td>
  <?php   // Show print, submit and export buttons. (TRk)
    showSubmitPrintButtons(); ?>
 </tr>
</table>
</div> <!-- end of parameters -->

<?php
    if ($_POST['form_refresh']) {
?>

<div id="report_results">


<table border='0' cellpadding='1' cellspacing='2' width='98%'>
 <thead style='display:table-header-group'>
  <tr bgcolor="#dddddd">
   <th class='bold'><?php xl('Category'   ,'e'); ?></th>
   <th class='bold'><?php xl('Type'       ,'e'); ?></th>
   <th class='bold'><?php xl('Code'       ,'e'); ?></th>
   <th class='bold'><?php xl('Mod'        ,'e'); ?></th>
   <th class='bold'><?php xl('Units'      ,'e'); ?></th>
   <th class='bold'><?php xl('Description','e'); ?></th>
<?php if (related_codes_are_used()) { ?>
   <th class='bold'><?php xl('Related'    ,'e'); ?></th>
<?php } ?>
<?php
$pres = sqlStatement("SELECT title FROM list_options " .
		     "WHERE list_id = 'pricelevel' ORDER BY seq");
while ($prow = sqlFetchArray($pres)) {
  // Added 5-09 by BM - Translate label if applicable
  echo "   <th class='bold' align='right' nowrap>" . xl_list_label($prow['title']) . "</th>\n";
}
?>
  </tr>
 </thead>
 <tbody>
<?php
 prepareAndShowResults(); // Prepare and show results. (TRK)
?>
 </tbody>
</table>

<?php } // end of submit logic ?>
</div>

</body>
</html>
