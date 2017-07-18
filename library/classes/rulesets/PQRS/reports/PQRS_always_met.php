<?php
/**
 * PQRS Measure Always Met
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class PQRS_always_met extends AbstractPQRSReport
{   
    public function createPopulationCriteria()
    {
         $populationCriteria = array();
         $populationCriteria[] = new PQRS_always_met_PopulationCriteria1();
         return $populationCriteria;    
    }
}

?>