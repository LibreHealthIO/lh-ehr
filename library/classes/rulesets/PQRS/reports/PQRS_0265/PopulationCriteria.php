<?php
/**
 * PQRS Measure 0265 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0265_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0265_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new PQRS_0265_Numerator();
    }
    
    public function createDenominator()
    {
        return new PQRS_0265_Denominator();
    }
    
    public function createExclusion()
    {
        return new PQRS_0265_Exclusion();
    }
}

?>