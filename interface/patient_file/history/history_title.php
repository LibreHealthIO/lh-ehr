<?php

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

include_once("../../globals.php");
include_once("$srcdir/patient.inc");
require_once("$srcdir/classes/Pharmacy.class.php");
?>

<html>
<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

</head>
<body class="body_title">

<?php
 $result = getPatientData($pid, "fname,lname,pid,phone_home,pharmacy_id,DOB,DATE_FORMAT(DOB,'%Y%m%d') as DOB_YMD");
 $provider_results = sqlQuery("select * from users where username=?", array($_SESSION{"authUser"}) );
 $age = getPatientAge($result["DOB_YMD"]);

 $info = 'ID: ' . $result['pid'];
 if ($result['DOB']) $info .= ', ' . xl('DOB') . ': ' . $result['DOB'] . ', ' . xl('Age') . ': ' . $age;
 if ($result['phone_home']) $info .= ', ' . xl('Home') . ': ' . $result['phone_home'];

 if ($result['pharmacy_id']) {
  $pharmacy = new Pharmacy($result['pharmacy_id']);
  if ($pharmacy->get_phone()) $info .= ', ' . xl('Pharm') . ': ' . $pharmacy->get_phone();
 }

 //escape variables for output (to prevent xss attacks)
 $patient_esc = text($result{"fname"} . " " . $result{"lname"});
 $info_esc = text($info);
 $provider_esc = text($provider_results{"fname"}.' '.$provider_results{"lname"});
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
 <tr>
  <td style="width:45%; vertical-align:middle; white-space: nowrap">
   <span class="title_bar_top"><?php echo $patient_esc; ?></span>
   <span style="font-size:0.7em;">(<?php echo $info_esc; ?>)</span>
  </td>
  <td style="width:35%; vertical-align:middle; white-space: nowrap; text-align:center">
   <span class="title_bar_top"><?php echo xlt('Logged in as'); ?>: <?php echo $provider_esc; ?></span>
  </td>
  <td style="width:20%; vertical-align:middle; white-space: nowrap; text-align:right">
   &nbsp;
  </td>
 </tr>
</table>

</body>
</html>
