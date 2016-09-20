<?php
/*
 *  Demographics_full.php
 *
 *  This program demographics_full.php is the Patient summary screen edit function.
 *
 *  The changes to this file as of November 16 2016 to include the insurance inactivate enhancement
 *  are covered under the terms of the Mozilla Public License, v. 2.0
 *
 * @copyright Copyright (C) 2016 Terry Hill <terry@lillysystems.com>
 * No previous copyright information. This is an original OpenEMR program.
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
 * @package LibreEHR
 * @author Terry Hill <terry@lilysystems.com>
 * No other authors listed in original program header
 * @link http://www.libreehr.org
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
require_once("../../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/erx_javascript.inc.php");

 // Session pid must be right or bad things can happen when demographics are saved!
 //
 include_once("$srcdir/pid.inc");
 $set_pid = $_GET["set_pid"] ? $_GET["set_pid"] : $_GET["pid"];
 if ($set_pid && $set_pid != $_SESSION["pid"]) {
  setpid($set_pid);
 }

 include_once("$srcdir/patient.inc");

 $result = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
 $result2 = getEmployerData($pid);

 // Check authorization.
 if ($pid) {
  if (!acl_check('patients', 'demo', '', 'write'))
   die(xl('Updating demographics is not authorized.'));
 } else {
  if (!acl_check('patients', 'demo', '', array('write','addonly') ))
   die(xl('Adding demographics is not authorized.'));
 }

$CPR = 4; // cells per row

// $statii = array('married','single','divorced','widowed','separated','domestic partner');
// $langi = getLanguages();
// $ethnoraciali = getEthnoRacials();
// $provideri = getProviderInfo();
if ($GLOBALS['insurance_information'] != '0') {
    $insurancei = getInsuranceProvidersExtra();
}else{
    $insurancei = getInsuranceProviders();
}

$fres = sqlStatement("SELECT * FROM layout_options " .
  "WHERE form_id = 'DEM' AND uor > 0 " .
  "ORDER BY group_name, seq");
?>
<html>
<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">

<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>

<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../../library/js/jquery-3.1.0.min.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>
<script type="text/javascript" src="../../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/options.js.php"); ?>

<link rel="stylesheet" type="text/css" href="../../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />

<script type="text/javascript">
$(document).ready(function(){
    tabbify();
    enable_modals();

    // special size for
    $(".medium_modal").fancybox( {
        'overlayOpacity' : 0.0,
        'showCloseButton' : true,
        'frameHeight' : 460,
        'frameWidth' : 650
    });

});

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

//code used from http://tech.irt.org/articles/js037/
function replace(string,text,by) {
 // Replaces text with by in string
 var strLength = string.length, txtLength = text.length;
 if ((strLength == 0) || (txtLength == 0)) return string;

 var i = string.indexOf(text);
 if ((!i) && (text != string.substring(0,txtLength))) return string;
 if (i == -1) return string;

 var newstr = string.substring(0,i) + by;

 if (i+txtLength < strLength)
  newstr += replace(string.substring(i+txtLength,strLength),text,by);

 return newstr;
}

function upperFirst(string,text) {
 return replace(string,text,text.charAt(0).toUpperCase() + text.substring(1,text.length));
}

<?php 
$count = getActiveInsuranceData();
for ($i=0;$i<count($count);$i++) { ?>
function auto_populate_employer_address<?php echo $i ?>(){
 var f = document.demographics_form;
 if (f.form_i<?php echo $i?>subscriber_relationship.options[f.form_i<?php echo $i?>subscriber_relationship.selectedIndex].value == "self")
 {
  f.i<?php echo $i?>subscriber_fname.value=f.form_fname.value;
  f.i<?php echo $i?>subscriber_mname.value=f.form_mname.value;
  f.i<?php echo $i?>subscriber_lname.value=f.form_lname.value;
  f.i<?php echo $i?>subscriber_street.value=f.form_street.value;
  f.i<?php echo $i?>subscriber_city.value=f.form_city.value;
  f.form_i<?php echo $i?>subscriber_state.value=f.form_state.value;
  f.i<?php echo $i?>subscriber_postal_code.value=f.form_postal_code.value;
  if (f.form_country_code)
    f.form_i<?php echo $i?>subscriber_country.value=f.form_country_code.value;
  f.i<?php echo $i?>subscriber_phone.value=f.form_phone_home.value;
  f.i<?php echo $i?>subscriber_DOB.value=f.form_DOB.value;
  if(typeof f.form_ss!="undefined")
    {
        f.i<?php echo $i?>subscriber_ss.value=f.form_ss.value;  
    }
  f.form_i<?php echo $i?>subscriber_sex.value = f.form_sex.value;
  f.i<?php echo $i?>subscriber_employer.value=f.form_em_name.value;
  f.i<?php echo $i?>subscriber_employer_street.value=f.form_em_street.value;
  f.i<?php echo $i?>subscriber_employer_city.value=f.form_em_city.value;
  f.form_i<?php echo $i?>subscriber_employer_state.value=f.form_em_state.value;
  f.i<?php echo $i?>subscriber_employer_postal_code.value=f.form_em_postal_code.value;
  if (f.form_em_country)
    f.form_i<?php echo $i?>subscriber_employer_country.value=f.form_em_country.value;
 }
}

<?php } ?>

function popUp(URL) {
 day = new Date();
 id = day.getTime();
 top.restoreSession();
 eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=400,height=300,left = 440,top = 362');");
}

function checkNum () {
 var re= new RegExp();
 re = /^\d*\.?\d*$/;
 str=document.demographics_form.monthly_income.value;
 if(re.exec(str))
 {
 }else{
  alert("<?php xl('Please enter a monetary amount using only numbers and a decimal point.','e'); ?>");
 }
}

// Indicates which insurance slot is being updated.
var insurance_index = 0;

// The OnClick handler for searching/adding the insurance company.
function ins_search(ins) {
    insurance_index = ins;
    return false;
}

// The ins_search.php window calls this to set the selected insurance.
function set_insurance(ins_id, ins_name) {
 var thesel = document.forms[0]['i' + insurance_index + 'provider'];
 var theopts = thesel.options; // the array of Option objects
 var i = 0;
 for (; i < theopts.length; ++i) {
  if (theopts[i].value == ins_id) {
   theopts[i].selected = true;
   return;
  }
 }
 // no matching option was found so create one, append it to the
 // end of the list, and select it.
 theopts[i] = new Option(ins_name, ins_id, false, true);
}

// This capitalizes the first letter of each word in the passed input
// element.  It also strips out extraneous spaces.
function capitalizeMe(elem) {
 var a = elem.value.split(' ');
 var s = '';
 for(var i = 0; i < a.length; ++i) {
  if (a[i].length > 0) {
   if (s.length > 0) s += ' ';
   s += a[i].charAt(0).toUpperCase() + a[i].substring(1);
  }
 }
 elem.value = s;
}

function divclick(cb, divid) {
 var divstyle = document.getElementById(divid).style;
 if (cb.checked) {
  divstyle.display = 'block';
 } else {
  divstyle.display = 'none';
 }
 return true;
}

// Compute the length of a string without leading and trailing spaces.
function trimlen(s) {
 var i = 0;
 var j = s.length - 1;
 for (; i <= j && s.charAt(i) == ' '; ++i);
 for (; i <= j && s.charAt(j) == ' '; --j);
 if (i > j) return 0;
 return j + 1 - i;
}

function validate(f) {
 var errCount = 0;
 var errMsgs = new Array();
<?php generate_layout_validation('DEM'); ?>

 var msg = "";
 msg += "<?php xl('The following fields are required', 'e' ); ?>:\n\n";
 for ( var i = 0; i < errMsgs.length; i++ ) {
    msg += errMsgs[i] + "\n";
 }
 msg += "\n<?php xl('Please fill them in before continuing.', 'e'); ?>";

 if ( errMsgs.length > 0 ) {
    alert(msg);
 }
 
//Patient Data validations
 <?php if($GLOBALS['erx_enable']){ ?>
 alertMsg='';
 for(i=0;i<f.length;i++){
  if(f[i].type=='text' && f[i].value)
  {
   if(f[i].name == 'form_fname' || f[i].name == 'form_mname' || f[i].name == 'form_lname')
   {
    alertMsg += checkLength(f[i].name,f[i].value,35);
    alertMsg += checkUsername(f[i].name,f[i].value);
   }
   else if(f[i].name == 'form_street' || f[i].name == 'form_city')
   {
    alertMsg += checkLength(f[i].name,f[i].value,35);
    alertMsg += checkAlphaNumericExtended(f[i].name,f[i].value);
   }
   else if(f[i].name == 'form_phone_home')
   {
    alertMsg += checkPhone(f[i].name,f[i].value);
   }
  }
 }
 if(alertMsg)
 {
   alert(alertMsg);
   return false;
 }
 <?php } ?>
 //return false;
 
// Some insurance validation.
<?php $count_insurance = getActiveInsuranceData();?>
 for (var i = 0; i < (<?php echo count($count_insurance);?>); ++i) {
  subprov = 'i' + i + 'provider';
  if (!f[subprov] || f[subprov].selectedIndex <= 0) continue;
  var subpfx = 'i' + i + 'subscriber_';
  var subrelat = f['form_' + subpfx + 'relationship'];
  var samename =
   f[subpfx + 'fname'].value == f.form_fname.value &&
   f[subpfx + 'mname'].value == f.form_mname.value &&
   f[subpfx + 'lname'].value == f.form_lname.value;
  var ss_regexp=/[0-9][0-9][0-9]-?[0-9][0-9]-?[0-9][0-9][0-9][0-9]/;
  var samess=true;
  var ss_valid=false;
  if(typeof f.form_ss!="undefined")
      {
        samess = f[subpfx + 'ss'].value == f.form_ss.value;
        ss_valid=ss_regexp.test(f[subpfx + 'ss'].value) && ss_regexp.test(f.form_ss.value);  
      }
  if (subrelat.options[subrelat.selectedIndex].value == "self") {
   if (!samename) {
    if (!confirm("<?php echo xls('Subscriber relationship is self but name is different! Is this really OK?'); ?>"))
     return false;
   }
   if (!samess && ss_valid) {
    if(!confirm("<?php echo xls('Subscriber relationship is self but SS number is different!')." ". xls("Is this really OK?"); ?>"))
    return false;
   }
  } // end self
  else {
   if (samename) {
    if (!confirm("<?php echo xls('Subscriber relationship is not self but name is the same! Is this really OK?'); ?>"))
     return false;
   }
   if (samess && ss_valid)  {
    if(!confirm("<?php echo xls('Subscriber relationship is not self but SS number is the same!') ." ". xls("Is this really OK?"); ?>"))
    return false;
   }
  } // end not self
 } // end for

 return errMsgs.length < 1;
}

function submitme() {
 var f = document.forms[0];
 if (validate(f)) {
  top.restoreSession();
  f.submit();
 }
}

// Onkeyup handler for policy number.  Allows only A-Z and 0-9.
function policykeyup(e) {
 var v = e.value.toUpperCase();
 var filteredString="";
 for (var i = 0; i < v.length; ++i) {
  var c = v.charAt(i);
  if ((c >= '0' && c <= '9') ||
     (c >= 'A' && c <= 'Z') ||
     (c == '*') ||
     (c == '-') ||
     (c == '_') ||
     (c == '(') ||
     (c == ')') ||
     (c == '#'))
     {
         filteredString+=c;
     }
 }
 e.value = filteredString;
 return;
}

// Added 06/2009 by BM to make compatible with list_options table and functions - using jquery
$(document).ready(function() {

 <?php 
$insurance = getActiveInsuranceData();
for ($i=0;$i<count($insurance);$i++) { ?>
  $("#form_i<?php echo $i?>subscriber_relationship").change(function() { auto_populate_employer_address<?php echo $i?>(); });
 <?php } ?>

});
function insurance_active(current){
//    var ins_id = 0,pid = 0,type="make_active";
    if($(current).is(":checked")){
    $(current).val(1);
    $("#i"+$(current).attr("data-id")+"inactive_value").val(1);
    }
    else{
    $(current).val(0);
    $("#i"+$(current).attr("data-id")+"inactive_value").val(0);
    }
/*    ins_id = $(current).attr("data-id");
    pid = <?php echo $_SESSION['pid']; ?>;
    $.ajax({
    url : "../../../library/ajax/ajax.php",
    data : {type:type,pid:pid,ins_id:ins_id},
    success : function(data){
    alert("The insurance has been made inactive.");
    }
    });*/
}

