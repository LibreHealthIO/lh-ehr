<?php

// the auto-loader will load in all the classes native to this application
require_once 'classes/autoloader.php';
spl_autoload_register('Autoloader::loadClasses');

// start a session different from the one in EHR
session_start();


// libraries used
include_once('../libraries/dependencies/functions.php');
include_once("../libraries/Guzzle/index.php");
include_once("../../../library/sql.inc");

$config = new Config;
$authenticationHeaders = $config->getAuthenticationHeaders();
$limsURL = $config->getLimsURL();

// easy CURL library
$client = new \GuzzleHttp\Client([
  'auth' => [ $authenticationHeaders[0], $authenticationHeaders[1]],
  'base_uri' => $limsURL
]);

//  action routing
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

if (!file_exists('pages/'.$action.'.php')) {
  $action = 'index';
}

if (!isset($_SESSION['lims_login']) || !isset($_SESSION['lims_user'])) {
  $action = 'login';
  require_once('pages/'.$action.'.php');
} else {
  require_once('templates/base/header.php');
  require_once('pages/'.$action.'.php');
  require_once('templates/base/footer.php');
}







?>