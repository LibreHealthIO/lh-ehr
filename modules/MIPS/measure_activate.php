<?php
/**
 * Display PQRS Measures for (de)activation
 *
 * Copyright (C) 2015 - 2017      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <leebc@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */

// SANITIZE ALL ESCAPES
$sanitize_all_escapes = true;

// STOP FAKE REGISTER GLOBALS
$fake_register_globals = false;

require_once '../../interface/globals.php';
require_once $srcdir.'/api.inc';
require_once("$srcdir/headers.inc.php");
//Include Bootstrap
call_required_libraries(array("bootstrap"));


$updateStatus = array(
	'off' => array(),
	'on' => array()
);

if(array_key_exists('action', $_GET) && $_GET['action'] == 'save') {
	if(array_key_exists('pqrsRules', $_POST)) {
		$pqrsRules = $_POST['pqrsRules'];
	} else {
		$pqrsRules = array();
	}

	if(array_key_exists('pqrsRulesInitial', $_POST)) {
		$pqrsRulesInitial = $_POST['pqrsRulesInitial'];
	} else {
		$pqrsRulesInitial = array();
	}

	foreach($pqrsRulesInitial as $pqrsRule => $pqrsRuleActive) {
		if(($pqrsRuleActive == '1' && !array_key_exists($pqrsRule, $pqrsRules)) ||
			($pqrsRuleActive == '0' && array_key_exists($pqrsRule, $pqrsRules))
		) {
			$pqrsRuleActive = ($pqrsRuleActive == 1 ? 0 : 1);

			sqlStatementNoLog('UPDATE `clinical_rules`
				SET `active` = ?
				WHERE `id` = ?;',
				array(
					$pqrsRuleActive,
					$pqrsRule
				)
			);

			$updateStatus[($pqrsRuleActive == 0 ? 'off' : 'on')][] = $pqrsRule;
		}
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
			<span class='title' visibility: hidden><?php echo htmlspecialchars( xl('MIPS Measure Selection'), ENT_NOQUOTES); ?></span>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">

		<style>
h1, h2, h3 {
	margin-bottom: 0.25ex;
}
#measures ul {
	padding-left: 1em;
	display: inline;
}
#measures li {
	list-style: none;
}
.updateStatus {
	color: #007c00;
}
.updateCount {
	font-weight: bold;
}
.checkbox-button {
	display: inline-block;
}
.checkbox-button label {
	background-color: #808080;
	border: 1px solid #333333;
	border-radius: 4px;
	color: #f0f0f0;
	display: inline-block;
	font-family: monospace;
	margin: 0.25ex 0.15ex 0.15ex 0ex;
	padding: 0 0.75ex;
	text-size: 16pt;
}
.checkbox-button label:before {
	color: #ff0000;
	content: '\2718';
	padding-right: 0.5ex;
}
.checkbox-button input[type=checkbox]:checked + label {
	color: #333333;
	background-color: #f0f0f0;
}
.checkbox-button input[type=checkbox]:checked + label:before {
	color: #007c00;
	content: '\2714';
}
.checkbox-button input[type=checkbox] {
	display: none;
}
.quickselect {
	border: 1px solid #333333;
	border-radius: 4px;
	color: #333333;
	display: inline-block;
	font-family: monospace;
	margin: 0.25ex 0.15ex 0.15ex 0ex;
	padding: 0 0.75ex;
	font-size: 9pt;
}
.ma-title {
	font-size: 20px; 
	margin-left: 15px;
}
.row{
	margin: 0;
}
		</style>
		<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
		<script language="JavaScript">
