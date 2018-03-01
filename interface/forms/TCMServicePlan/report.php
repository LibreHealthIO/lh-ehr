<?php

/*
 * For information on Form Development view: README
 */

// libreehr/interface/globals.php
include_once('../../globals.php');

// $src = path to libreehr/library directory
include_once($srcdir.'/api.inc');

include_once(dirname(__FILE__).'/classes/TCMServicePlan.php');

function tcmserviceplan_report($pid, $encounter, $cols, $id) {
	$tcmServicePlan = new TCMServicePlan(dirname(__FILE__));

	$tcmServicePlan->servicePlanId = $id;

	$tcmServicePlan->sqlReportServicePlan();

	$tcmServicePlan->displayReportServicePlan();
}
