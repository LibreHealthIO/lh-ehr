<?php
session_start();
require_once("includes/shared.inc.php");
require_once("includes/settings.inc.php");
require_once("includes/functions.inc.php");
require_once("includes/header.inc.php");

?>
<?php

    //credentials for mysql connection to the db
    $login  = $_POST["login"];
    $host   = $_POST["server"];
    $db     = $_POST["dbname"];
    $pass   = $_POST["pass"];

    // variable to get current step and task
    $step = $_POST["step"];
    $prevstep = $_POST["prevstep"];
    $task = $_POST["task"];
    $theme = $_POST["theme"];
    $izi_timeout = $_POST["izi_timeout"];
    $izi_theme = "#".$_POST["izi_theme"];

    if(isset($step) && $step = 10){
        //ok we can allow user to run the script
        $con = mysqli_connect($host, $login, $pass, $db);
        // Check connection
        if (mysqli_connect_errno())
        {
            die( "<div class='alert alert-danger'>
                            Failed to connect to MySQL : " . mysqli_connect_error()."
                            <p class='black'>Please click on the back button to restart procedure to ensure proper access and installation</p>
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

            // gather default theme
        if(isset($_POST["theme"])){
            $css_header = "style_prism.css";
            if($theme == "theme1"){
                $primary_color = "#ffffff"; $primary_font_color = "#fa2323";
                $secondary_color = "#ffffff";      $secondary_font_color = "#3141f5";
            }
            if($theme == "theme2"){
                $primary_color = "#ffffff"; $primary_font_color = "#000000";
                $secondary_color = "#f0e1f7";      $secondary_font_color = "#000000";
            }
            if($theme == "theme3"){
                $primary_color = "#ffffff"; $primary_font_color = "#6587e9";
                $secondary_color = "#ffffff";      $secondary_font_color = "#fa89ab";

            }
        }
        $sql = "UPDATE globals SET gl_value = '$primary_color' where gl_name = 'primary_color'";
        mysqli_query($con,$sql);
        $sql = "UPDATE globals SET gl_value = '$primary_font_color' where gl_name = 'primary_font_color'";
        mysqli_query($con,$sql);
        $sql = "UPDATE globals SET gl_value = '$secondary_color' where gl_name = 'secondary_color'";
        mysqli_query($con,$sql);
        $sql = "UPDATE globals SET gl_value = '$secondary_font_color' where gl_name = 'secondary_font_color'";
        mysqli_query($con,$sql);
        $sql = "UPDATE globals SET gl_value = '$css_header' where gl_name = 'css_header'";
        mysqli_query($con,$sql);
        $sql = "INSERT INTO globals VALUES ('izi_timeout', 0, $izi_timeout), ('izi_theme', 0, $izi_theme)";
        mysqli_query($con,$sql);

        $con->close();

    }else{
        header('location: index.php');
        session_destroy();
        // *** set new token
        $_SESSION['token'] = md5(uniqid(rand(), true));
    }

?>


<div class="card">
    <div class="col-md-4 pull-right">
        <div class="balloon">
            <div><span class="bal"><i class="librehealth-color">☺</i></span></div>
            <div><span class="bal">E</span></div>
            <div><span class="bal">H</span></div>
            <div><span class="bal">R</span></div>
        </div>
    </div>
    <div id='printStep'>
    <div class="col-md-12 col-xs-12 col-sm-12 text-center">
        <h1 class="librehealth-color" style="font-family: Circle;font-size: 50px">
            Congratulations!!!
        </h1>
    </div>



    <p class="clearfix">
    <p class="clearfix">
    <div class="col-md-12">
    <h4 class="green" style="font-weight: bolder;">Congratulations! LibreHealth EHR is now installed.</h4>

    <ul>
        <li>Access controls (php-GACL) are installed for fine-grained security, and can be administered in
            LibreHealth EHR's admin->acl menu.</li>
        <li>Reviewing <?php echo $LIBRE_SITE_DIR; ?>/config.php is a good idea. This file
            contains some settings that you may want to change.</li>
        <li>There's much information and many extra tools bundled within the LibreHealth EHR installation directory.
            Please refer to LibreHealth EHR/Documentation. Many forms and other useful scripts can be found at LibreHealth EHR/contrib.</li>
        <li>To ensure a consistent look and feel through out the application using
            <a href='http://www.mozilla.org/products/firefox/'>Firefox</a> is recommended.</li>
        <li>The LibreHealth EHR project home page, documentation, and forums can be found at <a href = "https://forums.librehealth.io/c/7-support" target="_blank">LibreHealth EHR</a></li>
        <li>We pursue grants to help fund the future development of LibreHealth EHR.  Please contact us via our website to let us know how you use LibreHealth EHR.  This information is valuable for evaluating the benefits of LibreHealth EHR to the medical community worldwide, and serves as a great way for you to advise us on how to serve you better.</li>
    </ul>
        </div>
    </div>

        <p>
        We recommend you print these instructions for future reference.
            <button class='small btn-default printMe'><span class='fa fa-print'></span> Print</button>
    </p>
<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
    <div class="col-md-12 col-sm-12 text-center">
        <a href="./?site=<?php echo $site_id; ?>" class="btn controlBtn btn-group-lg">Click here to start using LibreHealth EHR.</a>
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
</div>


<?php

require_once("includes/footer.inc.php");

?>
