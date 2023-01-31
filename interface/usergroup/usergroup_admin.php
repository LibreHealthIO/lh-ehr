<?php
/**
 * This script Assign acl 'Emergency login'.
 *
 * Copyright (C) 2015 Roberto Vasquez <robertogagliotta@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @author  Roberto Vasquez <robertogagliotta@gmail.com>
 * @link    http://librehealth.io
 */

require_once("../globals.php");
require_once("../../library/acl.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/auth.inc");
require_once("$srcdir/formdata.inc.php");
require_once($GLOBALS['srcdir'] . "/classes/postmaster.php");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/calendar.inc");

$alertmsg = '';
$bg_msg = '';
$set_active_msg=0;
$show_message=0;


/* Sending a mail to the admin when the breakglass user is activated only if $GLOBALS['Emergency_Login_email'] is set to 1 */
$bg_count=count($access_group);
$mail_id = explode(".",$SMTP_HOST);
for($i=0;$i<$bg_count;$i++){
if(($_GET['access_group'][$i] == "Emergency Login") && ($_GET['active'] == 'on') && ($_GET['pre_active'] == 0)){
  if(($_GET['get_admin_id'] == 1) && ($_GET['admin_id'] != "")){
    $res = sqlStatement("select username from users where id= ? ", array($_GET["id"]));
    $row = sqlFetchArray($res);
    $uname=$row['username'];
    $mail = new MyMailer();
        $mail->SetLanguage("en",$GLOBALS['fileroot'] . "/library/" );
        $mail->From = "admin@".$mail_id[1].".".$mail_id[2];
        $mail->FromName = "Administrator LibreEHR";
        $text_body  = "Hello Security Admin,\n\n The Emergency Login user ".$uname.
                                                " was activated at ".date('l jS \of F Y h:i:s A')." \n\nThanks,\nAdmin LibreEHR.";
        $mail->Body = $text_body;
        $mail->Subject = "Emergency Login User Activated";
        $mail->AddAddress($_GET['admin_id']);
        $mail->Send();
}
}
}
/* To refresh and save variables in mail frame */
if (isset($_POST["privatemode"]) && $_POST["privatemode"] =="user_admin") {
    if ($_POST["mode"] == "update") {
      if (isset($_POST["username"])) {
        // $tqvar = addslashes(trim($_POST["username"]));
        $tqvar = trim(formData('username','P'));
        $user_data = sqlFetchArray(sqlStatement("select * from users where id= ? ", array($_POST["id"])));
        sqlStatement("update users set username='$tqvar' where id= ? ", array($_POST["id"]));
        sqlStatement("update groups set user='$tqvar' where user= ?", array($user_data["username"]));
        //echo "query was: " ."update groups set user='$tqvar' where user='". $user_data["username"]  ."'" ;
      }
      if ($_POST["taxid"]) {
        $tqvar = formData('taxid','P');
        sqlStatement("update users set federaltaxid='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["state_license_number"]) {
        $tqvar = formData('state_license_number','P');
        sqlStatement("update users set state_license_number='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["drugid"]) {
        $tqvar = formData('drugid','P');
        sqlStatement("update users set federaldrugid='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["upin"]) {
        $tqvar = formData('upin','P');
        sqlStatement("update users set upin='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["npi"]) {
        $tqvar = formData('npi','P');
        sqlStatement("update users set npi='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["taxonomy"]) {
        $tqvar = formData('taxonomy','P');
        sqlStatement("update users set taxonomy = '$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["lname"]) {
        $tqvar = formData('lname','P');
        sqlStatement("update users set lname='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["job"]) {
        $tqvar = formData('job','P');
        sqlStatement("update users set specialty='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["mname"]) {
              $tqvar = formData('mname','P');
              sqlStatement("update users set mname='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["fullscreen_page"]) {
        $tqvar = formData('fullscreen_page', 'P');
        sqlStatement('update users set fullscreen_page="'.$tqvar.'" where id=?', array($_POST["id"]));
      }
      if ($_POST["fullscreen_enable"]) {
        sqlStatement("update users set fullscreen_enable=1 where id=?", array($_POST["id"]));
      } else {
        sqlStatement("update users set fullscreen_enable=0 where id=?", array($_POST["id"]));
      }

      if ($_POST["menu_role"]) {
        $tqvar = formData('menu_role', 'P');
        if ($tqvar != "") {
          sqlStatement("update users set menu_role='$tqvar' where id=?", array($_POST["id"]));
        } else {
          sqlStatement("update users set menu_role='Sample Role' where id=?", array($_POST["id"]));
        }
      }

      if ($_POST["facility_id"]) {
              $tqvar = formData('facility_id','P');
              sqlStatement("update users set facility_id = '$tqvar' where id = ? ", array($_POST["id"]));
              //(CHEMED) Update facility name when changing the id
              sqlStatement("UPDATE users, facility SET users.facility = facility.name WHERE facility.id = '$tqvar' AND users.id = {$_POST["id"]}");
              //END (CHEMED)
      }
      if ($GLOBALS['restrict_user_facility'] && $_POST["schedule_facility"]) {
          sqlStatement("delete from users_facility
            where tablename='users'
            and table_id= ?
            and facility_id not in (" . implode(",", $_POST['schedule_facility']) . ")", array($_POST["id"]));
          foreach($_POST["schedule_facility"] as $tqvar) {
          sqlStatement("replace into users_facility set
                facility_id = '$tqvar',
                tablename='users',
                table_id = {$_POST["id"]}");
        }
      }
      if ($_POST["fname"]) {
              $tqvar = formData('fname','P');
              sqlStatement("update users set fname='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_POST["suffix"]) {
              $tqvar = formData('suffix','P');
              sqlStatement("update users set suffix='$tqvar' where id= ? ", array($_POST["id"]));
      }
      if ($_FILES["profile_picture"]) {
        $res = sqlStatement("SELECT username, picture_url FROM users where id= ? ", $_POST["id"]);
        $row = sqlFetchArray($res);
        if ($_POST["username"]) {
          $uid = formData('username','P').time();
        } else {
          $uid = $row['username'].time();
        }
        if (realpath($GLOBALS['OE_SITES_BASE']."/".$_SESSION['site_id']."/profile_pictures/")) {

        }
        else {
          mkdir($GLOBALS['OE_SITES_BASE']."/".$_SESSION['site_id']."/profile_pictures/", 0755);
        }
        $bool = 0;
        $target_file =  basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $verify_image = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if($verify_image) {
          $mime = $verify_image["mime"];
          $mime_types = array('image/png',
                                  'image/jpeg',
                                  'image/gif',
                                  'image/bmp',
                                  'image/vnd.microsoft.icon');
          //mime check with all image formats.
          if (in_array($mime, $mime_types)) {
                $bool = 1;
              //if mime type matches, then do a size check
              //size check for 20mb
              if ($_FILES["profile_picture"]["size"] > 20971520) {
                $bool = 0;
              }
              else {
                $bool = 1;
              }
          }
          else {
            $bool = 0;
          }
    
        }
        else {
              $bool = 0;
        }
        $picture_url = "";
        //begin file uploading
        $destination_directory = $GLOBALS['OE_SITES_BASE']."/".$_SESSION['site_id']."/profile_pictures/";
        if ($bool) {
          if (file_exists($destination_directory.$row['picture_url'])){
            unlink($destination_directory.$row['picture_url']);
          }
          if (file_exists($destination_directory.$uid.".".$imageFileType)) {
            unlink($destination_directory.$uid.".".$imageFileType);
          }
          if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $destination_directory.$uid.".".$imageFileType)) {
              $picture_url = $uid.".".$imageFileType;
          }
          else {
            //may be failed due to directory permissions.
          }
        }
        else {
          //don't upload checks failed.
        }
        sqlStatement("update users set picture_url = '$picture_url' where id = ?", array($_POST["id"]));
      }
      //(CHEMED) Calendar UI preference
      if ($_POST["cal_ui"]) {
              $tqvar = formData('cal_ui','P');
              sqlStatement("update users set cal_ui = '$tqvar' where id = ? ", array($_POST["id"]));

              // added by bgm to set this session variable if the current user has edited
          //   their own settings
          if ($_SESSION['authId'] == $_POST["id"]) {
            $_SESSION['cal_ui'] = $tqvar;
          }
      }
      //END (CHEMED) Calendar UI preference

      if (isset($_POST['default_warehouse'])) {
        sqlStatement("UPDATE users SET default_warehouse = '" .
          formData('default_warehouse','P') .
          "' WHERE id = '" . formData('id','P') . "'");
      }

      if (isset($_POST['irnpool'])) {
        sqlStatement("UPDATE users SET irnpool = '" .
          formData('irnpool','P') .
          "' WHERE id = '" . formData('id','P') . "'");
      }

     if ($_POST["adminPass"] && $_POST["clearPass"]) {
        require_once("$srcdir/authentication/password_change.php");
        $clearAdminPass=$_POST['adminPass'];
        $clearUserPass=$_POST['clearPass'];
        $password_err_msg="";
        $success=update_password($_SESSION['authId'],$_POST['id'],$clearAdminPass,$clearUserPass,$password_err_msg);
        if(!$success)
        {
            error_log($password_err_msg);
            $alertmsg.=$password_err_msg;
        }
     }

      $tqvar  = $_POST["authorized"] ? 1 : 0;
      $actvar = $_POST["active"]     ? 1 : 0;
      $calvar = $_POST["calendar"]   ? 1 : 0;

      sqlStatement("UPDATE users SET authorized = $tqvar, active = $actvar, " .
        "calendar = $calvar, see_auth = ? WHERE " .
        "id = ? ", array($_POST['see_auth'], $_POST["id"]));
      //Display message when Emergency Login user was activated
      $bg_count=count($_POST['access_group']);
      for($i=0;$i<$bg_count;$i++){
        if(($_POST['access_group'][$i] == "Emergency Login") && ($_POST['pre_active'] == 0) && ($actvar == 1)){
         $show_message = 1;
        }
      }
      if(($_POST['access_group'])){
    for($i=0;$i<$bg_count;$i++){
        if(($_POST['access_group'][$i] == "Emergency Login") && ($_POST['user_type']) == "" && ($_POST['check_acl'] == 1) && ($_POST['active']) != ""){
         $set_active_msg=1;
        }
      }
    }
      if ($_POST["comments"]) {
        $tqvar = formData('comments','P');
        sqlStatement("update users set info = '$tqvar' where id = ? ", array($_POST["id"]));
      }
    $erxrole = formData('erxrole','P');
    sqlStatement("update users set newcrop_user_role = '$erxrole' where id = ? ", array($_POST["id"]));

      if ($_POST["physician_type"]) {
        $physician_type = formData('physician_type');
        sqlStatement("update users set physician_type = '$physician_type' where id = ? ", array($_POST["id"]));
      }

      if (isset($phpgacl_location) && acl_check('admin', 'acl')) {
        // Set the access control group of user
        $user_data = sqlFetchArray(sqlStatement("select username from users where id= ?", array($_POST["id"])));
        set_user_aro($_POST['access_group'], $user_data["username"],
          formData('fname','P'), formData('mname','P'), formData('lname','P'));
      }

        do_action( 'usergroup_admin_save', $_POST );

        refreshCalendar(); //after "Edit User" process is complete

    }
}

/* To refresh and save variables in mail frame  - Arb*/
if (isset($_POST["mode"])) {
  if ($_POST["mode"] == "new_user") {
    if ($_POST["authorized"] != "1") {
      $_POST["authorized"] = 0;
    }
    // $_POST["info"] = addslashes($_POST["info"]);

    $calvar = $_POST["calendar"] ? 1 : 0;
    $fullscreen_enable = $_POST["fullscreen_enable"] ? 1 : 0;
    $menuRole = $_POST["menu_role"] ?: "Sample Role";
    $res = sqlStatement("select distinct username from users where username != ''");
    $doit = true;
    while ($row = sqlFetchArray($res)) {
      if ($doit == true && $row['username'] == trim(formData('rumple'))) {
        $doit = false;
      }
    }

    if ($doit == true) {
    require_once("$srcdir/authentication/password_change.php");

    //if password expiration option is enabled,  calculate the expiration date of the password
    if($GLOBALS['password_expiration_days'] != 0){
    $exp_days = $GLOBALS['password_expiration_days'];
    $exp_date = date('Y-m-d', strtotime("+$exp_days days"));
    } else {
        $exp_date = date('Y-m-d');
    }

if (isset($_FILES)) {
  //images will be saved with their name
  $uid =  trim(formData('rumple')).time();
    //MAKE THE UPLOAD DIRECTORY IF IT DOESN'T EXIST
  if (realpath($GLOBALS['OE_SITES_BASE']."/".$_SESSION['site_id']."/profile_pictures")) {

  }
  else {
    mkdir($GLOBALS['OE_SITES_BASE']."/".$_SESSION['site_id']."/profile_pictures", 0755);
  }
  //for profile picture upload
  //mime check done.
  //size check done.
  //extension check done.
  //if any validation needed be added, please add it below.
  $bool = 0;
  $target_file =  basename($_FILES["profile_picture"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $verify_image = getimagesize($_FILES["profile_picture"]["tmp_name"]);
  if($verify_image) {
    $mime = $verify_image["mime"];
    $mime_types = array('image/png',
                            'image/jpeg',
                            'image/gif',
                            'image/bmp',
                            'image/vnd.microsoft.icon');
    //mime check with all image formats.
    if (in_array($mime, $mime_types)) {
          $bool = 1;
        //if mime type matches, then do a size check
        //size check for 20mb
        if ($_FILES["profile_picture"]["size"] > 20971520) {
          $bool = 0;
        }
        else {
          $bool = 1;
        }
    }
    else {
      $bool = 0;
    }

  }
  else {
        $bool = 0;
  }
  $picture_url = "";
  //begin file uploading
  $destination_directory = $GLOBALS['OE_SITES_BASE']."/".$_SESSION['site_id']."/profile_pictures/";
  if ($bool) {
    if (file_exists($destination_directory.$uid.".".$imageFileType)) {
      unlink($destination_directory.$uid.".".$imageFileType);
    }
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $destination_directory.$uid.".".$imageFileType)) {
        $picture_url = $uid.".".$imageFileType;
    }
    else {
      //may be failed due to directory permissions.
    }
  }
  else {
    //don't upload checks failed.
  }
}

    $insertUserSQL=
            "insert into users set " .
            "username = '"         . trim(formData('rumple'       )) .
            "', password = '"      . 'NoLongerUsed'                  .
            "', fname = '"         . trim(formData('fname'        )) .
            "', mname = '"         . trim(formData('mname'        )) .
            "', lname = '"         . trim(formData('lname'        )) .
            "', suffix = '"        . trim(formData('suffix'       )) .
            "', federaltaxid = '"  . trim(formData('federaltaxid' )) .
            "', state_license_number = '"  . trim(formData('state_license_number' )) .
            "', newcrop_user_role = '"  . trim(formData('erxrole' )) .
            "', physician_type = '"  . trim(formData('physician_type' )) .
            "', authorized = '"    . trim(formData('authorized'   )) .
            "', info = '"          . trim(formData('info'         )) .
            "', federaldrugid = '" . trim(formData('federaldrugid')) .
            "', upin = '"          . trim(formData('upin'         )) .
            "', npi  = '"          . trim(formData('npi'          )) .
            "', taxonomy = '"      . trim(formData('taxonomy'     )) .
            "', facility_id = '"   . trim(formData('facility_id'  )) .
            "', fullscreen_page = '". trim(formData('fullscreen_page')) .
            "', fullscreen_enable = '". $fullscreen_enable .
            "', menu_role = '". $menuRole .
            "', specialty = '"     . trim(formData('specialty'    )) .
            "', see_auth = '"      . trim(formData('see_auth'     )) .
            "', cal_ui = '"        . trim(formData('cal_ui'       )) .
            "', default_warehouse = '" . trim(formData('default_warehouse')) .
            "', irnpool = '"       . trim(formData('irnpool'      )) .
            "', calendar = '"      . $calvar                         .
            "', picture_url = '"      . $picture_url                         .
            "', pwd_expiration_date = '" . trim("$exp_date") .
            "'";

    $clearAdminPass=$_POST['adminPass'];
    $clearUserPass=$_POST['stiltskin'];
    $password_err_msg="";
    $prov_id="";
    $success = update_password($_SESSION['authId'], 0, $clearAdminPass, $clearUserPass,
      $password_err_msg, true, $insertUserSQL, trim(formData('rumple')), $prov_id);
    error_log($password_err_msg);
    $alertmsg .=$password_err_msg;
    if($success)
    {
      //set the facility name from the selected facility_id
      sqlStatement("UPDATE users, facility SET users.facility = facility.name WHERE facility.id = '" . trim(formData('facility_id')) . "' AND users.username = '" . trim(formData('rumple')) . "'");

      sqlStatement("insert into `groups` set name = '" . trim(formData('groupname')) .
        "', user = '" . trim(formData('rumple')) . "'");

      if (isset($phpgacl_location) && acl_check('admin', 'acl') && trim(formData('rumple'))) {
        // Set the access control group of user
        set_user_aro($_POST['access_group'], trim(formData('rumple')),
          trim(formData('fname')), trim(formData('mname')), trim(formData('lname')));
      }

    }



    } else {
      $alertmsg .= xl('User','','',' ') . trim(formData('rumple')) . xl('already exists.','',' ');
    }
   if($_POST['access_group']){
     $bg_count=count($_POST['access_group']);
         for($i=0;$i<$bg_count;$i++){
          if($_POST['access_group'][$i] == "Emergency Login"){
             $set_active_msg=1;
           }
    }
      }

      refreshCalendar(); //after "Add User" process is complete
  }
  else if ($_POST["mode"] == "new_group") {
    $res = sqlStatement("select distinct name, user from `groups`");
    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
      $result[$iter] = $row;
    $doit = 1;
    foreach ($result as $iter) {
      if ($doit == 1 && $iter{"name"} == trim(formData('groupname')) && $iter{"user"} == trim(formData('rumple')))
        $doit--;
    }
    if ($doit == 1) {
      sqlStatement("insert into `groups` set name = '" . trim(formData('groupname')) .
        "', user = '" . trim(formData('rumple')) . "'");
    } else {
      $alertmsg .= "User " . trim(formData('rumple')) .
        " is already a member of group " . trim(formData('groupname')) . ". ";
    }
  }
}

if (isset($_GET["mode"])) {

  /*******************************************************************
  // This is the code to delete a user.  Note that the link which invokes
  // this is commented out.  Somebody must have figured it was too dangerous.
  //
  if ($_GET["mode"] == "delete") {
    $res = sqlStatement("select distinct username, id from users where id = ?", array($_GET["id"]));

    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
      $result[$iter] = $row;

    // TBD: Before deleting the user, we should check all tables that
    // reference users to make sure this user is not referenced!

    foreach($result as $iter) {
      sqlStatement("delete from `groups` where user = '" . $iter{"username"} . "'");
    }
    sqlStatement("delete from users where iid = ?", array($_GET["id"]))
  }
  *******************************************************************/

  if ($_GET["mode"] == "delete_group") {
    $res = sqlStatement("select distinct user from `groups` where id = ?", array($_GET["id"]));
    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
      $result[$iter] = $row;
    foreach($result as $iter)
      $un = $iter{"user"};
    $res = sqlStatement("select name, user from `groups` where user = '$un' " .
      "and id != ?", array($_GET["id"]));

    // Remove the user only if they are also in some other group.  I.e. every
    // user must be a member of at least one group.
    if (sqlFetchArray($res) != FALSE) {
      sqlStatement("delete from `groups` where id = ?", array($_GET["id"]));
    } else {
      $alertmsg .= "You must add this user to some other group before " .
        "removing them from this group. ";
    }
  }
}

$form_inactive = empty($_REQUEST['form_inactive']) ? false : true;

?>
<html>
<head>
    <?php call_required_libraries(array("jquery-min-3-1-1","bootstrap","font-awesome", "iziModalToast")); ?>

    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $(".addUser").click(function () {
                $("#addUser-iframe").iziModal('open');
            });

            $(".editUser").click(function () {
                var link = $(this).attr("data-text");
                var title = $(this).children("span").text();
                initIziLink(link , title);
            });

            function initIziLink(link, title) {
                $("#editUser-iframe").iziModal({
                    title: '<b style="color: white">'+title+'</b>',
                    subtitle: 'Edit a new user with administrative roles',
                    headerColor: '#88A0B9',
                    closeOnEscape: true,
                    fullscreen:true,
                    overlayClose: false,
                    closeButton: true,
                    theme: 'light',  // light
                    iframe: true,
                    width:900,
                    focusInput: true,
                    padding:5,
                    iframeHeight: 400,
                    iframeURL:'user_admin.php?id='+link,
                    onClosed:function () {
                        location.reload();
                    }
                });

                setTimeout(function () {
                    call_izi();
                },200);
            }

            function call_izi() {
                $("#editUser-iframe").iziModal('open');
            }

            $("#addUser-iframe").iziModal({
                title: 'Add a new user',
                subtitle: 'Add a new user with administrative roles',
                headerColor: '#88A0B9',
                closeOnEscape: true,
                fullscreen:true,
                overlayClose: false,
                closeButton: true,
                theme: 'light',  // light
                iframe: true,
                width:900,
                focusInput: true,
                padding:5,
                iframeHeight: 400,
                iframeURL: "usergroup_admin_add.php",
                onClosed:function () {
                    location.reload();
                }
            });

        });

    </script>
<script language="JavaScript">

function authorized_clicked() {
 var f = document.forms[0];
 f.calendar.disabled = !f.authorized.checked;
 f.calendar.checked  =  f.authorized.checked;
}

</script>

</head>
<body class="body_top">

<!-- iframes to initialize izi -->
<div id="addUser-iframe"></div>
<div id="editUser-iframe"></div>

<div>
    <div>
        <table>
            <tr >
                <td><b><?php echo xlt('User / Groups'); ?></b>&nbsp;&nbsp;</td>
                <td><a href="#" class="css_button cp-positive addUser"><span><?php echo xlt('Add User'); ?></span></a>
                </td>
                <td><a href="facility_user.php" class="css_button cp-misc"><span><?php echo xlt('View Facility Specific User Information'); ?></span></a>
                </td>
            </tr>
        </table>
    </div>

<form name='userlist' method='post' action='usergroup_admin.php' onsubmit='return top.restoreSession()'><br>
    <input type='checkbox' name='form_inactive' value='1' onclick='submit()' <?php if ($form_inactive) echo 'checked '; ?>/>
    <span class='text'> <?php echo xlt('Include inactive users'); ?> </span>
</form>
<?php
if($set_active_msg == 1){
echo "<font class='alert'>".xl('Emergency Login ACL is chosen. The user is still in active state, please de-activate the user and activate the same when required during emergency situations. Visit Administration->Users for activation or de-activation.')."</font><br>";
}
if ($show_message == 1){
 echo "<font class='alert'>".xl('The following Emergency Login User is activated:')." "."<b>".$_GET['fname']."</b>"."</font><br>";
 echo "<font class='alert'>".xl('Emergency Login activation email will be circulated only if following settings in the interface/globals.php file are configured:')." \$GLOBALS['Emergency_Login_email'], \$GLOBALS['Emergency_Login_email_id']</font>";
}

?>
<div class="table-responsive">
<table class="table table-hover">
    <tr height="22">
        <th><b><?php echo xlt('Username'); ?></b></th>
        <th><b><?php echo xlt('Real Name'); ?></b></th>
        <th><b><span><?php echo xlt('Additional Info'); ?></span></b></th>
        <th><b><?php echo xlt('Authorized'); ?>?</b></th>

        <?php
$query = "SELECT * FROM users WHERE username != '' ";
if (!$form_inactive) $query .= "AND active = '1' ";
$query .= "ORDER BY username";
$res = sqlStatement($query);
for ($iter = 0;$row = sqlFetchArray($res);$iter++)
  $result4[$iter] = $row;
foreach ($result4 as $iter) {
  if ($iter{"authorized"}) {
    $iter{"authorized"} = xl('yes');
  } else {
      $iter{"authorized"} = "";
  }
  print "<tr>
        <td><b><a data-text=".$iter{"id"}." href='#' class='editUser' onclick='top.restoreSession()'><span>" . $iter{"username"} . "</span></a></b>" ."&nbsp;</td>
        <td><span class='text'>" . attr($iter{"fname"}) . ' ' . attr($iter{"lname"}) ."</span>&nbsp;</td>
        <td><span class='text'>" . attr($iter{"info"}) . "</span>&nbsp;</td>
        <td align='left'><span class='text'>" .$iter{"authorized"} . "</span>&nbsp;</td>";
  print "<td><!--<a href='usergroup_admin.php?mode=delete&id=" . $iter{"id"} .
    "' class='link_submit'>[Delete]</a>--></td>";
  print "</tr>\n";
}
?>
    </table>
    </div>
<?php
if (empty($GLOBALS['disable_non_default_groups'])) {
  $res = sqlStatement("select * from `groups` order by name");
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
</div>
<script language="JavaScript">
    <?php
    if ($alertmsg = trim($alertmsg)) {
        echo "var alertMsg ="."'".$alertmsg.";'\n";;
        echo "
      iziToast.warning({
            title: 'Warning -',
            message: alertMsg,
            position: 'bottomRight',
            icon: 'fa fa-exclamation-triangle'
        });
    ";
    }
    ?>
</script>

</body>
</html>
