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
			<div class="col-md-4">
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
			<div class="col-md-4">
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
			<div class="col-md-4">
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
							<label class="control-label col-sm-4" for="pulse">
								Pulse
							</label>

							<div class="col-sm-6">
								<input type="number" name="pulse" class="form-control" id="pulse">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="SpO2">
								SpO2 (%)
							</label>

							<div class="col-sm-6">
								<input type="number" name="SpO2" class="form-control" id="SpO2">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="SpO2">
								FiO2 (%)
							</label>

							<div class="col-sm-6">
								<input type="number" name="SpO2" class="form-control" id="SpO2">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="V_RespiratoryRate">
								Respiratory Rate
							</label>

							<div class="col-sm-6">
								<input type="number" name="V_RespiratoryRate" class="form-control" id="V_RespiratoryRate">
							</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_source">Temp Source</label>
					    	<div class="col-sm-6">
					    		<select name="V_source" class="form-control" id="V_source">
					    		<option>[select]</option>
							    <option>Axillary</option>
							    <option>Tympanic</option>
							    <option>Oral</option>
							    <option>Swan</option>
							    <option>Rectal</option>
							    <option>Esophageal</option>
							    <option>Temporal</option>
							    <option>Foley</option>
							    <option>Other</option>
							</select>
					    	</div>
						</div>
						<div class="form-group ">
					      <label class="control-label col-sm-4"> Room air(yes/no)</label>
					      <div class="col-sm-5"><input type="checkbox" name="remember"></div>
					      
					    </div>

					    <div class="form-group">
					    	<label class="control-label col-sm-4" for="V_deliveryMethod">O2 Delivery Method</label>
					    	<div class="col-sm-6">
					    		<select name="V_deliveryMethod" class="form-control" id="V_deliveryMethod">
					    		<option>[select]</option>
							    <option>Bagging ETT</option>
							    <option>Blowby</option>
							    <option>BPAP Mask</option>
							    <option>BPAP Nasal</option>
							    <option>BPAP Prongs</option>
							    <option>BPAP Vent</option>
							    <option>CPAP Mask</option>
							    <option>CPAP Nasal</option>
							    <option>CPAP Prongs</option>
							    <option>CPAP Ventilator</option>
							    <option>Face Tent</option>
							    <option>HHF</option>
							    <option>Trach-Vent HME</option>
							    <option>Hood</option>
							    <option>Mask, Aerosol</option>
							    <option>Mask, Low Flow</option>
							    <option>Mask, partial rebreathing</option>
							    <option>Mask, simple</option>
							    <option>Mist, Cool</option>
							    <option>Mist, Cool- Room Air</option>
							    <option>Mist, warm</option>
							    <option>Nasal Cannula</option>
							    <option>Ocillator</option>
							    <option>RAM Cannula</option>
							    <option>Trach Collar</option>
							    <option>Ventilator</option>
							    <option>Other(see comment)</option>
							</select>
					    	</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-4" for="V_flowRate">
								O2 flow rate(LPM)
							</label>

							<div class="col-sm-6">
								<input type="number" name="V_flowRate" class="form-control" id="V_flowRate">
							</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bp">Blood Pressure</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="V_bp" name="V_bp"></textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bpMean">BP mean</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="V_bpMean" name="V_bpMean"></textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bpLocation">Bp location</label>
					    	<div class="col-sm-6">
					    		<select name="V_bpLocation" class="form-control" id="V_bpLocation">
					    		<option>[select]</option>
							    <option>RUE</option>
							    <option>LUE</option>
							    <option>RLE</option>
							    <option>LLE</option>
							    <option>Other (comment)</option>
							</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bpPosition">Bp patient Position</label>
					    	<div class="col-sm-6">
					    		<select name="V_bpPosition" class="form-control" id="V_bpPosition">
					    		<option>[select]</option>
							    <option>Lying</option>
							    <option>Standing</option>
							    <option>Sitting</option>
							</select>
					    	</div>
						</div>
					</div> <!-- end of panel body vitals-->
					
				</div>
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