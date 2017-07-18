<?php
/**
 * PQRS Measure 0393 -- Population Criteria 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0393_PopulationCriteria2 implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria 2";
    }
    
    public function createInitialPatientPopulation()
    {
        return new PQRS_0393_InitialPatientPopulation2();
    }
    
    public function createNumerators()
    {
        return new PQRS_0393_Numerator2();
    }
    
    public function createDenominator()
    {
        return new PQRS_0393_Denominator2();
    }
    
    public function createExclusion()
    {
        return new PQRS_0393_Exclusion2();
    }
}

?>
