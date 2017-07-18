<?php
/**
 * PQRS Measure 0383 -- Initial Patient Population
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0383_InitialPatientPopulation extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population";
    }
    //Jack Wagon!!!! NOT ATTEMPTING!!!!
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
		$query =
		"SELECT COUNT(b1.code) as count ".  
		" FROM billing AS b1". 
		" JOIN patient_data AS p ON (p.pid = b1.pid)".
		" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
		" INNER JOIN pqrs_ptsf AS codelist_a ON (b1.code = codelist_a.code)".
		" WHERE b1.pid = ? ".
    	//" AND fe.provider_id = '".$this->_reportOptions['provider']."'".  //don't check for provider just on a diagnosis!
		" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) >= '18' ".
		" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0383_a');";
		
		$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
			if ($result['count']> 0){  
	
	    
				$query =
				"SELECT COUNT(b1.code) as count ".  
				" FROM billing AS b1". 
				" INNER JOIN pqrs_ptsf AS codelist_a ON (b1.code = codelist_a.code)".
				" WHERE b1.pid = ? ".
				" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0383_b');";
				
				$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
				if ($result['count']= 0){ return true;} else {return false;}  
				}else {return false;}			
			
    }
}
 