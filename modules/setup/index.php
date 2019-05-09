<?php
/**
 * This is the file that will select the is responsible of detecting if the setup procedure has already been
 * if yes the redirects to the login page is no, it prompts for you to begin installation of software.
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Mua Laurent <muarachmann@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
?>

<?php
//............................................................
// handle case of previous installation (sqlconfig file check)
//............................................................
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// Set the site ID if required.  This must be done before any database
// access is attempted.

if (!empty($_GET['site']))
        $site_id = $_GET['site'];
    else if (is_dir("../../sites" . $_SERVER['HTTP_HOST']))
        $site_id = $_SERVER['HTTP_HOST'];
    else
        $site_id = 'default';

$rootpath = dirname(__FILE__)."/../../";
$sqlfilepath = dirname(__FILE__)."/../../sites/$site_id/sqlconf.php";
$loginpath = '../../interface/login/login.php?site='.$site_id;
$forceinstall = 'force_installation.php';

    if (empty($site_id) || preg_match('/[^A-Za-z0-9\\-.]/', $site_id))
        die("Site ID '".htmlspecialchars($site_id,ENT_NOQUOTES)."' contains invalid characters.");

    $config = 0;
    if (is_file($sqlfilepath)){
        require_once ($sqlfilepath);
    }

    if ($config == 1) {
        //give oportunity for the user to install the script again
        header("Location: $forceinstall?site=$site_id");
    } else {
        if ($site_id != 'default'){
            header("Location: index.php");
        }
    }


require_once ("includes/header.inc.php");

?>

    <div class="col-md-12">
        <div class="col-md-6">
            <div class="text-center">
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-10 col-sm-12 col-xs-12 pull-right">
                <div class="card">
                    <a class="github-fork-ribbon" href="https://github.com/LibreHealthIO/lh-ehr" target="_blank" data-ribbon="Fork me on GitHub" title="Fork me on GitHub">Fork me on GitHub</a>
                    <h2 style="font-weight: bolder">LibreHealth EHR</h2>
                    <div class="text-center">
                        <img src="libs/images/logo2.png" class="img-responsive center-block librehealth-logo">
                    </div>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>

                    <div class="text-left">
                       <p>
                           The LibreHealth EHR application is a clinically-focused electronic health
                           record (EHR) system designed to be both easy to use
                           “out of the box” and also customizable for use in a variety of
                           health care settings.
                       </p>
                    </div>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>
                    <p class="clearfix"></p>
                    <div class="text-center">
                        <a href="start_up.php" class="upgradeBtn">
                            Click Here to launch setup
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div


<?php require_once ("includes/footer.inc.php"); ?>