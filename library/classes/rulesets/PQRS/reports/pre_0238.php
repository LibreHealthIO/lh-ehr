<?php
/**
 * Pre Measure 0238 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class pre_0238 extends AbstractPQRSReport {   
    public function createPopulationCriteria() {
		$populationCriteria = array();

		$populationCriteria[] = new pre_0238_PopulationCriteria1();
		$populationCriteria[] = new pre_0238_PopulationCriteria2();   

		return $populationCriteria;    
    }

}

?>

