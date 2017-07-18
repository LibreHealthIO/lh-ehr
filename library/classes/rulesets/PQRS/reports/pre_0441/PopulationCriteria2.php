<?php
/**
 * pre Measure 0441 -- Population Criteria 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0441_PopulationCriteria2 implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria 2";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0441_InitialPatientPopulation2();
    }
    
    public function createNumerators()
    {
        return new pre_0441_Numerator2();
    }
    
    public function createDenominator()
    {
        return new pre_0441_Denominator2();
    }
    
    public function createExclusion()
    {
        return new pre_0441_Exclusion2();
    }
}

?>
