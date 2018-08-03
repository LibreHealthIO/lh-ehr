<?php
/**
 * This is the file denotes the fifth step (STEP 5) of the setup procedure. This file is responsible for Installing and
 * Configuring Access Controls (php-GACL)... necessary for running the LibreEHR software smoothly.
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
    //==========================


    $step = $_POST["step"];
    $task = $_POST["task"];
    $stephold = $_POST["stepholder"];

    $installer = new Database($_POST);

if(isset($step) && $step = 5){
        //ok we can allow user to run the script
        //checking if it is from script 6 or 4
        if(isset($stephold) && $stephold == 4){
            // from page 4

        }else{
            //from page 6

        }
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

    echo "<h4>Installing and Configuring Access Controls (php-GACL)...</h4> \n";
    drawSetupStep($step);
    ?>
    <p class="clearfix"></p>

<?php

        switch ($stephold){

            case($stephold == "php_gacl"):{
                if ( ! $installer->install_gacl() ) {
                    echo $installer->error_message;

                    echo "<p class='clearfix'></p>";
                    echo "<p class='clearfix'></p>";
                    echo '<form action="step4.php" method="post">
                            <div class="control-btn2">
                            <input type="hidden" value="4" name="step">
                             <input type=\'hidden\' value=\'$installer->server\' name=\'server\' class=\'form-control\'> 
                             <input type=\'hidden\' value=\'$installer->dbname\' name=\'dbname\' class=\'form-control\'> 
                             <input type=\'hidden\' value=\'$installer->pass\' name=\'pass\' class=\'form-control\'> 
                                <input type=\'hidden\' value=\'$installer->login\' name=\'login\' class=\'form-control\'> 
                            <button type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                            </div>
                            </form>
                    ';
                }
                else {
                    // display the status information for gacl setup
                    echo $installer->debug_message;
                    echo "Gave the <span class='green'>'$installer->iuser'</span> user (password is <span class='green'>'$installer->iuserpass'</span>) administrator access<span data-html='true' data-toggle='tooltip' data-placement='right' title='It is recommended to <span class=\"fa fa-edit\"></span> write these down somewhere safe and easy to retrieve.' class='password_note'>.</span><br><br>";

                    echo "<p class='clearfix'></p>";
                    echo "<div class='alert alert-success'>
                    Done installing and configuring access controls (php-GACL).<br>
                    Next step will configure PHP.
                    <br>
                  ";
                    echo "<p class='clearfix'></p>";
                    echo "<p class='clearfix'></p>";
                    echo '<form action="step4.php" method="post">
                            <div class="control-btn2">
                            <input type="hidden" value="4" name="step">
                             <input type=\'hidden\' value=\'$installer->server\' name=\'server\' class=\'form-control\'> 
                             <input type=\'hidden\' value=\'$installer->dbname\' name=\'dbname\' class=\'form-control\'> 
                             <input type=\'hidden\' value=\'$installer->pass\' name=\'pass\' class=\'form-control\'> 
                                <input type=\'hidden\' value=\'$installer->login\' name=\'login\' class=\'form-control\'> 
                            <button type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                            </div>
                            </form>
                    ';

                    echo " <form action='step6.php' method='post'>
                    <div class='control-btn'>
                     <input type='hidden' value='6' name='step'>
                     <input type='hidden' value='$installer->server' name='server' class='form-control'> 
                     <input type='hidden' value='$installer->dbname' name='dbname' class='form-control'> 
                     <input type='hidden' value='$installer->pass' name='pass' class='form-control'> 
                     <input type='hidden' value='$installer->login' name='login' class='form-control'> 
                     <input type='hidden' name='site' value='$installer->site'>\n
                     <input type='hidden' name='iuser' value='$installer->iuser'>\n
                     <input type='hidden' name='iufname' value='$installer->iufname'>\n
                     <input type='hidden' name='iuname' value='$installer->iuname'>\n
                     <input type='hidden' name='iuserpass' value='$installer->iuserpass'>\n
                            <button type='submit' class='controlBtn'>
                                Continue <i class='fa fa-arrow-circle-right'></i>
                            </button>
                            </div>
                            </form>
                    ";
                }
                break;
            }

            case($stephold == "user_config"):{
                echo "wow here";

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

