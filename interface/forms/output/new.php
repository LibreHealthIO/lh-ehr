<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/headers.inc.php");
formHeader("Output Form");
$returnurl = 'encounter_top.php';
?>
<html>
	<head>
		<?php html_header_show();?>
		<?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1', 'font-awesome']); ?>
		<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
		<style>
			.col-sm-6 ,.col-md-3{
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
			}
		</script>
	</head>
	<body>
		<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/output/save.php?mode=new" name="my_form" onsubmit="return  top.restoreSession()">
			<div class="row">
				<div class="col-md-4">
					<!-- Save/Cancel buttons -->
					<input  type="submit" id="save" class="btn btn-success" value="<?php echo xla('Save'); ?>"> &nbsp;
					<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
				</div>
			</div>
			<div class="row">
				<div class=" col-sm-6">
					<!--Urine Output  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Urine Output','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Urine___ml____">Urine (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Urine___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Urine_occurrence___not_measured____">Urine occurrence: not measured</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Urine_occurrence___not_measured____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Output_Urine_description">Urine description</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="Output_Urine_description"></textarea>
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
					<!-- OR/Trauma Output  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('OR/Trauma Output','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="OR__ED_Estimated_Blood_Loss___ml____">OR/ED Estimated Blood Loss (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="OR__ED_Estimated_Blood_Loss___ml____">
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div>  <!-- end of col -->
				<div class=" col-sm-6">
					<!-- Urine Drain -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Urine Drain','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Urine_drain_type">Urine drain type</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="Urine_drain_type"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Urin__drain_status">Urine drain status</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="Urin__drain_status" onchange="getComment(this,'Comment', $(this).children(':selected').attr('class'),'CommentAreaUrineDrainStatus')">
						    			<option></option>
									    <option>patent</option>
									    <option class ="CommentAreaUrineDrainStatus">Other (Comment)</option>
						    		</select>
						    		<div id="Comment" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentAreaUrineDrainStatus'); $(this).parent().hide()">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Output_Intervention">Intervention</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="Output_Intervention"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site__appearance">Site appearance</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="Site__appearance" onchange="getComment(this,'Comment1', $(this).children(':selected').attr('class'),'CommentAreaSiteAppearance')">
						    			<option></option>
									    <option>clean/ dry/ no complications</option>
									    <option class ="CommentAreaSiteAppearance">Other (Comment)</option>
						    		</select>
						    		<div id="Comment1" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentAreaSiteAppearance'); $(this).parent().hide()">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Site__intervention">Site intervention</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="Site__intervention" onchange="getComment(this,'Comment2', $(this).children(':selected').attr('class'),'CommentAreaSiteInter')">
						    			<option></option>
									    <option>None</option>
									    <option class ="CommentAreaSiteInter">Comment</option>
						    		</select>
						    		<div id="Comment2" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentAreaSiteInter'); $(this).parent().hide()">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Urine_drain_output">Urine drain output</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Urine_drain_output">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="urine_description">urine description</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="urine_description"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Irrigation_in___ml____">Irrigation in (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Irrigation_in___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Irrigation_out___ml____">Irrigation out (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Irrigation_out___ml____">
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class=" col-sm-6">
					<!-- Stool Output  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Stool Output','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Stool___ml____">Stool (ml)</label>
						    	<div class="col-sm-6">
						    		<input class="form-control" type="number" name="Stool___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="stool_occurrence___times_not_measured____">stool occurrence: times not measured</label>
						    	<div class="col-sm-6">
						    		<input class="form-control" type="number" name="stool_occurrence___times_not_measured____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="stool_description">stool description</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="stool_description"></textarea> 
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class=" col-sm-6">
					<!-- Rectal Tube  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Rectal Tube','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Rectal_tube_type">Rectal tube type</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="Rectal_tube_type"></textarea> 
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Rectal_stool_description">stool description</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control" name="Rectal_stool_description"></textarea> 
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
			</div> <!-- end of row -->
			<div class="row">
				<div class=" col-sm-6">
					<!-- Current diet  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Urine/Stool Mix Output','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Urine_stool_mix___mls____">Urine stool mix (mls)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Urine_stool_mix___mls____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Urine_stool_mix_occurrence___times_not_measured____">Urine stool mix occurrence: times not measured</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Urine_stool_mix_occurrence___times_not_measured____">
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class=" col-sm-6">
					<!-- Emesis output  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Emesis output','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="emesis___ml___">emesis  (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="emesis___ml___">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="emesis_occurrence___times_not_measured____">emesis occurrence:  times not measured</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="emesis_occurrence___times_not_measured____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="emesis_description">emesis description</label>
						    	<div class="col-sm-6">
						    		<textarea class="form-control"  name="emesis_description"></textarea>
						    	</div>
							</div>	
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class=" col-sm-6">
					<!-- Blood output  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Blood output','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="blood___labs______ml____">blood (labs) (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="blood___labs______ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Estimated_Blood_Loss___ml____">Estimated Blood Loss  (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Estimated_Blood_Loss___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Ultrafuiltrate___ml____">Ultrafuiltrate  (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Ultrafuiltrate___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="Dialysate___ml____">Dialysate  (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="Dialysate___ml____">
						    	</div>
							</div>	
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
					<!-- Tubes/Drains  -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('Tubes/Drains','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="tube_type">tube type</label>
						    	<div class="col-sm-6">
						    		<textarea type="date" class="form-control" name="tube_type"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="tube_status">tube status</label>
						    	<div class="col-sm-6">
						    		<select class="form-control"  name="tube_status" onchange="getComment(this,'Comment5', $(this).children(':selected').attr('class'),'CommentAreaTubeStatus')">
						    			<option></option>
									    <option>patent</option>
									    <option class ="CommentAreaTubeStatus">Other (Comment)</option>
						    		</select>
						    		<div id="Comment5" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentAreaTubeStatus'); $(this).parent().hide()">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="tube_interventions">tube interventions</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="tube_interventions" onchange="getComment(this,'Comment4', $(this).children(':selected').attr('class'),'CommentAreaTubeInter')">
						    		<option></option>
						    		<option>None</option>
									    <option class ="CommentAreaTubeInter">Comment</option>
						    		</select>
						    		<div id="Comment4" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentAreaTubeInter'); $(this).parent().hide()">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="site_assess">site assess</label>
						    	<div class="col-sm-6">
						    		<textarea type="date" class="form-control" name="site_assess"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="drain_amount___ml____">drain amount (ml)</label>
						    	<div class="col-sm-6">
						    		<input type="number" class="form-control" name="drain_amount___ml____">
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="drainage_character">drainage character</label>
						    	<div class="col-sm-6">
						    		<textarea type="date" class="form-control" name="drainage_character"></textarea>
						    	</div>
							</div>
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="drain_amount___ml____">bedside emergency supplies</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="drain_amount___ml____">
						    			<option></option>
						    			<option>Present</option>
						    			<option>Not Present</option>
						    		</select>
						    	</div>
							</div>
						</div> <!-- end of panel body -->	
					</div> <!-- end of panel primary -->
				</div> <!-- end of col -->
				<div class=" col-sm-6">
					<!-- GI tube -->
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php xl('GI tube','e'); ?></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
						    	<label class="control-label col-sm-4" for="GI_tube_type">GI tube type</label>
						    	<div class="col-sm-6">
						    		<select class="form-control" name="GI_tube_type" onchange="getComment(this,'Comment3', $(this).children(':selected').attr('class'),'CommentAreaGITube')">
						    			<option></option>
									    <option>NG</option>
									    <option>GT</option>
									    <option>TP</option>
									    <option>JT</option>
									    <option class ="CommentAreaGITube">Etc (comment)</option>
						    		</select>
						    		<div id="Comment3" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentAreaGITube'); $(this).parent().hide()">OK</span>
									</div>
						    	</div>
							</div>
							<div class="form-group">
							    	<label class="control-label col-sm-4" for="Current_tube_use">Current tube use</label>
							    	<div class="col-sm-6">
							    		<select class="form-control" name="Current_tube_use">
							    			<option></option>
										    <option>Intake</option>
										    <option>Output</option>
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
						    		<select class="form-control" name="GI_tube_status" onchange="getComment(this,'Comment', $(this).children(':selected').attr('class'),'CommentAreaGITubeStatus')">
						    			<option></option>
									    <option>patent</option>
									    <option class ="CommentAreaGITubeStatus">other (Comment)</option>
						    		</select>
						    		<div id="Comment" style="display: none">
										<textarea class="form-control" placeholder="write your comment here"></textarea>
										<span style="background-color:#9696ad;padding: 5px; float: right;margin: 2px;" onclick="fillComment($(this).prev().val(), 'CommentAreaGITubeStatus'); $(this).parent().hide()">OK</span>
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