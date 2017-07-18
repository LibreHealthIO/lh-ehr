<?php
/**
 * PQRS Measure 0347 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0347_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query = 
"SELECT sex AS count".
" FROM patient_data".
" WHERE pid = ? ;";
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));

if ($result['count']='Male'){ 
$query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" WHERE b1.pid = ? ".
" AND fe.date >= '".$beginDate."' ".
" AND fe.date <= '".$endDate."' ".
" AND b1.code = '9004F'; ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 

if ($result['count']> 0){ return false;} else {return true;}  

} else {
	
$query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" WHERE b1.pid = ? ".
" AND fe.date >= '".$beginDate."' ".
" AND fe.date <= '".$endDate."' ".
" AND b1.code IN ('9003F','9004F'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 

if ($result['count']> 0){ return false;} else {return true;}  

}
    }
}