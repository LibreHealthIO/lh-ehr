<?php
// Copyright (C) 2010 MMF Systems, Inc>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once "reports_controllers/Edi271Controller.php";

?>
<html>
<head>
<?php html_header_show();?>
<title><?php echo htmlspecialchars( xl('EDI-271 Response File Upload'), ENT_NOQUOTES); ?></title>
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

<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>

<script type="text/javascript">
		function edivalidation(){

			var mypcc = "<?php echo htmlspecialchars( xl('Required Field Missing: Please choose the EDI-271 file to upload'), ENT_QUOTES);?>";

			if(document.getElementById('uploaded').value == ""){
				alert(mypcc);
				return false;
			}
			else
			{
				$("#theform").submit();
			}

		}
</script>

</head>
<body class="body_top">

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
	<?php 	if(isset($message) && !empty($message))
			{
	?>
				<div style="margin-left:25%;width:50%;color:RED;text-align:center;font-family:arial;font-size:15px;background:#ECECEC;border:1px solid;" ><?php echo $message; ?></div>
	<?php
				$message = "";
			}
			if(isset($messageEDI))
			{
	?>
				<div style="margin-left:25%;width:50%;color:RED;text-align:center;font-family:arial;font-size:15px;background:#ECECEC;border:1px solid;" >
					<?php echo htmlspecialchars( xl('Please choose the proper formatted EDI-271 file'), ENT_NOQUOTES); ?>
				</div>
	<?php
					$messageEDI = "";
			}
	?>

<div>

<span class='title'><?php echo htmlspecialchars( xl('EDI-271 File Upload'), ENT_NOQUOTES); ?></span>

<form enctype="multipart/form-data" name="theform" id="theform" action="edi_271.php" method="POST" onsubmit="return top.restoreSession()">

<div id="report_parameters">
	<table>
		<tr>
			<td width='550px'>
				<div style='float:left'>
					<table class='text'>
						<tr>
							<td style='width:125px;' class='label'> <?php echo htmlspecialchars( xl('Select EDI-271 file'), ENT_NOQUOTES); ?>:	</td>
							<td> <input name="uploaded" id="uploaded" type="file" size=37 /></td>
						</tr>
					</table>
				</div>
			</td>
			<td align='left' valign='middle' height="100%">
				<table style='border-left:1px solid; width:80%; height:100%' >
					<tr>
						<td>
							<div style='margin-left:15px'>
								<a href='#' class='css_button cp-misc' onclick='return edivalidation(); '><span><?php echo htmlspecialchars( xl('Upload'), ENT_NOQUOTES); ?></span>
								</a>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>

<input type="hidden" name="form_orderby" value="<?php echo htmlspecialchars( $form_orderby, ENT_QUOTES); ?>" />
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>

</form>
</body>
</html>
