<?php
/**
 * pre Measure 0391 -- Population Criteria 1
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0391_PopulationCriteria1 implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0391_InitialPatientPopulation1();
    }
    
    public function createNumerators()
    {
        return new pre_0391_Numerator1();
    }
    
    public function createDenominator()
    {
        return new pre_0391_Denominator1();
    }
    
    public function createExclusion()
    {
        return new pre_0391_Exclusion1();
    }
}

?>