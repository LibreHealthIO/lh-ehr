<?php
/*
 * Appointments report
 *
 * This report shows upcoming appointments with filtering and
 * sorting by patient, practitioner, appointment type, and date.
 * 2012-01-01 - Added display of home and cell phone and fixed header
 * 2015-06-19 - brought up to security standards teryhill@librehealth.io
 *
 * Copyright (C) 2015-2017 Terry Hill <teryhill@librehealth.io> 
 * Copyright (C) 2005-2010 Rod Roark <rod@sunsetsystems.com>
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
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR 
 * @author Rod Roark <rod@sunsetsystems.com> 
 * @author Terry Hill <teryhill@librehealth.io> 
 * @link http://librehealth.io 
 */
require_once "reports_controllers/AppointmentsController.php";
?>

<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<title><?php echo xlt('Appointments Report'); ?></title>

<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>

<script type="text/javascript">
 $(document).ready(function() {
  var win = top.printLogSetup ? top : opener.top;
  win.printLogSetup(document.getElementById('printbutton'));
 });
 function dosort(orderby) {
    var f = document.forms[0];
    f.form_orderby.value = orderby;
    f.submit();
    return false;
 }
 function oldEvt(eventid) {
    dlgopen('<?php echo $GLOBALS["web_root"]; ?>/modules/calendar/add_edit_event.php?eid=' + eventid, 'blank', 775, 375);
 }
 function refreshme() {
    // location.reload();
    document.forms[0].submit();
 }
</script>

<style type="text/css">
/* specifically include & exclude from printing */
@media print {
        #report_parameters {
                visibility: hidden;
                display: none;
        }
        #report_parameters_daterange {
                visibility: visible;
                display: inline;
        }
        #report_results table {
                margin-top: 0px;
        }
}
/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
</style>
</head>

<body class="body_top">

<!-- Required for the popup date selectors -->
<div id="overDiv"
    style="position: absolute; visibility: hidden; z-index: 1000;"></div>

<span class='title'><?php echo xlt('Report'); ?> - <?php echo xlt('Appointments'); ?></span>

<?php reportParametersDaterange(); #TRK ?>

<form method='post' name='theform' id='theform' action='appointments_report.php' onsubmit='return top.restoreSession()'>

<div id="report_parameters">

