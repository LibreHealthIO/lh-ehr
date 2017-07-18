<?php
/**
 * PQRS Measure 0258 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0258_Denominator extends PQRSFilter
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
" WHERE b1.pid = ? ;";
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
//Rule outs follow
if ($result['count']='Male'){ 
$query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" WHERE b1.pid = ? ".
" b1.code = '9004F'; ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 

if ($result['count']> 0){ return false;} else {return true;}  

} else {
	
$query =
" SELECT COUNT(b1.code) AS count".  
" FROM billing AS b1".
" WHERE b1.pid = ? ".
" b1.code IN ('9003F','9004F'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id))); 

if ($result['count']> 0){ return false;} else {return true;}  

}
    }
}
