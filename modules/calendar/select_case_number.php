<?php
/*
 * Used to pass the Authorization number to add_edit_event.php
 *
 * Copyright (C) 2018 Terry Hill <teryhill@librehealth.io>
 * Copyright (C) 2018 Naveen Muthusamy <kmnaveen101@gmail.com>
 *
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Naveen Muthusamy <kmnaveen101@gmail.com>
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */

require_once('../../interface/globals.php');
require_once("$srcdir/formdata.inc.php");
require_once($GLOBALS['srcdir'].'/headers.inc.php');
call_required_libraries(array("jquery-min-3-1-1","bootstrap"));
?>
<div class="col-xs-12 text-center">
<h5><?php echo xlt('Choose Case Number from List'); ?></h5>
<?php
if (isset($_GET['pid']) && isset($_GET['appt_date'])) {
  if (!empty($_GET['pid']) && !empty($_GET['appt_date'])) {
    $pid = $_GET["pid"];
    $appointment_date = $_GET['appt_date'];
    $case_number = '';

    $sql = "SELECT ptc.case_number, inj.title FROM pt_case AS ptc
            LEFT JOIN pt_case_info as pci ON ptc.case_number = pci.pci_case_number
            LEFT JOIN list_options as inj ON pci.pci_case_injury_type collate utf8_general_ci = inj.option_id
            WHERE ptc.pc_patient_id = ?
            ORDER BY ptc.case_number DESC";
    $res = sqlStatement($sql, array($pid));

    if (sqlNumRows($res) > 0) {
      echo "<select id='get_case_number' class='selection'>";
      echo "<option value='$case_number' id='$bodypart'></option>";
      while ($row = sqlFetchArray($res)) {
          $case_number = $row['case_number'];
          $bodypart = $row['title'];
          echo "<option value='$case_number' id='$bodypart'>$case_number , $bodypart</option>";
      }
      echo "</select>";
      echo "<br/><br/><div class='text-center'>";
     // echo "<button class='id_sender'>New Case number</button>";
     // echo "<input type='text' placeholder='Enter the New Case Number' class='id_receiver' style='display: none;'>";
     // echo "<input id='get_body_part' type='text' placeholder='Enter the Body Treatment Location' style='display:none; '></div>";
      echo "<input type='submit' class='cp-submit' value='Save Case Number' id='save_case_number'>";

    }
    else {
      echo "No Case Numbers Available";
      #echo "<input id='get_case_number' type='text' placeholder='Enter the NEW Case Number'>";
      #echo "<input id='get_body_part' type='text' placeholder='Enter the Body Treatment Location'>";
      #echo "<br/><br/><div class='text-center'>";
      #echo "<input type='submit' class='cp-submit' value='Save Case Number' id='save_case_number'></div>";
    }
  }
}
?>
<script>
$('#save_case_number').click(function () {
        var body = '';
        var value = $('#get_case_number').val();
        var body = $('#get_case_number').children(":selected").attr("id");
        if (body == null) {
            body  = $('#get_body_part').val();
        }
        parent.$('#form_case_number_id').val(value + "," + body);
        parent.$('#form_case_body_part').val(body);
        parent.$('#form_case_number').val(value);
        parent.$("#select_case_number_iframe").iziModal('close');

});
$('.id_sender').click(function () {
    $('#get_case_number').attr("id", "remove");
    $('.id_receiver').attr("id", "get_case_number");
    $('.id_receiver').css("display", "block");
    $('.selection').css("display", "none");
    $('.id_sender').css("display", "none");
    $('#get_body_part').css("display", "block");
});
</script>
</div>