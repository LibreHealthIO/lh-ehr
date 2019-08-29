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
					    			<option></option>
								    <option>1  completely immobile</option>
								    <option>2 very limited</option>
								    <option> 3 slightly limited</option>
								    <option>4 no limitations</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="pt_Activity">Activity</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="pt_Activity" name="pt_Activity">
					    			<option></option>
								    <option>1  confined to bed</option>
								    <option>2  chairfast</option>
								    <option>3  walks occassionally</option>
								    <option>4  walks frequently</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Sensory_Perception">Sensory Perception</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Sensory_Perception" name="Sensory_Perception">
					    			<option></option>
								    <option>1  completely limited</option>
								    <option>2  very limited</option>
								    <option>3  slightly limited</option>
								    <option>4  no impairment</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Moisture">Moisture</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Moisture" name="Moisture">
					    			<option></option>
								    <option>1  constantly moist</option>
								    <option>2  very moist</option>
								    <option>3  occassionally moist</option>
								    <option>4  rarely moist</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Friction__Shear">Friction/Shear</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Friction__Shear" name="Friction__Shear">
					    			<option></option>
								    <option>1  significant problem</option>
								    <option>2  problem</option>
								    <option>3  potential problem</option>
								    <option>4  no apparent problem</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Nutrition">Nutrition</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Nutrition" name="Nutrition">
					    			<option></option>
								    <option>1  very poor</option>
								    <option>2  inadequate</option>
								    <option>3  adequate</option>
								    <option>4  excellent</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Tissue__Perfusion__Oxygenation">Tissue/Perfusion/Oxygenation</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Tissue__Perfusion__Oxygenation" name="Tissue__Perfusion__Oxygenation">
					    			<option></option>
								    <option>1  extremely compromised</option>
								    <option>2  compromisede</option>
								    <option>3  adequate</option>
								    <option>4  excellent</option>
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
					    			<option></option>
								    <option>4 less than 3 y/o</option>
								    <option>3 3 to less than 7 y/o</option>
								    <option>2 7 to less than 13 y/o</option>
								    <option>1 13 years and older</option>
					    		</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Gender">Gender</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Gender" name="Gender">
					    			<option></option>
								    <option>2 male</option>
								    <option>1 female</option>
					    		</select>
					    	</div>
						</div>

						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Diagnosis">Diagnosis</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Diagnosis" name="Diagnosis">
					    			<option></option>
								    <option>4 neurological diagnosis</option>
								    <option>3 alterations in oxygenation - respiratory issue</option>
								    <option>dehydration</option>
								    <option>2 psych/behavioral disorders</option>
								    <option>1 other diagnosis</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Cognitive_impairments">Cognitive impairments</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Cognitive_impairments" name="Cognitive_impairments">
					    			<option></option>
								    <option>3 not aware of own limitations</option>
								    <option>2 forgets limitations</option>
								    <option>1 oriented to own ability</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Environmental_factors">Environmental factors</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Environmental_factors" name="Environmental_factors">
					    			<option></option>
								    <option>4 hx of falls</option>
								    <option>3 patient uses assistive devices</option>
								    <option>2 patient placed in bed</option>
								    <option>1 patient in outpatient area</option>
					    		</select>
					    	</div>
						</div>
						<div class="form-group">
					    	<label class="control-label col-sm-4" for="Response_to_surgery__sedation__anesthesia">Response to surgery/sedation/anesthesia</label>
					    	<div class="col-sm-6">
					    		<select class="form-control" id="Response_to_surgery__sedation__anesthesia" name="Response_to_surgery__sedation__anesthesia">
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
</body>
</html>