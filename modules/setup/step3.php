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

    echo "<h4>Database Selection</h4>\n";
    drawSetupStep($step);
    ?>
    <p class="clearfix"></p>
    <div class="alert-info">
        <p>
            Now I need to know whether you want me to create the database on my own or if you have already created a database for me to use.  For me to create the database, you will need to supply the MySQL root password.<br>
            <span class='fa fa-info-circle'></span> NOTE: clicking on "Continue" may delete or cause damage to data on your system. Before you continue please backup your data.</span>
        </p>
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <h4 style="text-decoration: underline;">Select Option</h4>
    
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
            <input type='hidden' name='site' value='$site_id'>\n
            <label for='inst1'><input type='radio' id='inst1' name='inst' value='1' checked> Have setup wizard create the database</label><br>\n
            <label for='inst2'><input type='radio' id='inst2' name='inst' value='2'> I have already created the database</label><br>\n
            <br>\n
                <p class=\"clearfix\"></p>
                <p class=\"clearfix\"></p>

            <div class='control-btn'>
             <button type='submit' class='controlBtn'>
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
