<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua <muarachmann@gmail.com>
 * Date: 5/21/18
 * Time: 2:32 AM
 */
?>

<?php
require_once("includes/header.inc.php");
include ('classes/Installer.php');
include ('classes/Wizard.php');

$installer = new Installer(); // initializing the installer class
$wizard    = new Wizard(); // initializing the wizard class

// variable to get current step
    $step = $_POST["step"];
    global $OE_SITE_DIR; // The Installer sets this

    $docsDirectory = "$OE_SITE_DIR/documents";
    $billingDirectory = "$OE_SITE_DIR/edi";
    $billingDirectory2 = "$OE_SITE_DIR/era";

    $billingLogDirectory = "$OE_SITE_DIR/logs";
    $lettersDirectory = "$OE_SITE_DIR/letter_templates";
    $gaclWritableDirectory = dirname(__FILE__)."/../../gacl/admin/templates_c";

//$Libre_Get_Modules = dirname(__FILE__)."/modules/modules.config.php";  //TODO stub

//These are files and dir checked before install for
// correct permissions.
        if (is_dir($OE_SITE_DIR)) {
            //$Libre_Get_Modules could be added to the 'writable' array. Remove if unneeded when module registry methods are finalized
            $writableFileList = array($installer->conffile);
            $writableDirList = array($docsDirectory, $billingDirectory, $billingDirectory2, $lettersDirectory, $gaclWritableDirectory);
        }
        else {
            $writableFileList = array();
            $writableDirList = array($OE_SITES_BASE, $gaclWritableDirectory);
        }

?>

<div class="card">
    <p class="clearfix"></p>
    <p class="clearfix"></p>

    <?php

    $wizard->drawSetupStep($step);
    $checkPermissions = true;
    echo $gaclWritableDirectory;

    echo("<p class=\"clearfix\"></p>");

    if ($checkPermissions) {
        echo "<div class='text-center alert-info'>We will now ensure correct file and directory permissions before starting installation:</div><br>\n";
        echo "<FONT COLOR='green'>Ensuring following files are world-writable...</FONT><br>\n";
        $errorWritable = 0;
        foreach ($writableFileList as $tempFile) {
            if (is_writable($tempFile)) {
                echo "'".realpath($tempFile)."' file is <FONT COLOR='green'><b>ready</b></FONT>.<br>\n";
            }
            else {
                echo "<p><span class='red'>UNABLE</span> to open file <span class='red'>'".realpath($tempFile)."'</span> for writing.<br>\n";
                echo "(configure file permissions; see below for further instructions)</p>\n";
                $errorWritable = 1;
            }
        }
        if ($errorWritable) {
            echo "<p><FONT COLOR='red'>You can't proceed until all above files are ready (world-writable).</FONT><br>\n";
            echo "In linux, recommend changing file permissions with the 'chmod 666 filename' command.<br>\n";
            echo "Fix above file permissions and then click the 'Check Again' button to re-check files.<br>\n";
            echo "<FORM METHOD='POST'><INPUT TYPE='SUBMIT' VALUE='Check Again'></p>" .
                "<INPUT TYPE='HIDDEN' NAME='site' VALUE='$site_id'></FORM><br>\n";

        }

        echo "<br><FONT COLOR='green'>Ensuring following directories have proper permissions...</FONT><br>\n";
        $errorWritable = 0;
        foreach ($writableDirList as $tempDir) {
            if (is_writable($tempDir)) {
                echo "'".realpath($tempDir)."' directory is <FONT COLOR='green'><b>ready</b></FONT>.<br>\n";
            }
            else {
                echo "<p><FONT COLOR='red'>UNABLE</FONT> to open directory '".realpath($tempDir)."' for writing by web server.<br>\n";
                echo "(configure directory permissions; see below for further instructions)</p>\n";
                $errorWritable = 1;
            }
        }
        if ($errorWritable) {
            echo "<p><FONT COLOR='red'>You can't proceed until all directories are ready.</FONT><br>\n";
            echo "In linux, recommend changing owners of these directories to the web server. For example, in many linux OS's the web server user is 'apache', 'nobody', or 'www-data'. So if 'apache' were the web server user name, could use the command 'chown -R apache:apache directory_name' command.<br>\n";
            echo "Fix above directory permissions and then click the 'Check Again' button to re-check directories.<br>\n";
            echo "<FORM METHOD='POST'><INPUT TYPE='SUBMIT' VALUE='Check Again'></p>" .
                "<INPUT TYPE='HIDDEN' NAME='site' VALUE='$site_id'></FORM><br>\n";
        }

        echo "<br>All required files and directories have been verified. Click to continue installation.<br>\n";
    }

    ?>





    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <div class="control-btn">
        <a href="step1.php" class="button next-btn">Back</a>
        <a href="step1.php" class="button next-btn">Next</a>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
    </div>

    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
</div>

<?php
require_once("includes/footer.inc.php");
?>
