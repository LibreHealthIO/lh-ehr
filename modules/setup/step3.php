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

// check for required extensions in php
$extension_check_array = array(
    'mysql',
    'cli',
    'gd',
    'gettext',
    'xsl',
    'curl',
    'mcrypt',
    'soap',
    'json',
    'mbstring',
    'zip',
    'ldap',
    'xml',
);



//==========================
// HANDLING FORM SUBMISSION
//==========================


// variable to get current step and task
$step = $_POST["step"];
$task = $_POST["task"];

?>

<div class="card">
    <p class="clearfix"></p>
    <p class="clearfix"></p>

    <?php

    echo "<h4>Librehealth EHR Versioning System</h4>\n";
    drawSetupStep($step);
    ?>

    <h4 style="text-decoration: underline;">System Info</h4>

    <?php
    $process_running = shell_exec('apt-get install php7.0-curl'); //give all php processes running in your server
    echo($process_running); //gives an array of processes


    $result = liveExecuteCommand('./test.sh');

    if($result['exit_status'] === 0){
        // do something if command execution succeeds
        echo $result;
    } else {
        // do something on failure
        echo "failed";
    }
    ?>

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

    $fail = '';
    $pass = '';

    //checking for php version
    if(version_compare(phpversion(), '5.2.0', '<')) {
        $fail .= '<td><strong>PHP</strong></td>';
        $fail .= '<td>You need<strong> PHP 5.2.0</strong> (or greater;<strong>Current Version:'.phpversion().')</strong></td>';
        $fail .= '<td><span class="fa fa-times red"></span></td>';
    }
    else {
        $pass .='<td><strong>PHP</strong></td>';
        $pass .='<td>You have<strong> PHP 5.2.0</strong> (or greater; <strong>Current Version:'.phpversion().')</strong></td>';
        $pass .='<td><span class="fa fa-check green"></span></td>';
    }

    //checking for mysql version
    if(!ini_get('safe_mode')) {
//                $pass .='<td>Safe Mode is <strong>off</strong></td>';
        preg_match('/[0-9]\.[0-9]+\.[0-9]+/', shell_exec('mysql -V'), $version);

        if(version_compare($version[0], '4.1.20', '<')) {
            $fail .= '<td><strong>MySQL</strong></td>';
            $fail .= '<td>You need<strong> MySQL 4.1.20</strong> (or greater; <strong>Current Version:.'.$version[1].')</strong></td>';
            $fail .= '<td><span class="fa fa-times red"></span></td>';
        }
        else {
            $pass .='<td><strong>MySQL</strong></td>';
            $pass .='<td>You have<strong> MySQL 4.1.20</strong> (or greater; <strong>Current Version:'.$version[1].')</strong></td>';
            $pass .='<td><span class="fa fa-check green"></span></td>';
        }
    }
    else {
//                $fail .= '<td>Safe Mode is <strong>on</strong></td>';
    }

    if($fail) {
        echo '<tr>'.$fail.'</tr></p>';
        echo 'The following requirements were successfully met:';
        echo '<tr>'.$pass.'</tr>';
    } else {
        echo '<tr>'.$pass.'</tr>';
    }

    echo " </tbody>
        </table>
    </div>";

    if($fail) {
        echo '<p><strong>Your server does not meet the following requirements in order to install LibreEHR.</strong>';
        echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for Magento:';
    } else {
        echo '<p><strong>Congratulations!</strong> Your server meets the requirements for LibreEHR.</p>';
    }

    ?>


</div>

<?php
require_once("includes/footer.inc.php");
?>
