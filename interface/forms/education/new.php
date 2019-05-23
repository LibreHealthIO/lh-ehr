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
			table, th, td {
			  border: 0px;
			  border-collapse: collapse;
			}
			th, td {
			  padding: 5px;
			  text-align: left;    
			}
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
	<form method=post action="<?php echo $rootdir;?>/forms/education/save.php?mode=new" name="my_form" onsubmit="beforeSubmit(); return top.restoreSession()">
		<div class="row">
			<div class="col-md-4">
				<!-- Save/Cancel buttons -->
				<input type="submit" id="save" class="btn btn-success" value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</div>
		
		<table style="width:100%">
			<thead>
				<tr>
				    <th>Topic #</th>
				    <th>Education Topic <span><a href="#" ><span title="add topic" onclick="addTopic()" style="color: #337ab7" class="glyphicon glyphicon-plus-sign"></span></a></span></th> 
				    <th>Learners </th> 
				    <th>Remarks </th> 
		 	 	</tr>
			</thead>

			<tbody class="addTopic">
				<tr id="row_1">
				  	<td>
				  		<mark>1</mark>
				  		<a href="#" ><span style="color: #337ab7" onclick="addLearner(1)" class="glyphicon glyphicon-plus-sign" title="Add Learner"></span> </a>
				  		<a href="#"><span style="color: red" onclick="inactivateLearner(1)" class="glyphicon glyphicon-ban-circle" title="inactivate learner"></span> </a> 
				  	</td>
				  	<td><input type="text" name="topic[]" placeholder="Education topic"></td>
				  	<input type="hidden" class="status_1" name="status[]" value="1">
				  	<input type="hidden"  name="count[]" value="1">
				  	<td>
				      <table>
				      	<thead>
				      		<tr>
							   <th>Learners</th>
							   <th>Learner's Readiness for Education</th> 
							   <th>Method of Education</th>
							   <th>Response to Eduction</th>
							   <th>Further interventions Needed</th>
							</tr>
				      	</thead>
				      	<tbody class="learner_1">
				      		<tr>
					          <td><textarea name="learners_1[]"></textarea></td>
					          <td><textarea name="readiness_1[]"></textarea></td>
					          <td><textarea name="response_1[]"></textarea></td>
					          <td><textarea name="method_1[]"></textarea></td>  
					          <td><textarea name="interventions_1[]"></textarea></td>  
					        </tr>
				      	</tbody>   
				      </table>
				    </td>  
				    <td><textarea name="remark[]"></textarea></td>  
			  </tr>
			</tbody>
		  
		</table>
		<textarea hidden="hidden"   name="learners"></textarea>
		<textarea hidden="hidden" name="readiness"></textarea>
		<textarea hidden="hidden" name="response"></textarea>
		<textarea hidden="hidden"  name="method" ></textarea>
		<textarea hidden="hidden"  name="interventions" ></textarea>

	</form>

	<script type="text/javascript">
		var count = 1; 
		function addLearner(currentIssueNumber ) {
			let intervention = createInterventionHtml(currentIssueNumber);
			$(`.intervention_${currentIssueNumber}`).prepend(intervention);
		}
		
		function addTopic() {
			let newIssue = newIssueHtml();
			$('.addTopic').prepend(newIssue);

		}
		function inactivateLearner(issueNumber) {
			$(`.status_${issueNumber}`).attr("value", "0");
			$(`#row_${issueNumber}`).css("background-color", "#c5c5bc");
			$(`#row_${issueNumber}`).find("input, textarea").attr("readonly","readonly");
		}
		function newIssueHtml() {
			count++;
			return `<tr id="row_${count}">
					  	<td>
					  		<mark>${count}</mark>
					  		<a href="#" ><span style="color: #337ab7" onclick="addLearner(${count})" class="glyphicon glyphicon-plus-sign" title="Add Learners"></span> </a>
					  		<a href="#"><span style="color: red" onclick="deactivateIssue(${count})" class="glyphicon glyphicon-ban-circle" title="Deactivate Topic"></span> </a> 
					  	</td>
					  	<td><input type="text" name="topic[]" placeholder="Education topic"></td>
					  	<input type="hidden" class="status_${count}" name="status[]" value="1">
					  	<input type="hidden"  name="count[]" value="${count}">
					  	<td>
					      <table>
					      	<thead>
					      		<tr>
								   <th>Learners</th>
								   <th>Learner's Readiness for Education</th> 
								   <th>Method of Education</th>
								   <th>Response to Eduction</th>
								   <th>Further interventions Needed</th>
								</tr>
					      	</thead>
					      	<tbody class="learner_${count}">
					      		<tr>
						          	<td><textarea name="learners_${count}[]"></textarea></td>
								    <td><textarea name="readiness_${count}[]"></textarea></td>
								    <td><textarea name="response_${count}[]"></textarea></td>
								    <td><textarea name="method_${count}[]"></textarea></td>   
								    <td><textarea name="interventions_${count}[]"></textarea></td>   
						        </tr>
					      	</tbody>   
					      </table>
					    </td>
					    <td><textarea name="remark[]"></textarea></td>  
				  </tr>
				  `
		}

		function createInterventionHtml(issue_number){
			return `<tr>
			          	<td><textarea name="learners_${count}[]"></textarea></td>
					    <td><textarea name="readiness_${count}[]"></textarea></td>
					    <td><textarea name="response_${count}[]"></textarea></td>
					    <td><textarea name="method_${count}[]"></textarea></td>   
					    <td><textarea name="interventions_${count}[]"></textarea></td>   
			        </tr>
				 `
		}

		function beforeSubmit() {
			var learner = new Array();
			var readiness = new Array();
			var response = new Array();
			var method = new Array();
			var intervention = new Array();
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

			}

			objectLearner = {"learner": learner};
			objectReadiness = {"readiness": readiness};
			objectResponse = {"response": response};
			objectMethod = {"method": method};
			objectIntervention = {"intervention": intervention};

			$('textarea[name="learners"]').val(JSON.stringify(objectLearner));
            $('textarea[name="readiness"]').val(JSON.stringify(objectReadiness));
            $('textarea[name="response"]').val(JSON.stringify(objectResponse));
            $('textarea[name="method"]').val(JSON.stringify(objectMethod));
            $('textarea[name="interventions"]').val(JSON.stringify(objectIntervention));

		}
	</script>
</body>
</html>