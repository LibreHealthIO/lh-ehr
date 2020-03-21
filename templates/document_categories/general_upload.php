<html>
<head>
<?php html_header_show();?>


 <style type="text/css" title="mystyles" media="all">
</style>
</head>
<body bgcolor="<?php echo $this->style.BGCOLOR2;?>">
<form method=post enctype="multipart/form-data" action="<?php echo $this->form_action;?>" onsubmit="return top.restoreSession()">
<input type="hidden" name="MAX_FILE_SIZE" value="64000000" />
<table>
	<tr>
		<td>Upload Document</td>
	</tr>
	<tr>
		<td><input type="file" name="file"></td>
	</tr>
	<tr>
		<td><input type="submit" value="Upload"></td>
	</tr>
                    <?php if( !empty($this->file)) {?>
	
	<tr>
		<td><br><br></td>
	</tr>
	<tr>
		<td>Upload Report</td>
	</tr>
	<tr>
		<td><?php echo $this->error;?></td>
	</tr>
	<tr>
		<td><pre><?php echo $this->file;?></pre></td>
	</tr>
                    <?php }?>
</table>
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
</form>
</body>
</html>
