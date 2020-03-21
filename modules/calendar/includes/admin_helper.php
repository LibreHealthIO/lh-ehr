<?php 

function getCategories() {
  $query = "SELECT * FROM libreehr_postcalendar_categories ORDER BY pc_catname;";
  $res = sqlStatement($query);
  return $res;
}

function createUpdateCategory($catid, $catname, $catcol, $catdes, $cattype, $durmins, $allday, $new=TRUE) {
  if($allday == 1) {
    $duration = 0;
  } else {
    $duration = $durmins*60;
  }
  
  if($new) {
    $query = "INSERT INTO libreehr_postcalendar_categories(pc_catname, 
      pc_catcolor, pc_catdesc, pc_cattype, pc_duration, pc_end_all_day)
      VALUES(?, ?, ?, ?, ?, ?);";
      $res = sqlStatement($query, array($catname, $catcol, $catdes, $cattype, $duration, $allday));
  } else {
    $query = "UPDATE libreehr_postcalendar_categories SET 
      pc_catname = ?, pc_catcolor = ?, pc_catdesc = ?,
      pc_cattype = ?, pc_duration = ?, pc_end_all_day = ? 
      WHERE pc_catid = ?;";
    $res = sqlStatement($query, array($catname, $catcol, $catdes, $cattype, $duration, $allday, $catid));
  }
  return $res;
}

function deleteCategory($catid) {
  $query = "DELETE FROM libreehr_postcalendar_categories WHERE pc_catid = ?";
  $res = sqlStatement($query, array($catid));
  return $res;
}

?>
