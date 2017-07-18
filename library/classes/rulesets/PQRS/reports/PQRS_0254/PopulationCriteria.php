<?php
/**
 * PQRS Measure 0254 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0254_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0254_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new PQRS_0254_Numerator();
    }
    
    public function createDenominator()
    {
        return new PQRS_0254_Denominator();
    }
    
    public function createExclusion()
    {
        return new PQRS_0254_Exclusion();
    }
}