$(document).ready(function() {
	$('input[type=checkbox]').change(function(e) {
		var checked = $(this).prop('checked'),
			container = $(this).parent(),
			siblings = container.siblings();

		container.find('input[type=checkbox]').prop({
			indeterminate: false,
			checked: checked
		});

		function checkSiblings(element) {
			var parent = element.parent().parent(),
			all = true;

			element.siblings().each(function() {
				return all = ($(this).children('input[type=checkbox]').prop('checked') === checked);
			});

			if(all && checked) {
				parent.children('input[type=checkbox]').prop({
					indeterminate: false,
					checked: checked
				});

				checkSiblings(parent);
			} else if(all && !checked) {
				parent.children('input[type=checkbox]').prop('checked', checked);
				parent.children('input[type=checkbox]').prop('indeterminate', (parent.find('input[type=checkbox]:checked').length > 0));

				checkSiblings(parent);
			} else {
				element.parents('li').children('input[type=checkbox]').prop({
					indeterminate: true,
					checked: false
				});
			}
		}

		checkSiblings(container);
	});

	$('#measures ul').each(function() {
		measure = $(this).children('li.checkbox-button').first();

		if(measure.length) {
			checkbox = $(measure).children('input[type=checkbox]');
			$(checkbox).click();
			$(checkbox).click();
		}
	});

});

	function quickSelect(specialty) {
		var mymeasurelist;
		console.log('Called quickSelect() with ' + specialty);
		switch(specialty) {

                        case "MIPS-1 Allergy/Immunology":
                                mymeasurelist = [110,111,130,226,238,317,338,340,374,402];
                                break;
                        case "MIPS-2 Anesthesiology":
                                mymeasurelist = [44,76,404,424,426,427,430,463];
                                break;
                        case "MIPS-3 Cardiology":
                                mymeasurelist = [5,6,7,8,47,118,128,130,204,226,236,238,243,317,322,323,324,326,344,345,374,402,431,438,441,442];
                                break;
                        case "MIPS-4 Dermatology":
                                mymeasurelist = [130,137,138,224,226,265,317,337,374,402,410,440];
                                break;
                        case "MIPS-5 Diagnostic Radiology":
                                mymeasurelist = [145,146,147,195,225,359,360,361,362,363,364,405,406,436];
                                break;
                        case "MIPS-6 Electrophysiology Cardiac Specialist":
                                mymeasurelist = [348,392,393];
                                break;
                        case "MIPS-7 Emergency Medicine":
                                mymeasurelist = [66,91,93,116,187,254,255,317,331,332,333,415,416];
                                break;
                        case "MIPS-8 Gastroenterology":
                                mymeasurelist = [47,128,130,185,226,271,275,317,320,343,374,390,401,402,425,431,439];
                                break;
                        case "MIPS-9 General Oncology":
                                mymeasurelist = [47,102,130,143,144,226,250,317,374,402,431,449,450,451,452,453,454,455,456,457];
                                break;
                        case "MIPS-10 General Practice/Family Medicine":
                                mymeasurelist = [1,5,6,7,8,24,39,47,48,50,65,66,91,93,109,110,111,112,113,116,117,119,126,128,130,134,154,155,181,204,226,238,243,317,326,331,332,333,334,337,338,342,370,374,383,387,394,398,400,401,402,408,412,414,418,431,438,441,442,443,444,447,464];
                                break;
                        case "MIPS-11 General Surgery":
                                mymeasurelist = [21,23,46,47,128,130,226,317,355,356,357,358,374,402];
                                break;
                        case "MIPS-12 Hospitalists":
                                mymeasurelist = [5,8,47,76,130,407];
                                break;
                        case "MIPS-13 Infectious Disease":
                                mymeasurelist = [65,66,91,93,110,111,116,128,130,176,205,226,275,331,332,333,334,337,338,340,387,390,394,400,401,407,447,464];
                                break;
                        case "MIPS-14 Internal Medicine":
                                mymeasurelist = [1,5,6,7,8,24,39,47,48,50,91,93,117,119,126,128,130,134,154,155,181,204,226,238,243,317,326,331,332,333,334,337,338,342,370,374,383,387,398,400,401,402,408,412,414,418,431,438,441,442,443,444,447];
                                break;
                        case "MIPS-15 Interventional Radiology":
                                mymeasurelist = [76,145,374,409,413,420,421,437,465];
                                break;
                        case "MIPS-16 Mental Health/Behavioral Health/Dementia":
                                mymeasurelist = [128,130,134,181,226,282,283,286,288,317,325,370,374,383,391,402,411,431];
                                break;
                        case "MIPS-17 Nephrology":
                                mymeasurelist = [1,46,47,110,111,119,122,182,317,318,327,328,330,400,403];
                                break;
                        case "MIPS-18 Neurology":
                                mymeasurelist = [47,130,154,155,226,268,282,283,286,288,290,291,293,317,374,386,402,408,412,414,419,431,435];
                                break; 
                        case "MIPS-19 Neurosurgical":
                                mymeasurelist = [21,23,130,187,226,345,346,409,413,459,460,461];
                                break; 
                        case "MIPS-20 Obstetrics and Gynecology":
                                mymeasurelist = [47,48,50,110,112,128,130,226,236,265,317,374,402,418,422,428,429,431,432,433,434,443,447,448];
                                break;
                        case "MIPS- 21 Ophlamology":
                                mymeasurelist = [12,14,19,117,130,140,141,191,192,226,303,374,384,385,388,389];
                                break;
                        case "MIPS-22 Orthopedic Surgery":
                                mymeasurelist = [21,23,24,46,47,109,128,130,131,134,154,155,178,179,180,226,317,350,351,352,353,358,374,402,408,412,414,418,459,460,461];
                                break;
                        case "MIPS-23 Otolaryngology":
                                mymeasurelist = [21,23,47,65,91,93,110,111,128,130,154,155,226,265, 276,277,278,279,317,331,332,333,334,357,358,374,398,402,431,464];
                                break;
                        case "MIPS-24 Pathology":
                                mymeasurelist = [99,100,249,250,251,395,396,397];
                                break;
                        case "MIPS-25 Pediatrics":
                                mymeasurelist = [65,66,91,93,110,134,205,391,394,398,402,444,447,464,467];
                                break;
                        case "MIPS-26 Physical Medicine":
                                mymeasurelist = [47,109,128,130,131,154,155,182,226,317,374,402,408,412,414,431];
                                break;
                        case "MIPS-27 Plastic Surgery":
                                mymeasurelist = [21,23,130,226,317,355,356,357,358];
                                break;
                        case "MIPS-28 Podiatry":
                                mymeasurelist = [126,127,128,154,155,226];
                                break;
                        case "MIPS-29 Preventive Medicine":
                                mymeasurelist = [1,24,39,47,48,109,110,111,112,113,116,119,126,128,130,134,154,155,226,317,374,402,431,438];
                                break; 
                        case "MIPS-30 Radiation Oncology":
                                mymeasurelist = [102,143,144,156];
                                break;
                        case "MIPS-31 Rheumatology":
                                mymeasurelist = [24,39,47,110,111,128,130,131,176,177,178,179,180,226,236,238,317,374,402];
                                break;
                        case "MIPS-32 Thoracic Surgery":
                                mymeasurelist = [21,23,43,47,130,164,165,166,167,168,226,236,317,358,374,402,441,445];
                                break;
                        case "MIPS-33 Urology":
                                mymeasurelist = [23,47,48,50,102,104,119,128,130,131,226,265,317,358,374,428,429,431,432,433,434];
                                break;
                        case "MIPS-34 Vascular Surgery":
                                mymeasurelist = [21,23,47,128,130,226,236,257,258,259,260,317,344,345,346,347,357,358,374,402,417,420,423,441];
                                break;

			default:
				console.log('Something Else!');
		}
		console.log('mymeasurelist = '+mymeasurelist);
		for (var mymeasure of mymeasurelist) {
			//console.log('For ' + mymeasure);
			mymeasurestring="#PQRS_"+("000" + mymeasure).slice(-4);
			$(mymeasurestring).prop('checked', true);
		}
	};

		</script>
	</head>
	<body class="body_top">
		<form action="?action=save" method="post">
			<h2>MIPS Measure Selector</h2>
