<?php
/*
 * Functions to be used in the clinical/visit/encounter forms. Any functions specific to the clinical/visit/encounter forms
 * that are used in multiple forms should be included in this file.
 *
 * @copyright Copyright (C) 2017 Terry Hill <teryhill@librehealth.io>
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

function checkFormIsActive ($form_name, $encounter)
{
   # This check if an active file exists and uses it as opposed to creating a new instance of the form.
   # This is similar to the code used by ZH in their file checks. Great Idea just not used else where.

    $query_if_exists = sqlquery("SELECT f.id FROM $form_name AS f " .
                          "INNER JOIN forms ON (f.id = forms.form_id) WHERE ".
                          "forms.deleted = 0 AND forms.encounter = ? ORDER BY f.id DESC", array($encounter));

    if (!empty($query_if_exists['id'])) {
        $formid = 0 + $query_if_exists['id'];
    }

return $formid;
}
#copy this and use case num from the calendar in place of encounter.
#used in EPA save.php
function FindCaseUsed ($form_name, $pid, $casenum )
{
   # Count the prior auths and determine the used number.
   # Pass the PID, Auth Number, and form name.
   # Make sure we have the correct auth.

   $query_get_casenum = sqlquery("SELECT f.prior_auth_number, f.auth_to FROM $form_name AS f " .
                          "WHERE pid = ? AND f.prior_auth_number = ? AND archived = '0' ", array($pid, $casenum));
    $query_casenum_count = sqlquery("SELECT count(*) AS count FROM $form_name " .
                          "WHERE pid = ? AND prior_auth_number = ? ", array($pid,$query_get_casenum['prior_auth_number']));
return $query_casenum_count;
}

function FindAuthUsed ($form_name, $pid, $encounter )
{
   # Count the prior auths and determine the used number.
   # Pass the PID, Auth Number, and form name.
   # Make sure we have the correct auth.

   $query_get_authnum = sqlquery("SELECT f.prior_auth_number, f.auth_to FROM $form_name AS f " .
                          "WHERE pid = ? AND f.case_number >= ? AND archived = '0' ORDER BY f.id DESC", array($pid, date("Y-m-d")));

    $query_auth_count = sqlquery("SELECT count(*) AS count FROM $form_name " .
                          "WHERE pid = ? AND prior_auth_number = ? ", array($pid,$query_get_authnum['prior_auth_number']));

return $query_auth_count;
}

function table_exists($table)
{
    
    $sql = "SHOW TABLES LIKE '{$table}'";
    $query = sqlQ($sql);
    $result = sqlNumRows($query);
    if( $result == '1' )
    {
            return true;
    }
    else
    {
            return false;
    }
    $result->free();
}

function is_Form_Enabled($directory_name)
{
   $sql = "SELECT state, sql_run, directory " .
      " FROM registry WHERE " .
      "directory = ? ";
    $form_enabled = sqlquery($sql, array($directory_name));
    if(($form_enabled['state'] == '1') && ($form_enabled['sql_run'] == '1'))
    {
            return true;
    }
    else
    {
            return false;
    }
    $result->free();
}

/* Remove all spaces and commas from the string passed as parameter.
 * @param: String                           $file_name
 * @return: String $file_name
*/
function removeSpaceComma($file_name) {
    $unwanted = array(" ", ",");
    return str_replace($unwanted, "_", $file_name);
}

?>