</script>
</head>

<body class="body_top">
<form action='demographics_save.php' name='demographics_form' method='post' onsubmit='return validate(this)'>
<input type='hidden' name='mode' value='save' />
<input type='hidden' name='db_id' value="<?php echo $result['id']?>" />
<table cellpadding='0' cellspacing='0' border='0'>
    <tr>
        <td>
            <?php if ($GLOBALS['concurrent_layout']) { ?>
            <a href="demographics.php" onclick="top.restoreSession()">
            <?php } else { ?>
            <a href="patient_summary.php" target="Main" onclick="top.restoreSession()">
            <?php } ?>
            <font class=title><?php xl('Current Patient','e'); ?></font>
            </a>
            &nbsp;&nbsp;
        </td>
        <td>
            <a href="javascript:submitme();" class='css_button'>
                <span><?php xl('Save','e'); ?></span>
            </a>
        </td>
        <td>
            <?php if ($GLOBALS['concurrent_layout']) { ?>
            <a class="css_button" href="demographics.php" onclick="top.restoreSession()">
            <?php } else { ?>
            <a href="patient_summary.php" target="Main" onclick="top.restoreSession()">
            <?php } ?>
            <span><?php xl('Cancel','e'); ?></span>
            </a>
        </td>
    </tr>
</table>
<?php

