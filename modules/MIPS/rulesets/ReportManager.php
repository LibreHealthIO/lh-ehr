<?php
/**
 * Manage & Run Reports
 *
 * Copyright (C) 2016      Suncoast Connection
 * @link    http://SuncoastConnection.com
 * @author  Bryan lee <leebc11 at acm dot org>
 */

require_once('ReportTypes.php');

class ReportManager {
  public function __construct() {
    foreach(glob(dirname(__FILE__).'/library/*.php') as $filename) {
      require_once($filename);
    }


    foreach(glob(dirname(__FILE__).'/PQRS/*.php') as $filename) {
      require_once($filename);
    }
  }

  public function runReport($rowRule, $patients, $dateTarget, $options = array()) {
    $ruleId = $rowRule['id'];
    $patientData = array();

    foreach( $patients as $patient ) {
      $patientData[] = $patient['pid'];
    }

    $reportFactory = null;

    switch (ReportTypes::getType($ruleId)) {

      case ReportTypes::PQRS:
        $reportFactory = new PQRSReportFactory();
        break;

      default:
      error_log("ReportManager:  (now with less fatality) Unknown rule: $ruleId");

        //throw new Exception('Unknown rule: '.$ruleId);
        break;
    }

    $report = null;

    if($reportFactory instanceof RsReportFactoryAbstract) {
      $report = $reportFactory->createReport(ReportTypes::getClassName($ruleId), $rowRule, $patientData, $dateTarget, $options);
    }

    $results = array();

    if($report instanceof RsReportIF &&
      !$report instanceof RsUnimplementedIF
    ) {
      $report->execute();

      $results = $report->getResults();
    }

    return RsHelper::formatClinicalRules($results);
  }
}

?>
