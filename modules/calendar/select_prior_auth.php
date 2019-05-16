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
<h5><?php echo xlt('Choose Authorization number from below, if no Authorization number is available then choose New Authorization Number'); ?></h5>
<?php
if (isset($_GET['pid']) && isset($_GET['appt_date'])) {
  if (!empty($_GET['pid']) && !empty($_GET['appt_date'])) {
    $pid = $_GET["pid"];
    $appointment_date = $_GET['appt_date'];
    $prior_auth = '';
    #error_log("mode: ".$_GET['mode'], 0);
    $sql = "SELECT DISTINCT f.prior_auth_number, f.auth_to, f.bodypart FROM form_enhanced_prior_auth AS f " .
           "WHERE pid = ? AND f.auth_to >= ? AND archived = '0' ORDER BY f.id DESC";
    $res = sqlStatement($sql, array($pid, $appointment_date));
#error_log(print_r($res, true));
    if (sqlNumRows($res) > 0) {
      echo "<select id='get_prior_auth' class='selection'>";
      echo "<option value='$prior_auth' id='$bodypart'></option>";
      while ($row = sqlFetchArray($res)) {
          $prior_auth = $row['prior_auth_number'];
          $bodypart = $row['bodypart'];
          echo "<option value='$prior_auth' id='$bodypart'>$prior_auth , $bodypart</option>";
      }
      echo "</select>";
      echo "<br/><br/><div class='text-center'>";
      echo "<button class='id_sender'>New Authorization number</button>";
      echo "<input type='text' placeholder='Enter the New Authorization Number' class='id_receiver' style='display: none;'>";
      echo "<input id='get_body_part' type='text' placeholder='Enter the Body Treatment Location' style='display:none; '></div>";
      echo "<input type='submit' class='cp-submit' value='Save Authorization Number' id='save_prior_auth'>";

    }
    else {
      echo "<input id='get_prior_auth' type='text' placeholder='Enter the NEW Authorization Number'>";
      echo "<input id='get_body_part' type='text' placeholder='Enter the Body Treatment Location'>";
      echo "<br/><br/><div class='text-center'>";
      echo "<input type='submit' class='cp-submit' value='Save Authorization Number' id='save_prior_auth'></div>";
    }
  }
}
?>
<script>
$('#save_prior_auth').click(function () {
        var body = '';
        var value = $('#get_prior_auth').val();
        var body = $('#get_prior_auth').children(":selected").attr("id");
        if (body == null) {
            body  = $('#get_body_part').val();
        }
        <?php if ($_GET['mode'] =='2') { ?>
            parent.$('#form_auth_2_id').val(value + "," + body);
            parent.$('#form_body_part_2').val(body);
            parent.$('#form_prior_auth_2').val(value);
            parent.$("#select_auth_2_iframe").iziModal('close');
        <?php } else { ?>
            parent.$('#form_auth_id').val(value + "," + body);
            parent.$('#form_body_part').val(body);
            parent.$('#form_prior_auth').val(value);
            parent.$("#select_auth_iframe").iziModal('close');
        <?php } ?>

});
$('.id_sender').click(function () {
    $('#get_prior_auth').attr("id", "remove");
    $('.id_receiver').attr("id", "get_prior_auth");
    $('.id_receiver').css("display", "block");
    $('.selection').css("display", "none");
    $('.id_sender').css("display", "none");
    $('#get_body_part').css("display", "block");
});
</script>
</div>