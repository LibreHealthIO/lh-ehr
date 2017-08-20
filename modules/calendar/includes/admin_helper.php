<?php 

function getCategories() {
  $query = "SELECT * FROM libreehr_postcalendar_categories ORDER BY pc_catname;";
  $res = sqlStatement($query);
  return $res;
}


?>
