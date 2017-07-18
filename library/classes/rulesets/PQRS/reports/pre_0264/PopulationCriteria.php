<?php
/**
 * pre Measure 0264 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0264_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0264_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0264_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0264_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0264_Exclusion();
    }
}
