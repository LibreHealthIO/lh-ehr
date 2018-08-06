<?php
/**
 * This is the file is the upgrade file step (UPGRADE) of the setup procedure. This file is responsible for grabbing the
 * default operating system from STEP 2 and displaying the right upgrade method to the user. Generates basic bash scripts
 * and shows screenshots to follow to guide users efficiently in case of linux and windows systems respectively
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


// variable to get current step and task
$os_type = strtolower($_POST["os_type"]);
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

    <h4>LibreHealth EHR Upgrade</h4>
    <p class="clearfix"></p>
    <div class="alert-info">
        <p>
            PLease follow the instructions given below to upgrade your system for an efficient functioning of the LibreHealth Software<br>
            <span class="fa fa-info-circle"></span> NB: This might require downloads/installations of softwares on your system!
        </p>
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>

            <?php
            switch ($os_type){

                case($os_type == "linux"): {

                    write_bashScript('linux');

                    echo '
                        <p>A bash script <span class="green">libreehr.sh</span> has been created at the root of your LibreEHR folder. You may want to <span class="red">delete</span> this file after executing it or
                        leave it for future use (in case of accidentally uninstalling some LibreEHR dependencies).
                        Ensure that you have permissions to run the script. Follow the instructions below to run the script for a successful upgrade.
                        </p>
                                                
                        <div class="alert alert-icon">
                        <span class="fa fa-info-circle"></span> NB: This will require some internet connection so make sure you have a good internet connection in order for you to 
                        upgrade your system.
                        </div>
                        <h4 class="librehealth-color"><span class="fa fa-terminal"></span>&nbsp; Open a terminal application</h4>
                        <p>Open a terminal at the root of your LibreEHR folder. To do this press the following keys <span class="badge">&nbsp; CTRL + ALT + T &nbsp;</span>
                    
                        <p class="clearfix"></p>
                        <h4 class="librehealth-color"><span class="fa fa-folder-open-o"></span>&nbsp; Browse to the root of your LibreEHR directory</h4>
                        <p>To do this type the following command in the terminal opened above 
                        <div class="well well-sm well-terminal">
                            <span class="green"><span class="fa fa-terminal"></span>user@user:$</span>&nbsp;&nbsp; sudo cd /var/www/html/LibreEHR
                        </div>
                        
                        
                        <p class="clearfix"></p>
                        <h4 class="librehealth-color"><span class="fa fa-file-code-o"></span>&nbsp; Change the script into an executable one (sudo command is optional)</h4>
                        <p>To do this type the following command in the terminal opened above 
                        <div class="well well-sm well-terminal">
                            <span class="green"><span class="fa fa-terminal"></span>user@user:/var/www/html/LibreEHR#</span>&nbsp;&nbsp; sudo chmod +x libreehr.sh
                        </div>
                        
                            <p class="clearfix"></p>
                        <h4 class="librehealth-color"><span class="fa fa-send-o"></span>&nbsp; Run/Execute the upgrade script (sudo command is optional)</h4>
                        <p>To do this type the following command in the terminal opened above 
                        <div class="well well-sm well-terminal">
                            <span class="green"><span class="fa fa-terminal"></span>user@user:/var/www/html/LibreEHR#</span>&nbsp;&nbsp; sudo ./libreehr.sh
                        </div>
                        
                        <p class="clearfix"></p>
                        <p class="text-center text-info">
                        Once done, you can click the return to setup button below inorder to resume your installation.
                        <br>
                        </p>
                    ';
                    break;

                }

                case($os_type == "windows"): {

                    echo '
                    <!-- Div carousel for windows slide -->
            <div class="text-center">
                <div class="owl-carousel owl-theme owl-loaded" id="slideImages">
                    <div class="owl-stage-outer">
                        <div class="owl-stage">
                            <div class="owl-item">
                                <img src="libs/images/slides/1.png">
                                <div class="owl-overlay">
                                    <h4>Download <a href="http://www.wampserver.com/en/" target="_blank" class="white owl-link">WAMPP</a>/<a href="https://www.apachefriends.org/download.html" target="_blank" class="white owl-link">XAMPP</a></h4>
                                    <p>
                                        Get the latest version of WAMPP/XAMPP from the following link. This software
                                        is a 3-in-1 software (Apache2, PHP, and MySQL) that enables the proper functioning of Librehealth EHR.
                                    </p>
                                </div>
                            </div>
                            <div class="owl-item">
                                <img src="libs/images/slides/2.png">
                                <div class="owl-overlay">
                                </div>
                            </div>
                            <div class="owl-item">
                                <img src="libs/images/slides/3.png">
                                <div class="owl-overlay">
                                </div>
                            </div>
                            <div class="owl-item">
                                <img src="libs/images/slides/4.png">
                                <div class="owl-overlay">
                                </div>
                            </div>
                            <div class="owl-item">
                                <img src="libs/images/slides/5.png">
                                <div class="owl-overlay">
                                </div>
                            </div>
                            <div class="owl-item">
                                <img src="libs/images/slides/6.png">
                                <div class="owl-overlay">
                                </div>
                            </div>
                            <div class="owl-item">
                                <img src="libs/images/slides/7.png">
                                <div class="owl-overlay">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="owl-controls">
                        <div class="owl-dots">
                        </div>
                    </div>
                </div>
            </div>
                    ';
                    break;
                }

                case($os_type == "mac"):{

                    echo '
                        <p>A bash script <span class="green">libreehr.sh</span> has been created at the root of your LibreEHR folder. You may want to <span class="red">delete</span> this file after executing it or
                        leave it for future use (in case of accidentally uninstalling some LibreEHR dependencies).
                        Ensure that you have permissions to run the script. Follow the instructions below to run the script for a successful upgrade.
                        </p>
                                                
                        <div class="alert alert-icon">
                        <span class="fa fa-info-circle"></span> NB: This will require some internet connection so make sure you have a good internet connection in order for you to 
                        upgrade your system.
                        </div>
                        <h4 class="librehealth-color"><span class="fa fa-terminal"></span>&nbsp; Open a terminal application</h4>
                        <p>Open a terminal at the root of your LibreEHR folder. To do this press the following keys <span class="badge">&nbsp; CTRL + ALT + T &nbsp;</span>
                    
                        <p class="clearfix"></p>
                        <h4 class="librehealth-color"><span class="fa fa-folder-open-o"></span>&nbsp; Browse to the root of your LibreEHR directory</h4>
                        <p>To do this type the following command in the terminal opened above 
                        <div class="well well-sm well-terminal">
                            <span class="green"><span class="fa fa-terminal"></span>user@user:$</span>&nbsp;&nbsp; sudo cd /var/www/html/LibreEHR
                        </div>
                        
                        
                        <p class="clearfix"></p>
                        <h4 class="librehealth-color"><span class="fa fa-file-code-o"></span>&nbsp; Change the script into an executable one (sudo command is optional)</h4>
                        <p>To do this type the following command in the terminal opened above 
                        <div class="well well-sm well-terminal">
                            <span class="green"><span class="fa fa-terminal"></span>user@user:/var/www/html/LibreEHR#</span>&nbsp;&nbsp; sudo chmod +x libreehr.sh
                        </div>
                        
                            <p class="clearfix"></p>
                        <h4 class="librehealth-color"><span class="fa fa-send-o"></span>&nbsp; Run/Execute the upgrade script (sudo command is optional)</h4>
                        <p>To do this type the following command in the terminal opened above 
                        <div class="well well-sm well-terminal">
                            <span class="green"><span class="fa fa-terminal"></span>user@user:/var/www/html/LibreEHR#</span>&nbsp;&nbsp; sudo ./libreehr.sh
                        </div>
                        
                        <p class="clearfix"></p>
                        <p class="text-center text-info">
                        Once done, you can click the return to setup button bellow inorder to resume your installation.
                        <br>
                        </p>
                    ';

                    break;
                }


            }


            ?>

    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <?php
    echo '<form action="step2.php" method="post">
                            <p class="clearfix"></p>
                            <p class="clearfix"></p>
                            <p class="clearfix"></p>
                            <div class="control-btn">
                            <input type="hidden" value="2" name="step">
                            <button type="submit" class="controlBtn">
                            <i class="fa fa-share-square-o"></i> Return to setup
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