<?php

if(count($updateStatus['off']) || count($updateStatus['on'])) {

?>
			<p class="updateStatus">Updated <span class="updateCount"><?php echo count($updateStatus['off']) + count($updateStatus['on']); ?></span> measures [<?php echo (count($updateStatus['off']) ? ' Off: <span class="updateCount">'.count($updateStatus['off']).'</span>' : '').(count($updateStatus['on']) ? ' On: <span class="updateCount">'.count($updateStatus['on']).'</span>' : ''); ?> ]</p>
<?php

}

?>
			<div id="measures">
				<div class="row"><p><span class="align-middle"><input class="btn btn-success btn-sm align-middle" type="submit" value="Update" /></span><span class="align-middle ma-title">MIPS Specialty Sets for 2018</span></p></div>
				<div class="row">
							<button type="button" class="quickselect" onclick="quickSelect('MIPS-1 Allergy/Immunology')">Allergy/Immunology</button>
							<button type="button" class="quickselect" onclick="quickSelect('MIPS-2 Anesthesiology')">Anesthesiology</button>
							<button type="button" class="quickselect" onclick="quickSelect('MIPS-3 Cardiology')">Cardiology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-4 Dermatology')">Dermatology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-5 Diagnostic Radiology')">Diagnostic Radiology</button>               
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-6 Electrophysiology Cardiac Specialist')">Electrophysiology Cardiac Specialist</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-7 Emergency Medicine')">Emergency Medicine</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-8 Gastroenterology')">Gastroenterology</button> 
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-9 General Oncology')">General Oncology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-10 General Practice/Family Medicine')">General Practice/Family Medicine</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-11 General Surgery')">General Surgery</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-12 Hospitalists')">Hospitalists</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-13 Infectious Disease')">Infectious Disease</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-14 Internal Medicine')">Internal Medicine</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-15 Interventional Radiology')">Interventional Radiology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-16 Mental Health/Behavioral Health/Dementia')">Mental Health/Behavioral Health/Dementia</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-17 Nephrology')">Nephrology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-18 Neurology')">Neurology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-19 Neurosurgical')">Neurosurgical</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-20 Obstetrics and Gynecology')">Obstetrics and Gynecology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-21 Ophlamology')">Ophlamology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-22 Orthopedic Surgery')">Orthopedic Surgery</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-23 Otolaryngology')">Otolaryngology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-24 Pathology')">Pathology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-25 Pediatrics')">Pediatrics</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-26 Physical Medicine')">Physical Medicine</button>   
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-27 Plastic Surgery')">Plastic Surgery</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-28 Podiatry')">Podiatry</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-29 Preventive Medicine')">Preventive Medicine</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-30 Radiation Oncology')">Radiation Oncology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-31 Rheumatology')">Rheumatology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-32 Thoracic Surgery')">Thoracic Surgery</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-33 Urology')">Urology</button>
                            <button type="button" class="quickselect" onclick="quickSelect('MIPS-34 Vascular Surgery')">Vascular Surgery</button>
				</div>
				<br/>
					<li>
						<input type="checkbox" id="pqrs-toggle">
						<label for="pqrs-toggle">All Measures</label>
						<ul>
							<li>
								<input type="checkbox" id="individual-toggle">
								<label for="individual-toggle">Individual Measure Selection (click twice to de-select all)</label>
								<ul>
