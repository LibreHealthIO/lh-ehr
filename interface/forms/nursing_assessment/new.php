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
	<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/nursing_assessment/save.php?mode=new" name="my_form">
		<div class="row">
			<!-- assessment section -->
			<div class="col-md-3">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Assessment','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-5" for="ass_comments">Comments/Remarks</label>
					    	<div class="col-sm-5">
					    		<textarea class="form-control" id="ass_comments" name="ass_comments"></textarea>
					    	</div>
						</div>
					</div>
					
				</div>
			</div>
			<!-- Provider notification section -->
			<div class="col-md-3">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Provider Notification','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-5" for="PN_reason">Reason for  Communication</label>
					    	<div class="col-sm-5">
					    		<textarea name="PN_reason" class="form-control" id="PN_reason"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label  class="control-label col-sm-4" for="PN_role">Provider Name/role</label>
					    	<div class="col-sm-6">
					    		<textarea name="PN_role" class="form-control" id="PN_role"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-5" for="PN_method">Method of Communication</label>
					    	<div class="col-sm-5">
					    		<select name="PN_method" class="form-control" id="PN_method">
					    		<option>[select]</option>
							    <option>Call</option>
							    <option>Page</option>
							    <option>Face to Face</option>
							    <option>Other</option>
							</select>
					    	</div>
						</div>
					</div>
					
				</div>
			</div>

			<!-- section for vitals -->
			<div class="col-md-3">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Vitals','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_temperature">Temperature (&#8451;)</label>
					    	<div class="col-sm-6">
					    		<input type="number" name="V_temperature" class="form-control" id="V_temperature">
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="">
								
							</label>

							<div class="col-sm-6">
								
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="col-md-3">
				
			</div>
		</div>

		<div>
			<!-- Save/Cancel buttons -->
			<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
			<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
		</div>
	</form>
</body>
</html>