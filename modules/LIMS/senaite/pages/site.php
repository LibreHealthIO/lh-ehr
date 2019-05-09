<?php
if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'setup';
}
require_once('./templates/pages/site/'.$sub.'.php');