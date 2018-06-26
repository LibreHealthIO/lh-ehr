<?php
// Copyright (C) 2010-2012 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("$srcdir/formdata.inc.php");

$id = formData('id','G') + 0;
$order = formData('order','G') + 0;
$labid = formData('labid','G') + 0;

echo "$('#con$id').html('<table class=\"table striped table-condensed noborder\" width=\"100%\" cellspacing=\"0\">";

// Determine indentation level for this container.
for ($level = 0, $parentid = $id; $parentid; ++$level) {
  $row = sqlQuery("SELECT parent FROM procedure_type WHERE procedure_type_id = '$parentid'");
  $parentid = $row['parent'] + 0;
}

$res = sqlStatement("SELECT * FROM procedure_type WHERE parent = '$id' " .
  "ORDER BY seq, name, procedure_type_id");

$encount = 0;

// Generate a table row for each immediate child.
while ($row = sqlFetchArray($res)) {
  $chid = $row['procedure_type_id'] + 0;

  // Find out if this child has any children.
  $trow = sqlQuery("SELECT procedure_type_id FROM procedure_type WHERE parent = '$chid' LIMIT 1");
  $iscontainer = !empty($trow['procedure_type_id']);

  $classes = 'col1';
  if ($iscontainer) {
    $classes .= ' haskids';
  }

  echo "<tr>";
  echo "<td style=\"width:20%\" id=\"td$chid\"";
  echo " onclick=\"toggle($chid)\"";
  echo " class=\"$classes\">";
  echo "<a style=\"margin:0 4 0 " . ($level * 9) . "pt\" class=\" btn btn-secondary plusminus\">";
  echo $iscontainer ? "+" : '|';
  echo "</a>";
  echo htmlspecialchars($row['name'], ENT_QUOTES) . "</td>";
  //
  echo "<td style=\"width:20%\" class=\"  text-center col2\">";
  if (substr($row['procedure_type'], 0, 3) == 'ord') {
    if ($order && ($labid == 0 || $row['lab_id'] == $labid)) {
      echo "<input type=\"radio\" name=\"form_order\" value=\"$chid\"";
      if ($chid == $order) echo " checked";
      echo " />";
    }
    else {
      echo xl('Yes');
    }
  }
  else {
    echo '&nbsp;';
  }
  echo "</td>";
  //
  echo "<td style=\"width:20%\" class=\" text-center col3\">" . htmlspecialchars($row['procedure_code'], ENT_QUOTES) . "</td>";
  echo "<td style=\"width:20%\" class=\" text-center col4\">" . htmlspecialchars($row['description'], ENT_QUOTES) . "</td>";
  echo "<td style=\"width:20%\" class=\" text-center col5\">";
  echo "<a href=\"types_edit.php?parent=0&typeid=$chid\" style=\" margin-right: 5px; \" class=\"btn trigger btn-primary haskids \">" . xl('Edit') . "</a> ";
  echo "<a href=\"types_edit.php?typeid=0&parent=$chid\" class=\"btn trigger btn-primary haskids \"> " . xl('Add') . "</button>";
  echo "</td>";
  echo "</tr>";
}

echo "</table>');\n";
echo "nextOpen();\n";
?>
