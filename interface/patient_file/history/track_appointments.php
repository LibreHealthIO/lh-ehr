<?php
/*
 *  track_appointments.php - Interface of Track Appointments tab used in Audit feature for Appointments
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

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");
require_once("$srcdir/formatting.inc.php");

// date format for datepicker
$dateFormat = DateFormatRead();

// if coming here from select patient tab
if (isset($_GET['set_pid'])) {
    $pid = $_GET['set_pid'];
}

// to access pid at server-side processing (dataTable)
$_SESSION['selected_pat_id'] = $pid;

$popup = empty($_REQUEST['popup']) ? 0 : 1;

// $header is HTML for table's column names row
$header = "<th>" . text(xl_list_label('Date')) . "</th>
           <th>" . text(xl_list_label('Time')) . "</th>
           <th>" . text(xl_list_label('Provider')) . "</th>
           <th>" . text(xl_list_label('Status')) . "</th>
           <th>" . text(xl_list_label('Category')) . "</th>";
// $coljson is a string used to map column fields on client-side to database fields
$coljson = '{"sName": "pc_eventDate"}, {"sName": "pc_startTime"}, {"sName": "pc_aid"}, {"sName": "pc_apptstatus"}, {"sName": "pc_title"}';
?>
<html>
<head>
<?php
  html_header_show();
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","font-awesome","jquery-ui","iziModalToast"));
?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $GLOBALS['css_path']; ?>jquery-datetimepicker/jquery.datetimepicker.css" media="screen" />
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'] ?>datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        //iziModal code for displaying a log of changes in date/time/status of selected appointment
        // using $(static-parent).on(event, dynamic-child, function) since data row <tr> elements are dynamically created
        $("#appt-table").on("click", "tr.data-row", function (event) {
            event.preventDefault();
            var apptPid = parseInt('<?php echo("{$pid}"); ?>');
            var eidString = $(this).attr("id") // id = "eid_number" for a row element <tr>
            eidString = eidString.substring(4);
            // when row displays "No matching records found" (no valid id)
            if (eidString.length === 0) {
                return false;
            }
            var apptEid = parseInt(eidString);
            var patName = "" + "<?php echo getPatientName($pid); ?>";

            initIziLink(apptPid, apptEid, patName);
        });

        function initIziLink(apptPid, apptEid, patName) {
            $("#appointmentLog-iframe").iziModal({
                title: 'Track Log of:',
                subtitle: 'Appointment ID: ' + '<strong>' + apptEid + '</strong>' + ', Patient: ' + '<strong>' + patName + '</strong>',
                headerColor: '#88A0B9',
                closeOnEscape: true,
                fullscreen: true,
                overlayClose: false,
                closeButton: true,
                theme: 'light',
                iframe: true,
                width: 500,
                focusInput: true,
                padding: 5,
                iframeHeight: 350,
                iframeURL: "../summary/appointment_log.php?appt_pid=" + apptPid + "&appt_eid=" + apptEid,
                onClosed: function () {
                            setTimeout(function () {
                                parent.$(".tab-refresh-icon").click();
                            }, 300);
                          }
            });

            setTimeout(function () {
                call_izi();
            }, 100);
        }

        function call_izi() {
            $("#appointmentLog-iframe").iziModal('open');
        }
    });
</script>
<style type="text/css">
tr.appt-head{
    font-size: 1em;
    font-weight: bold;
    background-color: #f59031;
    color: #ffffff;
}

tr.appt-head> td{
    border-right: 2px solid white;
}

tr.appt-head> td:last-child{
    border: none;
}

tr.odd{
    background-color: #dddddd;
}

tr.even{
    background-color: #ffffff;
}

tr.appt-hover{
    color: #000000;
    background-color: #ffca9a;
}

.dataTables_wrapper{
    padding: 20px;
}

.dataTables_length {
    width: 40%;
    float: left;
    color: #000000;
    font-size: 14px;
}

.dataTables_filter{
    width: 50%;
    float: right;
    text-align: right;
    color: #000000;
    font-size: 14px;
}

.dataTables_info {
    width: 60%;
    float: left;
}

.dataTables_paginate{
    float: right;
    text-align: right;
    font-size: 20px;
}

.dataTables_paginate> a{
    padding: 10px;
    color: #0000cc !important;
}

.dataTables_paginate> a:hover{
    color: #ff5555 !important;
}

.start-label{
    font-size: 14px;
    margin-left: 20px;
}

.end-label{
    font-size: 14px;
    margin-left: 20px;
}
</style>
</head>
<body class="body_top">
    <!-- to initialize the iziModal -->
    <div id="appointmentLog-iframe"></div>
    <div>
        <span class="title"><?php echo htmlspecialchars(xl('Track Appointments'),ENT_NOQUOTES); ?></span>
        &nbsp;
        <span>
            <?php if (isset($_GET['set_pid'])): ?>
                <!--if came here from select patient tab, go back to it-->
                <a href="../../main/finder/select_patient.php" class="css_button" onclick="top.restoreSession()"><?php echo htmlspecialchars(xl('Back To Select Patient'),ENT_NOQUOTES);?></a>
            <?php else: ?>
                <!--if came here from demographics tab, go back to it-->
                <a href="../summary/demographics.php" class="css_button" onclick="top.restoreSession()"><?php echo htmlspecialchars(xl('Back To Patient'),ENT_NOQUOTES);?></a>
            <?php endif; ?>
        </span>
    </div>
    <div style="margin-left: 30px;">
        <?php echo htmlspecialchars(xl('Selected Patient:'),ENT_NOQUOTES);?>
        &nbsp;
        <span class="title">
            <?php echo htmlspecialchars(getPatientName($pid),ENT_NOQUOTES) ?>
        </span>
    </div>
    <hr>
    <table>
        <tbody>
            <tr>
                <td>
                    <label for="startDate" class="start-label">
                        From date:
                        <input type="text" id="startDate" name="startDate" class="form-control form-rounded">
                    </label>
                </td>
                <td>
                    <label for="endDate" class="end-label">
                        To date:
                        <input type="text" id="endDate" name="endDate" class="form-control form-rounded">
                    </label>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table" id="appt-table">
        <thead>
            <tr class="appt-head">
                <?php echo $header; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="dataTables_empty">...</td>
            </tr>
        </tbody>
    </table>
    <br>
    <hr>
    <script type="text/javascript">
        $(document).ready(function() {
            // initializing the DataTable
            var oTable = $("#appt-table").dataTable({
                "bProcessing": true,
                "bServerSide": true,  // for server side processing in DataTables
                // See: http://legacy.datatables.net/usage/server-side
                "sAjaxSource": "track_appointments_ajax.php",  // server-side processing
                // See: https://datatables.net/beta/1.9/examples/server_side/custom_vars.html
                "fnServerParams": function ( aoData ) {  // sending dates to server for date-range filtering
                    aoData.push({ "name": "fromDate", "value": $('#startDate').val() });  // start date of range
                    aoData.push({ "name": "toDate", "value": $('#endDate').val() });  // end date of range
                 },
                // See: http://datatables.net/usage/columns
                "aoColumns": [ <?php echo $coljson; ?> ],  // can be accessed by $_GET['sColumns'] in data source file
                // values for table length
                "aLengthMenu": [ 10, 25, 50, 100 ],
                // value for default table length
                "iDisplayLength": <?php echo empty($GLOBALS['gbl_appt_list_page_size']) ? '10' : $GLOBALS['gbl_appt_list_page_size']; ?>,
                // strings for specifying various DataTables' texts
                // See: http://legacy.datatables.net/usage/i18n
                "oLanguage": {
                    "sSearch": "<?php echo xla('Search all columns'); ?>:",
                    "sLengthMenu"  : "<?php echo xla('Show ') . xla('entries:') . '<br>' . ' _MENU_ '; ?>",
                    "sZeroRecords" : "<?php echo xla('No matching records found'); ?>",
                    "sInfo"        : "<?php echo xla('Showing') . ' _START_ ' . xla('to{{range}}') . ' _END_ ' . xla('of') . ' _TOTAL_ ' . xla('entries'); ?>",
                    "sInfoEmpty"   : "<?php echo xla('Nothing to show'); ?>",
                    "sInfoFiltered": "(<?php echo xla('filtered from') . ' _MAX_ ' . xla('total entries'); ?>)",
                    "oPaginate": {
                        "sFirst"   : "<?php echo xla('First'); ?>",
                        "sPrevious": "<?php echo xla('Previous'); ?>",
                        "sNext"    : "<?php echo xla('Next'); ?>",
                        "sLast"    : "<?php echo xla('Last'); ?>"
                    }
                }
            });

            // this will add bootstrap classes to search all columns and entries inputs
            var search = $("#appt-table_filter :input");
            var entries = $("#appt-table_length :input");
            search.addClass("form-control form-rounded");
            entries.addClass("form-control form-rounded");
            // fontAwesome icons for paginate buttons
            $('.dataTables_paginate').prepend('<i class="fa fa-arrow-circle-left fa-lg" aria-hidden="true"></i>');
            $('.dataTables_paginate').append('<i class="fa fa-arrow-circle-right fa-lg" aria-hidden="true"></i>');
            // for row background color change when hovering
            $("#appt-table").on("mouseover", "tr.data-row", function() {
                $(this).addClass("appt-hover");
            });
            $("#appt-table").on("mouseout", "tr.data-row", function() {
                $(this).removeClass("appt-hover");
            });
            // Date Picking
            $('#startDate').datetimepicker({
                timepicker: false,
                format: '<?php echo $dateFormat; ?>',
                formatDate: '<?php echo $dateFormat; ?>',
                onShow: function( ct ){
                    this.setOptions({
                        maxDate: $('#endDate').val() ? $('#endDate').val() : false  // limit ending date choices if end date available
                    })
                }
            });
            $('#endDate').datetimepicker({
                timepicker: false,
                format: '<?php echo $dateFormat; ?>',
                formatDate: '<?php echo $dateFormat; ?>',
                onShow: function( ct ){
                    this.setOptions({
                        minDate: $('#startDate').val() ? $('#startDate').val() : false  // limit starting date choices if start date available
                    })
                }
            });
            // re-draw table when picking start date or end date
            $('#startDate').change(function() {
                oTable.fnDraw();
            });
            $('#endDate').change(function() {
                oTable.fnDraw();
            });
        });
    </script>
</body>
</html>

