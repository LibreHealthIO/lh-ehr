<?php
/**
 * Created by PhpStorm.
 * User: rachmann mua
 * Date: 6/16/18
 * Time: 9:48 PM
 */
?>

<?php

    // function to draw the setup step navigation
    function drawSetupStep($step = 1, $draw = true){

            $steps = array(1,2,3,4,5,6);

            $output  = "<div class='step-div'>\n";
                foreach($steps as $key){
                if($step > $key){
                $css_class = ' class="step completed-step"';
                $output .= "\t\t<div".$css_class."></div>\n";
            }
            else if($step == $key){
            $css_class = ' class="step active-step"';
            $step_num  = "<div class=\"pull-right step-count\"><span class=\"librehealth-color\">$key</span>&nbsp;/&nbsp;6</div>\n";
            $output .= "\t\t<div".$css_class."></div>\n";
            }
            else{
            $css_class = " class='step '";
            $output .= "\t\t<div".$css_class."></div>\n";
            }

            }
            $output .= $step_num;
            $output .= "\t</div><br>\n";
            if($draw) echo $output;
            else return $output;
    }

    // function to write sqlconfig file (should be written every time there is an install or initial state(start back process))
    // takes one parameter flag for the $config variable 1 => install 0 => uninstall



   function write_configuration_file($host, $port, $dbase, $login, $pass, $flag) {
            @touch('libre-config.php');
            $fd = @fopen('libre-config.php', 'w');
            if ( ! $fd ) {
              $error_message = 'unable to open configuration file for writing: ';
              return False;
            }
            $string = '<?php
        //  LibreEHR MySQL Config
                 ';

            $it_died = 0;   //fmg: variable keeps running track of any errors

            fwrite($fd,$string) or $it_died++;
            fwrite($fd,"\$host\t= '$host';\n") or $it_died++;
            fwrite($fd,"\$port\t= '$port';\n") or $it_died++;
            fwrite($fd,"\$login\t= '$login';\n") or $it_died++;
            fwrite($fd,"\$pass\t= '$pass';\n") or $it_died++;
            fwrite($fd,"\$dbase\t= '$dbase';\n\n") or $it_died++;
            fwrite($fd,"global \$disable_utf8_flag;\n") or $it_died++;
            fwrite($fd,"\$disable_utf8_flag = false;\n") or $it_died++;

        $string = '
        $sqlconf = array();
        global $sqlconf;
        $sqlconf["host"]= $host;
        $sqlconf["port"] = $port;
        $sqlconf["login"] = $login;
        $sqlconf["pass"] = $pass;
        $sqlconf["dbase"] = $dbase;
        /////////WARNING!/////////
        //Setting $config to = 0//
        // will break this site //
        //and cause SETUP to run//
        $config = '.$flag.'; /////////////
        //////////////////////////
        //////////////////////////
        //////////////////////////
        ?>
        ';
        ?><?php // done just for coloring

            fwrite($fd,$string) or $it_died++;
            fclose($fd) or $it_died++;

            //it's rather irresponsible to not report errors when writing this file.
            if ($it_died != 0) {
              $error_message = "ERROR. Couldn't write $it_died lines to config file'.\n";
              return FALSE;
            }

            return TRUE;
          }

          function getMysqlVersion(){

          }


          function extension_check($extensions){

              $fail = '';
              $pass = '';

              if(version_compare(phpversion(), '5.2.0', '<')) {
                  $fail .= '<li>You need<strong> PHP 5.2.0</strong> (or greater;<strong>Current Version:'.phpversion().')</strong></li>';
              }
              else {
                  $pass .='<li>You have<strong> PHP 5.2.0</strong> (or greater; <strong>Current Version:'.phpversion().')</strong></li>';
              }
              if(!ini_get('safe_mode')) {
                  $pass .='<li>Safe Mode is <strong>off</strong></li>';
                  preg_match('/[0-9]\.[0-9]+\.[0-9]+/', shell_exec('mysql -V'), $version);

                  if(version_compare($version[0], '4.1.20', '<')) {
                      $fail .= '<li>You need<strong> MySQL 4.1.20</strong> (or greater; <strong>Current Version:.'.$version[0].')</strong></li>';
                  }
                  else {
                      $pass .='<li>You have<strong> MySQL 4.1.20</strong> (or greater; <strong>Current Version:'.$version[0].')</strong></li>';
                  }
              }
              else { $fail .= '<li>Safe Mode is <strong>on</strong></li>';  }
              foreach($extensions as $extension) {
                  if(!extension_loaded($extension)) {
                      $fail .= '<li> You are missing the <strong>'.$extension.'</strong> extension</li>';
                  }
                  else{   $pass .= '<li>You have the <strong>'.$extension.'</strong> extension</li>';
                  }
              }

              if($fail) {
                  echo '<p><strong>Your server does not meet the following requirements in order to install Magento.</strong>';
                  echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for Magento:';
                  echo '<ul>'.$fail.'</ul></p>';
                  echo 'The following requirements were successfully met:';
                  echo '<ul>'.$pass.'</ul>';
              } else {
                  echo '<p><strong>Congratulations!</strong> Your server meets the requirements for Magento.</p>';
                  echo '<ul>'.$pass.'</ul>';
              }

          }



?>
