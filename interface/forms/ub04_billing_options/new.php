<?php
/**
* forms/new.php Form for gathering UB04 information.
*
* Form for gathering UB04 information
* to complete the data gathering for the UB04
*
* Copyright (C) 2014-2017 Terry Hill <teryhill@librehealth.io>
*
* LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
* See the Mozilla Public License for more details.
* If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*
* @package LibreHealth EHR
* @author Terry Hill <teryhill@librehealth.io>
* @link http://librehealth.io
*/

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/formsoptions.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);


if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}
#Check if an active Form already exists if it does use it.
$form_name = 'form_ub04_billing_options';
$formid   = 0 + formData('id', 'H');
if (empty($formid)) {
        $formid = checkFormIsActive($form_name,$encounter);
}

$obj = $formid ? formFetch("form_ub04_billing_options", $formid) : array();
$returnurl = 'encounter_top.php';
$encounter = $_SESSION["encounter"];
$encresult = getEncounterDateByEncounter($encounter);
$encounter_date = $encresult['date'];

if ($obj['hospitalization_date_from'] == '0000-00-00' || !$obj['hospitalization_date_from']) {
   $obj['hospitalization_date_from'] = substr($encounter_date,0,10);
}
if ($obj['hospitalization_date_to'] == '0000-00-00' || !$obj['hospitalization_date_to']) {
   $obj['hospitalization_date_to'] = substr($encounter_date,0,10);
}
if ($obj['type_of_bill'] == '' || !$obj['type_of_bill']) {
   $obj['type_of_bill'] = $GLOBALS['default_bill_type'];
}

if (!$obj['admit_type']){
   $obj['admit_type'] = $GLOBALS['admit_default_type'];
}

if (!$obj['admit_source']){
   $obj['admit_source'] = $GLOBALS['admit_default_source'];
}

if (!$obj['discharge_status'] ){
   $obj['discharge_status'] = $GLOBALS['discharge_status_default'];
}

if (!$obj['attending_id']){
   $obj['attending_id'] = $GLOBALS['attending_id'];
}

if (!$obj['operating_id']){
   $obj['operating_id'] = $GLOBALS['operating_id'];
}

if (!$obj['other_1_id']){
   $obj['other_1_id'] = $GLOBALS['other1_id'];
}

if (!$obj['other_2_id']){
   $obj['other_2_id'] = $GLOBALS['other2_id'];
}

?>

<html>
<head>
<?php html_header_show();?>
<!-- page styles -->
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/jquery.datetimepicker.css">
<link href="<?php echo $GLOBALS['standard_js_path']; ?>/bootstrap-3-3-4/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- other supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>/jquery-min-1-9-1/index.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>/bootstrap-3-3-4/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.datetimepicker.full.min.js"></script>

</head>

<body class="body_top">

<div id="form-main">
  <div id="form-div">
   <div class='container'>
    <style>form label{font-weight:normal;}</style>
<form method=post <?php echo "name='my_form' " .  "action='$rootdir/forms/ub04_billing_options/save.php?id=$formid'>\n";?>

<!-- container for the main body of the form -->
<div>

<div>

<span class="title" > <?php echo xlt('Billing Options for HCFA-1450 (UB-04)'); ?></span><Br><br>
<span class=text><?php echo xlt('BOX 4. Type of Bill'); ?>: </span>
<!--<span title='<?php //echo xlt('Example 0111'); ?>'><input type=text style="width: 35px;" size=4 name="type_of_bill" value="<?php echo $obj{"type_of_bill"};?>"></span>-->
          <div class="form-group inline" id="type_of_billInputContainer">
                    <label class="control-label" for="type_of_bill"></label>
                    <div class="controls inline-inputs,form-control col-sm-1" >
                        <input type="text" class="form-control" id="type_of_bill" placeholder="type_of_bill" value="<?php echo $obj{"type_of_bill"};?>">
                       <span class="help-inline"></span>
                    </div>
           </div>

&nbsp;
<br><br>
<tr>
  <td>
    <span class=text><?php echo xlt('BOX 6. Admission Date ');?>:</span>
  </td>
  <td>
   <?php $hospitalization_date_from = $obj{"hospitalization_date_from"}; ?>
   <input type='text' name='hospitalization_date_from' id="hospitalization_date_from"
          size='10' value='<?php echo oeFormatShortDate(attr($hospitalization_date_from)) ?>'/>
  </td>
 </tr>

