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


            function find_SQL_Version() {
                $output = shell_exec('mysql -V');
                preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
                return $version[0];
            }

        /**
         * Execute the given command by displaying console output live to the user.
         *  @param  string  cmd          :  command to be executed
         *  @return array   exit_status  :  exit status of the executed command
         *                  output       :  console output of the executed command
         */
        function liveExecuteCommand($cmd)
        {

            while (@ ob_end_flush()); // end all output buffers if any

            $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');

            $live_output     = "";
            $complete_output = "";

            while (!feof($proc))
            {
                $live_output     = fread($proc, 4096);
                $complete_output = $complete_output . $live_output;
                echo "$live_output";
                @ flush();
            }

            pclose($proc);

            // get exit status
            preg_match('/[0-9]+$/', $complete_output, $matches);

            // return exit status and intended output
            return array (
                'exit_status'  => intval($matches[0]),
                'output'       => str_replace("Exit status : " . $matches[0], '', $complete_output)
            );
        }


        function install_gacl($script){
        $install_results = get_require_contents($script);
        if (! $install_results ) {
            return false;
        }
        else{
            return $install_results;
        }

            }


     // http://www.php.net/manual/en/function.include.php
    function get_require_contents($filename) {
        if (is_file($filename)) {
            ob_start();
            require $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }


    function write_bashScript($sys){

            @touch('/var/www/html/LibreEHR/test.sh');

    }
?>
