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
		<?php
			include_once("$srcdir/api.inc");
			$obj = formFetch("form_nursing_shift", $_GET["id"]);
		?>
		<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/intake/save.php?mode=update&id=<?php echo attr($_GET["id"]);?>" name="my_form" onsubmit="return top.restoreSession()">
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
									    <option <?php if ($obj['type_of_fluid']=="Normal Saline (NS)") echo " SELECTED"; ?>>Normal Saline (NS)</option>
									    <option <?php if ($obj['type_of_fluid']=="1/2 Normal Saline (1/2 NS)") echo " SELECTED"; ?>>1/2 Normal Saline (1/2 NS)</option>
									    <option <?php if ($obj['type_of_fluid']=="Lactated Ringer’s (LR)") echo " SELECTED"; ?>>Lactated Ringer’s (LR)</option>
									    <option <?php if ($obj['type_of_fluid']=="D 5 W") echo " SELECTED"; ?>>D 5 W</option>
									    <option <?php if ($obj['type_of_fluid']=="D 5 NS") echo " SELECTED"; ?>>D 5 NS</option>
									    <option <?php if ($obj['type_of_fluid']=="D 5 1/2 NS") echo " SELECTED"; ?>>D 5 1/2 NS</option>
									    <option <?php if ($obj['type_of_fluid']=="D 5 LR") echo " SELECTED"; ?>>D 5 LR</option>
									    <option <?php if ($obj['type_of_fluid']=="Normosol-R") echo " SELECTED"; ?>>Normosol-R</option>
						    		</select>
						    	</div>
							</div>

							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site__location">Site/location</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="Site__location" onchange="getComment(this,'Comment0', $(this).children(':selected').attr('class'),'CommentArea')">
							    		<option></option>
									    <option <?php if ($obj['Site__location']=="RUE") echo " SELECTED"; ?>>RUE</option>
									    <option <?php if ($obj['Site__location']=="LUE") echo " SELECTED"; ?>>LUE</option>
									    <option <?php if ($obj['Site__location']=="RLE") echo " SELECTED"; ?>>RLE</option>
									    <option <?php if ($obj['Site__location']=="LLE") echo " SELECTED"; ?>>LLE</option>
									    <option class ="CommentArea" <?php if ($obj['Site__location']!="RUE" || $obj['Site__location']!="LUE" || $obj['Site__location']!="RLE" || $obj['Site__location']!="LLE" ) echo " SELECTED"; ?>><?php echo text($obj{"Site__location"});?></option>
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
						    		<input type="number" value="<?php echo text($obj{'Hourly_volume_infused___ml____'});?>" class="form-control" name="Hourly_volume_infused___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Fluid_start_time">Fluid start time</label>
						    	<div class="col-sm-6">
						    		<input type="time" class="form-control" value="<?php echo text($obj{'Fluid_start_time'});?>" name="Fluid_start_time">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Fluid_stop_time">Fluid stop time</label>
						    	<div class="col-sm-6">
						    		<input type="time" value="<?php echo text($obj{'Fluid_stop_time'});?>" class="form-control" name="Fluid_stop_time">
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
						    		<input type="number" value="<?php echo text($obj{'IV_fluid___in_ml____'});?>" class="form-control" name="IV_fluid___in_ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="OR__ED_blood_products___ml____">OR/ED blood products (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" value="<?php echo text($obj{'OR__ED_blood_products___ml____'});?>" class="form-control" name="OR__ED_blood_products___ml____">
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
						    		<input type="number" value="<?php echo text($obj{'IV_meds__Flush___ml____'});?>" class="form-control" name="IV_meds__Flush___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site__location_meds">Site/location</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="Site__location_meds" onchange="getComment(this,'Comment1', $(this).children(':selected').attr('class'),'CommentArea')">
							    		<option></option>
									    <option <?php if ($obj['Site__location_meds']=="RUE") echo " SELECTED"; ?>>RUE</option>
									    <option <?php if ($obj['Site__location_meds']=="LUE") echo " SELECTED"; ?>>LUE</option>
									    <option <?php if ($obj['Site__location_meds']=="RLE") echo " SELECTED"; ?>>RLE</option>
									    <option <?php if ($obj['Site__location_meds']=="LLE") echo " SELECTED"; ?>>LLE</option>
									    <option class ="CommentArea" <?php if ($obj['Site__location_meds']!="RUE" || $obj['Site__location_meds']!="LUE" || $obj['Site__location_meds']!="RLE" || $obj['Site__location_meds']!="LLE" ) echo " SELECTED"; ?>><?php echo text($obj{"Site__location_meds"});?></option>
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
									    <option <?php if ($obj['type_of_fluid_meds']=="Normal Saline (NS)") echo " SELECTED"; ?>>Normal Saline (NS)</option>
									    <option <?php if ($obj['type_of_fluid_meds']=="1/2 Normal Saline (1/2 NS)") echo " SELECTED"; ?>>1/2 Normal Saline (1/2 NS)</option>
									    <option <?php if ($obj['type_of_fluid_meds']=="Lactated Ringer’s (LR)") echo " SELECTED"; ?>>Lactated Ringer’s (LR)</option>
									    <option <?php if ($obj['type_of_fluid_meds']=="D 5 W") echo " SELECTED"; ?>>D 5 W</option>
									    <option <?php if ($obj['type_of_fluid_meds']=="D 5 NS") echo " SELECTED"; ?>>D 5 NS</option>
									    <option <?php if ($obj['type_of_fluid_meds']=="D 5 1/2 NS") echo " SELECTED"; ?>>D 5 1/2 NS</option>
									    <option <?php if ($obj['type_of_fluid_meds']=="D 5 LR") echo " SELECTED"; ?>>D 5 LR</option>
									    <option <?php if ($obj['type_of_fluid_meds']=="Normosol-R") echo " SELECTED"; ?>>Normosol-R</option>
						    		</select>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="other_fluid_additive___medication____">other fluid additive (medication)</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="other_fluid_additive___medication____"><?php echo text($obj{"other_fluid_additive___medication____"});?></textarea> 
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
						    		<input type="number" value="<?php echo text($obj{'PO_amount___ml____'});?>" class="form-control" name="PO_amount___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="PO_fluids_type">PO fluids type</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="PO_fluids_type"><?php echo text($obj{'PO_fluids_type'});?></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="PO_meal_">PO meal %</label>
						    	<div class="col-sm-6">
						    		<input type="number" value="<?php echo text($obj{'PO_meal_'});?>" class="form-control" name="PO_meal_">
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
									    <option <?php if ($obj['Line_type']=="Peripheral") echo " SELECTED"; ?>>Peripheral</option>
									    <option <?php if ($obj['Line_type']=="Midline") echo " SELECTED"; ?>>Midline</option>
									    <option <?php if ($obj['Line_type']=="Central / Hickman") echo " SELECTED"; ?>>Central / Hickman</option>
									    <option <?php if ($obj['Line_type']=="Central / non- tunnelled") echo " SELECTED"; ?>>Central / non- tunnelled</option>
									    <option <?php if ($obj['Line_type']=="PICC") echo " SELECTED"; ?>>PICC</option>
									    <option <?php if ($obj['Line_type']=="Port") echo " SELECTED"; ?>>Port</option>
						    		</select>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site_assessment">Site assessment</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="Site_assessment"><?php echo text($obj{'Site_assessment'});?></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="site_interventions">site interventions</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control"  name="site_interventions"><?php echo text($obj{'site_interventions'});?></textarea>
						    	</div>
							</div>							
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="dressing_type">dressing type</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="dressing_type"><?php echo text($obj{'dressing_type'});?></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="line_status">line status</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="line_status" onchange="getComment(this,'Comment2', $(this).children(':selected').attr('class'),'CommentArea')">
						    			<option></option>
									    <option <?php if ($obj['line_status']=="patent") echo " SELECTED"; ?>>patent</option>
									    <option class ="CommentArea" <?php if ($obj['line_status']!="patent") echo " SELECTED"; ?>><?php echo text($obj{'line_status'});?></option>
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
						    		<textarea class="form-control" name="line_interventions"><?php echo text($obj{'line_interventions'});?></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Cap_change_date">Cap change date</label>
						    	<div class="col-sm-6">
						    		<input type="date" value="<?php echo text($obj{'Cap_change_date'});?>" class="form-control" name="Cap_change_date">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="dressing_change_date">dressing change date</label>
						    	<div class="col-sm-6">
						    		<input type="date" value="<?php echo text($obj{'dressing_change_date'});?>" class="form-control" name="dressing_change_date">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="clinician_attempting">clinician attempting</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="clinician_attempting"><?php echo text($obj{'clinician_attempting'});?></textarea>
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
									    <option <?php if ($obj['Types_of_draw__stick']=="Vacuum tube") echo " SELECTED"; ?>>Vacuum tube</option>
									    <option <?php if ($obj['Types_of_draw__stick']=="Butterfly needle") echo " SELECTED"; ?>>Butterfly needle</option>
									    <option <?php if ($obj['Types_of_draw__stick']=="Syringe") echo " SELECTED"; ?>>Syringe</option>
									    <option <?php if ($obj['Types_of_draw__stick']=="Finger stick") echo " SELECTED"; ?>>Finger stick</option>
									    <option <?php if ($obj['Types_of_draw__stick']=="Heel stick") echo " SELECTED"; ?>>Heel stick</option>
						    		</select>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Draw__stick__attempt_locations">Draw/stick/attempt locations</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="Draw__stick__attempt_locations" onchange="getComment(this,'Comment3', $(this).children(':selected').attr('class'),'CommentArea')">
						    			<option></option>
									    <option <?php if ($obj['Draw__stick__attempt_locations']=="RUE") echo " SELECTED"; ?>>RUE</option>
									    <option <?php if ($obj['Draw__stick__attempt_locations']=="LUE") echo " SELECTED"; ?>>LUE</option>
									    <option <?php if ($obj['Draw__stick__attempt_locations']=="RLE") echo " SELECTED"; ?>>RLE</option>
									    <option <?php if ($obj['Draw__stick__attempt_locations']=="LLE") echo " SELECTED"; ?>>LLE</option>
									    <option class ="CommentArea" <?php if ($obj['Draw__stick__attempt_locations']!="RUE" || $obj['Draw__stick__attempt_locations']!="LUE" || $obj['Draw__stick__attempt_locations']!="RLE" || $obj['Draw__stick__attempt_locations']!="LLE" ) echo " SELECTED"; ?>><?php echo text($obj{"Draw__stick__attempt_locations"});?></option>
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
									    <option <?php if ($obj['GI_tube_type']=="NG") echo " SELECTED"; ?>>NG</option>
									    <option <?php if ($obj['GI_tube_type']=="GT") echo " SELECTED"; ?>>GT</option>
									    <option <?php if ($obj['GI_tube_type']=="TP") echo " SELECTED"; ?>>TP</option>
									    <option <?php if ($obj['GI_tube_type']=="JT") echo " SELECTED"; ?>>JT</option>
									    <option class ="CommentArea" <?php if ($obj['GI_tube_type']!="NG" || $obj['GI_tube_type']!="GT" || $obj['GI_tube_type']!="TP" || $obj['GI_tube_type']!="JT") echo " SELECTED"; ?>>Etc(Comment)</option>
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
										    <option <?php if ($obj['Current_tube_use']=="intake") echo " SELECTED"; ?>>intake</option>
										    <option <?php if ($obj['Current_tube_use']=="output") echo " SELECTED"; ?>>output</option>
							    		</select>
							    	</div>
							</div>
							<div class="form-group">
							    	<label class="control-label col-sm-4" for="Date_GI_tube_changed">Date GI tube changed</label>
							    	<div class="col-sm-6">
							    		<input type="date" value="<?php echo text($obj{'Date_GI_tube_changed'});?>" class="form-control" name="Date_GI_tube_changed">	
							    	</div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Reason_tube_changed">Reason tube changed</label>
							    <div class="col-sm-6">
							    	<textarea type="date" class="form-control" name="Reason_tube_changed"><?php echo text($obj{'Reason_tube_changed'});?></textarea>
							    </div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="GI_tube_status">GI tube status</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="GI_tube_status" onchange="getComment(this,'Comment', $(this).children(':selected').attr('class'),'CommentArea')">
						    			<option></option>
									   <option <?php if ($obj['GI_tube_status']=="patent") echo " SELECTED"; ?>>patent</option>
									   <option class ="CommentArea" <?php if ($obj['GI_tube_status']!="patent") echo " SELECTED"; ?>><?php echo text($obj{'line_status'});?></option>
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
							    	<textarea type="date" class="form-control" name="GI_tube_interventions"><?php echo text($obj{'GI_tube_interventions'});?></textarea>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="NG__OG_placement_confirmation">NG/OG placement confirmation</label>
							    <div class="col-sm-6">
							    	<select class="form-control" name="NG__OG_placement_confirmation">
							    		<option></option>
										<option <?php if ($obj['NG__OG_placement_confirmation']=="yes") echo " SELECTED"; ?>>yes</option>
										<option <?php if ($obj['NG__OG_placement_confirmation']=="no") echo " SELECTED"; ?>>no</option>
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
										<option <?php if ($obj['Exit_point_marker_visible_at_lip__nare']=="yes") echo " SELECTED"; ?>>yes</option>
										<option <?php if ($obj['Exit_point_marker_visible_at_lip__nare']=="no") echo " SELECTED"; ?>>no</option>
							    	</select>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Exit_point_value">Exit point value</label>
							    <div class="col-sm-6">
							    	<input type="number" value="<?php echo text($obj{'Exit_point_value'});?>" class="form-control" name="Exit_point_value">
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="GI_tube_site_assessment">GI tube site assessment</label>
							    <div class="col-sm-6">
							    	<textarea class="form-control" name="GI_tube_site_assessment"><?php echo text($obj{'GI_tube_site_assessment'});?></textarea>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="GI_tube_site_intervention">GI tube site intervention</label>
							    <div class="col-sm-6">
							    	<textarea type="date" class="form-control" name="GI_tube_site_intervention"><?php echo text($obj{'GI_tube_site_intervention'});?></textarea>
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Irrigation_in">Irrigation in</label>
							    <div class="col-sm-6">
							    	<input type="number" value="<?php echo text($obj{'Irrigation_in'});?>" class="form-control" name="Irrigation_in">
							    </div>
							</div>
							<div class="form-group">
							    <label class="control-label col-sm-4" for="Irrigation_out">Irrigation out</label>
							    <div class="col-sm-6">
							    	<input type="number" value="<?php echo text($obj{'Irrigation_out'});?>" class="form-control" name="Irrigation_out">
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