<?php
include_once("../../globals.php");
?>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>
<body class="body_top">

<span class="title"><?php xl('Prescriptions','e'); ?></span>
<table>
<tr height="20px">
<td>
<a href="<?php echo $GLOBALS['webroot']?>/controller.php?prescription&list&id=<?php echo $pid?>"  target='RxRight' class="css_button" onclick="top.restoreSession()">
<span><?php xl('List', 'e');?></span></a>
<a href="<?php echo $GLOBALS['webroot']?>/controller.php?prescription&edit&id=&pid=<?php echo $pid?>"  target='RxRight' class="css_button" onclick="top.restoreSession()">
<span><?php xl('Add','e');?></span></a>
<a href="../summary/demographics.php" class="css_button" onclick="top.restoreSession()">
<span>Back To Patient</span>
</a>
</td>
</tr>
<tr>
		<td>
			<a class="css_button large_button" href="demographics.php" target="pat" onclick="top.restoreSession()">
				<span class="css_button_span large_button_span"><?php echo xlt('Back to Patient');?></span>
			</a>
		</td>
</tr>
</table>

</body>
</html>
