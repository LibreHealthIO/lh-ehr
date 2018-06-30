<?php

// sub-action routing
$action = isset($_GET['sub']) ? $_GET['sub'] : 'index';



switch($action) {



  case 'index':
  default:

  require_once './templates/pages/user/index.php';
}
