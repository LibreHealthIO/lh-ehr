<?php
/**
 * PQRS Measure 0400 -- Denominator 
 *
 * Copyright (C) 2016      Suncoast Connection
 * @package PQRS_Gateway 
 * @link    http://suncoastconnection.com
 * @author  Bryan lee <bryan@suncoastconnection.com>
 * @author  Art Eaton <art@suncoastconnection.com>
 */
 
class PQRS_0400_Denominator extends PQRSFilter
{
    public function getTitle() 
    {
        return "Denominator";
    }
    
    public function test( PQRSPatient $patient, $beginDate, $endDate )
    {
$query =
"SELECT COUNT(b1.code) as count ".  
" FROM billing AS b1". 
" INNER JOIN pqrs_efcc3 AS codelist_a ON (b1.code = codelist_a.code)".
" WHERE b1.pid = ? ".
" AND (b1.code = codelist_a.code AND codelist_a.type = 'pqrs_0400_c') ; ";

$result = sqlFetchArray(sqlStatementNoLog($query, array($patient->id)));
if ($result['count']> 0){ return true;}else{return false;}
}
}
?>