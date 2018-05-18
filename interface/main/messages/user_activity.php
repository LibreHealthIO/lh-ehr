<?php
  require_once("../../globals.php");
  require_once("$srcdir/acl.inc");
  require_once("$srcdir/formdata.inc.php");
  require_once("$srcdir/options.inc.php");
  require_once("$srcdir/htmlspecialchars.inc.php");
  require_once("$srcdir/headers.inc.php");

  //select users whose last activity during
  //present date is logging in successfully
  $query = "SELECT `user`
            FROM `log`
            WHERE `date` IN (SELECT  max(`date`)
                             FROM `log`
                             WHERE DATE_FORMAT(`date`,'%Y-%M-%d') = DATE_FORMAT(now(),'%Y-%M-%d')
                             GROUP BY `user`)
                  AND `event` = 'login' AND `success` = 1";
  $query .= " LIMIT 500";
  $res = sqlStatement($query);
  //creating list of active users
  $list_items = "";
  while ($row = sqlFetchArray($res)) {
    $userq = strtoupper($row['user']);
    $list_item = "<li>{$userq}</li>";
    $list_items .= $list_item;
  }
  $list_string = "<ul>{$list_items}</ul>";
  //output list HTML
  echo $list_string;
?>
