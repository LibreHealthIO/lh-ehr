<?php
/**
 * Copyright (C) 2010-2012 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 */

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/headers.inc.php");
// This script can be run either inside the LibreEHR frameset for order catalog
// maintenance, or as a popup window for selecting an item to order.  In the
// popup case the GET variables 'popup' (a boolean) and 'order' (an optional
// item ID to select) will be provided, and maintenance may also be permitted.

$popup = empty($_GET['popup']) ? 0 : 1;
$order = formData('order', 'G') + 0;
$labid = formData('labid', 'G') + 0;

// If Save was clicked, set the result, close the window and exit.
//
if ($popup && $_POST['form_save']) {
  $form_order = formData('form_order') + 0;
  $ptrow = sqlQuery("SELECT name FROM procedure_type WHERE " .
    "procedure_type_id = '$form_order'");
  $name = addslashes($ptrow['name']);
?>
<script type="text/javascript" src="<?php echo $webroot ?>/interface/main/tabs/js/include_opener.js"></script>
<script language="JavaScript">
if (opener.closed || ! opener.set_proc_type) {
 alert('<?php xl('The destination form was closed; I cannot act on your selection.','e'); ?>');
}
else {
 opener.set_proc_type(<?php echo "$form_order, '$name'" ?>);
<?php
// This is to generate the "Questions at Order Entry" for the Procedure Order form.
// GET parms needed for this are: formid, formseq.
if (isset($_GET['formid'])) {
  require_once("qoe.inc.php");
  $qoe_init_javascript = '';
  echo ' opener.set_proc_html("';
  echo generate_qoe_html($form_order, intval($_GET['formid']), 0, intval($_GET['formseq']));
  echo '", "' . $qoe_init_javascript .  '");' . "\n";
}
?>
}
window.close(); // comment out for debugging
</script>
<?php
  exit();
}
// end Save logic

?>
<html>

<head>

<title><?php xl('Order and Result Types','e'); ?></title>

<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<?php call_required_libraries(array("jquery-min-3-1-1","bootstrap", "iziModalToast")); ?>
<style type="text/css">
  .noborder td, .noborder th {
    border: none !important;
  }
  .provider-table-after {
    margin-bottom: 0px;
  }
  tr.evenrow {
  background-color:#eae9e9;
  }
  tr.oddrow {
  background-color:#ffffff;
  }

  a:visited {
    color: white !important;
  }
  
</style>
<?php if ($popup) { ?>
<script type="text/javascript" src="../../library/topdialog.js"></script>
<?php } ?>
<script type="text/javascript" src="../../library/dialog.js"></script>

<script language="JavaScript">

<?php if ($popup) require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

<?php
// Create array of IDs to pre-select, leaf to top.
echo "preopen = [";
echo $order > 0 ? $order : 0;
for ($parentid = $order; $parentid > 0;) {
  $row = sqlQuery("SELECT parent FROM procedure_type WHERE procedure_type_id = '$parentid'");
  $parentid = $row['parent'] + 0;
  echo ", $parentid";
}
echo "];\n";
?>

// initiate by loading the top-level nodes
$(document).ready(function(){
 nextOpen();
});

// This is called repeatedly at initialization until all desired nodes
// have been opened.
function nextOpen() {
 if (preopen.length) {
  var thisid = preopen.pop();
  if (thisid == 0 || preopen.length > 0) {
   if (thisid > 0)
    toggle(thisid);
   else
    $.getScript('types_ajax.php?id=' + thisid + '&order=<?php echo $order; ?>' + '&labid=<?php echo $labid; ?>');
  }
  else {
   recolor();
  }
 }
 else {
  recolor();
 }
}

// toggle expansion indicator from + to - or vice versa
function swapsign(td1, from, to) {
 var s = td1.html();
 var i = s.indexOf('>' + from + '<');
 if (i >= 0) td1.html(s.substring(0,i+1) + to + s.substring(i+2));
}