&nbsp;

<tr>
  <td>
    <span class=text><?php echo xlt('Discharge date');?>:</span>
  </td>
  <td>
   <?php $hospitalization_date_to = $obj{"hospitalization_date_to"}; ?>
   <input type='text' name='hospitalization_date_to' id='hospitalization_date_to'
    size='10' value='<?php echo oeFormatShortDate(attr($hospitalization_date_to)); ?>'
   </td>
 </tr>

&nbsp;

<span class=text><?php echo xlt('BOX 13. Admission Hour'); ?>: </span>
<span title="Example 1:00 p.m = 1300"><input type=text style="width: 35px;" size=4 maxlength=4 name="admission_hour" value="<?php echo attr($obj{"admission_hour"});?>"></span><br><br>

</tr>
            <div class="form-group inline" id="box14InputContainer">
              <label class="control-label" for="box14"><?php echo xlt('BOX 14. Admit Type')?></label><br>
                <div class="controls inline-inputs ,form-control col-sm-2">
                 <?php
                  # Generate drop down list for Title
                   echo generate_select_list('admit_type', 'ub_admit_type',$obj{"admit_type"}, xl('Example Elective'),' ',"form-control");
                 ?>
                </div>
            </div>
&nbsp;

            <div class="form-group inline" id="box15InputContainer">
              <label class="control-label" for="box15"><?php echo xlt('BOX 15. Source of Admission')?></label><br>
                <div class="controls inline-inputs ,form-control col-sm-4">
 <?php
                  # Generate drop down list for Title
                   echo generate_select_list('admit_source', 'ub_admit_source',$obj{"admit_source"}, xl('Example Physician Referral'),' ',"form-control");
                 ?>
               </div>
            </div>
&nbsp;
</tr>
<br></br>

<span class=text><?php echo xlt('BOX 16. Discharge Hour (ex 1:00 a.m = 01)'); ?>: </span>
<span title='<?php echo xlt('Example 1:00 a.m = 0100'); ?>'><input type=text style="width: 35px;" size=4 name="discharge_hour" value="<?php echo attr($obj{"discharge_hour"});?>"></span><br><br>

<span class=text><?php echo xlt('BOX 17. Patient Discharge Status'); ?>: </span>
<span title='<?php echo xlt('Example Discharge to Home or Self Care (Routine Discharge) = 01'); ?>'><input type=text style="width: 20px;" size=2 name="discharge_status" value="<?php echo attr($obj{"discharge_status"});?>"></span>

&nbsp;

<span class=text><?php echo xlt('BOX 18-28. Condition Codes '); ?>: </span>
<input type=text style="width: 20px;" size=2 name="condition_code_1" value="<?php echo attr($obj{"condition_code_1"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_2" value="<?php echo attr($obj{"condition_code_2"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_3" value="<?php echo attr($obj{"condition_code_3"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_4" value="<?php echo attr($obj{"condition_code_4"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_5" value="<?php echo attr($obj{"condition_code_5"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_6" value="<?php echo attr($obj{"condition_code_6"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_7" value="<?php echo attr($obj{"condition_code_7"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_8" value="<?php echo attr($obj{"condition_code_8"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_9" value="<?php echo attr($obj{"condition_code_9"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_10" value="<?php echo attr($obj{"condition_code_10"});?>">
<input type=text style="width: 20px;" size=2 name="condition_code_11" value="<?php echo attr($obj{"condition_code_11"});?>">

&nbsp;

<span class=text><?php echo xlt('BOX 29 Accident State'); ?>: </span><input type=text style="width: 25px;" size=2 name="accident_state" value="<?php echo attr($obj{"accident_state"});?>"><br><br>

<tr>
  <td>
    <span class=text><?php echo xlt('BOX 30 Accident Date');?>:</span>
  </td>
  <td>
   <?php $accident_date = $obj{"accident_date"}; ?>
   <input type='text' name='accident_date' id='accident_date'
    size=10 value='<?php echo oeFormatShortDate(attr($accident_date)); ?>'
  </td>
 </tr>

 &nbsp;

