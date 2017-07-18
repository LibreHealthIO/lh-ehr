<?php
/**
 * pre Measure 0176 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0176_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0176_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0176_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0176_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0176_Exclusion();
    }
}

?>