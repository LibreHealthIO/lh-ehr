<?php
/**
 * message_status.php - generate contents for pop-up messenger.
 *
 * This file is included as an invisible iframe by forms that want to be
 * notified when a new message for the user comes in.
 *
 * Copyright (C) 2015-2017 Tony McCormick <tony@mi-squared.com> 
 * Copyright (C) 2012 Julia Longtin <julialongtin@diasp.org>
 *
 *
 * LICENSE: This Source Code is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreEHR
 * @author  Julia Longtin <julialongtin@diasp.org>
 * @author  Tony McCormick <tony@mi-squared.com>
 * @author  Terry Hill <teryhill@librehealth.io>
 * @link    http://www.libreehr.org
 *
 * This file is included as an invisible iframe by forms that want to be
 * notified when a new message for the user comes in.
 *
 * Added globals for the messages and the display interval 2016 Terry Hill <teryhill@librehealth.io>
 */
/* Sanitize All Escapes */
$fake_register_globals = false;
/* Stop fake register globals */
$sanitize_all_escapes = true;
/* include required globals */
require_once('../../globals.php');
/* for acl_check() */
require_once($GLOBALS['srcdir'] . '/acl.inc');
/* for text() */
require_once($GLOBALS['srcdir'] . '/htmlspecialchars.inc.php');
/* for getPnotesByUser(). */
require_once($GLOBALS['srcdir'] . '/pnotes.inc');
/* for GetDueReminderCount(). */
require_once($GLOBALS['srcdir'] . '/dated_reminder_functions.php');
?>
<html>
<head>
    <title><?php echo text(xl('Invisible Messaging IFrame')); ?></title>
</head>
<body>
<div id="notices"><?php
    $notices = 0;
    if ($GLOBALS['floating_message_alerts']) {
        // if this user has permission to patient notes..
        if (acl_check('patients', 'notes')) {
            // generate notice if the user has pending (unread) messages or reminders.
            $total = getPnotesByUser(true, false, $_SESSION['authUser'], true);
            $total += GetAllReminderCount();
            if ($total > 0) {
                echo '<div id="notice' . $notices . '"><div class="sticky"></div><div class="colour">blue</div><div class="title">' . xlt('Notice') . '</div><div class="text">' . xlt('You have') . ' ' . $total . ' ' . xlt('active notes and reminders' . (($total > 1) ? 's' : '')) . '.</div></div>';
                $notices++;
            }
        }
        // generate warning if user has overdue reminders.
        $total = GetDueReminderCount(0, strtotime(date('Y/m/d')));
        if ($total > 0) {
            echo '<div id="notice' . $notices . '"><div class="sticky">1</div><div class="UUID">OVERDUEWARN1</div><div class="colour">red</div><div class="title">' . xlt('WARNING') . '</div><div class="text">' . xlt('You have') . ' ' . $total . ' ' . xlt('overdue reminder' . (($total > 1) ? 's' : '')) . '.</div></div>';
            $notices++;
        }
    }
    if ($GLOBALS['floating_message_alerts_allergies']) {
        // Check for Allergies with Reaction/severity 
        if (acl_check('patients', 'med')) {
           $sql = "SELECT * FROM lists WHERE pid = ? AND type = 'allergy' ORDER BY begdate";
           $res = sqlStatement($sql, array($pid));
           while ($row = sqlFetchArray($res)) {
                if (!empty($row['reaction'])) {
                   $reaction = " (". $row['reaction'] . ") / " . $row['severity_al'];
                   if ($row['severity_al'] =="fatal") {
                       echo '<div id="notice' . $notices . '"><div class="sticky"></div><div class="colour">red</div><div class="title">' . xlt('FATAL ALLERGY REACTION FATAL') . '</div><div class="text">' . htmlspecialchars($row['title'] . $reaction , ENT_NOQUOTES) . '</div></div>';
                   }else{
                       echo '<div id="notice' . $notices . '"><div class="sticky"></div><div class="colour">red</div><div class="title">' . xlt('ALLERGY REACTION') . '</div><div class="text">' . htmlspecialchars($row['title'] . $reaction , ENT_NOQUOTES) . '</div></div>';
                   }
                   $notices++;
                }
            }
        }
    }
    if ($GLOBALS['floating_message_alerts']) {
        #Check for Patient alerts
        if (acl_check('patients', 'med')) {
            $sql = "SELECT * FROM lists WHERE pid = ? AND type = 'patient_alert' ORDER BY begdate";
            $res = sqlStatement($sql, array($pid));
            while ($row = sqlFetchArray($res)) {
                $reaction = " ";
                echo '<div id="notice' . $notices . '"><div class="sticky"></div><div class="colour">red</div><div class="title">' . xlt('PATIENT ALERT') . '</div><div class="text">' . htmlspecialchars($row['title'] . $reaction , ENT_NOQUOTES) . '</div></div>';
                $notices++;
            }
        }
    }
    /* uncomment this for demos
    echo '<div id="notice'.$notices.'"><div class="sticky">1</div><div class="UUID">VMTESTING1</div><div class="colour">red</div><div class="title">'.xlt('WARNING').'</div><div class="text">'.xlt('This VM is for TESTING ONLY').'</div>';
    $notices++;
    */
    if ($notices > 0) {
        echo '<div id="noticecount">' . $notices . '</div>';
    }
    ?></div>
</body>
</html>
