<?php
/**
 * PQRS Measure 0118 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class PQRS_0118 extends AbstractPQRSReport {   
    public function createPopulationCriteria() {
		$populationCriteria = array();

		$populationCriteria[] = new PQRS_0118_PopulationCriteria1();
		$populationCriteria[] = new PQRS_0118_PopulationCriteria2();   

		return $populationCriteria;    
    }

}

?>
