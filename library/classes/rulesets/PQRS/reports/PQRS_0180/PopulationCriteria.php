<?php
/**
 * PQRS Measure 0180 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0180_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0180_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new PQRS_0180_Numerator();
    }
    
    public function createDenominator()
    {
        return new PQRS_0180_Denominator();
    }
    
    public function createExclusion()
    {
        return new PQRS_0180_Exclusion();
    }
}

?>