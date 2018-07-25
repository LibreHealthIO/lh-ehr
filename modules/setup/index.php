<?php
/**
 * This is the file that will select the is responsible of detecting if the setup procedure has already been
 * if yes the redirects to the login page is no, it prompts for you to begin installation of software.
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Mua Laurent <muarachmann@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
?>

<?php
//............................................................
// handle case of previous installation (sqlconfig file check)
//............................................................

require_once ("includes/header.inc.php");

?>

    <div class="col-md-12">
        <div class="col-md-6">asds</div>
        <div class="col-md-6">
            <a href="start_up.php">Click Here to launch Installation</a>
        </div>
    </div


<?php require_once ("includes/footer.inc.php"); ?>