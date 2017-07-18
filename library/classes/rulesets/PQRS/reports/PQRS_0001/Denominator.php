<?php
/*
 * PQRS Measure 0001 -- Denominator
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0001_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
	$query =
	"SELECT COUNT(b1.code) AS count ". 
	" FROM billing AS b1 ". 
	" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter) ".  
	" JOIN patient_data AS p ON (b1.pid = p.pid) ". 
	" WHERE b1.pid = ? ".
	" AND fe.date >= '".$beginDate."' ".
	" AND fe.date <= '".$endDate."' ".
	" AND b1.code = 'G9687';";

		$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
	if ($result['count'] > 0){
		 return false;} else {return true;} 
		 //inverse count.  If find code, it is a denom exclude.
    }
}

?>