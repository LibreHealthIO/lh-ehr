<?php
  // Copyright (C) 2009 Rod Roark <rod@sunsetsystems.com>
  //
  // This program is free software; you can redistribute it and/or
  // modify it under the terms of the GNU General Public License
  // as published by the Free Software Foundation; either version 2
  // of the License, or (at your option) any later version.
  
  require_once("../globals.php");
  require_once("$srcdir/sql.inc");
  
  // Validation for non-unique external patient identifier.
  $alertmsg = '';
  if (!empty($_POST["form_pubpid"])) {
    $form_pubpid = trim($_POST["form_pubpid"]);
    $result = sqlQuery("SELECT count(*) AS count FROM patient_data WHERE " .
      "pubpid = '" . formDataCore($form_pubpid) . "'");
    if ($result['count']) {
      // Error, not unique.
      $alertmsg = xl('Warning: Patient ID is not unique!');
    }
  }
  
  require_once("$srcdir/pid.inc");
  require_once("$srcdir/patient.inc");
  require_once("$srcdir/options.inc.php");
  
  // here, we lock the patient data table while we find the most recent max PID
  // other interfaces can still read the data during this lock, however
  // sqlStatement("lock tables patient_data read");

  $result = sqlQuery("SELECT MAX(id)+2 AS pid FROM patient_data");
  
  $newpid = 1;
  
  if ($result['pid'] > 1) $newpid = $result['pid'];
  
  setpid($newpid);
  
  if (empty($pid)) {
    // sqlStatement("unlock tables");
    die("Internal error: setpid($newpid) failed!");
  }
  $pid = substr(time(), 0,5).$newpid;
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
        //size check
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
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $destination_directory.$pid.".".$imageFileType)) {
        $picture_url = $pid.".".$imageFileType;
    }
    else {
      //may be failed due to directory permissions.
    }
  }
  else {
    //don't upload checks failed.
  }

  // Update patient_data and employer_data:
  //
  $newdata = array();
  $newdata['patient_data' ] = array();
  $newdata['employer_data'] = array();
  $fres = sqlStatement("SELECT * FROM layout_options " .
    "WHERE form_id = 'DEM' AND uor > 0 AND field_id != '' " .
    "OR field_id = 'pid' " .
    "ORDER BY group_name, seq");
  while ($frow = sqlFetchArray($fres)) {
    $data_type = $frow['data_type'];
    $field_id  = $frow['field_id'];
    // $value     = '';
    $colname   = $field_id;
    $tblname   = 'patient_data';
    if (strpos($field_id, 'em_') === 0) {
      $colname = substr($field_id, 3);
      $tblname = 'employer_data';
    }
  
    $value = get_layout_form_value($frow);
  
    if ($field_id == 'pubpid' && empty($value)) $value = $pid;
    $newdata[$tblname][$colname] = $value;
  }
  $newdata['patient_data']['picture_url'] = $picture_url;
  updatePatientData($pid, $newdata['patient_data'], true);
  updateEmployerData($pid, $newdata['employer_data'], true);
  
  $i1dob = fixDate(formData("i1subscriber_DOB"));
  $i1date = fixDate(formData("i1effective_date"));
  
  // sqlStatement("unlock tables");
  // end table lock
  
  newHistoryData($pid);
  newInsuranceData(
    $pid,
    "primary",
    formData("i1provider"),
    formData("i1policy_number"),
    formData("i1group_number"),
    formData("i1plan_name"),
    formData("i1subscriber_lname"),
    formData("i1subscriber_mname"),
    formData("i1subscriber_fname"),
    formData("form_i1subscriber_relationship"),
    formData("i1subscriber_ss"),
    $i1dob,
    formData("i1subscriber_street"),
    formData("i1subscriber_postal_code"),
    formData("i1subscriber_city"),
    formData("form_i1subscriber_state"),
    formData("form_i1subscriber_country"),
    formData("i1subscriber_phone"),
    formData("i1subscriber_employer"),
    formData("i1subscriber_employer_street"),
    formData("i1subscriber_employer_city"),
    formData("i1subscriber_employer_postal_code"),
    formData("form_i1subscriber_employer_state"),
    formData("form_i1subscriber_employer_country"),
    formData('i1copay'),
    formData('form_i1subscriber_sex'),
    $i1date,
    formData('i1accept_assignment')
  );
  
  
  $i2dob = fixDate(formData("i2subscriber_DOB"));
  $i2date = fixDate(formData("i2effective_date"));
  
  
  
  newInsuranceData(
    $pid,
    "secondary",
    formData("i2provider"),
    formData("i2policy_number"),
    formData("i2group_number"),
    formData("i2plan_name"),
    formData("i2subscriber_lname"),
    formData("i2subscriber_mname"),
    formData("i2subscriber_fname"),
    formData("form_i2subscriber_relationship"),
    formData("i2subscriber_ss"),
    $i2dob,
    formData("i2subscriber_street"),
    formData("i2subscriber_postal_code"),
    formData("i2subscriber_city"),
    formData("form_i2subscriber_state"),
    formData("form_i2subscriber_country"),
    formData("i2subscriber_phone"),
    formData("i2subscriber_employer"),
    formData("i2subscriber_employer_street"),
    formData("i2subscriber_employer_city"),
    formData("i2subscriber_employer_postal_code"),
    formData("form_i2subscriber_employer_state"),
    formData("form_i2subscriber_employer_country"),
    formData('i2copay'),
    formData('form_i2subscriber_sex'),
    $i2date,
    formData('i2accept_assignment')
  );
  
  $i3dob  = fixDate(formData("i3subscriber_DOB"));
  $i3date = fixDate(formData("i3effective_date"));
  
  newInsuranceData(
    $pid,
    "tertiary",
    formData("i3provider"),
    formData("i3policy_number"),
    formData("i3group_number"),
    formData("i3plan_name"),
    formData("i3subscriber_lname"),
    formData("i3subscriber_mname"),
    formData("i3subscriber_fname"),
    formData("form_i3subscriber_relationship"),
    formData("i3subscriber_ss"),
    $i3dob,
    formData("i3subscriber_street"),
    formData("i3subscriber_postal_code"),
    formData("i3subscriber_city"),
    formData("form_i3subscriber_state"),
    formData("form_i3subscriber_country"),
    formData("i3subscriber_phone"),
    formData("i3subscriber_employer"),
    formData("i3subscriber_employer_street"),
    formData("i3subscriber_employer_city"),
    formData("i3subscriber_employer_postal_code"),
    formData("form_i3subscriber_employer_state"),
    formData("form_i3subscriber_employer_country"),
    formData('i3copay'),
    formData('form_i3subscriber_sex'),
    $i3date,
    formData('i3accept_assignment')
  );
  ?>
<html>
  <body>
    <script language="Javascript">
      <?php
        if ($alertmsg) {
          echo "alert('$alertmsg');\n";
        }
          echo "window.location='$rootdir/patient_file/summary/demographics.php?" .
            "set_pid=$pid&is_new=1';\n";
        ?>
    </script>
  </body>
</html>