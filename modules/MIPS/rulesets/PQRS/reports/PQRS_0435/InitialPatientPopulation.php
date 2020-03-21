<?php
/**
 * PQRS Measure 0435 -- Initial Patient Population
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Suncoast Connection
 */
 
class PQRS_0435_InitialPatientPopulation extends PQRSFilter
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
" INNER JOIN pqrs_efcc5 AS codelist_a ON (b1.code = codelist_a.code)".
" INNER JOIN pqrs_efcc5 AS codelist_b ON (b2.code = codelist_b.code)".
" WHERE b1.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0435_a' AND b1.modifier NOT IN('GQ','GT','95'))".
" AND (b2.code = codelist_b.code AND codelist_b.type = 'pqrs_0435_b'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 1){ return true;} else {return false;}  

    }
}

?>
