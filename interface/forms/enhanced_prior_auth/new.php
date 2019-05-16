<?php
/*
 * new.php for the creation of the Enhanced Prior Authorization
 *
 * This program creates the Enhanced Prior Authorization Form
 * Migrated this from the original template driven form developed
 * by Sherwin Gaddis <sherwingaddis@gmail.com>
 *
 * @copyright Copyright (C) 2018 Terry Hill <teryhill@librehealth.io>
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author  Terry Hill <teryhill@librehealth.io>
 * @author  Nilesh Prasad <prasadnilesh96@gmail.com>
 * @author  Sherwin Gaddis <sherwingaddis@gmail.com>
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
require_once("$srcdir/formsoptions.inc.php");
require_once("$srcdir/headers.inc.php");
formHeader("Enhanced Prior Authorization Form");

$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

if (! $encounter) { // comes from globals.php
 die(xl("Internal error: we do not seem to be in an encounter!"));
}

$formid   = 0 + formData('id', 'G');
$form_name = 'form_enhanced_prior_auth';
if (empty($formid)) {
    $formid = checkFormIsActive($form_name,$encounter);
}

$obj = $formid ? formFetch("form_enhanced_prior_auth", $formid) : array();

    $archived = 0 ;

$totused = $obj{"used"} + $obj{"addused"} ;
$used = $obj{"used"};
$temp_auth = $obj{"temp_auth"};

?>
<html>
  <head>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/css/jquery.datetimepicker.css">
    <!-- Get Bootstrap, jQuery (required for bootstrap), and Datepicker -->
    <?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1', 'datepicker']); ?>
    <script>
function showHint(str) {
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}

 function validate() {
            var EnteredDate = document.getElementById("auth_from").value; // get the auth from date

            var today = document.getElementById("auth_to").value;

            if (EnteredDate > today) {
                alert("FROM: date is greater than TO: date ");
                return false;
            }
            var AuthReq = document.getElementById("noAuthReq").checked;
            var Claims = document.getElementById("claims").value;
            //var ClearReq = document.getElementById("clear_req").checked; // Not needed will delete
            var n = Claims.length;

            if(document.getElementById("noAuthReq").checked && n === 0){
                  alert("Please fill in number of claims");
                  return false;
            }
        }

</script>
  </head>
  <body class="body_top">
    <div id="form-main">
     <div id="form-div">
      <div class='container'>
        <style>
        #scrollspy-me{position: relative;}
          * {
            font-weight:normal;
            color: black;
          }
        </style>
        <form method=post <?php echo "name='my_form' " .  "action='$rootdir/forms/enhanced_prior_auth/save.php?id=" . attr($formid) . "'\n";?> >
          <h2><?php echo xlt('Enhanced Prior Authorization Form'); ?></h2>
          <br/>

          <div id="numofinsplan">
           <?php $insplan = $obj{"ins_plan_num"}; ?>
               <span class="text"><?php echo xlt('Plan Allowed Visits'); ?>: </span>
                      <input class="form-control" style="display: inline-block; width: 65px" type=number size="5" name="insplan" id="claims" value="<?php echo $insplan;?>" title="Set the number of times no auth can be used" >
            <!--<br></br>-->
&nbsp;&nbsp;&nbsp;&nbsp;
           <span class="text"><?php echo xlt('Plan Allowed Visits Date'); ?>:</span>
           <td>
           <?php $ins_auth_date = $obj{"ins_auth_date"}; ?>
              <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="ins_auth_date"
              id="ins_auth_date"
              size='10'
              value='<?php echo oeFormatShortDate(attr($ins_auth_date)) ?>'
            />
           </td>
          <br><br>
          </div>
          <div id="numofauth">
           <?php $auth_for = $obj{"auth_for"}; ?>
               <span class="text"><?php echo xlt('Number of Authorized Visits'); ?>: </span>
                   <input class="form-control" style="display: inline-block; width: 65px" type=number size="5" name="auth_for" id="claims" value="<?php echo $auth_for;?>" title="Set the number of times auth can be used" >
            &nbsp;&nbsp;&nbsp;&nbsp;
           <span class="text"><?php echo xlt('Authorized Visits Date'); ?>:</span>
           <td>
           <?php $date_auth_for = $obj{"date_auth_for"}; ?>
              <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="date_auth_for"
              id="date_auth_for"
              size='10'
              value='<?php echo oeFormatShortDate(attr($date_auth_for)) ?>'
            />
           </td>
            <br></br>
          </div>

          <div id="numofscript">
           <?php $script_num = $obj{"prescription_num"}; ?>
               <span class="text"><?php echo xlt('Number of Prescription Visits'); ?>: </span>
                   <input class="form-control" style="display: inline-block; width: 65px" type=number size="5" name="script_num" id="claims" value="<?php echo $script_num;?>" title="Set the number of times prescription auth can be used" >
            <br></br>
          </div>

          <div id="authdollar">
           <?php $dollar = $obj{"dollar"}; ?>
               <span class="text"><?php echo xlt('Maximun Dollar Amount for Authorization'); ?>: </span>
                   <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="dollar" id="claims" value="<?php echo $dollar;?>" title="Set the number of times no auth can be used" >
            <br></br>
          </div>

         <div id="reeval">
           <?php $re_eval_num = $obj{"re_eval"}; ?>
               <span class="text"><?php echo xlt('Re-Evaluation after this number of visits'); ?>: </span>
                  <input class="form-control" style="display: inline-block; width: 85px" type=number size="15" name="re_eval_num" value="<?php echo $re_eval_num;?>" title="Re-Evaluation after this number of visits" >
            <br></br>
          </div>

           <div id="casenum">
           <?php $case_num = $obj{"case_number"}; ?>
               <span class="text"><?php echo xlt('Case Number for Authorizations'); ?>: </span>
               <input class="form-control" style="display: inline-block; width: 85px" type=entry size="15" name="case_num" value="<?php echo $case_num;?>" title="Case number for the Authorization">
            <br></br>
          </div>

          <div id="priauthnum">
                <span class="text"><?php echo xlt('Prior Authorization Number');?>: </span>
                   <input class="form-control" style="display: inline-block; width: 75px" type=entry size=15 id="prior_auth_number" name="prior_auth_number" value="<?php echo attr($obj{"prior_auth_number"});?>" >

            <br><br>
          </div>
         <div id="bodypart">
         <?php $bodypart = $obj{"bodypart"}; ?>
         <span class="text"><?php echo xlt('Body Part'); ?>: </span>
         <input class="form-control" style="display: inline-block; width: 275px" type=entry size="55" name="bodypart" value="<?php echo $bodypart;?>">

            <br><br>
          </div>
          <div id="descrip">
         <?php $desc = $obj{"ddesc"}; ?>
         <span class="text"><?php echo xlt('Description'); ?>: </span>
         <input class="form-control" style="display: inline-block; width: 275px" type=entry size="55" name="desc" value="<?php echo $desc;?>">
         <br><br>
          </div>
          <div id="authfromto">
   <span class="text"><?php echo xlt('From Date'); ?>:</span>
    <td>
        <?php $auth_from = $obj{"auth_from"}; ?>
                <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="auth_from"
              id="auth_from"
              size='10'
              value='<?php echo oeFormatShortDate(attr($auth_from)) ?>'
            />

        <span><?php echo xlt('To Date');?>:</span>
        <?php $auth_to = $obj{"auth_to"}; ?>
                <input
              class="form-control"
              style="display: inline-block; width: 10rem"
              type="text"
              name="auth_to"
              id="auth_to"
              size='10'
              value='<?php echo oeFormatShortDate(attr($auth_to)) ?>'
            />
    </td>
    <br><br>
          </div>
          <div id="authtotused">
          <?php //if ($archived == '1') { $used = $obj{"used"}; } ?>
          <span class="text"><?php echo xlt('Plan Used'); ?>: </span>
          <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="totused" value="<?php echo $totused;?>" title="Read Only" readonly>

           <br><br>
          </div>
          <div id="authused">
          <?php if ($archived == '1') { $used = $obj{"used"}; } ?>
 <!--         <span class="text"><?php //echo xlt('Number Used'); ?>: </span>-->
          <input class="hidden" style="display: inline-block; width: 65px" type=entry size="5" name="used" value="<?php echo $used;?>" title="Read Only" readonly>
          <?php if ($archived == '1') { $temp_auth = $obj{"temp_auth"}; } ?>
          <input class="hidden" style="display: inline-block; width: 65px" type=entry size="5" name="temp_auth" value="<?php echo $temp_auth;?>" title="Read Only" readonly>

<!--           <br><br>-->
          </div>
          <div id="authadditionalused">
          <?php if ($archived == '1') {
                   $addused = $obj{"addused"};
                } else {
                   $addused = $obj{"addused"};
                }                ?>
          <span class="text"><?php echo xlt('Non-Oxford treatement visits'); ?>: </span>
          <input class="form-control" style="display: inline-block; width: 65px" type=number size="5" name="addused" value="<?php echo $addused;?>">

           <br><br>
          </div>
          <div id="authcontact">
          <?php $auth_contact = $obj{"auth_contact"}; ?>
          <span class="text"><?php echo xlt('Authorization Contact'); ?>:</span>
          <input class="form-control" style="display: inline-block; width: 275px" type=entry size="25" name="auth_contact" value="<?php echo $auth_contact;?>">
            <br><br>
          </div>

          <div id="authphone">
          <?php $auth_phone = $obj{"auth_phone"}; ?>
          <span class="text"><?php echo xlt('Authorization Phone'); ?>:</span>
          <input class="form-control" style="display: inline-block; width: 150px" type=entry size="15" name="auth_phone" value="<?php echo $auth_phone;?>">
             <br><br>
          </div>

          <div id="authcode">
           <?php $code1 = $obj{"code1"};
                 $code2 = $obj{"code2"};
                 $code3 = $obj{"code3"};
                 $code4 = $obj{"code4"};
                 $code5 = $obj{"code5"};
                 $code6 = $obj{"code6"};
                 $code7 = $obj{"code7"};
           ?>
           <span class="text"><?php echo xlt('Code'); ?>:</span><td>
           <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="code1" value="<?php echo $code1;?>">
           <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="code2" value="<?php echo $code2;?>">
           <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="code3" value="<?php echo $code3;?>">
           <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="code4" value="<?php echo $code4;?>">
           <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="code5" value="<?php echo $code5;?>">
           <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="code6" value="<?php echo $code6;?>">
           <input class="form-control" style="display: inline-block; width: 65px" type=entry size="5" name="code7" value="<?php echo $code7;?>"></td>
          </div>
          <div id="additional-notes">
            <span class="text"><?php echo xlt('Additional Notes'); ?>: </span>
            <br>
            <textarea class="form-control" cols=40 rows=8 wrap=virtual name="comments"><?php echo text($obj{"comments"});?></textarea>
            <br><br>
          </div>

<!--          <div id="authalert">
            <span class="text"><?php echo xlt('Authorization Alert'); ?>:</span>
            <input class="form-control" style="display: inline-block; width: 105px" type=entry size="5" value="" />
            <select><option name="units" value="" /></option>
                <option name="units" value="days" <?php if(alert == "days") { ?>selected <?php } ?>/><?php echo xlt('Days'); ?></option>
                <option name="units" value="units" <?php if(alert == "units") { ?>selected <?php } ?>/><?php echo xlt('Units'); ?></option>
                <option name="units" value="open" <?php if(alert == "open") { ?>selected <?php } ?>/><?php echo xlt('Open Auth'); ?></option>
            </select>
            <br><br>
          </div>

          <div id="supoveride">
    <?php $override = $obj{"override"};?>
    <span class="text"><?php echo xlt('Supervisor Override'); ?>:</span>
    <input type="radio" name="override" value="1"
    <?php if($override == 1) { ?>checked <?php } ?>/><?php if($override == 1){ ?><input type="radio" name="override" value="" /><?php echo xlt('Clear'); ?> <?php }?>

            <br><br>
          </div>
-->
          <div id="archiveauth">
          <?php if ($archived == "0" ) { ?>
          <span class="text"><?php echo xlt('Archive This Authorization'); ?>:</span>
          <input type="radio" name="archived" value="1" />
          <?php } else { ?>
            <span class="text"><?php echo xlt('This Authorization is Archived'); ?>:</span>
            <!--<input type="radio" name="archived" value=$obj{"archived"} />-->
          <?php } ?>
            <br><br>
          </div>

          <div>
            <!-- Save/Cancel buttons -->
            <?php if ($archived == "0" ) { ?>
            <input type="button" id="save" class='cp-submit' value="<?php echo xla('Save'); ?>"> &nbsp;
            <?php } ?>
            <input type="button" id="dontsave" class="deleter cp-negative" value="<?php echo xla('Cancel'); ?>"> &nbsp;
          </div>
          <input type="hidden" name="id" value="<?php echo $id;?>" />
          <input type="hidden" name="activity" value="<?php echo $activity;?>">
          <input type="hidden" name="pid" value="<?php echo $pid;?>">
          <input type="hidden" name="process" value="true">

        </form>
      </div>
     </div>
    </div>

    <script language="javascript">

    $(document).ready(function(){
       $('#save').click(function() {
             if ($('#prior_auth_number').val().length == '0')  {
                 //dont submit the form no Authorization Number provided
                 //console.error($('#prior_auth_number').val().length);
                 alert($('#prior_auth_number').val() + "Please Enter a Prior Authorization Number.");
           } else {
                 //submit the form}
                 top.restoreSession();
                 document.my_form.submit();
           }
       });
        $("#dontsave").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/encounter_top.php";?>'; });
    });
    </script>
  </body>
<script>
    $(function() {
        $("#date_auth_for").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#ins_auth_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#auth_from").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#auth_to").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });

   function clearThis(target){
     target = document.getElementById(target);
     target.value = "";
   }
</script>
</html>
