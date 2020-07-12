<?php
// Copyright (C) 2010,2014 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This module provides for editing site-specific text files and
// for uploading site-specific image files.

// Disable magic quotes and fake register globals.
$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once('../globals.php');
require_once($GLOBALS['srcdir'].'/acl.inc');
require_once($GLOBALS['srcdir'].'/htmlspecialchars.inc.php');
/* for formData() */
require_once($GLOBALS['srcdir'].'/formdata.inc.php');
require_once("$srcdir/headers.inc.php");

if (!acl_check('admin', 'super')) die(htmlspecialchars(xl('Not authorized')));

$imagedir     = "$OE_SITE_DIR/images";
$educationdir = "$OE_SITE_DIR/filemanager/files/education";

if (!empty($_POST['bn_save'])) {

  $number_of_files = count($_FILES['form_image']['name']);
  for ($i=0; $i <$number_of_files ; $i++) { 
  // Handle image uploads.
    if (is_uploaded_file($_FILES['form_image']['tmp_name'][$i]) && $_FILES['form_image']['size'][$i]) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $_FILES['form_image']['tmp_name'][$i]);
      finfo_close($finfo);
      if ($mime == "image/png" OR $mime == "image/bmp" OR $mime == "image/jpeg" OR $mime == "image/gif") {
        $form_dest_filename = $_POST['form_dest_filename'];
        if ($form_dest_filename == '') {
          $form_dest_filename = $_FILES['form_image']['name'][$i];
        }
        $form_dest_filename = basename($form_dest_filename);
        if ($form_dest_filename == '') {
          die(htmlspecialchars(xl('Cannot find a destination filename')));
        }
        $imagepath = "$imagedir/$form_dest_filename";
        // If the site's image directory does not yet exist, create it.
        if (!is_dir($imagedir)) {
          mkdir($imagedir);
        }
        if (is_file($imagepath)) unlink($imagepath);
        $tmp_name = $_FILES['form_image']['tmp_name'][$i];
        if (!move_uploaded_file($_FILES['form_image']['tmp_name'][$i], $imagepath)) {
          die(htmlspecialchars(xl('Unable to create') . " '$imagepath'"));
        }
      }
      else {
         die(htmlspecialchars(xl('the file you have uploaded is not an image')));
      }
    }
}

  $number_of_files = count($_FILES['form_education']['name']);
  for ($i=0; $i <$number_of_files ; $i++) { 
    // Handle PDF uploads for patient education.
    if (is_uploaded_file($_FILES['form_education']['tmp_name'][$i]) && $_FILES['form_education']['size'][$i]) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $_FILES['form_image']['tmp_name'][$i]);
      finfo_close($finfo);
        $form_dest_filename = $_FILES['form_education']['name'][$i];
        $form_dest_filename = strtolower(basename($form_dest_filename));
        if (substr($form_dest_filename, -4) != '.pdf' && $mime == "application/pdf") {
          die(xlt('The choosen file must be a pdf file'));
        }
        $educationpath = "$educationdir/$form_dest_filename";
        // If the site's education directory does not yet exist, create it.
        if (!is_dir($educationdir)) {
          mkdir($educationdir);
        }
        if (is_file($educationpath)) unlink($educationpath);
        $tmp_name = $_FILES['form_education']['tmp_name'][$i];
        if (!move_uploaded_file($tmp_name, $educationpath)) {
          die(text(xl('Unable to create') . " '$educationpath'"));
        } 
    }
  }

}
?>
<html>

<head>
<title><?php echo xlt('File management'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style type="text/css">
 .dehead { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:bold }
 .detail { color:#000000; font-family:sans-serif; font-size:10pt; font-weight:normal }
</style>

<script language="JavaScript">
// This is invoked when a filename selection changes in the drop-list.
// In this case anything else entered into the form is discarded.
function msfFileChanged() {
 top.restoreSession();
 document.forms[0].submit();
}
</script>

</head>

<body class="body_top">
<form method='post' action='manage_site_files.php' enctype='multipart/form-data'
 onsubmit='return top.restoreSession()'>

<center>

<p>
<table border='1' width='95%'>

 <tr bgcolor='#dddddd' class='dehead'>
  <td colspan='2' align='center'><?php echo htmlspecialchars(xl('Upload Image to') . " $imagedir"); ?></td>
 </tr>

 <tr>
  <td valign='top' class='detail' nowrap>
   <?php echo htmlspecialchars(xl('Source File')); ?>:
   <input type="hidden" name="MAX_FILE_SIZE" value="12000000" />
   <input type="file" name="form_image[]"  accept="image/*" multiple="multiple" />&nbsp;
   <?php echo htmlspecialchars(xl('Destination Filename')) ?>:
   <select name='form_dest_filename'>
    <option value=''>(<?php echo htmlspecialchars(xl('Use source filename')) ?>)</option>
<?php
  // Generate an <option> for each file already in the images directory.
  $dh = opendir($imagedir);
  if (!$dh) die(htmlspecialchars(xl('Cannot read directory') . " '$imagedir'"));
  $imagesslist = array();
  while (false !== ($sfname = readdir($dh))) {
    if (substr($sfname, 0, 1) == '.') continue;
    if ($sfname == 'CVS'            ) continue;
    $imageslist[$sfname] = $sfname;
  }
  closedir($dh);
  ksort($imageslist);
  foreach ($imageslist as $sfname) {
    echo "    <option value='" . htmlspecialchars($sfname, ENT_QUOTES) . "'";
    echo ">" . htmlspecialchars($sfname) . "</option>\n";
  }
?>
   </select>
  </td>
 </tr>

 <tr bgcolor='#dddddd' class='dehead'>
  <td colspan='2' align='center'><?php echo text(xlt('Upload Patient Education PDF to .') . " $educationdir"); ?></td>
 </tr>
 <tr>
  <td valign='top' class='detail' nowrap>
   <?php echo xlt('Source File'); ?>:
   <input type="file" name="form_education[]" accept="application/pdf" multiple="multiple" />&nbsp;
   <?php echo xlt('File name must end in .pdf.'); ?>
  </td>
 </tr>

</table>

<p>
<input type='submit' class='cp-submit' name='bn_save' value='<?php echo htmlspecialchars(xl('Save')) ?>' />
</p>

</center>

</form>
</body>
</html>
