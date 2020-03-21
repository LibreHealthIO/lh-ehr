<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/headers.inc.php");
formHeader("Education Form");
$returnurl = 'encounter_top.php';
?>
<html>
	<head>
		<?php html_header_show();?>
		<?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1', 'font-awesome']); ?>
		<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
		<style>
			.add-topic {
				width: 200px;
				text-align: center;
				line-height: 50px;
				background-color: #286090;
				font-size: x-large;
				color: white;
				margin: auto;
			}
			.topic {
				box-shadow: 0px 0px 6px 2px;
			}
			
			.learner-heading {
				display: flex;
				justify-content: space-between;
				flex-wrap: nowrap;
				align-items: center;
			}
			.scroll-div {
				overflow-y: scroll;
				max-height: 700px;
			}
			input:focus, textarea:focus, select:focus {
				border-color: #ca1278 !important;
				border-width: 2px !important;
			}
			.add-learner {
				padding: 5px;
				cursor: pointer;
				background: #286090;
				border-radius: 5px;
				color: white;
				font-size: large;
				margin-bottom: 5px;
			}
		</style>
	</head>
<body>
	<?php
			include_once("$srcdir/api.inc");
			$res = sqlStatement("SELECT COUNT(*) as numberOfIssues FROM `form_education` WHERE id=?", array($_GET["id"]));
    		$numberOfIssues = sqlFetchArray($res);
			$sql = "SELECT * FROM `form_education` WHERE id=? AND pid = ? AND encounter = ?";
    		$obj = sqlStatement($sql, array($_GET["id"],$_SESSION["pid"], $_SESSION["encounter"]));
			if($numberOfIssues["numberOfIssues"]) {
				$html = "<input type=\"hidden\" id=\"num\" value=" . $numberOfIssues["numberOfIssues"] . " />";
				echo $html;
			} else {
				$html = "<input type=\"hidden\" id=\"num\" value=\"1\" />";
				echo $html;
			}
			

		?>
	<form method=post action="<?php echo $rootdir;?>/forms/education/save.php?id=<?php echo attr($_GET["id"]);?>" name="my_form" onsubmit="beforeSubmit(); return top.restoreSession()">
		<div class="row">
			<div class="col-md-4">
				<!-- Save/Cancel buttons -->
				<input type="submit" id="save" class="btn btn-success" value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</div>
		<div class="row" style="padding: 20px 0;cursor: pointer">
			<div class="add-topic" onclick="addTopic()">Add  a Topic</div>
		</div>
		<div class="container-fluid">
		<div class="row addTopic">
			<?php 
				for ($iter = 0; $row = sqlFetchArray($obj); $iter++){
						$data[$iter] = $row;
				}	
				if ($data) {
					$j = 1;
					foreach ($data as $key => $value) {	    		
			?>
						<div class="col-12 col-md-6 col-lg-4 topic">
							<div >
								<div class="form-group">
								<h2 class="learner-heading">
									<label for="comment" style="font-weight:unset">Topic</label>
									<mark class="labelTopic_<?php echo $j;?>" ><?php if($value['status'] == '0'){ echo "Topic Completed"; }?></mark>
									<a href="#"><span style="color: red" onclick="inactivateTopic(<?php echo $j; ?>)" class="glyphicon glyphicon-ban-circle" title="inactivate Topic"></span> </a> 	
								</h2>
								<textarea class="form-control" name="topic[]"><?php echo text($value['topic']); ?></textarea>
								<input type="hidden" class="status_<?php echo $j;?>"  name="status[]" value="<?php echo text($value['status']); ?>">
								<input type="hidden"  name="count[]" value="<?php echo text($j); ?>">
								</div>
								<div class="learner-heading">
									<h2 style="margin:unset">Learners</h2>
									<div onclick="addLearner(<?php echo $j; ?>)" class="add-learner">Add Learner<a href="#" ><span style="color: #337ab7"  class="glyphicon glyphicon-plus-sign"></span> </a></div>
								</div>
								<div  class="panel-group scroll-div issue_<?php echo $j;?>" id="accordion<?php echo $j;?>"  >
									<?php
										$Learner =  json_decode($value['learners']);
										$Readiness =  json_decode($value['readiness']);
										$Response =  json_decode($value['response']);
										$Method =  json_decode($value['method']);
										$Intervention =  json_decode($value['interventions']);
										$LearnerStatus =  json_decode($value['learners_status']);
										for($i = count($Learner) - 1; $i >= 0; $i--) {
				                    ?>	
											<div class="panel panel-default row_<?php echo $i + 1;?>">
												<div class="panel-heading">
												<div class="learner-heading">
													<h4 class="panel-title">
													<a data-toggle="collapse" data-parent="#accordion<?php echo $j; ?>" href="#learner_<?php echo $j . $i + 1; ?>">
													Learner <?php echo $i + 1; ?> <span class="caret"></span> </a>
													<mark class="labelLearner"><?php if($LearnerStatus[$i] == '0'){ echo "Completed ED Topic"; }?></mark></td>
												</h4>
												<a href="#"><span style="color: red; font-size: large;"  onclick="inactivateLearner(<?php echo $j; ?>, <?php echo $i + 1; ?>)" class="glyphicon glyphicon-ban-circle" title="inactivate Learner"></span> </a> 
												</div>
												</div>
												<div id="learner_<?php echo $j . $i + 1; ?>"  class="panel-collapse collapse">
												<div class="panel-body">
													<input hidden="hidden" value="<?php echo text($LearnerStatus[$i]); ?>" name="status_learner_<?php echo $j; ?>[]">
													<div class="form-group" class="learner-heading">
													<label >Learner:</label>
													<textarea class="form-control" name="learners_<?php echo $j; ?>[]"> <?php echo text($Learner[$i]); ?> </textarea>
													</div>
													<div class="form-group" class="learner-heading">
													<label >Learner's Readiness for Education:</label>
													<textarea class="form-control" name="readiness_<?php echo $j; ?>[]" > <?php echo text($Readiness[$i]); ?> </textarea>
													</div>
													<div class="form-group" class="learner-heading">
													<label >Method of Education:</label>
													<textarea class="form-control" name="response_<?php echo $j; ?>[]"> <?php echo text($Response[$i]); ?> </textarea>
													</div>
													<div class="form-group" class="learner-heading">
													<label >Response to Eduction:</label>
													<textarea class="form-control" name="method_<?php echo $j; ?>[]"> <?php echo text($Method[$i]); ?> </textarea>
													</div>
													<div class="form-group" class="learner-heading">
													<label>Further interventions Needed:</label>
													<textarea class="form-control" name="interventions_<?php echo $j; ?>[]" > <?php echo text($Intervention[$i]); ?> </textarea>
													</div>
												</div>
												</div>
											</div>
									<?php
										}		                            
				                    ?>
								</div>
								<div class="form-group">
								<h2><label for="remark[]" style="font-weight:unset">Remark</label></h2>
								<textarea class="form-control" name="remark[]"><?php echo text($value['remark']); ?></textarea>
								</div>
							</div>

						</div>
			<?php
			    		$j = $j + 1;
					} //close foreach
				} else {
			?>
					<div class="col-12 col-md-6 col-lg-4 topic">
						<div >
							<div class="form-group">
							<h2 class="learner-heading">
								<label for="comment" style="font-weight:unset">Topic</label>
								<mark class="labelTopic_1"></mark>
								<a href="#"><span style="color: red" onclick="inactivateTopic(1)" class="glyphicon glyphicon-ban-circle" title="inactivate Topic"></span> </a> 
								
							</h2>
							<textarea class="form-control" name="topic[]"></textarea>
							<input type="hidden" class="status_1" name="status[]" value="1">
							<input type="hidden"  name="count[]" value="1">
							</div>
							<div class="learner-heading">
								<h2 style="margin:unset">Learners</h2>
								<div onclick="addLearner(1)" class="add-learner">Add Learner<a href="#" ><span style="color: #337ab7"  class="glyphicon glyphicon-plus-sign"></span> </a></div>
							</div>
							<div class="panel-group scroll-div issue_1" id="accordion1">
								<div class="panel panel-default row_1">
									<div class="panel-heading">
									<div class="learner-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion1" href="#learner_11">
										Learner 1 <span class="caret"></span> </a>
										<mark class="labelLearner"></mark></td>
									</h4>
									<a href="#"><span style="color: red; font-size: large;"  onclick="inactivateLearner(1, 1)" class="glyphicon glyphicon-ban-circle" title="inactivate Topic"></span> </a> 
									</div>
									</div>
									<div id="learner_11"  class="panel-collapse collapse">
									<div class="panel-body">
										<input hidden="hidden" value="1" name="status_learner_1[]">
										<div class="form-group" class="learner-heading">
										<label >Learner:</label>
										<textarea class="form-control" name="learners_1[]"></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label >Learner's Readiness for Education:</label>
										<textarea class="form-control" name="readiness_1[]" ></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label >Method of Education:</label>
										<textarea class="form-control" name="response_1[]"></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label >Response to Eduction:</label>
										<textarea class="form-control" name="method_1[]"></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label>Further interventions Needed:</label>
										<textarea class="form-control" name="interventions_1[]" ></textarea>
										</div>
									</div>
									</div>
								</div>
							</div>
							<div class="form-group">
							<h2><label for="remark[]" style="font-weight:unset">Remark</label></h2>
							<textarea class="form-control" name="remark[]"></textarea>
							</div>
						</div>
					</div>
			<?php
			        } //close else that is there is no saved topic
			?>
  		</div>
		<textarea hidden="hidden"   name="learners"></textarea>
		<textarea hidden="hidden" name="readiness"></textarea>
		<textarea hidden="hidden" name="response"></textarea>
		<textarea hidden="hidden"  name="method" ></textarea>
		<textarea hidden="hidden"  name="interventions" ></textarea>
		<input name="learner_status" hidden="hidden" />
		</div>
	</form>

	<script type="text/javascript">
		var count = $('#num').val();
		function addLearner(currentTopicNumber ) {
			const learnerNumber = $(`.issue_${currentTopicNumber}`).children();
			let learner = createLearnerHtml(currentTopicNumber, learnerNumber.length + 1);
			$(`.issue_${currentTopicNumber}`).prepend(learner);
			
		}
		
		function addTopic() {
			let topic = newTopicHtml();
			$('.addTopic').prepend(topic);
		}
		function inactivateTopic(TopicNumber) {
			console.log(TopicNumber);
			$(`.status_${TopicNumber}`).attr("value", "0");
			$(`.labelTopic_${TopicNumber}`).html("Topic Completed");
		}
		function inactivateLearner(issueNumber, TopicNumber) {
			var learner = $(`#learner_${issueNumber}${TopicNumber}`);
			learner.find(`input[name="status_learner_${TopicNumber}[]"]`).attr("value", "0");
			learner.css("background-color", "#c5c5bc");
			learner.parent().find(`.labelLearner`).html("Completed ED Topic");
			learner.find("textarea").attr("readonly","readonly");
		}
		function newTopicHtml() {
			count++;
			return `
					<div class="col-12 col-md-6 col-lg-4 topic">
						<div >
							<div class="form-group">
							<h2 class="learner-heading">
								<label for="comment" style="font-weight:unset">Topic</label>
								<mark class="labelTopic_${count}"></mark>
								<a href="#"><span style="color: red" onclick="inactivateTopic(${count})" class="glyphicon glyphicon-ban-circle" title="inactivate Topic"></span> </a> 
								
							</h2>
							<textarea class="form-control" name="topic[]"></textarea>
							<input type="hidden" class="status_${count}" name="status[]" value="1">
				  			<input type="hidden"  name="count[]" value="${count}">
							</div>
							<div class="learner-heading">
								<h2 style="margin:unset">Learners</h2>
								<div onclick="addLearner(${count})"  class="add-learner" >Add Learner<a href="#" ><span style="color: #337ab7"  class="glyphicon glyphicon-plus-sign"></span> </a></div>
							</div>
							<div class="panel-group scroll-div issue_${count}" id="accordion${count}">
								<div class="panel panel-default row_1">
									<div class="panel-heading">
									<div class="learner-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion${count}" href="#learner_${count}1">
										Learner 1 <span class="caret"></span> </a>
										<mark class="labelLearner"></mark></td>
									</h4>
									<a href="#"><span style="color: red;font-size: large;"  onclick="inactivateLearner(${count}, 1)" class="glyphicon glyphicon-ban-circle" title="inactivate Topic"></span> </a> 
									</div>
									</div>
									<div id="learner_${count}1"  class="panel-collapse collapse">
									<div class="panel-body">
										<input hidden="hidden" value="1" name="status_learner_${count}[]">
										<div class="form-group" class="learner-heading">
										<label >Learner:</label>
										<textarea class="form-control" name="learners_${count}[]"></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label >Learner's Readiness for Education:</label>
										<textarea class="form-control" name="readiness_${count}[]" ></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label >Method of Education:</label>
										<textarea class="form-control" name="response_${count}[]"></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label >Response to Eduction:</label>
										<textarea class="form-control" name="method_${count}[]"></textarea>
										</div>
										<div class="form-group" class="learner-heading">
										<label>Further interventions Needed:</label>
										<textarea class="form-control" name="interventions_${count}[]" ></textarea>
										</div>
									</div>
									</div>
								</div>
							</div>
							<div class="form-group">
							<h2><label for="remark[]" style="font-weight:unset">Remark</label></h2>
							<textarea class="form-control" name="remark[]"></textarea>
							</div>
						</div>

					</div>
					`
		}

		function createLearnerHtml(issue_number, learnerNumber){
			return 	`
						<div class="panel panel-default row_${learnerNumber}">
							<div class="panel-heading">
							<div class="learner-heading">
								<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion${issue_number}" href="#learner_${issue_number}${learnerNumber}">
								Learner ${learnerNumber} <span class="caret"></span> </a>
								<mark class="labelLearner"></mark></td>
							</h4>
							<a href="#"><span style="color: red; font-size: large"  onclick="inactivateLearner(${issue_number}, ${learnerNumber})" class="glyphicon glyphicon-ban-circle" title="inactivate Topic"></span> </a> 
							</div>
							</div>
							<div id="learner_${issue_number}${learnerNumber}"  class="panel-collapse collapse">
							<div class="panel-body">
								<input hidden="hidden" value="1" name="status_learner_${issue_number}[]">
								<div class="form-group" class="learner-heading">
								<label >Learner:</label>
								<textarea class="form-control" name="learners_${issue_number}[]"></textarea>
								</div>
								<div class="form-group" class="learner-heading">
								<label >Learner's Readiness for Education:</label>
								<textarea class="form-control" name="readiness_${issue_number}[]" ></textarea>
								</div>
								<div class="form-group" class="learner-heading">
								<label >Method of Education:</label>
								<textarea class="form-control" name="response_${issue_number}[]"></textarea>
								</div>
								<div class="form-group" class="learner-heading">
								<label >Response to Eduction:</label>
								<textarea class="form-control" name="method_${issue_number}[]"></textarea>
								</div>
								<div class="form-group" class="learner-heading">
								<label>Further interventions Needed:</label>
								<textarea class="form-control" name="interventions_${issue_number}[]" ></textarea>
								</div>
							</div>
							</div>
						</div>
					`
		}

		function beforeSubmit() {
			var learner = new Array();
			var readiness = new Array();
			var response = new Array();
			var method = new Array();
			var intervention = new Array();
			var learners_status = new Array();
			for (var i = 1; i <= count; i++){
				let value = $(`textarea[name='learners_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	learner.push(JSON.stringify(value));

             	value = $(`textarea[name='interventions_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	intervention.push(JSON.stringify(value));

              	value = $(`textarea[name='readiness_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	readiness.push(JSON.stringify(value));

              	value = $(`textarea[name='response_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	response.push(JSON.stringify(value));

              	value = $(`textarea[name='method_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	method.push(JSON.stringify(value));

              	value = $(`input[name='status_learner_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	learners_status.push(JSON.stringify(value));

			}

			objectLearner = {"learner": learner};
			objectReadiness = {"readiness": readiness};
			objectResponse = {"response": response};
			objectMethod = {"method": method};
			objectIntervention = {"intervention": intervention};
			objectLearnerStatus = {"learners_status": learners_status};
			$('textarea[name="learners"]').val(JSON.stringify(objectLearner));
            $('textarea[name="readiness"]').val(JSON.stringify(objectReadiness));
            $('textarea[name="response"]').val(JSON.stringify(objectResponse));
            $('textarea[name="method"]').val(JSON.stringify(objectMethod));
            $('textarea[name="interventions"]').val(JSON.stringify(objectIntervention));
            $('input[name="learner_status"]').val(JSON.stringify(objectLearnerStatus));

		}
	</script>

</body>
</html>