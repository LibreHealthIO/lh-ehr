<?php

/**
 *  Common include pathing and related native LHEHR functions
 *
 * Copyright (C) 2017 Art Eaton
 * SOURCE:  Ken Chapple at mi-squared.com
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Art Eaton <art@suncoastconnection.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href=<?php echo $web_root; ?>/interface/themes/style.php?pc=<?php echo str_replace("#", "",$GLOBALS['primary_color']);?>&pfontcolor=<?php echo str_replace("#", "", $GLOBALS['primary_font_color']);?>&sc=<?php echo str_replace("#", "", $GLOBALS['secondary_color']);?>&sfontcolor=<?php echo str_replace("#", "",$GLOBALS['secondary_font_color']);?>>
<?php

    function include_js_library($path)
    {
?>

<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'].$path;?>"></script>

<?php
    }

    function include_css_library($path)
    {
?>
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['css_path'].$path;?>" media="screen" />

<?php
    }
?>

<?php
/*
    This function can be used to call various frequently used libraries.
    Parameters for this function are passed in an unkeyed array of strings.
    Strings equate to the directory name in the /assets/ directory.
*/
function call_required_libraries($library_array){
    /* First checking for any library in a directory matching the string "/jquery-min-/".
     * When one is found, use that value in the URL string, then add "index.js"
     */

    foreach ($library_array as $v){
        if (preg_match("/jquery-min-/", $v)) {?>
            <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'].$v ; ?>/index.js"></script>
        <?php
        }
    }

    if (in_array("bootstrap", $library_array)) {   ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['standard_js_path']; ?>bootstrap-3-3-4/dist/css/bootstrap.min.css" type="text/css">
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>bootstrap-3-3-4/dist/js/bootstrap.min.js"></script>
    <?php
    }

    if (in_array("fancybox", $library_array)) {   ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['css_path']; ?>fancybox/jquery.fancybox-1.2.6.css" media="screen" />
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>fancybox/jquery.fancybox-1.2.6.js"></script>
    <?php
    }
    if (in_array("fancybox-addpatient",$library_array)){   ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['css_path']; ?>fancybox-addpatient/jquery.fancybox-1.2.6.css" media="screen" />
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>fancybox-addpatient/jquery.fancybox-1.2.6.js"></script>
    <?php
    }
    if (in_array("fancybox-custom",$library_array)){   ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['css_path']; ?>fancybox-custom/jquery.fancybox-1.2.6.css" media="screen" />
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>fancybox-custom/jquery.fancybox-1.2.6.js"></script>
    <?php
    }
    if (in_array("knockout",$library_array)){   ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>knockout/knockout-3.4.0.js"></script>
    <?php
    }

    if (in_array("datepicker", $library_array)) {   ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['css_path']; ?>jquery-datetimepicker/jquery.datetimepicker.css" media="screen" />
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
    <?php
    }

    if (in_array('font-awesome', $library_array)) {   ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['assets'] ?>/fonts/font-awesome-4-6-3/css/font-awesome.min.css" type="text/css">
    <?php
    }

    if (in_array("jquery-ui", $library_array)) {   ?>
        <link rel="stylesheet" href="<?php echo $GLOBALS['css_path']; ?>jquery-ui-1-12-1/jquery-ui.css" media="screen" />
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-ui-1-12-1/jquery-ui.js"></script>
    <?php
    }

    if (in_array("common", $library_array)) {   ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot']; ?>/library/js/common.js"></script>
    <?php
    }

    if (in_array("gritter", $library_array)) {   ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-gritter/jquery.gritter.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['css_path']; ?>jquery-gritter/jquery.gritter.css" />
    <?php
    }

    if(in_array("select2", $library_array)) { ?>
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>select2/select2.full.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['css_path']; ?>select2/select2.min.css">
    <?php
    }
	
	if(in_array("iziModalToast", $library_array)) { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['css_path']; ?>iziModalToast/iziModal.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['css_path']; ?>iziModalToast/iziToast.min.css">
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>iziModalToast/iziModal.min.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>iziModalToast/iziToast.min.js"></script>
		<?php
	}

}
?>

<?php
/*
    This function resolves the Console-error "Uncaught TypeError: Cannot read property 'msie' of undefined" . This error comes up when using
    fancybox-1.2.6 and fancybox-1.3.4 versions with jQuery version 3.1.1. It is because the $.browser method was removed in jQuery 1.9.
*/
function resolveFancyboxCompatibility() { ?>
    <script type="text/javascript">
        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();
    </script>
<?php
}
// always include this when headers is included
// (don't know if it's a good practice because this is included even before <html>)
?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>