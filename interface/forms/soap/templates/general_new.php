<html>
	<head>
	<?php html_header_show();?>


	<style type="text/css" title="mystyles" media="all">
	td {
		font-size:12pt;
		font-family:helvetica;
	}
	li{
		font-size:11pt;
		font-family:helvetica;
		margin-left: 15px;
	}
	a {
		font-size:11pt;
		font-family:helvetica;
	}
	.title {
		font-family: sans-serif;
		font-size: 12pt;
		font-weight: bold;
		text-decoration: none;
		color: #000000;
	}

	.form_text{
		font-family: sans-serif;
		font-size: 9pt;
		text-decoration: none;
		color: #000000;
	}

	</style>

	</head>
	<body bgcolor="<?php echo $this->style["BGCOLOR2"];?>">
	<p><span class="title"><?php echo  xl('SOAP','e'); ?></span></p>
		<form name="soap" method="post" action="<?php echo $this->form_action;?>/interface/forms/soap/save.php"
		 onsubmit="return top.restoreSession()">
			<table>
				<tr>
					<td align="left"><?php echo  xl('Subjective','e'); ?></td>
					<td width="90%">
						<textarea name="subjective" cols="60" rows="6"><?php echo $this->data->get_subjective();?></textarea>
					</td>
				</tr>
				<tr>
					<td align="left"><?php echo  xl('Objective','e'); ?></td>
					<td width="90%">
						<textarea name="objective" cols="60" rows="6"><?php echo $this->data->get_objective();?></textarea>
					</td>
				</tr>
				<tr>
					<td align="left"><?php echo  xl('Assessment','e'); ?></td>
					<td width="90%">
						<textarea name="assessment" cols="60" rows="6"><?php echo $this->data->get_assessment();?></textarea>
					</td>
				</tr>
				<tr>
					<td align="left"><?php echo  xl('Plan','e'); ?></td>
					<td width="90%">
						<textarea name="plan" cols="60" rows="6"><?php echo $this->data->get_plan();?></textarea>
					</td>
				</tr>
				<tr>
			</table>
			<div>
				<!-- Save/Cancel buttons -->
				<input type="button" id="save" value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</form>
	</body>
</html>
