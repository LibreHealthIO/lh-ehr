
<?php
//------------Forms generated from formsWiz

 //SANITIZE ALL ESCAPES
 $sanitize_all_escapes=$_POST['true'];

 //STOP FAKE REGISTER GLOBALS
 $fake_register_globals=$_POST['false'];

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
if (!$encounter) { // comes from globals.php
    die(xlt("Internal error: we do not seem to be in an encounter!"));
}
if ($_GET["mode"] == "new"){
$newid = formSubmit("form_output", $_POST, $_GET["id"], $userauthorized);
addForm($encounter, "Output", $newid, "output", $pid, $userauthorized);
}elseif ($_GET["mode"] == "update") {
	formUpdate("form_output", $_POST, $_GET["id"], $userauthorized);
}
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>
