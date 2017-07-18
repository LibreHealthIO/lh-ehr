<?php
/**
 * PQRS Measure 0118 -- Denominator 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0118_Denominator2 extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator 2";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
return true;
//No pre-measure or rule outs
    }
}

?>
