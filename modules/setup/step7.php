<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua
 * Date: 6/30/18
 * Time: 2:13 AM
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

        echo "The \"".preg_replace("/${site_id}/","*",realpath($docsDirectory))."\", \"".preg_replace("/${site_id}/","*",realpath($billingDirectory))."\" and \"".preg_replace("/${site_id}/","*",realpath($billingDirectory2))."\" directories contain patient information, and
        it is important to secure these directories.  This can be done by pasting the below to end of your apache configuration file:<br>
        &nbsp;&nbsp;&lt;Directory \"".realpath(dirname(__FILE__))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AllowOverride FileInfo<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".realpath(dirname(__FILE__))."/sites\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AllowOverride None<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".preg_replace("/${site_id}/","*",realpath($docsDirectory))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order deny,allow<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Deny from all<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".preg_replace("/${site_id}/","*",realpath($billingDirectory))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order deny,allow<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Deny from all<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br>
        &nbsp;&nbsp;&lt;Directory \"".preg_replace("/${site_id}/","*",realpath($billingDirectory2))."\"&gt;<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;order deny,allow<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Deny from all<br>
        &nbsp;&nbsp;&lt;/Directory&gt;<br><br>";

        echo "If you are having difficulty finding your apache configuration file, then refer to the <a href='https://github.com/LibreHealthIO/LibreEHR/blob/master/INSTALL.md' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual for suggestions.<br><br>\n";
        echo "<br>We recommend you print these instructions for future reference.<br><br>";
        echo "Click 'continue' for further instructions.";

        echo "<br><FORM METHOD='POST'>\n
        <INPUT TYPE='HIDDEN' NAME='state' VALUE='7'>\n
        <INPUT TYPE='HIDDEN' NAME='site' VALUE='$site_id'>\n
        <INPUT TYPE='HIDDEN' NAME='iuser' VALUE='$installer->iuser'>\n
        <INPUT TYPE='HIDDEN' NAME='iuserpass' VALUE='$installer->iuserpass'>\n
        <br>\n
        <INPUT TYPE='SUBMIT' VALUE='Continue'><br></FORM><br>\n";

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

