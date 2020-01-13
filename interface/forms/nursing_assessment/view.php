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
		<style>
			.col-sm-6 ,.col-md-3{
				padding-left: 3px;
				padding-right: 3px;
			}
			input:focus, textarea:focus, select:focus {
				border-color: #ca1278 !important;
				border-width: 2px !important;
			}
		</style>
	</head>
<body>
	<?php
	include_once("$srcdir/api.inc");
	$obj = formFetch("form_nursing_assessment", $_GET["id"]);
	?>
	<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/nursing_assessment/save.php?mode=update&id=<?php echo attr($_GET["id"]);?>" name="my_form" onsubmit="return top.restoreSession()">
		<div class="row">
			<div class="col-md-4">
				<!-- Save/Cancel buttons -->
				<input type="submit" id="save" class="btn btn-success" value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-3">
				<!-- section for  blood glucose management -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Blood Glucose Management','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BG_level">Blood Glucose Level</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="BG_level" name="BG_level"><?php echo text($obj{"BG_level"});?></textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BG_interventions">Related Interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="BG_interventions" name="BG_interventions">
					    			<option></option>
								    <option <?php if ($obj['BG_interventions']=="Juice Given") echo " SELECTED"; ?>>Juice Given</option>
								    <option <?php if ($obj['BG_interventions']=="Carbohydrate Snack") echo " SELECTED"; ?>>Carbohydrate Snack</option>
								    <option <?php if ($obj['BG_interventions']=="Medication Given") echo " SELECTED"; ?>> Medication Given</option>
								    <option <?php if ($obj['BG_interventions']=="Provider Notified") echo " SELECTED"; ?>>Provider Notified</option>
								    <option <?php if ($obj['BG_interventions']=="Critical Care Guideline Activated") echo " SELECTED"; ?>>Critical Care Guideline Activated</option>
								    <option <?php if ($obj['BG_interventions']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
					</div> <!-- end of panel body -->
					
				</div> <!-- end of panel primary -->
			</div> <!-- end of col-4 -->

			<div class="col-sm-6 col-md-3">
					<!-- section for patient interactions -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Patient interactions','e'); ?></h3>
					</div>

					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pt_behavior">Patient Behaviour</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="pt_behavior" name="pt_behavior">
					    			<option></option>
					    			<option <?php if ($obj['pt_behavior']=="Content") echo " SELECTED"; ?>>Content</option>
					    			<option <?php if ($obj['pt_behavior']=="Combative") echo " SELECTED"; ?>>Combative</option>
					    			<option <?php if ($obj['pt_behavior']=="Compliant") echo " SELECTED"; ?>>Compliant</option>
					    			<option <?php if ($obj['pt_behavior']=="Interactive") echo " SELECTED"; ?>>Interactive</option>
					    			<option <?php if ($obj['pt_behavior']=="Restless") echo " SELECTED"; ?>>Restless</option>
					    			<option <?php if ($obj['pt_behavior']=="Irritable") echo " SELECTED"; ?>>Irritable</option>
					    			<option <?php if ($obj['pt_behavior']=="Noncompliant") echo " SELECTED"; ?>>Noncompliant</option>
					    			<option <?php if ($obj['pt_behavior']=="Flat Affect") echo " SELECTED"; ?>>Flat Affect</option>
					    			<option <?php if ($obj['pt_behavior']=="Constant Movement") echo " SELECTED"; ?>>Constant Movement</option>
					    			<option <?php if ($obj['pt_behavior']=="Non Responsive") echo " SELECTED"; ?>>Non Responsive</option>
					    			<option <?php if ($obj['pt_behavior']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pt_support">Patient Support</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="pt_support" name="pt_support"><?php echo text($obj{"pt_support"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pt_response">Patient Response</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="pt_response" name="pt_response"><?php echo text($obj{"pt_response"});?></textarea>
					    	</div>
						</div>
					</div> <!--  end of panel body -->
				</div> <!-- end of panel primary -->
			</div><!--  end of col-4 -->

			<div class="col-sm-6 col-md-3">
				<!-- assessment section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Assessment','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ass_comments">Comments/Remarks</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="ass_comments" name="ass_comments"><?php echo text($obj{"ass_comments"});?></textarea>
					    	</div>
						</div>
					</div>
					
				</div>
				
			</div><!--  end of col -->

			<div class="col-sm-6 col-md-3">
				<!-- Sedation section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Sedation/Respiration','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="repiratory_pattern">Repiratory Pattern</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="repiratory_pattern" id="repiratory_pattern">
					    			<option></option>
					    			<option <?php if ($obj['repiratory_pattern']=="Abdominal") echo " SELECTED"; ?>>Abdominal</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Agonal") echo " SELECTED"; ?>>Agonal</option>
					    			<option <?php if ($obj['repiratory_pattern']=="apnea") echo " SELECTED"; ?>>apnea</option>
					    			<option <?php if ($obj['repiratory_pattern']=="BiPAP") echo " SELECTED"; ?>>BiPAP</option>
					    			<option <?php if ($obj['repiratory_pattern']=="CPAP") echo " SELECTED"; ?>>CPAP</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Dyspnea") echo " SELECTED"; ?>>Dyspnea</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Gasping") echo " SELECTED"; ?>>Gasping</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Grunting") echo " SELECTED"; ?>>Grunting</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Irregular") echo " SELECTED"; ?>>Irregular</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Labored") echo " SELECTED"; ?>>Labored</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Nasal flaring") echo " SELECTED"; ?>>Nasal flaring</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Oscillator") echo " SELECTED"; ?>>Oscillator</option>
					    			<option <?php if ($obj['repiratory_pattern']=="Prolonged Expiration") echo " SELECTED"; ?>>Prolonged Expiration</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
		</div> <!-- end of row -->
		<div class="row">
				<div class="col-sm-6 col-md-3">
				<!-- section for vitals -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Vitals','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_temperature">Temperature (&#8451;)</label>
					    	<div class="col-sm-6">
					    		<input type="number" name="V_temperature" value="<?php echo text($obj{"V_temperature"});?>" class="form-control" id="V_temperature">
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="pulse">
								Pulse
							</label>

							<div class="col-sm-6">
								<input type="number" name="pulse" value="<?php echo text($obj{"pulse"});?>" class="form-control" id="pulse">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="SpO2">
								SpO2 (%)
							</label>

							<div class="col-sm-6">
								<input type="number" name="SpO2" value="<?php echo text($obj{"SpO2"});?>" class="form-control" id="SpO2">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="FiO2">
								FiO2 (%)
							</label>

							<div class="col-sm-6">
								<input type="number" name="FiO2" value="<?php echo text($obj{"FiO2"});?>" class="form-control" id="FiO2">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="V_RespiratoryRate">
								Respiratory Rate
							</label>

							<div class="col-sm-6">
								<input type="number" name="V_RespiratoryRate" value="<?php echo text($obj{"V_RespiratoryRate"});?>" class="form-control" id="V_RespiratoryRate">
							</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_source">Temp Source</label>
					    	<div class="col-sm-6">
					    		<select name="V_source" class="form-control" id="V_source">
					    		<option></option>
							    <option <?php if ($obj['V_source']=="Axillary") echo " SELECTED"; ?>>Axillary</option>
							    <option <?php if ($obj['V_source']=="Tympanic") echo " SELECTED"; ?>>Tympanic</option>
							    <option <?php if ($obj['V_source']=="Oral") echo " SELECTED"; ?>>Oral</option>
							    <option <?php if ($obj['V_source']=="Swan") echo " SELECTED"; ?>>Swan</option>
							    <option <?php if ($obj['V_source']=="Rectal") echo " SELECTED"; ?>>Rectal</option>
							    <option <?php if ($obj['V_source']=="Esophageal") echo " SELECTED"; ?>>Esophageal</option>
							    <option <?php if ($obj['V_source']=="Temporal") echo " SELECTED"; ?>>Temporal</option>
							    <option <?php if ($obj['V_source']=="Foley") echo " SELECTED"; ?>>Foley</option>
							    <option <?php if ($obj['V_source']=="Other") echo " SELECTED"; ?>>Other</option>
							</select>
					    	</div>
						</div>
						<div class="form-group ">
					      <label class="control-label col-sm-4" for="V_roomAir"> Room air(yes/no)</label>
					      <div class="col-sm-5"><input type="checkbox" value="<?php echo text($obj{"V_roomAir"});?>" name="V_roomAir" id="V_roomAir" name="remember"></div>
					      
					    </div>

					    <div class="form-group">
					    	<label class="control-label col-sm-4" for="V_deliveryMethod">O2 Delivery Method</label>
					    	<div class="col-sm-6">
					    		<select name="V_deliveryMethod" class="form-control" id="V_deliveryMethod">
					    		<option ></option>
							    <option <?php if ($obj['V_deliveryMethod']=="Bagging ETT") echo " SELECTED"; ?>>Bagging ETT</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Blowby") echo " SELECTED"; ?>>Blowby</option>
							    <option <?php if ($obj['V_deliveryMethod']=="BPAP Mask") echo " SELECTED"; ?>>BPAP Mask</option>
							    <option <?php if ($obj['V_deliveryMethod']=="BPAP Nasal") echo " SELECTED"; ?>>BPAP Nasal</option>
							    <option <?php if ($obj['V_deliveryMethod']=="BPAP Prongs") echo " SELECTED"; ?>>BPAP Prongs</option>
							    <option <?php if ($obj['V_deliveryMethod']=="BPAP Vent") echo " SELECTED"; ?>>BPAP Vent</option>
							    <option <?php if ($obj['V_deliveryMethod']=="CPAP Mask") echo " SELECTED"; ?>>CPAP Mask</option>
							    <option <?php if ($obj['V_deliveryMethod']=="CPAP Nasal") echo " SELECTED"; ?>>CPAP Nasal</option>
							    <option <?php if ($obj['V_deliveryMethod']=="CPAP Prongs") echo " SELECTED"; ?>>CPAP Prongs</option>
							    <option <?php if ($obj['V_deliveryMethod']=="CPAP Ventilator") echo " SELECTED"; ?>>CPAP Ventilator</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Face Tent") echo " SELECTED"; ?>>Face Tent</option>
							    <option <?php if ($obj['V_deliveryMethod']=="HHF") echo " SELECTED"; ?>>HHF</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Trach-Vent HME") echo " SELECTED"; ?>>Trach-Vent HME</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Hood") echo " SELECTED"; ?>>Hood</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Mask, Aerosol") echo " SELECTED"; ?>>Mask, Aerosol</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Mask, Low Flow") echo " SELECTED"; ?>>Mask, Low Flow</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Mask, partial rebreathing") echo " SELECTED"; ?>>Mask, partial rebreathing</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Mask, simple") echo " SELECTED"; ?>>Mask, simple</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Mist, Cool") echo " SELECTED"; ?>>Mist, Cool</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Mist, Cool- Room Air") echo " SELECTED"; ?>>Mist, Cool- Room Air</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Mist, warm") echo " SELECTED"; ?>>Mist, warm</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Nasal Cannula") echo " SELECTED"; ?>>Nasal Cannula</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Ocillator") echo " SELECTED"; ?>>Ocillator</option>
							    <option <?php if ($obj['V_deliveryMethod']=="RAM Cannula") echo " SELECTED"; ?>>RAM Cannula</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Trach Collar") echo " SELECTED"; ?>>Trach Collar</option>
							    <option <?php if ($obj['V_deliveryMethod']=="Ventilator") echo " SELECTED"; ?>>Ventilator</option>
							    <option ><?php echo text($obj{"V_deliveryMethod"});?></option>
							</select>
					    	</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-4" for="V_flowRate">
								O2 flow rate(LPM)
							</label>

							<div class="col-sm-6">
								<input type="number" name="V_flowRate" value="<?php echo text($obj{"V_flowRate"});?>" class="form-control" id="V_flowRate">
							</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bp">Blood Pressure</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="V_bp" name="V_bp"><?php echo text($obj{"V_bp"});?></textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bpMean">BP mean</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="V_bpMean" name="V_bpMean"><?php echo text($obj{"V_bpMean"});?></textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bpLocation">Bp location</label>
					    	<div class="col-sm-6">
					    		<select name="V_bpLocation" class="form-control" id="V_bpLocation">
									<option ><?php echo text($obj{"V_bpLocation"});?></option>
								    <option <?php if ($obj['V_bpLocation']=="RUE") echo " SELECTED"; ?>>RUE</option>
								    <option <?php if ($obj['V_bpLocation']=="LUE") echo " SELECTED"; ?>>LUE</option>
								    <option <?php if ($obj['V_bpLocation']=="RLE") echo " SELECTED"; ?>>RLE</option>
								    <option <?php if ($obj['V_bpLocation']=="LLE") echo " SELECTED"; ?>>LLE</option>
								</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="V_bpPosition">Bp patient Position</label>
					    	<div class="col-sm-6">
					    		<select name="V_bpPosition" class="form-control" id="V_bpPosition">
								    <option <?php if ($obj['V_bpPosition']=="Lying") echo " SELECTED"; ?>>Lying</option>
								    <option <?php if ($obj['V_bpPosition']=="Standing") echo " SELECTED"; ?>>Standing</option>
								    <option <?php if ($obj['V_bpPosition']=="Sitting") echo " SELECTED"; ?>>Sitting</option>
								</select>
					    	</div>
						</div>
					</div> <!-- end of panel body vitals-->
					
				</div>
			</div> <!-- end of vitals col -->
			<div class="col-sm-6 col-md-3">
				<!-- section for ADL -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('ADL','e'); ?></h3>
					</div>

					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_location">Patient Location</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_location" class="form-control" id="ADL_location">
						    		<option></option>
								    <option <?php if ($obj['ADL_location']=="Bed/Crib") echo " SELECTED"; ?>>Bed/Crib</option>
								    <option <?php if ($obj['ADL_location']=="Chair") echo " SELECTED"; ?>>Chair</option>
								    <option <?php if ($obj['ADL_location']=="Room") echo " SELECTED"; ?>>Room</option>
								    <option <?php if ($obj['ADL_location']=="Wheelchair") echo " SELECTED"; ?>>Wheelchair</option>
								    <option <?php if ($obj['ADL_location']=="Out of Room") echo " SELECTED"; ?>>Out of Room</option>
								    <option <?php if ($obj['ADL_location']=="Out on Pass") echo " SELECTED"; ?>>Out on Pass</option>
								    <option <?php if ($obj['ADL_location']=="Other") echo " SELECTED"; ?>>Other</option>
								</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_activity">Patient Activity</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_activity" class="form-control" id="ADL_activity">
						    		<option></option>
								    <option <?php if ($obj['ADL_activity']=="Awake") echo " SELECTED"; ?>>Awake</option>
								    <option <?php if ($obj['ADL_activity']=="Being Held") echo " SELECTED"; ?>>Being Held</option>
								    <option <?php if ($obj['ADL_activity']=="Dangle") echo " SELECTED"; ?>>Dangle</option>
								    <option <?php if ($obj['ADL_activity']=="Eating") echo " SELECTED"; ?>>Eating</option>
								    <option <?php if ($obj['ADL_activity']=="Playing") echo " SELECTED"; ?>>Playing</option>
								    <option <?php if ($obj['ADL_activity']=="Quiet Activity") echo " SELECTED"; ?>>Quiet Activity</option>
								    <option <?php if ($obj['ADL_activity']=="Sleeping") echo " SELECTED"; ?>>Sleeping</option>
								    <option <?php if ($obj['ADL_activity']=="Watching TV") echo " SELECTED"; ?>>Watching TV</option>
								    <option <?php if ($obj['ADL_activity']=="Wheelchair Ride") echo " SELECTED"; ?>>Wheelchair Ride</option>
								    <option <?php if ($obj['ADL_activity']=="Other") echo " SELECTED"; ?>>Other</option>
								</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_position">Patient Position</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_position" class="form-control" id="ADL_position">
						    		<option></option>
								    <option <?php if ($obj['ADL_position']=="Prone") echo " SELECTED"; ?>>Prone</option>
								    <option <?php if ($obj['ADL_position']=="Supine") echo " SELECTED"; ?>>Supine</option>
								    <option <?php if ($obj['ADL_position']=="Left Side") echo " SELECTED"; ?>>Left Side</option>
								    <option <?php if ($obj['ADL_position']=="Right Side") echo " SELECTED"; ?>>Right Side</option>
								    <option <?php if ($obj['ADL_position']=="Sitting") echo " SELECTED"; ?>>Sitting</option>
								    <option <?php if ($obj['ADL_position']=="Standing") echo " SELECTED"; ?>>Standing</option>
								    <option <?php if ($obj['ADL_position']=="Other") echo " SELECTED"; ?>>Other</option>
								</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_care">Care Elements Performed</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_care" class="form-control" id="ADL_care">
						    		<option></option>
								    <option <?php if ($obj['ADL_care']=="Assist with elimination") echo " SELECTED"; ?>>Assist with elimination</option>
								    <option <?php if ($obj['ADL_care']=="Cares of Deffered") echo " SELECTED"; ?>>Cares of Deffered</option>
								    <option <?php if ($obj['ADL_care']=="Device Repositioned") echo " SELECTED"; ?>>Device Repositioned</option>
								    <option <?php if ($obj['ADL_care']=="Diaper") echo " SELECTED"; ?>>Diaper</option>
								    <option <?php if ($obj['ADL_care']=="Electrode patches changed") echo " SELECTED"; ?>>Electrode patches changed</option>
								    <option <?php if ($obj['ADL_care']=="Eye care") echo " SELECTED"; ?>>Eye care</option>
								    <option <?php if ($obj['ADL_care']=="Hair care") echo " SELECTED"; ?>>Hair care</option>
								    <option <?php if ($obj['ADL_care']=="Linen Change") echo " SELECTED"; ?>>Linen Change</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_bath">Bath/Shower</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_bath" class="form-control" id="ADL_bath">
						    		<option></option>
								    <option <?php if ($obj['ADL_bath']=="Bath/Shower") echo " SELECTED"; ?>>Bath/Shower</option>
								    <option <?php if ($obj['ADL_bath']=="Bath refused") echo " SELECTED"; ?>>Bath refused</option>
								    <option <?php if ($obj['ADL_bath']=="Bed bath") echo " SELECTED"; ?>>Bed bath</option>
								    <option <?php if ($obj['ADL_bath']=="2% CHG bath") echo " SELECTED"; ?>>2% CHG bath</option>
								    <option <?php if ($obj['ADL_bath']=="2% CHG bath contraindicated") echo " SELECTED"; ?>>2% CHG bath contraindicated</option>
								    <option <?php if ($obj['ADL_bath']=="Contraindicated") echo " SELECTED"; ?>>Contraindicated</option>
								    <option <?php if ($obj['ADL_bath']=="Patient Unstable") echo " SELECTED"; ?>>Patient Unstable</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_bed">Bed/Position</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_bed" class="form-control" id="ADL_bed">
						    		<option></option>
								    <option <?php if ($obj['ADL_bed']=="HOB 10 degrees") echo " SELECTED"; ?>>HOB 10 degrees</option>
								    <option <?php if ($obj['ADL_bed']=="HOB 20 degrees") echo " SELECTED"; ?>>HOB 20 degrees</option>
								    <option <?php if ($obj['ADL_bed']=="HOB 30 degrees") echo " SELECTED"; ?>>HOB 30 degrees</option>
								    <option <?php if ($obj['ADL_bed']=="HOB 45 degrees") echo " SELECTED"; ?>>HOB 45 degrees</option>
								    <option <?php if ($obj['ADL_bed']=="HOB 60 degrees") echo " SELECTED"; ?>>HOB 60 degrees</option>
								    <option <?php if ($obj['ADL_bed']=="HOB 90 degrees") echo " SELECTED"; ?>>HOB 90 degrees</option>
								    <option <?php if ($obj['ADL_bed']=="HOB flat") echo " SELECTED"; ?>>HOB flat</option>
								    <option <?php if ($obj['ADL_bed']=="Reverse") echo " SELECTED"; ?>>Reverse</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_assistance">ADL Assistance Level</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_assistance" class="form-control" id="ADL_assistance">
						    		<option></option>
								    <option <?php if ($obj['ADL_assistance']=="Caregiver") echo " SELECTED"; ?>>Caregiver</option>
								    <option <?php if ($obj['ADL_assistance']=="Family") echo " SELECTED"; ?>>Family</option>
								    <option <?php if ($obj['ADL_assistance']=="Independent") echo " SELECTED"; ?>>Independent</option>
								    <option <?php if ($obj['ADL_assistance']=="Staff-single") echo " SELECTED"; ?>>Staff-single</option>
								    <option <?php if ($obj['ADL_assistance']=="Staff-multiple") echo " SELECTED"; ?>>Staff-multiple</option>
								    <option <?php if ($obj['ADL_assistance']=="Other") echo " SELECTED"; ?>>Other</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_mobilisation">Mobilisation</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_mobilisation" class="form-control" id="ADL_mobilisation">
						    		<option></option>
								    <option <?php if ($obj['ADL_mobilisation']=="Ambulate") echo " SELECTED"; ?>>Ambulate</option>
								    <option <?php if ($obj['ADL_mobilisation']=="Repositioned") echo " SELECTED"; ?>>Repositioned</option>
								    <option <?php if ($obj['ADL_mobilisation']=="ROM") echo " SELECTED"; ?>>ROM</option>
								    <option <?php if ($obj['ADL_mobilisation']=="Transferred to chair/bed") echo " SELECTED"; ?>>Transferred to chair/bed</option>
								    <option <?php if ($obj['ADL_mobilisation']=="Other") echo " SELECTED"; ?>>Other</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_scd">SCD's or Ted Hose</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_scd" class="form-control" id="ADL_scd">
						    		<option></option>
								    <option <?php if ($obj['ADL_scd']=="On") echo " SELECTED"; ?>>On</option>
								    <option <?php if ($obj['ADL_scd']=="OFF") echo " SELECTED"; ?>>OFF</option>
								    <option <?php if ($obj['ADL_scd']=="NA") echo " SELECTED"; ?>>NA</option>
								</select>
					    	</div>
						</div>
					</div> <!--  end of panel body -->
				</div> <!-- end of panel primary -->
			</div>
			<div class="col-sm-6 col-md-3">
					<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Pain','e'); ?></h3>
					</div>

					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_scale">Pain Scale</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_scale" name="Pain_scale">
					    			<option></option>
					    			<option <?php if ($obj['Pain_scale']=="Faces") echo " SELECTED"; ?>>Faces</option>
					    			<option <?php if ($obj['Pain_scale']=="N-Pass") echo " SELECTED"; ?>>N-Pass</option>
					    			<option <?php if ($obj['Pain_scale']=="rFLACC") echo " SELECTED"; ?>>rFLACC</option>
					    			<option <?php if ($obj['Pain_scale']=="Numeric/Verbal scale") echo " SELECTED"; ?>>Numeric/Verbal scale</option>
					    			<option <?php if ($obj['Pain_scale']=="COMFORT Scale") echo " SELECTED"; ?>>COMFORT Scale</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_gaol">Patient's Pain Goal</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_gaol" name="Pain_gaol">
					    			<option></option>
					    			<option <?php if ($obj['Pain_gaol']=="Mentally/Developmentally unable to report") echo " SELECTED"; ?>>Mentally/Developmentally unable to report</option>
					    			<option <?php if ($obj['Pain_gaol']=="0") echo " SELECTED"; ?>>0</option>
					    			<option <?php if ($obj['Pain_gaol']=="1") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['Pain_gaol']=="2") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['Pain_gaol']=="3") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['Pain_gaol']=="4") echo " SELECTED"; ?>>4</option>
					    			<option <?php if ($obj['Pain_gaol']=="5") echo " SELECTED"; ?>>5</option>
					    			<option <?php if ($obj['Pain_gaol']=="6") echo " SELECTED"; ?>>6</option>
					    			<option <?php if ($obj['Pain_gaol']=="7") echo " SELECTED"; ?>>7</option>
					    			<option <?php if ($obj['Pain_gaol']=="8") echo " SELECTED"; ?>>8</option>
					    			<option <?php if ($obj['Pain_gaol']=="9") echo " SELECTED"; ?>>9</option>
					    			<option <?php if ($obj['Pain_gaol']=="10") echo " SELECTED"; ?>>10</option>
					    		</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_rating">Pain rating</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_rating" name="Pain_rating">
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>0</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>4</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>5</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>6</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>7</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>8</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>9</option>
					    			<option <?php if ($obj['Pain_rating']=="10") echo " SELECTED"; ?>>10</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_location">Pain Location</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="Pain_location" name="Pain_location"><?php echo text($obj{"Pain_location"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_description">Pain description</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_description" name="Pain_description">
					    			<option></option>
					    			<option <?php if ($obj['Pain_description']=="Aching") echo " SELECTED"; ?>>Aching</option>
					    			<option <?php if ($obj['Pain_description']=="Burning") echo " SELECTED"; ?>>Burning</option>
					    			<option <?php if ($obj['Pain_description']=="Cramping") echo " SELECTED"; ?>>Cramping</option>
					    			<option <?php if ($obj['Pain_description']=="Crushing") echo " SELECTED"; ?>>Crushing</option>
					    			<option <?php if ($obj['Pain_description']=="Discomfort") echo " SELECTED"; ?>>Discomfort</option>
					    			<option <?php if ($obj['Pain_description']=="Dull") echo " SELECTED"; ?>>Dull</option>
					    			<option <?php if ($obj['Pain_description']=="Headache") echo " SELECTED"; ?>>Headache</option>
					    			<option <?php if ($obj['Pain_description']=="Heaviness") echo " SELECTED"; ?>>Heaviness</option>
					    			<option <?php if ($obj['Pain_description']=="Itching") echo " SELECTED"; ?>>Itching</option>
					    			<option <?php if ($obj['Pain_description']=="Nagging") echo " SELECTED"; ?>>Nagging</option>
					    			<option <?php if ($obj['Pain_description']=="Numbness") echo " SELECTED"; ?>>Numbness</option>
					    			<option <?php if ($obj['Pain_description']=="Penetrating") echo " SELECTED"; ?>>Penetrating</option>
					    			<option <?php if ($obj['Pain_description']=="Pins") echo " SELECTED"; ?>>Pins</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_frequency">Frequency</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_frequency" name="Pain_frequency">
					    			<option></option>
					    			<option <?php if ($obj['Pain_frequency']=="Constant/Continuous") echo " SELECTED"; ?>>Constant/Continuous</option>
					    			<option <?php if ($obj['Pain_frequency']=="With Movement") echo " SELECTED"; ?>>With Movement</option>
					    			<option <?php if ($obj['Pain_frequency']=="Intermittent") echo " SELECTED"; ?>>Intermittent</option>
					    			<option <?php if ($obj['Pain_frequency']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_interventions">Interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_interventions" name="Pain_interventions">
					    			<option></option>
					    			<option <?php if ($obj['Pain_interventions']=="Caregiver support") echo " SELECTED"; ?>>Caregiver support</option>
					    			<option <?php if ($obj['Pain_interventions']=="continuous opioid drip") echo " SELECTED"; ?>>continuous opioid drip</option>
					    			<option <?php if ($obj['Pain_interventions']=="declined pain medication") echo " SELECTED"; ?>>declined pain medication</option>
					    			<option <?php if ($obj['Pain_interventions']=="distraction") echo " SELECTED"; ?>>distraction</option>
					    			<option <?php if ($obj['Pain_interventions']=="epidural") echo " SELECTED"; ?>>epidural</option>
					    			<option <?php if ($obj['Pain_interventions']=="heat") echo " SELECTED"; ?>>heat</option>
					    			<option <?php if ($obj['Pain_interventions']=="holding") echo " SELECTED"; ?>>holding</option>
					    			<option <?php if ($obj['Pain_interventions']=="ice") echo " SELECTED"; ?>>ice</option>
					    			<option <?php if ($obj['Pain_interventions']=="imagery") echo " SELECTED"; ?>>imagery</option>
					    			<option <?php if ($obj['Pain_interventions']=="nonopioid") echo " SELECTED"; ?>>nonopioid</option>
					    			<option <?php if ($obj['Pain_interventions']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
					    </div>
					    	<div class="form-group">
						    	<label class="control-label col-sm-4" for="Pain_medSideEffects">Medications side effects</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" id="Pain_medSideEffects" name="Pain_medSideEffects">
						    			<option></option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="None") echo " SELECTED"; ?>>None</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Constipated") echo " SELECTED"; ?>>Constipated</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Nausea") echo " SELECTED"; ?>>Nausea</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="O2 Sats 90%") echo " SELECTED"; ?>>O2 Sats 90%</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Oversedation") echo " SELECTED"; ?>>Oversedation</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Pruritis") echo " SELECTED"; ?>>Pruritis</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Resp Decreased") echo " SELECTED"; ?>>Resp Decreased</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Resp Shallow") echo " SELECTED"; ?>>Resp Shallow</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Urine Retention") echo " SELECTED"; ?>>Urine Retention</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Vomitting") echo " SELECTED"; ?>>Vomitting</option>
						    			<option <?php if ($obj['Pain_medSideEffects']=="Other") echo " SELECTED"; ?>>Other</option>
						    		</select>
						    	</div> 
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="pasero_sedation_score">Pasero Sedation Score</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" id="pasero_sedation_score" name="pasero_sedation_score">
						    			<option></option>
						    			<option <?php if ($obj['pasero_sedation_score']=="S") echo " SELECTED"; ?>>S</option>
						    			<option <?php if ($obj['pasero_sedation_score']=="1") echo " SELECTED"; ?>>1</option>
						    			<option <?php if ($obj['pasero_sedation_score']=="2") echo " SELECTED"; ?>>2</option>
						    			<option <?php if ($obj['pasero_sedation_score']=="3") echo " SELECTED"; ?>>3</option>
						    			<option <?php if ($obj['pasero_sedation_score']=="4") echo " SELECTED"; ?>>4</option>
						    			<option <?php if ($obj['pasero_sedation_score']=="l") echo " SELECTED"; ?>>l</option>
						    		</select>
						    	</div> 
							</div>
					</div> <!--  end of panel body -->
				</div> <!-- end of panel primary -->
			</div><!--  end of col-4 -->
			<div class="col-sm-6 col-md-3">
				<!-- Provider notification section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Provider Notification','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="PN_reason">Reason for  Communication</label>
					    	<div class="col-sm-6">
					    		<textarea name="PN_reason" class="form-control" id="PN_reason"><?php echo text($obj{"PN_reason"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label  class="control-label col-sm-4" for="PN_role">Provider Name/role</label>
					    	<div class="col-sm-6">
					    		<textarea name="PN_role" class="form-control" id="PN_role"><?php echo text($obj{"PN_role"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="PN_method">Method of Communication</label>
					    	<div class="col-sm-6">
					    		<select name="PN_method" class="form-control" id="PN_method">
					    		<option></option>
							    <option <?php if ($obj['PN_method']=="Call") echo " SELECTED"; ?>>Call</option>
							    <option <?php if ($obj['PN_method']=="Page") echo " SELECTED"; ?>>Page</option>
							    <option <?php if ($obj['PN_method']=="Face to Face") echo " SELECTED"; ?>>Face to Face</option>
							    <option <?php if ($obj['PN_method']=="Other") echo " SELECTED"; ?>>Other</option>
							</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="PN_response">Response</label>
					    	<div class="col-sm-6">
					    		<select name="PN_response" class="form-control" id="PN_response">
					    		<option></option>
							    <option <?php if ($obj['PN_response']=="En route") echo " SELECTED"; ?>>En route</option>
							    <option <?php if ($obj['PN_response']=="At bedside") echo " SELECTED"; ?>>At bedside</option>
							    <option <?php if ($obj['PN_response']=="Escalation Huddle") echo " SELECTED"; ?>>Escalation Huddle</option>
							    <option <?php if ($obj['PN_response']=="In Department") echo " SELECTED"; ?>>In Department</option>
							    <option <?php if ($obj['PN_response']=="No New Orders") echo " SELECTED"; ?>>No New Orders</option>
							    <option <?php if ($obj['PN_response']=="See Orders") echo " SELECTED"; ?>>See Orders</option>
							    <option <?php if ($obj['PN_response']=="Waiting for Response") echo " SELECTED"; ?>>Waiting for Response</option>
							    <option <?php if ($obj['PN_response']=="other") echo " SELECTED"; ?>>other</option>
							</select>
					    	</div>
						</div>
					</div>
					
				</div>
				
			</div>

			
		</div> <!-- end of row -->

		<div class="row">
			<div class="col-sm-6 col-md-3">
				<!-- Neuro motor location section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Neuro Motor','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NM_location">Location</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NM_location" id="NM_location">
					    			<option></option>
					    			<option <?php if ($obj['NM_location']=="All extremities") echo " SELECTED"; ?>>All extremities</option>
					    			<option <?php if ($obj['NM_location']=="right side") echo " SELECTED"; ?>>right side</option>
					    			<option <?php if ($obj['NM_location']=="left side") echo " SELECTED"; ?>>left side</option>
					    			<option <?php if ($obj['NM_location']=="RUE") echo " SELECTED"; ?>>RUE</option>
					    			<option <?php if ($obj['NM_location']=="LUE") echo " SELECTED"; ?>>LUE</option>
					    			<option <?php if ($obj['NM_location']=="LLE") echo " SELECTED"; ?>>LLE</option>
					    			<option <?php if ($obj['NM_location']=="Upper bilateral") echo " SELECTED"; ?>>Upper bilateral</option>
					    			<option <?php if ($obj['NM_location']=="Lower Bilateral") echo " SELECTED"; ?>>Lower Bilateral</option>
					    			<option <?php if ($obj['NM_location']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NM_limbMovnt">Limb Movements</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NM_limbMovnt" id="NM_limbMovnt">
					    			<option></option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Full spontaneous") echo " SELECTED"; ?>>Full spontaneous</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Contracted") echo " SELECTED"; ?>>Contracted</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Decerebrate") echo " SELECTED"; ?>>Decerebrate</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Decorticate") echo " SELECTED"; ?>>Decorticate</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Flaccid") echo " SELECTED"; ?>>Flaccid</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Limited Spontaneous") echo " SELECTED"; ?>>Limited Spontaneous</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Jittery") echo " SELECTED"; ?>>Jittery</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Med paralyzed") echo " SELECTED"; ?>>Med paralyzed</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Moves to painful stimuli") echo " SELECTED"; ?>>Moves to painful stimuli</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NM_strength">Motor Strength</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NM_strength" id="NM_strength">
					    			<option></option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Normal") echo " SELECTED"; ?>>Normal</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Baseline") echo " SELECTED"; ?>>Baseline</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Strong") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="BiPAP") echo " SELECTED"; ?>>BiPAP</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Hypertonic") echo " SELECTED"; ?>>Hypertonic</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Hypotonic") echo " SELECTED"; ?>>Hypotonic</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Very Weak") echo " SELECTED"; ?>>Very Weak</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Flaccid") echo " SELECTED"; ?>>Flaccid</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Absent") echo " SELECTED"; ?>>Absent</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Unable to assess") echo " SELECTED"; ?>>Unable to assess</option>
					    			<option <?php if ($obj['NM_limbMovnt']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NM_sensation">Sensation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NM_sensation" id="NM_sensation">
					    			<option></option>
					    			<option <?php if ($obj['NM_sensation']=="Normal") echo " SELECTED"; ?>>Normal</option>
					    			<option <?php if ($obj['NM_sensation']=="full") echo " SELECTED"; ?>>full</option>
					    			<option <?php if ($obj['NM_sensation']=="Tingling") echo " SELECTED"; ?>>Tingling</option>
					    			<option <?php if ($obj['NM_sensation']=="Decreased") echo " SELECTED"; ?>>Decreased</option>
					    			<option <?php if ($obj['NM_sensation']=="Numbness") echo " SELECTED"; ?>>Numbness</option>
					    			<option <?php if ($obj['NM_sensation']=="Unable to assess") echo " SELECTED"; ?>>Unable to assess</option>
					    			<option <?php if ($obj['NM_sensation']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
			<div class="col-sm-6 col-md-3">
				<!-- Glasgow coma scale section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl(' Glasgow coma scale','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_eyeOpening">Eye opening</label>
					    	<div class="col-sm-6">
					    		<input type="number" value="<?php echo text($obj{"GCS_eyeOpening"});?>" min="1" max="4" class="form-control" name="GCS_eyeOpening" id="GCS_eyeOpening">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_bestVerbal">Best Verbal</label>
					    	<div class="col-sm-6">
					    		<input type="number" min="1" max="5" class="form-control" value="<?php echo text($obj{"GCS_bestVerbal"});?>" name="GCS_bestVerbal" id="GCS_bestVerbal">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_bestMotor">Best Motor</label>
					    	<div class="col-sm-6">
					    		<input type="number" id="GCS_bestMotor" value="<?php echo text($obj{"GCS_bestMotor"});?>" name="GCS_bestMotor" min="1" max="6">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_total">Total Score</label>
					    	<div class="col-sm-6">
					    		<input min="3" max="15" class="form-control" value="<?php echo text($obj{"GCS_total"});?>" name="GCS_total" id="GCS_total">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_remarks">Comments/Remarks</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="GCS_remarks" id="GCS_remarks">
					    			<option></option>
					    			<option <?php if ($obj['GCS_remarks']=="Sedated") echo " SELECTED"; ?>>Sedated</option>
					    			<option <?php if ($obj['GCS_remarks']=="medically paralyzed") echo " SELECTED"; ?>>medically paralyzed</option>
					    			<option <?php if ($obj['GCS_remarks']=="intubated") echo " SELECTED"; ?>>intubated</option>
					    			<option <?php if ($obj['GCS_remarks']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
			<div class="col-sm-6 col-md-3">
				<!-- Neurological exam section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Neurological exam','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_Orientaion">Orientation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_Orientaion" id="NE_Orientaion">
					    			<option></option>
					    			<option <?php if ($obj['NE_Orientaion']=="Alert") echo " SELECTED"; ?>>Alert</option>
					    			<option <?php if ($obj['NE_Orientaion']=="Alert, Awake and Oriented x3") echo " SELECTED"; ?>>Alert, Awake and Oriented x3</option>
					    			<option <?php if ($obj['NE_Orientaion']=="Alert, Awake, oriented appropriate to age") echo " SELECTED"; ?>>Alert, Awake, oriented appropriate to age</option>
					    			<option <?php if ($obj['NE_Orientaion']=="Agitated") echo " SELECTED"; ?>>Agitated</option>
					    			<option <?php if ($obj['NE_Orientaion']=="Awake") echo " SELECTED"; ?>>Awake</option>
					    			<option <?php if ($obj['NE_Orientaion']=="Awakens easily") echo " SELECTED"; ?>>Awakens easily</option>
					    			<option <?php if ($obj['NE_Orientaion']=="Comatose") echo " SELECTED"; ?>>Comatose</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_cry">cry</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_cry" id="NE_cry">
					    			<option></option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>Absent</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>high pitched</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>hoarse</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>intubated-visibly crying</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>stridorous</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>Unable to assess</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>weak</option>
					    			<option <?php if ($obj['NE_cry']=="Comatose") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_pr_bilat">Pupil reaction/bilat</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_pr_bilat" id="NE_pr_bilat">
					    			<option></option>
					    			<option <?php if ($obj['NE_pr_bilat']=="Brisk") echo " SELECTED"; ?>>Brisk</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="Dilated") echo " SELECTED"; ?>>Dilated</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="Hippus") echo " SELECTED"; ?>>Hippus</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="Sluggish") echo " SELECTED"; ?>>Sluggish</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="No response") echo " SELECTED"; ?>>No response</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="Unable to assess") echo " SELECTED"; ?>>Unable to assess</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="1mm-8mm") echo " SELECTED"; ?>>1mm-8mm</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="PERL") echo " SELECTED"; ?>>PERL</option>
					    			<option <?php if ($obj['NE_pr_bilat']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_pr_left">Pupil reaction/left</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_pr_left" id="NE_pr_left">
					    			<option></option>
					    			<option <?php if ($obj['NE_pr_left']=="Brisk") echo " SELECTED"; ?>>Brisk</option>
					    			<option <?php if ($obj['NE_pr_left']=="Dilated") echo " SELECTED"; ?>>Dilated</option>
					    			<option <?php if ($obj['NE_pr_left']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['NE_pr_left']=="Hippus") echo " SELECTED"; ?>>Hippus</option>
					    			<option <?php if ($obj['NE_pr_left']=="Sluggish") echo " SELECTED"; ?>>Sluggish</option>
					    			<option <?php if ($obj['NE_pr_left']=="No response") echo " SELECTED"; ?>>No response</option>
					    			<option <?php if ($obj['NE_pr_left']=="Unable to assess") echo " SELECTED"; ?>>Unable to assess</option>
					    			<option <?php if ($obj['NE_pr_left']=="1mm-8mm") echo " SELECTED"; ?>>1mm-8mm</option>
					    			<option <?php if ($obj['NE_pr_left']=="PERL") echo " SELECTED"; ?>>PERL</option>
					    			<option <?php if ($obj['NE_pr_left']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_pr_right">Pupil reaction/right</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_pr_right" id="NE_pr_right">
					    			<option></option>
					    			<option <?php if ($obj['NE_pr_right']=="Brisk") echo " SELECTED"; ?>>Brisk</option>
					    			<option <?php if ($obj['NE_pr_right']=="Dilated") echo " SELECTED"; ?>>Dilated</option>
					    			<option <?php if ($obj['NE_pr_right']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['NE_pr_right']=="Hippus") echo " SELECTED"; ?>>Hippus</option>
					    			<option <?php if ($obj['NE_pr_right']=="Sluggish") echo " SELECTED"; ?>>Sluggish</option>
					    			<option <?php if ($obj['NE_pr_right']=="No response") echo " SELECTED"; ?>>No response</option>
					    			<option <?php if ($obj['NE_pr_right']=="Unable to assess") echo " SELECTED"; ?>>Unable to assess</option>
					    			<option <?php if ($obj['NE_pr_right']=="1mm-8mm") echo " SELECTED"; ?>>1mm-8mm</option>
					    			<option <?php if ($obj['NE_pr_right']=="PERL") echo " SELECTED"; ?>>PERL</option>
					    			<option <?php if ($obj['NE_pr_right']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_oculomotor">Oculomotor</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_oculomotor" id="NE_oculomotor">
					    			<option></option>
					    			<option <?php if ($obj['NE_oculomotor']=="Fucus/Tracks/Conjugates") echo " SELECTED"; ?>>Fucus/Tracks/Conjugates</option>
					    			<option <?php if ($obj['NE_oculomotor']=="Disconjugate") echo " SELECTED"; ?>>Disconjugate</option>
					    			<option <?php if ($obj['NE_oculomotor']=="Focus") echo " SELECTED"; ?>>Focus</option>
					    			<option <?php if ($obj['NE_oculomotor']=="L gaze preference") echo " SELECTED"; ?>>L gaze preference</option>
					    			<option <?php if ($obj['NE_oculomotor']=="R gaze preference") echo " SELECTED"; ?>>R gaze preference</option>
					    			<option <?php if ($obj['NE_oculomotor']=="Nystagmus") echo " SELECTED"; ?>>Nystagmus</option>
					    			<option <?php if ($obj['NE_oculomotor']=="L Ptosis") echo " SELECTED"; ?>>L Ptosis</option>
					    			<option <?php if ($obj['NE_oculomotor']=="R ptosis") echo " SELECTED"; ?>>R ptosis</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_fontanel">fontanel</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_fontanel" id="NE_fontanel">
					    			<option></option>
					    			<option <?php if ($obj['NE_fontanel']=="soft") echo " SELECTED"; ?>>soft</option>
					    			<option <?php if ($obj['NE_fontanel']=="Flat") echo " SELECTED"; ?>>Flat</option>
					    			<option <?php if ($obj['NE_fontanel']=="Sutures approximated") echo " SELECTED"; ?>>Sutures approximated</option>
					    			<option <?php if ($obj['NE_fontanel']=="Sutures overriding") echo " SELECTED"; ?>>Sutures overriding</option>
					    			<option <?php if ($obj['NE_fontanel']=="Sutures split") echo " SELECTED"; ?>>Sutures split</option>
					    			<option <?php if ($obj['NE_fontanel']=="Bulging") echo " SELECTED"; ?>>Bulging</option>
					    			<option <?php if ($obj['NE_fontanel']=="Closed") echo " SELECTED"; ?>>Closed</option>
					    			<option <?php if ($obj['NE_fontanel']=="Depressed") echo " SELECTED"; ?>>Depressed</option>
					    			<option <?php if ($obj['NE_fontanel']=="R ptosis") echo " SELECTED"; ?>>Full</option>
					    			<option <?php if ($obj['NE_fontanel']=="Full") echo " SELECTED"; ?>>Pulsatile</option>
					    			<option <?php if ($obj['NE_fontanel']=="Tense") echo " SELECTED"; ?>>Tense</option>
					    			<option <?php if ($obj['NE_fontanel']=="Unable to assess") echo " SELECTED"; ?>>Unable to assess</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_reflexes">reflexes</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" min="0" max="5" value="<?php echo text($obj{"NE_reflexes"});?>"  name="NE_reflexes" id="NE_reflexes">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_seizure_activity">seizure activity</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_seizure_activity" id="NE_seizure_activity">
					    			<option></option>
					    			<option <?php if ($obj['NE_seizure_activity']=="No") echo " SELECTED"; ?>>No</option>
					    			<option <?php if ($obj['NE_seizure_activity']=="Yes") echo " SELECTED"; ?>>Yes</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_monitoring">neuro monitoring</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_monitoring" id="NE_monitoring">
					    			<option></option>
					    			<option <?php if ($obj['NE_monitoring']=="Continuous EEG") echo " SELECTED"; ?>>Continuous EEG</option>
					    			<option <?php if ($obj['NE_monitoring']=="Brainz") echo " SELECTED"; ?>>Brainz</option>
					    			<option <?php if ($obj['NE_monitoring']=="CFM") echo " SELECTED"; ?>>CFM</option>
					    			<option <?php if ($obj['NE_monitoring']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_collar">c-collar</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_collar" id="NE_collar">
					    			<option></option>
					    			<option <?php if ($obj['NE_collar']=="Yes") echo " SELECTED"; ?>>Yes</option>
					    			<option <?php if ($obj['NE_collar']=="Cleaned") echo " SELECTED"; ?>>Cleaned</option>
					    			<option <?php if ($obj['NE_collar']=="D/C'd") echo " SELECTED"; ?>>D/C'd</option>
					    			<option <?php if ($obj['NE_collar']=="Pads changed") echo " SELECTED"; ?>>Pads changed</option>
					    			<option <?php if ($obj['NE_collar']=="Skin checked") echo " SELECTED"; ?>>Skin checked</option>
					    			<option <?php if ($obj['NE_collar']=="Replaced") echo " SELECTED"; ?>>Replaced</option>
					    			<option <?php if ($obj['NE_collar']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
			<div  class="col-sm-6 col-md-3">
			<!-- Psychiatric section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Psychiatric issues (Daily Assessment)','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_Orientaion">Orientation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_Orientaion" id="psy_Orientaion">
					    			<option></option>
					    			<option <?php if ($obj['psy_Orientaion']=="Person") echo " SELECTED"; ?>>Person</option>
					    			<option <?php if ($obj['psy_Orientaion']=="Place") echo " SELECTED"; ?>>Place</option>
					    			<option <?php if ($obj['psy_Orientaion']=="Time") echo " SELECTED"; ?>>Time</option>
					    			<option <?php if ($obj['psy_Orientaion']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_mood">Mood/affect</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_mood" id="psy_mood">
					    			<option <?php if ($obj['psy_mood']=="Broad") echo " SELECTED"; ?>>Broad</option>
					    			<option <?php if ($obj['psy_mood']=="Depressed") echo " SELECTED"; ?>>Depressed</option>
					    			<option <?php if ($obj['psy_mood']=="Cheerful") echo " SELECTED"; ?>>Cheerful</option>
					    			<option <?php if ($obj['psy_mood']=="Irritable") echo " SELECTED"; ?>>Irritable</option>
					    			<option <?php if ($obj['psy_mood']=="Hostile") echo " SELECTED"; ?>>Hostile</option>
					    			<option <?php if ($obj['psy_mood']=="Restricted") echo " SELECTED"; ?>>Restricted</option>
					    			<option <?php if ($obj['psy_mood']=="Flat") echo " SELECTED"; ?>>Flat</option>
					    			<option <?php if ($obj['psy_mood']=="Labile") echo " SELECTED"; ?>>Labile</option>
					    			<option <?php if ($obj['psy_mood']=="Bizarre") echo " SELECTED"; ?>>Bizarre</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_memory">Concentration/Memory</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_memory" id="psy_memory">
									<option></option>
					    			<option <?php if ($obj['psy_memory']=="WNL") echo " SELECTED"; ?>>WNL</option>
					    			<option <?php if ($obj['psy_memory']=="good") echo " SELECTED"; ?>>good</option>
					    			<option <?php if ($obj['psy_memory']=="poor") echo " SELECTED"; ?>>poor</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_appearance">Appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_appearance" id="psy_appearance">
					    			<option></option>
					    			<option <?php if ($obj['psy_appearance']=="Disheveled") echo " SELECTED"; ?>>Disheveled</option>
					    			<option <?php if ($obj['psy_appearance']=="Neat/Clean") echo " SELECTED"; ?>>Neat/Clean</option>
					    			<option <?php if ($obj['psy_appearance']=="Poor personal hygiene") echo " SELECTED"; ?>>Poor personal hygiene</option>
					    			<option <?php if ($obj['psy_appearance']=="Appears older than stated") echo " SELECTED"; ?>>Appears older than stated</option>
					    			<option <?php if ($obj['psy_appearance']=="Appears younger than stated") echo " SELECTED"; ?>>Appears younger than stated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_behaviour">Behavior</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_behaviour" id="psy_behaviour">
					    			<option></option>
					    			<option <?php if ($obj['psy_behaviour']=="Normal/alert") echo " SELECTED"; ?>>Normal/alert</option>
					    			<option <?php if ($obj['psy_behaviour']=="Cooperative") echo " SELECTED"; ?>>Cooperative</option>
					    			<option <?php if ($obj['psy_behaviour']=="Uncooperative") echo " SELECTED"; ?>>Uncooperative</option>
					    			<option <?php if ($obj['psy_behaviour']=="Resistant") echo " SELECTED"; ?>>Resistant</option>
					    			<option <?php if ($obj['psy_behaviour']=="Overactive") echo " SELECTED"; ?>>Overactive</option>
					    			<option <?php if ($obj['psy_behaviour']=="Combative") echo " SELECTED"; ?>>Combative</option>
					    			<option <?php if ($obj['psy_behaviour']=="Withdrawn") echo " SELECTED"; ?>>Withdrawn</option>
					    			<option <?php if ($obj['psy_behaviour']=="Dramtized") echo " SELECTED"; ?>>Dramtized</option>
					    			<option <?php if ($obj['psy_behaviour']=="Self-destructive") echo " SELECTED"; ?>>Self-destructive</option>
					    			<option <?php if ($obj['psy_behaviour']=="Agitated") echo " SELECTED"; ?>>Agitated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_eyeContact">Eye contact</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_eyeContact" id="psy_eyeContact">
					    			<option></option>
					    			<option <?php if ($obj['psy_eyeContact']=="Appropriate") echo " SELECTED"; ?>>Appropriate</option>
					    			<option <?php if ($obj['psy_eyeContact']=="Poor") echo " SELECTED"; ?>>Poor</option>
					    			<option <?php if ($obj['psy_eyeContact']=="Non-existent") echo " SELECTED"; ?>>Non-existent</option>
					    			<option <?php if ($obj['psy_eyeContact']=="Glaring") echo " SELECTED"; ?>>Glaring</option>
					    			<option <?php if ($obj['psy_eyeContact']=="Culturally appropriate") echo " SELECTED"; ?>>Culturally appropriate</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_thought">Thought</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_thought" id="psy_thought">
					    			<option></option>
					    			<option <?php if ($obj['psy_thought']=="Logical/Coherent") echo " SELECTED"; ?>>Logical/Coherent</option>
					    			<option <?php if ($obj['psy_thought']=="Tangential") echo " SELECTED"; ?>>Tangential</option>
					    			<option <?php if ($obj['psy_thought']=="Loose association") echo " SELECTED"; ?>>Loose association</option>
					    			<option <?php if ($obj['psy_thought']=="Disorganized") echo " SELECTED"; ?>>Disorganized</option>
					    			<option <?php if ($obj['psy_thought']=="Over involvement in fantasy") echo " SELECTED"; ?>>Over involvement in fantasy</option>
					    			<option <?php if ($obj['psy_thought']=="Hallucination") echo " SELECTED"; ?>>Hallucination</option>
					    			<option <?php if ($obj['psy_thought']=="No Hallucinations") echo " SELECTED"; ?>>No Hallucinations</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pys_judgment">Insight/Judgement</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="pys_judgment" id="pys_judgment">
					    			<option></option>
					    			<option <?php if ($obj['pys_judgment']=="WNL") echo " SELECTED"; ?>>WNL</option>
					    			<option <?php if ($obj['pys_judgment']=="Good") echo " SELECTED"; ?>>Good</option>
					    			<option <?php if ($obj['pys_judgment']=="poor") echo " SELECTED"; ?>>poor</option>
					    			<option <?php if ($obj['pys_judgment']=="fair") echo " SELECTED"; ?>>fair</option>
					    			<option <?php if ($obj['pys_judgment']=="lacking") echo " SELECTED"; ?>>lacking</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_interactions">Patient/Caregiver interactions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="pys_interactions" id="pys_interactions">
					    			<option></option>
					    			<option <?php if ($obj['pys_interactions']=="Appropriate") echo " SELECTED"; ?>>Appropriate</option>
					    			<option <?php if ($obj['pys_interactions']=="Manipulative") echo " SELECTED"; ?>>Manipulative</option>
					    			<option <?php if ($obj['pys_interactions']=="Transference") echo " SELECTED"; ?>>Transference</option>
					    			<option <?php if ($obj['pys_interactions']=="Argumentative") echo " SELECTED"; ?>>Argumentative</option>
					    			<option <?php if ($obj['pys_interactions']=="Sabotaging") echo " SELECTED"; ?>>Sabotaging</option>
					    			<option <?php if ($obj['pys_interactions']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_riskPrecaution">Ongoing risk precautions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_riskPrecaution" id="psy_riskPrecaution">
					    			<option></option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="Assaultive") echo " SELECTED"; ?>>Assaultive</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="Boundary issues") echo " SELECTED"; ?>>Boundary issues</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="Escalated behaviour") echo " SELECTED"; ?>>Escalated behaviour</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="Fire Setting") echo " SELECTED"; ?>>Fire Setting</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="run") echo " SELECTED"; ?>>run</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="self-injurious behaviour") echo " SELECTED"; ?>>self-injurious behaviour</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="suicidal") echo " SELECTED"; ?>>suicidal</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['psy_riskPrecaution']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Psy_escalated_behavior">Escalated behavior</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="Psy_escalated_behavior" id="Psy_escalated_behavior">
					    			<option></option>
					    			<option <?php if ($obj['Psy_escalated_behavior']=="Distraction techniques") echo " SELECTED"; ?>>Distraction techniques</option>
					    			<option <?php if ($obj['Psy_escalated_behavior']=="limit setting") echo " SELECTED"; ?>>limit setting</option>
					    			<option <?php if ($obj['Psy_escalated_behavior']=="notify provider") echo " SELECTED"; ?>>notify provider</option>
					    			<option <?php if ($obj['Psy_escalated_behavior']=="parental assist") echo " SELECTED"; ?>>parental assist</option>
					    			<option <?php if ($obj['Psy_escalated_behavior']=="re-direction") echo " SELECTED"; ?>>re-direction</option>
					    			<option <?php if ($obj['Psy_escalated_behavior']=="verbal de-escalation") echo " SELECTED"; ?>>verbal de-escalation</option>
					    			<option <?php if ($obj['Psy_escalated_behavior']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
		</div> <!-- end of row -->

		<div class="row">
			<div class="col-sm-6 col-md-3">
				<!-- Cardiovascular system section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Cardiovascular system','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_heartSounds">Heart sounds</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_heartSounds" id="cardio_heartSounds">
					    			<option></option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Active precordium") echo " SELECTED"; ?>>Active precordium</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Click") echo " SELECTED"; ?>>Click</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Continuous") echo " SELECTED"; ?>>Continuous</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Distant") echo " SELECTED"; ?>>Distant</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Gallop") echo " SELECTED"; ?>>Gallop</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Intermittent") echo " SELECTED"; ?>>Intermittent</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Mechanical device") echo " SELECTED"; ?>>Mechanical device</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Muffled") echo " SELECTED"; ?>>Muffled</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Murmur") echo " SELECTED"; ?>>Murmur</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Normal") echo " SELECTED"; ?>>Normal</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Rub") echo " SELECTED"; ?>>Rub</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Split S2") echo " SELECTED"; ?>>Split S2</option>
					    			<option <?php if ($obj['cardio_heartSounds']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_PMI">PMI</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_PMI" id="cardio_PMI">
					    			<option></option>
					    			<option <?php if ($obj['cardio_PMI']=="L Sternal Border") echo " SELECTED"; ?>>L Sternal Border</option>
					    			<option <?php if ($obj['cardio_PMI']=="R sternal border") echo " SELECTED"; ?>>R sternal border</option>
					    			<option <?php if ($obj['cardio_PMI']=="midline") echo " SELECTED"; ?>>midline</option>
					    			<option <?php if ($obj['cardio_PMI']=="oscillator") echo " SELECTED"; ?>>oscillator</option>
					    			<option <?php if ($obj['cardio_PMI']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_PMI']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_rhythm">Rhythm</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_rhythm" id="cardio_rhythm">
					    			<option></option>
					    			<option <?php if ($obj['cardio_rhythm']=="Normal sinus") echo " SELECTED"; ?>>Normal sinus</option>
					    			<option <?php if ($obj['cardio_rhythm']=="regular") echo " SELECTED"; ?>>regular</option>
					    			<option <?php if ($obj['cardio_rhythm']=="irregular") echo " SELECTED"; ?>>irregular</option>
					    			<option <?php if ($obj['cardio_rhythm']=="sinus brady") echo " SELECTED"; ?>>sinus brady</option>
					    			<option <?php if ($obj['cardio_rhythm']=="sinus tach") echo " SELECTED"; ?>>sinus tach</option>
					    			<option <?php if ($obj['cardio_rhythm']=="sinus arryhthmia") echo " SELECTED"; ?>>sinus arryhthmia</option>
					    			<option <?php if ($obj['cardio_rhythm']=="paced") echo " SELECTED"; ?>>paced</option>
					    			<option <?php if ($obj['cardio_rhythm']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_rhythm']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_arrythmia">arrythmia</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_arrythmia" id="cardio_arrythmia">
					    			<option></option>
					    			<option <?php if ($obj['cardio_arrythmia']=="1st degree block") echo " SELECTED"; ?>>1st degree block</option>
					    			<option <?php if ($obj['cardio_arrythmia']=="2nd degree block") echo " SELECTED"; ?>>2nd degree block</option>
					    			<option <?php if ($obj['cardio_arrythmia']=="3rd degree block") echo " SELECTED"; ?>>3rd degree block</option>
					    			<option <?php if ($obj['cardio_arrythmia']=="Aberrant conduction") echo " SELECTED"; ?>>Aberrant conduction</option>
					    			<option <?php if ($obj['cardio_arrythmia']=="atrial arrhythmia") echo " SELECTED"; ?>>atrial arrhythmia</option>
					    			<option <?php if ($obj['cardio_arrythmia']=="atrial flutter") echo " SELECTED"; ?>>atrial flutter</option>
					    			<option <?php if ($obj['cardio_arrythmia']=="bigemeny") echo " SELECTED"; ?>>bigemeny</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_arryfreq">arrythmia frequency</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_arryfreq" id="cardio_arryfreq">
					    			<option></option>
					    			<option <?php if ($obj['cardio_arryfreq']=="Continuous") echo " SELECTED"; ?>>Continuous</option>
					    			<option <?php if ($obj['cardio_arryfreq']=="intermittent") echo " SELECTED"; ?>>intermittent</option>
					    			<option <?php if ($obj['cardio_arryfreq']=="occasional") echo " SELECTED"; ?>>occasional</option>
					    			<option <?php if ($obj['cardio_arryfreq']=="runs of") echo " SELECTED"; ?>>runs of</option>
					    			<option <?php if ($obj['cardio_arryfreq']=="frequent") echo " SELECTED"; ?>>frequent</option>
					    			<option <?php if ($obj['cardio_arryfreq']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_interventions">interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_interventions" id="cardio_interventions">
					    			<option></option>
					    			<option <?php if ($obj['cardio_interventions']=="None-patient tolerating rhythm") echo " SELECTED"; ?>>None-patient tolerating rhythm</option>
					    			<option <?php if ($obj['cardio_interventions']=="A-wire obtained") echo " SELECTED"; ?>>A-wire obtained</option>
					    			<option <?php if ($obj['cardio_interventions']=="Cardioversion") echo " SELECTED"; ?>>Cardioversion</option>
					    			<option <?php if ($obj['cardio_interventions']=="CR Monitoring") echo " SELECTED"; ?>>CR Monitoring</option>
					    			<option <?php if ($obj['cardio_interventions']=="EKG obtained") echo " SELECTED"; ?>>EKG obtained</option>
					    			<option <?php if ($obj['cardio_interventions']=="Electrolytes optimized") echo " SELECTED"; ?>>Electrolytes optimized</option>
					    			<option <?php if ($obj['cardio_interventions']=="Ice to face") echo " SELECTED"; ?>>Ice to face</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crc">cap refill central</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crc" id="cardio_crc">
					    			<option></option>
					    			<option <?php if ($obj['cardio_crc']=="1-8 seconds") echo " SELECTED"; ?>>1-8 seconds</option>
					    			<option <?php if ($obj['cardio_crc']=="8 seconds") echo " SELECTED"; ?>>>8 seconds</option>
					    			<option <?php if ($obj['cardio_crc']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_crc']=="flash") echo " SELECTED"; ?>>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crp">cap refill peripheral</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crp" id="cardio_crp">
									<option></option>
					    			<option <?php if ($obj['cardio_crp']=="1-8 seconds") echo " SELECTED"; ?>>1-8 seconds</option>
					    			<option <?php if ($obj['cardio_crp']=="8 seconds") echo " SELECTED"; ?>>>8 seconds</option>
					    			<option <?php if ($obj['cardio_crp']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_crp']=="flash") echo " SELECTED"; ?>>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crRUE">cap refill RUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crRUE" id="cardio_crRUE">
									<option></option>
					    			<option <?php if ($obj['cardio_crRUE']=="1-8 seconds") echo " SELECTED"; ?>>1-8 seconds</option>
					    			<option <?php if ($obj['cardio_crRUE']=="8 seconds") echo " SELECTED"; ?>>>8 seconds</option>
					    			<option <?php if ($obj['cardio_crRUE']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_crRUE']=="flash") echo " SELECTED"; ?>>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crLUE">cap refill LUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crLUE" id="cardio_crLUE">
									<option></option>
					    			<option <?php if ($obj['cardio_crLUE']=="1-8 seconds") echo " SELECTED"; ?>>1-8 seconds</option>
					    			<option <?php if ($obj['cardio_crLUE']=="8 seconds") echo " SELECTED"; ?>>>8 seconds</option>
					    			<option <?php if ($obj['cardio_crLUE']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_crLUE']=="flash") echo " SELECTED"; ?>>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crRLE">cap refill RLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crRLE" id="cardio_crRLE">
									<option></option>
					    			<option <?php if ($obj['cardio_crRLE']=="1-8 seconds") echo " SELECTED"; ?>>1-8 seconds</option>
					    			<option <?php if ($obj['cardio_crRLE']=="8 seconds") echo " SELECTED"; ?>>>8 seconds</option>
					    			<option <?php if ($obj['cardio_crRLE']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_crRLE']=="flash") echo " SELECTED"; ?>>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crLLE">cap refill LLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crLLE" id="cardio_crLLE">
					    			<option></option>
					    			<option <?php if ($obj['cardio_crLLE']=="Other") echo " SELECTED"; ?>>1-8 seconds</option>
					    			<option <?php if ($obj['cardio_crLLE']=="Other") echo " SELECTED"; ?>>>8 seconds</option>
					    			<option <?php if ($obj['cardio_crLLE']=="Other") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['cardio_crLLE']=="Other") echo " SELECTED"; ?>>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_centralColor">Central Color/Temp</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_centralColor" id="cardio_centralColor">
					    			<option></option>
					    			<option <?php if ($obj['cardio_centralColor']=="Pink, warm, dry") echo " SELECTED"; ?>>Pink, warm, dry</option>
					    			<option <?php if ($obj['cardio_centralColor']=="Bruising") echo " SELECTED"; ?>>Bruising</option>
					    			<option <?php if ($obj['cardio_centralColor']=="Cold") echo " SELECTED"; ?>>Cold</option>
					    			<option <?php if ($obj['cardio_centralColor']=="Cool") echo " SELECTED"; ?>>Cool</option>
					    			<option <?php if ($obj['cardio_centralColor']=="Cyanotic") echo " SELECTED"; ?>>Cyanotic</option>
					    			<option <?php if ($obj['cardio_centralColor']=="Cyanotic lips") echo " SELECTED"; ?>>Cyanotic lips</option>
					    			<option <?php if ($obj['cardio_centralColor']=="diaphoretic") echo " SELECTED"; ?>>diaphoretic</option>
					    			<option <?php if ($obj['cardio_centralColor']=="dry") echo " SELECTED"; ?>>dry</option>
					    			<option <?php if ($obj['cardio_centralColor']=="dusky") echo " SELECTED"; ?>>dusky</option>
					    			<option <?php if ($obj['cardio_centralColor']=="hot") echo " SELECTED"; ?>>hot</option>
					    			<option <?php if ($obj['cardio_centralColor']=="jaundiced") echo " SELECTED"; ?>>jaundiced</option>
					    			<option <?php if ($obj['cardio_centralColor']=="mottled") echo " SELECTED"; ?>>mottled</option>
					    			<option <?php if ($obj['cardio_centralColor']=="pale") echo " SELECTED"; ?>>pale</option>
					    			<option <?php if ($obj['cardio_centralColor']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['cardio_centralColor']=="plethoric") echo " SELECTED"; ?>>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_PeripheralColor">Peripheral Color/Temp</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_PeripheralColor" id="cardio_PeripheralColor">
					    			<option></option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="Pink, warm, dry") echo " SELECTED"; ?>>Pink, warm, dry</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="Bruising") echo " SELECTED"; ?>>Bruising</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="Cold") echo " SELECTED"; ?>>Cold</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="Cool") echo " SELECTED"; ?>>Cool</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="Cyanotic") echo " SELECTED"; ?>>Cyanotic</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="Cyanotic lips") echo " SELECTED"; ?>>Cyanotic lips</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="diaphoretic") echo " SELECTED"; ?>>diaphoretic</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="dry") echo " SELECTED"; ?>>dry</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="dusky") echo " SELECTED"; ?>>dusky</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="hot") echo " SELECTED"; ?>>hot</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="jaundiced") echo " SELECTED"; ?>>jaundiced</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="mottled") echo " SELECTED"; ?>>mottled</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="pale") echo " SELECTED"; ?>>pale</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['cardio_PeripheralColor']=="plethoric") echo " SELECTED"; ?>>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorRUE"> Color/Temp RUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorRUE" id="cardio_colorRUE">
					    			<option ></option>
					    			<option <?php if ($obj['cardio_colorRUE']=="Pink, warm, dry") echo " SELECTED"; ?>>Pink, warm, dry</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="Bruising") echo " SELECTED"; ?>>Bruising</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="Cold") echo " SELECTED"; ?>>Cold</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="Cool") echo " SELECTED"; ?>>Cool</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="Cyanotic") echo " SELECTED"; ?>>Cyanotic</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="Cyanotic lips") echo " SELECTED"; ?>>Cyanotic lips</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="diaphoretic") echo " SELECTED"; ?>>diaphoretic</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="dry") echo " SELECTED"; ?>>dry</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="dusky") echo " SELECTED"; ?>>dusky</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="hot") echo " SELECTED"; ?>>hot</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="jaundiced") echo " SELECTED"; ?>>jaundiced</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="mottled") echo " SELECTED"; ?>>mottled</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="pale") echo " SELECTED"; ?>>pale</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['cardio_colorRUE']=="plethoric") echo " SELECTED"; ?>>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorLUE"> Color/Temp LUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorLUE" id="cardio_colorLUE">
					    			<option ></option>
					    			<option <?php if ($obj['cardio_colorLUE']=="Pink, warm, dry") echo " SELECTED"; ?>>Pink, warm, dry</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="Bruising") echo " SELECTED"; ?>>Bruising</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="Cold") echo " SELECTED"; ?>>Cold</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="Cool") echo " SELECTED"; ?>>Cool</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="Cyanotic") echo " SELECTED"; ?>>Cyanotic</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="Cyanotic lips") echo " SELECTED"; ?>>Cyanotic lips</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="diaphoretic") echo " SELECTED"; ?>>diaphoretic</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="dry") echo " SELECTED"; ?>>dry</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="dusky") echo " SELECTED"; ?>>dusky</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="hot") echo " SELECTED"; ?>>hot</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="jaundiced") echo " SELECTED"; ?>>jaundiced</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="mottled") echo " SELECTED"; ?>>mottled</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="pale") echo " SELECTED"; ?>>pale</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['cardio_colorLUE']=="plethoric") echo " SELECTED"; ?>>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorRLE"> Color/Temp RLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorRLE" id="cardio_colorRLE">
					    			<option></option>
					    			<option <?php if ($obj['cardio_colorRLE']=="Pink, warm, dry") echo " SELECTED"; ?>>Pink, warm, dry</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="Bruising") echo " SELECTED"; ?>>Bruising</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="Cold") echo " SELECTED"; ?>>Cold</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="Cool") echo " SELECTED"; ?>>Cool</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="Cyanotic") echo " SELECTED"; ?>>Cyanotic</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="Cyanotic lips") echo " SELECTED"; ?>>Cyanotic lips</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="diaphoretic") echo " SELECTED"; ?>>diaphoretic</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="dry") echo " SELECTED"; ?>>dry</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="dusky") echo " SELECTED"; ?>>dusky</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="hot") echo " SELECTED"; ?>>hot</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="jaundiced") echo " SELECTED"; ?>>jaundiced</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="mottled") echo " SELECTED"; ?>>mottled</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="pale") echo " SELECTED"; ?>>pale</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['cardio_colorRLE']=="plethoric") echo " SELECTED"; ?>>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorLLE"> Color/Temp LLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorLLE" id="cardio_colorLLE">
					    			<option></option>
					    			<option <?php if ($obj['cardio_colorLLE']=="Pink, warm, dry") echo " SELECTED"; ?>>Pink, warm, dry</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="Bruising") echo " SELECTED"; ?>>Bruising</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="Cold") echo " SELECTED"; ?>>Cold</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="Cool") echo " SELECTED"; ?>>Cool</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="Cyanotic") echo " SELECTED"; ?>>Cyanotic</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="Cyanotic lips") echo " SELECTED"; ?>>Cyanotic lips</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="diaphoretic") echo " SELECTED"; ?>>diaphoretic</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="dry") echo " SELECTED"; ?>>dry</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="dusky") echo " SELECTED"; ?>>dusky</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="hot") echo " SELECTED"; ?>>hot</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="jaundiced") echo " SELECTED"; ?>>jaundiced</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="mottled") echo " SELECTED"; ?>>mottled</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="pale") echo " SELECTED"; ?>>pale</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['cardio_colorLLE']=="plethoric") echo " SELECTED"; ?>>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_centralPulses">Central pulses</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_centralPulses" id="cardio_centralPulses">
					    			<option></option>
					    			<option <?php if ($obj['cardio_centralPulses']=="1") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="2") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="3") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Absent by palpitation") echo " SELECTED"; ?>>Absent by palpitation</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Absent by doppler") echo " SELECTED"; ?>>Absent by doppler</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Bounding") echo " SELECTED"; ?>>Bounding</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Doppler") echo " SELECTED"; ?>>Doppler</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Strong") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Unequal") echo " SELECTED"; ?>>Unequal</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Carotid palpated") echo " SELECTED"; ?>>Carotid palpated</option>
					    			<option <?php if ($obj['cardio_centralPulses']=="Femoral palpated") echo " SELECTED"; ?>>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pheripheralPulses">Pheripheral pulses</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pheripheralPulses" id="cardio_pheripheralPulses">
					    			<option></option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="1") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="2") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="3") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Absent by palpitation") echo " SELECTED"; ?>>Absent by palpitation</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Absent by doppler") echo " SELECTED"; ?>>Absent by doppler</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Bounding") echo " SELECTED"; ?>>Bounding</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Doppler") echo " SELECTED"; ?>>Doppler</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Strong") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Unequal") echo " SELECTED"; ?>>Unequal</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Carotid palpated") echo " SELECTED"; ?>>Carotid palpated</option>
					    			<option <?php if ($obj['cardio_pheripheralPulses']=="Femoral palpated") echo " SELECTED"; ?>>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseRUE">Pulse RUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseRUE" id="cardio_pulseRUE">
					    			<option></option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="1") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="2") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="3") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Absent by palpitation") echo " SELECTED"; ?>>Absent by palpitation</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Absent by doppler") echo " SELECTED"; ?>>Absent by doppler</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Bounding") echo " SELECTED"; ?>>Bounding</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Doppler") echo " SELECTED"; ?>>Doppler</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Strong") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Unequal") echo " SELECTED"; ?>>Unequal</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Carotid palpated") echo " SELECTED"; ?>>Carotid palpated</option>
					    			<option <?php if ($obj['cardio_pulseRUE']=="Femoral palpated") echo " SELECTED"; ?>>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseLUE">Pulse LUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseLUE" id="cardio_pulseLUE">
					    			<option></option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="1") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="2") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="3") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Absent by palpitation") echo " SELECTED"; ?>>Absent by palpitation</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Absent by doppler") echo " SELECTED"; ?>>Absent by doppler</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Bounding") echo " SELECTED"; ?>>Bounding</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Doppler") echo " SELECTED"; ?>>Doppler</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Strong") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Unequal") echo " SELECTED"; ?>>Unequal</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Carotid palpated") echo " SELECTED"; ?>>Carotid palpated</option>
					    			<option <?php if ($obj['cardio_pulseLUE']=="Femoral palpated") echo " SELECTED"; ?>>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseRLE">Pulse RLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseRLE" id="cardio_pulseRUE">
					    			<option></option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="1") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="2") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="3") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Absent by palpitation") echo " SELECTED"; ?>>Absent by palpitation</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Absent by doppler") echo " SELECTED"; ?>>Absent by doppler</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Bounding") echo " SELECTED"; ?>>Bounding</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Doppler") echo " SELECTED"; ?>>Doppler</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Strong") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Unequal") echo " SELECTED"; ?>>Unequal</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Carotid palpated") echo " SELECTED"; ?>>Carotid palpated</option>
					    			<option <?php if ($obj['cardio_pulseRLE']=="Femoral palpated") echo " SELECTED"; ?>>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseLLE">Pulse LLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseLLE" id="cardio_pulseLLE">
					    			<option></option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="1") echo " SELECTED"; ?>>1</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="2") echo " SELECTED"; ?>>2</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="3") echo " SELECTED"; ?>>3</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Absent by palpitation") echo " SELECTED"; ?>>Absent by palpitation</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Absent by doppler") echo " SELECTED"; ?>>Absent by doppler</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Bounding") echo " SELECTED"; ?>>Bounding</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Doppler") echo " SELECTED"; ?>>Doppler</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Equal") echo " SELECTED"; ?>>Equal</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Strong") echo " SELECTED"; ?>>Strong</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Unequal") echo " SELECTED"; ?>>Unequal</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Carotid palpated") echo " SELECTED"; ?>>Carotid palpated</option>
					    			<option <?php if ($obj['cardio_pulseLLE']=="Femoral palpated") echo " SELECTED"; ?>>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
			<div class="col-sm-6 col-md-3">
				<!-- Respiratory system section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Respiratory system','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_pattern">Respiratory pattern</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_pattern" id="resp_pattern">
					    			<option></option>
					    			<option <?php if ($obj['resp_pattern']=="Abdominal") echo " SELECTED"; ?>>Abdominal</option>
					    			<option <?php if ($obj['resp_pattern']=="Agonal") echo " SELECTED"; ?>>Agonal</option>
					    			<option <?php if ($obj['resp_pattern']=="Apena") echo " SELECTED"; ?>>Apena</option>
					    			<option <?php if ($obj['resp_pattern']=="BiPAPA") echo " SELECTED"; ?>>BiPAPA</option>
					    			<option <?php if ($obj['resp_pattern']=="CPAP") echo " SELECTED"; ?>>CPAP</option>
					    			<option <?php if ($obj['resp_pattern']=="Dyspnea") echo " SELECTED"; ?>>Dyspnea</option>
					    			<option <?php if ($obj['resp_pattern']=="Grunting") echo " SELECTED"; ?>>Grunting</option>
					    			<option <?php if ($obj['resp_pattern']=="Labored") echo " SELECTED"; ?>>Labored</option>
					    			<option <?php if ($obj['resp_pattern']=="Irregular") echo " SELECTED"; ?>>Irregular</option>
					    			<option <?php if ($obj['resp_pattern']=="Nasal Flaring") echo " SELECTED"; ?>>Nasal Flaring</option>
					    			<option <?php if ($obj['resp_pattern']=="Oscillator") echo " SELECTED"; ?>>Oscillator</option>
					    			<option <?php if ($obj['resp_pattern']=="Prolonged expiration") echo " SELECTED"; ?>>Prolonged expiration</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_retractions">Retractions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_retractions" id="resp_retractions">
					    			<option></option>
					    			<option <?php if ($obj['resp_retractions']=="None") echo " SELECTED"; ?>>None</option>
					    			<option <?php if ($obj['resp_retractions']=="intercostal") echo " SELECTED"; ?>>intercostal</option>
					    			<option <?php if ($obj['resp_retractions']=="mild") echo " SELECTED"; ?>>mild</option>
					    			<option <?php if ($obj['resp_retractions']=="moderate") echo " SELECTED"; ?>>moderate</option>
					    			<option <?php if ($obj['resp_retractions']=="severe") echo " SELECTED"; ?>>severe</option>
					    			<option <?php if ($obj['resp_retractions']=="subcostal") echo " SELECTED"; ?>>subcostal</option>
					    			<option <?php if ($obj['resp_retractions']=="submandibular") echo " SELECTED"; ?>>submandibular</option>
					    			<option <?php if ($obj['resp_retractions']=="substernal") echo " SELECTED"; ?>>substernal</option>
					    			<option <?php if ($obj['resp_retractions']=="supraclavicular") echo " SELECTED"; ?>>supraclavicular</option>
					    			<option <?php if ($obj['resp_retractions']=="suprasternal") echo " SELECTED"; ?>>suprasternal</option>
					    			<option <?php if ($obj['resp_retractions']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_cough">cough</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_cough" id="resp_cough">
					    			<option></option>
					    			<option <?php if ($obj['resp_cough']=="Barky") echo " SELECTED"; ?>>Barky</option>
					    			<option <?php if ($obj['resp_cough']=="Croupy") echo " SELECTED"; ?>>Croupy</option>
					    			<option <?php if ($obj['resp_cough']=="Dry") echo " SELECTED"; ?>>Dry</option>
					    			<option <?php if ($obj['resp_cough']=="Gag") echo " SELECTED"; ?>>Gag</option>
					    			<option <?php if ($obj['resp_cough']=="Gag not present") echo " SELECTED"; ?>>Gag not present</option>
					    			<option <?php if ($obj['resp_cough']=="Harsh") echo " SELECTED"; ?>>Harsh</option>
					    			<option <?php if ($obj['resp_cough']=="Hemooptysis") echo " SELECTED"; ?>>Hemooptysis</option>
					    			<option <?php if ($obj['resp_cough']=="Loose") echo " SELECTED"; ?>>Loose</option>
					    			<option <?php if ($obj['resp_cough']=="Moist") echo " SELECTED"; ?>>Moist</option>
					    			<option <?php if ($obj['resp_cough']=="Nonproductive") echo " SELECTED"; ?>>Nonproductive</option>
					    			<option <?php if ($obj['resp_cough']=="Occasional") echo " SELECTED"; ?>>Occasional</option>
					    			<option <?php if ($obj['resp_cough']=="Paroxysmal") echo " SELECTED"; ?>>Paroxysmal</option>
					    			<option <?php if ($obj['resp_cough']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_appearance">appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_appearance" id="resp_appearance">
					    			<option></option>
					    			<option <?php if ($obj['resp_appearance']=="Pink") echo " SELECTED"; ?>>Pink</option>
					    			<option <?php if ($obj['resp_appearance']=="Pale") echo " SELECTED"; ?>>Pale</option>
					    			<option <?php if ($obj['resp_appearance']=="Mottled") echo " SELECTED"; ?>>Mottled</option>
					    			<option <?php if ($obj['resp_appearance']=="Cyanotic") echo " SELECTED"; ?>>Cyanotic</option>
					    			<option <?php if ($obj['resp_appearance']=="Dusky") echo " SELECTED"; ?>>Dusky</option>
					    			<option <?php if ($obj['resp_appearance']=="Nailed") echo " SELECTED"; ?>>Nailed</option>
					    			<option <?php if ($obj['resp_appearance']=="Clubbing") echo " SELECTED"; ?>>Clubbing</option>
					    			<option <?php if ($obj['resp_appearance']=="Barrel Chested") echo " SELECTED"; ?>>Barrel Chested</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsallLobes">Breath sounds all lobes</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsallLobes" id="resp_bsallLobes">
					    			<option></option>
					    			<option <?php if ($obj['resp_bsallLobes']=="Clear equal and good aeration bilaterally") echo " SELECTED"; ?>>Clear equal and good aeration bilaterally</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="clear and equal") echo " SELECTED"; ?>>clear and equal</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="Aeration") echo " SELECTED"; ?>>Aeration</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="air leak") echo " SELECTED"; ?>>air leak</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="coarse") echo " SELECTED"; ?>>coarse</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="crackles") echo " SELECTED"; ?>>crackles</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="diminished") echo " SELECTED"; ?>>diminished</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="end expiratory") echo " SELECTED"; ?>>end expiratory</option>
					    			<option <?php if ($obj['resp_bsallLobes']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsRUL">Breath sounds RUL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsRUL" id="resp_bsRUL">
					    			<option></option>
					    			<option <?php if ($obj['resp_bsRUL']=="Clear equal and good aeration bilaterally") echo " SELECTED"; ?>>Clear equal and good aeration bilaterally</option>
					    			<option <?php if ($obj['resp_bsRUL']=="clear and equal") echo " SELECTED"; ?>>clear and equal</option>
					    			<option <?php if ($obj['resp_bsRUL']=="Aeration") echo " SELECTED"; ?>>Aeration</option>
					    			<option <?php if ($obj['resp_bsRUL']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['resp_bsRUL']=="air leak") echo " SELECTED"; ?>>air leak</option>
					    			<option <?php if ($obj['resp_bsRUL']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_bsRUL']=="coarse") echo " SELECTED"; ?>>coarse</option>
					    			<option <?php if ($obj['resp_bsRUL']=="crackles") echo " SELECTED"; ?>>crackles</option>
					    			<option <?php if ($obj['resp_bsRUL']=="diminished") echo " SELECTED"; ?>>diminished</option>
					    			<option <?php if ($obj['resp_bsRUL']=="end expiratory") echo " SELECTED"; ?>>end expiratory</option>
					    			<option <?php if ($obj['resp_bsRUL']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsRML">Breath sounds RML</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsRML" id="resp_bsRML">
					    			<option></option>
					    			<option <?php if ($obj['resp_bsRML']=="Clear equal and good aeration bilaterally") echo " SELECTED"; ?>>Clear equal and good aeration bilaterally</option>
					    			<option <?php if ($obj['resp_bsRML']=="clear and equal") echo " SELECTED"; ?>>clear and equal</option>
					    			<option <?php if ($obj['resp_bsRML']=="Aeration") echo " SELECTED"; ?>>Aeration</option>
					    			<option <?php if ($obj['resp_bsRML']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['resp_bsRML']=="air leak") echo " SELECTED"; ?>>air leak</option>
					    			<option <?php if ($obj['resp_bsRML']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_bsRML']=="coarse") echo " SELECTED"; ?>>coarse</option>
					    			<option <?php if ($obj['resp_bsRML']=="crackles") echo " SELECTED"; ?>>crackles</option>
					    			<option <?php if ($obj['resp_bsRML']=="diminished") echo " SELECTED"; ?>>diminished</option>
					    			<option <?php if ($obj['resp_bsRML']=="end expiratory") echo " SELECTED"; ?>>end expiratory</option>
					    			<option <?php if ($obj['resp_bsRML']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsRLL">Breath sounds RLL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsRLL" id="resp_bsRLL">
					    			<option></option>
					    			<option <?php if ($obj['resp_bsRLL']=="Clear equal and good aeration bilaterally") echo " SELECTED"; ?>>Clear equal and good aeration bilaterally</option>
					    			<option <?php if ($obj['resp_bsRLL']=="clear and equal") echo " SELECTED"; ?>>clear and equal</option>
					    			<option <?php if ($obj['resp_bsRLL']=="Aeration") echo " SELECTED"; ?>>Aeration</option>
					    			<option <?php if ($obj['resp_bsRLL']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['resp_bsRLL']=="air leak") echo " SELECTED"; ?>>air leak</option>
					    			<option <?php if ($obj['resp_bsRLL']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_bsRLL']=="coarse") echo " SELECTED"; ?>>coarse</option>
					    			<option <?php if ($obj['resp_bsRLL']=="crackles") echo " SELECTED"; ?>>crackles</option>
					    			<option <?php if ($obj['resp_bsRLL']=="diminished") echo " SELECTED"; ?>>diminished</option>
					    			<option <?php if ($obj['resp_bsRLL']=="end expiratory") echo " SELECTED"; ?>>end expiratory</option>
					    			<option <?php if ($obj['resp_bsRLL']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsLUL">Breath sounds LUL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsLUL" id="resp_bsLUL">
					    			<option></option>
					    			<option <?php if ($obj['resp_bsLUL']=="Clear equal and good aeration bilaterally") echo " SELECTED"; ?>>Clear equal and good aeration bilaterally</option>
					    			<option <?php if ($obj['resp_bsLUL']=="clear and equal") echo " SELECTED"; ?>>clear and equal</option>
					    			<option <?php if ($obj['resp_bsLUL']=="Aeration") echo " SELECTED"; ?>>Aeration</option>
					    			<option <?php if ($obj['resp_bsLUL']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['resp_bsLUL']=="air leak") echo " SELECTED"; ?>>air leak</option>
					    			<option <?php if ($obj['resp_bsLUL']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_bsLUL']=="coarse") echo " SELECTED"; ?>>coarse</option>
					    			<option <?php if ($obj['resp_bsLUL']=="crackles") echo " SELECTED"; ?>>crackles</option>
					    			<option <?php if ($obj['resp_bsLUL']=="diminished") echo " SELECTED"; ?>>diminished</option>
					    			<option <?php if ($obj['resp_bsLUL']=="end expiratory") echo " SELECTED"; ?>>end expiratory</option>
					    			<option <?php if ($obj['resp_bsLUL']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsLLL">Breath sounds LLL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsLLL" id="resp_bsLLL">
					    			<option></option>
					    			<option <?php if ($obj['resp_bsLLL']=="Clear equal and good aeration bilaterally") echo " SELECTED"; ?>>Clear equal and good aeration bilaterally</option>
					    			<option <?php if ($obj['resp_bsLLL']=="clear and equal") echo " SELECTED"; ?>>clear and equal</option>
					    			<option <?php if ($obj['resp_bsLLL']=="Aeration") echo " SELECTED"; ?>>Aeration</option>
					    			<option <?php if ($obj['resp_bsLLL']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['resp_bsLLL']=="air leak") echo " SELECTED"; ?>>air leak</option>
					    			<option <?php if ($obj['resp_bsLLL']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_bsLLL']=="coarse") echo " SELECTED"; ?>>coarse</option>
					    			<option <?php if ($obj['resp_bsLLL']=="crackles") echo " SELECTED"; ?>>crackles</option>
					    			<option <?php if ($obj['resp_bsLLL']=="diminished") echo " SELECTED"; ?>>diminished</option>
					    			<option <?php if ($obj['resp_bsLLL']=="end expiratory") echo " SELECTED"; ?>>end expiratory</option>
					    			<option <?php if ($obj['resp_bsLLL']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_interventions">Respiratory interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_interventions" id="resp_interventions">
					    			<option></option>
					    			<option <?php if ($obj['resp_interventions']=="BIPAP/CPAP Trial") echo " SELECTED"; ?>>BIPAP/CPAP Trial</option>
					    			<option <?php if ($obj['resp_interventions']=="Chest PT") echo " SELECTED"; ?>>Chest PT</option>
					    			<option <?php if ($obj['resp_interventions']=="Cough") echo " SELECTED"; ?>>Cough</option>
					    			<option <?php if ($obj['resp_interventions']=="Continuous nebulizer") echo " SELECTED"; ?>>Continuous nebulizer</option>
					    			<option <?php if ($obj['resp_interventions']=="Cool mist") echo " SELECTED"; ?>>Cool mist</option>
					    			<option <?php if ($obj['resp_interventions']=="Deep breathing") echo " SELECTED"; ?>>Deep breathing</option>
					    			<option <?php if ($obj['resp_interventions']=="IS") echo " SELECTED"; ?>>IS</option>
					    			<option <?php if ($obj['resp_interventions']=="Intermittent positive pressure") echo " SELECTED"; ?>>Intermittent positive pressure</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_suctiontype">Suctioning type</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_suctiontype" id="resp_suctiontype">
					    			<option></option>
					    			<option <?php if ($obj['resp_suctiontype']=="Closed") echo " SELECTED"; ?>>Closed</option>
					    			<option <?php if ($obj['resp_suctiontype']=="open") echo " SELECTED"; ?>>open</option>
					    			<option <?php if ($obj['resp_suctiontype']=="nasal aspirate") echo " SELECTED"; ?>>nasal aspirate</option>
					    			<option <?php if ($obj['resp_suctiontype']=="deep only") echo " SELECTED"; ?>>deep only</option>
					    			<option <?php if ($obj['resp_suctiontype']=="home") echo " SELECTED"; ?>>home</option>
					    			<option <?php if ($obj['resp_suctiontype']=="bulb") echo " SELECTED"; ?>>bulb</option>
					    			<option <?php if ($obj['resp_suctiontype']=="nasal frida") echo " SELECTED"; ?>>nasal frida</option>
					    			<option <?php if ($obj['resp_suctiontype']=="Deep than other") echo " SELECTED"; ?>>Deep than other</option>
					    			<option <?php if ($obj['resp_suctiontype']=="bulb then other") echo " SELECTED"; ?>>bulb then other</option>
					    			<option <?php if ($obj['resp_suctiontype']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_oralSec">Oral secretions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_oralSec" id="resp_oralSec">
					    			<option></option>
					    			<option <?php if ($obj['resp_oralSec']=="blood tinged") echo " SELECTED"; ?>>blood tinged</option>
					    			<option <?php if ($obj['resp_oralSec']=="brown") echo " SELECTED"; ?>>brown</option>
					    			<option <?php if ($obj['resp_oralSec']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_oralSec']=="cloudy") echo " SELECTED"; ?>>cloudy</option>
					    			<option <?php if ($obj['resp_oralSec']=="copious") echo " SELECTED"; ?>>copious</option>
					    			<option <?php if ($obj['resp_oralSec']=="frank blood") echo " SELECTED"; ?>>frank blood</option>
					    			<option <?php if ($obj['resp_oralSec']=="frothy") echo " SELECTED"; ?>>frothy</option>
					    			<option <?php if ($obj['resp_oralSec']=="green") echo " SELECTED"; ?>>green</option>
					    			<option <?php if ($obj['resp_oralSec']=="large") echo " SELECTED"; ?>>large</option>
					    			<option <?php if ($obj['resp_oralSec']=="moderate") echo " SELECTED"; ?>>moderate</option>
					    			<option <?php if ($obj['resp_oralSec']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['resp_oralSec']=="old blood") echo " SELECTED"; ?>>old blood</option>
					    			<option <?php if ($obj['resp_oralSec']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['resp_oralSec']=="plugs") echo " SELECTED"; ?>>plugs</option>
					    			<option <?php if ($obj['resp_oralSec']=="scant") echo " SELECTED"; ?>>scant</option>
					    			<option <?php if ($obj['resp_oralSec']=="small") echo " SELECTED"; ?>>small</option>
					    			<option <?php if ($obj['resp_oralSec']=="tan") echo " SELECTED"; ?>>tan</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_nasalSec">Nasal secretions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_nasalSec" id="resp_nasalSec">
					    			<option ></option>
					    			<option <?php if ($obj['resp_nasalSec']=="blood tinged") echo " SELECTED"; ?>>blood tinged</option>
					    			<option <?php if ($obj['resp_nasalSec']=="brown") echo " SELECTED"; ?>>brown</option>
					    			<option <?php if ($obj['resp_nasalSec']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_nasalSec']=="cloudy") echo " SELECTED"; ?>>cloudy</option>
					    			<option <?php if ($obj['resp_nasalSec']=="copious") echo " SELECTED"; ?>>copious</option>
					    			<option <?php if ($obj['resp_nasalSec']=="frank blood") echo " SELECTED"; ?>>frank blood</option>
					    			<option <?php if ($obj['resp_nasalSec']=="frothy") echo " SELECTED"; ?>>frothy</option>
					    			<option <?php if ($obj['resp_nasalSec']=="green") echo " SELECTED"; ?>>green</option>
					    			<option <?php if ($obj['resp_nasalSec']=="large") echo " SELECTED"; ?>>large</option>
					    			<option <?php if ($obj['resp_nasalSec']=="moderate") echo " SELECTED"; ?>>moderate</option>
					    			<option <?php if ($obj['resp_nasalSec']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['resp_nasalSec']=="old blood") echo " SELECTED"; ?>>old blood</option>
					    			<option <?php if ($obj['resp_nasalSec']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['resp_nasalSec']=="plugs") echo " SELECTED"; ?>>plugs</option>
					    			<option <?php if ($obj['resp_nasalSec']=="scant") echo " SELECTED"; ?>>scant</option>
					    			<option <?php if ($obj['resp_nasalSec']=="small") echo " SELECTED"; ?>>small</option>
					    			<option <?php if ($obj['resp_nasalSec']=="tan") echo " SELECTED"; ?>>tan</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_airwaySec">Airway secretions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_airwaySec" id="resp_airwaySec">
					    			<option></option>
					    			<option <?php if ($obj['resp_airwaySec']=="blood tinged") echo " SELECTED"; ?>>blood tinged</option>
					    			<option <?php if ($obj['resp_airwaySec']=="brown") echo " SELECTED"; ?>>brown</option>
					    			<option <?php if ($obj['resp_airwaySec']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['resp_airwaySec']=="cloudy") echo " SELECTED"; ?>>cloudy</option>
					    			<option <?php if ($obj['resp_airwaySec']=="copious") echo " SELECTED"; ?>>copious</option>
					    			<option <?php if ($obj['resp_airwaySec']=="frank blood") echo " SELECTED"; ?>>frank blood</option>
					    			<option <?php if ($obj['resp_airwaySec']=="frothy") echo " SELECTED"; ?>>frothy</option>
					    			<option <?php if ($obj['resp_airwaySec']=="green") echo " SELECTED"; ?>>green</option>
					    			<option <?php if ($obj['resp_airwaySec']=="large") echo " SELECTED"; ?>>large</option>
					    			<option <?php if ($obj['resp_airwaySec']=="moderate") echo " SELECTED"; ?>>moderate</option>
					    			<option <?php if ($obj['resp_airwaySec']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['resp_airwaySec']=="old blood") echo " SELECTED"; ?>>old blood</option>
					    			<option <?php if ($obj['resp_airwaySec']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['resp_airwaySec']=="plugs") echo " SELECTED"; ?>>plugs</option>
					    			<option <?php if ($obj['resp_airwaySec']=="scant") echo " SELECTED"; ?>>scant</option>
					    			<option <?php if ($obj['resp_airwaySec']=="small") echo " SELECTED"; ?>>small</option>
					    			<option <?php if ($obj['resp_airwaySec']=="tan") echo " SELECTED"; ?>>tan</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->

			<div class="col-sm-6 col-md-3">
					<!-- section for GI System -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('GI Systems','e'); ?></h3>
					</div>

					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_abdAsssess">Abd assessment</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_abdAsssess" name="GI_abdAsssess">
					    			<option></option>
					    			<option <?php if ($obj['GI_abdAsssess']=="Round") echo " SELECTED"; ?>>Round</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="soft") echo " SELECTED"; ?>>soft</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="ascites") echo " SELECTED"; ?>>ascites</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="concave") echo " SELECTED"; ?>>concave</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="discolored") echo " SELECTED"; ?>>discolored</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="distended") echo " SELECTED"; ?>>distended</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="edematous") echo " SELECTED"; ?>>edematous</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="firm") echo " SELECTED"; ?>>firm</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="flat") echo " SELECTED"; ?>>flat</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="loops visible") echo " SELECTED"; ?>>loops visible</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="loops palpable") echo " SELECTED"; ?>>loops palpable</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="non distended") echo " SELECTED"; ?>>non distended</option>
					    			<option <?php if ($obj['GI_abdAsssess']=="non tender") echo " SELECTED"; ?>>non tender</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_girth">Abd girth (cm)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" value="<?php echo text($obj{"GI_girth"});?>" id="GI_girth" name="GI_girth">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_liverPos">Liver position</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_liverPos" name="GI_liverPos">
					    			<option></option>
					    			<option <?php if ($obj['GI_liverPos']=="Unable to assess") echo " SELECTED"; ?>>Unable to assess</option>
					    			<option <?php if ($obj['GI_liverPos']=="not palpable") echo " SELECTED"; ?>>not palpable</option>
					    			<option <?php if ($obj['GI_liverPos']=="midline") echo " SELECTED"; ?>>midline</option>
					    			<option <?php if ($obj['GI_liverPos']=="right costal margin") echo " SELECTED"; ?>>right costal margin</option>
					    			<option <?php if ($obj['GI_liverPos']=="at umbilicus") echo " SELECTED"; ?>>at umbilicus</option>
					    			<option <?php if ($obj['GI_liverPos']=="firm") echo " SELECTED"; ?>>firm</option>
					    			<option <?php if ($obj['GI_liverPos']=="soft") echo " SELECTED"; ?>>soft</option>
					    			<option <?php if ($obj['GI_liverPos']=="1cm below") echo " SELECTED"; ?>>1cm below</option>
					    			<option <?php if ($obj['GI_liverPos']=="2cm below") echo " SELECTED"; ?>>2cm below</option>
					    			<option <?php if ($obj['GI_liverPos']=="3cm below") echo " SELECTED"; ?>>3cm below</option>
					    			<option <?php if ($obj['GI_liverPos']=="4cm below") echo " SELECTED"; ?>>4cm below</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_symptoms">GI symptoms</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_symptoms" name="GI_symptoms">
					    			<option></option>
					    			<option <?php if ($obj['GI_symptoms']=="Arching") echo " SELECTED"; ?>>Arching</option>
					    			<option <?php if ($obj['GI_symptoms']=="bloating") echo " SELECTED"; ?>>bloating</option>
					    			<option <?php if ($obj['GI_symptoms']=="constipation") echo " SELECTED"; ?>>constipation</option>
					    			<option <?php if ($obj['GI_symptoms']=="cramping") echo " SELECTED"; ?>>cramping</option>
					    			<option <?php if ($obj['GI_symptoms']=="diarrhea") echo " SELECTED"; ?>>diarrhea</option>
					    			<option <?php if ($obj['GI_symptoms']=="epidural") echo " SELECTED"; ?>>epidural</option>
					    			<option <?php if ($obj['GI_symptoms']=="flatus") echo " SELECTED"; ?>>flatus</option>
					    			<option <?php if ($obj['GI_symptoms']=="gagging") echo " SELECTED"; ?>>gagging</option>
					    			<option <?php if ($obj['GI_symptoms']=="guarding") echo " SELECTED"; ?>>guarding</option>
					    			<option <?php if ($obj['GI_symptoms']=="hematuria") echo " SELECTED"; ?>>hematuria</option>
					    			<option <?php if ($obj['GI_symptoms']=="incontinent") echo " SELECTED"; ?>>incontinent</option>
					    			<option <?php if ($obj['GI_symptoms']=="malabsorption") echo " SELECTED"; ?>>malabsorption</option>
					    			<option <?php if ($obj['GI_symptoms']=="nausea") echo " SELECTED"; ?>>nausea</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_interventions">GI interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_interventions" name="GI_interventions">
					    			<option></option>
					    			<option <?php if ($obj['GI_interventions']=="24 hour stool collection") echo " SELECTED"; ?>>24 hour stool collection</option>
					    			<option <?php if ($obj['GI_interventions']=="bowel clean out/prep") echo " SELECTED"; ?>>bowel clean out/prep</option>
					    			<option <?php if ($obj['GI_interventions']=="bowel prep given") echo " SELECTED"; ?>>bowel prep given</option>
					    			<option <?php if ($obj['GI_interventions']=="bowel program") echo " SELECTED"; ?>>bowel program</option>
					    			<option <?php if ($obj['GI_interventions']=="bowel/digital stimulation") echo " SELECTED"; ?>>bowel/digital stimulation</option>
					    			<option <?php if ($obj['GI_interventions']=="flatus") echo " SELECTED"; ?>>flatus</option>
					    			<option <?php if ($obj['GI_interventions']=="diapered") echo " SELECTED"; ?>>diapered</option>
					    			<option <?php if ($obj['GI_interventions']=="enema") echo " SELECTED"; ?>>enema</option>
					    			<option <?php if ($obj['GI_interventions']=="feeds") echo " SELECTED"; ?>>feeds</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsAllQuad">Bowel sounds-all quadrants</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsAllQuad" name="GI_bsAllQuad">
					    			<option></option>
					    			<option <?php if ($obj['GI_bsAllQuad']=="active") echo " SELECTED"; ?>>active</option>
					    			<option <?php if ($obj['GI_bsAllQuad']=="Hyperactive") echo " SELECTED"; ?>>Hyperactive</option>
					    			<option <?php if ($obj['GI_bsAllQuad']=="Hypoactive") echo " SELECTED"; ?>>Hypoactive</option>
					    			<option <?php if ($obj['GI_bsAllQuad']=="High pitched") echo " SELECTED"; ?>>High pitched</option>
					    			<option <?php if ($obj['GI_bsAllQuad']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['GI_bsAllQuad']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['GI_bsAllQuad']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsRUG">BS RUQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsRUG" name="GI_bsRUG">
					    			<option></option>
					    			<option <?php if ($obj['GI_bsRUG']=="active") echo " SELECTED"; ?>>active</option>
					    			<option <?php if ($obj['GI_bsRUG']=="Hyperactive") echo " SELECTED"; ?>>Hyperactive</option>
					    			<option <?php if ($obj['GI_bsRUG']=="Hypoactive") echo " SELECTED"; ?>>Hypoactive</option>
					    			<option <?php if ($obj['GI_bsRUG']=="High pitched") echo " SELECTED"; ?>>High pitched</option>
					    			<option <?php if ($obj['GI_bsRUG']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['GI_bsRUG']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['GI_bsRUG']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsRLG">BS RLQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsRLG" name="GI_bsRLG">
					    			<option></option>
					    			<option <?php if ($obj['GI_bsRLG']=="active") echo " SELECTED"; ?>>active</option>
					    			<option <?php if ($obj['GI_bsRLG']=="Hyperactive") echo " SELECTED"; ?>>Hyperactive</option>
					    			<option <?php if ($obj['GI_bsRLG']=="Hypoactive") echo " SELECTED"; ?>>Hypoactive</option>
					    			<option <?php if ($obj['GI_bsRLG']=="High pitched") echo " SELECTED"; ?>>High pitched</option>
					    			<option <?php if ($obj['GI_bsRLG']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['GI_bsRLG']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['GI_bsRLG']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsLUG">BS LUQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsLUG" name="GI_bsLUG">
					    			<option></option>
					    			<option <?php if ($obj['GI_bsLUG']=="active") echo " SELECTED"; ?>>active</option>
					    			<option <?php if ($obj['GI_bsLUG']=="Hyperactive") echo " SELECTED"; ?>>Hyperactive</option>
					    			<option <?php if ($obj['GI_bsLUG']=="Hypoactive") echo " SELECTED"; ?>>Hypoactive</option>
					    			<option <?php if ($obj['GI_bsLUG']=="High pitched") echo " SELECTED"; ?>>High pitched</option>
					    			<option <?php if ($obj['GI_bsLUG']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['GI_bsLUG']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['GI_bsLUG']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsLLG">BS LLQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsLLG" name="GI_bsLLG">
					    			<option></option>
					    			<option <?php if ($obj['GI_bsLLG']=="active") echo " SELECTED"; ?>>active</option>
					    			<option <?php if ($obj['GI_bsLLG']=="Hyperactive") echo " SELECTED"; ?>>Hyperactive</option>
					    			<option <?php if ($obj['GI_bsLLG']=="Hypoactive") echo " SELECTED"; ?>>Hypoactive</option>
					    			<option <?php if ($obj['GI_bsLLG']=="High pitched") echo " SELECTED"; ?>>High pitched</option>
					    			<option <?php if ($obj['GI_bsLLG']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['GI_bsLLG']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['GI_bsLLG']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_epigastric">Epigastric</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_epigastric" name="GI_epigastric">
					    			<option></option>
					    			<option <?php if ($obj['GI_epigastric']=="active") echo " SELECTED"; ?>>active</option>
					    			<option <?php if ($obj['GI_epigastric']=="Hyperactive") echo " SELECTED"; ?>>Hyperactive</option>
					    			<option <?php if ($obj['GI_epigastric']=="Hypoactive") echo " SELECTED"; ?>>Hypoactive</option>
					    			<option <?php if ($obj['GI_epigastric']=="High pitched") echo " SELECTED"; ?>>High pitched</option>
					    			<option <?php if ($obj['GI_epigastric']=="absent") echo " SELECTED"; ?>>absent</option>
					    			<option <?php if ($obj['GI_epigastric']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['GI_epigastric']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
					</div> <!--  end of panel body -->
				</div> <!-- end of panel primary -->
			</div><!--  end of col-4 -->
			<div class="col-sm-6 col-md-3">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('GU system','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GU_symptoms">GU symptoms</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GU_symptoms" name="GU_symptoms">
					    			<option></option>
					    			<option <?php if ($obj['GU_symptoms']=="Burning") echo " SELECTED"; ?>>Burning</option>
					    			<option <?php if ($obj['GU_symptoms']=="Frequency") echo " SELECTED"; ?>>Frequency</option>
					    			<option <?php if ($obj['GU_symptoms']=="Distended") echo " SELECTED"; ?>>Distended</option>
					    			<option <?php if ($obj['GU_symptoms']=="Dysuria") echo " SELECTED"; ?>>Dysuria</option>
					    			<option <?php if ($obj['GU_symptoms']=="Fullness") echo " SELECTED"; ?>>Fullness</option>
					    			<option <?php if ($obj['GU_symptoms']=="Hematuria") echo " SELECTED"; ?>>Hematuria</option>
					    			<option <?php if ($obj['GU_symptoms']=="Incontinent") echo " SELECTED"; ?>>Incontinent</option>
					    			<option <?php if ($obj['GU_symptoms']=="Oliguria") echo " SELECTED"; ?>>Oliguria</option>
					    			<option <?php if ($obj['GU_symptoms']=="Unable to void") echo " SELECTED"; ?>>Unable to void</option>
					    			<option <?php if ($obj['GU_symptoms']=="Urgency") echo " SELECTED"; ?>>Urgency</option>
					    			<option <?php if ($obj['GU_symptoms']=="Spasms") echo " SELECTED"; ?>>Spasms</option>
					    			<option <?php if ($obj['GU_symptoms']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GU_interventions">GU interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GU_interventions" name="GU_interventions">
					    			<option></option>
					    			<option <?php if ($obj['GU_interventions']=="24 hour urine collection") echo " SELECTED"; ?>>24 hour urine collection</option>
					    			<option <?php if ($obj['GU_interventions']=="bladder crede") echo " SELECTED"; ?>>bladder crede</option>
					    			<option <?php if ($obj['GU_interventions']=="catheter irrigated") echo " SELECTED"; ?>>catheter irrigated</option>
					    			<option <?php if ($obj['GU_interventions']=="diapered") echo " SELECTED"; ?>>diapered</option>
					    			<option <?php if ($obj['GU_interventions']=="indwelling catheter") echo " SELECTED"; ?>>indwelling catheter</option>
					    			<option <?php if ($obj['GU_interventions']=="Medicated") echo " SELECTED"; ?>>Medicated</option>
					    			<option <?php if ($obj['GU_interventions']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['GU_interventions']=="self catheter") echo " SELECTED"; ?>>self catheter</option>
					    			<option <?php if ($obj['GU_interventions']=="specimen") echo " SELECTED"; ?>>specimen</option>
					    			<option <?php if ($obj['GU_interventions']=="Urgency") echo " SELECTED"; ?>>Urgency</option>
					    			<option <?php if ($obj['GU_interventions']=="Spasms") echo " SELECTED"; ?>>Spasms</option>
					    			<option <?php if ($obj['GU_interventions']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GU_bladderScan">Bladder scan residual amount (ml)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" value="<?php echo text($obj{"GU_bladderScan"});?>" id="GU_bladderScan" name="GU_bladderScan">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GU_drainage">perineal drainage</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GU_drainage" name="GU_drainage">
					    			<option></option>
					    			<option <?php if ($obj['GU_drainage']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['GU_drainage']=="small") echo " SELECTED"; ?>>small</option>
					    			<option <?php if ($obj['GU_drainage']=="moderate") echo " SELECTED"; ?>>moderate</option>
					    			<option <?php if ($obj['GU_drainage']=="large") echo " SELECTED"; ?>>large</option>
					    			<option <?php if ($obj['GU_drainage']=="blood clots") echo " SELECTED"; ?>>blood clots</option>
					    			<option <?php if ($obj['GU_drainage']=="blood tinged") echo " SELECTED"; ?>>blood tinged</option>
					    			<option <?php if ($obj['GU_drainage']=="brown") echo " SELECTED"; ?>>brown</option>
					    			<option <?php if ($obj['GU_drainage']=="clear") echo " SELECTED"; ?>>clear</option>
					    			<option <?php if ($obj['GU_drainage']=="cloudy") echo " SELECTED"; ?>>cloudy</option>
					    			<option <?php if ($obj['GU_drainage']=="curdlike") echo " SELECTED"; ?>>curdlike</option>
					    			<option <?php if ($obj['GU_drainage']=="foul smelling") echo " SELECTED"; ?>>foul smelling</option>
					    			<option <?php if ($obj['GU_drainage']=="frank blood") echo " SELECTED"; ?>>frank blood</option>
					    			<option <?php if ($obj['GU_drainage']=="green") echo " SELECTED"; ?>>green</option>
					    			<option <?php if ($obj['GU_drainage']=="milky white") echo " SELECTED"; ?>>milky white</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="GU_genitalia">describe genitalia</label>
							<div class="col-sm-6">
					    		<textarea  class="form-control" id="GU_genitalia" name="GU_genitalia"><?php echo text($obj{"GU_genitalia"});?> </textarea>
					    	</div>
						</div>
					</div> <!--  end of panel body -->
				</div> <!-- end of panel primary -->
			</div><!--  end of col-4 -->

		</div> <!-- end  of rows -->

		<div class="row">
			<div class="col-sm-6 col-md-3">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('MSK/ORTHO system','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_dlocation">Deficit Location</label>
					    	<div class="col-sm-6">
					    		<input type="text" class="form-control" id="msk_dlocation" value="<?php echo text($obj{"msk_dlocation"});?>" name="msk_dlocation">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_glocation">girth/circum location</label>
					    	<div class="col-sm-6">
					    		<input type="text" class="form-control" id="msk_glocation" value="<?php echo text($obj{"msk_glocation"});?>" name="msk_glocation">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_gcircum">girth/circumference (cm) </label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="msk_gcircum" value="<?php echo text($obj{"msk_gcircum"});?>" name="msk_gcircum">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_muscle">Muscle tone</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_muscle" name="msk_muscle">
					    			<option></option>
					    			<option <?php if ($obj['msk_muscle']=="Normal") echo " SELECTED"; ?>>Normal</option>
					    			<option <?php if ($obj['msk_muscle']=="hypotonic") echo " SELECTED"; ?>>hypotonic</option>
					    			<option <?php if ($obj['msk_muscle']=="hypertonic") echo " SELECTED"; ?>>hypertonic</option>
					    			<option <?php if ($obj['msk_muscle']=="R greater than L") echo " SELECTED"; ?>>R greater than L</option>
					    			<option <?php if ($obj['msk_muscle']=="L greater than R") echo " SELECTED"; ?>>L greater than R</option>
					    			<option <?php if ($obj['msk_muscle']=="Spastic") echo " SELECTED"; ?>>Spastic</option>
					    			<option <?php if ($obj['msk_muscle']=="Flaccid") echo " SELECTED"; ?>>Flaccid</option>
					    			<option <?php if ($obj['msk_muscle']=="Weak") echo " SELECTED"; ?>>Weak</option>
					    			<option <?php if ($obj['msk_muscle']=="Medically paralyzed") echo " SELECTED"; ?>>Medically paralyzed</option>
					    			<option <?php if ($obj['msk_muscle']=="Other") echo " SELECTED"; ?>>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_motion">motion</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_motion" name="msk_motion">
					    			<option></option>
					    			<option <?php if ($obj['msk_motion']=="Full ROM") echo " SELECTED"; ?>>Full ROM</option>
					    			<option <?php if ($obj['msk_motion']=="Limited") echo " SELECTED"; ?>>Limited</option>
					    			<option <?php if ($obj['msk_motion']=="Stiff") echo " SELECTED"; ?>>Stiff</option>
					    			<option <?php if ($obj['msk_motion']=="Contractures") echo " SELECTED"; ?>>Contractures</option>
					    			<option <?php if ($obj['msk_motion']=="Immobile") echo " SELECTED"; ?>>Immobile</option>
					    			<option <?php if ($obj['msk_motion']=="Posturing") echo " SELECTED"; ?>>Posturing</option>
					    			<option <?php if ($obj['msk_motion']=="L foot drag") echo " SELECTED"; ?>>L foot drag</option>
					    			<option <?php if ($obj['msk_motion']=="R foot drag") echo " SELECTED"; ?>>R foot drag</option>
					    			<option <?php if ($obj['msk_motion']=="L hemiparesis") echo " SELECTED"; ?>>L hemiparesis</option>
					    			<option <?php if ($obj['msk_motion']=="R hemiparesis") echo " SELECTED"; ?>>R hemiparesis</option>
					    			<option <?php if ($obj['msk_motion']=="Limp") echo " SELECTED"; ?>>Limp</option>
					    			<option <?php if ($obj['msk_motion']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_appearance">Appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_appearance" name="msk_appearance">
					    			<option></option>
					    			<option <?php if ($obj['msk_appearance']=="Normal") echo " SELECTED"; ?>>Normal</option>
					    			<option <?php if ($obj['msk_appearance']=="red") echo " SELECTED"; ?>>red</option>
					    			<option <?php if ($obj['msk_appearance']=="Swollen") echo " SELECTED"; ?>>Swollen</option>
					    			<option <?php if ($obj['msk_appearance']=="red") echo " SELECTED"; ?>>red</option>
					    			<option <?php if ($obj['msk_appearance']=="tender") echo " SELECTED"; ?>>tender</option>
					    			<option <?php if ($obj['msk_appearance']=="contractures") echo " SELECTED"; ?>>contractures</option>
					    			<option <?php if ($obj['msk_appearance']=="casted") echo " SELECTED"; ?>>casted</option>
					    			<option <?php if ($obj['msk_appearance']=="deformity") echo " SELECTED"; ?>>deformity</option>
					    			<option <?php if ($obj['msk_appearance']=="injury/trauma") echo " SELECTED"; ?>>injury/trauma</option>
					    			<option <?php if ($obj['msk_appearance']=="laceration") echo " SELECTED"; ?>>laceration</option>
					    			<option <?php if ($obj['msk_appearance']=="unable to assess") echo " SELECTED"; ?>>unable to assess</option>
					    			<option <?php if ($obj['msk_appearance']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_support">Support device</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_support" name="msk_support">
					    			<option></option>
					    			<option <?php if ($obj['msk_support']=="On") echo " SELECTED"; ?>>On</option>
					    			<option <?php if ($obj['msk_support']=="off") echo " SELECTED"; ?>>off</option>
					    			<option <?php if ($obj['msk_support']=="abductor pillow") echo " SELECTED"; ?>>abductor pillow</option>
					    			<option <?php if ($obj['msk_support']=="ACE wrap") echo " SELECTED"; ?>>ACE wrap</option>
					    			<option <?php if ($obj['msk_support']=="brace") echo " SELECTED"; ?>>brace</option>
					    			<option <?php if ($obj['msk_support']=="sling") echo " SELECTED"; ?>>sling</option>
					    			<option <?php if ($obj['msk_support']=="splint") echo " SELECTED"; ?>>splint</option>
					    			<option <?php if ($obj['msk_support']=="cast") echo " SELECTED"; ?>>cast</option>
					    			<option <?php if ($obj['msk_support']=="spica") echo " SELECTED"; ?>>spica</option>
					    			<option <?php if ($obj['msk_support']=="petal cast") echo " SELECTED"; ?>>petal cast</option>
					    			<option <?php if ($obj['msk_support']=="IV pole") echo " SELECTED"; ?>>IV pole</option>
					    			<option <?php if ($obj['msk_support']=="Ice pack") echo " SELECTED"; ?>>Ice pack</option>
					    			<option <?php if ($obj['msk_support']=="pillows") echo " SELECTED"; ?>>pillows</option>
					    			<option <?php if ($obj['msk_support']=="C Collar") echo " SELECTED"; ?>>C Collar</option>
					    			<option <?php if ($obj['msk_support']=="Bucks Traction") echo " SELECTED"; ?>>Bucks Traction</option>
					    			<option <?php if ($obj['msk_support']=="Hare") echo " SELECTED"; ?>>Hare</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_weight">Weight bearing</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_weight" name="msk_weight">
					    			<option></option>
					    			<option <?php if ($obj['msk_weight']=="Total") echo " SELECTED"; ?>>Total</option>
					    			<option <?php if ($obj['msk_weight']=="toe touch") echo " SELECTED"; ?>>toe touch</option>
					    			<option <?php if ($obj['msk_weight']=="partial") echo " SELECTED"; ?>>partial</option>
					    			<option <?php if ($obj['msk_weight']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['msk_weight']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="msk_degree">CPM degree</label>
							<div class="col-sm-6">
						    	<textarea class="form-control" id="msk_degree" name="msk_degree"> <?php echo text($obj{"msk_degree"});?></textarea>
						    </div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_state">CPM on/off</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_state" name="msk_state">
					    			<option></option>
					    			<option <?php if ($obj['msk_state']=="on") echo " SELECTED"; ?>>on</option>
					    			<option <?php if ($obj['msk_state']=="off") echo " SELECTED"; ?>>off</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_traction">Traction (lbs)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="msk_traction" value="<?php echo text($obj{"msk_traction"});?>" name="msk_traction">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_cap">cap refill</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="msk_cap" value="<?php echo text($obj{"msk_cap"});?>" name="msk_cap">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_assessment"> Traction assessment</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_assessment" name="msk_assessment">
					    			<option></option>
					    			<option <?php if ($obj['msk_assessment']=="Weight hanging freely") echo " SELECTED"; ?>>Weight hanging freely</option>
					    			<option <?php if ($obj['msk_assessment']=="Limb in neutral position") echo " SELECTED"; ?>>Limb in neutral position</option>
					    			<option <?php if ($obj['msk_assessment']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_sensation"> motion sensation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_sensation" name="msk_sensation">
					    			<option></option>
					    			<option <?php if ($obj['msk_sensation']=="Full spontaneous") echo " SELECTED"; ?>>Full spontaneous</option>
					    			<option <?php if ($obj['msk_sensation']=="moves fingers/toes") echo " SELECTED"; ?>>moves fingers/toes</option>
					    			<option <?php if ($obj['msk_sensation']=="responds to light touch") echo " SELECTED"; ?>>responds to light touch</option>
					    			<option <?php if ($obj['msk_sensation']=="hypersensitve") echo " SELECTED"; ?>>hypersensitve</option>
					    			<option <?php if ($obj['msk_sensation']=="hyperreflexive") echo " SELECTED"; ?>>hyperreflexive</option>
					    			<option <?php if ($obj['msk_sensation']=="Dorsi flex") echo " SELECTED"; ?>>Dorsi flex</option>
					    			<option <?php if ($obj['msk_sensation']=="Plantar flex") echo " SELECTED"; ?>>Plantar flex</option>
					    			<option <?php if ($obj['msk_sensation']=="no movement") echo " SELECTED"; ?>>no movement</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_extColorT">extremity color/temp</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_extColorT" name="msk_extColorT">
					    			<option></option>
					    			<option <?php if ($obj['msk_extColorT']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['msk_extColorT']=="Pale") echo " SELECTED"; ?>>Pale</option>
					    			<option <?php if ($obj['msk_extColorT']=="flushed") echo " SELECTED"; ?>>flushed</option>
					    			<option <?php if ($obj['msk_extColorT']=="mottled") echo " SELECTED"; ?>>mottled</option>
					    			<option <?php if ($obj['msk_extColorT']=="warm") echo " SELECTED"; ?>>warm</option>
					    			<option <?php if ($obj['msk_extColorT']=="cool") echo " SELECTED"; ?>>cool</option>
					    			<option <?php if ($obj['msk_extColorT']=="other") echo " SELECTED"; ?>>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_intervention">CMS check intervention</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_intervention" name="msk_intervention">
					    			<option></option>
					    			<option <?php if ($obj['msk_intervention']=="MD notified") echo " SELECTED"; ?>>MD notified</option>
					    			<option <?php if ($obj['msk_intervention']=="Elevated") echo " SELECTED"; ?>>Elevated</option>
					    			<option <?php if ($obj['msk_intervention']=="Reposition") echo " SELECTED"; ?>>Reposition</option>
					    			<option <?php if ($obj['msk_intervention']=="Ice applied") echo " SELECTED"; ?>>Ice applied</option>
					    			<option <?php if ($obj['msk_intervention']=="Cast splint") echo " SELECTED"; ?>>Cast splint</option>
					    		</select>
					    	</div>
						</div>

					</div> <!--  end of panel body -->
				</div> <!-- end of panel primary -->
			</div> <!-- end of col -->
			<div class="col-sm-6 col-md-3">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Skin System','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_assessment">Skin assessment</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_assessment" name="skin_assessment">
					    			<option></option>
					    			<option <?php if ($obj['skin_assessment']=="Skin folds checked") echo " SELECTED"; ?>>Skin folds checked</option>
					    			<option <?php if ($obj['skin_assessment']=="Posterior surfaces checked") echo " SELECTED"; ?>>Posterior surfaces checked</option>
					    			<option <?php if ($obj['skin_assessment']=="mucous membranes intact") echo " SELECTED"; ?>>mucous membranes intact</option>
					    			<option <?php if ($obj['skin_assessment']=="nail beds intact") echo " SELECTED"; ?>>nail beds intact</option>
					    			<option <?php if ($obj['skin_assessment']=="appropriate for ethnicity") echo " SELECTED"; ?>>appropriate for ethnicity</option>
					    			<option <?php if ($obj['skin_assessment']=="pink") echo " SELECTED"; ?>>pink</option>
					    			<option <?php if ($obj['skin_assessment']=="casted") echo " SELECTED"; ?>>casted</option>
					    			<option <?php if ($obj['skin_assessment']=="warm") echo " SELECTED"; ?>>warm</option>
					    			<option <?php if ($obj['skin_assessment']=="dry") echo " SELECTED"; ?>>dry</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_location">Edema location</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_location" name="skin_location"><?php echo text($obj{"skin_location"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_description">Edema description </label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_description" name="skin_description">
					    			<option <?php if ($obj['skin_description']=="Cold") echo " SELECTED"; ?>>Cold</option>
					    			<option <?php if ($obj['skin_description']=="Warm") echo " SELECTED"; ?>>Warm</option>
					    			<option <?php if ($obj['skin_description']=="Hot") echo " SELECTED"; ?>>Hot</option>
					    			<option <?php if ($obj['skin_description']=="Acute") echo " SELECTED"; ?>>Acute</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_rlocation">Rash location</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_rlocation" name="skin_rlocation"><?php echo text($obj{"skin_rlocation"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_rdescription">Rash description</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_rdescription" name="skin_rdescription">
					    			<option></option>
					    			<option <?php if ($obj['skin_rdescription']=="Blisters") echo " SELECTED"; ?>> Blisters</option>
					    			<option <?php if ($obj['skin_rdescription']=="Blotchy") echo " SELECTED"; ?>>Blotchy</option>
					    			<option <?php if ($obj['skin_rdescription']=="Bright red") echo " SELECTED"; ?>>Bright red</option>
					    			<option <?php if ($obj['skin_rdescription']=="Coarse") echo " SELECTED"; ?>>Coarse</option>
					    			<option <?php if ($obj['skin_rdescription']=="Cracked") echo " SELECTED"; ?>>Cracked</option>
					    			<option <?php if ($obj['skin_rdescription']=="Crusted") echo " SELECTED"; ?>>Crusted</option>
					    			<option <?php if ($obj['skin_rdescription']=="Diffuse") echo " SELECTED"; ?>>Diffuse</option>
					    			<option <?php if ($obj['skin_rdescription']=="Dry") echo " SELECTED"; ?>>Dry</option>
					    			<option <?php if ($obj['skin_rdescription']=="Fine") echo " SELECTED"; ?>>Fine</option>
					    			<option <?php if ($obj['skin_rdescription']=="Flat") echo " SELECTED"; ?>>Flat</option>
					    			<option <?php if ($obj['skin_rdescription']=="irritated") echo " SELECTED"; ?>>irritated</option>
					    			<option <?php if ($obj['skin_rdescription']=="Itchy") echo " SELECTED"; ?>>Itchy</option>
					    			<option <?php if ($obj['skin_rdescription']=="Lacy") echo " SELECTED"; ?>>Lacy</option>
					    			<option <?php if ($obj['skin_rdescription']=="Large bumps") echo " SELECTED"; ?>>Large bumps</option>
					    			<option <?php if ($obj['skin_rdescription']=="large patches") echo " SELECTED"; ?>>large patches</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_appearance">Appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_appearance" name="skin_appearance">
					    			<option></option>
					    			<option <?php if ($obj['skin_appearance']=="adipose tissue") echo " SELECTED"; ?>>adipose tissue</option>
					    			<option <?php if ($obj['skin_appearance']=="approximated") echo " SELECTED"; ?>>approximated</option>
					    			<option <?php if ($obj['skin_appearance']=="bleeding") echo " SELECTED"; ?>>bleeding</option>
					    			<option <?php if ($obj['skin_appearance']=="blistered") echo " SELECTED"; ?>>blistered</option>
					    			<option <?php if ($obj['skin_appearance']=="crepitus") echo " SELECTED"; ?>>crepitus</option>
					    			<option <?php if ($obj['skin_appearance']=="dehisced") echo " SELECTED"; ?>>dehisced</option>
					    			<option <?php if ($obj['skin_appearance']=="dressing clean, dry, intact") echo " SELECTED"; ?>>dressing clean, dry, intact</option>
					    			<option <?php if ($obj['skin_appearance']=="dressing with drainage") echo " SELECTED"; ?>>dressing with drainage</option>
					    			<option <?php if ($obj['skin_appearance']=="dry") echo " SELECTED"; ?>>dry</option>
					    			<option <?php if ($obj['skin_appearance']=="dusky") echo " SELECTED"; ?>>dusky</option>
					    			<option <?php if ($obj['skin_appearance']=="EB") echo " SELECTED"; ?>>EB</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_lassessement">Laceration assessment</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_lassessement" name="skin_lassessement"><?php echo text($obj{"skin_lassessement"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_NBlocation">Nodule/Bump location</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_NBlocation" name="skin_NBlocation"><?php echo text($obj{"skin_NBlocation"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_wlocation">Wound location</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_wlocation" name="skin_wlocation"><?php echo text($obj{"skin_wlocation"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_NBdescription">Nodule/Bump description</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_NBdescription" name="skin_NBdescription">
					    			<option></option>
					    			<option <?php if ($obj['skin_NBdescription']=="Pink") echo " SELECTED"; ?>>Pink</option>
					    			<option <?php if ($obj['skin_NBdescription']=="red") echo " SELECTED"; ?>>red</option>
					    			<option <?php if ($obj['skin_NBdescription']=="oozing") echo " SELECTED"; ?>>oozing</option>
					    			<option <?php if ($obj['skin_NBdescription']=="hard") echo " SELECTED"; ?>>hard</option>
					    			<option <?php if ($obj['skin_NBdescription']=="cursted") echo " SELECTED"; ?>>cursted</option>
					    			<option <?php if ($obj['skin_NBdescription']=="warm") echo " SELECTED"; ?>>warm</option>
					    			<option <?php if ($obj['skin_NBdescription']=="hot") echo " SELECTED"; ?>>hot</option>
					    			<option <?php if ($obj['skin_NBdescription']=="streaking") echo " SELECTED"; ?>>streaking</option>
					    			<option <?php if ($obj['skin_NBdescription']=="non-streaking") echo " SELECTED"; ?>>non-streaking</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_TfirstNoticed">Time first noticed</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_TfirstNoticed" name="skin_TfirstNoticed"><?php echo text($obj{"skin_TfirstNoticed"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_DfirstNoticed">Date first noticed</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_DfirstNoticed" name="skin_DfirstNoticed"><?php echo text($obj{"skin_DfirstNoticed"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_DfirstdressingApp">Date Dressing applied/changed</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_DfirstdressingApp" name="skin_DfirstdressingApp"><?php echo text($obj{"skin_DfirstdressingApp"});?></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_intervention">skin interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_intervention" name="skin_intervention">
					    			<option></option>
					    			<option <?php if ($obj['skin_intervention']=="Dresssing applied") echo " SELECTED"; ?>>Dresssing applied</option>
					    			<option <?php if ($obj['skin_intervention']=="Dressing Changed") echo " SELECTED"; ?>>Dressing Changed</option>
					    			<option <?php if ($obj['skin_intervention']=="cleaned") echo " SELECTED"; ?>>cleaned</option>
					    			<option <?php if ($obj['skin_intervention']=="medication applied") echo " SELECTED"; ?>>medication applied</option>
					    			<option <?php if ($obj['skin_intervention']=="MD notified") echo " SELECTED"; ?>>MD notified</option>
					    			<option <?php if ($obj['skin_intervention']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['skin_intervention']=="antibiotic started") echo " SELECTED"; ?>>antibiotic started</option>
					    			<option <?php if ($obj['skin_intervention']=="compression garment") echo " SELECTED"; ?>>compression garment</option>
					    			<option <?php if ($obj['skin_intervention']=="consult") echo " SELECTED"; ?>>consult</option>

					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_dressing">Dressing</label>
							<div class="col-sm-6">
					    		<select class="form-control" id="skin_dressing" name="skin_dressing">
					    			<option></option>
					    			<option <?php if ($obj['skin_dressing']=="Wet to dry") echo " SELECTED"; ?>>Wet to dry</option>
					    			<option <?php if ($obj['skin_dressing']=="Moist") echo " SELECTED"; ?>>Moist</option>
					    			<option <?php if ($obj['skin_dressing']=="tegaderm") echo " SELECTED"; ?>>tegaderm</option>
					    			<option <?php if ($obj['skin_dressing']=="mepore") echo " SELECTED"; ?>>mepore</option>
					    			<option <?php if ($obj['skin_dressing']=="mepilex border") echo " SELECTED"; ?>>mepilex border</option>
					    			<option <?php if ($obj['skin_dressing']=="mepilex border lite") echo " SELECTED"; ?>>mepilex border lite</option>
					    			<option <?php if ($obj['skin_dressing']=="mepilex sacral border") echo " SELECTED"; ?>>mepilex sacral border</option>
					    			<option <?php if ($obj['skin_dressing']=="douderm") echo " SELECTED"; ?>>douderm</option>
					    			<option <?php if ($obj['skin_dressing']=="sorbaview") echo " SELECTED"; ?>>sorbaview</option>
					    			<option <?php if ($obj['skin_dressing']=="tape") echo " SELECTED"; ?>>tape</option>
					    			<option <?php if ($obj['skin_dressing']=="gauze") echo " SELECTED"; ?>>gauze</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_drainage">Drainage</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_drainage" name="skin_drainage">
					    			<option></option>
					    			<option <?php if ($obj['skin_drainage']=="Small") echo " SELECTED"; ?>>Small</option>
					    			<option <?php if ($obj['skin_drainage']=="Clear") echo " SELECTED"; ?>>Clear</option>
					    			<option <?php if ($obj['skin_drainage']=="copious") echo " SELECTED"; ?>>copious</option>
					    			<option <?php if ($obj['skin_drainage']=="green") echo " SELECTED"; ?>>green</option>
					    			<option <?php if ($obj['skin_drainage']=="large") echo " SELECTED"; ?>>large</option>
					    			<option <?php if ($obj['skin_drainage']=="malodorous") echo " SELECTED"; ?>>malodorous</option>
					    			<option <?php if ($obj['skin_drainage']=="moderate") echo " SELECTED"; ?>>moderate</option>
					    			<option <?php if ($obj['skin_drainage']=="none") echo " SELECTED"; ?>>none</option>
					    			<option <?php if ($obj['skin_drainage']=="no new drainage") echo " SELECTED"; ?>>no new drainage</option>
					    			<option <?php if ($obj['skin_drainage']=="purulent") echo " SELECTED"; ?>>purulent</option>
					    			<option <?php if ($obj['skin_drainage']=="sanguineous") echo " SELECTED"; ?>>sanguineous</option>
					    			<option <?php if ($obj['skin_drainage']=="scant") echo " SELECTED"; ?>>scant</option>
					    			<option <?php if ($obj['skin_drainage']=="sero-sanguineous") echo " SELECTED"; ?>>sero-sanguineous</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_treatment"> Topical Treatment</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_treatment" name="skin_treatment">
					    			<option></option>
					    			<option <?php if ($obj['skin_treatment']=="Aquacel") echo " SELECTED"; ?>>Aquacel</option>
					    			<option <?php if ($obj['skin_treatment']=="Aquacel AG") echo " SELECTED"; ?>>Aquacel AG</option>
					    			<option <?php if ($obj['skin_treatment']=="Aquaphor") echo " SELECTED"; ?>>Aquaphor</option>
					    			<option <?php if ($obj['skin_treatment']=="Aquaphor with Maalox") echo " SELECTED"; ?>>Aquaphor with Maalox</option>
					    			<option <?php if ($obj['skin_treatment']=="Bacitracin") echo " SELECTED"; ?>>Bacitracin</option>
					    			<option <?php if ($obj['skin_treatment']=="Betadine") echo " SELECTED"; ?>>Betadine</option>
					    			<option <?php if ($obj['skin_treatment']=="Cavilon") echo " SELECTED"; ?>>Cavilon</option>
					    			<option <?php if ($obj['skin_treatment']=="Criticaid") echo " SELECTED"; ?>>Criticaid</option>
					    			<option <?php if ($obj['skin_treatment']=="Criticaid AF") echo " SELECTED"; ?>>Criticaid AF</option>
					    			<option <?php if ($obj['skin_treatment']=="Dakin's solution") echo " SELECTED"; ?>>Dakin's solution</option>
					    			<option <?php if ($obj['skin_treatment']=="Desitin") echo " SELECTED"; ?>>Desitin</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_comment">Comments/Remarks</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_comment" name="skin_comment"><?php echo text($obj{"skin_comment"});?></textarea>
					    	</div>
						</div>
						
					</div> <!--  end of panel body -->
				</div> <!-- end of panel primary -->
			</div> <!-- end of col -->
			<div class="col-sm-6 col-md-3">
				<!-- Thermoregulation section -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Thermoregulation','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="thermoregulation">Thermoregulation type</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="thermoregulation" id="thermoregulation">
					    			<option></option>
					    			<option <?php if ($obj['thermoregulation']=="Bair hugger") echo " SELECTED"; ?>>Bair hugger</option>
					    			<option <?php if ($obj['thermoregulation']=="blanket") echo " SELECTED"; ?>>blanket</option>
					    			<option <?php if ($obj['thermoregulation']=="blanket-cooling machine") echo " SELECTED"; ?>>blanket-cooling machine</option>
					    			<option <?php if ($obj['thermoregulation']=="blanket removed") echo " SELECTED"; ?>>blanket removed</option>
					    			<option <?php if ($obj['thermoregulation']=="bundling") echo " SELECTED"; ?>>bundling</option>
					    			<option <?php if ($obj['thermoregulation']=="clothes removed") echo " SELECTED"; ?>>clothes removed</option>
					    			<option <?php if ($obj['thermoregulation']=="cool cloths") echo " SELECTED"; ?>>cool cloths</option>
					    			<option <?php if ($obj['thermoregulation']=="Double wall isolette") echo " SELECTED"; ?>>Double wall isolette</option>
					    			<option <?php if ($obj['thermoregulation']=="double") echo " SELECTED"; ?>>double</option>
					    		</select>
					    	</div>

						</div>
					</div>
				</div>	
			</div><!--  end of col -->
		</div> <!-- end of rows -->

		<div>
			<!-- Save/Cancel buttons -->
			<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
			<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
		</div>
	</form>
	<script>
		$(document).ready(function() {
			$('.form-horizontal').submit(function() {
				$(this).find(':input').filter(function() { return !this.value; }).attr('disabled', 'disabled');
				return true;
			});
		});
	</script>
</body>
</html>