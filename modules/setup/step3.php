<?php
/**
 * This is the file denotes the third step (STEP 3) of the setup procedure. This file is responsible for grabbing user
 * decision about the database and optional site id selection. THis is neccessary for the installation of the software.
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
require_once("includes/shared.inc.php");
require_once("includes/settings.inc.php");
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");


    //==========================
    // HANDLING FORM SUBMISSION
    //==========================


    // variable to get current step and task
    $_SESSION["step"] = $_POST["step"];
    $step = $_POST["step"];
    $task = $_POST["task"];

    if(isset($_SESSION["step"]) && $step = 3){
        //ok we can allow user to run the script
    }else{
        header('location: index.php');
        session_destroy();
        // *** set new token
        $_SESSION['token'] = md5(uniqid(rand(), true));
    }

    
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

    <?php

    echo "<h4>Site ID & Database Selection</h4>\n";
    drawSetupStep($step);
    ?>
    <p class="clearfix"></p>
    <h4 class="librehealth-color">Optional Site ID Selection</h4>
    <p>
        Most LibreHealth EHR installations support only one site. If that is
        true for you then ignore the rest of this text and just select option for Database.
        Otherwise please enter a unique Site ID below.
    </p>
    <p>A Site ID is a short identifier with no spaces or special
        characters other than periods( . ) or dashes( _ , - ). It is case-sensitive and we
        suggest sticking to lower case letters for ease of use.
        If each site will have its own host/domain name, then use that name as the Site ID (e.g. www.example.com).
    </p>
    <p>The site ID is used to identify which site you will log in to.
        If it is a hostname then it is taken from the hostname in the URL.
        Otherwise you must append <span class="text-info">?site=siteid</span> to the URL used for
        logging in.
    </p>

    <div class="alert-info" style="padding: 10px;">
        <p> <span class="fa fa-info-circle"></span>
            It is permitted to leave <span class="text-info">default</span> as your site ID. This is the ID that will be used if it cannot otherwise be determined, but
            it is better for system upgrades to NOT use the default site ID.
        </p>
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>

    <div class="input-group siteID">
        <input id="siteID" type="text" class="form-control input-lg"  value="default" placeholder="enter siteID">
        <span class="input-group-addon controlBtn">Siteid</span>
    </div>


    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <div class="alert-danger">

    </div>
    <div id="errorSiteId" class="alert alert-danger alert-dismissable fade in hidden">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Site ID  contains invalid characters</strong>
    </div>

    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <div class="alert-info" style="padding: 10px;">
        <p>
            Now I need to know whether you want me to create the database on my own or if you have already created a database for me to use.  For me to create the database, you will need to supply the MySQL root password.<br>
            <span class='fa fa-info-circle'></span> NOTE: clicking on "Continue" may delete or cause damage to data on your system. Before you continue please backup your data.</span>
        </p>
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <h4 class="librehealth-color">Select Option</h4>
    
    <?php
        echo '<form action="step2.php" method="post">
                            <div class="control-btn2">
                            <input type="hidden" value="2" name="step">
                            <button type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                            </div>
                            </form>
                    ';
        echo "
        <form METHOD='POST' action='step4.php'>\n
            <input type='hidden' name='step' value='4'>\n
            <input type='hidden' name='site' id='site' value='default'>\n
            <label for='inst1'><input type='radio' id='inst1' name='inst' value='1' checked> Have setup wizard create the database</label><br>\n
            <label for='inst2'><input type='radio' id='inst2' name='inst' value='2'> I have already created the database</label><br>\n
            <br>\n
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>

            <div class='control-btn'>
             <button type='submit' class='controlBtn' id='submitStep3'>
             Continue  <i class='fa fa-arrow-circle-right'></i>
             </button>
             </div>
             </form>
        ";
    ?>
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

<?php
require_once("includes/footer.inc.php");
?>
