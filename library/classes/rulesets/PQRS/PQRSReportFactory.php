<?php
/**
 * Report Factory for PQRS Measures
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>.
 *
 * @package OpenEMR
 * @link    http://www.oemr.org
 * @link    http://SuncoastConnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Art Eaton <art@starfrontiers.org>
 */

class PQRSReportFactory extends RsReportFactoryAbstract {

  public function __construct() {
    foreach(glob(dirname(__FILE__).'/library/*.php') as $filename) {
      require_once($filename);
    }

    foreach(glob(dirname(__FILE__).'/reports/*.php') as $filename) {
      require_once($filename);
    }

    foreach(glob(dirname(__FILE__).'/groups/*.php') as $filename) {
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