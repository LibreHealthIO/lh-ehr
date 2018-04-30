<?php
 // Copyright (C) 2010-2017 LibreHealth EHR Support LLC
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

 // This report lists prescriptions and their dispensations according
 // to various input selection criteria.
 //
 // Prescription report written by Rod Roark, 2010
 // Fixed drug name search to work in a broader sense - tony@mi-squared.com, 2010
 // Added five new reports as per EHR certification requirements for Patient Lists - LibreEHR Support LLC, 2010

require_once "reports_controllers/ClinicalController.php";
?>
<html>
<head>
<?php html_header_show();?>
<title>
<?php echo htmlspecialchars(xl('Clinical Reports'),ENT_NOQUOTES); ?>
</title>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
    <script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>
<script language="JavaScript">

 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });

       function toggle(id) {
                var tr = document.getElementById(id);
            if (tr == null) {
                return;
            }
                var bExpand = tr.style.display == '';
                tr.style.display = (bExpand ? 'none' : '');
            }
            function changeimage(id, sMinus, sPlus) {
                var img = document.getElementById(id);
                if (img!=null) {
                   var bExpand = img.src.indexOf(sPlus) >= 0;
                        if (!bExpand)
                        img.src = "../pic/blue-up-arrow.gif";
                        else
                        img.src = "../pic/blue-down-arrow.gif";
                }
            }
       function Toggle_trGrpHeader2(t_id,i_id) {
                var img=i_id;
                changeimage(img, 'blue-down-arrow.gif', 'blue-up-arrow.gif');
                var id1=t_id;
                toggle(id1);
             }
// This is for callback by the find-code popup.
// Appends to or erases the current list of diagnoses.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0][current_sel_name];
 var s = f.value;
 if (code) {
  if (s.length > 0) s += ';';
  s += codetype + ':' + code;
 } else {
  s = '';
 }
 f.value = s;
}

//This invokes the find-code popup.
function sel_diagnosis(e) {
 current_sel_name = e.name;
 dlgopen('../patient_file/encounter/find_code_popup.php?codetype=<?php echo collect_codetypes("diagnosis","csv"); ?>', '_blank', 500, 400);
}

//This invokes the find-code popup.
function sel_procedure(e) {
 current_sel_name = e.name;
 dlgopen('../patient_file/encounter/find_code_popup.php?codetype=<?php echo collect_codetypes("procedure","csv"); ?>', '_blank', 500, 400);
}
</script>
<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">
/* specifically include & exclude from printing */
@media print {
#report_parameters {
    visibility: hidden;
    display: none;
}
#report_parameters_daterange {
    visibility: visible;
    display: inline;
}
#report_results table {
    margin-top: 0px;
}
}

/* specifically exclude some from the screen */
@media screen {
#report_parameters_daterange {
    visibility: hidden;
    display: none;
}
}
.optional_area_service_codes {
    <?php
    if($type != 'Service Codes' || $type == '')
    {
        ?> display: none;
    <?php
    }
    ?>
}
</style>
<script language="javascript" type="text/javascript">
    function checkType() {
            if ($('#type').val() == 'Service Codes') {
            $('.optional_area_service_codes').css("display", "inline");
        }
            else {
            $('.optional_area_service_codes').css("display", "none");
        }
    }

    function submitForm() {
        var d_from = new String($('#form_from_date').val());
        var d_to = new String($('#form_to_date').val());

        var d_from_arr = d_from.split('-');
        var d_to_arr = d_to.split('-');

        var dt_from = new Date(d_from_arr[0], d_from_arr[1], d_from_arr[2]);
        var dt_to = new Date(d_to_arr[0], d_to_arr[1], d_to_arr[2]);

        var mili_from = dt_from.getTime();
        var mili_to = dt_to.getTime();
        var diff = mili_to - mili_from;

        $('#date_error').css("display", "none");

        if(diff < 0) //negative
        {
            $('#date_error').css("display", "inline");
        }
            else {
            $("#form_refresh").attr("value","true");
            $("#theform").submit();
        }
    }

    $(document).ready(function() {
        $(".numeric_only").keydown(function(event) {
            //alert(event.keyCode);
            // Allow only backspace and delete
            if ( event.keyCode == 46 || event.keyCode == 8 ) {
                // let it happen, don't do anything
            }
            else {
                    if (!((event.keyCode >= 96 && event.keyCode <= 105) || (event.keyCode >= 48 && event.keyCode <= 57))) {
                    event.preventDefault();
                }
            }
        });
    });
