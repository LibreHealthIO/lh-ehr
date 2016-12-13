<?php
/** 
 * Patient Tracker Status Editor 
 *
 * This allows entry and editing of current status for the patient from within patient tracker and updates the status on the calendar.
 * Contains a drop down for the Room information driven by the list Patient Flow Board Rooms.
 * 
 * Copyright (C) 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @package LibreEHR 
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link http://www.libreehr.org 
 *  
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 * 
 */ 
 
$fake_register_globals=false;
$sanitize_all_escapes=true;
  
require_once("../globals.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/encounter_events.inc.php");
require_once("$srcdir/patient_tracker.inc.php");
?>
 <html>
  <head>
  <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
  <link rel="stylesheet" type="text/css" href="../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
  <script type="text/javascript" src="../../library/js/jquery.1.3.2.js"></script>
  <script type="text/javascript" src="../../library/js/common.js"></script>
  <script type="text/javascript" src="../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>

<?php
    # Get the information for fields
    $tracker_id = $_GET['tracker_id'];
    $trow = sqlQuery("SELECT apptdate, appttime, patient_tracker_element.room AS lastroom, " .
                            "patient_tracker_element.status AS laststatus, eid, random_drug_test, encounter, pid " .
                            "FROM patient_tracker " .
                            "LEFT JOIN patient_tracker_element " .
                            "ON patient_tracker.id = patient_tracker_element.pt_tracker_id " .
                            "AND patient_tracker.lastseq = patient_tracker_element.seq " .
                            "WHERE patient_tracker.id =?",array($_GET['tracker_id']));
 
    $tkpid = $trow['pid'];
    $appttime = $trow['appttime'];
    $apptdate = $trow['apptdate']; 
    $pceid = $trow['eid'];
    $theroom = '';
	 
  if ($_POST['statustype'] !='') { 
    $status = $_POST['statustype'];
    if (strlen($_POST['roomnum']) != 0) {
       $theroom = $_POST['roomnum'];
    }

    # Manage tracker status. Also auto create encounter, if applicable.
    if (!empty($tkpid)) {
     if ($GLOBALS['auto_create_new_encounters'] && $apptdate == date('Y-m-d') && (is_checkin($status) == '1') && !is_tracker_encounter_exist($apptdate,$appttime,$tkpid,$pceid))		 
	 {		
        # Gather information for encounter fields
        $genenc = sqlQuery("select pc_catid as category, pc_hometext as reason, pc_aid as provider, pc_facility as facility, pc_billing_location as billing_facility " .
                           "from libreehr_postcalendar_events where pc_eid =? " , array($pceid));
        $encounter = todaysEncounterCheck($tkpid, $apptdate, $genenc['reason'], $genenc['facility'], $genenc['billing_facility'], $genenc['provider'], $genenc['category'],false);
        # Capture the appt status and room number for patient tracker. This will map the encounter to it also.
        if (!empty($pceid)) {
        manage_tracker_status($apptdate,$appttime,$pceid,$tkpid,$_SESSION["authUser"],$status,$theroom,$encounter);
	 }
      }
      else 
      {
        # Capture the appt status and room number for patient tracker.
        if (!empty($pceid)) {
          manage_tracker_status($apptdate,$appttime,$pceid,$tkpid,$_SESSION["authUser"],$status,$theroom);
        }
      }
     }
    
     echo "<html>\n<body>\n<script language='JavaScript'>\n";	
     echo " window.opener.pattrk.submit();\n";
     echo " window.close();\n";    
     echo "</script></body></html>\n";
     exit();
  }
     #get the patient name for display
     $row = sqlQuery("select fname, lname " .
     "from patient_data where pid =? limit 1" , array($tkpid));

?>
 </head>
  <body class="body_top">
    <center>
    <form id="form_note" method="post" action="patient_tracker_status.php?tracker_id=<?php echo attr($tracker_id) ?>" enctype="multipart/form-data" >
    <table>
    <h2><?php echo xlt('Change Status for'). " " . text($row['fname']) . " " . text($row['lname']); ?></h2>

    <span class=text><?php  echo xlt('Status Type'); ?>: </span><br> 
<?php
    # Generate drop down list for status.
	echo generate_select_list('statustype', 'apptstat',$trow['laststatus'], xl('Status Type'));
?>
	<br><br>   
	<span class=text><?php  echo xlt('Exam Room Number'); ?>: </span><br>
<?php
    # Generate drop down list for room number.
	echo generate_select_list('roomnum', 'patient_flow_board_rooms',$trow['lastroom'], xl('Exam Room Number'));
?>
<br><br>
    <tr>
     <td>
      <a href='javascript:;' class='css_button_small' style='color:gray' onclick='document.getElementById("form_note").submit();'><span><?php echo xla('Save')?></span></a>
      &nbsp;
      <a href='javascript:;' class='css_button_small' style='color:gray' onclick="window.close().submit();" ><span><?php  echo xla('Cancel'); ?></span></a>
     </td>
    </tr>
    </table>
    </td>
    </form>
    </center>
  </body>
</html>
