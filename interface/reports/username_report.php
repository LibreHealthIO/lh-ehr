<?php
/**
 * interface/reports/claims_viewer.php List number of documents.
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
 * along with this program. If not, see <http://opensource.org/licenses/mpl-license.php>;.
 * Copyright (c) 2018 Growlingflea Software <daniel@growlingflea.com>
 * File adapted for user activity log.
 * @package LibreEHR
 * @author  Daniel Pflieger daniel@growlingflea.com daniel@mi-squared.com
 */
 $fake_register_globals=false;
 $sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/vendor/libreehr/Framework/DataTable/DataTable.php");
require_once( "reports_controllers/AppointmentsController.php");

$form_from_date = $_POST['form_from_date'] ?  $_POST['form_from_date'] : date($DateFormat);
$form_to_date = $_POST['form_to_date'] ?  $_POST['form_to_date'] : date($DateFormat);

//if date is i m/d/y we need to fix it for queries
$to_date =prepareDateBeforeSave($form_to_date) ;
$from_date = prepareDateBeforeSave($form_from_date);
?>
<head>
<?php html_header_show();?>
<title><?php xl('User Activity Report','e'); ?></title>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<style type="text/css">
@import "<?php echo $GLOBALS['webroot'] ?>/assets/js/datatables/media/css/demo_page.css";
@import "<?php echo $GLOBALS['webroot'] ?>/assets/js/datatables/media/css/demo_table.css";
@import "<?php echo $GLOBALS['webroot'] ?>/assets/css/jquery-ui-1-12-1/jquery-ui.css";

.mytopdiv { float: left; margin-right: 1em; }
</style>

<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/assets/js/datatables/media/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/assets/js/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/assets/js/jquery-min-3-3-1/index.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/tooltip.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/assets/js/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js"></script>
<script type='text/javascript' src='<?php echo $GLOBALS['webroot'] ?>/library/dialog.js'></script>
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/assets/js/fancybox-1.3.4/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['webroot'] ?>/assets/js/DataTables-1.10.16/datatables.css">
<script type="text/javascript" charset="utf8" src="<?php echo $GLOBALS['webroot'] ?>/assets/js/DataTables-1.10.16/datatables.js"></script>
<!-- this is a 3rd party script -->
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/assets/js/datatables/extras/ColReorder/media/js/ColReorderWithResize.js"></script>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script>
$(document).ready(function() {

    if($('#show_session_times_button').val()) {
        $('.session_table').hide();
        $('#show_session_times_table').show();

        show_session_times();
        console.log($("#details").val() + '= details');
    }

    if($('#show_session_sums_button').val()) {
        $('.session_table').hide();
        $('#show_session_sums_table').show();
        show_session_sums();
    }

    if($('#show_session_details_button').val()) {
        $('.session_table').hide();
        $('#show_session_details_table').show();
        show_session_details();
    }

    if(!($('#show_session_times_button').val() || $('#show_session_details_button').val() || $('#show_session_sums_button').val())){

        $('.session_table').hide();
        $('#show_session_sums_table').show();
        show_session_sums();
    }
});

var oTable;


function show_session_times(){

    oTable=$('#show_session_times_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'csv'
        ],
        ajax:{ type: "POST",
            url: "../../library/ajax/username_report_ajax.php",
            data: {
                func:"show_session_times",
                to_date:   "<?php echo $to_date; ?>",
                from_date:" <?php echo $from_date; ?> "
            }},
        columns:[
            { 'data': 'date'         },
            { 'data': 'user'         },
            { 'data': 'lname'        },
            { 'data': 'fname'       },
            { 'data': 'session_time'}
        ],
        "iDisplayLength": 100,
        "select":true,
        "searching":true,
        "retrieve" : true
    });

    $('#column0_search_show_session_times_table').on( 'keyup', function () {
        oTable
            .columns( 0 )
            .search( this.value )
            .draw();
    } );

    $('#column1_search_show_session_times_table').on( 'keyup', function () {
        oTable
            .columns( 1 )
            .search( this.value)
            .draw();
    } );

    $('#column2_search_show_session_times_table').on( 'keyup', function () {
        oTable
            .columns( 2 )
            .search( this.value )
            .draw();
    } );

    $('#column3_search_show_session_times_table').on( 'keyup', function () {
        oTable
            .columns( 3 )
            .search( this.value )
            .draw();
    } );

    $('#column4_search_show_session_times_table').on( 'keyup', function () {
        oTable
            .columns( 4 )
            .search( this.value )
            .draw();
    } );



}

function show_session_sums()
{

    oTable=$('#show_session_sums_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'csv'
        ],
        ajax:{ type: "POST",
            url: "../../library/ajax/username_report_ajax.php",
            data: {
                func:"show_session_sums",
                to_date:   "<?php echo $to_date; ?>",
                from_date:" <?php echo $from_date; ?> "
            }},
        columns:[
            { 'data': 'date' },
            { 'data': 'username' },
            { 'data': 'lname' },
            { 'data': 'fname' },
            { 'data': 'sum' }
        ],
        "iDisplayLength": 100,
        "select":true,
        "searching":true,
        "retrieve" : true
    });
    $('#column0_search_show_session_sums_table').on( 'keyup', function () {
        oTable
            .columns( 0 )
            .search( this.value )
            .draw();
    } );

    $('#column1_search_show_session_sums_table').on( 'keyup', function () {
        oTable
            .columns( 1 )
            .search( this.value)
            .draw();
    } );

    $('#column2_search_show_session_sums_table').on( 'keyup', function () {
        oTable
            .columns( 2 )
            .search( this.value )
            .draw();
    } );

    $('#column3_search_show_session_sums_table').on( 'keyup', function () {
        oTable
            .columns( 3 )
            .search( this.value )
            .draw();
    } );

    $('#column4_search_show_session_sums_table').on( 'keyup', function () {
        oTable
            .columns( 4 )
            .search( this.value )
            .draw();
    } );



}


