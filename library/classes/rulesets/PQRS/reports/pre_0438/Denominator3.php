<?php
/**
 * pre Measure 0438 -- Denominator 3
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0438_Denominator3 extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator 3";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
return true;
    }
}

?>
