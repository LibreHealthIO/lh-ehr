<?php
/**
 * This is the file denotes the sixth step (STEP 6) of the setup procedure.
 * This file is responsible for the configuration of php in the system.
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
session_start();
require_once("includes/shared.inc.php");
require_once("includes/settings.inc.php");
require_once("classes/Database.php");
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");

//==========================
// HANDLING FORM SUBMISSION
// (getting variables passed from step 5)
//==========================

    //user info for configuring userinfo.php & parameters.php
    $server = $_POST["server"];     $dbname = $_POST["dbname"];
    $pass = $_POST["pass"];         $login = $_POST["login"];


    //user's parameters for login screen
    $site = $_POST["site"];         $iuserpass = $_POST["iuserpass"];
    $iufname = $_POST["iufname"];   $iuname = $_POST["iuname"];
    $iuser = $_POST["iuser"];


//get info abt current step
$step = $_POST["step"];
$task = $_POST["task"];

    if(isset($step) && $step = 6){
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
    echo "<div id='printStep'>";
    echo "<h4>Configuration of PHP...</h4>\n";
    drawSetupStep($step);
    ?>
    <p class="clearfix"></p>

<?php

    echo "We recommend making the following changes to your PHP installation, which can normally be done by editing the php.ini configuration file:\n";
    echo "<ul>";
        $gotFileFlag = 0;
        if (version_compare(PHP_VERSION, '5.2.4', '>=')) {
        $phpINIfile = php_ini_loaded_file();
        if ($phpINIfile) {
        echo "<li><span class='green'>Your php.ini file can be found at ".$phpINIfile."</font></li>\n";
        $gotFileFlag = 1;
        }
        }
        echo "<li>To ensure proper functioning of LibreHealth EHR you must make sure that settings in php.ini file include:
            <ul>
                <li>\"short_open_tag = <span class='blueblack'>On</span>\"</li>
                <li>\"display_errors = <span class='blueblack'>Off</span>\"</li>
                <li>\"max_execution_time\" set to at least <span class='blueblack'>600</span></li>
                <li>\"max_input_time\" set to at least <span class='blueblack'>600</span></li>
                <li>\"max_input_vars\" set to at least <span class='blueblack'>3000</span></li>
                <li>\"post_max_size\" set to at least <span class='blueblack'>32M</span></li>
                <li>\"memory_limit\" set to at least <span class='blueblack'>512M</span></li>
                <li>\"session.gc_maxlifetime\" set to <span class='blueblack'>14400</span></li>
                <li>\"error_reporting\" = <span class='blueblack'>E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE</span></li>
            </ul>";
            echo "
        <li>In order to take full advantage of the patient documents capability you must make sure that settings in php.ini file include:
            <ul>
                <li>\"file_uploads\" = <span class='blueblack'>On</span></li>
                <li>\"upload_max_filesize\" is appropriate for your use <span class='blueblack'>(32M seems good)</span></li>
                <li>\"upload_tmp_dir\" is set to a correct default value that will work on your system.</li>
            </ul>";

            echo "<li>To ensure proper functioning of LibreHealth EHR you must make sure that settings in MYSQL <span class='blueblack'>/etc/mysql/my.cnf</span> file include:
            <ul>
                <li>\"key_buffer_size\" set to 2% of your system's RAM (Less thatn 2% recommended) </li>
                <li>\"innodb_buffer_pool_size\" set to 70% of available RAM.</li>
            </ul>";

            if (!$gotFileFlag) {
            echo "<li>If you are having difficulty finding your php.ini file, then refer to the <a href='https://github.com/LibreHealthIO/LibreEHR/blob/master/INSTALL.md' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual for suggestions.</li>\n";
        }
        echo "</ul>";
        echo "</div>";
    echo "<div class='alert alert-success'>
            We recommend you print these instructions for future reference.<br>
            Next step will configure Apache web server.
            <div class='pull-right'>
            <button class='small btn-default printMe'><span class='fa fa-print'></span> Print</button>
            </div>
            </div>";

            echo "<p class='clearfix'></p>";
            echo "<p class='clearfix'></p>";
            echo "<form action='step5.php' method='post'>
                 <div class='control-btn2'>
                 <input type='hidden' value='5' name='step'>
                 <input type='hidden' value='php_gacl' name='stepholder'>
                 <input type='hidden' value='$server' name='server' class='form-control'> 
                 <input type='hidden' value='$dbname' name='dbname' class='form-control'> 
                 <input type='hidden' value='$pass' name='pass' class='form-control'> 
                 <input type='hidden' value='$login' name='login' class='form-control'>
                 <button type='submit' class='controlBtn'>
                 <i class='fa fa-arrow-circle-left'></i> Back
                 </button>
                 </div>
                 </form>
                     ";


                    echo " <form action='step7.php' method='post'>
                    <div class='control-btn'>
                     <input type='hidden' value='7' name='step'>
                     <input type='hidden' value='6' name='prevstep'>
                     <input type='hidden' value='$server' name='server' class='form-control'> 
                     <input type='hidden' value='$dbname' name='dbname' class='form-control'> 
                     <input type='hidden' value='$pass' name='pass' class='form-control'> 
                     <input type='hidden' value='$login' name='login' class='form-control'> 
                     <input type='hidden' name='site' value='$site'>
                     <input type='hidden' name='iuser' value='$iuser'>
                     <input type='hidden' name='iufname' value='$iufname'>
                     <input type='hidden' name='iuname' value='$iuname'>
                     <input type='hidden' name='iuserpass' value='$iuserpass'>
                            <button type='submit' class='controlBtn'>
                                Continue <i class='fa fa-arrow-circle-right'></i>
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