<span class=text><?php echo xlt('BOX 31-34A. Occurrence Codes and Dates '); ?>: </span>
<input type=text style="width: 20px;" size=2 name="occurrence_code_1" value="<?php echo attr($obj{"occurrence_code_1"});?>">

<tr>
  <td>
   <?php $occurrence_date_1 = $obj{"occurrence_date_1"}; ?>
   <input type='text' name='occurrence_date_1' id='occurrence_date_1'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_1)); ?>'
  </td>
 </tr>

<input type=text style="width: 20px;" size=2 name="occurrence_code_2" value="<?php echo attr($obj{"occurrence_code_2"});?>">

<tr>
  <td>
   <?php $occurrence_date_2 = $obj{"occurrence_date_2"}; ?>
   <input type='text' name='occurrence_date_2' id='occurrence_date_2'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_2)); ?>'
  </td>
 </tr>

<input type=text style="width: 20px;" size=2 name="occurrence_code_3" value="<?php echo attr($obj{"occurrence_code_3"});?>">

<tr>
  <td>
   <?php $occurrence_date_3 = $obj{"occurrence_date_3"}; ?>
   <input type='text' name='occurrence_date_3' id='occurrence_date_3'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_3)); ?>'
  </td>
 </tr>

<input type=text style="width: 20px;" size=2 name="occurrence_code_4" value="<?php echo attr($obj{"occurrence_code_4"});?>">

<tr>
  <td>
   <?php $occurrence_date_4 = $obj{"occurrence_date_4"}; ?>
   <input type='text' name='occurrence_date_4' id='occurrence_date_4'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_4)); ?>'
  </td>
 </tr><br><br>

<span class=text><?php echo xlt('BOX 31-34B. Occurrence Codes and Dates '); ?>: </span>
<input type=text style="width: 20px;" size=2 name="occurrence_code_1A" value="<?php echo attr($obj{"occurrence_code_1A"});?>">

<tr>
  <td>
   <?php $occurrence_date_1A = $obj{"occurrence_date_1A"}; ?>
   <input type='text' name='occurrence_date_1A' id='occurrence_date_1A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_1A)); ?>'
  </td>
 </tr>

<input type=text style="width: 20px;" size=2 name="occurrence_code_2A" value="<?php echo attr($obj{"occurrence_code_2A"});?>">

<tr>
  <td>
   <?php $occurrence_date_2A = $obj{"occurrence_date_2A"}; ?>
   <input type='text' name='occurrence_date_2A' id='occurrence_date_2A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_2A)); ?>'
  </td>
 </tr>

 <input type=text style="width: 20px;" size=2 name="occurrence_code_3A" value="<?php echo attr($obj{"occurrence_code_3A"});?>">

<tr>
  <td>
   <?php $occurrence_date_3A = $obj{"occurrence_date_3A"}; ?>
   <input type='text' name='occurrence_date_3A' id='occurrence_date_3A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_3A)); ?>'
  </td>
 </tr>

<input type=text style="width: 20px;" size=2 name="occurrence_code_4A" value="<?php echo attr($obj{"occurrence_code_4A"});?>">

<tr>
  <td>
   <?php $occurrence_date_4A = $obj{"occurrence_date_4A"}; ?>
   <input type='text' name='occurrence_date_4A' id='occurrence_date_4A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_date_4A)); ?>'
  </td>
 </tr><br><br>

<span class=text><?php echo xlt('BOX 35-36A. Occurrence Span Codes and Dates'); ?>: </span>
<input type=text style="width: 20px;" size=2 name="occurrence_span_code_1" value="<?php echo attr($obj{"occurrence_span_code_1"});?>">

<tr>
  <td>
   <?php $occurrence_span_date_from_1 = $obj{"occurrence_span_date_from_1"}; ?>
   <input type='text' name='occurrence_span_date_from_1' id='occurrence_span_date_from_1'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_from_1)); ?>'
  </td>
 </tr>

<tr>
  <td>
   <?php $occurrence_span_date_to_1 = $obj{"occurrence_span_date_to_1"}; ?>
   <input type='text' name='occurrence_span_date_to_1' id='occurrence_span_date_to_1'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_to_1)); ?>'
  </td>
 </tr>

