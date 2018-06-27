<?php
require_once("../../interface/globals.php");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/calendar.inc");

$dateFormat = DateFormatRead();
call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker"));

// create event slots according to:

// 1. Provider Vacation dates
if ($_POST['form_action'] == "vacation_submit") {

}

// 2. Clinic Holiday dates
if ($_POST['form_action'] == "holiday_submit") {

}

// after slots creation is done, close "Add Vacation/Holiday" window
if ($_POST['form_action'] == "vacation_submit" || $_POST['form_action'] == "holiday_submit") {
  $close_window = '<script type="text/javascript">window.close();</script>';
  echo $close_window;
  exit();
}

?>
<html>
<head>
  <link href="css/add.css" rel="stylesheet" />
  <script type="text/javascript" src="../../library/dialog.js"></script>
</head>
<body>

  <div class="container-fluid">
    <div class="row title">
      <h3><?php echo htmlspecialchars(xl('Add Vacation/Holiday'), ENT_NOQUOTES); ?></h3>
    </div>

    <!--Vacation & Holiday tab options-->
    <div class="row title">
      <h3><button type="button" class="btn btn-default btn-sm col-sm-offset-1 col-sm-4" id="vacation-btn">Provider Vacation</button></h3>
      <h3><button type="button" class="btn btn-default btn-sm col-sm-offset-1 col-sm-4" id="holiday-btn">Clinic Holiday</button></h3>
    </div>


    <!--Vacation & Holiday tabs-->
    <!--Add Vacation tab-->
    <form class="form-horizontal vacation-form" id="provider_form" action="add.php" method="POST" style="margin-top: 20px;">
      <div class="form-group">
        <label class="control-label col-sm-2" for="startDate">Start date</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Date">
        </div>
        <label class="control-label col-sm-2" for="endDate">End date</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="endDate" name="endDate" placeholder="Date">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="provider_id">Provider</label>
        <div class="col-sm-10">
          <select class="form-control" id="provider_id" name="provider_id">
            <option value="0">Choose Provider</option>
            <?php
            $providers = getProviderInfo();
            foreach($providers as $provider) {
              $id = $provider['id'];
              $selected = ($_POST['provider_id'] == $id) ? "selected" : "";
              echo "<option value='$id' $selected>" . htmlspecialchars($provider['lname'],ENT_QUOTES) . ", " . htmlspecialchars($provider['fname'],ENT_QUOTES) . "</option>\n";
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <input type="hidden" name="form_action" value="vacation_submit">
          <button type="submit" class="btn btn-default" id="vacation_submit">Submit</button>
        </div>
      </div>
    </form>
    <!--Add Vacation tab-->


    <!--Add Holiday tab-->
    <form class="form-horizontal holiday-form" id="clinic_form" action="add.php" method="POST" style="margin-top: 20px;">
      <div class="form-group">
        <label class="control-label col-sm-2" for="addDate">Add date</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="addDate" name="addDate" placeholder="Date">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="clinic_id">Clinic</label>
        <div class="col-sm-10">
          <select class="form-control" id="clinic_id" name="clinic_id">
            <option value="0">Choose Clinic/Facility</option>
            <?php
              $pref_facility = sqlFetchArray(sqlStatement( "SELECT u.facility_id
                                                            FROM users u
                                                            LEFT JOIN facility f on (u.facility_id = f.id)
                                                            WHERE u.id = ?", array($_SESSION['authId']) ));
              //e2f denotes id of default facility of logged in user
              $e2f = $pref_facility['facility_id'];

              $facils = getUserFacilities($_SESSION['authId']);
              //$_SESSION['authId'] is id of logged in user
              //ufid is an array of id of schedule facilities of that user
              $ufid = array();
              foreach ($facils as $uf) {
                $ufid[] = $uf['id'];
              }
              $qsql = sqlStatement("SELECT id, name FROM facility WHERE service_location != 0");
              while ($facrow = sqlFetchArray($qsql)) {
                if ($GLOBALS['restrict_user_facility']) {
                  //if restricting user to scheduling events at only their allowed facilities
                  //then list only schedule facilities of that user or default facility
                  //in Clinic drop down in Calendar's Add Vacation/Holiday panel
                  if (in_array($facrow['id'], $ufid) || $facrow['id'] == $e2f) {
                    $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
                    echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
                  }
                } else {
                  //if not restricting then list all facilities
                  //where service_location is not 0 including default facility
                  //in Clinic drop down in Calendar's Add Vacation/Holiday panel
                  $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
                  echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
                }
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-2">
          <button type="button" class="btn btn-default" id="add">Add</button>
        </div>
        <div class="col-sm-2">
          <input type="hidden" name="form_action" value="holiday_submit">
          <button type="submit" class="btn btn-default" id="holiday_submit">Submit</button>
        </div>
      </div>
    </form>
    <br>
    <table class="table table-hover col-sm-offset-1" id="date-table">
      <tr style="background-color: #ddd;">
        <th><?php echo xlt('Selected date(s)')?></th>
      </tr>
    </table>

    <script type="text/javascript">
      $(document).ready(function () {
        // Adding selected date to date-table
        $("#add").on("click", function () {
          //checking if date field is empty before adding
          var f = document.getElementById("clinic_form");
          if (!f.addDate.value) {
            alert('<?php echo addslashes( xl("Please select a date before adding.") ); ?>');
            return false;
          }

          var dateForm = $("input#addDate").val();
          var row = '<tr class="date-row">' +
                        '<td>' + dateForm + '</td>'
                    '</tr>';
          $("#date-table").append(row).fadeIn();
        });
      });
    </script>
    <!--Add Holiday tab-->


    <!-- Controlling tabs -->
    <script type="text/javascript">
      $(document).ready(function () {
        $("#holiday-btn").on("click", function () {
          $(".vacation-form").fadeOut(100, function (){
            $(".holiday-form").fadeIn(400);
          });
        });

        $("#vacation-btn").on("click", function () {
          $("#date-table").fadeOut(100);
          $(".date-row").remove();
          $(".holiday-form").fadeOut(100, function (){
            $(".vacation-form").fadeIn(400);
          });
        });
      });
    </script>
  </div>


  <script>
    // Date Picking
    $('#addDate').datetimepicker({
      timepicker: false,
      format: '<?php echo $dateFormat; ?>',
      formatDate: '<?php echo $dateFormat; ?>',
    });
    $('#startDate').datetimepicker({
      timepicker: false,
      format: '<?php echo $dateFormat; ?>',
      formatDate: '<?php echo $dateFormat; ?>',
      onShow: function( ct ){
        this.setOptions({
          maxDate: $('#endDate').val() ? $('#endDate').val() : false  // limit ending date choices if end date available
       })
      }
    });
    $('#endDate').datetimepicker({
      timepicker: false,
      format: '<?php echo $dateFormat; ?>',
      formatDate: '<?php echo $dateFormat; ?>',
      onShow: function( ct ){
        this.setOptions({
          minDate: $('#startDate').val() ? $('#startDate').val() : false  // limit starting date choices if start date available
       })
      }
    });

    // Submitting Provider Vacation dates
    $("#vacation_submit").click(function () {
      // check for errors/empty inputs in Provider Vacation form
      var f = document.getElementById("provider_form");
      // in starting date and ending date
      if (!f.startDate.value || !f.endDate.value) {
        alert('<?php echo addslashes( xl("A start date and an end date are both required for the range of vacation.") ); ?>');
        return false;
      }
      // if provider is not chosen
      var selected_option = $("#provider_id option:selected").val();
      if (selected_option === "0") {
        alert('<?php echo addslashes( xl("A provider is required.") ); ?>');
        return false;
      }
      // if there is an appt. on any of requested dates

      // validation done - submit form
      top.restoreSession();
      f.submit();
      return true;
    });

    // Submitting Clinic Holiday dates
    $("#holiday_submit").click(function () {
      // check for errors/empty inputs in Clinic Holiday form
      var f = document.getElementById("clinic_form");
      // in add date
      if (!f.addDate.value) {
        alert('<?php echo addslashes( xl("A selected date(s) is required for a clinic holiday.") ); ?>');
        return false;
      }
      // if clinic is not chosen
      var selected_option = $("#clinic_id option:selected").val();
      if (selected_option === "0") {
        alert('<?php echo addslashes( xl("A clinic is required.") ); ?>');
        return false;
      }
      // if there is an appt. on any of requested dates

      // validation done - submit form
      top.restoreSession();
      f.submit();
      return true;
    });
  </script>
</body>
</html>
