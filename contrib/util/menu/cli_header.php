<?php
namespace pcmedics;
    if(php_sapi_name()!=='cli')
    {
//        echo "Not allowed!";
//        exit();
    }
    if (!defined('IS_WINDOWS'))
     define('IS_WINDOWS', (stripos(PHP_OS,'WIN') === 0));
    
    // root is two levels up from directory containing this header file.
    $libreehr_root_dir = dirname(dirname(dirname(dirname(__FILE__))));
    
    require_once($libreehr_root_dir."/contrib/util/menu/db_connection.php");
    ini_set("display_errors","1");
?>
