<?php
/**
 * pre Measure 0007 -- Initial Patient Population 2
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class pre_0007_InitialPatientPopulation2 extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population 2";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$score = 0;
$query =
"SELECT COUNT(b1.code) as count ". 
" FROM billing AS b1".   
" JOIN patient_data AS p ON (b1.pid = p.pid)".
" INNER JOIN pqrs_efcc1 AS codelist_b ON (b1.code = codelist_b.code)".
" WHERE b1.pid = ? ".
" AND (b1.code = codelist_b.code AND codelist_b.type = 'pqrs_0007_b') ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ $score +=1;}
if ($score === 1){
		$query =
		"SELECT COUNT(b1.code) as count ". 
		" FROM billing AS b1". 
		" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".  
		" JOIN patient_data AS p ON (b1.pid = p.pid)".
		" INNER JOIN pqrs_efcc1 AS codelist_c ON (b1.code = codelist_c.code)".		
		" WHERE b1.pid = ? ".
		" AND YEAR(fe.date) <= '2014'".
		" AND (b1.code = codelist_c.code AND codelist_c.type = 'pqrs_0007_c') ";  
		
		$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
	}
if ($result['count']> 0){ $score +=1;}  
if ($score === 2){
    	$query =
		"SELECT COUNT(b1.code) as count ". 
		" FROM billing AS b1".  
		" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".  
		" JOIN patient_data AS p ON (b1.pid = p.pid)".
		" INNER JOIN pqrs_efcc1 AS codelist_a ON (b1.code = codelist_a.code)".
		" WHERE b1.pid = ? ".
		" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) >= '18'  ".
        " AND fe.provider_id = '".$this->_reportOptions['provider']."'". 
		" AND fe.date >= '".$beginDate."' ".
		" AND fe.date <= '".$endDate."' ".
		" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0007_a') ".
		" AND b1.modifier NOT IN('GQ', 'GT')";  
		
		$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
	}
$score += $result['count']; 
if ($score >= 4){return true;} else {return false;}  
 
    }
}

?>
