<?php
/**
 * PQRS Measure 0138 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class PQRS_0138 extends AbstractPQRSReport {   
    public function createPopulationCriteria() {
		$populationCriteria = array();

		$populationCriteria[] = new PQRS_0138_PopulationCriteria1();
		$populationCriteria[] = new PQRS_0138_PopulationCriteria2();   

		return $populationCriteria;    
    }

}

?>