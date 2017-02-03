

This code is called and used in one place in the code base at:
/interface/patient_file/report/custom_report.php

The function is:
<?php if ($PDF_OUTPUT) { ?>
<link rel="stylesheet" href="<?php echo  $webserver_root . '/interface/themes/style_pdf.css' ?>" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $webserver_root; ?>/library/ESign/css/esign_report.css" />
<?php } else {?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/library/ESign/css/esign_report.css" />
<?php } ?>

This is called in multiple locations in the report.