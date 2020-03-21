<?php
/**
 * PQRS Measure 0110 -- Initial Patient Population
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
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0110_a' AND b1.modifier NOT IN('GQ','GT','95'));";

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
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0111_a' AND b1.modifier NOT IN('GQ','GT','95'));";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 1){ return true;} else {return false;}  
//cascade using the code list from measure 111 for the 2 encounter match requirement.
    }
}
}
?>
