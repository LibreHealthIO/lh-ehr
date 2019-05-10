
<?php
/*
 * HCC Measure HCC_0107 -- Population Criteria
 *
 * Copyright (C) 2018      Suncoast Connection
  * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */
 
class HCC_0107_InitialPatientPopulation extends PQRSFilter
{
    public function getTitle() 
    {
        return "Initial Patient Population";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {

$query =
"SELECT COUNT(b1.code) AS count ". 
" FROM billing AS b1 ". 
" JOIN form_encounter AS fe ON (b1.encounter = fe.encounter) ".  
" JOIN patient_data AS p ON (b1.pid = p.pid) ". 
" INNER JOIN mips_hcc AS codelist_a ON (b1.code = codelist_a.code)".
" WHERE b1.pid = ? ";

        $thisprov = $this->_reportOptions['provider'];
        if ($thisprov != 1000000001){ $query .=
        " AND fe.provider_id = '".$this->_reportOptions['provider']."'";}
        $query .=  
" AND fe.date >= '".$beginDate."'".
" AND fe.date <= '".$endDate."'" .
" AND (b1.code = codelist_a.code AND codelist_a.type = 'HCC_0107') ;";


$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count'] > 0){
 return true;} else {return false;} 


    }
}

?>
