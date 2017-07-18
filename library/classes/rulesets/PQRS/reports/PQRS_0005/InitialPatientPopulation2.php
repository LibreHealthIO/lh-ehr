<?php
/*
 * PQRS Measure 0005 -- Population Criteria 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0005_InitialPatientPopulation2 extends PQRSFilter
{


    public function getTitle() 
    {
        return "Initial Patient Population 2";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
	    

$query =
"SELECT COUNT(b1.code) as count ". 
" FROM billing AS b1 ".  
" INNER JOIN billing AS b2 ON (b1.pid = b2.pid) ".  
" INNER JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".  
" INNER JOIN patient_data AS p ON (b1.pid = p.pid)".  
" INNER JOIN pqrs_efcc1 AS codelist_c ON (b1.code = codelist_c.code)".
" INNER JOIN pqrs_efcc1 AS codelist_b ON (b2.code = codelist_b.code)".
" WHERE b1.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'". 
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) >= '18'  ". 
" AND (b1.code = codelist_c.code AND codelist_c.type = 'pqrs_0005_c') ".
" AND (b2.code = codelist_b.code AND codelist_b.type = 'pqrs_0005_b') ;";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} else {return false;}  
	    

    }
}

?>
