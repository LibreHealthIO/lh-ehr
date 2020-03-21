<!-- Work/School Note Form created by Nikolai Vitsyn: 2004/02/13 and update 2005/03/30 
     Copyright (C) Open Source Medical Software 

     This program is free software; you can redistribute it and/or
     modify it under the terms of the GNU General Public License
     as published by the Free Software Foundation; either version 2
     of the License, or (at your option) any later version.

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. -->

<?php

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
formHeader("Form: note");
$returnurl = 'encounter_top.php';
$provider_results = sqlQuery("select fname, lname from users where username=?",array($_SESSION{"authUser"}));
require_once($GLOBALS['srcdir']."/formatting.inc.php");
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

/* name of this form */
$form_name = "note"; 

// get the record from the database
if ($_GET['id'] != "") $obj = formFetch("form_".$form_name, $_GET["id"]);
/* remove the time-of-day from the date fields */
if ($obj['date_of_signature'] != "") {
    $dateparts = explode(" ", $obj['date_of_signature']);
    $obj['date_of_signature'] = $dateparts[0];
}
?>
<html><head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<!-- supporting javascript code -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/textformat.js"></script>


<script language="JavaScript">

function PrintForm() {
    newwin = window.open("<?php echo $rootdir."/forms/".$form_name."/print.php?id=".attr($_GET["id"]); ?>","mywin");
}

</script>

</head>
<body class="body_top">

<form method=post action="<?php echo $rootdir."/forms/".$form_name."/save.php?mode=update&id=".attr($_GET["id"]);?>" name="my_form" id="my_form">
<span class="title"><?php echo xlt('Work/School Note'); ?></span><br></br>

<div style="margin: 10px;">
<input type="button" class="save" value="    <?php echo xla('Save'); ?>    "> &nbsp; 
<input type="button" class="dontsave" value="<?php echo xla('Don\'t Save'); ?>"> &nbsp; 
<input type="button" class="printform" value="<?php echo xla('View Printable Version'); ?>"> &nbsp; 
</div>

<select name="note_type">
<option value="WORK NOTE" <?php if ($obj['note_type']=="WORK NOTE") echo " SELECTED"; ?>><?php echo xlt('WORK NOTE'); ?></option>
<option value="SCHOOL NOTE" <?php if ($obj['note_type']=="SCHOOL NOTE") echo " SELECTED"; ?>><?php echo xlt('SCHOOL NOTE'); ?></option>
</select>
<br>
<b><?php echo xlt('MESSAGE:'); ?></b>
<br>
<textarea name="message" id="message" cols ="67" rows="4"><?php echo text($obj["message"]);?></textarea>
<br> <br>

<table>
<tr><td>
<span class=text><?php echo xlt('Doctor:'); ?> </span><input type=entry name="doctor" value="<?php echo attr($obj["doctor"]);?>">
</td><td>
<span class="text"><?php echo xlt('Date'); ?></span>
   <input type='text' size='10' name='date_of_signature' id='date_of_signature'
    value='<?php echo htmlspecialchars(oeFormatShortDate(attr($obj['date_of_signature']))); ?>'
    title='<?php echo xla('yyyy-mm-dd'); ?>'/>
</td></tr>
</table>

<div style="margin: 10px;">
<input type="button" class="save" value="    <?php echo xla('Save'); ?>    "> &nbsp; 
<input type="button" class="dontsave" value="<?php echo xla('Don\'t Save'); ?>"> &nbsp; 
<input type="button" class="printform" value="<?php echo xla('View Printable Version'); ?>"> &nbsp; 
</div>

</form>

</body>
<link rel="stylesheet" href="../../../library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="../../../library/js/jquery.datetimepicker.full.min.js"></script>

<script language="javascript">

// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $(".save").click(function() { top.restoreSession(); $("#my_form").submit(); });
    $(".dontsave").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
    $(".printform").click(function() { PrintForm(); });

    // disable the Print ability if the form has changed
    // this forces the user to save their changes prior to printing
    $("#img_date_of_signature").click(function() { $(".printform").attr("disabled","disabled"); });
    $("input").keydown(function() { $(".printform").attr("disabled","disabled"); });
    $("select").change(function() { $(".printform").attr("disabled","disabled"); });
    $("textarea").keydown(function() { $(".printform").attr("disabled","disabled"); });
    $("#date_of_signature").datetimepicker({
        timepicker: false,
        format: "<?= $DateFormat; ?>"
});
    $.datetimepicker.setLocale('<?= $DateLocale;?>');
});

</script>

</html>

