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

    if($('#details').val()) {
        listusers();
        console.log($("#details").val() + '= details');
    }
    else {
        user_summary();
        console.log($("#summary").val() + '= summary');

    }


});

var iter=0;
var oTable;

$("#form_from_date").val();
//Function to initiate datatables plugin
function init_datatables()
{
	oTable=$('#document_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf'
        ],

        "iDisplayLength": 100,
        "select":true,
        "searching":true,
        "retrieve" : true
	});





	iter=1;
}

function refreshPage(){

    window.location.reload();




}

//Function to populate document list
function listusers()
{


    $.ajax({
        type: "POST",
        url: "../../library/ajax/username_report_ajax.php",
        data: {
            func:"list_all_users",
            to_date:$("#form_to_date").val(),
            from_date:$("#form_from_date").val()
        },
        beforeSend: function(){
            $('#image').show();
        },

        success:function(data)
        {
            $('#users_list').html(data);
            init_datatables();
        },
        complete: function(){
            $('#image').hide();
        }

    });



	iter=1;
}

function user_summary()
{

    $.ajax({
        type: "POST",
        url: "../../library/ajax/username_report_ajax.php",
        data: {
            func:"user_summary",
            to_date: $("#form_to_date").val(),
            from_date: $("#form_from_date").val()
        },
        beforeSend: function(){
            $('#image').show();
        },

        success:function(data)
        {
            $('#users_list').html(data);
            init_datatables();
        },
        complete: function(){
            $('#image').hide();
        }

    });




}





</script>
</head>
<body class="body_top formtable">&nbsp;&nbsp;
<form action="./username_report.php" method="post">
<label><input value="<?php echo htmlspecialchars(xl('Show Session Details')) ?> " type="submit" id="details_selector" name="show_all" id="show_all"><?php ?></label>

<label><input value="<?php echo htmlspecialchars(xl('Show Summary')) ?>" type="submit" id="summary_selector" name="show_summary" id="show_summary"><?php ?></label>
<?php

if ( ! $_POST['form_from_date']) {
    // If a specific patient, default to 2 years ago.
    $from_date = date($DateFormat);

} else {
    $from_date = fixDate($_POST['form_from_date'], date($DateFormat));
    $to_date = fixDate($_POST['form_to_date'], date($DateFormat));
}

if ( !$_POST['form_to_date']) {
    // If a specific patient, default to 2 years ago.
    $to_date = date($DateFormat);
}

?>

    <input type='text' name='form_from_date' id='form_from_date' size='10'
           value='<?php echo $from_date ?>' >

    <input type='text' name='form_to_date' id='form_to_date' size='10'
           value='<?php echo $to_date ?>' >

    <input hidden id = 'summary' value = '<?php echo $_POST['show_summary']  ?>'>
    <input hidden id = 'details' value = '<?php echo $_POST['show_all']  ?>'>

</form>



&nbsp;&nbsp;

<img hidden id="image" src="/images/loading.gif" width="100" height="100">



<table cellpadding="0" cellspacing="0" border="0" class="display formtable" id="document_table">
	<thead>

		<tr>
			<th><?php echo xla('Date'); ?></th>
			<th><?php echo xla('User'); ?></th>
			<th><?php echo xla('Last'); ?></th>
			<th><?php echo xla('First'); ?></th>
			<th><?php echo xla('Session Time'); ?></th>
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