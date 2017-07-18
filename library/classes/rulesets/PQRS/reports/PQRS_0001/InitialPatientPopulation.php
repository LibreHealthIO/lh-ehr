<?php
/*
 * PQRS Measure 0001 -- Population Criteria
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0001_InitialPatientPopulation extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
	
	$query =
	"SELECT COUNT(b1.code) AS count ". 
	" FROM billing AS b1 ". 
	" INNER JOIN billing AS b2 ON (b1.pid = b2.pid) ". 
	" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter) ".  
	" JOIN patient_data AS p ON (b1.pid = p.pid) ". 
	" INNER JOIN pqrs_efcc1 AS codelist_b ON (b1.code = codelist_b.code)".
	" INNER JOIN pqrs_efcc1 AS codelist_a ON (b2.code = codelist_a.code)".
	" WHERE b1.pid = ? ".
    " AND fe.provider_id = '".$this->_reportOptions['provider']."'".  
	" AND fe.date >= '".$beginDate."' ".
	" AND fe.date <= '".$endDate."' ".
	" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date)  BETWEEN '18' AND '75' ".  //age must be between 18 and 75 on the date of treatment
	" AND (b1.code = codelist_b.code AND codelist_b.type = 'pqrs_0001_b') ".
	" AND (b2.code = codelist_a.code AND codelist_a.type = 'pqrs_0001_a') ;";
	

		$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
	if ($result['count'] > 0){
		 return true;} else {return false;} 
	

    }
}

?>