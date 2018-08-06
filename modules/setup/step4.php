<?php
/**
 * This is the file denotes the fourth step (STEP 4) of the setup procedure. This file is denoted as the core of the
 * setup installation. Server parameters are gotten , database creation , database cloning, mysql dumping, creation of subdirectories,
 * addition of initial user, and password protection.
 *
 * NB: works hand in hand with the database.php script to perform its actions
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
    $_SESSION["step"] = $_POST["step"];
    $step = $_POST["step"];
    $task = $_POST["task"];

    if(isset($_SESSION["step"]) && $step = 4){
        //ok we can allow user to run the script
    }else{
        header('location: index.php');
        session_destroy();
        // *** set new token
        $_SESSION['token'] = md5(uniqid(rand(), true));
    }

    $inst = $_POST["inst"];

    // Support "?site=siteid" in the URL, otherwise assume "clinic", to protect the default directory.
    $site_id = 'clinic';
    if (!empty($_POST['site'])) {
        $site_id = trim($_POST['site']);
    }


    if($task == 'annul'){
        session_destroy();
        write_configuration_file('localhost',3306,'libreehr','libreehr','libreehr',0);
        header('location: index.php');
        exit;
    }

?>
<div class="card">

    <div class="coverForm">
        <div class="ajaxLoader hidden"></div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>

    <?php
        
        echo "<h4>MySQL Configuration</h4>\n";
        drawSetupStep($step);
    ?>
    <p class="clearfix"></p>
    <div class="alert-info">
        <p>
            Now you need to supply the MySQL server information and path information. Detailed instructions on each item can be found in the <a href='https://github.com/LibreHealthIO/lh-ehr/blob/master/INSTALL.md#step-2' target='_blank'><span STYLE='text-decoration: underline;'>'Installation'</span></a> manual file.
        </p>
    </div>
    <p class="clearfix"></p>
    <p class="clearfix"></p>
    <form id="databaseForm" method="post" action="step5.php">
        <h4 class="librehealth-color">MySQL SERVER</h4>
        <div class="form-group">
            <div class="row">
                <div class="col-md-2"><label>Server Host:</label></div>
                <div class="col-md-4"><input style="width: 100%;" value="localhost" name="server" type="text" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(If you run MySQL and Apache/PHP on the same computer, then leave this as 'localhost'. If they are on separate computers, then enter the IP address of the computer running MySQL.)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Server Port:</label></div>
                <div class="col-md-4"><input type="text" value="3306" name="port" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is the MySQL port. The default port for MySQL is 3306.)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Database Name:</label></div>
                <div class="col-md-4"><input type="text" value="libreehr" name="dbname" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is the name of the LibreHealth EHR database in MySQL - 'libreehr' is recommended.)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Login Name:</label></div>
                <div class="col-md-4"><input type="text" value="libreehr" name="login" class="form-control" ></div>
                <div class="col-md-6"><p class="help-block">(This is the name of the LibreHealth EHR login name in MySQL - 'libreehr' is the recommended)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Password:</label></div>
                <div class="col-md-4"><input type="password" value="" name="pass" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is the Login Password for when PHP accesses MySQL - it should be at least 8 characters long and composed of both numbers and letters)</p></div>
            </div>
        </div>
        <p class="clearfix"></p>
        <?php
            if ($inst != 2) {
                echo '<div class="form-group">';
                echo '
                <div class="row">
                <div class="col-md-2"><label>Name for Root Account:</label></div>
                <div class="col-md-4"><input type="text" value="root" name="root" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is name for MySQL root account. For localhost, it is usually ok to leave it \'root\'.)</p></div>
                    </div>
                ';
                echo '
                <div class="row">
                <div class="col-md-2"><label>Root Pass:</label></div>
                <div class="col-md-4"><input type="password" value="" name="rootpass" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is your MySQL root password. For localhost, it is usually ok to leave it blank.)</p></div>
                    </div>
                ';
                echo '
                <div class="row">
                <div class="col-md-2"><label>User Hostname:</label></div>
                <div class="col-md-4"><input type="text" value="localhost" name="loginhost" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(If you run Apache/PHP and MySQL on the same computer, then leave this as \'localhost\'. If they are on separate computers, then enter the IP address of the computer running Apache/PHP.)</p></div>
                    </div>
                ';
    
                echo '
                <div class="row">
                <div class="col-md-2"><label>UTF-8 Collation: </label></div>
                <div class="col-md-4">
                    <select class="selectpicker form-control" name="collate"  data-style="btn-success" data-live-search="true">
                              <option value=\'utf8_bin\'          >Bin</option>
                              <option value=\'utf8_czech_ci\'     >Czech</option>
                              <option value=\'utf8_danish_ci\'    >Danish</option>
                              <option value=\'utf8_esperanto_ci\' >Esperanto</option>
                              <option value=\'utf8_estonian_ci\'  >Estonian</option>
                              <option value=\'utf8_general_ci\' selected>General</option>
                              <option value=\'utf8_hungarian_ci\' >Hungarian</option>
                              <option value=\'utf8_icelandic_ci\' >Icelandic</option>
                              <option value=\'utf8_latvian_ci\'   >Latvian</option>
                              <option value=\'utf8_lithuanian_ci\'>Lithuanian</option>
                              <option value=\'utf8_persian_ci\'   >Persian</option>
                              <option value=\'utf8_polish_ci\'    >Polish</option>
                              <option value=\'utf8_roman_ci\'     >Roman</option>
                              <option value=\'utf8_romanian_ci\'  >Romanian</option>
                              <option value=\'utf8_slovak_ci\'    >Slovak</option>
                              <option value=\'utf8_slovenian_ci\' >Slovenian</option>
                              <option value=\'utf8_spanish2_ci\'  >Spanish2 (Traditional)</option>
                              <option value=\'utf8_spanish_ci\'   >Spanish (Modern)</option>
                              <option value=\'utf8_swedish_ci\'   >Swedish</option>
                              <option value=\'utf8_turkish_ci\'   >Turkish</option>
                              <option value=\'utf8_unicode_ci\'   >Unicode (German, French, Russian, Armenian, Greek)</option>
                              <option value=\'\'                  >None (Do not force UTF-8)</option>
                     </select>
                </div>
                <div class="col-md-6"><p class="help-block">(This is the collation setting for mysql. Leave as \'General\' if you are not sure. If the language you are planning to use in LibreHealth EHR is in the menu, then you can select it. Otherwise, just select \'General\'.)</p></div>
                    </div>
                ';
                echo '</div>';
            }

            //section to decide if cloning of database or not
            // Include a "source" site ID drop-list and a checkbox to indicate
            // if cloning its database.  When checked, do not display initial user
            // and group stuff below.(hides them away)

            $dh = opendir($LIBRE_SITES_BASE);
            if (!$dh) die("Cannot read directory '$LIBRE_SITES_BASE'.");
            $siteslist = array();
            while (false !== ($sfname = readdir($dh))) {
                if (substr($sfname, 0, 1) == '.') continue;
                if ($sfname == $site_id         ) continue;
                $sitedir = "$LIBRE_SITES_BASE/$sfname";
                if (!is_dir($sitedir)               ) continue;
                if (!is_file("$sitedir/sqlconf.php")) continue;
                $siteslist[$sfname] = $sfname;
            }
            closedir($dh);
            // If this is not the first site...
            if (!empty($siteslist)) {
                ksort($siteslist);
                echo '<div class="form-group">';

                echo '
                <div class="row">
                <div class="col-md-2"><label>Source Site:</label></div>
                <div class="col-md-4">
                <select class="selectpicker form-control" name="source_site_id"  data-style="btn-primary" data-live-search="true">';
                             foreach ($siteslist as $sfname) {
                 echo "\n <option value = '$sfname'";if ($sfname == "default") echo " selected";echo "> $sfname </option>\n";
                                }
                 echo '</select>
                    </div>
                <div class="col-md-6"><p class="help-block">(The site directory that will be a model for the new site.)</p></div>
                    </div>
                ';
                echo '  <p class="clearfix"></p>
                <div class="row">
                <div class="col-md-2"><label>Clone Source Database:</label></div>
                <div class="col-md-4 text-center"><input type="checkbox" id="checkbox" name="clone_database"/></div>
                <div class="col-md-6"><p class="help-block">(Clone the source site\'s database instead of creating a fresh one.)</p></div>
                    </div>
                ';

                echo "</div>";
            }

        // get hidden values parsed via form
        echo "<input type='hidden' name='site' value='".$site_id."'>";
        echo "<input type='hidden' name='inst' value='".$inst."'>";
        ?>
        <div class="text-center"><hr style="width: 90%"/></div>
        <div id="libreUser">
        <h4 class="librehealth-color">LIBREEHR USER</h4>
        <div class="form-group">
            <div class="row">
                <div class="col-md-2"><label>Initial User:</label></div>
                <div class="col-md-4"><input style="width: 100%;" value="admin" name="iuser" type="text" class="form-control"></div>
                <div class="col-md-6"><p class="help-block">(This is the login name of user that will be created for you. Limit this to one word.)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Initial User Password:</label></div>
                <div class="col-md-4"><input style="width: 100%;" value="" name="iuserpass" type="password" class="form-control"></div>
                <div class="col-md-6"><p class="help-block">(This is the account for the initial user account above.)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Initial User's First Name:</label></div>
                <div class="col-md-4"><input style="width: 100%;" value="Administrator" name="iufname" type="text" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is the First name of the 'initial user'.)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Initial User's Last Name:</label></div>
                <div class="col-md-4"><input style="width: 100%;" value="Administrator" name="iuname" type="text" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is the Last name of the 'initial user'.)</p></div>
            </div>
            <p class="clearfix"></p>
            <div class="row">
                <div class="col-md-2"><label>Initial Group:</label></div>
                <div class="col-md-4"><input style="width: 100%;" name='igroup' value='Default' type="text" class="form-control" required></div>
                <div class="col-md-6"><p class="help-block">(This is the group that will be created for your users.  This should be the name of your practice.)</p></div>
            </div>
            <p class="clearfix"></p>
        </div>
        </div>
        <!-- holder for the next steps either user config or php-galc config parameters-->
        <!-- sync with the setup.js file lines(175-182)-->
        <div id="nextStep">
        </div>
    
        <div class='control-btn'>
            <input type="hidden" value="5" name="step">
            <button id="submitStep4" type='submit' class='controlBtn'>
                Continue  <i class='fa fa-arrow-circle-right'></i>
            </button>
        </div>
    </form>
    <p class="clearfix"></p>
    </div>
    <p class="clearfix"></p>

    <div class="progress" data-toggle="tooltip" title="">
        <div id="libreehrProgress" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
             aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:0.2%">
        </div>
    </div>

    
    <?php
        echo '<form action="step3.php" method="post">
                            <div class="control-btn2">
                            <input type="hidden" value="3" name="step">
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
