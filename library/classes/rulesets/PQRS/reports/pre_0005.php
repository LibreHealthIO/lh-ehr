<?php
/**
 * Pre Measure 0005 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class pre_0005 extends AbstractPQRSReport {   
    public function createPopulationCriteria() {
		$populationCriteria = array();

		$populationCriteria[] = new pre_0005_PopulationCriteria1();
		$populationCriteria[] = new pre_0005_PopulationCriteria2();   

		return $populationCriteria;    
    }

}

?>