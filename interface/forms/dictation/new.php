<!-- Form generated from formsWiz -->
<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
require_once("$srcdir/headers.inc.php");
//Include Bootstrap
call_required_libraries($bootstrap=true,$fancybox=false,$knockout=false,$datepicker=false);
formHeader("Form: dictation");
$returnurl = 'encounter_top.php';
?>
<html><head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<style>
   textarea
   {
       margin-bottom:16px;
   }
</style>
</head>
<body class="body_top">
<form method=post action="<?php echo $rootdir;?>/forms/dictation/save.php?mode=new" name="my_form">
<span class="title"><?php echo xlt('Speech Dictation'); ?></span><br><br>
<span class=text><?php echo xlt('Dictation: '); ?></span><br><textarea cols=80 rows=24 wrap=virtual name="dictation" ></textarea><br>
<span class=text><?php echo xlt('Additional Notes:'); ?> </span><br><textarea cols=80 rows=8 wrap=virtual name="additional_notes" ></textarea><br>
<br>
<a href="javascript:top.restoreSession();document.my_form.submit();" class="btn btn-primary"><?php echo xlt('Save'); ?></a>

<a href="<?php echo "$rootdir/patient_file/encounter/$returnurl";?>" class="btn btn-primary"
 onclick="top.restoreSession()"><?php echo xlt('Don\'t Save'); ?></a>
</form>
<?php
formFooter();
?>
