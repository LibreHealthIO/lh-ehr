<?php
  require_once("../globals.php");
  require_once("$srcdir/acl.inc");
  require_once("$srcdir/formdata.inc.php");
  require_once("$srcdir/options.inc.php");
  require_once("$srcdir/htmlspecialchars.inc.php");
  require_once("$srcdir/headers.inc.php");

  //select last activity of each user during present date
  $query = "SELECT `user`, `event`, `date`
            FROM `log`
            WHERE `date` IN (SELECT  max(`date`)
                             FROM `log`
                             WHERE DATE_FORMAT(`date`,'%Y-%M-%d') = DATE_FORMAT(now(),'%Y-%M-%d')
                             GROUP BY `user`)";
  $query .= " LIMIT 500";
  $res = sqlStatement($query);
?>

<html>
<head>
<?php
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","fancybox"));
  resolveFancyboxCompatibility();
?>

<title><?php echo xlt('Users Activity'); ?></title>

<!-- style tag moved into proper CSS file -->

</head>

<body class="body_top">
  <center>
    <h2><?php echo xlt('Last Activity Today'); ?></h2>
  </center>
  <hr>

  <!-- using addressbook_list styles-->
  <div id="addressbook_list">
  <table class="table">
    <tr class='head'>
      <td><?php echo xlt('User'); ?></td>
      <td><?php echo xlt('Activity'); ?></td>
      <td><?php echo xlt('Time'); ?></td>
    </tr>

  <?php
    $listEmpty = true;
    while ($row = sqlFetchArray($res)) {
      $listEmpty = false;
      $user = strtoupper($row['user']);
      if ($row['event'] == "login") {
        $activity = "LOGGED IN";
      } else if ($row['event'] == "logout") {
        $activity = "LOGGED OUT";
      }
      //to get time from date
      $time = substr($row['date'], strpos($row['date'], " "));
      $time = "AT " . $time;

      echo "<tr>\n";
      echo "  <td>" . text($user) . "</td>\n";
      echo "  <td>" . text($activity) . "</td>\n";
      echo "  <td>" . text($time) . "</td>\n";
      echo "</tr>\n";
    }
  ?>
  </table>

  <center>
    <h3>
      <?php
        if ($listEmpty) {
          echo "No Activity Today";
        }
      ?>
    </h3>
  </center>
  <hr>
  </div>

  <!--Adding Jquery Plugins-->

  <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
  <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
</body>
</html>
