<?php

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");

//if coming here from select patient tab
if (isset($_GET['set_pid'])) {
    $pid = $_GET['set_pid'];
}

?>
<html>
<head>
<?php
  html_header_show();
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","font-awesome","jquery-ui","iziModalToast"));
?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'] ?>datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        //iziModal displaying a log of changes in date/time/status of selected appointment
        $("#appt-table> tbody> tr").on("click", function (event) {
            event.preventDefault();
            var apptPid = parseInt('<?php echo("{$pid}"); ?>');
            var apptEid = parseInt($(this).attr("data-text"));
            var patName = "" + "<?php echo getPatientName($pid); ?>";

            initIziLink(apptPid, apptEid, patName);
        });

        function initIziLink(apptPid, apptEid, patName) {
            $("#appointmentLog-iframe").iziModal({
                title: 'Track Log of:',
                subtitle: 'Appointment, Patient: ' + "<strong>" + patName + "</strong>",
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
                iframeHeight: 400,
                iframeURL: "../summary/appointment_log.php?appt_pid=" + apptPid + "&appt_eid=" + apptEid,
                onClosed: function () {
                            setTimeout(function () {
                                parent.$(".fa-refresh").click();
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

tr.appt-odd{
    background-color: #dddddd;
}

tr.appt-even{
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
    <table class="table" id="appt-table">
        <thead>
            <tr class="appt-head">
                <th><?php echo xlt('Appt. Date'); ?></th>
                <th><?php echo xlt('Appt. Time'); ?></th>
                <th><?php echo xlt('Provider'); ?></th>
                <th><?php echo xlt('Status'); ?></th>
                <th><?php echo xlt('Category'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr class="appt-odd" data-text="1">
                <td><?php echo xlt('4/6/18'); ?></td>
                <td><?php echo xlt('10:30'); ?></td>
                <td><?php echo xlt('John Doe'); ?></td>
                <td><?php echo xlt('No Show'); ?></td>
                <td><?php echo xlt('Office Visit'); ?></td>
            </tr>
            <tr class="appt-even" data-text="2">
                <td><?php echo xlt('1/1/18'); ?></td>
                <td><?php echo xlt('09:30'); ?></td>
                <td><?php echo xlt('James Doe'); ?></td>
                <td><?php echo xlt('No Show'); ?></td>
                <td><?php echo xlt('Office Visit'); ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <hr>
</body>
</html>

