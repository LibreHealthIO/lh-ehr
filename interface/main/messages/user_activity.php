<?php
/*
 *  user_activity.php - processing file which gets a list of users currently logged in
 *
 * Copyright (C) 2018 Apoorv Choubey < theapoorvs1@gmail.com >
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Apoorv Choubey < theapoorvs1@gmail.com >
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

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
