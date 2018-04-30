<?php
/*
 * These functions are common functions used in Services by category report.
 * They have been pulled out and placed in this file. This is done to prepare
 * the for building a report generator.
 *
 * Copyright (C) 2018 Tigpezeghe Rodrige <tigrodrige@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Tigpezeghe Rodrige <tigrodrige@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 */

 require_once("../globals.php");
 require_once("../../custom/code_types.inc.php");
 require_once("../../library/report_functions.php");
 require_once("$srcdir/sql.inc");
 require_once("$srcdir/headers.inc.php");
 require_once("$srcdir/formatting.inc.php");

 $filter = $_REQUEST['filter'] + 0;
 $where = "c.active = 1";
 if ($filter) $where .= " AND c.code_type = '$filter'";
 if (empty($_REQUEST['include_uncat']))
   $where .= " AND c.superbill != '' AND c.superbill != '0'";

/* This function prepares the results to be displayed
 * @parameters: void
 * @return: void
 * */
function prepareAndShowResults() {
  global $where;
  $res = sqlStatement("SELECT c.*, lo.title FROM codes AS c " .
    "LEFT OUTER JOIN list_options AS lo ON lo.list_id = 'superbill' " .
    "AND lo.option_id = c.superbill " .
    "WHERE $where ORDER BY lo.title, c.code_type, c.code, c.modifier");

  $last_category = '';
  $irow = 0;
  while ($row = sqlFetchArray($res)) {
    $category = $row['title'] ? $row['title'] : 'Uncategorized';
    $disp_category = '&nbsp';
    if ($category !== $last_category) {
      $last_category = $category;
      $disp_category = $category;
      ++$irow;
    }
    foreach ($code_types as $key => $value) {
      if ($value['id'] == $row['code_type']) {
        break;
      }
    }
    $bgcolor = (($irow & 1) ? "#ffdddd" : "#ddddff");
    echo "  <tr bgcolor='$bgcolor'>\n";
    // Added 5-09 by BM - Translate label if applicable
    echo "   <td class='text'>" . xl_list_label($disp_category) . "</td>\n";
    echo "   <td class='text'>$key</td>\n";
    echo "   <td class='text'>" . $row['code'] . "</td>\n";
    echo "   <td class='text'>" . $row['modifier'] . "</td>\n";
    echo "   <td class='text'>" . $row['units'] . "</td>\n";
    echo "   <td class='text'>" . $row['code_text'] . "</td>\n";

    if (related_codes_are_used()) {
      // Show related codes.
      echo "   <td class='text'>";
      $arel = explode(';', $row['related_code']);
      foreach ($arel as $tmp) {
        list($reltype, $relcode) = explode(':', $tmp);
        $reltype = $code_types[$reltype]['id'];
        $relrow = sqlQuery("SELECT code_text FROM codes WHERE " .
          "code_type = '$reltype' AND code = '$relcode' LIMIT 1");
        echo $relcode . ' ' . trim($relrow['code_text']) . '<br />';
      }
      echo "</td>\n";
    }

    $pres = sqlStatement("SELECT p.pr_price " .
      "FROM list_options AS lo LEFT OUTER JOIN prices AS p ON " .
      "p.pr_id = '" . $row['id'] . "' AND p.pr_selector = '' " .
      "AND p.pr_level = lo.option_id " .
      "WHERE list_id = 'pricelevel' ORDER BY lo.seq");
    while ($prow = sqlFetchArray($pres)) {
      echo "   <td class='text' align='right'>" . bucks($prow['pr_price']) . "</td>\n";
    }
    echo "  </tr>\n";
  }
}

?>
