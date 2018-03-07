save file
<?php
exit();

/*
 * For information on Form Development view: README
 */

// libreehr/interface/globals.php
include_once('../../globals.php');

// $src = path to libreehr/library directory
include_once($srcdir.'/api.inc');
include_once($srcdir.'/options.inc.php');
include_once($srcdir.'/forms.inc');

include('TCMAssessment.php');

$tcmAssessment = new TCMAssessment;

if(!array_key_exists('mode', $_GET)
	|| in_array($_GET['mode'], array('new', 'update'))
)
	throw new \UnexpectedValueException('Mode must be specified (ie: new, update).');

switch($_GET['mode']) {
	case 'new':

		break;

	case 'update':

		break;

}

unset($_POST['formSubmit']);
$tcmAssessment->formData = $_POST;
$validate = $tcmAssessment->checkNewAssessment();

if($validate) {
	$tcmAssessment->saveNewAssessment();
	header('location: view.php', true, 307);
} else {
	$tcmAssessment->displayNewAssessment();
}