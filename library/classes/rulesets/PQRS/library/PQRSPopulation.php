<?php
/**
 * Defines a population of patients
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

require_once( "PQRSPatient.php" );

class PQRSPopulation extends RsPopulation {
    /*
     * Initialize the patient population
     */
    public function __construct(array $patientIdArray) {
        foreach ($patientIdArray as $patientId) {
            $this->_patients[]= new PQRSPatient($patientId);
        }
    }

    /*
     * ArrayAccess Interface
     */
    public function offsetSet($offset, $value) {
        if ($value instanceof PQRSPatient) {
            if($offset == '') {
                $this->_patients[] = $value;
            } else {
                $this->_patients[$offset] = $value;
            }
        } else {
            throw new Exception('Value must be an instance of PQRSPatient');
        }
    }

}

?> 