<?php
require_once("../../interface/globals.php");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/calendar.inc");
require_once("$srcdir/encounter_events.inc.php");
require_once("$srcdir/appointments.inc.php");

$dateFormat = DateFormatRead();
call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker"));

// create event slots according to:

// 1. Provider Vacation dates
if ($_POST['form_action'] == "vacation_submit") {
  // check if there is an appt. (either recurring or non-recurring) b/w (or on) start & end dates of vacation
  $start_date = $_POST['startDate'];
  $end_date = $_POST['endDate'];
  $fetchedEvents = fetchAllEvents($start_date, $end_date); // returns an array of events b/w start and end dates
  if (!empty($fetchedEvents)) {
    // don't make requested event slots
    // alert user and close "Add Vacation/Holiday" dialog box
    $alert_close_box = '<script type="text/javascript">
                          alert("There are already some events b/w requested start and end dates. Please try again.");
                          var addDialogBox = top.$(".dialogIframe"); // select dialog box element
                          var windowCloseBtn = addDialogBox.find(".closeDlgIframe"); // find close button element
                          windowCloseBtn.trigger("click"); // simulate "click" event on button
                        </script>';
    echo $alert_close_box;
    exit();
  }

  $event_date = $_POST['startDate'];  // Vacation starts at this date
  $event_end_date = $_POST['endDate'];  // Vacation ends at this date
  $starttime = "{$GLOBALS['schedule_start']}:00:00";
  $endtime = "{$GLOBALS['schedule_end']}:00:00";
  $provider = $_POST['provider_id'];

  // Event location specifications
  $locationspecs = array( "event_location" => "",
                          "event_street1" => "",
                          "event_street2" => "",
                          "event_city" => "",
                          "event_state" => "",
                          "event_postal" => "" );
  $locationspec = serialize($locationspecs);

  // Recurring event specifications
  $recurrspec = array( "event_repeat_freq" => "1", // every
                       "event_repeat_freq_type" => "0",  // day
                       "event_repeat_on_num" => "1",
                       "event_repeat_on_day" => "0",
                       "event_repeat_on_freq" => "0",
                       "exdate" => "" );
  $args = array();
    // specify values needed for the INSERT into table - libreehr_postcalendar_events - columns
    $args['form_category'] = "4";  // Vacation
    $args['new_multiple_value'] = "";
    $args['form_provider'] = $provider;  // selected provider
    $args['form_title'] = "Vacation";
    $args['form_comments'] = "";
    $args['event_date'] = $event_date;
    $args['form_enddate'] = $event_end_date;
    $args['duration'] = 0;  // no duration in provider events
    $args['form_repeat'] = "1";  // Repeats box checked
    $args['recurrspec'] = $recurrspec;
    $args['starttime'] = $starttime;
    $args['endtime'] = $endtime;
    $args['form_allday'] = 0;
    $args['form_apptstatus'] = "-";  // None
    $args['form_prefcat'] = "0";  // None
    $args['locationspec'] = $locationspec;

    $def_facility = sqlFetchArray(sqlStatement( "SELECT u.facility_id
                                                 FROM users u
                                                 LEFT JOIN facility f on (u.facility_id = f.id)
                                                 WHERE u.id = ?", array($_SESSION['authId']) ));
    // default_user_facility denotes id of default facility of logged in user
    $default_user_facility = $def_facility['facility_id'];
    // Taking facility & billing facility as default facility of logged in user
    $args['facility'] = $default_user_facility;
    $args['billing_facility'] = $default_user_facility;

  // Insert events - Vacation event starts at some date and repeats till end date,
  // covering Calendar slots over entire clinic hours under selected provider.
  InsertEvent($args);
  refreshCalendar(); // after "Add vacation" process is complete
}

// 2. Clinic Holiday dates
if ($_POST['form_action'] == "holiday_submit") {
  // check if there is an appt. (either recurring or non-recurring) on any of selected holiday dates
  $dateAndType = json_decode($_POST['jsonData'], true);  // convert JSON string to an associative array
  foreach ($dateAndType as $date => $type) {
    // check each selected date
    $fetchedEvents = fetchAllEvents($date, $date); // returns an array of events on $date or including $date (in case of recurring appts.)
    if (!empty($fetchedEvents)) {
      // don't make requested event slots if there's at least 1 conflicting date
      // alert user and close "Add Vacation/Holiday" dialog box
      $alert_close_box = '<script type="text/javascript">
                            alert("There are already some events b/w requested start and end dates. Please try again.");
                            var addDialogBox = top.$(".dialogIframe"); // select dialog box element
                            var windowCloseBtn = addDialogBox.find(".closeDlgIframe"); // find close button element
                            windowCloseBtn.trigger("click"); // simulate "click" event on button
                          </script>';
      echo $alert_close_box;
      exit();
    }
  }

  function createHolidayEvent($key, $value, $prov_id) {
    // $key is event date as a YYYY-MM-DD string
    // $value is day type string
    // $prov_id is id string of a provider

    $event_date = $key;  // event date
    $event_end_date = "0000-00-00";  // Since, single event
    // if $value == "Full day"
    $starttime = "{$GLOBALS['schedule_start']}:00:00";
    $endtime = "{$GLOBALS['schedule_end']}:00:00";
    if ($value == "First half") {
      $starttime = "{$GLOBALS['schedule_start']}:00:00";
      $endtime = "13:00:00";
    } elseif ($value == "Second half") {
      $starttime = "13:00:00";
      $endtime = "{$GLOBALS['schedule_end']}:00:00";
    }
    $provider = $prov_id;

    // Event location specifications
    $locationspecs = array( "event_location" => "",
                            "event_street1" => "",
                            "event_street2" => "",
                            "event_city" => "",
                            "event_state" => "",
                            "event_postal" => "" );
    $locationspec = serialize($locationspecs);

    // Recurring event specifications
    $recurrspec = array( "event_repeat_freq" => "0",
                         "event_repeat_freq_type" => "0",
                         "event_repeat_on_num" => "1",
                         "event_repeat_on_day" => "0",
                         "event_repeat_on_freq" => "0",
                         "exdate" => "" );

    $args = array();
      // specify values needed for the INSERT into table - libreehr_postcalendar_events - columns
      $args['form_category'] = "6";  // Holiday
      $args['new_multiple_value'] = "";
      $args['form_provider'] = $provider;  // selected provider
      $args['form_title'] = "Holidays";
      $args['form_comments'] = "";
      $args['event_date'] = $event_date;
      $args['form_enddate'] = $event_end_date;
      $args['duration'] = 0;  // no duration in provider events
      $args['form_repeat'] = "0";  // Repeats box not checked
      $args['recurrspec'] = $recurrspec;
      $args['starttime'] = $starttime;
      $args['endtime'] = $endtime;
      $args['form_allday'] = 0;
      $args['form_apptstatus'] = "-";  // None
      $args['form_prefcat'] = "0";  // None
      $args['locationspec'] = $locationspec;
      $args['facility'] = $_POST['clinic_id'];
      $args['billing_facility'] = $_POST['clinic_id'];

  // Insert events - A holiday event is made for each selected date,
  // covering Calendar slots according to selected day type for that date under all providers.
  InsertEvent($args);
  }

  // no conflicting dates, make requested events
  foreach ($dateAndType as $key => $value) {
    $providers = getProviderInfo();
    foreach($providers as $provider) {
      $prov_id = $provider['id'];
      createHolidayEvent($key, $value, $prov_id);
    }
  }

  refreshCalendar(); // after "Add holiday" process is complete
}

// after slots creation is done, close "Add Vacation/Holiday" dialog box
if ($_POST['form_action'] == "vacation_submit" || $_POST['form_action'] == "holiday_submit") {
  $close_box = '<script type="text/javascript">
                  var addDialogBox = top.$(".dialogIframe"); // select dialog box element
                  var windowCloseBtn = addDialogBox.find(".closeDlgIframe"); // find close button element
                  windowCloseBtn.trigger("click"); // simulate "click" event on button
                </script>';
  echo $close_box;
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
          <input type="radio" id="radio-choice-1" name="holiday-types" value="Full day" class="holiday-radio">
          <label for="radio-choice-1">Full day</label>
        </div>
        <div class="col-sm-2">
          <input type="radio" id="radio-choice-2" name="holiday-types" value="First half" class="holiday-radio">
          <label for="radio-choice-2">First Half</label>
        </div>
        <div class="col-sm-2">
          <input type="radio" id="radio-choice-3" name="holiday-types" value="Second half" class="holiday-radio">
          <label for="radio-choice-3">Second Half</label>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-2">
          <button type="button" class="btn btn-default" id="add">Add</button>
        </div>
        <div class="col-sm-2">
          <input type="hidden" name="form_action" value="holiday_submit">
          <button type="submit" class="btn btn-default" id="holiday_submit">Submit</button>
          <!-- For passing datesAndTypes object to server via JSON -->
          <input type="hidden" name="jsonData" id="jsonValue" value="">
        </div>
      </div>
    </form>
    <br>
    <table class="table table-hover col-sm-offset-1" id="date-table">
      <tr style="background-color: #ddd;">
        <th><?php echo xlt('Selected date(s)')?></th>
        <th><?php echo xlt('Full day/Half day')?></th>
      </tr>
    </table>

    <script type="text/javascript">
      var datesAndTypes = {}; // declared in global scope when loading script for first time
      $(document).ready(function () {
        // Adding selected date and day type to:
          // 1. an object - datesAndTypes
        // 2. a table - date-table
        $("#add").on("click", function () {
          // checking if date field is empty before adding
          var f = document.getElementById("clinic_form");
          if (!f.addDate.value) {
            alert('<?php echo addslashes( xl("Please select a date before adding.") ); ?>');
            return false;
          }
          // checking if any holiday radio button - full day/half day - is checked before adding
          if($(".holiday-radio").is(":checked")) {
            var dayType = $("input[name='holiday-types']:checked").val(); // either Full day or First half or Second half
          } else {
            alert('<?php echo addslashes( xl("Please specify if holiday is full day or half day.") ); ?>');
            return false;
          }

          // adding rows
          var formDate = $("input[name='addDate']").val();
          var row = '<tr class="date-row">' +
                        '<td>' + formDate + '</td>' +
                        '<td>' + dayType + '</td>' +
                    '</tr>';
          $("#date-table").append(row).fadeIn();
          datesAndTypes[formDate] = dayType;  // adding data to object in format - "date" : "day type"
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
          $(".date-row").remove();  // clear data-table rows
          $(".holiday-radio").prop("checked", false);  // uncheck all radio buttons
          datesAndTypes = {};  // clear data in object when changing tab to Provider Vacation
          $(".holiday-form").fadeOut(100, function (){
            $(".vacation-form").fadeIn(400);
          });
        });
      });
    </script>
  </div>


  <script type="text/javascript">
  $(document).ready(function () {
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
        alert('<?php echo addslashes( xl("A selected date is required for a clinic holiday.") ); ?>');
        return false;
      }
      // if clinic is not chosen
      var selected_option = $("#clinic_id option:selected").val();
      if (selected_option === "0") {
        alert('<?php echo addslashes( xl("A clinic is required.") ); ?>');
        return false;
      }
      // convert datesAndTypes object to a JSON string
      var jsonString = JSON.stringify(datesAndTypes);
      $("input[name='jsonData']").val(jsonString); // pass JSON string to an input element

      // validation done - submit form
      top.restoreSession();
      f.submit();
      return true;
    });
  });
  </script>
</body>
</html>
