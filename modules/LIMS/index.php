<?php

// libraries used
require_once 'libraries/Guzzle/index.php';

// easy CURL library
$client = new \GuzzleHttp\Client();



// list of LIMS and their respective directories

$lims_available = [
  'senaite',
];


$lims = isset ($_GET['lims'] ) ? $_GET['lims'] : null;

if ($lims) {
  if (in_array($lims, $lims_available)) {
    $urlPrepend = $lims;
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    header('location: '. $lims.'/index.php?action='.$action);
  } else {
    die('LIMS not found!');
  }
} else {
  die("LIMS not specified");
}
