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
require_once("includes/settings.inc.php");
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


if(isset($_SESSION["step"]) && $step = 5){
        //ok we can allow user to run the script
        //checking if it is from script 6 or 4
        if(!isset($_POST['installer_var'])){
            // from page 4
            $installer = new Database($_POST);
            $installerVar = serialize($_POST);
        }else{
            //from page 6
            $passed_array = unserialize($_POST["installer_var"]);
            $installer = new Database($passed_array);
            // parse the whole post array unto the next view
            $installerVar = serialize($passed_array);


        }
    }else{
        header('location: start_up.php');
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

    echo "<h4>Installing and Configuring Access Controls (php-GACL)...</h4>\n";
    drawSetupStep($step);
    ?>
    <p class="clearfix"></p>

<?php

        switch ($stephold){

            case($stephold == "php_gacl"):{
                if ( ! $installer->install_gacl() ) {
                    echo $installer->error_message;
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
                            <button type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                            </div>
                            </form>
                    ';

                    echo " <form action='step6.php' method='post'>
                    <div class='control-btn'>
                     <input type='hidden' value='6' name='step'>
                     <input type='hidden' name='installer_var'  value='$installerVar'>
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

