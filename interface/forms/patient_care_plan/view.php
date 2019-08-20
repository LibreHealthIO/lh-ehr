<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/headers.inc.php");
formHeader("Patient Care Plan Form");
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
		<?php
			include_once("$srcdir/api.inc");
			$res2 = sqlStatement("SELECT MAX(id) as largestId FROM `form_patient_care_plan`");
    		$getMaxid = sqlFetchArray($res2);
			$obj = formFetch("form_patient_care_plan", $_GET["id"]);

			if ($getMaxid['largestId']) {
				$count = $getMaxid['largestId'] + 1 ;
				$html = "<input type=\"hidden\" id=\"num\" value=" . $count . " />";
				echo $html;
		        
		    } else {
		        // echo "<input type=\"hidden\" id=\"num\" value=\"1\" />";
    		}
		?>
		<form class="form-horizontal" method=post action="<?php echo $rootdir;?>/forms/patient_care_plan/save.php?mode=update&id=<?php echo attr($_GET["id"]);?>" name="my_form" onsubmit="return top.restoreSession()">
			<div class="row">
					<!-- Save/Cancel buttons -->
				<input type="submit" id="save" class='btn btn-success' value="<?php echo xla('Save'); ?>"> &nbsp;
				<input type="button" id="dontsave" class="deleter btn btn-danger" value="<?php echo xla('Cancel'); ?>"> &nbsp;
			</div>
		</form>

		<script type="text/javascript">
		var count = $('#num').val();
		console.log(count); 
		function addIntervention(currentIssueNumber ) {
			let intervention = createInterventionHtml(currentIssueNumber);
			$(`.intervention_${currentIssueNumber}`).prepend(intervention);
		}
		
		function addIssue() {
			let newIssue = newIssueHtml();
			$('.addIssue').prepend(newIssue);

		}
		function deactivateIssue(issueNumber) {
			$(`.status_${issueNumber}`).attr("value", "0");
			$(`#row_${issueNumber}`).css("background-color", "#c5c5bc");
			$(`#row_${issueNumber}`).find("input, textarea").attr("readonly","readonly");
		}
		function newIssueHtml() {
			count++;
			return `<tr id="row_${count}">
					  	<td>
					  		<mark>${count}</mark>
					  		<a href="#" ><span style="color: #337ab7" onclick="addIntervention(${count})" class="glyphicon glyphicon-plus-sign" title="Add Interventions"></span> </a>
					  		<a href="#"><span style="color: red" onclick="deactivateIssue(${count})" class="glyphicon glyphicon-ban-circle" title="Deactivate Issue"></span> </a> 
					  	</td>
					  	<td><input type="text" name="issue[]" placeholder="key issue"></td>
					  	<input type="hidden" class="status_${count}" name="status[]" value="1">
					  	<input type="hidden"  name="count[]" value="${count}">
					  	<td>
					      <table>
					      	<thead>
					      		<tr>
								   <th>Intervention</th>
								   <th>Outcome</th> 
								   <th>Goal</th>
								   <th>Progress</th>
								</tr>
					      	</thead>
					      	<tbody class="intervention_${count}">
					      		<tr>
						          	<td><textarea name="intervention_${count}[]"></textarea></td>
								    <td><textarea name="outcome_${count}[]"></textarea></td>
								    <td><textarea name="goal_${count}[]"></textarea></td>
								    <td><textarea name="progress_${count}[]"></textarea></td>   
						        </tr>
					      	</tbody>   
					      </table>
					    </td>
				  </tr>
				  `
		}

		function createInterventionHtml(issue_number){
			return `<tr>
					    <td><textarea name="intervention_${count}[]"></textarea></td>
					    <td><textarea name="outcome_${count}[]"></textarea></td>
					    <td><textarea name="goal_${count}[]"></textarea></td>
					    <td><textarea name="progress_${count}[]"></textarea></td>  
				    </tr>
				 `
		}

		function beforeSubmit() {
			var intervention = new Array();
			var outcome = new Array();
			var goal = new Array();
			var progress = new Array();
			for (var i = 1; i <= count; i++){
				let int = $(`textarea[name='intervention_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	intervention.push(JSON.stringify(int));

              	 let out = $(`textarea[name='outcome_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	outcome.push(JSON.stringify(out));

              	 let gol = $(`textarea[name='goal_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	goal.push(JSON.stringify(gol));

              	 let prog = $(`textarea[name='progress_${i}[]']`)
              					.map(function(){return $(this).val();}).get();
              	progress.push(JSON.stringify(prog));

			}

			objectIntervention = {"intervention": intervention};
			objectOutcome = {"outcome": outcome};
			objectGoal = {"goal": goal};
			objectProgress = {"progress": progress};
			console.log(JSON.stringify(objectIntervention));

			$('textarea[name="Interventions"]').val(JSON.stringify(objectIntervention));
            $('textarea[name="Outcome"]').val(JSON.stringify(objectOutcome));
            $('textarea[name="Goal"]').val(JSON.stringify(objectGoal));
            $('textarea[name="Progress"]').val(JSON.stringify(objectProgress));

		}
	</script>

	</body>
</html>