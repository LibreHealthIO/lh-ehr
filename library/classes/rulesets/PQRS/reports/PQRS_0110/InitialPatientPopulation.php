<?php
/**
 * PQRS Measure 0110 -- Initial Patient Population
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0110_InitialPatientPopulation extends PQRSFilter
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
" JOIN patient_data AS p ON (b1.pid = p.pid)".
" INNER JOIN pqrs_poph AS codelist_a ON (b1.code = codelist_a.code)".
" WHERE b1.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND ((fe.date BETWEEN '".$beginDate."' AND DATE_SUB('".$beginDate."', INTERVAL 9 MONTH))".
" OR (fe.date BETWEEN DATE_SUB('".$beginDate."', INTERVAL 9 MONTH) AND '".$endDate."'));";
" AND TIMESTAMPDIFF(MONTH,p.DOB,fe.date) >= '6' ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0110_a' AND b1.modifier NOT IN('GQ','GT'));";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} else {
 //checked for single encounter version first...now checking for 2 encounter option.   
 
    $query =
"SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1". 
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" JOIN patient_data AS p ON (b1.pid = p.pid)".
" INNER JOIN pqrs_poph AS codelist_a ON (b1.code = codelist_a.code)".
" WHERE b1.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND ((fe.date BETWEEN '".$beginDate."' AND DATE_SUB('".$beginDate."', INTERVAL 9 MONTH))".
" OR (fe.date BETWEEN DATE_SUB('".$beginDate."', INTERVAL 9 MONTH) AND '".$endDate."'));";
" AND TIMESTAMPDIFF(MONTH,p.DOB,fe.date) >= '6' ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0111_a' AND b1.modifier NOT IN('GQ','GT'));";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 1){ return true;} else {return false;}  
//cascade using the code list from measure 111 for the 2 encounter match requirement.
    }
}
}
?>
