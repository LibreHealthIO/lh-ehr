<?php
/**
 * pre Measure 0224 -- Exclusion 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */

class pre_0224_Exclusion2 extends PQRSFilter
{
    public function getTitle() 
    {
        return "Exclusion 2";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND b1.code = '3319F' AND b1.modifier IN ('1P', '3P');";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 

if ($result['count'] > 0){ return true;} else {return false;}    

    }
}

?>
