<?php

    // May need to update this for multi-site in the future
    require_once($libreehr_root_dir."/sites/default/sqlconf.php");
    require_once($libreehr_root_dir."/library/adodb/adodb.inc.php");
    require_once($libreehr_root_dir."/library/adodb/drivers/adodb-mysql.inc.php");
    require_once($libreehr_root_dir."/library/htmlspecialchars.inc.php");
    
    $database = NewADOConnection("mysql"); 
    
    // Below clientFlags flag is telling the mysql connection to allow local_infile setting,
    // which is needed to import data in the Administration->Other->External Data Loads feature.
    // Note this is a specific bug to work in Ubuntu 12.04, of which the Data Load feature does not
    // work and is suspicious for a bug in PHP of that OS; Setting this clientFlags fixes this bug
    // and appears to not cause problems in other operating systems.
    $database->clientFlags = 128;
    $connection_status=$database->NConnect($host.":".$port, $login, $pass, $dbase);    
    if(!$connection_status)
    {
        error_log("Could not connect to ".$host.":".$port);
    }
    
    function report_error(&$database,$query)
    {
        $msg= "=======>Query Failed". $query . "\n";
        $msg.= $database->ErrorMsg() . "\n";
	error_log($msg);
        echo $msg;
        return $msg;
    }    
    
    function column_list()
    {
        $columns=array();
        for($arg_num=0;$arg_num<func_num_args();$arg_num++)
        {
            array_push($columns,  func_get_arg($arg_num));
        }
        return implode(",",$columns);
    }
    
    function quoted_list()
    {
        $data=array();
        for($arg_num=0;$arg_num<func_num_args();$arg_num++)
        {
            array_push($data,  "'".func_get_arg($arg_num)."'");
        }
        return implode(",",$data);
    }
?>
