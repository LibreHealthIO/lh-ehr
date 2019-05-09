<?php
/**
 * PQRS Measure 0279 -- Initial Patient Population
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
 
class PQRS_0279_InitialPatientPopulation extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b1.code) as count". 
" FROM billing AS b1".  
" INNER JOIN billing AS b2 ON (b1.pid = b2.pid)".  
" INNER JOIN pqrs_efcc1 AS codelist_a ON (b1.code = codelist_a.code)".
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter)".
" JOIN patient_data AS p ON (b1.pid = p.pid)". 
" WHERE b1.pid = ? ".
" AND fe.date BETWEEN '".$beginDate."' AND '".$endDate."' ".
" AND TIMESTAMPDIFF(YEAR,p.DOB,fe.date) >= '18'  ".  
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0005_a' AND b1.modifier NOT IN('GQ','GT','95')) ".
" AND b2.code IN('G47.30', 'G47.33');";
//use code list for measure 0005
$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));

if ($result['count'] > 0){ return true;} else {return false;} 
    }
}

?>