</script>
</head>
<body class="body_top">
<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<span class='title'>
<?php echo htmlspecialchars(xl('Report - Clinical'),ENT_NOQUOTES); ?>
</span>
<!-- Search can be done using age range, gender, and ethnicity filters.
Search options include diagnosis, procedure, prescription, medical history, and lab results.
-->
<?php reportParametersDaterange(); #TRK ?>
<form name='theform' id='theform' method='post' action='clinical_reports.php'>
    <div id="report_parameters">
        <input type='hidden' name='form_refresh' id='form_refresh' value=''/>
        <table>
            <tr>
                <td width='740px'>
                    <div style='float:left'>
                <table class='text'>
                    <tr>
                        <td class='label' width="100">
                            <?php echo htmlspecialchars(xl('Facility'), ENT_NOQUOTES); ?>:
                        </td>
                        <td width="250"> <?php dropdown_facility($facility,'facility',false); ?> </td>

                        <td class='label'><?php echo htmlspecialchars(xl('Patient ID'),ENT_NOQUOTES); ?>:</td>
                        <td><input name='patient_id' class="numeric_only" type='text' id="patient_id"
                            title='<?php echo htmlspecialchars(xl('Optional numeric patient ID'), ENT_QUOTES); ?>'
                            value='<?php echo htmlspecialchars($patient_id, ENT_QUOTES); ?>' size='10' maxlength='20'/>
                        </td>
                    </tr>
                    <tr>
                        <?php // Show From and To dates fields. (TRK)
                            showFromAndToDates(); ?>
                    </tr>
                    <tr>
                       <?php // Show patient's age range. (TRK)
                            showPatientAgeRange(); ?>
                        <td class='label'><?php echo htmlspecialchars(xl('Problem DX'),ENT_NOQUOTES); ?>:</td>
                        <td><input type='text' name='form_diagnosis' size='10' maxlength='250'
                                value='<?php echo htmlspecialchars($form_diagnosis, ENT_QUOTES); ?>'
                                onclick='sel_diagnosis(this)' title='<?php echo htmlspecialchars(xl('Click to select or change diagnoses'), ENT_QUOTES); ?>' readonly/>
                        </td>
                        <td>&nbsp;</td>
