<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua <muarachmann@gmail.com>
 * Date: 5/21/18
 * Time: 2:32 AM
 */
?>

<?php require_once("includes/header.inc.php"); ?>
<div class="card">
<p class="clearfix"></p>
<p class="clearfix"></p>

   <ul class="list-unstyled">
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;Before proceeding, be sure that you have a properly installed and configured MySQL server available, and a PHP configured webserver.</li>
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;Detailed installation instructions can be found in the <a href='https://github.com/LibreHealthIO/LibreEHR/blob/master/INSTALL.md' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual file.</li>
       <li><i class="glyphicon glyphicon-send librehealth-color"></i> &nbsp;If you are upgrading from a previous version, do NOT use this script.  Please read the 'Upgrading' section found in the <a href='https://github.com/LibreHealthIO/LibreEHR/blob/master/INSTALL.md' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual file.</li>
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
<div class="control-btn">
    <form action="step1.php" method="POST">
        <input type="hidden" value="1" name="step">
    <button type="submit" class="button next-btn">NEXT</button>
    </form>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
</div>

<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
</div>

<?php require_once("includes/footer.inc.php"); ?>
