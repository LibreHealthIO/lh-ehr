<?php

// list of LIMS and their respective directories

$lims_available = [
  'senaite',
];

// DB library to check whether LIMS is enabled or not
include_once("../../library/sql.inc");

$checkEnableQuery = sqlStatement("SELECT * from globals WHERE gl_name = 'lims_enabled'");
$enabled = sqlFetchArray($checkEnableQuery)['gl_value'];
if ($enabled != 1) {
  die('LIMS is not enabled. Please enable it through the global configuration menu');
} else {
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
}
