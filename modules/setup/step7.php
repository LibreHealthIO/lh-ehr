<?php
/**
 * This is the file denotes the seventh step (STEP 7) of the setup procedure.
 * This file is responsible for the Configuration of Apache web server...
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
include_once("includes/settings.inc.php");
require_once("classes/Database.php");
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");

//==========================
// HANDLING FORM SUBMISSION
//==========================


// variable to get current step and task
$_SESSION["step"] = $_POST["step"];
$step = $_POST["step"];
$task = $_POST["task"];
$stephold = $_POST["stepholder"];

    $installer = new Database($_POST);

//
//if(isset($_SESSION["step"]) && $step = 5){
//    //ok we can allow user to run the script
//    //checking if it is from script 6 or 4
//    if(!isset($_POST['installer_var'])){
//        // from page 4
//        $installer = new Database($_POST);
//        $installerVar = serialize($_POST);
//    }else{
//        //from page 6
//        $passed_array = unserialize($_POST["installer_var"]);
//        $installer = new Database($passed_array);
//        // parse the whole post array unto the next view
//        $installerVar = serialize($passed_array);
//
//
//    }
//}else{
//    header('location: start_up.php');
//    session_destroy();
//    // *** set new token
//    $_SESSION['token'] = md5(uniqid(rand(), true));
//}

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

    echo "<h4>Configuration of Apache web server...</h4>\n";
    drawSetupStep($step);
    ?>
    <p class="clearfix"></p>


<?php

        echo "<div>
                $installer->dbname
                <p>
               The following directories below contain patient information, and it is important to secure these directories.
                </p>
            <ul>
                <li><span class='blueblack'>\"".preg_replace("/${site_id}/","",realpath($docsDirectory))."\"</span>.</li>
                <li><span class='blueblack'>\"".preg_replace("/${site_id}/","",realpath($billingDirectory))."\"</span>. </li>
                <li><span class='blueblack'>\"".preg_replace("/${site_id}/","",realpath($billingDirectory2))."\"</span>.</li>
            </ul>
                  
             <p><span class='librehealth-color'>This can be done by pasting the below to end of your apache configuration file:</span></p>
        <div class=\"well well-sm well-terminal\">
        &nbsp;&nbsp;&lt;Directory \"".realpath(dirname(__FILE__))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AllowOverride FileInfo<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".realpath(dirname(__FILE__))."/sites\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AllowOverride None<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".preg_replace("/${site_id}/","",realpath($docsDirectory))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order deny,allow<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Deny from all<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".preg_replace("/${site_id}/","",realpath($billingDirectory))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order deny,allow<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Deny from all<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".preg_replace("/${site_id}/","",realpath($billingDirectory2))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order deny,allow<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Deny from all<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br><br>
        </div>
        </div>
        ";

        echo "<div class='alert alert-info text-center'>
            <span class='fa fa-info-circle'></span> <span class='black'>If you are having difficulty finding your apache configuration file, then refer to the</span> 
            <a href='https://github.com/LibreHealthIO/LibreEHR/blob/master/INSTALL.md' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> 
            <span class='black'>manual for suggestions.</span>
            <br><br><span class='black'>We recommend you print these instructions for future reference.</span>
            </div>
            ";

                echo "<p class='clearfix'></p>";
                echo "<p class='clearfix'></p>";
                echo "<form action='step5.php' method='post'>
                     <div class='control-btn2'>
                     <input type='hidden' value='6' name='step'>
                     <input type='hidden' value='php_gacl' name='stepholder'>
                     <input type='hidden' name='installer_var'  value='$passed_array'>
                     <button type='submit' class='controlBtn'>
                     <i class='fa fa-arrow-circle-left'></i> Back
                     </button>
                     </div>
                 </form>
                     ";

                echo " <form action='userinfo.php' method='post'>
                            <div class='control-btn'>
                             <input type='hidden' value='6' name='step'>
                             <input type='hidden' name='installer_var'  value='$passed_array'>
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

