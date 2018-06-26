<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua <muarachmann@gmail.com>
 * Date: 5/21/18
 * Time: 2:32 AM
 */
?>

<?php
    require_once("includes/shared.inc.php");
    require_once("includes/settings.inc.php");
    require_once("includes/functions.inc.php");
    require_once("includes/header.inc.php");
    
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

    <h4>Installation Instructions</h4>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
   <ul class="list-unstyled">
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;Before proceeding, be sure that you have a properly installed and configured MySQL server available, and a PHP configured webserver.</li>
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;Detailed installation instructions can be found in the <a href='https://github.com/LibreHealthIO/LibreEHR/blob/master/INSTALL.md' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual file.</li>
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;If you are upgrading from a previous version, do <strong>NOT</strong> use this script.  Please read the 'Upgrading' section found in the <a href='https://github.com/LibreHealthIO/lh-ehr/blob/master/INSTALL.md#upgrading' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual file.</li>
   </ul>

    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <div class="alert-info">
        NOTE: It is worth reading the installation.MD file before clicking on the start button so as to
        get a smooth installation.
    </div>


    <p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>

    <form action="step1.php" method="POST">
        <input type="hidden" value="1" name="step">
        <div class="control-btn">
    <button type="submit" class="controlBtn">Start</button>
        </div>
    </form>
    <p class="clearfix"></p>
    <p class="clearfix"></p>


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

<?php require_once("includes/footer.inc.php"); ?>
