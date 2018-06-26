<?php
/*
 *  report.php used by the Enhanced Prior Authorization
 *
 *  This program is used by the enhanced_prior_authorization
 *
 * @copyright Copyright (C) 2018 Terry Hill <teryhill@librehealth.io>
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
include_once(dirname(__FILE__).'/../../globals.php');
include_once($GLOBALS["srcdir"]."/api.inc");
include_once("$srcdir/formatting.inc.php");
function enhanced_prior_auth_report( $pid, $encounter, $cols, $id) {
    $count = 0;
    $data = formFetch("form_enhanced_prior_auth", $id);
    if ($data) {
    print "<table><tr>";
        foreach($data as $key => $value) {
            if ($key == "id" || $key == "pid" || $key == "user" || $key == "groupname" || $key == "authorized" || $key == "activity" || $key == "date" || $value == "" || $value == "0" || $value == "0000-00-00 00:00:00" || $value =="0000-00-00") {
                continue;
            }
            if($key!=='used') {
             if ($value == "1") {
                $value = "Yes";
             }
            }
            if($key==='prior_auth_number')
            {
               $key = ' Prior Authorization Number ';
               $value = $value . " ";
            }
            if($key==='auth_for')
            {
               $key = ' Authorized for ';
               $value = $value . ' Visits';
            }
            if($key==='ddesc')
            {
               $key = ' Description ';
            }
            if($key==='auth_from')
            {
                $key = ' Authorized from ';
               $value = oeFormatShortDate($value);
            }
            if($key==='auth_to')
            {
               $key = ' Authorized to ';
               $value = oeFormatShortDate($value);
            }
            $key=ucwords(str_replace("_"," ",$key));
            print "<td><span class=bold> &nbsp;&nbsp;$key: </span><span class=text>" . text($value) . "</span></td>";
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
