<?php
 /**
  *  Copyright Medical Information Integration,LLC info@mi-squared.com
 * 
 * LICENSE: This Source Code is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * Rewrite and modifications by sjpadgett@gmail.com Padgetts Consulting 2016.
 *
 * @package LibreHealth EHR
 * @author  Medical Information Integration,LLC <info@mi-squared.com>
 * @link    http://librehealth.io
  */

$sanitize_all_escapes=true;
$fake_register_globals=false;
  
/* include globals.php, required. */
require_once('../../globals.php');

/* include api.inc. also required. */
require_once($GLOBALS['srcdir'].'/api.inc');

/* include our smarty derived controller class. */
require('C_FormAnnotate.class.php');

/* Create a form object. */
$c = new C_FormAnnotate();

/* Save the form contents .*/
echo $c->default_action_process($_POST);

/* return to the encounter. */
@formJump();
?>
