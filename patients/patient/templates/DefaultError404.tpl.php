<?php
/**
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link http://librehealth.io
 *
 */

    $this->assign('title', xlt("LibreHealth EHR Patient Portal") . " | " . xlt("File Not Found"));
    $this->assign('nav','home');

    $this->display('_Header.tpl.php');
?>

<div class="container">

    <h1><?php echo xlt('Oh Snap!'); ?></h1>

    <!-- this is used by app.js for scraping -->
    <!-- ERROR The page you requested was not found /ERROR -->

    <p><?php echo xlt('The page you requested was not found. Please check that you typed the URL correctly.'); ?></p>

</div> <!-- /container -->

<?php
    $this->display('_Footer.tpl.php');
?>