<?php

$rules = sqlStatementNoLog(
	'SELECT `id`, `active`
	FROM `clinical_rules`
	WHERE `id` LIKE "PQRS_%"
		AND `id` NOT LIKE "%_Group_%"
	ORDER BY `id` ASC;'
);

foreach($rules as $rule) {
	$id = $rule['id'];
	$active = $rule['active'];

	$idParts = explode('_', $id);
	array_shift($idParts);
	$label = implode(' ', $idParts);

?>
									<li class="checkbox-button">
										<input type="hidden" name="pqrsRulesInitial[<?php echo $id; ?>]" value="<?php echo $active ?>">
										<input type="checkbox" class="measure" id="<?php echo $id; ?>" name="pqrsRules[<?php echo $id; ?>]" value="1"<?php if($active == 1) { echo ' checked="checked"'; } ?>>
										<label for="<?php echo $id; ?>"><?php echo $label; ?></label>
									</li>
<?php

}

?>
								</ul>
							</li>
							<li>
								<ul>
<?php

$rules = sqlStatementNoLog(
	'SELECT `id`, `active`
	FROM `clinical_rules`
	WHERE `id` LIKE "pre_%"
		AND `id` NOT LIKE "%_Group_%"
	ORDER BY `id` ASC;'
);

foreach($rules as $rule) {
	$id = $rule['id'];
	$active = $rule['active'];

	$idParts = explode('_', $id);
	array_shift($idParts);
	$label = implode(' ', $idParts);

?>
									<li class="checkbox-button">
										<input type="hidden" name="pqrsRulesInitial[<?php echo $id; ?>]" value="<?php echo $active ?>">
										<input type="checkbox" class="measure" id="<?php echo $id; ?>" name="pqrsRules[<?php echo $id; ?>]" value="1"<?php if($active == 1) { echo ' checked="checked"'; } ?>>
										<label for="<?php echo $id; ?>"><?php echo "pre-select ".$label; ?></label>
									</li>
<?php

}

?>

										</ul>
									</li>
							<li>
								<ul>
<?php

$rules = sqlStatementNoLog(
	'SELECT `id`, `active`
	FROM `clinical_rules`
	WHERE `id` LIKE "HCC_%"
		AND `id` NOT LIKE "%_Group_%"
	ORDER BY `id` ASC;'
);

foreach($rules as $rule) {
	$id = $rule['id'];
	$active = $rule['active'];

	$idParts = explode('_', $id);
	array_shift($idParts);
	$label = implode(' ', $idParts);

?>
									<li class="checkbox-button">
										<input type="hidden" name="pqrsRulesInitial[<?php echo $id; ?>]" value="<?php echo $active ?>">
										<input type="checkbox" class="measure" id="<?php echo $id; ?>" name="pqrsRules[<?php echo $id; ?>]" value="1"<?php if($active == 1) { echo ' checked="checked"'; } ?>>
										<label for="<?php echo $id; ?>"><?php echo "HCC ". $label; ?></label>
									</li>
<?php

}

?>

										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</form>
	</body>
</html>
