<?php
// select_patient.php - Interface and processing file for Select Patient tab used in Audit feature for Appointments
// Copyright (C) 2012 Rod Roark <rod@sunsetsystems.com>
// Sponsored by David Eschelbacher, MD
// Copyright (C) 2018 Apoorv Choubey < theapoorvs1@gmail.com >
//
// @package LibreHealth EHR
// @author Rod Roark <rod@sunsetsystems.com>
// @author Apoorv Choubey < theapoorvs1@gmail.com >
// @link http://librehealth.io
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;

require_once("../../globals.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/headers.inc.php");

//Including required libraries.
call_required_libraries(array("jquery-min-3-1-1","bootstrap"));

$popup = empty($_REQUEST['popup']) ? 0 : 1;
$defaultFilterName = empty($_REQUEST['defaultFilterName']) ? null : $_REQUEST['defaultFilterName'];
$defaultFilterValue = empty($_REQUEST['defaultFilterValue']) ? null : $_REQUEST['defaultFilterValue'];
$defaultFilterIndex = null;

//Generate some code based on the list of columns.
$colcount = 0;
$header0 = "";
$header  = "";
$coljson = "";
$res = sqlStatement("SELECT option_id, title FROM list_options WHERE " .
  "list_id = 'ptlistcols' and activity = '1' ORDER BY seq, title");
while ($row = sqlFetchArray($res)) {
  $colname = $row['option_id'];
  $title = xl_list_label($row['title']);
  $header .= "   <th>";
  $header .= text($title);
  $header .= "</th>\n";
  $headerValue = "";
  if ( $colname == $defaultFilterName ) {
    $defaultFilterIndex = $colcount;
    $headerValue = $defaultFilterValue;
  }
  $header0 .= "   <td align='center'><input type='text' size='10' ";
  $header0 .= "value='$headerValue' class='form-control form-rounded' /></td>\n";
  if ($coljson) $coljson .= ", ";
  $coljson .= "{\"sName\": \"" . addcslashes($colname, "\t\r\n\"\\") . "\"}";
  ++$colcount;
}
?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo "Select Patient"; ?></title>

<style type="text/css">
@import "<?php echo $GLOBALS['standard_js_path'] ?>datatables/media/css/demo_page.css";
@import "<?php echo $GLOBALS['standard_js_path'] ?>datatables/media/css/demo_table.css";
.mytopdiv { float: left; margin-right: 1em; }
</style>

<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'] ?>datatables/media/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'] ?>datatables/media/js/jquery.dataTables.min.js"></script>
<!-- this is a 3rd party script -->
<script type="text/javascript" src="<?php echo $GLOBALS['standard_js_path'] ?>datatables/extras/ColReorder/media/js/ColReorderWithResize.js"></script>

<script type="text/javascript">
$(document).ready(function() {
 // Initializing the DataTable.
 //
 var oTable = $('#pt_table').dataTable( {
  "bProcessing": true,
  // next 2 lines invoke server side processing
  "bServerSide": true,
  "sAjaxSource": "dynamic_finder_ajax.php",
  // sDom invokes ColReorderWithResize and allows inclusion of a custom div
  "sDom"       : 'Rlfrt<"mytopdiv">ip',
  // These column names come over as $_GET['sColumns'], a comma-separated list of the names.
  // See: http://datatables.net/usage/columns and
  // http://datatables.net/release-datatables/extras/ColReorder/server_side.html
  "aoColumns": [ <?php echo $coljson; ?> ],
  "aLengthMenu": [ 10, 25, 50, 100 ],
  "iDisplayLength": <?php echo empty($GLOBALS['gbl_pt_list_page_size']) ? '10' : $GLOBALS['gbl_pt_list_page_size']; ?>,
  // language strings are included so we can translate them
  "oLanguage": {
   "sSearch"      : "<?php echo xla('Search all columns'); ?>:",
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


 // This is to support column-specific search fields.
 // Borrowed from the multi_filter.html example.
 $("thead input").keyup(function () {
  // Filter on the column (the index) of this element
    oTable.fnFilter( this.value, $("thead input").index(this) );
 });

 <?php if ( $defaultFilterValue !== null &&
    $defaultFilterIndex !== null ) { ?>
    oTable.fnFilter( "<?php echo $defaultFilterValue; ?>", <?php echo $defaultFilterIndex; ?> );
 <?php } ?>

 // OnClick handler for the rows
 $('#pt_table tbody tr').live('click', function () {
  // ID of a row element is pid_{value}
  var newpid = this.id.substring(4);
  // If the pid is invalid, then don't attempt to set
  // The row display for "No matching records found" has no valid ID, but is
  // otherwise clickable. (Matches this CSS selector).  This prevents an invalid
  // state for the PID to be set.
  if (newpid.length===0)
  {
      return;
  }

   top.restoreSession();
   //update iframe of select patient tab to track appointments tab when a patient row is clicked
   window.location = "../../patient_file/history/track_appointments.php?set_pid=" + newpid;

 });
});


// this will add bootstrap classes to search all columns and entries inputs
$(document).ready(function() {

  var search = $("#pt_table_filter :input");
  var entries = $("#pt_table_length :input");
  var paginate_pre = $('.paginate_disabled_previous');
  var paginate_next = $('.paginate_disabled_next');

  search.addClass("form-control form-rounded");
  entries.addClass("form-control form-rounded");
  paginate_pre.css({'height':'30px', 'background-color':'#888'});
  paginate_next.css({'height':'30px', 'background-color':'#888'});
});
</script>

</head>
<body class="body_top" style="min-height:20px; padding: 19px; margin-bottom: 20px; background-color: #f5f5f5;">

<div id="dynamic" style="padding-bottom: 30px">

<!-- Class "display" is defined in demo_table.css -->
<table cellpadding="0" cellspacing="0" border="0" class="table table-hover " id="pt_table">
 <thead>
  <tr>
<?php echo $header0; ?>
  </tr>
  <tr>
<?php echo $header; ?>
  </tr>
 </thead>
 <tbody>
  <tr>
   <!-- Class "dataTables_empty" is defined in jquery.dataTables.css -->
   <td colspan="<?php echo $colcount; ?>" class="dataTables_empty">...</td>
  </tr>
 </tbody>
</table>

</div>

</body>
</html>

