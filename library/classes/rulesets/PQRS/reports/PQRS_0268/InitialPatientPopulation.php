<?php
/**
 * PQRS Measure 0268 -- Initial Patient Population
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0268_InitialPatientPopulation extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1". 
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" JOIN patient_data AS p ON (p.pid = b1.pid)".
" INNER JOIN billing AS b2 ON (b2.pid = b1.pid)".
" INNER JOIN pqrs_efcc3 AS codelist_a ON (b1.code = codelist_a.code)".
" INNER JOIN pqrs_efcc3 AS codelist_b ON (b2.code = codelist_b.code)".
" WHERE b1.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND p.sex = 'Female'".
" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) BETWEEN '12' AND '44'  ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0268_a' AND b1.modifier NOT IN('GQ','GT')) ".
" AND (b2.code = codelist_b.code AND codelist_b.type = 'pqrs_0268_b'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} else {return false;}  
    }
}