function show_session_details()
{

    oTable=$('#show_session_details_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'csv'
        ],
        ajax:{ type: "POST",
            url: "../../library/ajax/username_report_ajax.php",
            data: {
                func:"show_session_details",
                to_date:   "<?php echo $to_date; ?>",
                from_date:" <?php echo $from_date; ?> "
            }},
        columns:[
            { 'data': 'date' },
            { 'data': 'user' },
            { 'data': 'event' },
            { 'data': 'comments' }
        ],
        "iDisplayLength": 100,
        "select":true,
        "searching":true,
        "retrieve" : true
    });

    $('#column1_search_show_session_details_table').on( 'keyup', function () {
        oTable
            .columns( 1 )
            .search( this.value)
            .draw();
    } );

    $('#column2_search_show_session_details_table').on( 'keyup', function () {
        oTable
            .columns( 2 )
            .search( this.value )
            .draw();
    } );

    $('#column3_search_show_session_details_table').on( 'keyup', function () {
        oTable
            .columns( 3 )
            .search( this.value )
            .draw();
    } );






}






</script>
</head>
<body class="body_top formtable">&nbsp;&nbsp;
<form action="./username_report.php" method="post">
<label><input value="<?php echo htmlspecialchars(xl('Show Session Times')) ?> " type="submit" id="show_session_times_selector" name="show_session_times" ><?php ?></label>

<label><input value="<?php echo htmlspecialchars(xl('Show Session Sums')) ?>" type="submit" id="show_session_sums_selector" name="show_session_sums" ><?php ?></label>
<label><input value="<?php echo htmlspecialchars(xl('Show Session Details')) ?>" type="submit" id="show_session_details_selector" name="show_session_details" ><?php ?></label>

    <input type='text' name='form_from_date' id='form_from_date' size='10'
           value='<?php echo $form_from_date ?>' >

    <input type='text' name='form_to_date' id='form_to_date' size='10'
           value='<?php echo $form_to_date ?>' >

    <input hidden id = 'show_session_sums_button' value = '<?php echo $_POST['show_session_sums']  ?>'>
    <input hidden id = 'show_session_times_button' value = '<?php echo $_POST['show_session_times']  ?>'>
    <input hidden id = 'show_session_details_button' value = '<?php echo $_POST['show_session_details']  ?>'>

</form>



&nbsp;&nbsp;

<img hidden id="image" src="/images/loading.gif" width="100" height="100">



<table cellpadding="0" cellspacing="0" border="0" class="display formtable session_table" id="show_session_sums_table">
	<thead>

        <tr>
            <th><input  id = 'column0_search_show_session_sums_table' width="90%" align="left"></th>
            <th><input  id = 'column1_search_show_session_sums_table' width="90%" align="left"></th>
            <th><input  id = 'column2_search_show_session_sums_table' width="90%" align="left"></th>
            <th><input  id = 'column3_search_show_session_sums_table' width="90%" align="left"></th>
            <th><input  id = 'column4_search_show_session_sums_table' width="90%" align="left"></th>
        </tr>

		<tr>
			<th align="left"><?php echo xla('Date'); ?></th>
			<th align="left"><?php echo xla('User'); ?></th>
			<th align="left"><?php echo xla('Last'); ?></th>
			<th align="left"><?php echo xla('First'); ?></th>
			<th align="left"><?php echo xla('Session Time Sums'); ?></th>
		</tr>

	</thead>
	<tbody id="users_list">
	</tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="display formtable session_table" id="show_session_times_table">
    <thead>
    <tr>
        <th><input  id = 'column0_search_show_session_times_table' width="90%" align="left"></th>
        <th><input  id = 'column1_search_show_session_times_table' width="90%" align="left"></th>
        <th><input  id = 'column2_search_show_session_times_table' width="90%" align="left"></th>
        <th><input  id = 'column3_search_show_session_times_table' width="90%" align="left"></th>
        <th><input  id = 'column4_search_show_session_times_table' width="90%" align="left"></th>
    </tr>

    <tr>
        <th align="left"><?php echo xla('Date'); ?></th>
        <th align="left"><?php echo xla('User'); ?></th>
        <th align="left"><?php echo xla('Last'); ?></th>
        <th align="left"><?php echo xla('First'); ?></th>
        <th align="left"><?php echo xla('Session Time'); ?></th>
    </tr>

    </thead>
    <tbody id="users_list">
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" border="0" class="display formtable session_table" id="show_session_details_table">
    <thead>
    <tr>
        <th><input  id = 'column0_search_show_session_details_table' width="90%" align="left"></th>
        <th><input  id = 'column1_search_show_session_details_table' width="90%" align="left"></th>
        <th><input  id = 'column2_search_show_session_details_table' width="90%" align="left"></th>
        <th><input  id = 'column3_search_show_session_details_table' width="90%" align="left"></th>

    </tr>

    <tr>
        <th align="left"><?php echo xla('Date'); ?></th>
        <th align="left"><?php echo xla('User'); ?></th>
        <th align="left"><?php echo xla('Event'); ?></th>
        <th align="left"><?php echo xla('Comments'); ?></th>

    </tr>

    </thead>
    <tbody id="users_list">
    </tbody>
</table>
</body>
<link rel="stylesheet" href="../../assets/js/jquery-datetimepicker-2-5-4/jquery.datetimepicker.css">
<script type="text/javascript" src="../../assets/js/jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>

<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "<?php echo $DateFormat ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "<?php echo $DateFormat ?>"
        });

    });
</script>