<?php
/**
 * pre Measure 0441 -- Initial Patient Population 1
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
 
class pre_0441_InitialPatientPopulation1 extends PQRSFilter
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
