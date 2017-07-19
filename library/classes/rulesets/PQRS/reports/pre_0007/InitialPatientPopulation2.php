<?php
/**
 * pre Measure 0007 -- Initial Patient Population 2
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
