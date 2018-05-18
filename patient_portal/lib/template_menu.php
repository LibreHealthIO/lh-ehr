<?php
/**
 *
 * Patient Portal template_menu.php
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the authors and to the LibreHealth EHR community.
 *
 */

foreach (glob($GLOBALS['OE_SITE_DIR'] . "/documents/onsite_portal_documents/templates/*.tpl") as $filename) {
    $basefile = basename($filename,".tpl");
    $btnname = str_replace('_', ' ',$basefile);
    $btnfile = $basefile . '.tpl';

    echo '<li class="bg-success"><a id="' . $basefile . '"' . 'href="#" onclick="page.newDocument(' . "<%= cpid %>,'<%= cuser %>','$btnfile')".'"'.">$btnname</a></li>";
}
foreach (glob($GLOBALS['OE_SITE_DIR'] . "/documents/onsite_portal_documents/templates/" . $pid . "/*.tpl") as $filename) {
    $basefile = basename($filename,".tpl");
    $btnname = str_replace('_', ' ',$basefile);
    $btnfile = $basefile . '.tpl';

    echo '<li class="bg-success"><a id="' . $basefile . '"' . 'href="#" onclick="page.newDocument(' . "<%= cpid %>,'<%= cuser %>','$btnfile')".'"'.">$btnname</a></li>";
}

?>