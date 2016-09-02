<?php 
/**
 * Annotate Diagram (forms)
 *
 * This program is used to allow the selection of the forms in the Annotate Diagram form. 
 *
 * Copyright (C) 2016 Jerry Padgett sjpadgett@gmail.com
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0 and the following 
 * Healthcare Disclaimer
 *  
 * In the United States, or any other jurisdictions where they may apply, the following additional disclaimer of 
 * warranty and limitation of liability are hereby incorporated into the terms and conditions of MPL 2.0:
 *
 * No warranties of any kind whatsoever are made as to the results that You will obtain from relying upon the covered code
 *(or any information or content obtained by way of the covered code), including but not limited to compliance with privacy
 * laws or regulations or clinical care industry standards and protocols. Use of the covered code is not a substitute for a
 * health care provider’s standard practice or professional judgment. Any decision with regard to the appropriateness of treatment,
 * or the validity or reliability of information or content made available by the covered code, is the sole responsibility
 * of the health care provider. Consequently, it is incumbent upon each health care provider to verify all medical history
 * and treatment plans with each patient.
 *
 * Under no circumstances and under no legal theory, whether tort (including negligence), contract, or otherwise, 
 * shall any Contributor, or anyone who distributes Covered Software as permitted by the license, 
 * be liable to You for any indirect, special, incidental, consequential damages of any character including, 
 * without limitation, damages for loss of goodwill, work stoppage, computer failure or malfunction, 
 * or any and all other damages or losses, of any nature whatsoever (direct or otherwise) 
 * on account of or associated with the use or inability to use the covered content (including, without limitation, 
 * the use of information or content made available by the covered code, all documentation associated therewith, 
 * and the failure of the covered code to comply with privacy laws and regulations or clinical care industry 
 * standards and protocols), even if such party shall have been informed of the possibility of such damages.
 *
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * Rewrite and modifications by sjpadgett@gmail.com Padgetts Consulting 2016.
 *
 * @package LibreEHR
 * @author  Jerry Padgett <sjpadgett@gmail.com> 
 * @author  Terry Hill <terry@lillysystems.com>
 * @link    http://www.libreehr.org
 */

include_once("../../globals.php");
require_once($GLOBALS['srcdir'].'/api.inc');
	$sanitize_all_escapes=true;
	$fake_register_globals=false;
	$images_dir = '../../forms/annotate_diagram/diagram/';
	$images_per_row = 7;
?>
<html>
<head>
<title><?php xl('Select New Diagram for Annotation','e'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.9.1.min.js"></script>
<style type="text/css">
body {
	background-color: skyblue;		
}
.outer {
	margin: 40px;
}
#container {
	position: relative;
	border:3px solid red;
    box-sizing:border-box;
    margin:10px;
	min-width:1px;
    min-height:1px;
    display:inline-block;
	overflow: hidden
}
#diagram {
	box-sizing:border-box;
    min-width:65px;
    min-height:65px;
	//background-color:blue;
	position: relative;
	//border:3px solid black;
}
#diagram ul {padding-left:10px;}
#diagram li {display: inline; margin-right: 3px;}
#diagram li img{
	border:1px solid black;
    width:100%;
    max-width:75px;
	//height:100%;
    //max-height:85px;
}
</style>
</head>
<body>
<?php
function get_files($images_dir,$exts = array('png')) {
	$files = array();
	
	if($handle = opendir($images_dir)) {
		while(false !== ($file = readdir($handle))) {
			$extension = strtolower(get_file_extension($file));
			if($extension && in_array($extension,$exts)) {
				$files[] = $file;
			}
		}
		closedir($handle);
	}
	return $files;
}
function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}
echo '<div class="outer">';
echo '<h4 class="text-center">'. xl("Click Thumbnails to view then Click full image to select.","e") .'</h4>';
echo '<div id="diagram" class="diagram" style="text-align:left;"><ul>';
	$image_files = get_files($images_dir);
	if(count($image_files)) {
		$index = 0;
		foreach($image_files as $index=>$file){
			$index++;
			$thumbnail_image = $images_dir.$file;
			echo '<li><img src='.$thumbnail_image.' /></li>';
			if($index % $images_per_row == 0) { echo '<div class="clear"></div>'; }
		}
	echo '<div class="clear"></div>';
	}
	else {
		echo '<p>'.xl("There are no images in this gallery.","e").'</p>';
	}
echo '</ul></div>';
echo '<div id="container" class="container">';
//$t = xl("Click on diagram above to view then Click here to select.","e");
echo '<img src="" alt="" id="main-img" class="main-img" onClick="SelectImage(this);" />';
echo '</div></div>';
?>
</body>
<script type="text/JavaScript">
$(document).ready(function() {
//	if (window.showModalDialog)
//				window.returnValue = false;
	$("#diagram li img").click( function(){
		$('#main-img').attr('src',$(this).attr('src'));
	});
});
function getFrmTitle(iname) {
	iname = iname.match(/[^\/?#]+(?=$|[?#])/) + '';
	iname = iname.split('.');
	iname = iname[0].charAt(0).toUpperCase() + iname[0].slice(1);
	
   	var fTitle = prompt("Please enter this form name or click OK for highlighted title", iname.replace(/[_-]/g, " "));
    if ( fTitle != "") {
        return fTitle;
    }
	else{
		fTitle = "Diagram";
	}
    if ( fTitle == null ) fTitle = "";
	return fTitle;
}
var SelectImage = function(obj) {
    var imgname = $(obj).attr("src");
	var fname = getFrmTitle(imgname);
	if (fname == null || fname == "")
		return false;
    if (opener.closed || !opener.SetDiagram( imgname, fname ))
        alert('The parent form was closed and lost scope; Close and try again.');
//    else{
//		if (window.showModalDialog) window.returnValue = true;
//	}
    window.close();
    return false;
};
</script>
</html>