<table>
    <tr>
        <td width='650px'>
        <div style='float: left'>

        <table class='text'>
            <tr>
                <td class='label'><?php echo xlt('Facility'); ?>:</td>
                <td><?php dropdown_facility($facility , 'form_facility'); ?>
                </td>
                <td class='label'><?php echo xlt('Provider'); ?>:</td>
                <td>
                    <?php // Build a drop-down list of providers. (TRK)
                        dropDownProviders(); ?>
                </td>
            </tr>
            <tr>
                <?php // Show From and To dates fields. (TRK)
                    showFromAndToDates(); ?>
            </tr>
            
            <tr>
                <td class='label'><?php echo xlt('Status'); # status code drop down creation ?>:</td>
                <td><?php generate_form_field(array('data_type'=>1,'field_id'=>'apptstatus','list_id'=>'apptstat','empty_title'=>'All'),$_POST['form_apptstatus']);?></td>
                <td><?php echo xlt('Category') #category drop down creation ?>:</td>
                <td>
                    <select id="form_apptcat" name="form_apptcat">
                        <?php // Build a drop down of Categories. (TRK)
                            dropDownCategories(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><label><input type='checkbox' name='form_show_available'
                    <?php  if ( $show_available_times ) echo ' checked'; ?>> <?php  echo xlt('Show Available Times'); # check this to show available times on the report ?>
                </label></td>
                <td></td>
                <td><label><input type="checkbox" name="incl_reminders" id="incl_reminders" 
                    <?php echo ($incl_reminders ? ' checked':''); # This will include the reminder for the patients on the report ?>>
                    <?php echo xlt('Show Reminders'); ?></label></td>
            
            <tr>
                <td></td>
                <?php # these two selects will show entries that do not have a facility or a provider ?>
                <td><label><input type="checkbox" name="with_out_provider" id="with_out_provider" <?php if($chk_with_out_provider) echo "checked";?>>&nbsp;<?php echo xlt('Without Provider'); ?></label></td>
                <td></td>
                <td><label><input type="checkbox" name="with_out_facility" id="with_out_facility" <?php if($chk_with_out_facility) echo "checked";?>>&nbsp;<?php echo xlt('Without Facility'); ?></label></td>
            </tr>
            
        </table>

        </div>

        </td>
        <td align='left' valign='middle' height="100%">
            <table style='border-left: 1px solid; width: 90%; height: 100%'>
                <tr>
                    <td>
                        <div style='margin-left: 15px'>
                            <a href='#' class='css_button' onclick='$("#form_refresh").attr("value","true"); $("#theform").submit();'>
                                <span> <?php echo xlt('Submit'); ?> </span> </a> 
                            <?php if ($_POST['form_refresh'] || $_POST['form_orderby'] ) { ?>
                                <a href='#' class='css_button' id='printbutton'> 
                                    <span> <?php echo xlt('Print'); ?> </span> </a> 
                                <a href='#' class='css_button' onclick='window.open("../patient_file/printed_fee_sheet.php?fill=2","_blank")' onsubmit='return top.restoreSession()'> 
                                    <span> <?php echo xlt('Superbills'); ?> </span> </a> 
                                <a href='#' class='css_button' onclick='window.open("../patient_file/addr_appt_label.php","_blank")' onsubmit='return top.restoreSession()'> 
                                    <span> <?php echo xlt('Address Labels'); ?> </span> </a> 
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <tr>&nbsp;&nbsp;<?php echo xlt('Most column headers can be clicked to change sort order') ?></tr>
            </table>
        </td>
    </tr>
</table>

</div>
<!-- end of search parameters --> <?php
if ($_POST['form_refresh'] || $_POST['form_orderby']) {
    $showDate = ($from_date != $to_date) || (!$to_date);
    ?>
<div id="report_results">
<table>

    <thead>
        <th><a href="nojs.php" onclick="return dosort('doctor')"
    <?php if ($form_orderby == "doctor") echo " style=\"color:#00cc00\"" ?>><?php  echo xlt('Provider'); ?>
        </a></th>

        <th <?php echo $showDate ? '' : 'style="display:none;"' ?>><a href="nojs.php" onclick="return dosort('date')"
    <?php if ($form_orderby == "date") echo " style=\"color:#00cc00\"" ?>><?php  echo xlt('Date'); ?></a>
        </th>

        <th><a href="nojs.php" onclick="return dosort('time')"
    <?php if ($form_orderby == "time") echo " style=\"color:#00cc00\"" ?>><?php  echo xlt('Time'); ?></a>
        </th>

        <th><a href="nojs.php" onclick="return dosort('patient')"
    <?php if ($form_orderby == "patient") echo " style=\"color:#00cc00\"" ?>><?php  echo xlt('Patient'); ?></a>
        </th>

        <th><a href="nojs.php" onclick="return dosort('pid')"
    <?php if ($form_orderby == "pid") echo " style=\"color:#00cc00\"" ?>><?php  echo xlt('ID'); ?></a>
        </th>

            <th><?php echo xlt('Home'); //Sorting by phone# not really useful ?></th>

                <th><?php echo xlt('Cell'); //Sorting by phone# not really useful ?></th>
                
        <th><a href="nojs.php" onclick="return dosort('type')"
    <?php if ($form_orderby == "type") echo " style=\"color:#00cc00\"" ?>><?php  echo xlt('Type'); ?></a>
        </th>
        
        <th><a href="nojs.php" onclick="return dosort('status')"
            <?php if ($form_orderby == "status") echo " style=\"color:#00cc00\"" ?>><?php  echo xlt('Status'); ?></a>
        </th>
    </thead>
    <tbody>
        <!-- added for better print-ability -->
    <?php // TODO: Pull this out
    
    $lastdocname = "";
    //Appointment Status Checking
        $form_apptstatus = $_POST['form_apptstatus'];
        $form_apptcat=null;
    if(isset($_POST['form_apptcat']))
        {
            if($form_apptcat!="ALL")
            {
                $form_apptcat=intval($_POST['form_apptcat']);
            }
        }
            
    //Without provider and facility data checking
    $with_out_provider = null;
    $with_out_facility = null;
    if( isset($_POST['with_out_provider']) ){
        $with_out_provider = $_POST['with_out_provider'];
    }
    
    if( isset($_POST['with_out_facility']) ){
        $with_out_facility = $_POST['with_out_facility'];
    }
    $appointments = fetchAppointments( $from_date, $to_date, $patient, $provider, $facility, $form_apptstatus, $with_out_provider, $with_out_facility,$form_apptcat );
    
    if ( $show_available_times ) {
        $availableSlots = getAvailableSlots( $from_date, $to_date, $provider, $facility );
        $appointments = array_merge( $appointments, $availableSlots );
    }
    $appointments = sortAppointments( $appointments, $form_orderby );
    $pid_list = array();  // Initialize list of PIDs for Superbill option
    $totalAppontments = count($appointments);   
    
    foreach ( $appointments as $appointment ) {
                array_push($pid_list,$appointment['pid']);
        $patient_id = $appointment['pid'];
        $docname  = $appointment['ulname'] . ', ' . $appointment['ufname'] . ' ' . $appointment['umname'];
                
        $errmsg  = "";
        $pc_apptstatus = $appointment['pc_apptstatus'];
        ?>

        <tr valign='top' id='p1.<?php echo attr($patient_id) ?>' bgcolor='<?php echo $bgcolor ?>'>
        <td class="detail">&nbsp;<?php echo ($docname == $lastdocname) ? "" : text($docname) ?>
        </td>

        <td class="detail" <?php echo $showDate ? '' : 'style="display:none;"' ?>><?php echo text(oeFormatShortDate($appointment['pc_eventDate'])) ?>
        </td>

        <td class="detail"><?php echo text(oeFormatTime($appointment['pc_startTime'])) ?>
        </td>

        <td class="detail">&nbsp;<?php echo text($appointment['fname'] . " " . $appointment['lname']) ?>
        </td>

        <td class="detail">&nbsp;<?php echo text($appointment['pid']) ?></td>

        <td class="detail">&nbsp;<?php echo text($appointment['phone_home']) ?></td>

        <td class="detail">&nbsp;<?php echo text($appointment['phone_cell']) ?></td>

        <td class="detail">&nbsp;<?php echo text(xl_appt_category($appointment['pc_catname'])) ?></td>
        
        <td class="detail">&nbsp;
            <?php
                //Appointment Status
                if($pc_apptstatus != ""){
                    $frow['data_type']=1;
                    $frow['list_id']='apptstat';
                    generate_print_field($frow, $pc_apptstatus);
                }
            ?>
        </td>
    </tr>

    <?php if ($patient_id && $incl_reminders) {
        // collect reminders first, so can skip it if empty
        $rems = fetch_reminders ($patient_id, $appointment['pc_eventDate']);
    } ?>
    <?php if ($patient_id && (!empty($rems) || !empty($appointment['pc_hometext']))) { // Not display of available slot or not showing reminders and comments empty ?>
    <tr valign='top' id='p2.<?php echo attr($patient_id) ?>' >
       <td colspan=<?php echo $showDate ? '"3"' : '"2"' ?> class="detail" />
       <td colspan=<?php echo ($incl_reminders ? "3":"6") ?> class="detail" align='left'>
        <?php
        if (trim($appointment['pc_hometext'])) {
            echo '<b>'.xlt('Comments') .'</b>: '.attr($appointment['pc_hometext']);
        }
        if ($incl_reminders) {
            echo "<td class='detail' colspan='3' align='left'>";
            $new_line = '';
            foreach ($rems as $rem_due => $rem_items) {
                echo "$new_line<b>$rem_due</b>: ".attr($rem_items);
                $new_line = '<br>';
            }
            echo "</td>";
        }
        ?>
        </td>
    </tr>
    <?php
    } // End of row 2 display
    
    $lastdocname = $docname;
    }
    // assign the session key with the $pid_list array - note array might be empty -- handle on the printed_fee_sheet.php page.
        $_SESSION['pidList'] = $pid_list;
    ?>
    <tr>
        <td colspan="10" align="left"><?php echo xlt('Total number of appointments'); ?>:&nbsp;<?php echo text($totalAppontments);?></td>
    </tr>
    </tbody>
</table>
</div>
<!-- end of search results --> <?php } else { ?>
<div class='text'><?php echo xlt('Please input search criteria above, and click Submit to view results.'); ?>
</div>
    <?php } ?> <input type="hidden" name="form_orderby"
    value="<?php echo attr($form_orderby) ?>" /> <input type="hidden"
    name="patient" value="<?php echo attr($patient) ?>" /> <input type='hidden'
    name='form_refresh' id='form_refresh' value='' /></form>

<script type="text/javascript">
<?php
if ($alertmsg) { echo " alert('$alertmsg');\n"; }
?>
</script>
</body>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>
</html>