function end_cell() {
  global $item_count, $cell_count;
  if ($item_count > 0) {
    echo "</td>";
    $item_count = 0;
  }
}

function end_row() {
  global $cell_count, $CPR;
  end_cell();
  if ($cell_count > 0) {
    for (; $cell_count < $CPR; ++$cell_count) echo "<td></td>";
    echo "</tr>\n";
    $cell_count = 0;
  }
}

function end_group() {
  global $last_group;
  if (strlen($last_group) > 0) {
    end_row();
    echo " </table>\n";
    echo "</div>\n";
  }
}

$last_group = '';
$cell_count = 0;
$item_count = 0;
$display_style = 'block';

$group_seq=0; // this gives the DIV blocks unique IDs

?>
<br>
  <div class="section-header">
   <span class="text"><b> <?php xl("Demographics", "e" )?></b></span>
</div>

<div id="DEM" >

    <ul class="tabNav">
       <?php display_layout_tabs('DEM', $result, $result2); ?>
    </ul>

    <div class="tabContainer">
        <?php display_layout_tabs_data_editable('DEM', $result, $result2); ?>
    </div>
</div>
<br>
<div id="DEM" >


<?php
 if (! $GLOBALS['simplified_demographics']) {

      $insurance_headings = array(xl("Primary Insurance Provider"), xl("Secondary Insurance Provider"), xl("Tertiary Insurance provider"));
      $insurance_info = array();
      $insurance_info = getActiveInsuranceData();
      $insurance_info_inactive = getInactiveInsuranceData();
      $insurance_types = array();$inactive = array();
      $types_temp = "";
      foreach($insurance_info as $types){
        $inactive[$types['type']] = (isset($inactive[$types['type']])&&$inactive[$types['type']]==1)?1:$types['inactive'];
        $insurance_types[] = $types['type'];
        $types_temp = $types['type'];
      }

    ?>
    <input type="hidden" name="total_insurances" id="total_insurances" value=<?php echo count($insurance_types);?>>
     <div class="section-header">
         <span class="text"><b><?php xl("Insurance", "e" )?></b></span>
     </div>
    <!--select name="create_insurance" id="create_insurance" onchange="addinsurance(this)">
    <option value="">Select</option>
    <option data-inactive=<?php echo $inactive['primary'];?> value="primary">Primary</option>
    <option data-inactive=<?php echo $inactive['secondary'];?> value="secondary">Secondary</option>
    <option data-inactive=<?php echo $inactive['tertiary'];?> value="tertiary">Tertiary</option>
    </select-->
    <div id="INSURANCE" >
        <ul class="tabNav">
        <?php
        foreach (array("primary","secondary","tertiary","inactive") as $instype) {
        ?><li 
        <?php echo ($instype == 'primary') ? 'class="current"' : '' ?>>
        <a href="/play/javascript-tabbed-navigation/">
        <?php 
              $CapInstype=ucfirst($instype); 
              xl($CapInstype,'e'); 
        ?>
        </a>
        </li>
        <?php
        }
        ?>
        </ul>

    <div class="tabContainer">

    <?php
      for($i=0;$i<3;$i++) {
       $result3 = $insurance_info[$i];
       $inactive_element = $result3['inactive']?1:0;
    ?>

        <div class="tab <?php echo $i == 0 ? 'current': '' ?>" style='height:auto;width:auto'>    <!---display icky, fix to auto-->

        <table border="0">

         <tr>
          <td valign=top width="430">
           <table border="0">

             <tr>
              <td valign='top'>
               <span class='required'><?php echo $insurance_headings[$i]."&nbsp;"?></span>
              </td>
              <td class='required'>:</td>
              <td>
                           <a href="../../practice/ins_search.php" class="iframe medium_modal css_button" onclick="ins_search(<?php echo $i?>)">
                <span><?php echo xl('Search/Add') ?></span>
                    </a>
                <select name="i<?php echo $i?>provider">
                <option value=""><?php xl('Unassigned','e'); ?></option>
                <?php
                 foreach ($insurancei as $iid => $iname) {
                  echo "<option value='" . $iid . "'";
                  if (strtolower($iid) == strtolower($result3{"provider"}))
                   echo " selected";
                  echo ">" . $iname . "</option>\n";
                 }
                ?>
               </select>

              </td>
             </tr>

            <tr>
             <td>
              <span class='required'><?php xl('Plan Name','e'); ?> </span>
             </td>
             <td class='required'>:</td>
             <td>
              <input type='entry' size='20' name='i<?php echo $i?>plan_name' value="<?php echo $result3{"plan_name"} ?>"
               onchange="capitalizeMe(this);" />&nbsp;&nbsp;
             </td>
            </tr>

            <tr>
             <td>
              <span class='required'><?php xl('Effective Date','e'); ?></span>
             </td>
             <td class='required'>:</td>
             <td>
              <input type='entry' size='16' id='i<?php echo $i ?>effective_date' name='i<?php echo $i ?>effective_date'
               value='<?php echo $result3['date'] ?>'
               onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'
               title='yyyy-mm-dd' />
                          <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22' id='img_i<?php echo $i ?>effective_date' border='0' alt='[?]' style='cursor:pointer' title='<?php xl('Click here to choose a date','e'); ?>'>
             </td>
            </tr>

            <tr>
             <td>
              <span class='required'><?php xl('Terminate Date','e'); ?></span>
             </td>
             <td class='required'>:</td>
             <td>
              <input type='entry' size='16' id='i<?php echo $i ?>termination_date' name='i<?php echo $i ?>termination_date'
               value='<?php echo $result3['eDate'] ?>'
               onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)'
               title='yyyy-mm-dd Report use only' />
                          <img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22' id='img_i<?php echo $i ?>termination_date' border='0' alt='[?]' style='cursor:pointer' title='<?php xl('Click here to choose a date','e'); ?>'>
             </td>
            </tr>
            <tr>
             <td><span class=required><?php xl('Policy Number','e'); ?></span></td>
             <td class='required'>:</td>
             <td><input type='entry' size='16' name='i<?php echo $i?>policy_number' value="<?php echo $result3{"policy_number"}?>"
              onkeyup='policykeyup(this)'></td>
            </tr>

            <tr>
             <td><span class=required><?php xl('Group Number','e'); ?></span></td>
             <td class='required'>:</td>
             <td><input type=entry size=16 name=i<?php echo $i?>group_number value="<?php echo $result3{"group_number"}?>" onkeyup='policykeyup(this)'></td>
            </tr>

            <tr<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
             <td class='bold'><?php xl('Subscriber Employer (SE)','e'); ?><br><span style='font-weight:normal'>
              (<?php xl('if unemployed enter Student','e'); ?>,<br><?php xl('PT Student, or leave blank','e'); ?>) </span></td>
              <td class='bold'>:</td>
             <td><input type=entry size=25 name=i<?php echo $i?>subscriber_employer
              value="<?php echo $result3{"subscriber_employer"}?>"
               onchange="capitalizeMe(this);" /></td>
            </tr>

            <tr<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
             <td><span class=bold><?php xl('SE Address','e'); ?></span></td>
             <td class='bold'>:</td>
             <td><input type=entry size=25 name=i<?php echo $i?>subscriber_employer_street
              value="<?php echo $result3{"subscriber_employer_street"}?>"
               onchange="capitalizeMe(this);" /></td>
            </tr>

            <tr<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
             <td colspan="3">
              <table>
               <tr>
                <td><span class=bold><?php xl('SE City','e'); ?>: </span></td>
                <td><input type=entry size=15 name=i<?php echo $i?>subscriber_employer_city
                 value="<?php echo $result3{"subscriber_employer_city"}?>"
                  onchange="capitalizeMe(this);" /></td>
                <td><span class=bold><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('SE State','e') : xl('SE Locality','e') ?>: </span></td>
            <td>
                 <?php
                  // Modified 7/2009 by BM to incorporate data types
              generate_form_field(array('data_type'=>$GLOBALS['state_data_type'],'field_id'=>('i'.$i.'subscriber_employer_state'),'list_id'=>$GLOBALS['state_list'],'fld_length'=>'15','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_employer_state']);
                 ?>
                </td>
               </tr>
               <tr>
                <td><span class=bold><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('SE Zip Code','e') : xl('SE Postal Code','e') ?>: </span></td>
                <td><input type=entry size=15 name=i<?php echo $i?>subscriber_employer_postal_code value="<?php echo $result3{"subscriber_employer_postal_code"}?>"></td>
                <td><span class=bold><?php xl('SE Country','e'); ?>: </span></td>
            <td>
                 <?php
                  // Modified 7/2009 by BM to incorporate data types
              generate_form_field(array('data_type'=>$GLOBALS['country_data_type'],'field_id'=>('i'.$i.'subscriber_employer_country'),'list_id'=>$GLOBALS['country_list'],'fld_length'=>'10','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_employer_country']);
                 ?>
            </td>
               </tr>
              </table>
             </td>
            </tr>

           </table>
          </td>

          <td valign=top>
        <table border="0">
            <tr>
                <td><span class=required><?php xl('Relationship','e'); ?></span></td>
                <td class=required>:</td>
                <td colspan=3><?php
                    // Modified 6/2009 by BM to use list_options and function
                    generate_form_field(array('data_type'=>1,'field_id'=>('i'.$i.'subscriber_relationship'),'list_id'=>'sub_relation','empty_title'=>' '), $result3['subscriber_relationship']);
                    ?>

                <a href="javascript:popUp('browse.php?browsenum=<?php echo $i?>')" class=text>(<?php xl('Browse','e'); ?>)</a></td>
                <td></td><td></td><td></td><td></td>
            </tr>
                        <tr>
                <td width=120><span class=required><?php xl('Subscriber','e'); ?> </span></td>
                <td class=required>:</td>
                <td colspan=3><input type=entry size=10 name=i<?php echo $i?>subscriber_fname    value="<?php echo $result3{"subscriber_fname"}?>" onchange="capitalizeMe(this);" />
                <input type=entry size=3 name=i<?php echo $i?>subscriber_mname value="<?php echo $result3{"subscriber_mname"}?>" onchange="capitalizeMe(this);" />
                <input type=entry size=10 name=i<?php echo $i?>subscriber_lname value="<?php echo $result3{"subscriber_lname"}?>" onchange="capitalizeMe(this);" /></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td><span class=bold><?php xl('D.O.B.','e'); ?> </span></td>
                <td class=required>:</td>
                <td><input type='entry' size='11' id='i<?php echo $i?>subscriber_DOB' name='i<?php echo $i?>subscriber_DOB' value='<?php echo $result3['subscriber_DOB'] ?>' onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd' <?php echo $inactive_element; ?> /><img src='../../pic/show_calendar.gif' align='absbottom' width='24' height='22' id='img_i<?php echo $i; ?>dob_date' border='0' alt='[?]' style='cursor:pointer' title='<?php xl('Click here to choose a date','e'); ?>'></td>

                <td><span class=bold><?php xl('Sex','e'); ?>: </span></td>
                <td><?php
                    // Modified 6/2009 by BM to use list_options and function
                    generate_form_field(array('data_type'=>1,'field_id'=>('i'.$i.'subscriber_sex'),'list_id'=>'sex'), $result3['subscriber_sex']);
                    ?>
                </td>
                <td></td><td></td> <td></td><td></td>
            </tr>
            <tr>
                <td class=leftborder><span class=bold><?php xl('S.S.','e'); ?> </span></td>
                <td class=required>:</td>
                <td><input type=entry size=11 name=i<?php echo $i?>subscriber_ss value="<?php echo trim($result3{"subscriber_ss"})?>"></td>
            </tr>

            <tr>
                <td><span class=required><?php xl('Subscriber Address','e'); ?> </span></td>
                <td class=required>:</td>
                <td><input type=entry size=20 name=i<?php echo $i?>subscriber_street value="<?php echo $result3{"subscriber_street"}?>" onchange="capitalizeMe(this);" /></td>

                <td><span class=required><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('State','e') : xl('Locality','e') ?>: </span></td>
                <td>
                    <?php
                    // Modified 7/2009 by BM to incorporate data types
                    generate_form_field(array('data_type'=>$GLOBALS['state_data_type'],'field_id'=>('i'.$i.'subscriber_state'),'list_id'=>$GLOBALS['state_list'],'fld_length'=>'15','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_state']);
                ?>
                </td>
            </tr>
            <tr>
                <td class=leftborder><span class=required><?php xl('City','e'); ?></span></td>
                <td class=required>:</td>
                <td><input type=entry size=11 name=i<?php echo $i?>subscriber_city value="<?php echo $result3{"subscriber_city"}?>" onchange="capitalizeMe(this);" /></td><td class=leftborder><span class='required'<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>><?php xl('Country','e'); ?>: </span></td><td>
                    <?php
                    // Modified 7/2009 by BM to incorporate data types
                    generate_form_field(array('data_type'=>$GLOBALS['country_data_type'],'field_id'=>('i'.$i.'subscriber_country'),'list_id'=>$GLOBALS['country_list'],'fld_length'=>'10','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_country']);
                    ?>
                </td>
</tr>
            <tr>
                <td><span class=required><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('Zip Code','e') : xl('Postal Code','e') ?> </span></td><td class=required>:</td><td><input type=entry size=10 name=i<?php echo $i?>subscriber_postal_code value="<?php echo $result3{"subscriber_postal_code"}?>"></td>

                <td colspan=2>
                </td><td></td>
            </tr>
            <tr>
                <td><span class=bold><?php xl('Subscriber Phone','e'); ?></span></td>
                <td class=required>:</td>
                <td><input type='text' size='20' name='i<?php echo $i?>subscriber_phone' value='<?php echo $result3["subscriber_phone"] ?>' onkeyup='phonekeyup(this,mypcc)' /></td>
                <td colspan=2><span class=bold><?php xl('CoPay','e'); ?>: <input type=text size="6" name=i<?php echo $i?>copay value="<?php echo $result3{"copay"}?>"></span></td>
                <td colspan=2>
                </td><td></td><td></td>
            </tr>
            <tr>
                <td colspan=0><span class='required'><?php xl('Accept Assignment','e'); ?></span></td>
                <td class=required>:</td>
                <td colspan=2>
                    <select name=i<?php echo $i?>accept_assignment>
                        <option value="TRUE" <?php if (strtoupper($result3{"accept_assignment"}) == "TRUE") echo "selected"?>><?php xl('YES','e'); ?></option>
                        <option value="FALSE" <?php if (strtoupper($result3{"accept_assignment"}) == "FALSE") echo "selected"?>><?php xl('NO','e'); ?></option>
                    </select>
                </td>
                <td></td><td></td>
                <td colspan=2>
                </td><td></td>
            </tr>
      <tr>
        <td><span class='bold'><?php xl('Secondary Medicare Type','e'); ?></span></td>
        <td class='bold'>:</td>
        <td colspan='6'>
          <select name=i<?php echo $i?>policy_type>
<?php
  foreach ($policy_types AS $key => $value) {
    echo "            <option value ='$key'";
    if ($key == $result3['policy_type']) echo " selected";
    echo ">" . htmlspecialchars($value) . "</option>\n";
  }
?>
          </select>
        </td>
      </tr>
        </table>

          </td>
         </tr>
        </table>

    <input type="hidden" name="i<?php echo $i;?>insurance_type" id="i<?php echo $i;?>insurance_type" value="<?php echo $result3["type"];?>" >

        </div>

    <?php } //end insurer for loop ?>

    <div class="tab" style='height:auto;width:auto'>
    <?php for($i=0;$i<count($insurance_info_inactive);$i++) {?>
    <table border="0">
      <!--tr-->
    <?php //echo "<br> Insurance type :  ".ucfirst($insurance_info_inactive[$i]['type']);?>
    <br><?php echo "<br> This" . " " . ucfirst($insurance_info_inactive[$i]['type']) . " " . "Insurance Was Inactivated on" . " " . substr($insurance_info_inactive[$i]['inactive_time'],0,10)."<br>" ;?>
    <span><?php echo "This Insurance is Valid for Claims Dated From". " " . substr($insurance_info_inactive[$i]['date'],0,10) . " To " . substr($insurance_info_inactive[$i]['eDate'],0,10)  ;?></span>     
 <td valign=top width="600">
    <table border="0">
    <tr>
            <td valign='top'>
              <span class='bold'><?php echo xlt('Insurance Provider');?></span>
            </td>
        <td class='bold'>:</td>
            <td>
                   <?php
                   foreach ($insurancei as $iid => $iname) {
                          if (strtolower($iid) == strtolower($insurance_info_inactive[$i]{"provider"})) 
              echo $iname;
                          }
                   ?>
           </td>
        </tr>
    <tr>
           <td>
             <span class='bold'><?php echo xlt('Plan Name'); ?> </span>
           </td>
       <td class='bold'>:</td>
           <td>
              <?php echo $insurance_info_inactive[$i]{"plan_name"} ?>
           </td>
        </tr>
    <tr>
        <td>
          <span class='bold'><?php echo xlt('Effective Date'); ?> </span>
        </td>
        <td class='bold'>:</td>
        <td>
          <?php echo $insurance_info_inactive[$i]{"date"};?>
        </td>
    </tr>
    <tr>
       <td>
        <span class="bold"><?php echo xlt('Policy Number');?></span>
       </td>
       <td class='bold'>:</td>
       <td>
        <?php echo $insurance_info_inactive[$i]["policy_number"];?>
       </td>
    </tr>
    <tr>
           <td><span class=bold><?php echo xlt('Group Number'); ?></span></td>
       <td class='bold'>:</td>
           <td><?php echo $insurance_info_inactive[$i]{"group_number"};?></td>
        </tr>
    <tr>
           <td class='bold'><?php echo xlt('Subscriber Employer (SE)'); ?><br>
           <span style='font-weight:normal'>(<?php echo xlt('if unemployed enter Student'); ?>,<br><?php echo xlt('PT Student, or leave blank'); ?>) </span>
       <td class='bold'>:</td>
       </td>
           <td>
           <?php echo $insurance_info_inactive[$i]{"subscriber_employer"}?>
        </td>
        </tr>
    <tr>
           <td><span class=bold><?php echo xlt('SE Address'); ?></span></td>
       <td class='bold'>:</td>
           <td><?php echo $insurance_info_inactive[$i]{"subscriber_employer_street"};?></td>
        </tr>
    <tr>
       <td><span class=bold><?php echo xlt('SE City'); ?>: </span></td>
           <td class='bold'>:</td>
       <td><?php echo $insurance_info_inactive[$i]{"subscriber_employer_city"};?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('SE State','e') : xl('SE Locality','e') ?>: </span></td>
       <td class='bold'>:</td>
       <td><?php echo  $insurance_info_inactive[$i]['subscriber_employer_state'];?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo xlt('SE Country'); ?>: </span></td>
       <td class='bold'>:</td>
       <td><?php echo $insurance_info_inactive[$i]['subscriber_employer_country']; ?></td>
    </tr>
        <!--/tr-->
    <!--tr-->
          <tr>
           <td>
           <span class=bold><?php echo xlt('Relationship'); ?></span>
           </td> 
          <td class='bold'>:</td>
         
       <td>
       <?php echo $insurance_info_inactive[$i]['subscriber_relationship'];?>    
       </td>
    <!--/tr-->
     </tr>
    <tr>
         <td width=120><span class=bold><?php echo xlt('Subscriber'); ?> </span></td>
           <td class='bold'>:</td>
       <td><?php echo htmlspecialchars($insurance_info_inactive[$i]['subscriber_fname'] . ' ' . $insurance_info_inactive[$i]['subscriber_mname'] . ' ' . $insurance_info_inactive[$i]['subscriber_lname'],ENT_NOQUOTES); ?></td>
    </tr>
    </table>
    </td>
       <td valign="top">
         <table border="0">
    <tr>
       <td><span class=bold><?php echo xlt('D.O.B.'); ?></span></td>
       <td>:</td>
       <td><?php echo $insurance_info_inactive[$i]['subscriber_DOB']; ?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo xlt('Sex'); ?>: </span></td>
       <td>:</td>
       <td><?php echo $insurance_info_inactive[$i]['subscriber_sex'];?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo xlt('S.S.'); ?> </span></td>
       <td>:</td>
       <td><?php echo trim($insurance_info_inactive[$i]{"subscriber_ss"});?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo xlt('Subscriber Address'); ?> </span></td>
       <td class='bold'>:</td>
       <td><?php echo $insurance_info_inactive[$i]{"subscriber_street"};?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('State','e') : xl('Locality','e') ?>: </span></td>
       <td class='bold'>:</td>
       <td><?php echo $insurance_info_inactive[$i]['subscriber_state'];?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo xlt('City'); ?></span></td>
       <td class='bold'>:</td>
       <td><?php echo $insurance_info_inactive[$i]{"subscriber_city"};?></td>
    </tr>
    <tr>
       <td><span class='bold'<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>><?php echo xlt('Country'); ?>: </span></td>
       <td class='bold'>:</td>
       <td><?php echo $insurance_info_inactive[$i]['subscriber_country'];?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('Zip Code') : xl('Postal Code') ?> </span></td>
       <td class='bold'>:</td>
       <td><?php echo $insurance_info_inactive[$i]["subscriber_postal_code"];?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo xlt('Subscriber Phone'); ?></span></td>
       <td>:</td>
       <td><?php echo $insurance_info_inactive[$i]["subscriber_phone"]; ?></td>
    </tr>
    <tr>
       <td><span class=bold><?php echo xlt('CoPay'); ?>: </span></td>
       <td>:</td>
       <td><?php echo $insurance_info_inactive[$i]{"copay"};?></td>
    </tr>
    <tr>
       <td><span class='bold'><?php echo xlt('Accept Assignment'); ?></span></td>
       <td class='bold'>:</td>
       <td><?php if (strtoupper($insurance_info_inactive[$i]{"accept_assignment"}) == "TRUE") {?>
        <?php echo xlt('YES'); }else{
        echo xlt('NO');
        }?>
       </td>
    </tr>
    <tr>
           <td><span class='bold'><?php echo xlt('Secondary Medicare Type'); ?></span></td>
       <td>:</td>
       <td><?php
  foreach ($policy_types AS $key => $value) {
    if ($key == $result3['policy_type']) echo htmlspecialchars($value);
  }
?></td>
    </tr>
    </table>
    </td>
    <!--/tr-->
    </table>
    
    <?php echo "<span width:auto>".str_repeat("_",135)."</span>"; } ?>

    </div>
