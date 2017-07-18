<?php
/**
 * pre Measure 0440 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0440_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0440_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0440_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0440_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0440_Exclusion();
    }
}
