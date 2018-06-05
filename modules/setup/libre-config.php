<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua <muarachmanngmail.com>
 * Date: 5/21/18
 * Time: 11:50 AM
 */
?>

<?php
/*
 * LibreHealth configuration file for software
 */

$host	= 'localhost';
$port	= '3306';
$login	= 'libreehr';
$pass	= 'rachmanninov2016';
$dbase	= 'libreehr';

//Added ability to disable
//utf8 encoding - bm 05-2009
global $disable_utf8_flag;
$disable_utf8_flag = false;

$sqlconf = array();
global $sqlconf;
$sqlconf["host"]= $host;
$sqlconf["port"] = $port;
$sqlconf["login"] = $login;
$sqlconf["pass"] = $pass;
$sqlconf["dbase"] = $dbase;


//////////////////////////
//////////////////////////
//////////////////////////
//////DO NOT TOUCH THIS///
$config = 1; /////////////
//////////////////////////
//////////////////////////
//////////////////////////
?>

