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
		</style>
	</head>
<body>
	<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/nursing_assessment/save.php?mode=new" name="my_form">
		<div class="row">
			<div class="col-md-4">
				<!-- Save/Cancel buttons -->
				<input type="submit" id="save" class="btn btn-success" value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</div>
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
						    		<option>[select]</option>
								    <option>Bed/Crib</option>
								    <option>Chair</option>
								    <option>Room</option>
								    <option>Wheelchair</option>
								    <option>Out of Room</option>
								    <option>Out on Pass</option>
								    <option>Other</option>
								</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_activity">Patient Activity</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_activity" class="form-control" id="ADL_activity">
						    		<option>[select]</option>
								    <option>Awake</option>
								    <option>Being Held</option>
								    <option>Dangle</option>
								    <option>Eating</option>
								    <option>Playing</option>
								    <option>Quiet Activity</option>
								    <option>Sleeping</option>
								    <option>Watching TV</option>
								    <option>Wheelchair Ride</option>
								    <option>Other</option>
								</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_position">Patient Position</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_position" class="form-control" id="ADL_position">
						    		<option>[select]</option>
								    <option>Prone</option>
								    <option>Supine</option>
								    <option>Left Side</option>
								    <option>Right Side</option>
								    <option>Sitting</option>
								    <option>Standing</option>
								    <option>Other</option>
								</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_care">Care Elements Performed</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_care" class="form-control" id="ADL_care">
						    		<option>[select]</option>
								    <option>Assist with elimination</option>
								    <option>Cares of Deffered</option>
								    <option>Device Repositioned</option>
								    <option>Diaper</option>
								    <option>Electrode patches changed</option>
								    <option>Eye care</option>
								    <option>Hair care</option>
								    <option>Linen Change</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_bath">Bath/Shower</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_bath" class="form-control" id="ADL_bath">
						    		<option>[select]</option>
								    <option>Bath/Shower</option>
								    <option>Bath refused</option>
								    <option>Bed bath</option>
								    <option>2% CHG bath</option>
								    <option>2% CHG bath contraindicated</option>
								    <option>Contraindicated</option>
								    <option>Patient Unstable</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_bed">Bed/Position</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_bed" class="form-control" id="ADL_bed">
						    		<option>[select]</option>
								    <option>HOB 10 degrees</option>
								    <option>HOB 20 degrees</option>
								    <option>HOB 30 degrees</option>
								    <option>HOB 45 degrees</option>
								    <option>HOB 60 degrees</option>
								    <option>HOB 90 degrees</option>
								    <option>HOB flat</option>
								    <option>Reverse</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_assistance">ADL Assistance Level</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_assistance" class="form-control" id="ADL_assistance">
						    		<option>[select]</option>
								    <option>Caregiver</option>
								    <option>Family</option>
								    <option>Independent</option>
								    <option>Staff-single</option>
								    <option>Staff-multiple</option>
								    <option>Other</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_mobilisation">Mobilisation</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_mobilisation" class="form-control" id="ADL_mobilisation">
						    		<option>[select]</option>
								    <option>Ambulate</option>
								    <option>Repositioned</option>
								    <option>ROM</option>
								    <option>Transferred to chair/bed</option>
								    <option>Other</option>
								</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ADL_scd">SCD's or Ted Hose</label>
					    	<div class="col-sm-6">
					    		<select name="ADL_scd" class="form-control" id="ADL_scd">
						    		<option>[select]</option>
								    <option>On</option>
								    <option>OFF</option>
								    <option>NA</option>
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
					    			<option>[select]</option>
					    			<option>Faces</option>
					    			<option>N-Pass</option>
					    			<option>rFLACC</option>
					    			<option>Numeric/Verbal scale</option>
					    			<option>COMFORT Scale</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_gaol">Patient's Pain Goal</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_gaol" name="Pain_gaol">
					    			<option>[select]</option>
					    			<option>Mentally/Developmentally unable to report</option>
					    			<option>0</option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>4</option>
					    			<option>5</option>
					    			<option>6</option>
					    			<option>7</option>
					    			<option>8</option>
					    			<option>9</option>
					    			<option>10</option>
					    		</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_rating">Pain rating</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_rating" name="Pain_rating">
					    			<option>0</option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>4</option>
					    			<option>5</option>
					    			<option>6</option>
					    			<option>7</option>
					    			<option>8</option>
					    			<option>9</option>
					    			<option>10</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_location">Pain Location</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="Pain_location" name="Pain_location">
					    		</textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_description">Pain description</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_description" name="Pain_description">
					    			<option>[select]</option>
					    			<option>Aching</option>
					    			<option>Burning</option>
					    			<option>Cramping</option>
					    			<option>Crushing</option>
					    			<option>Discomfort</option>
					    			<option>Dull</option>
					    			<option>Headache</option>
					    			<option>Heaviness</option>
					    			<option>Itching</option>
					    			<option>Nagging</option>
					    			<option>Numbness</option>
					    			<option>Penetrating</option>
					    			<option>Pins</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_frequency">Frequency</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_frequency" name="Pain_frequency">
					    			<option>[select]</option>
					    			<option>Constant/Continuous</option>
					    			<option>With Movement</option>
					    			<option>Intermittent</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_interventions">Interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_interventions" name="Pain_interventions">
					    			<option>[select]</option>
					    			<option>Caregiver support</option>
					    			<option>continuous opioid drip</option>
					    			<option>declined pain medication</option>
					    			<option>distraction</option>
					    			<option>epidural</option>
					    			<option>heat</option>
					    			<option>holding</option>
					    			<option>ice</option>
					    			<option>imagery</option>
					    			<option>nonopioid</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
					    </div>
					    	<div class="form-group">
						    	<label class="control-label col-sm-4" for="Pain_medSideEffects">Medications side effects</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" id="Pain_medSideEffects" name="Pain_medSideEffects">
						    			<option>[select]</option>
						    			<option>None</option>
						    			<option>Constipated</option>
						    			<option>Nausea</option>
						    			<option>O2 Sats<90%</option>
						    			<option>Oversedation</option>
						    			<option>Pruritis</option>
						    			<option>Resp Decreased</option>
						    			<option>Resp Shallow</option>
						    			<option>Urine Retention</option>
						    			<option>Vomitting</option>
						    			<option>Other</option>
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
					    	<label class="control-label col-sm-4" for="PN_method">Method of Communication</label>
					    	<div class="col-sm-6">
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

			
		</div> <!-- end of row -->

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
					    		<textarea class="form-control" id="BG_level" name="BG_level"></textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BG_interventions">Related Interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="BG_interventions" name="BG_interventions">
					    			<option>[select]</option>
								    <option>Juice Given</option>
								    <option>Carbohydrate Snack</option>
								    <option> Medication Given</option>
								    <option>Provider Notified</option>
								    <option>Critical Care Guideline Activated</option>
								    <option>Other</option>
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
					    	<label class="control-label col-sm-4" for="P_interactions">Patient Behaviour</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="P_interactions" name="P_interactions">
					    			<option>Content</option>
					    			<option>Combative</option>
					    			<option>Compliant</option>
					    			<option>Interactive</option>
					    			<option>Restless</option>
					    			<option>Irritable</option>
					    			<option>Noncompliant</option>
					    			<option>Flat Affect</option>
					    			<option>Constant Movement</option>
					    			<option>Non Responsive</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BG_level">Patient Support</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="BG_level" name="BG_level"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BG_level">Patient Response</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="BG_level" name="BG_level"></textarea>
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
					    		<textarea class="form-control" id="ass_comments" name="ass_comments"></textarea>
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
					    			<option>[select]</option>
					    			<option>Abdominal</option>
					    			<option>Agonal</option>
					    			<option>apnea</option>
					    			<option>BiPAP</option>
					    			<option>CPAP</option>
					    			<option>Dyspnea</option>
					    			<option>Gasping</option>
					    			<option>Grunting</option>
					    			<option>Irregular</option>
					    			<option>Labored</option>
					    			<option>Nasal flaring</option>
					    			<option>Oscillator</option>
					    			<option>Prolonged Expiration</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
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
					    			<option>[select]</option>
					    			<option>Bair hugger</option>
					    			<option>blanket</option>
					    			<option>blanket-cooling machine</option>
					    			<option>blanket removed</option>
					    			<option>bundling</option>
					    			<option>clothes removed</option>
					    			<option>cool cloths</option>
					    			<option>Double wall isolette</option>
					    			<option>double</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
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
					    			<option>[select]</option>
					    			<option>All extremities</option>
					    			<option>right side</option>
					    			<option>left side</option>
					    			<option>RUE</option>
					    			<option>LUE</option>
					    			<option>LLE</option>
					    			<option>Upper bilateral</option>
					    			<option>Lower Bilateral</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NM_limbMovnt">Limb Movements</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NM_limbMovnt" id="NM_limbMovnt">
					    			<option>[select]</option>
					    			<option>Full spontaneous</option>
					    			<option>Contracted</option>
					    			<option>Decerebrate</option>
					    			<option>Decorticate</option>
					    			<option>Flaccid</option>
					    			<option>Limited Spontaneous</option>
					    			<option>Jittery</option>
					    			<option>Med paralyzed</option>
					    			<option>Moves to painful stimuli</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NM_strength">Motor Strength</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NM_strength" id="NM_strength">
					    			<option>[select]</option>
					    			<option>Normal</option>
					    			<option>Baseline</option>
					    			<option>Strong</option>
					    			<option>BiPAP</option>
					    			<option>Hypertonic</option>
					    			<option>Hypotonic</option>
					    			<option>Weak</option>
					    			<option>Very Weak</option>
					    			<option>Flaccid</option>
					    			<option>Absent</option>
					    			<option>Unable to assess</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NM_sensation">Sensation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NM_sensation" id="NM_sensation">
					    			<option>[select]</option>
					    			<option>Normal</option>
					    			<option>full</option>
					    			<option>Tingling</option>
					    			<option>Decreased</option>
					    			<option>Numbness</option>
					    			<option>Unable to assess</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
					</div>
				</div>	
			</div><!--  end of col -->
		</div> <!-- end of row -->

		<div>
			<!-- Save/Cancel buttons -->
			<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
			<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
		</div>
	</form>
</body>
</html>