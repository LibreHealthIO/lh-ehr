<?php
/**
 * Pre Measure 0441 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package PQRS_Gateway
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class pre_0441 extends AbstractPQRSReport {   
    public function createPopulationCriteria() {
		$populationCriteria = array();

		$populationCriteria[] = new pre_0441_PopulationCriteria1();
		$populationCriteria[] = new pre_0441_PopulationCriteria2();   

		return $populationCriteria;    
    }

}

?>
