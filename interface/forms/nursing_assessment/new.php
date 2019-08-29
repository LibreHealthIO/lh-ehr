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
		<script type="text/javascript">
			var currentSelect;
			var currentCommentDiv;
			function getComment(select, element, focused, className){ 
				console.log(focused);
				 currentCommentDiv = element;
				if(focused === className){
					$(`#${element}`).show();
				}else{
					$(`#${element}`).hide();
				}
			}
			function fillComment(element, option){
				console.log(element);
				$(`.${option}`).text(element);
				$(`#${currentCommentDiv}`).hide();
			}
		</script>
	</head>
<body>
	<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/nursing_assessment/save.php?mode=new" name="my_form" onsubmit="return top.restoreSession()">
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
					    		<textarea class="form-control" id="BG_level" name="BG_level"></textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BG_interventions">Related Interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="BG_interventions" name="BG_interventions">
					    			<option></option>
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
					    	<label class="control-label col-sm-4" for="pt_behavior">Patient Behaviour</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="pt_behavior" name="pt_behavior">
					    			<option></option>
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
					    	<label class="control-label col-sm-4" for="pt_support">Patient Support</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="pt_support" name="pt_support"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pt_response">Patient Response</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="pt_response" name="pt_response"></textarea>
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
					    			<option></option>
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
							<label class="control-label col-sm-4" for="FiO2">
								FiO2 (%)
							</label>

							<div class="col-sm-6">
								<input type="number" name="FiO2" class="form-control" id="FiO2">
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
					    		<option></option>
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
					      <label class="control-label col-sm-4" for="V_roomAir"> Room air(yes/no)</label>
					      <div class="col-sm-5"><input type="checkbox" name="V_roomAir" id="V_roomAir" name="remember"></div>
					      
					    </div>

					    <div class="form-group">
					    	<label class="control-label col-sm-4" for="V_deliveryMethod">O2 Delivery Method</label>
					    	<div class="col-sm-6">
					    		<select onchange="getComment(this,'Delivery_method_Comment', $(this).children(':selected').attr('class'),'CommentArea')" name="V_deliveryMethod" class="form-control" id="V_deliveryMethod">
					    		<option></option>
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
							    <option class ="CommentArea">Other(see comment)</option>
							</select>
							<div id="Delivery_method_Comment" style="display: none">
									<textarea  class="form-control" placeholder="write your comment here"></textarea>
									<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
							</div>
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
						    		<option></option>
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
						    		<option></option>
								    <option>Lying</option>
								    <option>Standing</option>
								    <option>Sitting</option>
								</select>
								<div id="Bp_method_Comment" style="display: none">
									<textarea  class="form-control" placeholder="write your comment here"></textarea>
									<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
								</div>
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
						    		<option></option>
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
						    		<option></option>
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
						    		<option></option>
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
						    		<option></option>
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
						    		<option></option>
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
						    		<option></option>
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
						    		<option></option>
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
						    		<option></option>
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
					    			<option></option>
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
					    			<option></option>
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
					    		<textarea class="form-control" id="Pain_location" name="Pain_location"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Pain_description">Pain description</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Pain_description" name="Pain_description">
					    			<option></option>
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
					    			<option></option>
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
					    			<option></option>
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
						    			<option></option>
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
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="pasero_sedation_score">Pasero Sedation Score</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" id="pasero_sedation_score" name="pasero_sedation_score">
						    			<option></option>
						    			<option>S</option>
						    			<option>1</option>
						    			<option>2</option>
						    			<option>3</option>
						    			<option>4</option>
						    			<option>l</option>
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
					    		<select name="PN_method" onchange="getComment(this,'PN_method_Comment', $(this).children(':selected').attr('class'), 'CommentArea')" class="form-control" id="PN_method">
						    		<option></option>
								    <option>Call</option>
								    <option>Page</option>
								    <option>Face to Face</option>
								    <option class ="CommentArea">Other (comment)</option>
								</select>
								<div id="PN_method_Comment" style="display: none">
									<textarea  class="form-control" placeholder="write your comment here"></textarea>
									<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
								 </div>  	
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="PN_response">Response</label>
					    	<div class="col-sm-6">
					    		<select onchange="getComment(this,'response_method_Comment', $(this).children(':selected').attr('class'), 'CommentArea')" name="PN_response" class="form-control" id="PN_response">
					    		<option></option>
							    <option>En route</option>
							    <option>At bedside</option>
							    <option>Escalation Huddle</option>
							    <option>In Department</option>
							    <option>No New Orders</option>
							    <option>See Orders</option>
							    <option>Waiting for Response</option>
							    <option class ="CommentArea">Other (comment)</option>
							</select>
							<div id="response_method_Comment" style="display: none">
									<textarea  class="form-control" placeholder="write your comment here"></textarea>
									<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
								 </div>
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
					    			<option></option>
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
					    			<option></option>
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
					    			<option></option>
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
					    		<select class="form-control" name="GCS_eyeOpening" id="GCS_eyeOpening">
					    			<option></option>
					    			<option>1 spontaneous</option>
					    			<option>2 to speech or sound</option>
					    			<option>3 to painful stimuli</option>
					    			<option>4 none</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_bestVerbal">Best Verbal</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="GCS_bestVerbal" id="GCS_bestVerbal">
					    			<option></option>
					    			<option>1 appropriate words/sounds related to development</option>
					    			<option>2 confused</option>
					    			<option>3 Inappropriate</option>
					    			<option>4 Incomprehensive words</option>
					    			<option>5 none</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_bestMotor">Best Motor</label>
					    	<div class="col-sm-6">
					    		<select id="GCS_bestMotor" name="GCS_bestMotor">
					    			<option></option>
					    			<option>1  spontaneous movement/obeys commands</option>
					    			<option>2 localizes to pain</option>
					    			<option>3 withdraws to pain</option>
					    			<option>4 abnormal flexion</option>
					    			<option>5 abnormal extension</option>
					    			<option>6 none</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_total">Total Score</label>
					    	<div class="col-sm-6">
					    		<input min="3" max="15" class="form-control" name="GCS_total" id="GCS_total">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GCS_remarks">Comments/Remarks</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="GCS_remarks" id="GCS_remarks">
					    			<option></option>
					    			<option>Sedated</option>
					    			<option>medically paralyzed</option>
					    			<option>intubated</option>
					    			<option>other</option>
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
					    			<option>Alert</option>
					    			<option>Alert, Awake and Oriented x3</option>
					    			<option>Alert, Awake, oriented appropriate to age</option>
					    			<option>Agitated</option>
					    			<option>Awake</option>
					    			<option>Awakens easily</option>
					    			<option>Comatose</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_cry">cry</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_cry" id="NE_cry">
					    			<option></option>
					    			<option>Absent</option>
					    			<option>high pitched</option>
					    			<option>hoarse</option>
					    			<option>intubated-visibly crying</option>
					    			<option>stridorous</option>
					    			<option>Unable to assess</option>
					    			<option>Strong</option>
					    			<option>weak</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_pr_bilat">Pupil reaction/bilat</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_pr_bilat" id="NE_pr_bilat">
					    			<option></option>
					    			<option>Brisk</option>
					    			<option>Dilated</option>
					    			<option>Equal</option>
					    			<option>Hippus</option>
					    			<option>Sluggish</option>
					    			<option>No response</option>
					    			<option>Unable to assess</option>
					    			<option>1mm-8mm</option>
					    			<option>PERL</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_pr_left">Pupil reaction/left</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_pr_left" id="NE_pr_left">
					    			<option></option>
					    			<option>Brisk</option>
					    			<option>Dilated</option>
					    			<option>Equal</option>
					    			<option>Hippus</option>
					    			<option>Sluggish</option>
					    			<option>No response</option>
					    			<option>Unable to assess</option>
					    			<option>1mm-8mm</option>
					    			<option>PERL</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_pr_right">Pupil reaction/right</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_pr_right" id="NE_pr_right">
					    			<option></option>
					    			<option>Brisk</option>
					    			<option>Dilated</option>
					    			<option>Equal</option>
					    			<option>Hippus</option>
					    			<option>Sluggish</option>
					    			<option>No response</option>
					    			<option>Unable to assess</option>
					    			<option>1mm-8mm</option>
					    			<option>PERL</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_oculomotor">Oculomotor</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_oculomotor" id="NE_oculomotor">
					    			<option></option>
					    			<option>Fucus/Tracks/Conjugates</option>
					    			<option>Disconjugate</option>
					    			<option>Focus</option>
					    			<option>L gaze preference</option>
					    			<option>R gaze preference</option>
					    			<option>Nystagmus</option>
					    			<option>L Ptosis</option>
					    			<option>R ptosis</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_fontanel">fontanel</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_fontanel" id="NE_fontanel">
					    			<option></option>
					    			<option>soft</option>
					    			<option>Flat</option>
					    			<option>Sutures approximated</option>
					    			<option>Sutures overriding</option>
					    			<option>Sutures split</option>
					    			<option>Bulging</option>
					    			<option>Closed</option>
					    			<option>Depressed</option>
					    			<option>Full</option>
					    			<option>Pulsatile</option>
					    			<option>Tense</option>
					    			<option>Unable to assess</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_reflexes">reflexes</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" min="0" max="5" name="NE_reflexes" id="NE_reflexes">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_seizure_activity">seizure activity</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_seizure_activity" id="NE_seizure_activity">
					    			<option></option>
					    			<option>No</option>
					    			<option>Yes</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_monitoring">neuro monitoring</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_monitoring" id="NE_monitoring">
					    			<option></option>
					    			<option>Continuous EEG</option>
					    			<option>Brainz</option>
					    			<option>CFM</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="NE_collar">c-collar</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="NE_collar" id="NE_collar">
					    			<option></option>
					    			<option>Yes</option>
					    			<option>Cleaned</option>
					    			<option>D/C'd</option>
					    			<option>Pads changed</option>
					    			<option>Skin checked</option>
					    			<option>Replaced</option>
					    			<option>Other</option>
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
					    			<option>Person</option>
					    			<option>Place</option>
					    			<option>Time</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_mood">Mood/affect</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_mood" id="psy_mood">
					    			<option></option>
					    			<option>Broad</option>
					    			<option>Depressed</option>
					    			<option>Cheerful</option>
					    			<option>Irritable</option>
					    			<option>Hostile</option>
					    			<option>Restricted</option>
					    			<option>Flat</option>
					    			<option>Labile</option>
					    			<option>Bizarre</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_memory">Concentration/Memory</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_memory" id="psy_memory">
					    			<option></option>
					    			<option>WNL</option>
					    			<option>good</option>
					    			<option>poor</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_appearance">Appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_appearance" id="psy_appearance">
					    			<option></option>
					    			<option>Disheveled</option>
					    			<option>Neat/Clean</option>
					    			<option>Poor personal hygiene</option>
					    			<option>Appears older than stated</option>
					    			<option>Appears younger than stated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_behaviour">Behavior</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_behaviour" id="psy_behaviour">
					    			<option></option>
					    			<option>Normal/alert</option>
					    			<option>Cooperative</option>
					    			<option>Uncooperative</option>
					    			<option>Resistant</option>
					    			<option>Overactive</option>
					    			<option>Combative</option>
					    			<option>Withdrawn</option>
					    			<option>Dramtized</option>
					    			<option>Self-destructive</option>
					    			<option>Agitated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_eyeContact">Eye contact</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_eyeContact" id="psy_eyeContact">
					    			<option></option>
					    			<option>Appropriate</option>
					    			<option>Poor</option>
					    			<option>Non-existent</option>
					    			<option>Glaring</option>
					    			<option>Culturally appropriate</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_thought">Thought</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_thought" id="psy_thought">
					    			<option></option>
					    			<option>Logical/Coherent</option>
					    			<option>Tangential</option>
					    			<option>Loose association</option>
					    			<option>Disorganized</option>
					    			<option>Over involvement in fantasy</option>
					    			<option>Hallucination</option>
					    			<option>No Hallucinations</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pys_judgment">Insight/Judgement</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="pys_judgment" id="pys_judgment">
					    			<option></option>
					    			<option>WNL</option>
					    			<option>Good</option>
					    			<option>poor</option>
					    			<option>fair</option>
					    			<option>lacking</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_interactions">Patient/Caregiver interactions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="pys_interactions" id="pys_interactions">
					    			<option></option>
					    			<option>Appropriate</option>
					    			<option>Manipulative</option>
					    			<option>Transference</option>
					    			<option>Argumentative</option>
					    			<option>Sabotaging</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="psy_riskPrecaution">Ongoing risk precautions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="psy_riskPrecaution" id="psy_riskPrecaution">
					    			<option></option>
					    			<option>Assaultive</option>
					    			<option>Boundary issues</option>
					    			<option>Escalated behaviour</option>
					    			<option>Fire Setting</option>
					    			<option>run</option>
					    			<option>self-injurious behaviour</option>
					    			<option>suicidal</option>
					    			<option>none</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Psy_escalated_behavior">Escalated behavior</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="Psy_escalated_behavior" id="Psy_escalated_behavior">
					    			<option></option>
					    			<option>Distraction techniques</option>
					    			<option>limit setting</option>
					    			<option>notify provider</option>
					    			<option>parental assist</option>
					    			<option>re-direction</option>
					    			<option>verbal de-escalation</option>
					    			<option>Other</option>
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
					    			<option>Active precordium</option>
					    			<option>Click</option>
					    			<option>Continuous</option>
					    			<option>Distant</option>
					    			<option>Gallop</option>
					    			<option>Intermittent</option>
					    			<option>Mechanical device</option>
					    			<option>Muffled</option>
					    			<option>Murmur</option>
					    			<option>Normal</option>
					    			<option>Rub</option>
					    			<option>Split S2</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_PMI">PMI</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_PMI" id="cardio_PMI">
					    			<option></option>
					    			<option>L Sternal Border</option>
					    			<option>R sternal border</option>
					    			<option>midline</option>
					    			<option>oscillator</option>
					    			<option>unable to assess</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_rhythm">Rhythm</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_rhythm" id="cardio_rhythm">
					    			<option></option>
					    			<option>Normal sinus</option>
					    			<option>regular</option>
					    			<option>irregular</option>
					    			<option>sinus brady</option>
					    			<option>sinus tach</option>
					    			<option>sinus arryhthmia</option>
					    			<option>paced</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_arrythmia">arrythmia</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_arrythmia" id="cardio_arrythmia">
					    			<option></option>
					    			<option>1st degree block</option>
					    			<option>2nd degree block</option>
					    			<option>3rd degree block</option>
					    			<option>Aberrant conduction</option>
					    			<option>atrial arrhythmia</option>
					    			<option>atrial flutter</option>
					    			<option>bigemeny</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_arryfreq">arrythmia frequency</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_arryfreq" id="cardio_arryfreq">
					    			<option></option>
					    			<option>Continuous</option>
					    			<option>intermittent</option>
					    			<option>occasional</option>
					    			<option>runs of</option>
					    			<option>frequent</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_interventions">interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_interventions" id="cardio_interventions">
					    			<option></option>
					    			<option>None-patient tolerating rhythm</option>
					    			<option>A-wire obtained</option>
					    			<option>Cardioversion</option>
					    			<option>CR Monitoring</option>
					    			<option>EKG obtained</option>
					    			<option>Electrolytes optimized</option>
					    			<option>Ice to face</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crc">cap refill central</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crc" id="cardio_crc">
					    			<option></option>
					    			<option>1-8 seconds</option>
					    			<option>>8 seconds</option>
					    			<option>unable to assess</option>
					    			<option>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crp">cap refill peripheral</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crp" id="cardio_crp">
					    			<option></option>
					    			<option>1-8 seconds</option>
					    			<option>>8 seconds</option>
					    			<option>unable to assess</option>
					    			<option>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crRUE">cap refill RUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crRUE" id="cardio_crRUE">
					    			<option></option>
					    			<option>1-8 seconds</option>
					    			<option>>8 seconds</option>
					    			<option>unable to assess</option>
					    			<option>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crLUE">cap refill LUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crLUE" id="cardio_crLUE">
					    			<option></option>
					    			<option>1-8 seconds</option>
					    			<option>>8 seconds</option>
					    			<option>unable to assess</option>
					    			<option>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crRLE">cap refill RLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crRLE" id="cardio_crRLE">
					    			<option></option>
					    			<option>1-8 seconds</option>
					    			<option>>8 seconds</option>
					    			<option>unable to assess</option>
					    			<option>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_crLLE">cap refill LLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_crLLE" id="cardio_crLLE">
					    			<option></option>
					    			<option>1-8 seconds</option>
					    			<option>>8 seconds</option>
					    			<option>unable to assess</option>
					    			<option>flash</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_centralColor">Central Color/Temp</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_centralColor" id="cardio_centralColor">
					    			<option></option>
					    			<option>Pink, warm, dry</option>
					    			<option>Bruising</option>
					    			<option>Cold</option>
					    			<option>Cool</option>
					    			<option>Cyanotic</option>
					    			<option>Cyanotic lips</option>
					    			<option>diaphoretic</option>
					    			<option>dry</option>
					    			<option>dusky</option>
					    			<option>hot</option>
					    			<option>jaundiced</option>
					    			<option>mottled</option>
					    			<option>pale</option>
					    			<option>pink</option>
					    			<option>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_PeripheralColor">Peripheral Color/Temp</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_PeripheralColor" id="cardio_PeripheralColor">
					    			<option></option>
					    			<option>Pink, warm, dry</option>
					    			<option>Bruising</option>
					    			<option>Cold</option>
					    			<option>Cool</option>
					    			<option>Cyanotic</option>
					    			<option>Cyanotic lips</option>
					    			<option>diaphoretic</option>
					    			<option>dry</option>
					    			<option>dusky</option>
					    			<option>hot</option>
					    			<option>jaundiced</option>
					    			<option>mottled</option>
					    			<option>pale</option>
					    			<option>pink</option>
					    			<option>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorRUE"> Color/Temp RUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorRUE" id="cardio_colorRUE">
					    			<option></option>
					    			<option>Pink, warm, dry</option>
					    			<option>Bruising</option>
					    			<option>Cold</option>
					    			<option>Cool</option>
					    			<option>Cyanotic</option>
					    			<option>Cyanotic lips</option>
					    			<option>diaphoretic</option>
					    			<option>dry</option>
					    			<option>dusky</option>
					    			<option>hot</option>
					    			<option>jaundiced</option>
					    			<option>mottled</option>
					    			<option>pale</option>
					    			<option>pink</option>
					    			<option>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorLUE"> Color/Temp LUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorLUE" id="cardio_colorLUE">
					    			<option></option>
					    			<option>Pink, warm, dry</option>
					    			<option>Bruising</option>
					    			<option>Cold</option>
					    			<option>Cool</option>
					    			<option>Cyanotic</option>
					    			<option>Cyanotic lips</option>
					    			<option>diaphoretic</option>
					    			<option>dry</option>
					    			<option>dusky</option>
					    			<option>hot</option>
					    			<option>jaundiced</option>
					    			<option>mottled</option>
					    			<option>pale</option>
					    			<option>pink</option>
					    			<option>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorRLE"> Color/Temp RLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorRLE" id="cardio_colorRLE">
					    			<option></option>
					    			<option>Pink, warm, dry</option>
					    			<option>Bruising</option>
					    			<option>Cold</option>
					    			<option>Cool</option>
					    			<option>Cyanotic</option>
					    			<option>Cyanotic lips</option>
					    			<option>diaphoretic</option>
					    			<option>dry</option>
					    			<option>dusky</option>
					    			<option>hot</option>
					    			<option>jaundiced</option>
					    			<option>mottled</option>
					    			<option>pale</option>
					    			<option>pink</option>
					    			<option>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_colorLLE"> Color/Temp LLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_colorLLE" id="cardio_colorLLE">
					    			<option></option>
					    			<option>Pink, warm, dry</option>
					    			<option>Bruising</option>
					    			<option>Cold</option>
					    			<option>Cool</option>
					    			<option>Cyanotic</option>
					    			<option>Cyanotic lips</option>
					    			<option>diaphoretic</option>
					    			<option>dry</option>
					    			<option>dusky</option>
					    			<option>hot</option>
					    			<option>jaundiced</option>
					    			<option>mottled</option>
					    			<option>pale</option>
					    			<option>pink</option>
					    			<option>plethoric</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_centralPulses">Central pulses</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_centralPulses" id="cardio_centralPulses">
					    			<option></option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>Absent by palpitation</option>
					    			<option>Absent by doppler</option>
					    			<option>Bounding</option>
					    			<option>Doppler</option>
					    			<option>Equal</option>
					    			<option>Strong</option>
					    			<option>Unequal</option>
					    			<option>Weak</option>
					    			<option>Carotid palpated</option>
					    			<option>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pheripheralPulses">Pheripheral pulses</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pheripheralPulses" id="cardio_pheripheralPulses">
					    			<option></option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>Absent by palpitation</option>
					    			<option>Absent by doppler</option>
					    			<option>Bounding</option>
					    			<option>Doppler</option>
					    			<option>Equal</option>
					    			<option>Strong</option>
					    			<option>Unequal</option>
					    			<option>Weak</option>
					    			<option>Carotid palpated</option>
					    			<option>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseRUE">Pulse RUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseRUE" id="cardio_pulseRUE">
					    			<option></option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>Absent by palpitation</option>
					    			<option>Absent by doppler</option>
					    			<option>Bounding</option>
					    			<option>Doppler</option>
					    			<option>Equal</option>
					    			<option>Strong</option>
					    			<option>Unequal</option>
					    			<option>Weak</option>
					    			<option>Carotid palpated</option>
					    			<option>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseLUE">Pulse LUE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseLUE" id="cardio_pulseLUE">
					    			<option></option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>Absent by palpitation</option>
					    			<option>Absent by doppler</option>
					    			<option>Bounding</option>
					    			<option>Doppler</option>
					    			<option>Equal</option>
					    			<option>Strong</option>
					    			<option>Unequal</option>
					    			<option>Weak</option>
					    			<option>Carotid palpated</option>
					    			<option>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseRLE">Pulse RLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseRLE" id="cardio_pulseRUE">
					    			<option></option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>Absent by palpitation</option>
					    			<option>Absent by doppler</option>
					    			<option>Bounding</option>
					    			<option>Doppler</option>
					    			<option>Equal</option>
					    			<option>Strong</option>
					    			<option>Unequal</option>
					    			<option>Weak</option>
					    			<option>Carotid palpated</option>
					    			<option>Femoral palpated</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="cardio_pulseLLE">Pulse LLE</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="cardio_pulseLLE" id="cardio_pulseLLE">
					    			<option></option>
					    			<option>1</option>
					    			<option>2</option>
					    			<option>3</option>
					    			<option>Absent by palpitation</option>
					    			<option>Absent by doppler</option>
					    			<option>Bounding</option>
					    			<option>Doppler</option>
					    			<option>Equal</option>
					    			<option>Strong</option>
					    			<option>Unequal</option>
					    			<option>Weak</option>
					    			<option>Carotid palpated</option>
					    			<option>Femoral palpated</option>
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
					    			<option>Abdominal</option>
					    			<option>Agonal</option>
					    			<option>Apena</option>
					    			<option>BiPAPA</option>
					    			<option>CPAP</option>
					    			<option>Dyspnea</option>
					    			<option>Grunting</option>
					    			<option>Labored</option>
					    			<option>Irregular</option>
					    			<option>Nasal Flaring</option>
					    			<option>Oscillator</option>
					    			<option>Prolonged expiration</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_retractions">Retractions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_retractions" id="resp_retractions">
					    			<option></option>
					    			<option>None</option>
					    			<option>intercostal</option>
					    			<option>mild</option>
					    			<option>moderate</option>
					    			<option>severe</option>
					    			<option>subcostal</option>
					    			<option>submandibular</option>
					    			<option>substernal</option>
					    			<option>supraclavicular</option>
					    			<option>suprasternal</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_cough">cough</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_cough" id="resp_cough">
					    			<option></option>
					    			<option>Barky</option>
					    			<option>Croupy</option>
					    			<option>Dry</option>
					    			<option>Gag</option>
					    			<option>Gag not present</option>
					    			<option>Harsh</option>
					    			<option>Hemooptysis</option>
					    			<option>Loose</option>
					    			<option>Moist</option>
					    			<option>Nonproductive</option>
					    			<option>Occasional</option>
					    			<option>Paroxysmal</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_appearance">appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_appearance" id="resp_appearance">
					    			<option></option>
					    			<option>Pink</option>
					    			<option>Pale</option>
					    			<option>Mottled</option>
					    			<option>Cyanotic</option>
					    			<option>Dusky</option>
					    			<option>Nailed</option>
					    			<option>Clubbing</option>
					    			<option>Barrel Chested</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsallLobes">Breath sounds all lobes</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsallLobes" id="resp_bsallLobes">
					    			<option></option>
					    			<option>Clear equal and good aeration bilaterally</option>
					    			<option>clear and equal</option>
					    			<option>Aeration</option>
					    			<option>absent</option>
					    			<option>air leak</option>
					    			<option>clear</option>
					    			<option>coarse</option>
					    			<option>crackles</option>
					    			<option>diminished</option>
					    			<option>end expiratory</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsRUL">Breath sounds RUL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsRUL" id="resp_bsRUL">
					    			<option></option>
					    			<option>Clear equal and good aeration bilaterally</option>
					    			<option>clear and equal</option>
					    			<option>Aeration</option>
					    			<option>absent</option>
					    			<option>air leak</option>
					    			<option>clear</option>
					    			<option>coarse</option>
					    			<option>crackles</option>
					    			<option>diminished</option>
					    			<option>end expiratory</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsRML">Breath sounds RML</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsRML" id="resp_bsRML">
					    			<option></option>
					    			<option>Clear equal and good aeration bilaterally</option>
					    			<option>clear and equal</option>
					    			<option>Aeration</option>
					    			<option>absent</option>
					    			<option>air leak</option>
					    			<option>clear</option>
					    			<option>coarse</option>
					    			<option>crackles</option>
					    			<option>diminished</option>
					    			<option>end expiratory</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsRLL">Breath sounds RLL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsRLL" id="resp_bsRLL">
					    			<option></option>
					    			<option>Clear equal and good aeration bilaterally</option>
					    			<option>clear and equal</option>
					    			<option>Aeration</option>
					    			<option>absent</option>
					    			<option>air leak</option>
					    			<option>clear</option>
					    			<option>coarse</option>
					    			<option>crackles</option>
					    			<option>diminished</option>
					    			<option>end expiratory</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsLUL">Breath sounds LUL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsLUL" id="resp_bsLUL">
					    			<option></option>
					    			<option>Clear equal and good aeration bilaterally</option>
					    			<option>clear and equal</option>
					    			<option>Aeration</option>
					    			<option>absent</option>
					    			<option>air leak</option>
					    			<option>clear</option>
					    			<option>coarse</option>
					    			<option>crackles</option>
					    			<option>diminished</option>
					    			<option>end expiratory</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_bsLLL">Breath sounds LLL</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_bsLLL" id="resp_bsLLL">
					    			<option></option>
					    			<option>Clear equal and good aeration bilaterally</option>
					    			<option>clear and equal</option>
					    			<option>Aeration</option>
					    			<option>absent</option>
					    			<option>air leak</option>
					    			<option>clear</option>
					    			<option>coarse</option>
					    			<option>crackles</option>
					    			<option>diminished</option>
					    			<option>end expiratory</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_interventions">Respiratory interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_interventions" id="resp_interventions">
					    			<option></option>
					    			<option>BIPAP/CPAP Trial</option>
					    			<option>Chest PT</option>
					    			<option>Cough</option>
					    			<option>Continuous nebulizer</option>
					    			<option>Cool mist</option>
					    			<option>Deep breathing</option>
					    			<option>IS</option>
					    			<option>Intermittent positive pressure</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_suctiontype">Suctioning type</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_suctiontype" id="resp_suctiontype">
					    			<option></option>
					    			<option>Closed</option>
					    			<option>open</option>
					    			<option>nasal aspirate</option>
					    			<option>deep only</option>
					    			<option>home</option>
					    			<option>bulb</option>
					    			<option>nasal frida</option>
					    			<option>Deep than other</option>
					    			<option>bulb then other</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_oralSec">Oral secretions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_oralSec" id="resp_oralSec">
					    			<option></option>
					    			<option>blood tinged</option>
					    			<option>brown</option>
					    			<option>clear</option>
					    			<option>cloudy</option>
					    			<option>copious</option>
					    			<option>frank blood</option>
					    			<option>frothy</option>
					    			<option>green</option>
					    			<option>large</option>
					    			<option>moderate</option>
					    			<option>none</option>
					    			<option>old blood</option>
					    			<option>pink</option>
					    			<option>plugs</option>
					    			<option>scant</option>
					    			<option>small</option>
					    			<option>tan</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_nasalSec">Nasal secretions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_nasalSec" id="resp_nasalSec">
					    			<option></option>
					    			<option>blood tinged</option>
					    			<option>brown</option>
					    			<option>clear</option>
					    			<option>cloudy</option>
					    			<option>copious</option>
					    			<option>frank blood</option>
					    			<option>frothy</option>
					    			<option>green</option>
					    			<option>large</option>
					    			<option>moderate</option>
					    			<option>none</option>
					    			<option>old blood</option>
					    			<option>pink</option>
					    			<option>plugs</option>
					    			<option>scant</option>
					    			<option>small</option>
					    			<option>tan</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="resp_airwaySec">Airway secretions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" name="resp_airwaySec" id="resp_airwaySec">
					    			<option></option>
					    			<option>blood tinged</option>
					    			<option>brown</option>
					    			<option>clear</option>
					    			<option>cloudy</option>
					    			<option>copious</option>
					    			<option>frank blood</option>
					    			<option>frothy</option>
					    			<option>green</option>
					    			<option>large</option>
					    			<option>moderate</option>
					    			<option>none</option>
					    			<option>old blood</option>
					    			<option>pink</option>
					    			<option>plugs</option>
					    			<option>scant</option>
					    			<option>small</option>
					    			<option>tan</option>
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
					    			<option>Round</option>
					    			<option>soft</option>
					    			<option>ascites</option>
					    			<option>concave</option>
					    			<option>discolored</option>
					    			<option>distended</option>
					    			<option>edematous</option>
					    			<option>firm</option>
					    			<option>flat</option>
					    			<option>loops visible</option>
					    			<option>loops palpable</option>
					    			<option>non distended</option>
					    			<option>non tender</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_girth">Abd girth (cm)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="GI_girth" name="GI_girth">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_liverPos">Liver position</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_liverPos" name="GI_liverPos">
					    			<option></option>
					    			<option>Unable to assess</option>
					    			<option>not palpable</option>
					    			<option>midline</option>
					    			<option>right costal margin</option>
					    			<option>at umbilicus</option>
					    			<option>firm</option>
					    			<option>soft</option>
					    			<option>1cm below</option>
					    			<option>2cm below</option>
					    			<option>3cm below</option>
					    			<option>4cm below</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_symptoms">GI symptoms</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_symptoms" name="GI_symptoms">
					    			<option></option>
					    			<option>Arching</option>
					    			<option>bloating</option>
					    			<option>constipation</option>
					    			<option>cramping</option>
					    			<option>diarrhea</option>
					    			<option>epidural</option>
					    			<option>flatus</option>
					    			<option>gagging</option>
					    			<option>guarding</option>
					    			<option>hematuria</option>
					    			<option>incontinent</option>
					    			<option>malabsorption</option>
					    			<option>nausea</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_interventions">GI interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_interventions" name="GI_interventions">
					    			<option></option>
					    			<option>24 hour stool collection</option>
					    			<option>bowel clean out/prep</option>
					    			<option>bowel prep given</option>
					    			<option>bowel program</option>
					    			<option>bowel/digital stimulation</option>
					    			<option>flatus</option>
					    			<option>diapered</option>
					    			<option>enema</option>
					    			<option>feeds</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsAllQuad">Bowel sounds-all quadrants</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsAllQuad" name="GI_bsAllQuad">
					    			<option></option>
					    			<option>active</option>
					    			<option>Hyperactive</option>
					    			<option>Hypoactive</option>
					    			<option>High pitched</option>
					    			<option>absent</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsRUG">BS RUQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsRUG" name="GI_bsRUG">
					    			<option></option>
					    			<option>active</option>
					    			<option>Hyperactive</option>
					    			<option>Hypoactive</option>
					    			<option>High pitched</option>
					    			<option>absent</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsRLG">BS RLQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsRLG" name="GI_bsRLG">
					    			<option></option>
					    			<option>active</option>
					    			<option>Hyperactive</option>
					    			<option>Hypoactive</option>
					    			<option>High pitched</option>
					    			<option>absent</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsLUG">BS LUQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsLUG" name="GI_bsLUG">
					    			<option></option>
					    			<option>active</option>
					    			<option>Hyperactive</option>
					    			<option>Hypoactive</option>
					    			<option>High pitched</option>
					    			<option>absent</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_bsLLG">BS LLQ</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_bsLLG" name="GI_bsLLG">
					    			<option></option>
					    			<option>active</option>
					    			<option>Hyperactive</option>
					    			<option>Hypoactive</option>
					    			<option>High pitched</option>
					    			<option>absent</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GI_epigastric">Epigastric</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GI_epigastric" name="GI_epigastric">
					    			<option></option>
					    			<option>active</option>
					    			<option>Hyperactive</option>
					    			<option>Hypoactive</option>
					    			<option>High pitched</option>
					    			<option>absent</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
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
					    			<option>Burning</option>
					    			<option>Frequency</option>
					    			<option>Distended</option>
					    			<option>Dysuria</option>
					    			<option>Fullness</option>
					    			<option>Hematuria</option>
					    			<option>Incontinent</option>
					    			<option>Oliguria</option>
					    			<option>Unable to void</option>
					    			<option>Urgency</option>
					    			<option>Spasms</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GU_interventions">GU interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GU_interventions" name="GU_interventions">
					    			<option></option>
					    			<option>24 hour urine collection</option>
					    			<option>bladder crede</option>
					    			<option>catheter irrigated</option>
					    			<option>diapered</option>
					    			<option>indwelling catheter</option>
					    			<option>Medicated</option>
					    			<option>none</option>
					    			<option>self catheter</option>
					    			<option>specimen</option>
					    			<option>Urgency</option>
					    			<option>Spasms</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GU_bladderScan">Bladder scan residual amount (ml)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="GU_bladderScan" name="GU_bladderScan">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="GU_drainage">perineal drainage</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="GU_drainage" name="GU_drainage">
					    			<option></option>
					    			<option>none</option>
					    			<option>small</option>
					    			<option>moderate</option>
					    			<option>large</option>
					    			<option>blood clots</option>
					    			<option>blood tinged</option>
					    			<option>brown</option>
					    			<option>clear</option>
					    			<option>cloudy</option>
					    			<option>curdlike</option>
					    			<option>foul smelling</option>
					    			<option>frank blood</option>
					    			<option>green</option>
					    			<option>milky white</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="GU_genitalia">describe genitalia</label>
							<div class="col-sm-6">
					    		<textarea  class="form-control" id="GU_genitalia" name="GU_genitalia"> </textarea>
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
					    		<input type="text" class="form-control" id="msk_dlocation" name="msk_dlocation">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_glocation">girth/circum location</label>
					    	<div class="col-sm-6">
					    		<input type="text" class="form-control" id="msk_glocation" name="msk_glocation">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_gcircum">girth/circumference (cm) </label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="msk_gcircum" name="msk_gcircum">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_muscle">Muscle tone</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_muscle" name="msk_muscle">
					    			<option></option>
					    			<option>Normal</option>
					    			<option>hypotonic</option>
					    			<option>hypertonic</option>
					    			<option>R greater than L</option>
					    			<option>L greater than R</option>
					    			<option>Spastic</option>
					    			<option>Flaccid</option>
					    			<option>Weak</option>
					    			<option>Medically paralyzed</option>
					    			<option>Other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_motion">motion</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_motion" name="msk_motion">
					    			<option></option>
					    			<option>Full ROM</option>
					    			<option>Limited</option>
					    			<option>Stiff</option>
					    			<option>Contractures</option>
					    			<option>Immobile</option>
					    			<option>Posturing</option>
					    			<option>L foot drag</option>
					    			<option>R foot drag</option>
					    			<option>L hemiparesis</option>
					    			<option>R hemiparesis</option>
					    			<option>Limp</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_appearance">Appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_appearance" name="msk_appearance">
					    			<option></option>
					    			<option>Normal</option>
					    			<option>red</option>
					    			<option>Swollen</option>
					    			<option>red</option>
					    			<option>tender</option>
					    			<option>contractures</option>
					    			<option>casted</option>
					    			<option>deformity</option>
					    			<option>injury/trauma</option>
					    			<option>laceration</option>
					    			<option>unable to assess</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_support">Support device</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_support" name="msk_support">
					    			<option></option>
					    			<option>On</option>
					    			<option>off</option>
					    			<option>abductor pillow</option>
					    			<option>ACE wrap</option>
					    			<option>brace</option>
					    			<option>sling</option>
					    			<option>splint</option>
					    			<option>cast</option>
					    			<option>spica</option>
					    			<option>petal cast</option>
					    			<option>IV pole</option>
					    			<option>Ice pack</option>
					    			<option>pillows</option>
					    			<option>C Collar</option>
					    			<option>Bucks Traction</option>
					    			<option>Hare</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_weight">Weight bearing</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_weight" name="msk_weight">
					    			<option></option>
					    			<option>Total</option>
					    			<option>toe touch</option>
					    			<option>partial</option>
					    			<option>none</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="msk_degree">CPM degree</label>
							<div class="col-sm-6">
						    	<textarea class="form-control" id="msk_degree" name="msk_degree"> </textarea>
						    </div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_state">CPM on/off</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_state" name="msk_state">
					    			<option></option>
					    			<option>on</option>
					    			<option>off</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_traction">Traction (lbs)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="msk_traction" name="msk_traction">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_cap">cap refill</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="msk_cap" name="msk_cap">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_assessment"> Traction assessment</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_assessment" name="msk_assessment">
					    			<option></option>
					    			<option>Weight hanging freely</option>
					    			<option>Limb in neutral position</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_sensation">motion sensation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_sensation" name="msk_sensation">
					    			<option></option>
					    			<option>Full spontaneous</option>
					    			<option>moves fingers/toes</option>
					    			<option>responds to light touch</option>
					    			<option>hypersensitve</option>
					    			<option>hyperreflexive</option>
					    			<option>Dorsi flex</option>
					    			<option>Plantar flex</option>
					    			<option>no movement</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_extColorT">extremity color/temp</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_extColorT" name="msk_extColorT">
					    			<option></option>
					    			<option>pink</option>
					    			<option>Pale</option>
					    			<option>flushed</option>
					    			<option>mottled</option>
					    			<option>warm</option>
					    			<option>cool</option>
					    			<option>other</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="msk_intervention">CMS check intervention</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="msk_intervention" name="msk_intervention">
					    			<option></option>
					    			<option>MD notified</option>
					    			<option>Elevated</option>
					    			<option>Reposition</option>
					    			<option>Ice applied</option>
					    			<option>Cast splint</option>
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
					    			<option>Skin folds checked</option>
					    			<option>Posterior surfaces checked</option>
					    			<option>mucous membranes intact</option>
					    			<option>nail beds intact</option>
					    			<option>appropriate for ethnicity</option>
					    			<option>pink</option>
					    			<option>casted</option>
					    			<option>warm</option>
					    			<option>dry</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_location">Edema location</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_location" name="skin_location"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_description">Edema description </label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_description" name="skin_description">
					    			<option>Cold</option>
					    			<option>Warm</option>
					    			<option>Hot</option>
					    			<option>Acute</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_rlocation">Rash location</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_rlocation" name="skin_rlocation"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_rdescription">Rash description</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_rdescription" name="skin_rdescription">
					    			<option></option>
					    			<option> Blisters</option>
					    			<option>Blotchy</option>
					    			<option>Bright red</option>
					    			<option>Coarse</option>
					    			<option>Cracked</option>
					    			<option>Crusted</option>
					    			<option>Diffuse</option>
					    			<option>Dry</option>
					    			<option>Fine</option>
					    			<option>Flat</option>
					    			<option>irritated</option>
					    			<option>Itchy</option>
					    			<option>Lacy</option>
					    			<option>Large bumps</option>
					    			<option>large patches</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_appearance">Appearance</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_appearance" name="skin_appearance">
					    			<option></option>
					    			<option>adipose tissue</option>
					    			<option>approximated</option>
					    			<option>bleeding</option>
					    			<option>blistered</option>
					    			<option>crepitus</option>
					    			<option>dehisced</option>
					    			<option>dressing clean, dry, intact</option>
					    			<option>dressing with drainage</option>
					    			<option>dry</option>
					    			<option>dusky</option>
					    			<option>EB</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_lassessement">Laceration assessment</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_lassessement" name="skin_lassessement"></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_NBlocation">Nodule/Bump location</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_NBlocation" name="skin_NBlocation"></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_wlocation">Wound location</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_wlocation" name="skin_wlocation"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_NBdescription">Nodule/Bump description</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_NBdescription" name="skin_NBdescription">
					    			<option></option>
					    			<option>Pink</option>
					    			<option>red</option>
					    			<option>oozing</option>
					    			<option>hard</option>
					    			<option>cursted</option>
					    			<option>warm</option>
					    			<option>hot</option>
					    			<option>streaking</option>
					    			<option>non-streaking</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_TfirstNoticed">Time first noticed</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_TfirstNoticed" name="skin_TfirstNoticed"></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_DfirstNoticed">Date first noticed</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_DfirstNoticed" name="skin_DfirstNoticed"></textarea>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_DfirstdressingApp">Date Dressing applied/changed</label>
							<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_DfirstdressingApp" name="skin_DfirstdressingApp"></textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_intervention">skin interventions</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_intervention" name="skin_intervention">
					    			<option></option>
					    			<option>Dresssing applied</option>
					    			<option>Dressing Changed</option>
					    			<option>cleaned</option>
					    			<option>medication applied</option>
					    			<option>MD notified</option>
					    			<option>none</option>
					    			<option>antibiotic started</option>
					    			<option>compression garment</option>
					    			<option>consult</option>

					    		</select>
					    	</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" for="skin_dressing">Dressing</label>
							<div class="col-sm-6">
					    		<select class="form-control" id="skin_dressing" name="skin_dressing">
					    			<option></option>
					    			<option>Wet to dry</option>
					    			<option>Moist</option>
					    			<option>tegaderm</option>
					    			<option>mepore</option>
					    			<option>mepilex border</option>
					    			<option>mepilex border lite</option>
					    			<option>mepilex sacral border</option>
					    			<option>douderm</option>
					    			<option>sorbaview</option>
					    			<option>tape</option>
					    			<option>gauze</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_drainage">Drainage</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_drainage" name="skin_drainage">
					    			<option></option>
					    			<option>Small</option>
					    			<option>Clear</option>
					    			<option>copious</option>
					    			<option>green</option>
					    			<option>large</option>
					    			<option>malodorous</option>
					    			<option>moderate</option>
					    			<option>none</option>
					    			<option>no new drainage</option>
					    			<option>purulent</option>
					    			<option>sanguineous</option>
					    			<option>scant</option>
					    			<option>sero-sanguineous</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_treatment"> Topical Treatment</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="skin_treatment" name="skin_treatment">
					    			<option></option>
					    			<option>Aquacel</option>
					    			<option>Aquacel AG</option>
					    			<option>Aquaphor</option>
					    			<option>Aquaphor with Maalox</option>
					    			<option>Bacitracin</option>
					    			<option>Betadine</option>
					    			<option>Cavilon</option>
					    			<option>Criticaid</option>
					    			<option>Criticaid AF</option>
					    			<option>Dakin's solution</option>
					    			<option>Desitin</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="skin_comment">Comments/Remarks</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="skin_comment" name="skin_comment"></textarea>
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
		</div> <!-- end of rows -->

		<div>
			<!-- Save/Cancel buttons -->
			<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
			<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
		</div>
	</form>
</body>
</html>