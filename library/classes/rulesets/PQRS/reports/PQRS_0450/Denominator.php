<?php
/**
 * PQRS Measure 0450 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0450_Denominator extends PQRSFilter
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
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND b1.code = 'G9829' ; ";
//Requires Pre-Measure
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 
if ($result['count']== 0){ return false;}  
   
 
$query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND b1.code IN ('G9831','G9832','G9830') ; ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 
if ($result['count']== 0){ return false;} 
//Requires Pre-Measure
       
$query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND b1.code IN ('G9833','G9834') ; ";
//rule-out
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 
if ($result['count']> 0){ return false;} else {return true;} 

    }
}
