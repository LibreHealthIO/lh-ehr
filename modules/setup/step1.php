<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua <muarachmann@gmail.com>
 * Date: 5/21/18
 * Time: 2:32 AM
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

    if(isset($_SESSION["step"]) && $step = 1){
        //ok we can allow user to run the script
    }else{
        header('location: start_up.php');
        session_destroy();
        // *** set new token
        $_SESSION['token'] = md5(uniqid(rand(), true));
    }


    if($task == 'start'){
        session_destroy();
        write_configuration_file('localhost',3306,'libreehr','libreehr','libreehr',0);
        header('location: start_up.php');
        exit;
    }
    
    if($task == 'annul'){
        session_destroy();
        write_configuration_file('localhost',3306,'libreehr','libreehr','libreehr',0);
        header('location: index.php');
        exit;
    }

        if($task == 'back'){
            session_destroy();
            header('location: start_up.php');
            exit;
        }


        if (is_dir($LIBRE_SITE_DIR)) {
            //$Libre_Get_Modules could be added to the 'writable' array.
            $writableFileList = array($conffile);
            $writableDirList = array($docsDirectory, $billingDirectory, $billingDirectory2, $lettersDirectory, $gaclWritableDirectory);
        }
        else {
            $writableFileList = array();
            $writableDirList = array($LIBRE_SITES_BASE, $gaclWritableDirectory);
        }

?>

<div class="card">
    <p class="clearfix"></p>
    <p class="clearfix"></p>

    <?php

    echo "<h4>Write Access Configuration</h4>\n";

    drawSetupStep($step);
    $checkPermissions = true;

    echo("<p class=\"clearfix\"></p>");

    if ($checkPermissions) {
        echo "<div class='text-center alert-info'>We will now ensure correct file and directory permissions before starting installation:</div><br>\n";

        echo "<h4 class='green'>Ensuring following files are world-writable...</h4><br>\n";
        $errorWritable = 0;
        foreach ($writableFileList as $tempFile) {
            if (is_writable($tempFile)) {
                echo "<i class='fa fa-file-o librehealth-color'></i> -&nbsp; '".realpath($tempFile)."' file is <span class='green'><b>ready</b></span>.<br>\n";
            }
            else {
                echo "<p><span class='red'>UNABLE</span> to open file <span class='red'>'".realpath($tempFile)."'</span> for writing.<br>\n";
                echo "(configure file permissions; see below for further instructions)</p>\n";
                $errorWritable = 1;
            }
        }
        if ($errorWritable) {
            echo "<p><span class='red'>You can't proceed until all above files are ready (world-writable).</span><br>\n";
            echo "<div class='alert-warning'>In linux, recommend changing file permissions with the 'chmod 666 filename' command.<br>\n";
            echo "Fix above file permissions and then click the 'Check Again' button to re-check files.<br> <p class='clearfix'></p>
                     <p class='clearfix'></p></div>\n";
            echo "<form method='POST' action='step1.php'>
                     <p class='clearfix'></p>
                     <p class='clearfix'></p>
                     <p class='clearfix'></p>
                     <input type='hidden' value='1' name='step'>
                     <div class='control-btn'>
                     <input type='submit' VALUE='Check Again'>
                     </div>
                     </form> 
                     \n";
            $checkPermissions = false;

        }

        else{


            echo "<br><h4 class='green'>Ensuring following directories have proper permissions...</h4><br>\n";
            $errorWritable = 0;
            foreach ($writableDirList as $tempDir) {
                if (is_writable($tempDir)) {
                    echo "<i class='fa fa-file-o librehealth-color'></i> -&nbsp;'".realpath($tempDir)."' directory is <span class='green'><b>ready</b></span>.<br><br>\n";
                }
                else {
                    echo "<p><span class='red'>UNABLE</span> to open directory <span class='red'> '".realpath($tempDir)."' </span> for writing by web server.<br></p>\n";
                    $errorWritable = 1;
                }
            }
            if ($errorWritable) {
                echo "<p>(configure directory permissions; see below for further instructions)</p>\n";
                echo "<p><span class='red'>You can't proceed until all directories are ready.</span><br>\n";
                echo "<div class='alert-warning'>In linux, recommend changing owners of these directories to the web server. For example, in many linux OS's the web server user is 'apache', 'nobody', or 'www-data'. So if 'apache' were the web server user name, could use the command 'chown -R apache:apache directory_name' command.<br>\n";
                echo "Fix above directory permissions and then click the 'Check Again' button to re-check directories. <p class='clearfix'></p>
                     <p class='clearfix'></p></div><br>\n";
                echo "<form method='POST' action='step1.php'>
                     <p class='clearfix'></p>
                     <p class='clearfix'></p>
                     <input type='hidden' value='1' name='step'>
                     <div class='control-btn'>
                     <input type='submit' VALUE='Check Again'>
                     </div>
                     </form> 
                     \n";
                $checkPermissions = false;
            }


        }


        if($checkPermissions){
            echo "<div class='text-center'>
                <div class=\"alert-info\">
                +                All required files and directories have been verified. Click next to continue installation.
                +            </div><br>\n
                 </div>";
            echo "
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>
                
                <form method='post'>
                <input type='hidden' value='start' name='task'>
                       <div class='control-btn2'>
                           <button type='submit' class='controlBtn'>
                           <i class='fa fa-arrow-circle-o-left'></i> Back
                           </button>
                     </div>
                </form>";
            echo "
                <form method='post' action='step2.php'>
                <input type='hidden' value='2' name='step'>
                <input type='hidden' value='send' name='task'>
                     <div class='control-btn'>
                           <button type='submit' class='controlBtn'>
                          Next  <i class='fa fa-arrow-circle-o-right'></i>
                           </button>
                     </div>
                </form>
                    <p class=\"clearfix\"></p>
              
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>
            
            ";
        }
    }


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
