<?php
/**
 * interface/patient_file/ccr_import.php Upload screen and parser for the CCR XML.
 *
 * Functions to upload the CCR XML and to parse and insert it into audit tables.
 *
 * Copyright (C) 2013 Z&H Consultancy Services Private Limited <sam@zhservices.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @author  Eldho Chacko <eldho@zhservices.com>
 * @author  Ajil P M <ajilpm@zhservices.com>
 * @link    http://librehealth.io
 */

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once(dirname(__FILE__) . "/../globals.php");
require_once("$srcdir/headers.inc.php");

?>
<html>
  <head>
      <title>
          <?php echo xlt('Import');?>
      </title>
      <span class="title" style="display: none"><?php echo xlt("Steps for uploading CCR XML");?></span>
      <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
      <?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1']); ?>
  </head>

  <body class="body_top">
      <center>
          <h2><?php echo xlt("Steps for uploading CCR XML");?></h2>
          <table class="table table-bordered table-hover" style="width:85%;font-size:14px;">
              <tr>
                  <td>
                      <?php echo xlt('1. To upload CCR document of already existing patient use Patient Summary Screen->Documents. For CCR document of a new patient use Miscellanous->New Documents screen').'.'; ?>
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php echo xlt('2. Upload the xml file under the category CCR').'.'; ?>
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php echo xlt('3. After Uploading click the button "Import"').'.'; ?>
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php echo xlt('4. Approve the patient from Patient/Client->Import->Pending Approval').'.'; ?>
                  </td>
              </tr>
          </table>
      </center>
  </body>
</html>