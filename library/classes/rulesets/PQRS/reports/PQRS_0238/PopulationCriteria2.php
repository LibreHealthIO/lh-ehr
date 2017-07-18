<?php
/**
 * PQRS Measure 0238 -- Population Criteria 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0238_PopulationCriteria2 implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria 2";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0238_InitialPatientPopulation2();
    }
    
    public function createNumerators()
    {
        return new PQRS_0238_Numerator2();
    }
    
    public function createDenominator()
    {
        return new PQRS_0238_Denominator2();
    }
    
    public function createExclusion()
    {
        return new PQRS_0238_Exclusion2();
    }
}

?>
