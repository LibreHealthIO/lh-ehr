<?php
/**
 * pre Measure 0438 -- Initial Patient Population 2
 *
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0438_InitialPatientPopulation2 extends PQRSFilter
{
    public function getTitle()
    {
        return "Initial Patient Population 2";
    }

    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
	$query =
	"SELECT COUNT(b1.code) as count ".  
	" FROM billing AS b1". 
	" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
	" JOIN patient_data AS p ON (p.pid = b1.pid)".
	" INNER JOIN pqrs_efcc5 AS codelist_a ON (b1.code = codelist_a.code)".
	" WHERE b1.pid = ? ".
	" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
	" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) >= '21' ".
	" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
	" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0438_a' AND b1.modifier NOT IN('GQ','GT')); ";
	
	$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
	if ($result['count']> 0){ return true;} else {return false;}  

    
    }
}

?>
