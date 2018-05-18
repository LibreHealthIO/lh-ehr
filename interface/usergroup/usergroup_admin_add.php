<?php
/*
 *  usergroup_admin_add.php for the adding of the user information
 *
 *  This program is used to add the users
 *
 * Copyright (C) 2016-2017 
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
 * 
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
 

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("../../library/acl.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/erx_javascript.inc.php");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/role.php");

$alertmsg = '';

?>
<html>
<head>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<?php call_required_libraries(array("jquery-min-3-1-1", "fancybox", "common")); ?>

<script src="checkpwd_validation.js" type="text/javascript"></script>

<script language="JavaScript">
function trimAll(sString)
{
    while (sString.substring(0,1) == ' ')
    {
        sString = sString.substring(1, sString.length);
    }
    while (sString.substring(sString.length-1, sString.length) == ' ')
    {
        sString = sString.substring(0,sString.length-1);
    }
    return sString;
} 

function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#prof_img')
                    .attr('src', e.target.result)
                    .width(64)
                    .height(64);
                $('#prof_img').css("display", "block"); 
                $('#file_input_button').text("Edit Profile Picture");
            };

            reader.readAsDataURL(input.files[0]);
        }
}

function submitform() {
    if (document.forms[0].rumple.value.length>0 && document.forms[0].stiltskin.value.length>0 && document.getElementById('fname').value.length >0 && document.getElementById('lname').value.length >0) {
       top.restoreSession();

       //Checking if secure password is enabled or disabled.
       //If it is enabled and entered password is a weak password, alert the user to enter strong password.
       if(document.new_user.secure_pwd.value == 1){
          var password = trim(document.new_user.stiltskin.value);
          if(password != "") {
             var pwdresult = passwordvalidate(password);
             if(pwdresult == 0){
                alert("<?php echo xl('The password must be at least eight characters, and should'); echo '\n'; echo xl('contain at least three of the four following items:'); echo '\n'; echo xl('A number'); echo '\n'; echo xl('A lowercase letter'); echo '\n'; echo xl('An uppercase letter'); echo '\n'; echo xl('A special character');echo '('; echo xl('not a letter or number'); echo ').'; echo '\n'; echo xl('For example:'); echo ' healthCare@09'; ?>");
                return false;
             }
          }
       } //secure_pwd if ends here

       <?php if($GLOBALS['erx_enable']){ ?>
       alertMsg='';
       f=document.forms[0];
       for(i=0;i<f.length;i++){
          if(f[i].type=='text' && f[i].value)
          {
             if(f[i].name == 'rumple')
             {
                alertMsg += checkLength(f[i].name,f[i].value,35);
                alertMsg += checkUsername(f[i].name,f[i].value);
             }
             else if(f[i].name == 'fname' || f[i].name == 'mname' || f[i].name == 'lname')
             {
                alertMsg += checkLength(f[i].name,f[i].value,35);
                alertMsg += checkUsername(f[i].name,f[i].value);
             }
             else if(f[i].name == 'federaltaxid')
             {
                alertMsg += checkLength(f[i].name,f[i].value,10);
                alertMsg += checkFederalEin(f[i].name,f[i].value);
             }
             else if(f[i].name == 'state_license_number')
             {
                alertMsg += checkLength(f[i].name,f[i].value,10);
                alertMsg += checkStateLicenseNumber(f[i].name,f[i].value);
             }
             else if(f[i].name == 'npi')
             {
                alertMsg += checkLength(f[i].name,f[i].value,35);
                alertMsg += checkTaxNpiDea(f[i].name,f[i].value);
             }
             else if(f[i].name == 'federaldrugid')
             {
                alertMsg += checkLength(f[i].name,f[i].value,30);
                alertMsg += checkAlphaNumeric(f[i].name,f[i].value);
             }
             
          }
       }
       if(alertMsg)
       {
          alert(alertMsg);
          return false;
       }
       <?php } // End erx_enable only include block?>

        document.forms[0].submit();
        parent.$.fn.fancybox.close(); 

    } else {
       if (document.forms[0].rumple.value.length<=0)
       {
          document.forms[0].rumple.style.backgroundColor="red";
          alert("<?php xl('Required field missing: Please enter the User Name','e');?>");
          document.forms[0].rumple.focus();
          return false;
       }
       if (document.forms[0].stiltskin.value.length<=0)
       {
          document.forms[0].stiltskin.style.backgroundColor="red";
          alert("<?php echo xl('Please enter the pass phrase'); ?>");
          document.forms[0].stiltskin.focus();
          return false;
       }
       if(trimAll(document.getElementById('fname').value) == ""){
          document.getElementById('fname').style.backgroundColor="red";
          alert("<?php echo xl('Required field missing: Please enter the First name');?>");
          document.getElementById('fname').focus();
          return false;
       }
       if(trimAll(document.getElementById('lname').value) == ""){
          document.getElementById('lname').style.backgroundColor="red";
          alert("<?php echo xl('Required field missing: Please enter the Last name');?>");
          document.getElementById('lname').focus();
          return false;
       }
    }
}
function authorized_clicked() {
     var f = document.forms[0];
     f.calendar.disabled = !f.authorized.checked;
     f.calendar.checked  =  f.authorized.checked;
}

</script>
<style type="text/css">
  .physician_type_class{
    width: 120px !important;
  }
</style>
</head>
<body class="body_top">
<table><tr><td>
<span class="title"><?php echo xlt('Add User'); ?></span>&nbsp;</td>

<td>
<a class="css_button cp-submit" name='form_save' id='form_save' href='#' onclick="return submitform()">
    <span><?php echo xlt('Save');?></span></a>
<a class="css_button large_button cp-negative" id='cancel' href='#'>
    <span class='css_button_span large_button_span'><?php echo xlt('Cancel');?></span>
</a>
</td></tr></table>
<br><br>
<form name='new_user' method='post'  target="_parent" action="usergroup_admin.php" enctype="multipart/form-data" 
 onsubmit='return top.restoreSession()'>
<table border=0>
<tr>
<td><img id="prof_img" style="display: none; border-radius: 40px; border: 8px solid #888;"></td>
<td style="padding-left: 350px;">
<input type="file" name="profile_picture" id="files"  class="hidden" style="display: none;" onchange="readURL(this);" />
<label for="files" class="css_button cp-positive" id="file_input_button"><?php echo xlt('Add Profile Picture'); ?>
</label>
</td>
</tr>
<tr><td valign=top>
<input type='hidden' name='mode' value='new_user'>
<input type='hidden' name='secure_pwd' value="<?php echo $GLOBALS['secure_password']; ?>">
<span class="bold">&nbsp;</span>
</td><td>
<table border=0 cellpadding=0 cellspacing=0 style="width:600px;">
<tr>
<td style="width:150px;"><span class="text"><?php echo xlt('Username'); ?>: </span></td><td  style="width:220px;"><input type=entry name=rumple style="width:120px;"> <span class="mandatory">&nbsp;*</span></td>
<td style="width:150px;"><span class="text"><?php echo xlt('Pass Phrase'); ?>: </span></td><td style="width:250px;"><input type="entry" style="width:120px;" name=stiltskin><span class="mandatory">&nbsp;*</span></td>
</tr>
<tr>

    <td style="width:150px;"></td><td  style="width:220px;"></span></td>
    <TD style="width:200px;"><span class=text><?php echo xlt('Your Pass Phrase'); ?>: </span></TD>
    <TD class='text' style="width:280px;"><input type='password' name=adminPass style="width:120px;"  value="" autocomplete='off'><font class="mandatory">*</font></TD>

</tr>
<tr>
<td><span class="text"<?php if ($GLOBALS['disable_non_default_groups']) echo " style='display:none'"; ?>><?php echo xlt('Groupname'); ?>: </span></td>
<td>
<select name=groupname<?php if ($GLOBALS['disable_non_default_groups']) echo " style='display:none'"; ?>>
<?php
$res = sqlStatement("select distinct name from groups");
$result2 = array();
for ($iter = 0;$row = sqlFetchArray($res);$iter++)
  $result2[$iter] = $row;
foreach ($result2 as $iter) {
  print "<option value='".$iter{"name"}."'>" . $iter{"name"} . "</option>\n";
}
?>
</select></td>
<td><span class="text"><?php echo xlt('Provider'); ?>: </span></td><td>
 <input type='checkbox' name='authorized' value='1' onclick='authorized_clicked()' />
 &nbsp;&nbsp;<span class='text'><?php echo xlt('Calendar'); ?>:
 <input type='checkbox' name='calendar' disabled />
</td>
</tr>
<tr>
<td><span class="text"><?php echo xlt('First Name'); ?>: </span></td><td><input type=entry name='fname' id='fname' style="width:120px;"><span class="mandatory">&nbsp;*</span></td>
<td><span class="text"><?php echo xlt('Middle Name'); ?>: </span></td><td><input type=entry name='mname' style="width:120px;"></td>
</tr>
<tr>
<td><span class="text"><?php echo xlt('Last Name'); ?>: </span></td><td><input type=entry name='lname' id='lname' style="width:120px;"><span class="mandatory">&nbsp;*</span></td>
<td><span class="text"><?php echo xlt('Default Facility'); ?>: </span></td><td><select style="width:120px;" name=facility_id>
<?php
$fres = sqlStatement("select * from facility where service_location != 0 order by name");
if ($fres) {
  for ($iter = 0;$frow = sqlFetchArray($fres);$iter++)
    $result[$iter] = $frow;
  foreach($result as $iter) {
?>
<option value="<?php echo $iter{'id'};?>"><?php echo $iter{'name'};?></option>
<?php
  }
}
?>
</select></td>
</tr>
<tr>
<td><span class=text><?php echo xlt('Suffix'); ?>: </span></td><td><input type=entry name=suffix style="width:150px;"></td>
<td><span class="text"><?php echo xlt('Federal Tax ID'); ?>: </span></td><td><input type=entry name='federaltaxid' style="width:120px;"></td>

</tr>
<tr>
<td><span class="text"><?php echo xlt('DEA Number'); ?>: </span></td><td><input type=entry name='federaldrugid' style="width:120px;"></td>


<tr>
<td><span class="text"><?php echo xlt('NPI'); ?>: </span></td><td><input type="entry" name="npi" style="width:120px;"></td>
<td><span class="text"><?php echo xlt('Job Description'); ?>: </span></td><td><input type="entry" name="specialty" style="width:120px;"></td>
</tr>

<tr>
    <td>
        <span class="text"><?php echo xlt('Provider Type'); ?>: </span>
    </td>
    <td>
        <?php echo generate_select_list("physician_type", "physician_type", '','',xl('Select Type'),'physician_type_class','','',''); ?>
    </td>
    <td class='text'><?php echo xlt('See Authorizations'); ?>: </td>
<td><select name="see_auth" style="width:120px;">
<?php
 foreach (array(1 => xl('None'), 2 => xl('Only Mine'), 3 => xl('All')) as $key => $value)
 {
  echo " <option value='$key'";
  echo ">$value</option>\n";
 }
?>
</select></td>

</tr>

<!-- (CHEMED) Calendar UI preference -->
<tr>
<td><span class="text"><?php echo xlt('Taxonomy'); ?>: </span></td>
<td><input type="entry" name="taxonomy" style="width:120px;" value="207Q00000X"></td>
<td><span class="text"><?php echo xlt('Calendar UI'); ?>: </span></td><td><select name="cal_ui" style="width:120px;">
<?php
 foreach (array(3 => xl('Outlook'), 1 => xl('Original'), 2 => xl('Fancy')) as $key => $value)
 {
  echo " <option value='$key'>$value</option>\n";
 }
?>
</select></td>
</tr>
<!-- END (CHEMED) Calendar UI preference -->

<tr>
<td><span class="text"><?php echo xlt('State License Number'); ?>: </span></td>
<td><input type="entry" name="state_license_number" style="width:120px;"></td>
<td class='text'><?php echo xlt('NewCrop eRX Role'); ?>:</td>
<td>
  <?php echo generate_select_list("erxrole", "newcrop_erx_role", '','','--Select Role--','','','',array('style'=>'width:120px')); ?>  
</td>
</tr>

<?php if ($GLOBALS['inhouse_pharmacy']) { ?>
<tr>
 <td class="text"><?php echo xlt('Default Warehouse'); ?>: </td>
 <td class='text'>
<?php
echo generate_select_list('default_warehouse', 'warehouse',
  '', '');
?>
 </td>
 <td class="text"><?php echo xlt('Invoice Refno Pool'); ?>: </td>
 <td class='text'>
<?php
echo generate_select_list('irnpool', 'irnpool', '',
  xl('Invoice reference number pool, if used'));
?>
 </td>
</tr>
<?php } ?>

<?php
 // List the access control groups if phpgacl installed
 if (isset($phpgacl_location) && acl_check('admin', 'acl')) {
?>
  <tr>
  <td class='text'><?php echo xlt('Access Control'); ?>:</td>
  <td><select name="access_group[]" multiple style="width:120px;">
  <?php
   $list_acl_groups = acl_get_group_title_list();
   $default_acl_group = 'Administrators';
   foreach ($list_acl_groups as $value) {
    if ($default_acl_group == $value) {
     // Modified 6-2009 by BM - Translate group name if applicable
     echo " <option value='$value' selected>" . xl_gacl_group($value) . "</option>\n";
    }
    else {
     // Modified 6-2009 by BM - Translate group name if applicable
     echo " <option value='$value'>" . xl_gacl_group($value) . "</option>\n";
    }
   }
  ?>
  </select></td>
  
  <td><span class="text"><?php echo xlt('Additional Info'); ?>: </span></td>
  <td><textarea name=info style="width:120px;" cols=27 rows=4 wrap=auto></textarea></td>
  </tr>
  <tr>
  <td><span class="text"><?php echo xlt('Menu role'); ?>:</span></td>
  <td>
  <select style="width:120px;" name="menu_role" id="menu_role">
      <?php
         $role = new Role();
         $role_list = $role->getRoleList();
         foreach($role_list as $role_title) {
           ?>  <option value="<?php echo $role_title; ?>"><?php echo xlt($role_title); ?></option>
          <?php
         }
      ?>
      </select>
  </td>
  <td><span class="text"> <?php echo xlt('Full screen page'); ?>:</span></td>
  <td>
      <select style="width:120px;" name="fullscreen_page" id="fullscreen_page">
                <option value="Calendar|/interface/main/main_info.php">Calendar</option>
                <option value="Flow Board|/interface/patient_tracker/patient_tracker.php">Flow Board</option>
      </select>

  
  </td>
  </tr>
  <tr>
  <td>
      <span class="text"> <?php echo xlt('Full screen page enabled'); ?>: </span>
  </td>
  <td>
      <input type="checkbox" name="fullscreen_enable"/>
  </td>
  <?php do_action( 'usergroup_admin_add' ); ?>

  </tr>

  <tr height="25"><td colspan="4">&nbsp;</td></tr>
<?php
 }
?>

</table>

<br>

<input type="hidden" name="newauthPass">
</form>
</td>

</tr>

<tr<?php if ($GLOBALS['disable_non_default_groups']) echo " style='display:none'"; ?>>

<td valign=top>
<form name='new_group' method='post' action="usergroup_admin.php"
 onsubmit='return top.restoreSession()'>
<br>
<input type=hidden name=mode value=new_group>
<span class="bold"><?php echo xlt('New Group'); ?>:</span>
</td><td>
<span class="text"><?php echo xlt('Groupname'); ?>: </span><input type=entry name=groupname size=10>
&nbsp;&nbsp;&nbsp;
<span class="text"><?php echo xlt('Initial User'); ?>: </span>
<select name=rumple>
<?php
$res = sqlStatement("select distinct username from users where username != ''");
for ($iter = 0;$row = sqlFetchArray($res);$iter++)
  $result[$iter] = $row;
foreach ($result as $iter) {
  print "<option value='".$iter{"username"}."'>" . $iter{"username"} . "</option>\n";
}
?>
</select>
&nbsp;&nbsp;&nbsp;
<input type="submit" value=<?php echo xlt('Save'); ?>>
</form>
</td>

</tr>

<tr <?php if ($GLOBALS['disable_non_default_groups']) echo " style='display:none'"; ?>>

<td valign=top>
<form name='new_group' method='post' action="usergroup_admin.php"
 onsubmit='return top.restoreSession()'>
<input type=hidden name=mode value=new_group>
<span class="bold"><?php echo xlt('Add User To Group'); ?>:</span>
</td><td>
<span class="text">
<?php echo xlt('User'); ?>
: </span>
<select name=rumple>
<?php
$res = sqlStatement("select distinct username from users where username != ''");
for ($iter = 0;$row = sqlFetchArray($res);$iter++)
  $result3[$iter] = $row;
foreach ($result3 as $iter) {
  print "<option value='".$iter{"username"}."'>" . $iter{"username"} . "</option>\n";
}
?>
</select>
&nbsp;&nbsp;&nbsp;
<span class="text"><?php echo xlt('Groupname'); ?>: </span>
<select name=groupname>
<?php
$res = sqlStatement("select distinct name from groups");
$result2 = array();
for ($iter = 0;$row = sqlFetchArray($res);$iter++)
  $result2[$iter] = $row;
foreach ($result2 as $iter) {
  print "<option value='".$iter{"name"}."'>" . $iter{"name"} . "</option>\n";
}
?>
</select>
&nbsp;&nbsp;&nbsp;
<input type="submit" value=<?php echo xlt('Add User To Group'); ?>>
</form>
</td>
</tr>

</table>

<?php
if (empty($GLOBALS['disable_non_default_groups'])) {
  $res = sqlStatement("select * from groups order by name");
  for ($iter = 0;$row = sqlFetchArray($res);$iter++)
    $result5[$iter] = $row;

  foreach ($result5 as $iter) {
    $grouplist{$iter{"name"}} .= $iter{"user"} .
      "(<a class='link_submit' href='usergroup_admin.php?mode=delete_group&id=" .
      $iter{"id"} . "' onclick='top.restoreSession()'>Remove</a>), ";
  }

  foreach ($grouplist as $groupname => $list) {
    print "<span class='bold'>" . $groupname . "</span><br>\n<span class='text'>" .
      substr($list,0,strlen($list)-2) . "</span><br>\n";
  }
}
?>

<script language="JavaScript">
<?php
  if ($alertmsg = trim($alertmsg)) {
    echo "alert('$alertmsg');\n";
  }
?>
$(document).ready(function(){
    $("#cancel").click(function() {
          parent.$.fn.fancybox.close();
     });
  /*
     $("#role_name").on('change', function(e) {
       
        $.ajax({
          "url": '../../library/ajax/get_fullscreen_pages.php',
          "method": "POST",
          "data" : {
             "role_name": $("#role_name").val()
          },
          success: function(data) {
            obj = JSON.parse(data);
            $("#fullscreen_page").empty();
            obj.forEach(function(item) {
              option = document.createElement('option');
              option.text = item.label;
              option.value = item.id;
              $("#fullscreen_page").append(option);
              
            });

          },
          error: function(err) {
            console.log(err);
          }
          });

       }); */ 
});
</script>
<table>

</table>

</body>
</html>
