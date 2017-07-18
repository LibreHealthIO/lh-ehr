<?php
/**
 * PQRS Measure 0238 -- Population Criteria 1
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0238_PopulationCriteria1 implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0238_InitialPatientPopulation1();
    }
    
    public function createNumerators()
    {
        return new PQRS_0238_Numerator1();
    }
    
    public function createDenominator()
    {
        return new PQRS_0238_Denominator1();
    }
    
    public function createExclusion()
    {
        return new PQRS_0238_Exclusion1();
    }
}

?>