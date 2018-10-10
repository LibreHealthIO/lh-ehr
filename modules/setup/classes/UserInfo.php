<?php
/**
 * This file is responsible for returning information about the users systems such as browser type, operating system, linux disto
 *
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

    class UserInfo
    {



        private static function get_user_agent() {
            return  $_SERVER['HTTP_USER_AGENT'];
        }

        public static function get_ip() {
            $mainIp = '';
            if (getenv('HTTP_CLIENT_IP'))
                $mainIp = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $mainIp = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $mainIp = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $mainIp = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
                $mainIp = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $mainIp = getenv('REMOTE_ADDR');
            else
                $mainIp = 'UNKNOWN';
            return $mainIp;
        }

        public static function get_os() {

            $user_agent = self::get_user_agent();
            $os_platform    =   "Unknown OS Platform";
            $os_array       =   array(
                '/windows nt 10/i'     	=>  'Windows 10',
                '/windows nt 6.3/i'     =>  'Windows 8.1',
                '/windows nt 6.2/i'     =>  'Windows 8',
                '/windows nt 6.1/i'     =>  'Windows 7',
                '/windows nt 6.0/i'     =>  'Windows Vista',
                '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                '/windows nt 5.1/i'     =>  'Windows XP',
                '/windows xp/i'         =>  'Windows XP',
                '/windows nt 5.0/i'     =>  'Windows 2000',
                '/windows me/i'         =>  'Windows ME',
                '/win98/i'              =>  'Windows 98',
                '/win95/i'              =>  'Windows 95',
                '/win16/i'              =>  'Windows 3.11',
                '/macintosh|mac os x/i' =>  'Mac OS X',
                '/mac_powerpc/i'        =>  'Mac OS 9',
                '/linux/i'              =>  'Linux',
                '/ubuntu/i'             =>  'Ubuntu',
                '/iphone/i'             =>  'iPhone',
                '/ipod/i'               =>  'iPod',
                '/ipad/i'               =>  'iPad',
                '/android/i'            =>  'Android',
                '/blackberry/i'         =>  'BlackBerry',
                '/webos/i'              =>  'Mobile'
            );

            foreach ($os_array as $regex => $value) {
                if (preg_match($regex, $user_agent)) {
                    $os_platform    =   $value;
                }
            }
            return $os_platform;
        }

        public static function  get_browser() {

            $user_agent= self::get_user_agent();

            $browser        =   "Unknown Browser";

            $browser_array  =   array(
                '/msie/i'       =>  'Internet Explorer',
                '/Trident/i'    =>  'Internet Explorer',
                '/firefox/i'    =>  'Firefox',
                '/safari/i'     =>  'Safari',
                '/chrome/i'     =>  'Chrome',
                '/edge/i'       =>  'Edge',
                '/opera/i'      =>  'Opera',
                '/OPR/i'        =>  'Opera',
                '/netscape/i'   =>  'Netscape',
                '/maxthon/i'    =>  'Maxthon',
                '/konqueror/i'  =>  'Konqueror',
                '/ubrowser/i'   =>  'UC Browser',
                '/mobile/i'     =>  'Handheld Browser'
            );

            foreach ($browser_array as $regex => $value) {

                if (preg_match($regex, $user_agent)) {
                    $browser  =   $value;
                    if($browser === 'Opera'){
                        $browser = "opera.png";
                    }
                    if($browser === 'Firefox'){
                        $browser = "firefox.png";
                    }
                    if($browser === 'Internet Explorer'){
                        $browser = "internet-explorer.png";
                    }
                    if($browser === 'Safari'){
                        $browser = "safari.png";
                    }
                    if($browser === 'Chrome'){
                        $browser = "chrome.png";
                    }
                    }

            }

            return $browser;

        }

        //function to get distro if on linux
        public static function get_distro(){
                if(!$r = @parse_ini_file("/etc/lsb-release")){
                    if(!$r = @parse_ini_file("/etc/os-release")){
                        $r = @parse_ini_file("/etc/redhat-release");
                    }
                }
                /*for ex:
                    $r["DISTRIB_ID"]=Ubuntu
                    $r["DISTRIB_RELEASE"]=13.10
                    $r["DISTRIB_CODENAME"]=saucy
                    $r["DISTRIB_DESCRIPTION"]="Ubuntu 13.10"
                */
                $r["debian_version"]=trim(@file_get_contents("/etc/debian_version")); // for ex. "wheezy/sid"
                $r["PHP_OS"]=PHP_OS;
                $r["SERVER_SOFTWARE"]=@$_SERVER["SERVER_SOFTWARE"];
                $r["osx_system_arch"]=@$_SERVER["_system_arch"];// Mac-specific for ex.=> x86_64
                $r["osx_system_version"]=@$_SERVER["_system_version"];// Mac-specific for ex.=> 10.9
                /**
                'a': This is the default. Contains all modes in the sequence "s n r v m".
                's': Operating system name. eg. FreeBSD.
                'n': Host name. eg. localhost.example.com.
                'r': Release name. eg. 5.1.2-RELEASE.
                'v': Version information. Varies a lot between operating systems.
                'm': Machine type. eg. i386.
                 */
                $r["kernel_release_name"]=php_uname('r');
                $r["os_name"]=php_uname('s');
                $r["uname_version_info"]=php_uname('v');
                $r["machine_type"]=php_uname('m');
                $r["php_uname"]=php_uname();

                if(stristr($r["uname_version_info"],"Ubuntu")){
                    // source: http://wiki.ubuntuusers.de/Kernel/Linux-Versionsnummern#Versionsnummern-herausfinden
                    $distribution["4.10"]=array("Warty Warthog", "2.6.8");
                    $distribution["5.04"]=array("Hoary Hedgehog", "2.6.10");
                    $distribution["5.10"]=array("Breezy Badger", "2.6.12");
                    $distribution["6.06"]=array("Dapper Drake", "2.6.15");
                    $distribution["6.10"]=array("Edgy Eft", "2.6.17");
                    $distribution["7.04"]=array("Feisty Fawn", "2.6.20");
                    $distribution["7.10"]=array("Gutsy Gibbon", "2.6.22");
                    $distribution["8.04"]=array("Hardy Heron", "2.6.24");
                    $distribution["8.10"]=array("Intrepid Ibex", "2.6.27");
                    $distribution["9.04"]=array("Jaunty Jackalope", "2.6.28");
                    $distribution["9.10"]=array("Karmic Koala", "2.6.31");
                    $distribution["10.04"]=array("Lucid Lynx", "2.6.32");
                    $distribution["10.10"]=array("Maverick Meerkat", "2.6.35");
                    $distribution["11.04"]=array("Natty Narwhal", "2.6.38");
                    $distribution["11.10"]=array("Oneiric Ocelot", "3.0");
                    $distribution["12.04"]=array("Precise Pangolin", "3.2"); #Backports: 3.5, 3.8, 3.11, 3.13
                    $distribution["12.10"]=array("Quantal Quetzal", "3.5");
                    $distribution["13.04"]=array("Raring Ringtail", "3.8");
                    $distribution["13.10"]=array("Saucy Salamander", "3.11");
                    $distribution["14.04"]=array("Trusty Tahr", "3.13"); # Backports 3.16, 3.19
                    $distribution["14.10"]=array("Utopic Unicorn", "3.16");
                    $distribution["15.04"]=array("Vivid Vervet", "3.19");
                    $distribution["15.10"]=array("Wily Werewolf", "4.2");
                    $distribution["16.04"]=array("Xenial Xerus", "4.4");

                    foreach($distribution as $distribution=>$name_kernel){
                        list($name,$kernel)=$name_kernel;
                        if(version_compare($r["kernel_release_name"],$kernel,'>=')) {
                            $r["ubuntu_distribution_by_kernel"]=$distribution;
                            $r["ubuntu_distribution_name_by_kernel"]=$name;
                            $r["ubuntu_distribution_shortname_by_kernel"]=strtolower(preg_replace("/ .*$/","", $name));
                        }
                    }
                    if(empty($r["DISTRIB_RELEASE"]) and !empty($r["ubuntu_distribution_by_kernel"])) {
                        $r["DISTRIB_RELEASE"]=$r["ubuntu_distribution_by_kernel"];
                        $r["DISTRIB_DESCRIPTION"]="Ubuntu ".$r["ubuntu_distribution_by_kernel"];
                        $r["DISTRIB_CODENAME"]=$r["ubuntu_distribution_shortname_by_kernel"];
                    }
                }
                return $r;
        }

        public static function  get_device(){

            $tablet_browser = 0;
            $mobile_browser = 0;

            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $tablet_browser++;
            }

            if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $mobile_browser++;
            }

            if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
                $mobile_browser++;
            }

            $mobile_ua = strtolower(substr(self::get_user_agent(), 0, 4));
            $mobile_agents = array(
                'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                'newt','noki','palm','pana','pant','phil','play','port','prox',
                'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                'wapr','webc','winw','winw','xda ','xda-');

            if (in_array($mobile_ua,$mobile_agents)) {
                $mobile_browser++;
            }

            if (strpos(strtolower(self::get_user_agent()),'opera mini') > 0) {
                $mobile_browser++;
                //Check for tablets on opera mini alternative headers
                $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
                if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                    $tablet_browser++;
                }
            }

            if ($tablet_browser > 0) {
                // do something for tablet devices
                return 'Tablet';
            }
            else if ($mobile_browser > 0) {
                // do something for mobile devices
                return 'Mobile';
            }
            else {
                // do something for everything else
                return 'Computer';
            }
        }


    }

?>
