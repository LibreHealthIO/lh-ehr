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

?>
