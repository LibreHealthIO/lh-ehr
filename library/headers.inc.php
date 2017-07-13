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
<link rel="stylesheet" href="<?php echo $GLOBALS['standard_js_path'] ?>bootstrap-3-3-4/dist/css/bootstrap.min.css" type="text/css">
<?php

function include_js_library($path)
{
?>
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'].$path;?>"></script>
<?php
}function include_css_library($path)
{
?>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['css_path'].$path;?>" media="screen" />
<?php
}

?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'];?>/bootstrap-3-3-4/dist/js/bootsrap.min.js"></script>
