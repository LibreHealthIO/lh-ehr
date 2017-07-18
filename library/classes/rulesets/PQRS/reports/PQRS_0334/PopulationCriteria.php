<?php
/**
 * PQRS Measure 0334 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0334_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0334_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new PQRS_0334_Numerator();
    }
    
    public function createDenominator()
    {
        return new PQRS_0334_Denominator();
    }
    
    public function createExclusion()
    {
        return new PQRS_0334_Exclusion();
    }
}

?>