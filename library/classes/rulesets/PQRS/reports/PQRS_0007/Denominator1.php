<?php
/**
 * PQRS Measure 0007 -- Denominator 1
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0007_Denominator1 extends PQRSFilter
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
		" JOIN patient_data AS p ON (b1.pid = p.pid)".
		" INNER JOIN pqrs_efcc1 AS codelist_c ON (b1.code = codelist_c.code)".		
		" WHERE b1.pid = ? ".
		" AND b1.code = 'G8694'";  
		
		$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;}else{return false;}        

    }
}

?>
