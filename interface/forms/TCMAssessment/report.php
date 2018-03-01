<?php

/*
 * For information on Form Development view: README
 */

// libreehr/interface/globals.php
include_once('../../globals.php');

// $src = path to libreehr/library directory
include_once($srcdir.'/api.inc');

include_once(dirname(__FILE__).'/classes/TCMAssessment.php');

function tcmassessment_report($pid, $encounter, $cols, $id) {
	$tcmAssessment = new TCMAssessment(dirname(__FILE__));

	$tcmAssessment->assessmentId = $id;

	$tcmAssessment->sqlReportAssessment();

	$tcmAssessment->displayReportAssessment();
}