<input type=text style="width: 20px;" size=2 name="occurrence_span_code_2" value="<?php echo attr($obj{"occurrence_span_code_2"});?>">

<tr>
  <td>
   <?php $occurrence_span_date_from_2 = $obj{"occurrence_span_date_from_2"}; ?>
   <input type='text' name='occurrence_span_date_from_2' id='occurrence_span_date_from_2'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_from_2)); ?>'
  </td>
 </tr>

<tr>
  <td>
   <?php $occurrence_span_date_to_2 = $obj{"occurrence_span_date_to_2"}; ?>
   <input type='text' name='occurrence_span_date_to_2' id='occurrence_span_date_to_2'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_to_2)); ?>'
  </td>
 </tr><br><br>

<span class=text><?php echo xlt('BOX 35-36B. Occurrence Span Codes and Dates'); ?>: </span>
<input type=text style="width: 20px;" size=2 name="occurrence_span_code_1A" value="<?php echo attr($obj{"occurrence_span_code_1A"});?>">

<tr>
  <td>
   <?php $occurrence_span_date_from_1A = $obj{"occurrence_span_date_from_1A"}; ?>
   <input type='text' name='occurrence_span_date_from_1A' id='occurrence_span_date_from_1A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_from_1A)); ?>'
  </td>
 </tr>

<tr>
  <td>
   <?php $occurrence_span_date_to_1A = $obj{"occurrence_span_date_to_1A"}; ?>
   <input type='text' name='occurrence_span_date_to_1A' id='occurrence_span_date_to_1A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_to_1A)); ?>'
  </td>
 </tr>

<input type=text style="width: 20px;" size=2 name="occurrence_span_code_2A" value="<?php echo attr($obj{"occurrence_span_code_2A"});?>" >

<tr>
  <td>
   <?php $occurrence_span_date_from_2A = $obj{"occurrence_span_date_from_2A"}; ?>
   <input type='text' name='occurrence_span_date_from_2A' id='occurrence_span_date_from_2A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_from_2A)); ?>'
  </td>
 </tr>

<tr>
  <td>
   <?php $occurrence_span_date_to_2A = $obj{"occurrence_span_date_to_2A"}; ?>
   <input type='text' name='occurrence_span_date_to_2A' id='occurrence_span_date_to_2A'
    size=10 value='<?php echo oeFormatShortDate(attr($occurrence_span_date_to_2A)); ?>'
  </td>
 </tr><br><br>

<span class=text><?php echo xlt('BOX 39-41 A. Value Codes and Amounts'); ?>: </span><input type=text style="width: 20px;" size=2 name="value_code_1a" value="<?php echo attr($obj{"value_code_1a"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_1a" value="<?php echo attr($obj{"value_code_amount_1a"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_2a" value="<?php echo attr($obj{"value_code_2a"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_2a" value="<?php echo attr($obj{"value_code_amount_2a"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_3a" value="<?php echo attr($obj{"value_code_3a"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_3a" value="<?php echo attr($obj{"value_code_amount_3a"});?>">

&nbsp;

<span class=text><?php echo xlt('BOX 39-41 B. Value Codes and Amounts'); ?>: </span><input type=text style="width: 20px;" size=2 name="value_code_1b" value="<?php echo attr($obj{"value_code_1b"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_1b" value="<?php echo attr($obj{"value_code_amount_1b"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_2b" value="<?php echo attr($obj{"value_code_2b"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_2b" value="<?php echo attr($obj{"value_code_amount_2b"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_3b" value="<?php echo attr($obj{"value_code_3b"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_3b" value="<?php echo attr($obj{"value_code_amount_3b"});?>"><br><br>

<span class=text><?php echo xlt('BOX 39-41 C. Value Codes and Amounts'); ?>: </span><input type=text style="width: 20px;" size=2 name="value_code_1c" value="<?php echo attr($obj{"value_code_1c"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_1c" value="<?php echo attr($obj{"value_code_amount_1c"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_2c" value="<?php echo attr($obj{"value_code_2c"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_2c" value="<?php echo attr($obj{"value_code_amount_2c"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_3c" value="<?php echo attr($obj{"value_code_3c"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_3c" value="<?php echo attr($obj{"value_code_amount_3c"});?>">

