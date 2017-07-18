<?php
/**
 * Defines a population of patients
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
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