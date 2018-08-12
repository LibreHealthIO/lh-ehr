<?php
// Copyright (C) 2010-2014 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/headers.inc.php");

$typeid = formData('typeid', 'R') + 0;
$parent = formData('parent', 'R') + 0;

$info_msg = "";

function QuotedOrNull($fld) {
  $fld = formDataCore($fld,true);
  if ($fld) return "'$fld'";
  return "NULL";
}

function invalue($name) {
  $fld = formData($name,"P",true);
  return "'$fld'";
}

function rbinput($name, $value, $desc, $colname) {
  global $row;
  $ret  = "<input type='radio' name='$name' value='$value'";
  if ($row[$colname] == $value) $ret .= " checked";
  $ret .= " />$desc";
  return $ret;
}

function rbvalue($rbname) {
  $tmp = $_POST[$rbname];
  if (! $tmp) $tmp = '0';
  return "'$tmp'";
}

function cbvalue($cbname) {
  return empty($_POST[$cbname]) ? 0 : 1;
}

function recursiveDelete($typeid) {
  $res = sqlStatement("SELECT procedure_type_id FROM " .
    "procedure_type WHERE parent = '$typeid'");
  while ($row = sqlFetchArray($res)) {
    recursiveDelete($row['procedure_type_id']);
  }
  sqlStatement("DELETE FROM procedure_type WHERE " .
    "procedure_type_id = '$typeid'");
}

?>
<html>
<head>
<script type="text/javascript" src="<?php echo $webroot ?>/interface/main/tabs/js/include_opener.js"></script>    
<title><?php echo $typeid ? xlt('Edit') : xlt('Add New'); ?> <?php echo xlt('Order/Result Type'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<?php call_required_libraries(array("jquery-min-3-1-1","bootstrap")); ?>
<style>
td { font-size:10pt; }

.inputtext {
 padding-left:2px;
 padding-right:2px;
}

.button {
 font-family:sans-serif;
 font-size:9pt;
 font-weight:bold;
}

.ordonly { }
.resonly { }

</style>

<script type="text/javascript" src="../../library/topdialog.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.2.2.min.js"></script>

<script language="JavaScript">

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

// The name of the form field for find-code popup results.
var rcvarname;

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var f = document.forms[0];
 var s = f[rcvarname].value;
 if (code) {
  if (s.length > 0) s += ';';
  s += codetype + ':' + code;
 } else {
  s = '';
 }
 f[rcvarname].value = s;
}

// This invokes the find-code popup.
function sel_related(varname) {
 if (typeof varname == 'undefined') varname = 'form_related_code';
 rcvarname = varname;
 dlgopen('../patient_file/encounter/find_code_popup.php', '_blank', 500, 400);
}

// Show or hide sections depending on procedure type.
function proc_type_changed() {
 var f = document.forms[0];
 var pt = f.form_procedure_type;
 var ix = pt.selectedIndex;
 if (ix < 0) ix = 0;
 var ptval = pt.options[ix].value;
 var ptpfx = ptval.substring(0, 3);
 $('.ordonly').hide();
 $('.resonly').hide();
 if (ptpfx == 'ord') $('.ordonly').show();
 if (ptpfx == 'res'|| ptpfx == 'rec') $('.resonly').show();
}
function closeModal() {
    parent.$('#modal-iframe').iziModal('close');
    parent.location.reload();
}

$(document).ready(function() {
 proc_type_changed();


  $("#close").click(function() {
    closeModal();
  });             
});

</script>

</head>

<body class="body_top">
  <div class="container">
  
<?php
// If we are saving, then save and close the window.
//
$errors = [];
if ($_POST['form_save']) {
 
  $sets =
    "name = "           . invalue('form_name')           . ", " .
    "lab_id = "         . invalue('form_lab_id')         . ", " .
    "procedure_code = " . invalue('form_procedure_code') . ", " .
    "procedure_type = " . invalue('form_procedure_type') . ", " .
    "body_site = "      . invalue('form_body_site')      . ", " .
    "specimen = "       . invalue('form_specimen')       . ", " .
    "route_admin = "    . invalue('form_route_admin')    . ", " .
    "laterality = "     . invalue('form_laterality')     . ", " .
    "description = "    . invalue('form_description')    . ", " .
    "units = "          . invalue('form_units')          . ", " .
    "`range` = "        . invalue('form_range')          . ", " .
    "standard_code = "  . invalue('form_standard_code')  . ", " .
    "related_code = "   . invalue('form_related_code')   . ", " .
    "seq = "            . invalue('form_seq');

  // base validation rule
  if (($_POST['form_name'] === '') || ($_POST['form_description'] === '') || ($_POST['form_seq'] === '') || ($_POST['form_procedure_type'] === '')) {
    $errors[] = 'Please fill in all the fields';
  }
  var_dump($_POST['form_procedure_type']);
  switch($_POST['form_procedure_type']) {
    case 'ord':
      if ( ($_POST['form_lab_id'] === '') || ($_POST['form_procedure_code'] === '') || ($_POST['form_standard_code'] === '') || ($_POST['form_body_site'] === '')
           || ($_POST['form_specimen'] === '') || ($_POST['route_admin'] === '') || ($_POST['form_laterality'] === '')) {
            if(count($errors) <= 0) {
              $errors[] = 'Please fill in all the fields';
            }
      }
    break;

    case 'res':
    case 'rec':

      if (($_POST['form_procedure_code'] === '') || ($_POST['form_units'] === '') || ($_POST['form_range'] === '')) {
        if (count($errors) <= 0) {
          $errors[] = 'Please fill in all the fields';
        }
      }

    break;
  }
  
  if(count($errors) <= 0) {
    if ($typeid) {
      sqlStatement("UPDATE procedure_type SET $sets WHERE procedure_type_id = '$typeid'");
      // Get parent ID so we can refresh the tree view.
      $row = sqlQuery("SELECT parent FROM procedure_type WHERE " .
        "procedure_type_id = '$typeid'");
      $parent = $row['parent'];
    } else {
      $newid = sqlInsert("INSERT INTO procedure_type SET parent = '$parent', $sets");
      // $newid is not really used in this script
    }
  }
  
}