</div>
</div>

<?php } // end of "if not simplified_demographics" ?>
</div></div>

</form>

<br>

<script language="JavaScript">

 // fix inconsistently formatted phone numbers from the database
 var f = document.forms[0];
 if (f.form_phone_contact) phonekeyup(f.form_phone_contact,mypcc);
 if (f.form_phone_home   ) phonekeyup(f.form_phone_home   ,mypcc);
 if (f.form_phone_biz    ) phonekeyup(f.form_phone_biz    ,mypcc);
 if (f.form_phone_cell   ) phonekeyup(f.form_phone_cell   ,mypcc);

<?php if (! $GLOBALS['simplified_demographics']) { 
$insurance_count = getActiveInsuranceData();
for($i=0;$i<count($insurance_count);$i++){
?>
 phonekeyup(f.i<?php echo $i;?>subscriber_phone,mypcc);
 //phonekeyup(f.i2subscriber_phone,mypcc);
 //phonekeyup(f.i3subscriber_phone,mypcc);
<?php }} ?>

<?php if ($GLOBALS['concurrent_layout'] && $set_pid) { ?>
 parent.left_nav.setPatient(<?php echo "'" . addslashes($result['fname']) . " " . addslashes($result['lname']) . "',$pid,'" . addslashes($result['pubpid']) . "','', ' " . xl('DOB') . ": " . oeFormatShortDate($result['DOB_YMD']) . " " . xl('Age') . ": " . getPatientAgeDisplay($result['DOB_YMD']) . "'"; ?>);
 parent.left_nav.setRadio(window.name, 'dem');
<?php } ?>

<?php echo $date_init; ?>
<?php if (! $GLOBALS['simplified_demographics']) { 
$insurance_count = getActiveInsuranceData();
for ($i=0; $i<count($insurance_count); $i++){ ?>
 Calendar.setup({inputField:"i<?php echo $i;?>effective_date", ifFormat:"%Y-%m-%d", button:"img_i<?php echo $i;?>effective_date"});
 Calendar.setup({inputField:"i<?php echo $i;?>termination_date", ifFormat:"%Y-%m-%d", button:"img_i<?php echo $i;?>termination_date"});
 Calendar.setup({inputField:"i<?php echo $i;?>subscriber_DOB", ifFormat:"%Y-%m-%d", button:"img_i<?php echo $i;?>dob_date"});
 <?php } } ?>
</script>

<!-- include support for the list-add selectbox feature -->
<?php include $GLOBALS['fileroot']."/library/options_listadd.inc"; ?>

</body>
<script language='JavaScript'>
    // Array of skip conditions for the checkSkipConditions() function.
    var skipArray = [
        <?php echo $condition_str; ?>
    ];
    checkSkipConditions();
    $("input").change(function() {
        checkSkipConditions();
    });
    $("select").change(function() {
        checkSkipConditions();
    });
</script>


</html>
