
<?php
//------------Forms generated from formsWiz

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
if (!$encounter) { // comes from globals.php
    die(xlt("Internal error: we do not seem to be in an encounter!"));
}
if ($_GET["mode"] == "new"){
$newid = formSubmit("form_intake", $_POST, $_GET["id"], $userauthorized);
addForm($encounter, "Intake", $newid, "intake", $pid, $userauthorized);
}elseif ($_GET["mode"] == "update") {
	formUpdate("form_intake", $_POST, $_GET["id"], $userauthorized);
}
$_SESSION["encounter"] = $encounter;
formHeader("Redirecting....");
formJump();
formFooter();
?>
