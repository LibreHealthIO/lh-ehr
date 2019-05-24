<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/headers.inc.php");
formHeader("Nursing Assessment Form");
$returnurl = 'encounter_top.php';
?>
<html>
	<head>
		<?php html_header_show();?>
		<?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1', 'font-awesome']); ?>
		<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
	</head>
<body>
	<form method=post action="<?php echo $rootdir;?>/forms/nursing_assessment/save.php?mode=new" name="my_form">
		<fieldset>
			<input type="text" name="">
			<input type="checkbox" name="">
		</fieldset>

		<fieldset>
			<input type="radio" name="">
			<input type="select" name="">
		</fieldset>

		<div>
			<!-- Save/Cancel buttons -->
			<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
			<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
		</div>
	</form>
</body>
</html>