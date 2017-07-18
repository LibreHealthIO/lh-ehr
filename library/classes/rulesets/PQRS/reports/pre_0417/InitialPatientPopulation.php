<?php
/**
 * pre Measure 0417 -- Initial Patient Population
 *
 * Copyright (C) 2016      Suncoast Connection
 *
 * @package OpenEMR
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Suncoast Connection
 */
 
class pre_0417_InitialPatientPopulation extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
" SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1 ".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter) ".
" JOIN patient_data AS p ON (p.pid = b1.pid)".
" WHERE b1.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date)>='18' ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".  
" AND b1.code IN ('35081','35102');"; 

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count'] > 0){ return true;} else {return false;} 
    }
}

?>
