<?php

/*
 * For information on Form Development view: README
 */

// libreehr/interface/globals.php
include_once('../../globals.php');

// $src = path to libreehr/library directory
include_once($srcdir.'/api.inc');
//include_once($srcdir.'/options.inc.php');
include_once($srcdir.'/forms.inc');

include(dirname(__FILE__).'/classes/TCMAssessment.php');
include(dirname(__FILE__).'/classes/TCMAssessmentAjax.php');

$tcmAssessmentAjax = new TCMAssessmentAjax;
if(array_key_exists('action', $_GET))
	$tcmAssessmentAjax->action = $_GET['action'];

$tcmAssessmentAjax->tcmAssessment = new TCMAssessment(dirname(__FILE__));
if(array_key_exists('id', $_GET))
	$tcmAssessmentAjax->tcmAssessment->assessmentId = $_GET['id'];

if(array_key_exists('test', $_GET))
	$tcmAssessmentAjax->testState = (boolean) $_GET['test'];

$tcmAssessmentAjax->userAuthorized = $userauthorized;

$tcmAssessmentAjax->formData = $_POST;

echo $tcmAssessmentAjax->response();
	