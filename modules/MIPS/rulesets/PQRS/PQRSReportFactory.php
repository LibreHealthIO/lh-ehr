<?php
/**
 * Report Factory for PQRS Measures
 *
 * Copyright (C) 2015 - 2017      Suncoast Connection
  * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */

class PQRSReportFactory extends RsReportFactoryAbstract {

  public function __construct() {
    foreach(glob(dirname(__FILE__).'/library/*.php') as $filename) {
      require_once($filename);
    }

    foreach(glob(dirname(__FILE__).'/reports/*.php') as $filename) {
      require_once($filename);
    }

    foreach(glob(dirname(__FILE__).'/groups/*.php') as $filename) {//not used
      require_once($filename);
    }
  }

  public function createReport($className, $rowRule, $patientData, $dateTarget, $options) {
    $reportObject = null;

    if(class_exists($className)) {
      $reportObject = new $className($rowRule, $patientData, $dateTarget, $options);
    } else {
      $reportObject = new NFQ_Unimplemented();
    }

    return $reportObject;
  }
}

?>