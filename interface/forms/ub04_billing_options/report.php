<?php
/** 
* 
* Copyright (C) 2014-2017 Terry Hill <teryhill@librehealth.io> 
* 
* LICENSE: This Source Code is subject to the terms of the Mozilla Public License, v. 2.0.
* See the Mozilla Public License for more details.
* If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*
* @package LibreHealth EHR
* @author Terry Hill <teryhill@librehealth.io>
* @link http://librehealth.io
*
* Please help the overall project by sending changes you make to the authors and to the LibreHealth EHR community.
*
*/
include_once("../../globals.php");
include_once($GLOBALS["srcdir"]."/api.inc");


function ub04_billing_options_report( $pid, $encounter, $cols, $id) {
    $count = 0;
    $data = formFetch("form_ub04_billing_options", $id);
    if ($data) {
    print "<table><tr>";
       foreach($data as $key => $value) {
            if ($key == "id" || $key == "pid" || $key == "user" || 
			$key == "groupname" || $key == "authorized" || $key == "activity" || 
			$key == "date" || $value == "" || $value == "0" || $value == "0000-00-00 00:00:00" || $value =="0000-00-00") 
			{
                continue;
            }
        

           if($key==='attending_id' || $key==='operating_id' || $key==='other_1_id' || $key==='other_2_id')
            {
                
                $trow = sqlQuery("SELECT id, lname, mname, fname FROM users WHERE ".
                         "id = ? ",array($value));
                $value=$trow['fname'] . ' ' . $trow['mname'] . ' ' . $trow['lname'];
                
            }
            
           if($key==='admit_source')
            {
                
                $trow = sqlQuery("SELECT option_id, title FROM list_options WHERE ".
                         "list_id = ? AND activity=1 AND option_id = ?", array('ub_admit_source',$value));
                $value=$trow['title'];
                
            }
            
           if($key==='admit_type')
            {
                
                $trow = sqlQuery("SELECT option_id, title FROM list_options WHERE ".
                         "list_id = ? AND activity=1 AND option_id = ?", array('ub_admit_type',$value));
                $value=$trow['title'];
                
            }    
            
            $key=ucwords(str_replace("_"," ",$key));
            print "<td><span class=bold>$key: </span><br><span class=text>$value</span></td>";
            $count++;
            if ($count == $cols) 
			{
                $count = 0;
                print "</tr><tr>\n";
            }
        }
    }
    print "</tr></table>";
}
?> 
