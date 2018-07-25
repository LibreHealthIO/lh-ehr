<?php
/**
 * This is the file that is responsible for directing users on things they will encounter during the setup procedure
 *so that they are aware of what occurs at every stage
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

    session_start();
    // *** set new token for the setup process
    $_SESSION['token'] = md5(uniqid(rand(), true));
    require_once("includes/shared.inc.php");
    require_once("includes/settings.inc.php");
    require_once("includes/functions.inc.php");
    require_once("includes/header.inc.php");
    $task = $_POST["task"];
    
    if($task == 'annul'){
        session_destroy();
        write_configuration_file('localhost',3306,'libreehr','libreehr','libreehr',0);
        header('location: index.php');
        exit;
    }

?>






<div class="card">
<p class="clearfix"></p>
<p class="clearfix"></p>

    <h3 class="librehealth-color">Installation Instructions</h3>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
   <ul class="list-unstyled">
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;Before proceeding, be sure that you have a properly installed and configured MySQL server available, and a PHP configured webserver.</li>
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;Detailed installation instructions can be found in the <a href='https://github.com/LibreHealthIO/LibreEHR/blob/master/INSTALL.md' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual file.</li>
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;If you are upgrading from a previous version, do <strong>NOT</strong> use this script.  Please read the 'Upgrading' section found in the <a href='https://github.com/LibreHealthIO/lh-ehr/blob/master/INSTALL.md#upgrading' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual file.</li>
   </ul>

    <p class="clearfix"></p>
    <div class="alert-info">
        NOTE: It is worth reading the installation.MD file before clicking on the start button so as to
        get a smooth installation.
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <h4 class="librehealth-color text-center">HINTS</h4>

    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        This block represents an action was <strong>successfully</strong> done!
    </div>

    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        This block represents an action was <strong>unsuccessful</strong> or simply <strong>failed!</strong>
    </div>

    <div class="alert alert-warning alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        This block represents the fact that the user may proceed with the action but generally recommended
        to fulfil previous conditions
    </div>

    <div class="alert alert-info alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        This block represents an information pertaining to the user and user is required to <strong>go through</strong> it.
    </div>

<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>

    <form action="step1.php" method="POST">
        <input type="hidden" value="1" name="step">
        <div class="control-btn">
    <button type="submit" class="controlBtn">Start</button>
        </div>
    </form>
    <p class="clearfix"></p>
    <p class="clearfix"></p>


    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <form method="post">
        <input type="hidden" value="annul" name="task">
        <div class="cancel-btn">
            <button type="submit" class="cancelBtn">
                <i class="fa fa-times-circle-o"></i> Cancel
            </button>
        </div>
    </form>
</div>

<?php require_once("includes/footer.inc.php"); ?>