// onclick handler to expand or collapse a node
function toggle(id) {
 var td1 = $('#td' + id);
 if (!td1.hasClass('haskids')) return;
 if (td1.hasClass('isExpanded')) {
  $('#con' + id).remove();
  td1.removeClass('isExpanded');
  swapsign(td1, '-', '+');
  recolor();
 }
 else {
  td1.parent().after('<tr class="outertr"><td colspan="5" id="con' + id + '" style="padding:0">Loading...</td></tr>');
  td1.addClass('isExpanded');
  swapsign(td1, '+', '-');
  $.getScript('types_ajax.php?id=' + id + '&order=<?php echo $order; ?>' + '&labid=<?php echo $labid; ?>');
 }
}

// Called by the edit window to refresh a given node's children
function refreshFamily(id, haskids) {
 if (id) { // id == 0 means top level
  var td1 = $('#td' + id);
  if (td1.hasClass('isExpanded')) {
   $('#con' + id).remove();
   td1.removeClass('isExpanded');
   swapsign(td1, '-', '+');
  }
  if (td1.hasClass('haskids') && !haskids) {
   td1.removeClass('haskids');
   // swapsign(td1, '+', '.');
   swapsign(td1, '+', '|');
   return;
  }
  if (!td1.hasClass('haskids') && haskids) {
   td1.addClass('haskids');
   // swapsign(td1, '.', '+');
   swapsign(td1, '|', '+');
  }
  if (haskids) {
   td1.parent().after('<tr class="outertr"><td colspan="5" id="con' + id + '" style="padding:0">Loading...</td></tr>');
   td1.addClass('isExpanded');
   swapsign(td1, '+', '-');
  }
 }
 if (haskids)
  $.getScript('types_ajax.php?id=' + id + '&order=<?php echo $order; ?>' + '&labid=<?php echo $labid; ?>');
 else
  recolor();
}


$(document).on('click', '.trigger', function (event) {
    event.preventDefault();
    console.log(event.target.href);
    $("#modal-iframe").iziModal({
    iframe: true,
    iframeHeight: 500,
    width: 550,
    height: 500,
    iframeURL: event.target.href,
    overlayClose: true,
    closeButton: true,
    closeOnEscape: true,
    
    });
    $("#modal-iframe").iziModal('open');
});

// add a node
// function anode(id) {
//  dlgopen('types_edit.php?typeid=0&parent=' + id, '_blank', 700, 550);
// }

// call this to alternate row colors when anything changes the number of rows
function recolor() {
 var i = 0;
 $('#con0 tr').each(function(index) {
  // skip any row that contains other rows
  if ($(this).hasClass('outertr')) return;
  this.className = (i++ & 1) ? "evenrow" : "oddrow";
 });
}

</script>

</head>

<body >

  <div class="container">
    <div id="modal-iframe"></div>
    <h2 class="text-center" ><?php xl('Types of Orders and Results','e') ?></h2>

    <form method='post' name='theform' action='types.php?popup=<?php echo $popup ?>&order=<?php
    echo $order;
    if (isset($_GET['formid' ])) echo '&formid='  . $_GET['formid'];
    if (isset($_GET['formseq'])) echo '&formseq=' . $_GET['formseq'];
    ?>'>

    <table class="table provider-table-after" style="width:100%">
      <thead>
        <tr>
          <th class="text-center" style="width:20%"><?php xl('Name','e') ?></th>
          <th class="text-center" style="width:20%"><?php xl('Order','e') ?></th>
          <th class="text-center" style="width:20%"><?php xl('Code','e') ?></th>
          <th class="text-center" style="width:20%"><?php xl('Description','e') ?></th>
          <th class="text-center" style="width:20%">Action</th>
        </tr>
      </thead>
    </tr>
    </table>
    <div id="con0"></div>
  
    <p>
    <?php if ($popup) { ?>
    <button type='submit' name='form_save' class="btn btn-primary"><?php xl('Save','e'); ?></button>
    &nbsp;
    <button type='button' onclick='window.close()' class="btn btn-danger"> <?php xl('Cancel','e'); ?></button>
    &nbsp;
    <?php } ?>
    <a href="types_edit.php?typeid=0&parent=0"  class="trigger btn btn-primary"><?php xl('Add Top Level','e'); ?></a>
    </p>

    </form>

  </div>

</body>
</html>

