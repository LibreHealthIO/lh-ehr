<?php

class CommissionReport_helper{
    public static function mysql_query_billing_encounters_get($from_date, $to_date=NULL){
        /* Sanitize date. (start) */
        $t         = strtotime($from_date);
        $from_date = date('Y-m-d', $t);
        if($to_date){
            $t       = strtotime($to_date);
            $to_date = date('Y-m-d', $t);
        }
        /* Sanitize date. (end) */
        $query_where_date = '';
        if($from_date){
            if($to_date){
                $query_where_date .= 'bill_date >= "'.$from_date.' 00:00:00'.'" AND bill_date <= "'.$to_date  .' 23:59:59'.'" ';
            }else{
                $query_where_date .= 'bill_date >= "'.$from_date.' 00:00:00'.'" AND bill_date <= "'.$from_date.' 23:59:59'.'" ';
            }
        }
        $query = 'SELECT
                *
            FROM
                billing
            WHERE '.
                $query_where_date.';';
// error_log($query, 0);
        $result     = sqlStatement($query);
        $encounters = array();
        while($row = sqlFetchArray($result)){
            array_push($encounters, $row['encounter']);
        };
        if(count($encounters)){
            $encounters = implode(',', $encounters);
// error_log($encounters, 0);
            return $encounters;
        }else{
            return NULL;
        }
    }
}
