<?php
//by Art Eaton
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
foreach ($_POST as $k => $var) {
$_POST[$k] = mysql_escape_string($var);
echo "$var\n";
}
if ($encounter == "")
$encounter = date("Ymd");

if ($_GET["mode"] == "new"){
	
$newid = formSubmit("form_button_ros", $_POST, $_GET["id"], $userauthorized);
//save the cfars form using the forms api to redirect back to encounter
addForm($encounter, "ROS", $newid, "button_ros", $pid, $userauthorized);
}elseif ($_GET["mode"] == "update") {
sqlInsert("update form_button_ros set pid = {$_SESSION["pid"]},groupname='".$_SESSION["authProvider"]."',user='".$_SESSION["authUser"]."',authorized=$userauthorized,activity=1, date = NOW(),
GEN1 ='".$_POST["GEN1"]."',
GEN2 ='".$_POST["GEN2"]."',
GEN3 ='".$_POST["GEN3"]."',
GEN4 ='".$_POST["GEN4"]."',
GEN5 ='".$_POST["GEN5"]."',
GEN6 =' ".$_POST["GEN6"]."',
  





GEN_notes ='".$_POST["GEN_notes"]."',
EYES1 ='".$_POST["EYES1"]."',
EYES2 ='".$_POST["EYES2"]."',
EYES3 ='".$_POST["EYES3"]."',
EYES4 ='".$_POST["EYES4"]."',
EYES5 ='".$_POST["EYES5"]."',
EYES6 ='".$_POST["EYES6"]."',
EYES7 ='".$_POST["EYES7"]."',
GEN_notes ='".$_POST["GEN_notes"]."',
ENT1  ='".$_POST["ENT1"]."',
ENT2  ='".$_POST["ENT2"]."',
ENT3  ='".$_POST["ENT3"]."',
ENT4  ='".$_POST["ENT4"]."',
ENT5  ='".$_POST["ENT5"]."',
ENT6  ='".$_POST["ENT6"]."',
ENT7  ='".$_POST["ENT7"]."',
ENT8  ='".$_POST["ENT8"]."',
ENT9  ='".$_POST["ENT9"]."',
ENT10 ='".$_POST["ENT10"]."',
ENT11 ='".$_POST["ENT11"]."',
ENT_notes ='".$_POST["ENT_notes"]."',
RESP1 ='".$_POST["RESP1"]."',
RESP2 ='".$_POST["RESP2"]."',
RESP3 ='".$_POST["RESP3"]."',
RESP4 ='".$_POST["RESP4"]."',
RESP5 ='".$_POST["RESP5"]."',
RESP6 ='".$_POST["RESP6"]."',
RESP7 ='".$_POST["RESP7"]."',
RESP_notes ='".$_POST["RESP_notes"]."',
CVS1  ='".$_POST["CVS1"]."',
CVS2  ='".$_POST["CVS2"]."',
CVS3  ='".$_POST["CVS3"]."',
CVS_notes ='".$_POST["CVS_notes"]."',
GI1   ='".$_POST["GI1"]."',
GI2   ='".$_POST["GI2"]."',
GI3   ='".$_POST["GI3"]."',
GI4   ='".$_POST["GI4"]."',
GI5   ='".$_POST["GI5"]."',
GI6   ='".$_POST["GI6"]."',
GI7   ='".$_POST["GI7"]."',
GI8   ='".$_POST["GI8"]."',
GI9   ='".$_POST["GI9"]."',
GI10  ='".$_POST["GI10"]."',
GI11  ='".$_POST["GI11"]."',
GT_notes ='".$_POST["GT_notes"]."',
GU1   ='".$_POST["GU1"]."',
GU2   ='".$_POST["GU2"]."',
GU3   ='".$_POST["GU3"]."',
GU4   ='".$_POST["GU4"]."',
GU5   ='".$_POST["GU5"]."',
GU6   ='".$_POST["GU6"]."',
GU7   ='".$_POST["GU7"]."',
GU8   ='".$_POST["GU8"]."',
GU_notes ='".$_POST["GU_notes"]."',
MSK1  ='".$_POST["MSK1"]."',
MSK2  ='".$_POST["MSK2"]."',
MSK3  ='".$_POST["MSK3"]."',
MSK4  ='".$_POST["MSK4"]."',
MSK5  ='".$_POST["MSK5"]."',
MSK6  ='".$_POST["MSK6"]."',
MSK7  ='".$_POST["MSK7"]."',
MSK_notes ='".$_POST["MSK_notes"]."',
NEURO1 ='".$_POST["NEURO1"]."',
NEURO2 ='".$_POST["NEURO2"]."',
NEURO3 ='".$_POST["NEURO3"]."',
NEURO4 ='".$_POST["NEURO4"]."',
NEURO5 ='".$_POST["NEURO5"]."',
NEURO6 ='".$_POST["NEURO6"]."',
NEURO7 ='".$_POST["NEURO7"]."',
NEURO8 ='".$_POST["NEURO8"]."',
NEURO9 ='".$_POST["NEURO9"]."',
NEURO10 ='".$_POST["NEURO10"]."',
NEURO11 ='".$_POST["NEURO11"]."',
NEURO12 ='".$_POST["NEURO12"]."',
NEURO_notes ='".$_POST["NEURO_note"]."',
HEME1 ='".$_POST["HEME1"]."',
HEME2 ='".$_POST["HEME2"]."',
HEME3 ='".$_POST["HEME3"]."',
HEME4 ='".$_POST["HEME4"]."',
HEME5 ='".$_POST["HEME5"]."',
HEME6 ='".$_POST["HEME6"]."',
HEME_notes ='".$_POST["HEME_notes"]."',
ENDO1 ='".$_POST["ENDO1"]."',
ENDO2 ='".$_POST["ENDO2"]."',
ENDO3 ='".$_POST["ENDO3"]."',
ENDO4 ='".$_POST["ENDO4"]."',
ENDO5 ='".$_POST["ENDO5"]."',
ENDO_notes ='".$_POST["ENDO_notes"]."',
DERM1 ='".$_POST["DERM1"]."',
DERM2 ='".$_POST["DERM2"]."',
DERM3 ='".$_POST["DERM3"]."',
DERM4 ='".$_POST["DERM4"]."',
DERM5 ='".$_POST["DERM5"]."',
DERM6 ='".$_POST["DERM6"]."',
DERM_notes ='".$_POST["DERM_notes"]."',
finalize ='".$_POST["finalize"]."' where id=$id");
}

                       
 //Finish  up business.                                           
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>

