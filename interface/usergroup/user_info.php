<?php
  include_once("../globals.php");
  include_once("$srcdir/sql.inc");
  include_once("$srcdir/auth.inc");
  include_once("$srcdir/headers.inc.php");
  $uid = $_SESSION['authId'];
  $query= sqlQ("SELECT picture_url FROM users WHERE id=$uid");
  $r = sqlFetchArray($query);
  ?>
<html>
  <head>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <script src="checkpwd_validation.js" type="text/javascript"></script>
    <?php
      call_required_libraries(['bootstrap', 'jquery-min-1-9-1']);
      ?>
    <script language='JavaScript'>
      //Validating password and display message if password field is empty - starts
      var webroot='<?php echo $webroot?>';
      function update_password()
      {
          top.restoreSession();
          // Not Empty
          // Strong if required
          // Matches
      
          $.post("user_info_ajax.php",
              {
                  curPass:    $("input[name='curPass']").val(),
                  newPass:    $("input[name='newPass']").val(),
                  newPass2:   $("input[name='newPass2']").val(),
              },
              function(data)
              {
                  $("input[type='password']").val("");
                  $("#display_msg").html(data);
              }
      
          );
          return false;
      }
      
    </script>
  </head>
  <body class="body_top">
<table>
<tr>
  <Td colspan="1">
    <span class="title"><?php echo xlt('Pass Phrase Change'); ?></span>
  </Td>  
</tr>
</table>    
    <?php
      $ip=$_SERVER['REMOTE_ADDR'];
      $res = sqlStatement("select fname,lname,username from users where id=?",array($_SESSION["authId"])); 
      $row = sqlFetchArray($res);
            $iter=$row;
      ?>
    <div id="display_msg"></div>
      <Table>
  <form method="POST" enctype="multipart/form-data">
  <tr>
  <Td colspan="1">
  <h5>
      <?php
      if ($r['picture_url']) {
        $picture_url = $r['picture_url'];
        echo "<img src='../../profile_pictures/$picture_url' height='64px' width='64px' style='border-radius: 40px;'>";
      }
      ?>

  </Td>
  <TD style="padding-left: 1em; text-transform: capitalize;"><span class="text btn "><?php echo $iter["username"]; ?></span></td>
  <td class="right" style="padding-left: 1em;">
    <input type="file" name="profile_picture" id="files" onchange="form.submit()" class="hidden" />
      <label for="files" class="btn cp-positive" id="file_input_button">
      <?php
      if ($r['picture_url']) {
        echo xlt('Edit Profile Picture');
      }
      else {
         echo xlt('Add Profile Picture');
      }
      ?>
      </label>
      </form>
      </h5>
  </td>
  </tr>
  </Table>
    <br>
    <FORM NAME="user_form" METHOD="POST" ACTION="user_info.php"
      onsubmit="top.restoreSession()">
      <input type=hidden name=secure_pwd value="<?php echo $GLOBALS['secure_password']; ?>">
      <TABLE style="border-collapse: separate; border-spacing: 5px">
        <TR>
          <TD><span class=text><?php xl('Full Name','e'); ?>: </span></TD>
          <TD><span class=text><?php echo htmlspecialchars($iter["fname"] . " " . $iter["lname"], ENT_NOQUOTES); ?></span></td>
        </TR>
        <TR>
          <TD><span class=text><?php xl('Username','e'); ?>: </span></TD>
          <TD><span class=text><?php echo $iter["username"]; ?></span></td>
        </TR>
        <TR>
          <TD><span class=text><?php xl('Current Pass Phrase','e'); ?>: </span></TD>
          <TD><input type=password name=curPass size=20 value="" autocomplete='off' class="form-control form-rounded"></td>
        </TR>
        <TR>
          <TD><span class=text><?php xl('New Pass Phrase','e'); ?>: </span></TD>
          <TD><input type=password name=newPass size=20 value="" autocomplete='off' class="form-control form-rounded"></td>
        </TR>
        <TR>
          <TD><span class=text><?php xl('Repeat New Pass Phrase','e'); ?>: </span></TD>
          <TD><input type=password name=newPass2 size=20 value="" autocomplete='off' class="form-control form-rounded"></td>
        </TR>
      </TABLE>
      <br>&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="Submit" VALUE=<?php echo xla('Save Changes'); ?> onClick="return update_password()" class="cp-submit">
    </FORM>
    <br><br>
  </BODY>
</HTML>
<?php
  //  da39a3ee5e6b4b0d3255bfef95601890afd80709 == blank

if (isset($_FILES)) {
  //images will be saved with their uid
  $uid = $_SESSION['authId'];
    //MAKE THE UPLOAD DIRECTORY IF IT DOESN'T EXIST
  if (realpath("../../profile_pictures/")) {
      
  }
  else {
    mkdir("../../profile_pictures/", 0755);
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
  $destination_directory = "../../profile_pictures/";
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





if ($picture_url) {
  //show success message and update the value in db and also refresh the page.
  if (sqlQ("UPDATE users SET picture_url='$picture_url' WHERE id='$uid'")) {
        echo "<script>
    if (confirm('profile picture has been set successfully')) {
      window.location = 'user_info.php';
    }
    else {
       window.location = 'user_info.php';
    }
    </script>";
  }
  else {
            echo "<script>
    if (confirm('Error in setting the profile picture')) {
      window.location = 'user_info.php';
    }
    else {
       window.location = 'user_info.php';
    }
    </script>";
  }
}  
else {
  //show failure message.
}


  ?>
  
