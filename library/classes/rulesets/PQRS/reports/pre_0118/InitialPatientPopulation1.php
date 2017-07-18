<?php
/**
 * pre Measure 0118 -- Initial Patient Population 1
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0118_InitialPatientPopulation1 extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population 1";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1". 
" INNER JOIN pqrs_efcc2 AS codelist_b ON (b1.code = codelist_b.code)".
" WHERE b1.pid = ? ".
" AND (b1.code = codelist_b.code AND codelist_b.type = 'pqrs_0118_b');";
//check for CAD Dx
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){

			$query =
			"SELECT COUNT(b1.code) as count ".  
			" FROM billing AS b1". 
			" INNER JOIN pqrs_efcc2 AS codelist_a ON (b1.code = codelist_a.code)".
			" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
			" WHERE b1.pid = ? ".
            " AND fe.provider_id = '".$this->_reportOptions['provider']."'".
			" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0118_a' AND b1.modifier NOT IN('GQ','GT'));";
			// Checking for two visits
			$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
			if ($result['count']> 1){ return true;} else {return false;}  
		} else {return false;}  
    }
}

?>