&nbsp;

<span class=text><?php echo xlt('BOX 39-41 D. Value Codes and Amounts'); ?>: </span><input type=text style="width: 20px;" size=2 name="value_code_1d" value="<?php echo attr($obj{"value_code_1d"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_1d" value="<?php echo attr($obj{"value_code_amount_1d"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_2d" value="<?php echo attr($obj{"value_code_2d"});?>">
<input type=text style="width: 65px;" size=9 name="value_code_amount_2d" value="<?php echo attr($obj{"value_code_amount_2d"});?>">
<input type=text style="width: 20px;" size=2 name="value_code_3d" value="<?php echo attr($obj{"value_code_3d"});?>">
<input type=text style="width: 63px;" size=9 name="value_code_amount_3d" value="<?php echo attr($obj{"value_code_amount_3d"});?>"><br><br>

<span class=text><?php echo xlt('BOX 63. Treatment Authorization Codes');?>: </span>
<input type=text style="width: 112px;" size=15 maxlength=15 name="prior_auth_number" value="<?php echo attr($obj{"prior_auth_number"});?>">
<input type=text style="width: 112px;" size=15 maxlength=15 name="prior_auth_number_1" value="<?php echo attr($obj{"prior_auth_number_1"});?>">
<input type=text style="width: 112px;" size=15 maxlength=15 name="prior_auth_number_2" value="<?php echo attr($obj{"prior_auth_number_2"});?>"><br><br>
<tr>
<span class=text><?php echo xlt('BOX 76. Attending Physician Information'); ?>: </span>
    <td><?php  # Build a drop-down list of providers. # Added (TLH)
               genProviderSelect('attending_id', '-- '.xl("Please Select").' --',$obj{"attending_id"}, false, true);
               #error_log("Provider id: ".$obj{"attending_id"}, 0);
        ?></td>&nbsp;&nbsp;
</tr>
<br>
<br>

<span class=text><?php echo xlt('BOX 77. Operating Physician Information'); ?>: </span>

<tr>
    <td><?php  # Build a drop-down list of providers. # Added (TLH)
               genProviderSelect('operating_id', '-- '.xl("Please Select").' --',$obj{"operating_id"}, false, true);
        ?></td>&nbsp;&nbsp;

</tr>
<br>
<br>
<tr>
<span class=text><?php echo xlt('BOX 78. Other Physician # 1 Information'); ?>: </span>
    <td><?php  # Build a drop-down list of providers. # Added (TLH)
               genProviderSelect('other_1_id', '-- '.xl("Please Select").' --',$obj{"other_1_id"}, false, true);
        ?></td>&nbsp;&nbsp;
</tr>
<br>
<br>
<tr>
<span class=text><?php echo xlt('BOX 79. Other Physician # 2 Information'); ?>: </span>


    <td><?php  # Build a drop-down list of providers. # Added (TLH)
               genProviderSelect('other_2_id', '-- '.xl("Please Select").' --',$obj{"other_2_id"}, false, true);
        ?></td>&nbsp;&nbsp;
</tr>
<br>
<br>


</div>

<div>
<!-- Save/Cancel buttons -->
<input type="button" class="save" value="<?php echo xlt('Save'); ?>"> &nbsp &nbsp &nbsp &nbsp;
<input type="button" class="dontsave" value="<?php echo xlt('Don\'t Save'); ?>"> &nbsp;
</div>
</div> <!-- end form_container -->
</form>
</div>
</div>
</div>

</body>

<script language="javascript">

// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $(".save").click(function() { top.restoreSession(); document.my_form.submit(); });
    $(".dontsave").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
});

    $(function() {
        $("#hospitalization_date_from, #hospitalization_date_to, #accident_date, #occurrence_date_1, #occurrence_date_2, #occurrence_date_3, #occurrence_date_4, #occurrence_date_1A, #occurrence_date_2A, #occurrence_date_3A, #occurrence_date_4A, #occurrence_span_date_from_1, #occurrence_span_date_to_1, #occurrence_span_date_from_2, #occurrence_span_date_to_2, #occurrence_span_date_from_1A, #occurrence_span_date_to_1A, #occurrence_span_date_from_2A, #occurrence_span_date_to_2A" ).datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });

</script>

</html>

