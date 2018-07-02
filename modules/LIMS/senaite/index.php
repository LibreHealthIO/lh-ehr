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
$limsURL = $config->getLimsURL();



$parser = new \GuzzleHttp\Cookie\SetCookie;
$jar = new \GuzzleHttp\Cookie\SessionCookieJar('session_id', true);

// retrieve login cookie from session and put it in a jar to be used by 
// all the API requests
if (isset($_SESSION['login_cookie']) && ($_SESSION['login_cookie'] != null)) {
  $cookie = $parser->fromString($_SESSION['login_cookie']);
  $jar->setCookie($cookie);
  unset($_SESSION['login_cookie']);
}

// easy CURL library
$client = new \GuzzleHttp\Client([
  'base_uri' => $limsURL,
  'cookies' => $jar
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