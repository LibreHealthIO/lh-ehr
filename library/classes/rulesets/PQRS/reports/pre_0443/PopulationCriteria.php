<?php
/**
 * pre Measure 0443 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0443_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0443_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0443_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0443_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0443_Exclusion();
    }
}
