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
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");

//==========================
// HANDLING FORM SUBMISSION
//==========================


// variable to get current step and task
$step = $_POST["step"];
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

    <?php

    echo "<h4>Installing and Configuring Access Controls (php-GACL)...</h4>\n";
    drawSetupStep($step);
    ?>
    <p class="clearfix"></p>
    <h4 class="librehealth-color"></h4>


<?php
        echo "<h4 class='librehealth-color'>Configuration</h4>";
        $output = install_gacl($gaclSetupScript1);
        if(!$output){
            echo("install_gacl failed: unable to require gacl script ".$gaclSetupScript1."");
        }else{
            echo ($output);
        }

        $output = install_gacl($gaclSetupScript2);
        if(!$output){
            echo("install_gacl failed: unable to require gacl script ".$gaclSetupScript2."");
        }else{
            echo ($output);
        }
//    if ( ! $installer->install_gacl() ) {
//    echo $installer->error_message;
//    }
//    else {
//    // display the status information for gacl setup
//    echo $installer->debug_message;
//    }
//
//    echo "Gave the '$installer->iuser' user (password is '$installer->iuserpass') administrator access.<br><br>";
//
//    echo "Done installing and configuring access controls (php-GACL).<br>";
//    echo "Next step will configure PHP.";

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

