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

include(dirname(__FILE__).'/classes/TCMServicePlan.php');
include(dirname(__FILE__).'/classes/TCMServicePlanAjax.php');

$tcmServicePlanAjax = new TCMServicePlanAjax;

if(array_key_exists('action', $_GET))
	$tcmServicePlanAjax->action = $_GET['action'];

$tcmServicePlanAjax->tcmServicePlan = new TCMServicePlan(dirname(__FILE__));

if(array_key_exists('id', $_GET))
	$tcmServicePlanAjax->tcmServicePlan->servicePlanId = $_GET['id'];

if(array_key_exists('test', $_GET))
	$tcmServicePlanAjax->testState = (boolean) $_GET['test'];

$tcmServicePlanAjax->userAuthorized = $userauthorized;

$tcmServicePlanAjax->formData = json_decode(file_get_contents('php://input'));

echo $tcmServicePlanAjax->response();	