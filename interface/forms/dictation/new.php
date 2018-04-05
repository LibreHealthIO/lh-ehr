<!-- Form generated from formsWiz -->
<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/headers.inc.php");
formHeader("Form: dictation");
$returnurl = 'encounter_top.php';
?>
<html>
	<head>
		<?php html_header_show();?>
		<?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1']); ?>
		<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
	</head>
	<body class="body_top">
		<span class="title" style="display: none;"><?php echo xlt('Speech Dictation'); ?></span><br><br>
		<h2><?php echo xlt('Speech Dictation'); ?></h2>
		<form method=post action="<?php echo $rootdir;?>/forms/dictation/save.php?mode=new" name="my_form">
			<table class="table table-bordered table-hover table-condensed">
				<tr>
					<td align="left"><?php echo xlt('Dictation:'); ?></td>
					<td width="90%">
						<textarea class="form-control" style="width: auto;" name="dictation" cols="80" rows="24"></textarea>
					</td>
				</tr>
				<tr>
					<td align="left"><?php echo xlt('Additional Notes:'); ?></td>
					<td width="90%">
						<textarea class="form-control" style="width: auto;" name="additional_notes" cols="80" rows="8"></textarea>
					</td>
				</tr>
			</table>
			<div>
				<!-- Save/Cancel buttons -->
				<a role="button" href="javascript:top.restoreSession(); document.my_form.submit();" class="link_submit cp-submit">
					<?php echo xlt('Save'); ?>
				</a>
				<a class="deleter cp-negative" role="button" href="<?php echo "$rootdir/patient_file/encounter/$returnurl";?>" class="link" onclick="top.restoreSession()">
					<?php echo xlt('Don\'t Save'); ?>
				</a>
			</div>
		</form>
<!-- Includes closing body and html tags -->
<?php
formFooter();
?>
