<?php
/**
 * PQRS Measure 0052 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0052_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" WHERE b1.pid = ? ".
" AND b1.code = 'G8924' ; ";
//Spirometry test results demonstrate FEV1/FVC < 70%, FEV1 < 60% predicted and patient has COPD
//symptoms (e.g., dyspnea, cough/sputum, wheezing): G8924
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 

if ($result['count']> 0){ return true;} else {return false;}  
    }
}

?>
