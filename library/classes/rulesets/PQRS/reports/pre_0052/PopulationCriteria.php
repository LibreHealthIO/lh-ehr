<?php
/**
 * pre Measure 0052 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0052_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0052_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0052_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0052_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0052_Exclusion();
    }
}

?>
