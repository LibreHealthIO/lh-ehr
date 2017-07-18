<?php
/**
 * PQRS Measure 0395 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0395_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0395_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new PQRS_0395_Numerator();
    }
    
    public function createDenominator()
    {
        return new PQRS_0395_Denominator();
    }
    
    public function createExclusion()
    {
        return new PQRS_0395_Exclusion();
    }
}
