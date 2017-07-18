<?php
/**
 * PQRS Measure 0447 -- Initial Patient Population
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0447_InitialPatientPopulation extends PQRSFilter
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
" INNER JOIN pqrs_mips AS codelist_a ON (b1.code = codelist_a.code)".
" WHERE b1.pid = ? ".
" AND p.sex = 'Female'".
" AND fe.date BETWEEN '2015-01-01' AND '".$beginDate."' ".
" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) >= '15' ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0447_a'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ 
    $query =
"SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1". 
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" JOIN patient_data AS p ON (p.pid = b1.pid)".
" INNER JOIN pqrs_mips AS codelist_a ON (b1.code = codelist_a.code)".
" WHERE b1.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date BETWEEN '".$beginDate."' AND '".$beginDate."' ".
" AND TIMESTAMPDIFF(YEAR,p.DOB,'".$beginDate."') >= '16' ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0447_a'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} else {return false;}  
    
    } else {return false;}  
    }
}
