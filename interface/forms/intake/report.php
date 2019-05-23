<?php
//------------Forms created by elizabeth 2019/06/24
include_once(dirname(__FILE__).'/../../globals.php');
include_once($GLOBALS["srcdir"]."/api.inc");
function intake_report( $pid, $encounter, $cols, $id) {
$count = 0;
$data = formFetch("form_intake", $id);
if ($data) {
print "<table><tr>";
foreach($data as $key => $value) {
if ($key == "id" || $key == "pid" || $key == "user" || $key == "groupname" || $key == "authorized" || $key == "activity" || $key == "date" || 
	$value == "" || $value == " " || $value == "0000-00-00 00:00:00") {
	continue;
}

$key=ucwords(str_replace("____",") ",$key));
$key=ucwords(str_replace("___"," (",$key));
$key=ucwords(str_replace("__","/",$key));
$key=ucwords(str_replace("_"," ",$key));

print "<td><span class=bold>$key: </span><span class=text>$value</span></td>";
$count++;
if ($count == $cols) {
$count = 0;
print "</tr><tr>\n";
}
}
}
print "</tr></table>";
}
?>