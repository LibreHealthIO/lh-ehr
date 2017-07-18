<?php
/**
 * PQRS Measure 0446 -- Call to createPopulationCriteria()
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Suncoast Connection
 */

class PQRS_0446 extends AbstractPQRSReport {   
    public function createPopulationCriteria() {
		$populationCriteria = array();

		$populationCriteria[] = new PQRS_0446_PopulationCriteria1();
		$populationCriteria[] = new PQRS_0446_PopulationCriteria2();   

		return $populationCriteria;    
    }

}

?>
