<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/17/18
 * Time: 9:35 AM
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

    //credentials to be inserted into the db about user
    $iufname           =  $_POST["iufname"];
    $iulname           =  $_POST["iulname"];
    $iumname           =  $_POST["iumname"];
    $iuemail           =  $_POST["iuemail"];
    $iuphone           =  $_POST["iuphone"];
    //$iuprofilepic           =  $_POST["iuprofilepic"];

    //credentials for mysql connection to the db
    $login  = $_POST["login"];
    $host   = $_POST["server"];
    $db     = $_POST["dbname"];
    $pass   = $_POST["pass"];

    //credentials to be inserted into the db (first facility)
    $facility  = $_POST["facility"];    $legal       =  $_POST["legal"];
    $see_auth  = $_POST["see_auth"];    $facAddress  = $_POST["facAddress"];
    $facPhone  = $_POST["facPhone"];    $facEmail    = $_POST["facEmail"];
    $country   = $_POST["country"];     $state       = $_POST["country"];
    $zip       = $_POST["zip"];         $city        = $_POST["city"];

// variable to get current step and task
$step = $_POST["step"];
$prevstep = $_POST["prevstep"];
$task = $_POST["task"];

    if(isset($step) && $step = 9){
        //ok we can allow user to run the script

    }else{
        header('location: index.php');
        session_destroy();
        // *** set new token
        $_SESSION['token'] = md5(uniqid(rand(), true));
    }

    ?>
    <div class="card">
        <div id="demo-iframe"></div>
        <h2><strong>Parameter Settings</strong></h2>
        <?php
            if($prevstep == 8){

                $con = mysqli_connect($host, $login, $pass, $db);
                // Check connection
                if (mysqli_connect_errno())
                {
                    die( "<div class='alert alert-danger'>
                            Failed to connect to MySQL : " . mysqli_connect_error()."
                            <p class='black'>Please click on the back button to restart procedure to ensure proper access</p>
                        </div>
                        <p class='clearfix'></p>
                        <p class='clearfix'></p>
                        <p class='clearfix'></p>
                        <form action='step3.php' method='post'>
                             <div class='cancel-btn'>
                                <input type='hidden' value='3' name='step'>
                                    <button id='backStep4' type='submit' class='controlBtn'>
                                        <i class='fa fa-arrow-circle-left'></i> Back
                                    </button>
                             </div>
                          </form>
                        "

                        );
                }
                $sql = "UPDATE users SET fname = '$iufname', lname= '$iulname', mname= '$iumname', email= '$iuemail', phone= '$iuphone'  WHERE id = 1";
                mysqli_query($con,$sql);


            }else{
                //from page userinfo
            }
        ?>
        <div class="form-group">
            <div class="row">
                <div class="col-md-2"><label>Notification time :</label></div>
                <div class="col-md-4"><input value="5" name="iufname" placeholder="Notification time(in seconds)" type="text" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">This is the time (measured in seconds) taken to display a notification. Click <span class="badge badge-inverse izi-demo-toast">here</span> to try to have a preview</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Default Theme:</label></div>
                <div class="col-md-4">
                    <div class="col-md-4">
                    <input name="theme" type="radio" class="form-control" checked>
                        <br>
                        <a class="test-popup-link" href="libs/images/test.png" title="Theme 3">
                            <img src="libs/images/test.png">
                        </a>
                    </div>
                    <div class="col-md-4">
                    <input name="theme" type="radio" class="form-control">
                        <br>
                        <a class="test-popup-link" href="libs/images/test.png" title="Theme 3">
                            <img src="libs/images/test.png">
                        </a>
                    </div>
                    <div class="col-md-4">
                    <input name="theme" type="radio" class="form-control">
                        <br>
                        <a class="test-popup-link" href="libs/images/test.png" title="Theme 3">
                            <img src="libs/images/test.png">
                        </a>
                    </div>
                </div>
                <div class="col-md-6"><p class="help-block">This is the default theme that would be installed in the software. Click on one of the thumbnails to have a preview</p></div>
            </div>


            <p class="clearfix"></p>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Modal Theme: </label></div>
                <div class="col-md-4">
                    <input type="text" value="88A0B9" id="modalColor" name="iumname" class="form-control jscolor">
                </div>
                <div class="col-md-6"><p class="help-block">This is the time (measured in seconds) taken to display a notification. Click <span class="badge badge-inverse izi-demo-modal">here</span> to try to have a preview</p></div>
            </div>
        </div>


    </div>


<?php
require_once("includes/footer.inc.php");
?>
