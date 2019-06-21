<?php
/**
* Function to check and/or sanitize things for security such as
* directories names, file names, etc.
*
* Copyright (C) 2012 by following Brady Miller <brady@sparmy.com>
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
* @package LibreHealth EHR
* @author Brady Miller <brady@sparmy.com>
* @author Roberto Vasquez <robertogagliotta@gmail.com>
* @link http://librehealth.io
*/
// If the label contains any illegal characters, then the script will die.
function check_file_dir_name($label) {
  if (empty($label) || preg_match('/[^A-Za-z0-9_.-]/', $label))
    die(xlt("ERROR: The following variable contains invalid characters").": ". attr($label));
}

// Convert all illegal characters to _
function convert_safe_file_dir_name($label) {
  return preg_replace('/[^A-Za-z0-9_.-]/','_',$label);
}

//image mime check with all image formats.
function image_has_right_mime($image_properties) {
  $mime = $image_properties["mime"];
    $mime_types = array('image/png',
                            'image/jpeg',
                            'image/gif',
                            'image/bmp',
                            'image/vnd.microsoft.icon');
   
   return in_array($mime, $mime_types);
}

//image file extension check
function image_has_right_extension($image_file_type, $extensions) {
  
  return in_array($image_file_type, $extensions);
}

//image size check
function image_has_right_size($size) {
  return $size < 20971520;
}


?>
