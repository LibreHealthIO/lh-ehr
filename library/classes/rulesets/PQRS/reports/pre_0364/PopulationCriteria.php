<?php
/**
 * pre Measure 0364 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0364_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0364_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0364_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0364_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0364_Exclusion();
    }
}
