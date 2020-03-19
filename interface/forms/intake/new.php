<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/headers.inc.php");
formHeader("Intake Form");
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
		<script type="text/javascript">
			var currentCommentDiv;
			function getComment(select, element, focused, className){ 
				 currentCommentDiv = element;
				if(focused === className){
					$(`#${element}`).show();
				}else{
					$(`#${element}`).hide();
				}
			}
			function fillComment(element, option){
				$(`.${option}`).text(element);
				$(`#${currentCommentDiv}`).hide();
			}
		</script>
	</head>
	<body>
		<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/intake/save.php?mode=new" name="my_form" onsubmit="return top.restoreSession()">
			<div class="row">
				<div class="col-md-4">
					<!-- Save/Cancel buttons -->
					<input  type="submit" id="save" class="btn btn-success" value="<?php echo xla('Save'); ?>"> &nbsp;
					<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-sm-6">
					<!-- IV fluids  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('IV fluids','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="type_of_fluid">type of fluid</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" id="type_of_fluid" name="type_of_fluid">
							    		<option></option>
									    <option>Normal Saline (NS)</option>
									    <option>1/2 Normal Saline (1/2 NS)</option>
									    <option>Lactated Ringer’s (LR)</option>
									    <option>D 5 W</option>
									    <option>D 5 NS</option>
									    <option>D 5 1/2 NS</option>
									    <option>D 5 LR</option>
									    <option>Normosol-R</option>
						    		</select>
						    	</div>
							</div>

							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site__location">Site/location</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="Site__location" onchange="getComment(this,'Comment0', $(this).children(':selected').attr('class'),'CommentArea')">
							    		<option></option>
									    <option>RUE</option>
									    <option>LUE</option>
									    <option>RLE</option>
									    <option>LLE</option>
									    <option class ="CommentArea">Other (comment)</option>
						    		</select>
						    		<div id="Comment0" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Hourly_volume_infused___ml____">Hourly volume infused (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Hourly_volume_infused___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Fluid_start_time">Fluid start time</label>
						    	<div class="col-sm-6">
						    		<input type="time" class="form-control" name="Fluid_start_time">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Fluid_stop_time">Fluid stop time</label>
						    	<div class="col-sm-6">
						    		<input type="time" class="form-control" name="Fluid_stop_time">
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div>  <!-- end of col -->
				<div class="col-md-4 col-sm-6">
					<!-- OR fluid Intake  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('OR fluid Intake','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="IV_fluid___in_ml____">IV fluid (in ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="IV_fluid___in_ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="OR__ED_blood_products___ml____">OR/ED blood products (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="OR__ED_blood_products___ml____">
						    	</div>
							</div>

						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class="col-md-4 col-sm-6">
					<!-- IV Meds/Flush  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('IV Meds/Flush','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="IV_meds__Flush___ml____">IV meds/Flush (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="IV_meds__Flush___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site__location_meds">Site/location</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="Site__location_meds" onchange="getComment(this,'Comment1', $(this).children(':selected').attr('class'),'CommentArea')">
							    		<option></option>
									    <option>RUE</option>
									    <option>LUE</option>
									    <option>RLE</option>
									    <option>LLE</option>
									    <option class ="CommentArea">Other (comment)</option>
						    		</select>
						    		<div id="Comment1" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="type_of_fluid_meds">type of fluid</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="type_of_fluid_meds">
							    		<option></option>
									    <option>Normal Saline (NS)</option>
									    <option>1/2 Normal Saline (1/2 NS)</option>
									    <option>Lactated Ringer’s (LR)</option>
									    <option>D 5 W</option>
									    <option>D 5 NS</option>
									    <option>D 5 1/2 NS</option>
									    <option>D 5 LR </option>
									    <option>Normosol-R</option>
						    		</select>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="other_fluid_additive___medication____">other fluid additive (medication)</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="other_fluid_additive___medication____"></textarea> 
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
			</div> <!-- end of row -->
			<div class="row">
				<div class="col-md-3 col-sm-6">
					<!-- Current diet  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Current diet','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="PO_amount___ml____">PO amount (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="PO_amount___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="PO_fluids_type">PO fluids type</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="PO_fluids_type"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="PO_meal_">PO meal %</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="PO_meal_">
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class="col-md-3 col-sm-6">
					<!-- Access  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Access','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Line_type">Line type</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="Line_type">
						    			<option></option>
									    <option>Peripheral</option>
									    <option>Midline</option>
									    <option>Central / Hickman</option>
									    <option>Central / non- tunnelled</option>
									    <option>PICC</option>
									    <option>Port</option>
						    		</select>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site_assessment">Site assessment</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="Site_assessment"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="site_interventions">site interventions</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control"  name="site_interventions"></textarea>
						    	</div>
							</div>							
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="dressing_type">dressing type</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="dressing_type"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="line_status">line status</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="line_status" onchange="getComment(this,'Comment2', $(this).children(':selected').attr('class'),'CommentArea')">
						    			<option></option>
									    <option>patent</option>
									    <option class ="CommentArea">Other (Comment)</option>
						    		</select>
						    		<div id="Comment2" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="line_interventions">line interventions</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="line_interventions"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Cap_change_date">Cap change date</label>
						    	<div class="col-sm-6">
						    		<input type="date" class="form-control" name="Cap_change_date">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="dressing_change_date">dressing change date</label>
						    	<div class="col-sm-6">
						    		<input type="date" class="form-control" name="dressing_change_date">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="clinician_attempting">clinician attempting</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="clinician_attempting"></textarea>
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class="col-md-3 col-sm-6">
					<!-- Alarm limits set  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Venipunctures/Finger Sticks/Peripheral IV Attempts/','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Types_of_draw__stick">Types of draw/stick</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="Types_of_draw__stick">
						    			<option></option>
									    <option>Vacuum tube</option>
									    <option>Butterfly needle</option>
									    <option>Syringe</option>
									    <option>Finger stick</option>
									    <option>Heel stick</option>
						    		</select>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Draw__stick__attempt_locations">Draw/stick/attempt locations</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="Draw__stick__attempt_locations" onchange="getComment(this,'Comment3', $(this).children(':selected').attr('class'),'CommentArea')">
						    			<option></option>
									    <option>RUE</option>
									    <option>LUE</option>
									    <option>RLE</option>
									    <option>LLE</option>
									    <option class ="CommentArea">Other (comment)</option>
						    		</select>
						    		<div id="Comment3" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="number_of_attempts"># of attempts</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="number_of_attempts">
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class="col-md-3 col-sm-6">
					<!-- GI tube -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('GI tube','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="GI_tube_type">GI tube type</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="GI_tube_type" onchange="getComment(this,'Comment5', $(this).children(':selected').attr('class'),'CommentArea')">
						    			<option></option>
									    <option>NG</option>
									    <option>GT</option>
									    <option>TP</option>
									    <option>JT</option>
									    <option class ="CommentArea">Etc (comment)</option>
						    		</select>
						    		<div id="Comment5" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
							    	<label class="control-label col-sm-4" for="Current_tube_use">Current tube use</label>
							    	<div class="col-sm-6">
							    		<select class="form-control" name="Current_tube_use">
							    			<option></option>
										    <option>intake</option>
										    <option>output</option>
							    		</select>
							    	</div>
							</div>
							<div class="form-group">
							    	<label class="control-label col-sm-4" for="Date_GI_tube_changed">Date GI tube changed</label>
							    	<div class="col-sm-6">
							    		<input type="date" class="form-control" name="Date_GI_tube_changed">	
							    	</div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Reason_tube_changed">Reason tube changed</label>
							    <div class="col-sm-6">
							    	<textarea type="date" class="form-control" name="Reason_tube_changed"></textarea>
							    </div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="GI_tube_status">GI tube status</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="GI_tube_status" onchange="getComment(this,'Comment', $(this).children(':selected').attr('class'),'CommentArea')">
						    			<option></option>
									    <option>patent</option>
									    <option class ="CommentArea">other (Comment)</option>
						    		</select>
						    		<div id="Comment" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentArea')">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="GI_tube_interventions">GI tube interventions</label>
							    <div class="col-sm-6">
							    	<textarea type="date" class="form-control" name="GI_tube_interventions"></textarea>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="NG__OG_placement_confirmation">NG/OG placement confirmation</label>
							    <div class="col-sm-6">
							    	<select class="form-control" name="NG__OG_placement_confirmation">
							    		<option></option>
										<option>yes</option>
										<option>no</option>
							    	</select>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="pH_value">pH value</label>
							    <div class="col-sm-6">
							    	<input type="number" class="form-control" name="pH_value">
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Exit_point_marker_visible_at_lip__nare">Exit point marker visible at lip/nare?</label>
							    <div class="col-sm-6">
							    	<select class="form-control" name="Exit_point_marker_visible_at_lip__nare">
							    		<option></option>
										<option>yes</option>
										<option>no</option>
							    	</select>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Exit_point_value">Exit point value</label>
							    <div class="col-sm-6">
							    	<input type="number" class="form-control" name="Exit_point_value">
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="GI_tube_site_assessment">GI tube site assessment</label>
							    <div class="col-sm-6">
							    	<textarea class="form-control" name="GI_tube_site_assessment"></textarea>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="GI_tube_site_intervention">GI tube site intervention</label>
							    <div class="col-sm-6">
							    	<textarea type="date" class="form-control" name="GI_tube_site_intervention"></textarea>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Irrigation_in">Irrigation in</label>
							    <div class="col-sm-6">
							    	<input type="number" class="form-control" name="Irrigation_in">
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Irrigation_out">Irrigation out</label>
							    <div class="col-sm-6">
							    	<input type="number" class="form-control" name="Irrigation_out">
							    </div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
			</div><!-- end of row -->
			<div>
				<!-- Save/Cancel buttons -->
				<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</form>
	</body>
</html>