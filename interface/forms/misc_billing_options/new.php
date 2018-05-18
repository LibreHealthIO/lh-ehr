<?php
/*
 *  new.php for the creation of the misc_billing_form
 *
 *  This program creates the misc_billing_form
 *
 * @copyright Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 *
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-license.php.
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/api.inc");
require_once("$srcdir/formdata.inc.php");
require_once("date_qualifier_options.php");
require_once("$srcdir/formsoptions.inc.php");
require_once("$srcdir/headers.inc.php");
formHeader("Misc Billing Options");

$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

    
if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}

$formid   = 0 + formData('id', 'G');
$form_name = 'form_misc_billing_options';
if (empty($formid)) {
    $formid = checkFormIsActive($form_name,$encounter);
}
$obj = $formid ? formFetch("form_misc_billing_options", $formid) : array();
function generateDateQualifierSelect($name,$options,$obj)
{
    echo     "<select name='".attr($name)."'>";
    for($idx=0;$idx<count($options);$idx++)
    {
        echo "<option value='".attr($options[$idx][1])."'";
        if($obj[$name]==$options[$idx][1]) echo " selected";
        echo ">".text($options[$idx][0])."</option>";
    }
    echo     "</select>";

}

?>
<html>
  <head>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/jquery.datetimepicker.css">
    <!-- Get Bootstrap, jQuery (required for bootstrap), and Datepicker -->
    <?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1', 'datepicker']); ?>
  </head>
  <body class="body_top">
    <div id="form-main">
     <div id="form-div">
      <div class='container'>
        <style>
          * {
            font-weight:normal;
            color: black;
          }
        </style>
        <form method=post <?php echo "name='my_form' " .  "action='$rootdir/forms/misc_billing_options/save.php?id=" . attr($formid) . "'\n";?> >
          <h2><?php echo xlt('Misc Billing Options for HCFA-1500'); ?></h2>
          <br/>
          <i><?php echo xlt('Checked box = yes ,  empty = no');?></i>
          <br><br>

          <!-- Can't use bootstrap because checkboxes are by default styled o the left -->
          <div id="box-10a">
            <label>
              <span class="text"><?php echo xlt('BOX 10 A. Employment related '); ?>: </span>
              <input type=checkbox name="employment_related" value="1" <?php if ($obj['employment_related'] == "1") echo "checked";?>>
            </label>
            <br><br>
          </div>

          <div id="box-10b">
            <label>
              <span class="text"><?php echo xlt('BOX 10 B. Auto Accident '); ?>: </span>
              <input type=checkbox name="auto_accident" value="1" <?php if ($obj['auto_accident'] == "1") echo "checked";?>>
            </label>
            <span class="text"><?php echo xlt('State'); ?>: </span>
            <div style="width: 150px; display: inline-block">
              <?php
                echo generate_select_list('accident_state', 'state', $obj{"accident_state"}, 'State');
              ?>
            </div>
            <br><br>
          </div>

          <div id="box-10c">
            <label>
              <span class="text"><?php echo xlt('BOX 10 C. Other Accident '); ?>: </span>
              <input type=checkbox name="other_accident" value="1" <?php if ($obj['other_accident'] == "1") echo "checked";?>>
            </label>
            <br><br>
          </div>

          <div id="box-10d">
            <span class="text"><?php echo xlt('BOX 10 D. EPSDT Referral Code ');?></span>
            <input class="form-control" type=text style="width: 50px; display: inline-block" name="medicaid_referral_code" value="<?php echo attr($obj{"medicaid_referral_code"});?>" >&nbsp;&nbsp;&nbsp;&nbsp;
            <label>
              <span class="text"><?php echo xlt('EPSDT '); ?>: </span>
              <input type=checkbox name="epsdt_flag" value="1" <?php if ($obj['epsdt_flag'] == "1") echo "checked";?>>
            </label>
            <br><br>
          </div>

          <span class="text" title="<?php echo xla("For HCFA 02/12 Onset date specified on the Encounter Form needs a qualifier");?>"></span>

          <div id="box-14">
            <span class="text"><?php echo xlt('BOX 14. Onset Date and Qualifier');?>.</span>    
            <br><br>
            <?php $onset_date = $obj{"onset_date"}; ?>
            <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="onset_date"
              id="onset_date"
              size='10'
              value='<?php echo oeFormatShortDate(attr($onset_date)) ?>'
            />
           <!-- <td><span class=text><?php echo xlt('BOX 14 Onset Date Qualifier'); ?>: </span>-->
            &nbsp;
            <?php generateDateQualifierSelect("box_14_date_qual",$box_14_qualifier_options,$obj); ?></span></td>
            <br><br>
          </div>
          
          <div id="box-15">

          <span class="text" title="<?php echo xla('For HCFA 02/12 Box 15 is Other Date with a qualifier to specify what the date indicates');?>"></span>
            <span class="text"><?php echo xlt('BOX 15. Other Date and Qualifier');?>.</span>    
            <br><br>
            <?php $date_initial_treatment = $obj{"date_initial_treatment"}; ?>
            <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="date_initial_treatment"
              id="date_initial_treatment"
              size='10'
              value='<?php echo oeFormatShortDate(attr($date_initial_treatment)) ?>'
            />

            &nbsp;
            <?php generateDateQualifierSelect("box_15_date_qual",$box_15_qualifier_options,$obj); ?>


            <br><br>
          </div>

          <div id="box-16">
            <span class="text"><?php echo xlt('BOX 16. Date unable to work');?></span>
            <br><br>
            <span><?php echo xlt('From Date');?>:</span>
            <?php $off_work_from = $obj{"off_work_from"}; ?>
            <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="off_work_from"
              id="off_work_from"
              size='10'
              value='<?php echo oeFormatShortDate(attr($off_work_from)) ?>'
            />
            <span><?php echo xlt('To Date');?>:</span>
            <?php $off_work_to = $obj{"off_work_to"}; ?>
            <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="off_work_to"
              id="off_work_to"
              size='10'
              value='<?php echo oeFormatShortDate(attr($off_work_to)) ?>'
            />
            <br><br>
          </div>

          <div id="box-17">
            <span class="text"><?php echo xlt('BOX 17. Provider Information') ?>:</span>
            <br><br>
            <span><?php echo xlt('Provider') ?></span>
            <?php  # Build a drop-down list of providers. # Added (TLH)
              genProviderSelect('provider_id', '-- '.xl("Please Select").' --',$obj{"provider_id"}, false, true);
            ?>
            &nbsp;
            <span><?php echo xlt('Provider Qualifier'); ?>: </span>
              <?php
                echo generate_select_list('provider_qualifier_code', 'provider_qualifier_code',$obj{"provider_qualifier_code"}, 'Provider Qualifier Code');
              ?>
            <br><br>
          </div>

          <div id="box-18">
            <span class="text"><?php echo xlt('BOX 18. Hospitalization date');?></span>
            <br><br>
            <span><?php echo xlt('From Date');?>:</span>
            <?php $hospitalization_date_from = $obj{"hospitalization_date_from"}; ?>
            <input 
              class="form-control"
              style="display: inline-block; width: 100px"
              type='text' 
              name='hospitalization_date_from' 
              id="hospitalization_date_from"
              size='10' 
              value='<?php echo oeFormatShortDate(attr($hospitalization_date_to)) ?>'
            />
            <span><?php echo xlt('To Date');?>:</span>
            <?php $hospitalization_date_from = $obj{"hospitalization_date_to"}; ?>
            <input 
              class="form-control"
              style="display: inline-block; width: 100px"
              type='text' 
              name='hospitalization_date_to' 
              id="hospitalization_date_to"
              size='10' 
              value='<?php echo oeFormatShortDate(attr($hospitalization_date_to)) ?>'
            />
            <br><br>
          </div>

          <div id="box-20">
            <label>
              <span class="text"><?php echo xlt('BOX 20. Is Outside Lab used?'); ?>: </span>
              <input type=checkbox name="outside_lab" value="1" <?php if ($obj['outside_lab'] == "1") echo "checked";?>>
            </label>
            <span class="text"><?php echo xlt('Amount Charges'); ?>: </span>
            <input
              class="form-control"
              style="display: inline-block; width: 75px"
              type=entry
              size="7"
              align='right'
              name="lab_amount"
              value="<?php echo attr($obj{"lab_amount"});?>"
            />
            <br><br>
          </div>

          <div id="box-22">
            <span class="text"><?php echo xlt('BOX 22. Medicaid Information ');?></span>
            <br><br>
            <span><?php echo xlt('Resubmission Code (ICD-9) '); ?>:</span>
            <input class="form-control" style="display: inline-block; width: 100px" type=entry size=9 name="medicaid_resubmission_code" value="<?php echo attr($obj{"medicaid_resubmission_code"});?>" >
            <span><?php echo xlt('Original Reference No. ');?></span>
            <input class="form-control" style="display: inline-block; width: 175px" type=entry size=15 name="medicaid_original_reference" value="<?php echo attr($obj{"medicaid_original_reference"});?>" >
            <br><br>
          </div>

          <div id="box-23">
            <span class="text"><?php echo xlt('BOX 23. Prior Authorization No. ');?></span>
            <input class="form-control" style="display: inline-block; width: 175px" type=entry size=15 name="prior_auth_number" value="<?php echo attr($obj{"prior_auth_number"});?>" >
            <br><br>
          </div>

          <div id="x12-only">
            <label>
              <span class="text"><?php echo xlt('X12 only: Replacement Claim '); ?>: </span>
              <input type=checkbox name="replacement_claim" value="1" <?php if ($obj['replacement_claim'] == "1") echo "checked";?>>
            </label>
            <br><br>
            <span class="text"><?php echo xlt('X12 only ICN resubmission No. ');?></span>
            <input class="form-control" style="display: inline-block; width: 250px" type=entry size=35 name="icn_resubmission_number" value="<?php echo attr($obj{"icn_resubmission_number"});?>" >
            <br><br>
          </div>

          <div id="additional-notes">
            <span class="text"><?php echo xlt('Additional Notes'); ?>: </span>
            <br>
            <textarea class="form-control" cols=40 rows=8 wrap=virtual name="comments"><?php echo text($obj{"comments"});?></textarea>
            <br><br>
          </div>

          <div>
            <!-- Save/Cancel buttons -->
            <input type="button" id="save" class='cp-submit' value="<?php echo xla('Save'); ?>"> &nbsp; 
            <input type="button" id="dontsave" class="deleter cp-negative" value="<?php echo xla('Cancel'); ?>"> &nbsp; 
          </div>
        </form>
      </div>
     </div>
    </div>

    <script language="javascript">
    // jQuery stuff to make the page a little easier to use

    $(document).ready(function(){
        $("#save").click(function() { top.restoreSession(); document.my_form.submit(); });
        $("#dontsave").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/encounter_top.php";?>'; });
    });
    </script>
  </body>
<script>
    $(function() {
        $("#hospitalization_date_from, #hospitalization_date_to, #off_work_from, #off_work_to, #onset_date, #date_initial_treatment" ).datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>
</html>