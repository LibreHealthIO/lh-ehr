<?php

function getCategories() {
  $query = "SELECT * FROM libreehr_postcalendar_categories ORDER BY pc_catname;";
  $res = sqlStatement($query);
  return $res;
}

function createUpdateCategory($catid, $catname, $catcol, $catdes, $cattype, $durmins, $allday, $new=TRUE, $caticon, $catIconColor, $catIconBgColor, $catSeq) {
  if($allday == 1) {
    $duration = 0;
  } else {
    $duration = $durmins*60;
  }

  if($new) {
    $query = "INSERT INTO libreehr_postcalendar_categories(pc_catname,
      pc_catcolor, pc_catdesc, pc_cattype, pc_duration, pc_end_all_day, pc_categories_icon, pc_icon_color, pc_icon_bg_color, pc_seq)
      VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
      $res = sqlStatement($query, array($catname, $catcol, $catdes, $cattype, $duration, $allday, $caticon, $catIconColor, $catIconBgColor, $catSeq));
  } else {
    $query = "UPDATE libreehr_postcalendar_categories SET
      pc_catname = ?, pc_catcolor = ?, pc_catdesc = ?,
      pc_cattype = ?, pc_duration = ?, pc_end_all_day = ?, pc_categories_icon = ?,
      pc_icon_color = ?, pc_icon_bg_color = ?, pc_seq = ?
      WHERE pc_catid = ?;";
    $res = sqlStatement($query, array($catname, $catcol, $catdes, $cattype, $duration, $allday, $caticon, $catIconColor, $catIconBgColor, $catSeq, $catid));
  }
  return $res;
}

function deleteCategory($catid) {
  $query = "DELETE FROM libreehr_postcalendar_categories WHERE pc_catid = ?";
  $res = sqlStatement($query, array($catid));
  return $res;
}

?>
