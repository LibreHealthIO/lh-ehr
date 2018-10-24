<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/12/18
 * Time: 9:39 AM
 */

require_once("includes/shared.inc.php");
require_once("includes/settings.inc.php");
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");

$site_id = $_GET["site"];

$rootpath = dirname(__FILE__)."/../../";
$sitespath = dirname(__FILE__)."/../../sites";
$sqlfilepath = dirname(__FILE__)."/../../sites/$site_id/sqlconf.php";
$loginpath = '../../interface/login/login.php?site='.$site_id;

$task = $_POST["task"];

if($task == 'reinstall'){
    session_destroy();
    rewrite_configuration_file(0);
    header('location: index.php');
    exit;
}

$confirmed_configfiles_array = array();
$installed_libreehr_sites = array();
$not_installed_libreehr_sites = array();
$config_files_array = array();

?>

    <div class="card">
        <h4 class="green">LibreHealth Successfully Installed</h4>

        <?php $iterator =  getSitesDirectories($sitespath);
            foreach($iterator as $file) {
                $filepath = getConfigFiles($file);
                array_push($config_files_array, $filepath);
                if(basename(dirname($filepath)) == ""){

                }else{
                   // echo "<p>" . basename(dirname($filepath)). "</p>";
                    array_push($confirmed_configfiles_array, $filepath);
                }

                }

        foreach($confirmed_configfiles_array as $key => $value)
        {
            if($value === ""){
                 //echo "<p class='red'>". $key."has the value ". $value. "</p>";
            }
            else{
                $configVariable = findString($value);
                if($configVariable == 1){
                    array_push($installed_libreehr_sites, $value);
                }else{
                    array_push($not_installed_libreehr_sites, $value);
                }

                // echo "<p class='librehealth-color'>". $key."has the value ". $value. "</p>";
            }
        }
         ?>
        <div class="col-md-8">
            <h5 class="green">Installed LHEHR sites</h5>
            <p>We detected an installation of LibreEHR in the following directory
                <span class="blueblack">LibreEHR/sites/
                <ul>
                <?php
                foreach ($installed_libreehr_sites as $value) {
                    echo "<li class='librehealth-color'>". basename(dirname($value)) ."</li>";
                }
                ?>
                </ul>
            </span>
            </p>
        </div>
        <div class="col-md-4">
            <h5 class="red">Non-Installed LHEHR sites</h5>
            <ul>
            <?php
            foreach ($not_installed_libreehr_sites as $value) {
                echo "<li class='red'>". basename(dirname($value)) ."</li>";
            }
            ?>
            </ul>
        </div>
        <p class="clearfix"></p>

        <p>
            If you wish to force re-installation, click on the Re-install EHR buton below. Else login
            into your EHR site.
        </p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>


        <div class="col-md-12">
            <div class="col-md-6 text-center">
                <img src="libs/images/logo.png" width="120" height="120" class="img-responsive center-block">
                <p class="clearfix"></p>
                <p class="clearfix"></p>
                <p class="clearfix"></p>
                <a href="<?php echo($loginpath); ?>" class="controlBtn">
                    LOGIN EHR
                </a>
            </div>
            <div class="col-md-6 text-center">
                <img src="libs/images/reinstall.gif" class="img-responsive center-block">
                <p class="clearfix"></p>
                <p class="clearfix"></p>
                <form method="POST">
                    <input type="hidden" value="reinstall" name="task">
                    <button type="submit" class="controlBtn">Re-Install EHR</button>
                </form>
            </div>
        </div>

        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
        <p class="clearfix"></p>
    </div>

<?php require_once ("includes/footer.inc.php"); ?>