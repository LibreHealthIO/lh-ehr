<?php
/**
 * This is the file is the users information and denotes the final steps of the setup procedure. This file is responsible for grabbing
 * the initial user's information and store in the database. Just a way to get additional information about user and initialize
 * users first facility (BY DEFAULT)  ;-).
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
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");


//==========================
// HANDLING FORM SUBMISSION
//==========================
//user info for configuring userinfo.php & parameters.php
$server = $_POST["server"];     $dbname = $_POST["dbname"];
$pass = $_POST["pass"];         $login = $_POST["login"];

//user's parameters for login screen
$site = $_POST["site"];     $iuserpass = $_POST["iuserpass"];
$iuser = $_POST["iuser"];  $iufname = $_POST["iufname"];   $iuname = $_POST["iuname"];

// variable to get current step and task
$step = $_POST["step"];
$task = $_POST["task"];
    if(isset($step) && $step = 8){
        //ok we can allow user to run the script
        if($prevstep == 7){

        }else{
            //from page userinfo
        }
    }else{
        header('location: index.php');
        session_destroy();
        // *** set new token
        $_SESSION['token'] = md5(uniqid(rand(), true));
    }
?>
    <div class="card">
                <p class="clearfix"></p>
        <h2><strong>User Information</strong></h2>
        <p class="alert alert-info">Please let us know more about you and your facility by helping us fill the form below NB: E-mail is for security bulletins.</p>
        <i>Fields with (<span class="arial librehealth-color"> *</span>) are mandatory</i>
        <p class="clearfix"></p>
        <h4 class="librehealth-color text-right"><small>(Please provide information about yourself below) </small>PERSONAL INFO</h4>
        <p class="clearfix"></p>
        <form id="userForm" method="POST" action="parameters.php" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"><label>First Name <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input value="<?php echo($iufname) ?>" name="iufname" placeholder="Enter First Name" type="text" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Last Name <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="text" value="<?php echo($iuname) ?>"  placeholder="Enter Last Name" name="iulname" class="form-control" required></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Middle Name:</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Enter Middle Name" name="iumname" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Email <span class="arial librehealth-color"> *</span> :</label></div>
                    <div class="col-md-9"><input type="email" value="" placeholder="Email address" name="iuemail" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Phone :</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Phone number" name="iuphone" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Profile Picture:</label></div>
                    <div class="col-md-3"><input type="file" id="iuprofilepic" name="iuprofilepic" class="form-control"></div>
                    <div class="col-md-3">
                        <div class="iu-pics hidden">
                            <span id="closeProfilePic" class="fa fa-times-circle-o pics-close"></span>
                            <img src=""  id="profile-img" class="img-responsive profile-pics" />
                        </div>
                    </div>
                </div>
                <p class="clearfix"></p>
                <p class="clearfix"></p>
                <p class="clearfix"></p>
                <h4 class="librehealth-color text-right"><small>(Please provide information about your default facility) </small>ABOUT FACILITY</h4>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Facilty Name <span class="arial librehealth-color"> *</span> : <span class="hint-symbol" data-toggle="tooltip" title="Name of facility you own. Can be a hospital, clinic or any medical unit.">[ ? ]</span></label></div>
                    <div class="col-md-4"><input type="text" value="" placeholder="e.g Medline Plus Clinic" name="facility" class="form-control" required></div>
                    <div class="col-md-2"><label>Legal Entity <span class="arial librehealth-color"> *</span> : <span class="hint-symbol" data-toggle="tooltip" title="Name of facility that is registered under for the government or any legal party">[ ? ]</span></label></div>
                    <div class="col-md-3"><input type="text" value="" placeholder="e.g Medline Plus Clinic" name="legal" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Facility Email :</label></div>
                    <div class="col-md-9"><input type="email" value="" placeholder="Email address" name="facEmail" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Facility Phone :</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Phone number" name="facPhone" class="form-control"></div>
                </div>

                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-2"><label>Address(street) :</label></div>
                    <div class="col-md-9"><input type="text" value="" placeholder="Address" name="facAddress" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
                <div class="row">
                    <div class="col-md-3"><label>Country/State/City/Zip:</label></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="Country" name="country" class="form-control"></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="State" name="state" class="form-control"></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="City" name="city" class="form-control"></div>
                    <div class="col-md-2"><input type="text" value="" placeholder="Zip" name="zip" class="form-control"></div>
                </div>
                <p class="clearfix"></p>
            </div>
            <?php

            echo " 
                   <input type='hidden' value='9' name='step'>
                   <input type='hidden' value='8' name='prevstep'>
                   <input type='hidden' value='$server' name='server' class='form-control'> 
                   <input type='hidden' value='$dbname' name='dbname' class='form-control'> 
                   <input type='hidden' value='$pass' name='pass' class='form-control'> 
                   <input type='hidden' value='$login' name='login' class='form-control'> 
                    <div class='control-btn'>
                      <button type='submit' class='controlBtn'>
                       Continue <i class='fa fa-arrow-circle-right'></i>
                </button>
                    </div>
                    ";
            ?>
        </form>

        <?php
        echo '<form action="step7.php" method="post">
                 <div class="control-btn2">
                    <input type="hidden" value="7" name="step">
                        <button id="backStep4" type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                        </button>
                 </div>
              </form>
                    ';
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
