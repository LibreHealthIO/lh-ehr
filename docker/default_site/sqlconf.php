<?php
//  LibreEHR
//  MySQL Config

$host	= 'db';
$port	= '3306';
$login	= 'libreehr';
$pass	= 's3cret';
$dbase	= 'libreehr';

global $disable_utf8_flag;
$disable_utf8_flag = false;

$sqlconf = array();
global $sqlconf;
$sqlconf["host"]= $host;
$sqlconf["port"] = $port;
$sqlconf["login"] = $login;
$sqlconf["pass"] = $pass;
$sqlconf["dbase"] = $dbase;
/////////WARNING!/////////
//Setting $config to = 0//
// will break this site //
//and cause SETUP to run//
$config = 1; /////////////
//////////////////////////
//////////////////////////
//////////////////////////
?>
