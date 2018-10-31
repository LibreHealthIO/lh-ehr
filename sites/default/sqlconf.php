<?php
//  LibreEHR
//  MySQL Config

$host	= 'localhost';
$port	= '3306';
$login	= 'libreehr';
$pass	= 'libreehr';
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

//$config for default site files should never be changed.
$config = 0; 
