<?php
/**
 * PQRS Measure 0383 -- Initial Patient Population
 *
 * Copyright (C) 2015 - 2017      Suncoast Connection
  * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
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
 