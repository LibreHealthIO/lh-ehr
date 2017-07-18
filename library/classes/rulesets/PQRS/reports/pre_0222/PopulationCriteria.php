<?php
/**
 * pre Measure 0222 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0222_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0222_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0222_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0222_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0222_Exclusion();
    }
}
