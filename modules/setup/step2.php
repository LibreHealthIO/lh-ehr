<?php
/**
 * This is the file denotes the second step (STEP 2) of the setup procedure. This scripts checks if your environment
 * is suitable for running LibreEHR.It checks for the server configuration, mysql and php versions, also checks for loaded extensions etc
 *
 * NB: This script prevents setup from following its normal course until the environment is suitable for installing the software.
 * This is done with the help of an upgrade button
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
require_once("classes/UserInfo.php");
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");


    //==========================
    // HANDLING FORM SUBMISSION
    //==========================


    // variable to get current step and task
    $_SESSION["step"] = $_POST["step"];
    $step = $_POST["step"];
    $task = $_POST["task"];

    if(isset($_GET["t"]) && $_GET["t"] = 2){
        //ok we can allow user to run the script
        $_SESSION["step"] = $_GET["t"]; // to get value coming from the terminal launch
        $step = $_GET["t"];
    }

    if(isset($_SESSION["step"]) && $step = 2){
        //ok we can allow user to run the script
    }
    else{
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


    // check for required extensions in php
    $extension_check_array = array(
        'curl',
        'imagick',
        'mbstring',
        'mysql',
        'cli',
        'gd',
        'gettext',
        'xsl',
        'mcrypt',
        'soap',
        'json',
        'zip',
        'ldap',
        'xml',
    );

//object to handle system information
    $userInfo =  new UserInfo();
    //get info
    $sys = $userInfo::get_os();
    $distro = $userInfo::get_distro();
    $os = $sys.', '.$distro['DISTRIB_ID'];
    $about_sys = $distro['uname_version_info'].' -'.$distro['DISTRIB_DESCRIPTION'].' -'.$distro['machine_type'];
    $server_software = $distro['SERVER_SOFTWARE'];
//    $distro = $distro['DISTRIB_DESCRIPTION'].' ' .$distro['DISTRIB_RELEASE'].' '. $distro['DISTRIB_CODENAME'];



?>

<div class="card">
    <p class="clearfix"></p>
    <p class="clearfix"></p>

    <?php
//    print_r($distro);
    echo "<h4>System Info & Server Configuration</h4>\n";
    drawSetupStep($step);
    ?>

    <h4 style="text-decoration: underline;">System Info</h4>

    <?php
       echo "
           <div class='pull-left'>
            Operating system:<span class='librehealth-color'> ".$os."</span><br>
            About system:<span class='librehealth-color'> ".$about_sys."</span><br>
            Server software:<span class='librehealth-color'> ".$server_software."</span><br>
           </div>
       ";

    ?>

    <?php
    echo "
           <div class='pull-right'>
           Browser:
           <img src='libs/images/".$userInfo::get_browser()."'/></div>
       ";
    ?>

    <p class="clearfix"></p>


      <?php

      echo "<div class='text-center'>
        <table class='table table-bordered table-hover'>
            <thead>
            <tr>
                <th class='text-center'>Software Package</th>
                <th class='text-center'>Current version</th>
                <th class='text-center'>Status &nbsp;<sub><small>(libreehr)</small></sub></th>
            </tr>
            </thead>
            <tbody>";

            $failphp = '';
            $failmysql = '';
            $pass = '';

            //checking for php version
            if(version_compare(phpversion(), '4.2.0', '<')) {
                $failphp .= '<td><strong>PHP</strong></td>';
                $failphp .= '<td>You need<strong> PHP 5.2.0</strong> (or greater;<strong>Current Version:'.phpversion().')</strong></td>';
                $failphp .= '<td><span class="fa fa-times red"></span></td>';
            }
            else {
                $pass .='<td><strong>PHP</strong></td>';
                $pass .='<td>
                            You have<strong> PHP 5.2.0</strong> (or greater; <strong>Current Version:'.phpversion().')</strong>
                           <p class="clearfix"></p>
                           <span data-toggle="collapse" data-target="#php-extensions" class="details pull-left">View Details <span class="fa fa-plus-square-o"></span></span><br>
                            <div class="pull-left">
                            <ul class="list-unstyled">
                           <div id="php-extensions" class="collapse text-left">
                                <ul class="list-unstyled">
                                    ';
                                foreach($extension_check_array as $extension) {
                                  if(!extension_loaded($extension)) {
                                          $pass .= '<li class="text-left"><span class="fa fa-mail-forward red"></span> You are missing the <strong><span class="red">'.$extension.'</span></strong> extension <small class="librehealth-color">'; if($extension){$pass.='(required for LIMS integration)';} $pass.='</small></li>';
                                  }
                                  else{   $pass .= '<li class="text-left"><span class="fa fa-mail-forward green"></span> You have the <strong><span class="green">'.$extension.'</span></strong> extension ';
                                  if($extension == 'mbstring'){$pass.='<small class="librehealth-color">(required for LIMS)';} $pass.='</small>';
                                  if($extension == 'mysql'){$pass.='<small class="librehealth-color">(required for LIMS, Updater integration)';} $pass.='</small>';
                                  if($extension == 'xml'){$pass.='<small class="librehealth-color">(required for LIMS, Updater integration)';} $pass.='</small>';
                                  if($extension == 'gd'){$pass.='<small class="librehealth-color">(required for LIMS, Updater integration)';} $pass.='</small>
                                                   </li>';
                                  }
                                    };
                                    $pass.='
                                </ul>
                            </div>
                            </ul>
                            </div>
                        </td>';
                $pass .='<td><span class="fa fa-check green"></span></td></tr>';
            }

            //checking for mysql version
            if(!ini_get('safe_mode')) {
//                $pass .='<td>Safe Mode is <strong>off</strong></td>';
                $version = find_SQL_Version();

                if(version_compare($version, '4.1.20', '<')) {
                    $failmysql .= '<td><strong>MySQL</strong></td>';
                    $failmysql .= '<td>You need<strong> MySQL 4.1.20</strong> (or greater; <strong>Current Version:.'.$version.')</strong></td>';
                    $failmysql .= '<td><span class="fa fa-times red"></span></td>';
                }
                else {
                    $pass .='<td><strong>MySQL</strong></td>';
                    $pass .='<td>You have<strong> MySQL 4.1.20</strong> (or greater; <strong>Current Version:'.$version.')</strong></td>';
                    $pass .='<td><span class="fa fa-check green"></span></td>';
                }
            }
            else {
//                $fail .= '<td>Safe Mode is <strong>on</strong></td>';
            }


              if($failphp) {
                  echo '<tr>'.$failphp.'</tr>';
              }
              if ($failmysql){
                  echo '<tr>'.$failmysql.'</tr>';
                  echo '<tr>'.$pass.'</tr>';
              } else {
                  echo '<tr>'.$pass.'</tr>';
              }

          echo " </tbody>
        </table>
    </div>";

         if($failphp || $failmysql) {
                echo '<p><strong>Your server does not meet the following requirements in order to install LibreEHR.</strong>';
                echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for LibreEHR:';
             echo "<p class='clearfix'></p>";
             echo "<p class='clearfix'></p>";
             echo '<form action="step1.php" method="post">
                            <div class="control-btn2">
                            <input type="hidden" value="1" name="step">
                            <button type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                            </div>
                            </form>
                    ';
                echo '    <form action="upgrade.php" method="post">
                        <div class="control-btn">
                        <input type="hidden" name="os_type" value='."$sys".'>
                        <button type="submit" class="upgradeBtn">
                            <i class="fa fa-cogs"></i> Upgrade
                        </button>
                        </div>
                        </form>
                ';
            } else {
                echo "<p class='clearfix'></p>";
                echo "<p class='clearfix'></p>";
                echo '<div class="alert-success text-center"><p><strong>Congratulations!</strong> Your server meets the requirements for LibreEHR.</p></div>';
             echo "<p class='clearfix'></p>";
             echo "<p class='clearfix'></p>";
             echo "<p class='clearfix'></p>";
                 echo '<form action="step1.php" method="post">
                            <div class="control-btn2">
                            <input type="hidden" value="1" name="step">
                            <button type="submit" class="controlBtn">
                            <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                            </div>
                            </form>
                    ';
    
             echo ' <form action="step3.php" method="post">
                    <div class="control-btn">
                     <input type="hidden" value="3" name="step">
                            <button type="submit" class="controlBtn">
                                Continue <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            </div>
                            </form>
                    ';
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
