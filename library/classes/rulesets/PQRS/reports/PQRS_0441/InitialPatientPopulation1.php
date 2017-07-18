<?php
/**
 * PQRS Measure 0441 -- Initial Patient Population 1
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0441_InitialPatientPopulation1 extends PQRSFilter
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
" INNER JOIN billing AS b2 ON (b2.pid = b1.pid)".
" JOIN patient_data AS p ON (p.pid = b1.pid)".
" INNER JOIN pqrs_mips AS codelist_a ON (b1.code = codelist_a.code)".
" INNER JOIN pqrs_mips AS codelist_b ON (b2.code = codelist_b.code)".
" WHERE b1.pid = ? ".
" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) BETWEEN '18' AND '75'".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date >= DATE_SUB('".$beginDate."', INTERVAL 1 YEAR)".
" AND fe.date <= '".$endDate."' ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0441_a') ".
" AND (b2.code = codelist_b.code AND codelist_b.type = 'pqrs_0441_b');";
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 1){ return true;} else {return false;}  

    }
}

?>