else  if ($_POST['form_delete']) {

  if ($typeid) {
    // Get parent ID so we can refresh the tree view after deleting.
    $row = sqlQuery("SELECT parent FROM procedure_type WHERE " .
      "procedure_type_id = '$typeid'");
    $parent = $row['parent'];
    recursiveDelete($typeid);
  }

}

if ( ($_POST['form_save'] || $_POST['form_delete']) && count($errors) <= 0) {
  // Find out if this parent still has any children.
  $trow = sqlQuery("SELECT procedure_type_id FROM procedure_type WHERE parent = '$parent' LIMIT 1");
  $haskids = empty($trow['procedure_type_id']) ? 'false' : 'true';
  // Close this window and redisplay the updated list.
  echo "<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('$info_msg');\n";
  echo " closeModal(); \n";
  echo "</script></body></html>\n";
  exit();
}

if ($typeid) {
  $row = sqlQuery("SELECT * FROM procedure_type WHERE procedure_type_id = '$typeid'");
}
?>
<form method='post' name='theform' class="form-horizontal"
 action='types_edit.php?typeid=<?php echo $typeid ?>&parent=<?php echo $parent ?>'>
<!-- no restoreSession() on submit because session data are not relevant -->

  <div class="form-group">
      <label for="form_procedure_type" class="col-sm-2 control-label"><?php echo xlt('Procedure Type'); ?>: </label>
      <div class="col-sm-10">
        <?php
          echo generate_select_list('form_procedure_type', 'proc_type', $row['procedure_type'],
          xl('The type of this entity'), ' ', '', 'proc_type_changed()');
        ?>
      </div>
  </div>
  <div class="form-group">
    <label for="form_name1" class="col-sm-2 control-label" > <?php echo xlt('Name'); ?></label>
    <div class="col-sm-10">
      <input type="text" class="form-control" size='40' name='form_name' id='form_name1' maxlength='63'
      value='<?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>'
      title='<?php echo xlt('Your name for this category, procedure or result'); ?>' />
    </div>
  </div>
  <div class="form-group">
    <label for="form_description1" class="col-sm-2 control-label"><?php echo xlt('Description'); ?></label>
    <div class="col-sm-10">
      <input type='text' size='40' class='form-control' name='form_description' id='form_description1' maxlength='255'
      value='<?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?>'
      title='<?php echo xlt('Description of this procedure or result code'); ?>'
      />
    </div>
  </div>
  <div class="form-group">
    <label for="form_seq1" class="col-sm-2 control-label"><?php echo xlt('Sequence'); ?></label>
    <div class="col-sm-10">
      <input type='text' size='4' style='width:auto;' name='form_seq' class='form-control' maxlength='11'
      value='<?php echo $row['seq'] + 0; ?>'
      title='<?php echo xla('Relative ordering of this entity'); ?>'
      class='form-control' />
    </div>
  </div>
  <div class="form-group ordonly">
      <label for="form_lab_id1" class="col-sm-2 control-label"><?php echo xlt('Order From'); ?>:</label>
      <div class="col-sm-10">
        <select class='form-control' style="width: auto; display: inline-block"  id='form_lab_id1' name='form_lab_id' title='<?php echo xla('The entity performing this procedure'); ?>'>
            <?php
              $ppres = sqlStatement("SELECT ppid, name FROM procedure_providers " .
                "ORDER BY name, ppid");
              while ($pprow = sqlFetchArray($ppres)) {
                echo "<option value='" . attr($pprow['ppid']) . "'";
                if ($pprow['ppid'] == $row['lab_id']) echo " selected";
                echo ">" . text($pprow['name']) . "</option>";
              }
            ?>
        </select>
      </div>
     
  </div>
  <div class="form-group ordonly resonly">
    <label for="form_procedure_code1" class="col-sm-2 control-label"><?php echo xlt('Identifying Code'); ?>:</label>
    <div class="col-sm-10">
      <input type='text' size='4' style='width: auto;' name='form_procedure_code' id='form_procedure_code1' maxlength='31'
      value='<?php echo htmlspecialchars($row['procedure_code'], ENT_QUOTES); ?>'
      title='<?php echo xla('The vendor-specific code identifying this procedure or result'); ?>'
      style='width:100%' class='form-control' />
    </div>
  </div>

  <div class="ordonly form-group">
    <label for="form_standard_code1" class="col-sm-2 control-label"><?php echo xlt('Standard Code'); ?></label>
    <div class="col-sm-10">
      <input type='text' size='4' style='width: auto;' id='form_standard_code1' name='form_standard_code'
        value='<?php echo attr($row['standard_code']); ?>'
        title='<?php echo xla('Enter the LOINC code for this procedure'); ?>'
        class='form-control'  />
    </div>
  </div>

  <div class="ordonly form-group">
    <label for="form_body_site" class="col-sm-2 control-label"><?php echo xlt('Body Site'); ?>:</label>
    <div class="col-sm-10">
      <?php
      generate_form_field_with_class(array('data_type' => 1, 'field_id' => 'body_site',
        'list_id' => 'proc_body_site',
        'description' => xl('Body site, if applicable')), $row['body_site'], 'form-control');
      ?>
    </div>
    
  </div>

  <div class="ordonly form-group">
    <label for="proc_specimen" class="col-sm-2 control-label"><?php echo xlt('Specimen Type'); ?>:</label>
    <div class="col-sm-10">
      <?php
        generate_form_field_with_class(array('data_type' => 1, 'field_id' => 'specimen',
          'list_id' => 'proc_specimen',
          'description' => xl('Specimen Type')),
          $row['specimen'], 'form-control');
      ?>   
    </div>
  </div>

  <div class="ordonly form-group">
    <label for="proc_route" class="col-sm-2 control-label"><?php echo xlt('Administer Via'); ?>:</label>
    <div class="col-sm-10">
    <?php
      generate_form_field(array('data_type' => 1, 'field_id' => 'route_admin',
        'list_id' => 'proc_route',
        'description' => xl('Route of administration, if applicable')),
        $row['route_admin'], 'form-control');
      ?>
    </div>
  </div>

  <div class="ordonly form-group">
    <label for="proc_lat" class="col-sm-2 control-label"><?php echo xlt('Laterality'); ?>:</label>
    <div class="col-sm-10">
      <?php
        generate_form_field_with_class(array('data_type' => 1, 'field_id' => 'laterality',
          'list_id' => 'proc_lat',
          'description' => xl('Laterality of this procedure, if applicable')),
          $row['laterality'], 'form-control');
      ?>
    </div>
  </div>

  <div class="resonly form-group">
    <label for="proc_unit" class="col-sm-2 control-label"><?php echo xlt('Default Units'); ?>:</label>
    <div class="col-sm-10">
    <?php
      generate_form_field_with_class(array('data_type' => 1, 'field_id' => 'units',
        'list_id' => 'proc_unit',
        'description' => xl('Optional default units for manual entry of results')),
        $row['units'], 'form-control');
    ?>
    </div>
  </div>

  <div class="resonly form-group">
    <label for="form_range1" class="col-sm-2 control-label"><?php echo xlt('Default Range'); ?>:</label>
    <div class="col-sm-10">
      <input type='text' id='form_range1' size='10' name='form_range' maxlength='255' style='width: auto;'
      value='<?php echo htmlspecialchars($row['range'], ENT_QUOTES); ?>'
      title='<?php echo xla('Optional default range for manual entry of results'); ?>'
      style='width:100%' class='form-control' />
    </div>
  </div>

  <div class="resonly form-group">
    <label for="form_related_code1" class="col-sm-2 control-label"><?php echo xlt('Followup Services'); ?>:</label>
    <div class="col-sm-10">
      <input type='text' size='50' name='form_related_code' id='form_related_code1'
      value='<?php echo $row['related_code'] ?>' onclick='sel_related("form_related_code")'
      title='<?php echo xla('Click to select services to perform if this result is abnormal'); ?>'
      style='width:auto' class="form-control" readonly />
    </div>
  </div>
  <?php if(count($errors) > 0) { ?>
  <div class="form-group alert alert-danger">
    <ul>
    <?php 
      foreach($errors as $error) {
        echo '<li style="font-size: 15px;">'.$error.'</li>';
      }
    ?>
    </ul>
  </div>
 <?php } ?>


<br />

<input type='submit' class='btn btn-success' name='form_save' value='<?php echo xla('Save'); ?>' />

<?php if ($typeid) { ?>
&nbsp;
<input type='submit' name='form_delete' class='btn btn-danger' value='<?php echo xla('Delete'); ?>' style='color:red' />
<?php } ?>

&nbsp;
<input type='button'  class='btn btn-danger' value='<?php echo xla('Cancel'); ?>' id="close" />
</p>

</center>
</form>
</div>
</body>
</html>

