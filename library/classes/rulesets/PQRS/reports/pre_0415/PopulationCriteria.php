<?php
/**
 * pre Measure 0415 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Suncoast Connection
 */
 
class pre_0415_PopulationCriteria implements PQRSPopulationCriteriaFactory
{
    public function getTitle()
    {
        return "Population Criteria";
    }
    
    public function createInitialPatientPopulation()
    {
        return new pre_0415_InitialPatientPopulation();
    }
    
    public function createNumerators()
    {
        return new pre_0415_Numerator();
    }
    
    public function createDenominator()
    {
        return new pre_0415_Denominator();
    }
    
    public function createExclusion()
    {
        return new pre_0415_Exclusion();
    }
}

?>
