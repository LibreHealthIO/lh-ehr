<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'analysis';
}

switch($sub) {

  case 'categories':


  break;




}



require_once('./templates/pages/site/analysis/'.$sub.'.php');