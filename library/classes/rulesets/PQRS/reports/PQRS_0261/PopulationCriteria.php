<?php
/**
 * PQRS Measure 0261 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0261_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0261_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new PQRS_0261_Numerator();
    }
    
    public function createDenominator()
    {
        return new PQRS_0261_Denominator();
    }
    
    public function createExclusion()
    {
        return new PQRS_0261_Exclusion();
    }
}

?>