<?php
/**
 * PQRS Measure 0430 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Suncoast Connection
 */
 
class PQRS_0430_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
  $query =
"SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1". 
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" JOIN billing AS b2 ON (b2.pid = b1.pid)".
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND b1.code = '4554F' AND b2.code = '4556F'; "; 

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} else {return false;} 
    }
}

?>