<!-- Visolve -->
                    </tr>
                    <tr>
                        <td class='label'><?php echo htmlspecialchars(xl('Gender'),ENT_NOQUOTES); ?>:</td>
                        <td><?php echo generate_select_list('gender', 'sex', $sql_gender, 'Select Gender', 'Unassigned', '', ''); ?></td>
                        <td class='label'><?php echo htmlspecialchars(xl('Drug'),ENT_NOQUOTES); ?>:</td>
                        <td><input type='text' name='form_drug_name' size='10' maxlength='250'
                                value='<?php echo htmlspecialchars($form_drug_name, ENT_QUOTES); ?>'
                                title='<?php echo htmlspecialchars(xl('Optional drug name, use % as a wildcard'), ENT_QUOTES); ?>'/>
                        </td>

                    </tr>
                    <tr>
                        <td class='label'><?php echo htmlspecialchars(xl('Race'),ENT_NOQUOTES); ?>:</td>
                        <td><?php echo generate_select_list('race', 'race', $sql_race, 'Select Race', 'Unassigned', '', ''); ?></td>
                        <td class='label'><?php echo htmlspecialchars(xl('Ethnicity'),ENT_NOQUOTES); ?>:</td>
                        <td><?php echo generate_select_list('ethnicity', 'ethnicity', $sql_ethnicity, 'Select Ethnicity', 'Unassigned', '', ''); ?></td>
                        <td class='label'><?php echo htmlspecialchars(xl('Immunization'), ENT_NOQUOTES); ?>:
                        </td>
                        <td><input type='text' name='form_immunization' size='10' maxlength='250'
                                value='<?php echo htmlspecialchars($form_immunization, ENT_QUOTES); ?>'
                                title='<?php echo htmlspecialchars(xl('Optional immunization name or code, use % as a wildcard'), ENT_QUOTES); ?>'/>
                        </td>
                    </tr>
                    <tr>
                        <td class='label' width='100'>
                            <?php echo htmlspecialchars(xl('Lab Result'), ENT_NOQUOTES); ?>:
                        </td>
                        <td width='100'><input type='text' name='form_lab_results' size='13' maxlength='250'
                                            value='<?php echo htmlspecialchars($form_lab_results, ENT_QUOTES); ?>'
                                            title='<?php echo htmlspecialchars(xl('Result, use % as a wildcard'), ENT_QUOTES); ?>'/>
                        </td>
                        <td class='label' width='100'>
                            <?php echo htmlspecialchars(xl('Option'), ENT_NOQUOTES); ?>:
                        </td>
                        <td>
                            <select name="type" id="type" onChange="checkType();">
                                <option> <?php echo htmlspecialchars(xl('Select'),ENT_NOQUOTES); ?></option>
                                <option value="Procedure" <?php if ($type == 'Procedure') {echo "selected";} ?>>
                                    <?php echo htmlspecialchars(xl('Procedure'), ENT_NOQUOTES); ?>
                                </option>
                                <option value="Medical History" <?php if ($type == 'Medical History') {echo "selected";} ?>>
                                    <?php echo htmlspecialchars(xl('Medical History'), ENT_NOQUOTES); ?>
                                </option>
                                <option value="Service Codes" <?php if ($type == 'Service Codes') {echo "selected";} ?>>
                                    <?php echo htmlspecialchars(xl('Service Codes'), ENT_NOQUOTES); ?>
                                </option>
                            </select>
                        </td>
                        <td class='label'><?php echo htmlspecialchars(xl('Communication'), ENT_NOQUOTES); ?>:
                        </td>
                        <td>
                            <select name="communication" id="communication" title="<?php echo htmlspecialchars(xl('Select Communication Preferences'), ENT_NOQUOTES); ?>">
                                <option value=""> <?php echo htmlspecialchars(xl('Select'), ENT_NOQUOTES); ?>
                                </option>
                                <?php foreach($comarr as $comkey => $comvalue){ ?>
                                <option value="<?php echo attr($comkey); ?>" <?php if ($communication == $comkey) {echo "selected";} ?>><?php echo text($comvalue); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <table>
                    <tr class="optional_area_service_codes">
                        <td width='100'>&nbsp;</td>
                        <td width='100'>&nbsp;</td>
                        <td width='195'>&nbsp;</td>
                        <td class='label' width='76'>
                            <?php echo htmlspecialchars(xl('Code'), ENT_NOQUOTES); ?>:
                        </td>
                        <td><input type='text' name='form_service_codes' size='10' maxlength='250'
                                value='<?php echo htmlspecialchars($form_service_codes, ENT_QUOTES); ?>'
                                onclick='sel_procedure(this)' title='<?php echo htmlspecialchars(xl('Click to select or change service codes'), ENT_QUOTES); ?>' readonly/>&nbsp;
                        </td>
                    </tr>
                </table>
                <table class='text'>
                    <tr>
                        <!-- Sort by Start -->
                        <td class='label' width='63'>
                            <?php echo htmlspecialchars(xl('Sort By'), ENT_NOQUOTES); ?>:
                        </td>
                        <td>
                            <input type='checkbox' name='form_pt_name'<?php if ($_POST['form_pt_name'] == true) echo ' checked'; ?>>
                                <?php echo htmlspecialchars(xl('Patient Name'),ENT_NOQUOTES); ?>&nbsp;

                            <input type='checkbox' name='form_pt_age'<?php if ($_POST['form_pt_age'] == true) echo ' checked'; ?>>
                                <?php echo htmlspecialchars(xl('Age'),ENT_NOQUOTES); ?>&nbsp;

                            <input type='checkbox' name='form_diagnosis_allergy'<?php if ($_POST['form_diagnosis_allergy'] == true) echo ' checked'; ?>>
                                <?php echo htmlspecialchars(xl('Allergies'),ENT_NOQUOTES); ?>&nbsp;

                            <input type='checkbox' name='form_diagnosis_medprb'<?php if ($_POST['form_diagnosis_medprb'] == true) echo ' checked'; ?>>
                                <?php echo htmlspecialchars(xl('Medical Problems'),ENT_NOQUOTES); ?>&nbsp;

                            <input type='checkbox' name='form_drug'<?php if ($_POST['form_drug'] == true) echo ' checked'; ?>>
                                <?php echo htmlspecialchars(xl('Drug'),ENT_NOQUOTES); ?>&nbsp;

                            <input type='checkbox' name='ndc_no'<?php if ($_POST['ndc_no'] == true) echo ' checked'; ?>>
                                <?php echo htmlspecialchars(xl('NDC Number'),ENT_NOQUOTES); ?>&nbsp;
                            <input type='checkbox' name='lab_results'<?php if ($_POST['lab_results'] == true) echo ' checked'; ?>>
                                <?php echo htmlspecialchars(xl('Lab Results'),ENT_NOQUOTES); ?>&nbsp;
                            <input type='checkbox' name='communication_check'<?php if ($_POST['communication_check'] == true) echo ' checked'; ?>>
                                <?php echo xlt('Communication'); ?>
                        </td>
                    </tr>
                <!-- Sort by ends -->
            </tr>
            <tr>
                <td colspan=3>
                    <span id="date_error" style="color: #F00; font-siz: 11px; display: none;">
                        <?php echo htmlspecialchars(xl('From Date Cannot be Greater than To Date.'), ENT_NOQUOTES); ?>
                    </span>&nbsp;
                </td>
            </tr>
        </table>
    </div>
                 </td>
                <?php // Show print and export buttons. (TRK)
                    showSubmitPrintButtons(); ?>
             </tr>
        </table>
    </div>
<!-- end of parameters -->
<?php
    $from_date = prepareDateBeforeSave($from_date, true);
    $to_date   = prepareDateBeforeSave($to_date, true);
// SQL scripts for the various searches
$sqlBindArray = array();
if ($_POST['form_refresh'])
{

// Preparing results for Clinical reports. (TRK)
$answer = prepareResults();
$result = $answer['result'];
$row_id = $answer['row_id'];
$img_id = $answer['img_id'];
$k = $answer['k'];
if(sqlNumRows($result)){
   //Added on 6-jun-2k14(regarding displaying smoking code descriptions)
   $smoke_codes_arr = getSmokeCodes();
?>
<br>
    <div id = "report_results">

    <?php $pidarr = array();
            while ($row = sqlFetchArray($result)) { ?>
        <table width=90% align="center" cellpadding="5" cellspacing="0" style="font-family:tahoma;color:black;"
               border="0">
        <tr bgcolor = "#CCCCCC" style="font-size:15px;">
                <td><b><?php echo htmlspecialchars(xl('Summary of'), ENT_NOQUOTES);
                        echo " "; ?><?php echo htmlspecialchars($row['patient_name'], ENT_NOQUOTES); ?></b></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="center">
                    <span onclick="javascript:Toggle_trGrpHeader2(<?php echo $row_id; ?>,<?php echo $img_id; ?>);"><img
                            src="../pic/blue-down-arrow.gif" id="<?php echo $img_id;
                        $img_id++; ?>"
                            title="<?php echo htmlspecialchars(xl('Click here to view patient details'), ENT_QUOTES); ?>"/></span>
                </td>
            </tr>
            <table width="100%" align="center" id="<?php echo $row_id;
            $row_id++; ?>" class="border1" style="display:none; font-size:13px;" cellpadding=5>
                <tr bgcolor="#C3FDB8" align="left">
                <td width="15%"><b><?php echo htmlspecialchars(xl('Patient Name'),ENT_NOQUOTES); ?></b></td>
                <td width="5%"><b><?php echo htmlspecialchars(xl('PID'),ENT_NOQUOTES);?></b></td>
                <td width="5%"><b><?php echo htmlspecialchars(xl('Age'),ENT_NOQUOTES);?></b></td>
                <td width="10%"><b><?php echo htmlspecialchars(xl('Gender'),ENT_NOQUOTES); ?></b></td>
                <td width="15%"><b><?php echo htmlspecialchars(xl('Race'),ENT_NOQUOTES);?></b></td>
                <td width="15%"><b><?php echo htmlspecialchars(xl('Ethnicity'),ENT_NOQUOTES);?></b></td>
                    <td width="15%" <?php if (strlen($communication) == 0 || $_POST['communication_check'] == true) { ?> colspan=5 <?php } ?>>
                        <b><?php echo htmlspecialchars(xl('Provider'), ENT_NOQUOTES); ?></b></td>
                <?php if(strlen($communication) > 0 || ($_POST['communication_check'] == true)){ ?>
                <td colspan=4><b><?php echo xlt('Communication');?></b></td>
                <?php } ?>
                </tr>
                <tr bgcolor="#FFFFFF">
                <td><?php echo htmlspecialchars($row['patient_name'],ENT_NOQUOTES); ?>&nbsp;</td>
                <td> <?php echo htmlspecialchars($row['patient_id'],ENT_NOQUOTES); ?>&nbsp;</td>
                <td> <?php echo htmlspecialchars($row['patient_age'],ENT_NOQUOTES); ?>&nbsp;</td>
                    <td> <?php echo htmlspecialchars(generate_display_field(array('data_type' => '1', 'list_id' => 'sex'), $row['patient_sex']), ENT_NOQUOTES); ?>
                        &nbsp;</td>
                    <td> <?php echo htmlspecialchars(generate_display_field(array('data_type' => '1', 'list_id' => 'race'), $row['patient_race']), ENT_NOQUOTES); ?>
                        &nbsp;</td>
                    <td> <?php echo htmlspecialchars(generate_display_field(array('data_type' => '1', 'list_id' => 'ethnicity'), $row['patient_ethinic']), ENT_NOQUOTES); ?>
                        &nbsp;</td>
                    <td <?php if (strlen($communication) == 0 || ($_POST['communication_check'] == true)) { ?> colspan=5 <?php } ?>> <?php echo htmlspecialchars($row['users_provider'], ENT_NOQUOTES); ?>
                        &nbsp;</td>

                                <?php if(strlen($communication) > 0 || $_POST['communication_check'] == true){ ?>
                                        <td colspan=4><?php echo text($row['communications']); ?></td>
                                <?php }  ?>
                </tr>
<!-- Diagnosis Report Start-->
                <?php
                if (strlen($form_diagnosis) > 0 || $_POST['form_diagnosis_allergy'] == true || $_POST['form_diagnosis_medprb'] == true) {
                ?>
                        <tr bgcolor="#C3FDB8" align= "left">
                        <td colspan=12><b><?php echo "#";
                                echo htmlspecialchars(xl('Diagnosis Report'), ENT_NOQUOTES); ?></b></td>
                </tr>
                <tr bgcolor="#C3FDB8" align= "left">
                <td><b><?php echo htmlspecialchars(xl('Diagnosis Date'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Diagnosis'),ENT_NOQUOTES);?></b></td>
                <td colspan=10><b><?php echo htmlspecialchars(xl('Diagnosis Name'),ENT_NOQUOTES);?></b></td>
                </tr>
                            <tr bgcolor="#FFFFFF">
                <td><?php echo htmlspecialchars($row['lists_date'],ENT_NOQUOTES); ?>&nbsp;</td>
                <td><?php echo htmlspecialchars($row['lists_diagnosis'],ENT_NOQUOTES); ?>&nbsp;</td>
                                <td colspan=10><?php echo htmlspecialchars($row['lists_title'],ENT_NOQUOTES); ?>&nbsp;</td>
                </tr>
    <?php } ?>
<!-- Diagnosis Report End-->

<!-- Prescription Report Start-->
                   <?php
                if (strlen($form_drug_name) > 0 || $_POST['form_drug'] == true) {
                ?>
                            <tr bgcolor="#C3FDB8" align= "left">
                <td colspan=12><b><?php echo "#"; echo htmlspecialchars(xl('Prescription Report'),ENT_NOQUOTES);?><b></td></tr>
                            <tr bgcolor="#C3FDB8" align= "left">
                <td><b><?php echo htmlspecialchars(xl('Date'),ENT_NOQUOTES); ?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Drug Name'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Route'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Dosage'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Form'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Interval'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Size'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Unit'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('ReFill'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Quantity'),ENT_NOQUOTES);?></b></td>
                <td colspan="2"><b><?php echo htmlspecialchars(xl('NDC'),ENT_NOQUOTES);?></b></td>
                </tr>
                            <tr bgcolor="#FFFFFF" align="">
                <?php
                    $rx_route =  generate_display_field(array('data_type'=>'1','list_id'=>'drug_route'), $row['route']) ;
                    $rx_form = generate_display_field(array('data_type'=>'1','list_id'=>'drug_form'), $row['hform']) ;
                    $rx_interval = generate_display_field(array('data_type'=>'1','list_id'=>'drug_interval'), $row['hinterval']) ;
                    $rx_units =   generate_display_field(array('data_type'=>'1','list_id'=>'drug_units'), $row['hunit']);
                ?>
                        <td> <?php echo htmlspecialchars(oeFormatShortDate($row['prescriptions_date_modified']), ENT_NOQUOTES); ?>
                            &nbsp;</td>
                <td><?php echo htmlspecialchars($row['drug'],ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($rx_route,ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($row['dosage'],ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($rx_form,ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($rx_interval,ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($row['size'],ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($rx_units,ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($row['refills'],ENT_NOQUOTES); ?></td>
                <td><?php echo htmlspecialchars($row['quantity'],ENT_NOQUOTES); ?></td>
                <td colspan="2"><?php echo htmlspecialchars($row['ndc_number'],ENT_NOQUOTES); ?></td>
                            </tr>
                <?php } ?>
<!-- Prescription Report End-->

<!-- Lab Results Report Start-->
                <?php
                if (strlen($form_lab_results) > 0 || $_POST['lab_results'] == true) {
                ?>
                            <tr bgcolor="#C3FDB8" align= "left">
                        <td colspan=12><b><?php echo "#";
                                echo htmlspecialchars(xl('Lab Results Report'), ENT_NOQUOTES); ?><b></td>
                    </tr>
                            <tr bgcolor="#C3FDB8" align= "left">
                <td><b><?php echo htmlspecialchars(xl('Date'),ENT_NOQUOTES); ?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Facility'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Unit'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Result'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Range'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Abnormal'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Comments'),ENT_NOQUOTES);?></b></td>
                <td colspan=5><b><?php echo htmlspecialchars(xl('Document ID'),ENT_NOQUOTES);?></b></td>
                </tr>
                            <tr bgcolor="#FFFFFF">
                        <td> <?php echo htmlspecialchars(oeFormatShortDate($row['procedure_result_date']), ENT_NOQUOTES); ?>
                            &nbsp;</td>
                                <td> <?php echo htmlspecialchars($row['procedure_result_facility'],ENT_NOQUOTES); ?>&nbsp;</td>
                        <td> <?php echo htmlspecialchars(generate_display_field(array('data_type' => '1', 'list_id' => 'proc_unit'), $row['procedure_result_units']), ENT_NOQUOTES); ?>
                            &nbsp;</td>
                                 <td> <?php echo htmlspecialchars($row['procedure_result_result'],ENT_NOQUOTES); ?>&nbsp;</td>
                                 <td> <?php echo htmlspecialchars($row['procedure_result_range'],ENT_NOQUOTES); ?>&nbsp;</td>
                                 <td> <?php echo htmlspecialchars($row['procedure_result_abnormal'],ENT_NOQUOTES); ?>&nbsp;</td>
                                 <td> <?php echo htmlspecialchars($row['procedure_result_comments'],ENT_NOQUOTES); ?>&nbsp;</td>
                        <td colspan=5> <?php echo htmlspecialchars($row['procedure_result_document_id'], ENT_NOQUOTES); ?>
                            &nbsp;</td>
                        </tr>
                <?php } ?>
<!-- Lab Results End-->

<!-- Procedures Report Start-->
                <?php
                if ($type == 'Procedure') {
                ?>
                <tr bgcolor="#C3FDB8" align= "left">
                    <td colspan=12>
                        <b><?php echo "#"; echo htmlspecialchars(xl('Procedure Report'), ENT_NOQUOTES); ?><b>
                    </td>
                </tr>
                <tr bgcolor="#C3FDB8" align= "left">
                    <td><b><?php echo htmlspecialchars(xl('Date'),ENT_NOQUOTES); ?></b></td>
                    <td><b><?php echo htmlspecialchars(xl('Standard Name'),ENT_NOQUOTES);?></b></td>
                    <td><b><?php echo htmlspecialchars(xl('Procedure'),ENT_NOQUOTES); ?></b></td>
                    <td><b><?php echo htmlspecialchars(xl('Encounter'),ENT_NOQUOTES);?></b></td>
                    <td><b><?php echo htmlspecialchars(xl('Priority'),ENT_NOQUOTES);?></b></td>
                    <td><b><?php echo htmlspecialchars(xl('Status'),ENT_NOQUOTES);?></b></td>
                    <td><b><?php echo htmlspecialchars(xl('Instruction'),ENT_NOQUOTES);?></b></td>
                    <td><b><?php echo htmlspecialchars(xl('Activity'),ENT_NOQUOTES);?></b></td>
                    <td colspan=3><b><?php echo htmlspecialchars(xl('Control ID'),ENT_NOQUOTES);?></b></td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <?php
                        $procedure_type_standard_code_arr = explode(':', $row['procedure_type_standard_code']);
                        $procedure_type_standard_code = $procedure_type_standard_code_arr[1];
                    ?>
                                  <!-- Procedure -->
                    <td> <?php echo htmlspecialchars(oeFormatShortDate($row['procedure_order_date_ordered']), ENT_NOQUOTES); ?>&nbsp;
                    </td>
                    <td> <?php echo htmlspecialchars($procedure_type_standard_code,ENT_NOQUOTES); ?>&nbsp;</td>
                    <td> <?php echo htmlspecialchars($row['procedure_name'],ENT_NOQUOTES); ?>&nbsp;</td>
                    <td> <?php echo htmlspecialchars($row['procedure_order_encounter'],ENT_NOQUOTES); ?>&nbsp;
                    </td>
                    <td> <?php echo htmlspecialchars(generate_display_field(array('data_type' => '1', 'list_id' => 'ord_priority'), $row['procedure_order_order_priority']), ENT_NOQUOTES); ?>&nbsp;
                    </td>
                    <td> <?php echo htmlspecialchars(generate_display_field(array('data_type' => '1', 'list_id' => 'ord_status'), $row['procedure_order_order_status']), ENT_NOQUOTES); ?>&nbsp;
                    </td>
                    <td> <?php echo htmlspecialchars($row['procedure_order_patient_instructions'], ENT_NOQUOTES); ?>&nbsp;
                    </td>
                    <td> <?php echo htmlspecialchars($row['procedure_order_activity'],ENT_NOQUOTES); ?>&nbsp;
                    </td>
                    <td colspan=3> <?php echo htmlspecialchars($row['procedure_order_control_id'], ENT_NOQUOTES); ?>&nbsp;
                    </td>

                </tr>
            <?php } ?>
<!-- Procedure Report End-->

<!-- Medical History Report Start-->
                <?php
                if ($type == 'Medical History') {
                ?>
                    <tr bgcolor="#C3FDB8" align= "left">
                        <td colspan=12>
                            <b><?php echo "#"; echo htmlspecialchars(xl('Medical History'), ENT_NOQUOTES); ?><b>
                        </td>
                    </tr>
                    <tr bgcolor="#C3FDB8" align= "left">
                        <td><b><?php echo htmlspecialchars(xl('History Date'),ENT_NOQUOTES); ?></b></td>
                        <td><b><?php echo htmlspecialchars(xl('Tobacco'),ENT_NOQUOTES);?></b></td>
                        <td><b><?php echo htmlspecialchars(xl('Alcohol'),ENT_NOQUOTES);?></b></td>
                        <td colspan=8>
                            <b><?php echo htmlspecialchars(xl('Recreational Drugs'), ENT_NOQUOTES); ?></b>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <?php
                            $tmp_t = explode('|', $row['history_data_tobacco']);
                            $tmp_a = explode('|', $row['history_data_alcohol']);
                            $tmp_d = explode('|', $row['history_data_recreational_drugs']);
                            $his_tobac =  generate_display_field(array('data_type'=>'1','list_id'=>'smoking_status'), $tmp_t[3]) ;
                        ?>
                        <td> <?php echo htmlspecialchars(oeFormatShortDate($row['history_data_date']), ENT_NOQUOTES); ?>&nbsp;
                        </td>
                        <td> <?php
                            //Added on 6-jun-2k14(regarding displaying smoking code descriptions)
                            if(!empty($smoke_codes_arr[$tmp_t[3]])){
                                $his_tobac.= " ( ".$smoke_codes_arr[$tmp_t[3]]." )";
                            }
                            echo htmlspecialchars($his_tobac,ENT_NOQUOTES); ?>&nbsp;
                        </td>
                        <?php
                            if ($tmp_a[1] == "currentalcohol") $res = xl('Current Alcohol');
                            if ($tmp_a[1] == "quitalcohol") $res = xl('Quit Alcohol');
                            if ($tmp_a[1] == "neveralcohol") $res = xl('Never Alcohol');
                            if ($tmp_a[1] == "not_applicablealcohol") $res = xl('N/A');
                        ?>
                        <td> <?php echo htmlspecialchars($res,ENT_NOQUOTES); ?>&nbsp;</td>
                        <?php
                            if ($tmp_d[1] =="currentrecreational_drugs") $resd=xl('Current Recreational Drugs');
                            if ($tmp_d[1] == "quitrecreational_drugs") $resd = xl('Quit');
                            if ($tmp_d[1] == "neverrecreational_drugs") $resd = xl('Never');
                            if ($tmp_d[1] == "not_applicablerecreational_drugs") $resd = xl('N/A');
                        ?>
                        <td colspan=8> <?php echo htmlspecialchars($resd,ENT_NOQUOTES); ?>&nbsp;</td>
                    </tr>
                  <?php } ?>
<!-- Medical History Report End-->

<!-- Service Codes Report Start-->
                <?php
                if ( $type == 'Service Codes') {
                ?>
                            <tr bgcolor="#C3FDB8" align= "left">
                        <td colspan=11><b><?php echo "#";
                                echo htmlspecialchars(xl('Service Codes'), ENT_NOQUOTES); ?><b></td>
                    </tr>
                            <tr bgcolor="#C3FDB8" align= "left">
                <td><b><?php echo htmlspecialchars(xl('Date'),ENT_NOQUOTES); ?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Code'),ENT_NOQUOTES);?></b></td>
                <td><b><?php echo htmlspecialchars(xl('Encounter ID'),ENT_NOQUOTES);?></b></td>
                        <td colspan=8><b><?php echo htmlspecialchars(xl('Code Text'), ENT_NOQUOTES); ?></b></td>
                    </tr>
                            <tr bgcolor="#FFFFFF">
                <td><?php echo htmlspecialchars(oeFormatShortDate($row['date']),ENT_NOQUOTES); ?>&nbsp;</td>
                    <td><?php echo htmlspecialchars($row['code'],ENT_NOQUOTES); ?>&nbsp;</td>
                        <td><?php echo htmlspecialchars($row['encounter'],ENT_NOQUOTES); ?>&nbsp;</td>
                <td colspan=8><?php echo htmlspecialchars($row['code_text'],ENT_NOQUOTES); ?>&nbsp;</td>
                            </tr>
                <?php } ?>
<!-- Service Codes Report End-->

<!-- Immunization Report Start-->
                <?php
                if (strlen($form_immunization) > 0) {
                    ?>
                    <tr bgcolor="#C3FDB8" align= "left">
                        <td colspan=12><b><?php echo "#";
                                echo htmlspecialchars(xl('Immunization Report'), ENT_NOQUOTES); ?></b></td>
                    </tr>
                    <tr bgcolor="#C3FDB8" align= "left">
                        <td><b><?php echo htmlspecialchars(xl('Immunization Date'),ENT_NOQUOTES);?></b></td>
                        <td><b><?php echo htmlspecialchars(xl('CVX Code'),ENT_NOQUOTES);?></b></td>
                        <td><b><?php echo htmlspecialchars(xl('Vaccine'),ENT_NOQUOTES);?></b></td>
                        <td><b><?php echo htmlspecialchars(xl('Amount'),ENT_NOQUOTES);?></b></td>
                        <td><b><?php echo htmlspecialchars(xl('Administered Site'),ENT_NOQUOTES);?></b></td>
                        <td colspan="7"><b><?php echo htmlspecialchars(xl('Notes'),ENT_NOQUOTES);?></b></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td><?php echo htmlspecialchars($row['imm_date'],ENT_NOQUOTES); ?>&nbsp;</td>
                        <td><?php echo htmlspecialchars($row['cvx_code'],ENT_NOQUOTES); ?>&nbsp;</td>
                        <td><?php echo htmlspecialchars($row['imm_code_short'], ENT_NOQUOTES) . " (" . htmlspecialchars($row['imm_code']) . ")"; ?>
                            &nbsp;</td>
                        <td>
                        <?php
                        if ($row["amount_administered"] > 0) {
                            echo htmlspecialchars( $row["amount_administered"] . " " . generate_display_field(array('data_type'=>'1','list_id'=>'drug_units'), $row['amount_administered_unit']) , ENT_NOQUOTES);
                        }else{
                            echo "&nbsp;";
                        }
                        ?>

                      </td>

                      <td>
                       <?php echo generate_display_field(array('data_type'=>'1','list_id'=>'proc_body_site'), $row['administration_site']); ?>
                      </td>

                      <td colspan="7">
                       <?php echo htmlspecialchars($row['notes']); ?>
                      </td>
                    </tr>
            <?php } ?>
<!-- Immunization Report End-->
                             </table>
            <?php }  //while loop end
            ?>
        </table> <!-- Main table ends -->
<?php
} //End if $result
} //End if form_refresh
        else {
            ?>
            <div
                class='text'> <?php echo htmlspecialchars(xl('Please input search criteria above, and click Submit to view results.'), ENT_NOQUOTES); ?> </div><?php
}
?>
</form>
</body>

<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });
</script>
</html>
