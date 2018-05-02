<?php
/** 
* 
* Copyright (C) 2016 Sherwin Gaddis <sherwingaddis@gmail.com>
* Copyright (C) 2016-2018 Nilesh Prasad <prasadnilesh96@gmail.com
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
* @author  Nilesh Prasad <prasadnilesh96@gmail.com
* @author  Sherwin Gaddis <sherwingaddis@gmail.com>
* @link    http://librehealth.io
*/


//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../../globals.php");
require_once("$srcdir/forms.inc");

global $pid;

$displays = getPriorAuthData($pid);

?>
<html>
<title></title>
<head>
<?php html_header_show();?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/ajtooltip.js"></script>

<style>
.display p {
	text-align: right;
}
.display{
	text-align: right;
	position: relative;
	float: left;
}

</style>
<script language="JavaScript">

//function toencounter(enc, datestr) {
function toencounter(rawdata) {
    var parts = rawdata.split("~");
    var enc = parts[0];
    var datestr = parts[1];

    top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
    parent.left_nav.setEncounter(datestr, enc, window.name);
    parent.left_nav.setRadio(window.name, 'enc');
    parent.left_nav.loadFrame('enc2', window.name, 'patient_file/encounter/encounter_top.php?set_encounter=' + enc);
<?php } else { ?>
    top.Title.location.href = '../encounter/encounter_title.php?set_encounter='   + enc;
    top.Main.location.href  = '../encounter/patient_encounter.php?set_encounter=' + enc;
<?php } ?>
}


	 // Helper function to set the contents of a div.
    function setDivContent(id, content) {
    $("#"+id).html(content);
   }
   
function pop(stuff){
	var things = stuff;
	alert("working" + things);
}
</script>
</head>

<body>
<h1>Prior Auth Info</h1>

<?php while($display = sqlFetchArray($displays)){ ?>
	<div class="display">
		Date: <br>
		Prior Auth#:  <br>
		Date From: / To: <br>
		CPT's: <br>
		Desc: <br>
		Units:</br>
		Used: <br>		
		Encounter:  <br>
	</div>
	<div class="info">
		<?php echo $display['date']; ?><br>
		<?php echo $display['prior_auth_number']; ?><br>
		<?php echo $display['auth_from']." &nbsp;&nbsp;-&nbsp;&nbsp; ".$display['auth_to']; ?><br>
		<?php echo $display['code1']. " " . $display['code2']. " " .$display['code3']. " ". $display['code4']. " "
					   . $display['code5']. " ". $display['code6']. " ". $display['code7']; ?><br>
		<?php echo $display['desc']; ?><br>
		<?php echo $display['auth_for']; ?><br>		
		<?php echo $display['used']; ?><br>		
		<?php echo $display['encounter']; ?><br>
	</div>
</br>
<?php if(getSupervisor($authUser) == "Supervisor"){ ?>
<a href="#" id="edit"  onclick="toencounter('<?php 
        $d = explode(" ", $display['date']);
    echo $display['encounter']."~". $d[0]?>')" ><button>Edit</button></a>
<?php } ?>

<hr>
<?php } ?>
</body>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $(".encrow").mouseover(function() { $(this).toggleClass("highlight"); });
    $(".encrow").mouseout(function() { $(this).toggleClass("highlight"); });
    $(".encrow").click(function() { toencounter(this.id); }); 

});

</script>
</html>