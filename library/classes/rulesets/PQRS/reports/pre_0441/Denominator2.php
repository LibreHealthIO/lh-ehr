<?php
/**
 * pre Measure 0441 -- Denominator 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0441_Denominator2 extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator 2";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
return true;
    }
}

?>
