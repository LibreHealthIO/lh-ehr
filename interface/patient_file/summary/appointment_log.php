<?php
/*
 *  appointment_log.php - Interface and processing file for Log window used in Audit feature for Appointments
 *
 * Copyright (C) 2018 Apoorv Choubey < theapoorvs1@gmail.com >
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Apoorv Choubey < theapoorvs1@gmail.com >
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

require_once("../../globals.php");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");
require_once("$srcdir/formatting.inc.php");

// incoming patient id (pid) and event id (eid) of selected appointment
$appt_pid = $_GET['appt_pid'];
$appt_eid = $_GET['appt_eid'];

$log_query = "SELECT id, apptdate, DATE_FORMAT(appttime,'%H:%i') as appttime, title, original_user as `user`, DATE_FORMAT(`date`,'%Y-%m-%d') as userdate
              FROM patient_tracker
              JOIN patient_tracker_element ON id = pt_tracker_id AND id IN (SELECT id
                                                                            FROM patient_tracker
                                                                            WHERE pid = $appt_pid AND eid = $appt_eid)
              JOIN list_options ON option_id = status
              ORDER BY id ASC, patient_tracker_element.seq ASC";
$result = sqlStatement($log_query);
?>
<html>
<head>
<?php
  html_header_show();
  call_required_libraries(array("jquery-min-3-1-1","bootstrap"));
?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript">
    $(document).ready( function() {
        $("#close").click(function() {
            parent.$("#appointmentLog-iframe").iziModal('close');
        });
    });
</script>
<style type="text/css">
.log-container{
    max-width: 470px;
    max-height: 300px;
    overflow: auto;
    border: 2px solid black;
}

.log-row{
    border-bottom: 1px solid black;
    padding: 6px 8px;
}

.log-font{
    font-size: 1.2em;
    text-align: center;
}

.red-text{
    color: #FF4136;
}

.green-text{
    color: #2ECC40;
}
</style>
</head>
<body class="body_top">
    <a class="css_button large_button" id="close" href="#">
        <span class="css_button_span large_button_span" style="font-size: 14px;"><?php echo htmlspecialchars(xl('Close'), ENT_NOQUOTES); ?></span>
    </a>
    <hr>
    <div class="log-container log-font">
        <?php
          $first_row = true;
          $previous_id = "";
          while ($row = sqlFetchArray($result)) {
            $appt_date = $row['apptdate'];
            // oeFormatTime() converts HH:mm:ss to 12 hour or 24 hour format as per Admin > Locale > Time Display Format
            $appt_time = oeFormatTime($row['appttime']);
            $user = $row['user'];
            $user_date = $row['userdate'];
            $appt_status = $row['title'];

            // echo log rows
            echo "<div class='log-row'>";
            $action = "moved";
            $connector = "to";
            if ($first_row) {
                $action = "created";
                $connector = "for";
                $first_row = false;
                $previous_id = $row['id'];
            } elseif ($row['id'] == $previous_id) {
                // status is changed
                $action = "changed";
            }
            echo "<strong>{$action}</strong> by <span class='red-text'>{$user}</span> on <span class='green-text'>{$user_date}</span> {$connector} {$appt_date} at {$appt_time} as ({$appt_status})";
            echo "</div>";
            $previous_id = $row['id'];
          }
        ?>
    </div>
</body>
</html>

