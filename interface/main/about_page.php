<?php
/** 
 * LibreHealth EHR About Page 
 *
 * This Displays an About page for LibreHealth EHR Displaying Version Number, Support Phone Number
 * If it have been entered in Globals along with the Manual and On Line Support Links
 * 
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io> 
 * 
 * LICENSE: This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 3 
 * of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details. 
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;. 
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package LibreHealth EHR 
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link http://librehealth.io
 *  
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 * 
 */ 
 
$fake_register_globals=false;
$sanitize_all_escapes=true;
  
require_once("../globals.php");
?>
 <html>
  <head>
  <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
 </head>
  <body class="body_top">
    <div style="text-align: center;">
    <span class="title"><?php  echo xlt('About LibreHealth EHR'); ?> </span><br><br>  
    <span class="text"><?php  echo xlt('Version Number'); ?>: <?php echo "v".text($libreehr_version) ?></span><br><br>
    <?php if (!empty($GLOBALS['support_phone_number'])) { ?>
      <span class="text"><?php  echo xlt('Support Phone Number'); ?>: <?php echo $GLOBALS['support_phone_number'] ?></span><br><br>
    <?php } ?>
   </div>
    <a href="<?php echo "https://wiki.librehealth.io"; ?>" target="_blank" class="css_button cp-misc"><span><?php echo xlt('User Manual'); ?></span></a><br><br>
    <?php if (!empty($GLOBALS['online_support_link'])) { ?>
             <a href='<?php echo $GLOBALS["online_support_link"]; ?>' target="_blank" class="css_button cp-misc"><span><?php echo xlt('Online Support'); ?></span></a><br><br>
    <?php } ?>
   <a href="../../acknowledge_license_cert.html" target="_blank" class="css_button cp-misc"><span><?php echo xlt('Acknowledgments, Licensing and Certification'); ?></span></a>
  </body>
</html>
