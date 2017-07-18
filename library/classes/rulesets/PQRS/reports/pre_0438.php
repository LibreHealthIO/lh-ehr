<?php
/**
 * Pre Measure 0438 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class pre_0438 extends AbstractPQRSReport {   
    public function createPopulationCriteria() {
		$populationCriteria = array();

		$populationCriteria[] = new pre_0438_PopulationCriteria1();
		$populationCriteria[] = new pre_0438_PopulationCriteria2();   
		$populationCriteria[] = new pre_0438_PopulationCriteria3(); 
		return $populationCriteria;    
    }

}

?>
