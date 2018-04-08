<?php
/**
 * CDR trigger log report.
 *
 * Copyright (C) 2015 Brady Miller <brady@sparmy.com>
 * Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
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
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://librehealth.io
 */

require_once "reports_controllers/CdrLogController.php";

?>

<html>

<head>
<?php html_header_show();?>

<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">

<title><?php echo xlt('Alerts Log'); ?></title>

<script type="text/javascript" src="../../library/overlib_mini.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>
<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/js/jquery-1.9.1.min.js"></script>


<style type="text/css">

/* specifically include & exclude from printing */
@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results table {
       margin-top: 0px;
    }
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}

</style>
</head>

<body class="body_top">

<!-- Required for the popup date selectors -->
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<span class='title'><?php echo xlt('Alerts Log'); ?></span>

<form method='post' name='theform' id='theform' action='cdr_log.php?search=1' onsubmit='return top.restoreSession()'>

  <div id="report_parameters">
    <table>
      <tr>
        <td width='470px'>
          <div style='float:left'>
            <table class='text'>
              <tr>
                <?php // Show From and To dates fields. (TRK)
                  showFromAndToDates(); ?>
              </tr>
            </table>
          </div>
        </td>
        <td align='left' valign='middle' height="100%">
          <table style='border-left:1px solid; width:90%; height:100%' >
            <tr>
              <td>
                <div style='margin-left:15px'>
                  <a id='search_button' href='#' class='css_button' onclick='top.restoreSession(); $("#theform").submit()'>
                    <span>
                      <?php echo xlt('Search'); ?>
                    </span>
                  </a>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>  <!-- end of search parameters -->

  <br>

 <?php // Show results for search. (TRK)
  showResults(); ?>

</form>

</body>

<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: true,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });
</script>

</html>

