<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/headers.inc.php");
formHeader("Nursing Shift Form");
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
			input type="number":focus, textarea:focus, select:focus {
				border-color: #ca1278 !important;
				border-width: 2px !important;
			}
		</style>
	</head>
<body>
	<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/nursing_shift/save.php?mode=new" name="my_form" onsubmit="return top.restoreSession()">
		<div class="row">
			<div class="col-md-4">
				<!-- Save/Cancel buttons -->
				<input  type="submit" id="save" class="btn btn-success" value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-md-3">
				<!-- Section Braden Q Scale -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Braden Q Scale','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Mobility">Mobility</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Mobility" name="Mobility">
					    			<option value="0"></option>
								    <option value="1">1  completely immobile</option>
								    <option value="2">2 very limited</option>
								    <option value="3"> 3 slightly limited</option>
								    <option value="4">4 no limitations</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pt_Activity">Activity</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="pt_Activity" name="pt_Activity">
					    			<option value="0"></option>
								    <option value="1">1  confined to bed</option>
								    <option value="2">2  chairfast</option>
								    <option value="3">3  walks occassionally</option>
								    <option value="4">4  walks frequently</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Sensory_Perception">Sensory Perception</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Sensory_Perception" name="Sensory_Perception">
					    			<option value="0"></option>
								    <option value="1">1  completely limited</option>
								    <option value="2">2  very limited</option>
								    <option value="3">3  slightly limited</option>
								    <option value="4">4  no impairment</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Moisture">Moisture</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Moisture" name="Moisture">
					    			<option value="0"></option>
								    <option value="1">1  constantly moist</option>
								    <option value="2">2  very moist</option>
								    <option value="3">3  occassionally moist</option>
								    <option value="4">4  rarely moist</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Friction__Shear">Friction/Shear</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Friction__Shear" name="Friction__Shear">
					    			<option value="0"></option>
								    <option value="1">1  significant problem</option>
								    <option value="2">2  problem</option>
								    <option value="3">3  potential problem</option>
								    <option value="4">4  no apparent problem</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Nutrition">Nutrition</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Nutrition" name="Nutrition">
					    			<option value="0"></option>
								    <option value="1">1  very poor</option>
								    <option value="2">2  inadequate</option>
								    <option value="3">3  adequate</option>
								    <option value="4">4  excellent</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Tissue__Perfusion__Oxygenation">Tissue/Perfusion/Oxygenation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Tissue__Perfusion__Oxygenation" name="Tissue__Perfusion__Oxygenation">
					    			<option value="0"></option>
								    <option value="1">1  extremely compromised</option>
								    <option value="2">2  compromised</option>
								    <option value="3">3  adequate</option>
								    <option value="4">4  excellent</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Total_score">Total Score</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="Total_score" name="Total_score" >
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Interventions">Interventions</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="Interventions" name="Interventions"></textarea>
					    	</div>
						</div>
					</div> <!-- end of panel body -->			
				</div> <!-- end of panel primary -->
			</div> <!-- end of col-4 -->
			<div class="col-sm-6 col-md-2">
				<!-- Humpty Dumpty Fall Risk Assessment Score -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Humpty Dumpty Fall Risk Assessment Score','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Age">Age</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Age" name="Age">
					    			<option value="0"></option>
								    <option value="4">4 less than 3 y/o</option>
								    <option value="3">3 3 to less than 7 y/o</option>
								    <option value="2">2 7 to less than 13 y/o</option>
								    <option value="1">1 13 years and older</option>
					    		</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Gender">Gender</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Gender" name="Gender">
					    			<option value="0"></option>
								    <option value="2">2 male</option>
								    <option value="1">1 female</option>
					    		</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Diagnosis">Diagnosis</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Diagnosis" name="Diagnosis">
					    			<option value="0"></option>
								    <option value="4">4 neurological diagnosis</option>
								    <option value="3">3 alterations in oxygenation - respiratory issue</option>
								    <option value="2">2 psych/behavioral disorders</option>
								    <option value="1">1 other diagnosis</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Cognitive_impairments">Cognitive impairments</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Cognitive_impairments" name="Cognitive_impairments">
					    			<option value="0"></option>
								    <option value="3">3 not aware of own limitations</option>
								    <option value="2">2 forgets limitations</option>
								    <option value="1">1 oriented to own ability</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Environmental_factors">Environmental factors</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Environmental_factors" name="Environmental_factors">
					    			<option value="0"></option>
								    <option value="4">4 hx of falls</option>
								    <option value="3">3 patient uses assistive devices</option>
								    <option value="2">2 patient placed in bed</option>
								    <option value="1">1 patient in outpatient area</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Response_to_surgery__sedation__anesthesia">Response to surgery/sedation/anesthesia</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Response_to_surgery__sedation__anesthesia" name="Response_to_surgery__sedation__anesthesia">
					    			<option value="0"></option>
								    <option value="3">3 within 24 hours</option>
								    <option value="2">2 within 48 hours</option>
								    <option value="1">1 more than 48 hrs/none</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Medication_usage">Medication usage</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Medication_usage" name="Medication_usage">
					    			<option value="0"></option>
								    <option value="3">3 multiple usage of sedative medications</option>
								    <option value="2">2 use of one sedative medication</option>
								    <option value="1">1 other medications/none</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Total_score_Risk_level">Total score Risk level</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="Total_score_Risk_level" name="Total_score_Risk_level" >
					    	</div>
						</div>
					</div> <!-- end of panel body -->
				</div> <!-- end of panel primary -->
			</div> <!-- end of col-4 -->
			<div class="col-sm-6 col-md-3">
				<!-- Bedside Safety  -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Bedside Safety ','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="ID_band_location">ID band location</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="ID_band_location" name="ID_band_location">
					    			<option></option>
								    <option>RUE</option>
								    <option>LUE</option>
								    <option>RLE</option>
								    <option>LLE</option>
					    		</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Bed_type">Bed type</label>
					    	<div class="col-sm-6">
					    		<textarea class="form-control" id="Bed_type" name="Bed_type">
					    		</textarea>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Bed_safety_assessment_done">Bed safety assessment done:</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Bed_safety_assessment_done" name="Bed_safety_assessment_done">
					    			<option></option>
								    <option>Yes</option>
								    <option>No</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Whiteboard_filled_out">Whiteboard filled out</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Whiteboard_filled_out" name="Whiteboard_filled_out">
					    			<option></option>
								    <option>Yes</option>
								    <option>No</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Standard_safety_items_present_at_bedside">Standard safety items present at bedside (list) </label>
					    	<div class="col-sm-6">  		
					    		<textarea class="form-control" id="Standard_safety_items_present_at_bedside" name="Standard_safety_items_present_at_bedside">
					    		</textarea>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Alarms_audible_if_in_use">Alarms audible if in use</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Alarms_audible_if_in_use" name="Alarms_audible_if_in_use">
					    			<option></option>
								    <option>3 within 24 hours</option>
								    <option>2 within 48 hours</option>
								    <option>1 more than 48 hrs/none</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Medication_usage">Medication usage</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Medication_usage" name="Medication_usage">
					    			<option></option>
								    <option>3 multiple usage of sedative medications</option>
								    <option>2 use of one sedative medication</option>
								    <option>1 other medications/none</option>
					    		</select>
					    	</div>
						</div>
						
					</div> <!-- end of panel body -->
				</div> <!-- end of panel primary -->
			</div> <!-- end of col-4 -->
			<div class="col-sm-6 col-md-2">
				<!-- Alarm limits set  -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Alarm limits set','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="HR_alarm_limit_high">HR alarm limit high</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="HR_alarm_limit_high" name="HR_alarm_limit_high" >
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="RR_alarm_limit_low">RR alarm limit low</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="RR_alarm_limit_low" name="RR_alarm_limit_low" >
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="RR_alarm_limit_high">RR alarm limit high</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="RR_alarm_limit_high" name="RR_alarm_limit_high" >
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Apnea_delay">Apnea delay</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="Apnea_delay" name="Apnea_delay" >
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="SPO2_alarm_limit_low">SPO2 alarm limit low</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="SPO2_alarm_limit_low" name="SPO2_alarm_limit_low" >
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="SPO2_alarm_limit_high">SPO2 alarm limit high</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="SPO2_alarm_limit_high" name="SPO2_alarm_limit_high" >
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BP_alarm_limit_low">BP alarm limit low</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="BP_alarm_limit_low" name="BP_alarm_limit_low" >
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BP_alarm_limit_high">BP alarm limit high</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="BP_alarm_limit_high" name="BP_alarm_limit_high" >
					    	</div>
						</div>
					</div> <!-- end of panel body -->
				</div> <!-- end of panel primary -->
			</div> <!-- end of col-4 -->
			<div class="col-sm-6 col-md-2">
				<!-- Alarm limits set  -->
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><?php xl('Measurements','e'); ?></h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Weight">Weight (kg)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="Weight" name="Weight">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Wt_source">Wt source</label>
					    	<div class="col-sm-6">
					    		<input type="number"  class="form-control" id="Wt_source" name="Wt_source">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Height">Height (cm)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="Height" name="Height">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Height_source">Height source</label>
					    	<div class="col-sm-6">
					    		<input type="number"  class="form-control" id="Height_source" name="Height_source">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BMI">BMI</label>
					    	<div class="col-sm-6">
					    		<input type="number"  class="form-control" id="BMI" name="BMI">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="BSA">BSA</label>
					    	<div class="col-sm-6">
					    		<input type="number" type="number" class="form-control" id="BSA" name="BSA">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Head_circumference">Head circumference (cm)</label>
					    	<div class="col-sm-6">
					    		<input type="number" class="form-control" id="Head_circumference" name="Head_circumference">
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="abd_girth">abd girth (cm)</label>
					    	<div class="col-sm-6">
					    		<input type="number"  class="form-control" id="abd_girth" name="abd_girth">
					    	</div>
						</div>
					</div> <!-- end of panel body -->	
				</div> <!-- end of panel primary -->
			</div> <!-- end of col-4 -->
		</div>
		<div>
			<!-- Save/Cancel buttons -->
			<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
			<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
		</div>
	</form>
	<script>
		let humpty_dumpty = {
			Total_score_Risk_level: 0,
			Age: 0,
			Gender: 0,
			Diagnosis: 0, 
			Cognitive_impairments: 0,
			Environmental_factors: 0,
			Response_to_surgery__sedation__anesthesia: 0,
			Medication_usage: 0,
		}
		let braden_q_scale = {
			Total_score: 0,
			Mobility: 0,
			pt_Activity: 0,
			Sensory_Perception: 0, 
			Moisture: 0,
			Friction__Shear: 0,
			Nutrition: 0,
			Tissue__Perfusion__Oxygenation: 0,
		}
		document.addEventListener('input', function (event) {
				switch (event.target.id) {
					case 'Age':
						humpty_dumpty.Age = Number(event.target.value);
						break;
					case 'Gender':
						humpty_dumpty.Gender = Number(event.target.value);
						break;
					case 'Diagnosis':
						humpty_dumpty.Diagnosis = Number(event.target.value);
						break;
					case 'Cognitive_impairments':
						humpty_dumpty.Cognitive_impairments = Number(event.target.value);
						break;
					case 'Environmental_factors':
						humpty_dumpty.Environmental_factors = Number(event.target.value);
						break;
					case 'Mobility':
						braden_q_scale.Mobility = Number(event.target.value);
						break;
					case 'pt_Activity':
						braden_q_scale.pt_Activity = Number(event.target.value);
						break;
					case 'Sensory_Perception':
						braden_q_scale.Sensory_Perception = Number(event.target.value);
						break;
					case 'Moisture':
						braden_q_scale.Moisture = Number(event.target.value);
						break;
					case 'Friction__Shear':
						braden_q_scale.Friction__Shear = Number(event.target.value);
						break;
					case 'Nutrition':
						braden_q_scale.Nutrition = Number(event.target.value);
						break;
					case 'Tissue__Perfusion__Oxygenation':
						braden_q_scale.Tissue__Perfusion__Oxygenation = Number(event.target.value);
						break;
				}
				if (humpty_dumpty.hasOwnProperty(event.target.id)) {
					humpty_dumpty.Total_score_Risk_level = 0;
					for (const key in humpty_dumpty) {
						if (humpty_dumpty.hasOwnProperty(key)) {
							humpty_dumpty.Total_score_Risk_level += Number(humpty_dumpty[key]);
						}
					}
					if (humpty_dumpty.Total_score_Risk_level > 0) {
						document.getElementById('Total_score_Risk_level').value = humpty_dumpty.Total_score_Risk_level;
					} else {
						document.getElementById('Total_score_Risk_level').value = '';
					}
				} else if (braden_q_scale.hasOwnProperty(event.target.id)) {
					braden_q_scale.Total_score = 0;
					for (const key in braden_q_scale) {
						if (braden_q_scale.hasOwnProperty(key)) {
							braden_q_scale.Total_score += Number(braden_q_scale[key]);
						}
					}
					if (braden_q_scale.Total_score > 0) {
						document.getElementById('Total_score').value = braden_q_scale.Total_score;
					} else {
						document.getElementById('Total_score').value = '';
					}
				}
			}, false);
		$(document).ready(function() {
			$('.form-horizontal').submit(function() {
				$(this).find(':input').filter(function() { return !this.value; }).attr('disabled', 'disabled');
				return true;
			});
		});
	</script>
</body>
</html>