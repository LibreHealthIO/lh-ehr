<?php
  // FACILITIES
  // From Michael Brinson 2006-09-19:
  if (isset($_POST['pc_username'])) $_SESSION['pc_username'] = $_POST['pc_username'];

  //(CHEMED) Facility filter
  if (isset($_POST['all_users'])) $_SESSION['pc_username'] = $_POST['all_users'];

  // bug fix to allow default selection of a provider
  // added 'if..POST' check -- JRM
  if (isset($_REQUEST['pc_username']) && $_REQUEST['pc_username']) $_SESSION['pc_username'] = $_REQUEST['pc_username'];

  // (CHEMED) Get the width of vieport
  if (isset($_GET['framewidth'])) $_SESSION['pc_framewidth'] = $_GET['framewidth'];

  // FACILITY FILTERING (lemonsoftware) (CHEMED)
  $_SESSION['pc_facility'] = 0;

  /*********************************************************************
  if ($_POST['pc_facility'])  $_SESSION['pc_facility'] = $_POST['pc_facility'];
  *********************************************************************/
  if (isset($_COOKIE['pc_facility']) && $GLOBALS['set_facility_cookie']) $_SESSION['pc_facility'] = $_COOKIE['pc_facility'];
  // override the cookie if the user doesn't have access to that facility any more
  if ($_SESSION['userauthorized'] != 1 && $GLOBALS['restrict_user_facility']) { 
    $facilities = getUserFacilities($_SESSION['authId']);
    // use the first facility the user has access to, unless...
    $_SESSION['pc_facility'] = $facilities[0]['id']; 
    // if the cookie is in the users' facilities, use that.
    foreach ($facilities as $facrow) {
      if (($facrow['id'] == $_COOKIE['pc_facility']) && $GLOBALS['set_facility_cookie'])
        $_SESSION['pc_facility'] = $_COOKIE['pc_facility'];
    }
  }
  if (isset($_POST['pc_facility']))  {
    $_SESSION['pc_facility'] = $_POST['pc_facility'];
  }
  /********************************************************************/

  if (isset($_GET['pc_facility']))  $_SESSION['pc_facility'] = $_GET['pc_facility'];
  if ($GLOBALS['set_facility_cookie'] && ($_SESSION['pc_facility'] > 0)) setcookie("pc_facility", $_SESSION['pc_facility'], time() + (3600 * 365));

  // Simplifying by just using request variable instead of checking for both post and get - KHY
  if (isset($_REQUEST['viewtype'])) $_SESSION['viewtype'] = $_REQUEST['viewtype'];
?>
