<?php
/**
 * PQRS Measure 0383 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0383_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b3.code) as count ".  
" FROM billing AS b3". 
" INNER JOIN form_encounter AS fe ON (b3.encounter = fe.encounter)".
" JOIN pqrs_ptsf AS codelist_c ON (b3.code = codelist_c.code)".
" WHERE b3.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date >= '".$beginDate."' ".
" AND fe.date <= '".$endDate."' ".
" AND (b3.code = codelist_c.code AND codelist_c.type = 'pqrs_0383_c'); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} 

$query =
"SELECT COUNT(b4.code) as count ".  
" FROM billing AS b4". 
" INNER JOIN form_encounter AS fe ON (b4.encounter = fe.encounter)".
" INNER JOIN facility AS fac ON (fe.facility = fac.id)".
" JOIN pqrs_ptsf AS codelist_d ON (b4.code = codelist_d.code)".
" WHERE b4.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date >= '".$beginDate."' ".
" AND fe.date <= '".$endDate."' ".
" AND  (b4.code = codelist_d.code AND codelist_d.type = 'pqrs_0383_d' AND fac.pos_code IN ('03', '05', '07', '09', '11', '12', '13', '14', '15', '20', '22', '24', '26', '33', '49', '50', '52', '53', '71', '72')); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;}

$query =
"SELECT COUNT(b6.code) as count ".  
" FROM billing AS b6". 
" INNER JOIN form_encounter AS fe ON (b6.encounter = fe.encounter)".
" INNER JOIN facility AS fac ON (fe.facility = fac.id)".
" JOIN pqrs_ptsf AS codelist_f ON (b6.code = codelist_f.code)".
" WHERE b6.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date >= '".$beginDate."' ".
" AND fe.date <= '".$endDate."' ".
" AND (b6.code = codelist_f.code AND codelist_f.type = 'pqrs_0383_f' AND fac.pos_code IN ('23','31','32','56')); ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} 

$query =
"SELECT COUNT(b7.code) as count ".  
" FROM billing AS b7". 
" INNER JOIN form_encounter AS fe ON (b7.encounter = fe.encounter)".
" INNER JOIN facility AS fac ON (fe.facility = fac.id)".
" JOIN pqrs_ptsf AS codelist_g ON (b7.code = codelist_g.code)".
" WHERE b7.pid = ? ".
" AND fe.provider_id = '".$this->_reportOptions['provider']."'".
" AND fe.date >= '".$beginDate."' ".
" AND fe.date <= '".$endDate."' ".
" AND(b7.code = codelist_g.code AND codelist_g.type = 'pqrs_0383_g' AND fac.pos_code  IN ('21','51')) ; ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;} else {return false;}

    }
}
