<?php
/*
 *  add.php - Interface and processing file for Add Vacation/Holiday feature in Calendar
 *
 * Copyright (C) 2018 Apoorv Choubey < theapoorvs1@gmail.com >
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Apoorv Choubey < theapoorvs1@gmail.com >
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
require_once("../../interface/globals.php");
require_once("$srcdir/headers.inc.php");
require_once("$srcdir/htmlspecialchars.inc.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/calendar.inc");
require_once("$srcdir/encounter_events.inc.php");
require_once("$srcdir/appointments.inc.php");
$dateFormat = DateFormatRead();

 $starttimem = '00';
if (isset($_GET['starttimem'])) {
  $starttimem = substr('00' . $_GET['starttimem'], -2);
}
 $endtimem = '00';
//
if (isset($_GET['endtimem'])) {
  $endtimem = substr('00' . $_GET['endtimem'], -2);
}
 //
 if (isset($_GET['starttimeh'])) {
  $starttimeh = $_GET['starttimeh'];
  if (isset($_GET['startampm'])) {
        if ($_GET['startampm'] == '2' && $starttimeh < 12) {
    $starttimeh += 12;
  }
    }
 } else {
  $starttimeh = date("G");
 }
  if (isset($_GET['endtimeh'])) {
  $endtimeh = $_GET['endtimeh'];
  if (isset($_GET['endampm'])) {
        if ($_GET['endampm'] == '2' && $endtimeh < 12) {
    $endtimeh += 12;
  }
    }
 } else {
  $endtimeh = date("G");
 }
 if (!isset($_GET['endtimeh'])) {
   $sched_stop = ($GLOBALS['schedule_end']);
   $endtimeh = $sched_stop;
  }
 $startampm = '';
 $endampm = '';
$startampm = '1';
 $endampm = '1';
 if ($starttimeh >= 12) { // p.m. starts at noon and not 12:01
  $startampm = '2';
      if ( $GLOBALS['time_display_format'] == 1) {
        if ($starttimeh > 12) {
            $starttimeh -= 12;
        }
    } else {
        if ($starttimeh > 24) {
            $starttimeh -= 12;
        }
    }
 }
  if ($endtimeh >= 12) { // p.m. starts at noon and not 12:01
  $endampm = '2';
      if ( $GLOBALS['time_display_format'] == 1) {
        if ($endtimeh > 12) {
            $endtimeh -= 12;
        }
    } else {
        if ($endtimeh > 24) {
            $endtimeh -= 12;
    }
 }
}

call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker"));
// create event slots according to:
// 1. Provider Vacation dates
if ($_POST['form_action'] == "vacation_submit") {
  // check if there is an appt. (either recurring or non-recurring) b/w (or on) start & end dates of vacation
  $start_date = $_POST['startDate'];
  $end_date = $_POST['endDate'];
  $fetchedEvents = fetchAllEvents($start_date, $end_date, $_POST['provider_id']); // returns an array of events b/w start and end dates for selected provider
  if (!empty($fetchedEvents)) {
    // don't make requested event slots,
    // alert user and close "Add Vacation/Holiday" dialog box
    $alert_close_box = '<script type="text/javascript">
                          alert("There are already some events between the requested Vacation start and end dates. Please try again.");
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
  if ($_POST['holiday_action'] == "Delete Holiday") {
    // user requests to delete a set of holidays
    $dateAndType = json_decode($_POST['jsonData'], true);  // convert JSON string to an associative array
    // this associative array contains elements in form of "date" => array("day type", "facility id")
    foreach ($dateAndType as $date => $value) {
      // for each set of date and facility, delete holiday event under all providers
      // no need to iterate through providers in this case
#      error_log("delete: ".$value[1], 0);
      if($value[1] == 'all') {
        sqlStatement("DELETE FROM libreehr_postcalendar_events WHERE pc_eventDate = ? AND pc_catid = '7'", array($date));
      }else{
        sqlStatement("DELETE FROM libreehr_postcalendar_events WHERE pc_eventDate = ? AND pc_catid = '7' AND pc_facility = ?", array($date, $value[1]));
      }
    }
  } elseif ($_POST['holiday_action'] == "Add Holiday") {
    // user requests to add a set of holidays
    // check if there is an appt. (either recurring or non-recurring) on at least one of selected holiday dates
    $dateAndType = json_decode($_POST['jsonData'], true);  // convert JSON string to an associative array
    // this associative array contains elements in form of "date" => array("day type", "facility id")
    foreach ($dateAndType as $date => $value) {
      // check each selected date
      $fetchedEvents = fetchAllEvents($date, $date); // returns an array of events on $date or including $date (in case of recurring appts.)
      if (!empty($fetchedEvents)) {
        foreach ($fetchedEvents as $event) {
          // check if event is a patient event
#          error_log(print_r($event, true));
          #error_log("end time form: ".$event['pc_pid'], 0);
          if ($event['pc_pid'] != 0) {
              if($event['pc_facility'] == $value[1]) {
            // don't make requested event slots,
            // alert user and close "Add Vacation/Holiday" dialog box
            $alert_close_box = '<script type="text/javascript">
                                  alert("There are already some patient events with same facility on at least one of the selected dates. Please try again.");
                                  var addDialogBox = top.$(".dialogIframe"); // select dialog box element
                                  var windowCloseBtn = addDialogBox.find(".closeDlgIframe"); // find close button element
                                  windowCloseBtn.trigger("click"); // simulate "click" event on button
                                </script>';
            echo $alert_close_box;
            exit();
              }
          } else {
            // event is provider event, move onto next event
          }
        }
      }
    }
    function createHolidayEvent($date, $value, $prov_id, $fac_id) {
      // $date is event date as a YYYY-MM-DD string
      // $value is an array consisting of day type string followed by facility id string
      // $prov_id is id string of a provider
      $event_date = $date;  // event date
      $event_end_date = "0000-00-00";  // Since, single event
      // if $value[0] == "Full day"
      $starttime = "{$GLOBALS['schedule_start']}:00:00";
      $endtime = "{$GLOBALS['schedule_end']}:00:00";
      if ($value[0] == "First half") {
        $starttime = "{$GLOBALS['schedule_start']}:00:00";
        $endtime = "13:00:00";
      } elseif ($value[0] == "Second half") {
        $starttime = "13:00:00";
        $endtime = "{$GLOBALS['schedule_end']}:00:00";
      } elseif ($value == "time") {
       $tmph = 0;
      $tmpm = 0;
      $etmph = 0;
      $etmpm = 0;
      $tmph = $_POST['form_hour'] + 0;
      $tmpm = $_POST['form_minute'] + 0;
        if ($_POST['form_ampm'] == '2' && $tmph < 12){
             $tmph += 12;
        }
      $tmph = sprintf( "%02d", $tmph );
      $tmpm = sprintf( "%02d", $tmpm );
      $starttime = "$tmph:$tmpm:00";
      $etmph = $_POST['form_hour_end'] + 0;
      $etmpm = $_POST['form_minute_end'] + 0;
       if ($_POST['form_ampm_end'] == '2' && $etmph < 12){
           $etmph += 12;
       }
      $endtime = "$etmph:$etmpm:00";
    }
 #     }
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
        $args['form_category'] = "7";  // Holiday
        $args['new_multiple_value'] = "";
        $args['form_provider'] = $provider;  // selected provider
        $args['form_title'] = "Closure";
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
        // Taking facility & billing facility as selected facility of holiday
        $args['facility'] = $fac_id;
        $args['billing_facility'] = $fac_id;
        #error_log("value[1]: ".$fac_id, 0);
        #error_log(print_r($value, true));
      // Insert events - A holiday event is made for each selected date,
      // covering Calendar slots according to selected day type for that date with selected facility under all providers.
      InsertEvent($args);
    }
    // reached here, means no conflicting dates, make requested events
#    error_log(print_r($dateAndType, true));
    foreach ($dateAndType as $date => $value) {
        if($value[1] == 'all') {
           $providers = getProviderInfo();
        }else{
           $providers = getProviderInfo( "%", '', $value[1]);
        }
#      error_log(print_r($providers, true));
#      error_log("providers: ".$providers, 0);
      foreach($providers as $provider) {
        $prov_id = $provider['id'];
        if($value[1] == 'all') {
          $fac_id = $provider['facility_id'];
        } else{
          $fac_id = $value[1];
        }
        createHolidayEvent($date, $value, $prov_id, $fac_id );
      }
    }
  }
  refreshCalendar(); // after "Add/Delete holiday" process is complete
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
      <h3><?php echo htmlspecialchars(xl('Add Vacation/Closure'), ENT_NOQUOTES); ?></h3>
    </div>

    <!--Vacation & Holiday tab options-->
    <div class="row title">
      <h3><button type="button" class="btn btn-default btn-sm col-sm-offset-1 col-sm-4" id="vacation-btn"><?php echo xlt('Provider Vacation'); ?></button></h3>
      <h3><button type="button" class="btn btn-default btn-sm col-sm-offset-1 col-sm-4" id="holiday-btn"><?php echo xlt('Clinic Closure'); ?></button></h3>
    </div>


    <!--Vacation & Holiday tabs-->
    <!--Add Vacation tab-->
    <form class="form-horizontal vacation-form" id="provider_form" action="add.php" method="POST" style="margin-top: 20px;">
      <div class="form-group">
        <label class="control-label col-sm-2" for="startDate"><?php echo xlt('Start date'); ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Date">
        </div>
        <label class="control-label col-sm-2" for="endDate"><?php echo xlt('End date'); ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="endDate" name="endDate" placeholder="Date">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="provider_id"><?php echo xlt('Provider'); ?></label>
        <div class="col-sm-10">
          <select class="form-control" id="provider_id" name="provider_id">
            <option value="0"><?php echo xlt('Choose Provider'); ?></option>
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
          <button type="submit" class="btn btn-default" id="vacation_submit"><?php echo xlt('Submit'); ?></button>
        </div>
      </div>
    </form>
    <!--Add Vacation tab-->


    <!--Add Holiday tab-->
    <form class="form-horizontal holiday-form" id="clinic_form" action="add.php" method="POST" style="margin-top: 20px;">
      <div class="form-group">
        <label class="control-label col-sm-2" for="addDate"><?php echo xlt('Closure Date'); ?></label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="addDate" name="addDate" placeholder="Date">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
          <input type="radio" id="radio-choice-1" name="holiday-actions" value="Add Holiday" class="holiday-action">
          <label for="radio-choice-1"><?php echo xlt('Add Closure(s)'); ?></label>
        </div>
        <div class="col-sm-4">
          <input type="radio" id="radio-choice-2" name="holiday-actions" value="Delete Holiday" class="holiday-action">
          <label for="radio-choice-2"><?php echo xlt('Delete Closure(s)'); ?></label>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="clinic_id"><?php echo xlt('Clinic'); ?></label>
        <div class="col-sm-10">
          <select class="form-control" id="clinic_id" name="clinic_id">
            <option value="0" data-text="Undefined"><?php echo xlt('Choose Clinic/Facility'); ?></option>
            <option value="all" data-text="All"><?php echo xlt('All Facilitys'); ?></option>
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
                #$ufname[] = $uf['name'];
              }
              $qsql = sqlStatement("SELECT id, name FROM facility WHERE service_location != 0");
              while ($facrow = sqlFetchArray($qsql)) {
                if ($GLOBALS['restrict_user_facility']) {
                  //if restricting user to scheduling events at only their allowed facilities
                  //then list only schedule facilities of that user or default facility
                  //in Clinic drop down in Calendar's Add Vacation/Holiday panel
                  if (in_array($facrow['id'], $ufid) || $facrow['id'] == $e2f) {
                    $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
                    #echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
                    echo "<option value='" . attr($facrow['id']) . "' data-text='" . $facrow['name'] . "' $selected>" . text($facrow['name']) . "</option>";
                  }
                } else {
                  //if not restricting then list all facilities
                  //where service_location is not 0 including default facility
                  //in Clinic drop down in Calendar's Add Vacation/Holiday panel
                  $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
                  #echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
                  echo "<option value='" . attr($facrow['id']) . "' data-text='" . $facrow['name'] . "' $selected>" . text($facrow['name']) . "</option>";
                }
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-2">
          <input type="radio" id="radio-choice-3" name="holiday-types" value="Full day" class="holiday-type">
          <label for="radio-choice-3"><?php echo xlt('Full day'); ?></label>
        </div>
        <div class="col-sm-2">
          <input type="radio" id="radio-choice-4" name="holiday-types" value="First half" class="holiday-type">
          <label for="radio-choice-4"><?php echo xlt('First Half'); ?></label>
        </div>
        <div class="col-sm-2">
          <input type="radio" id="radio-choice-5" name="holiday-types" value="Second half" class="holiday-type">
          <label for="radio-choice-5"><?php echo xlt('Second Half'); ?></label>
        </div>
        <div class="col-sm-4">
          <input type="radio" id="radio-choice-6" name="holiday-types" value="time" class="holiday-type">
          <label for="radio-choice-6"><?php echo xlt('Time'); ?></label>
          <br>
          <tr>
          <td width='1%' nowrap id='tdallday2'>
          <?php echo xlt('Start Time'); ?>
          </td>
          <td width='1%' nowrap id='tdallday3'>

          <span>
          <input type='text' size='2' name='form_hour' value='<?php echo attr($starttimeh) ?>'
           title='<?php echo xla('Event start time'); ?>' /> :
          <input type='text' size='2' name='form_minute' value='<?php echo attr($starttimem) ?>'
           title='<?php echo xla('Event start time'); ?>' />&nbsp;
          </span>
          <select name='form_ampm' title='<?php echo xla("Note: 12:00 noon is PM, not AM"); ?>'>
          <option value='1'><?php echo xlt('AM'); ?></option>
          <option value='2'
          <?php
            if ($startampm == '2') {
              echo " selected";
            }
            ?>>
            <?php
              echo xlt('PM');
           ?>
          </option>
          </select>
          </td>
          </tr>
          <br>
          <tr>
          <td width='1%' nowrap id='tdallday2'>
          &nbsp;
           <?php echo xlt('End Time'); ?>
          </td>
          <td width='1%' nowrap id='tdallday3'>
          <span>
          <input type='text' size='2' name='form_hour_end' value='<?php echo attr($endtimeh) ?>'
           title='<?php echo xla('Event end time'); ?>' /> :
          <input type='text' size='2' name='form_minute_end' value='<?php echo attr($endtimem) ?>'
           title='<?php echo xla('Event end time'); ?>' />&nbsp;
          </span>
          <select name='form_ampm_end' title='<?php echo xla("Note: 12:00 noon is PM, not AM"); ?>'>
          <option value='1'><?php echo xlt('AM'); ?></option>
          <option value='2'
          <?php
            if ($endampm == '2') {
              echo " selected";
            }
            ?>>
            <?php
              echo xlt('PM');
            ?>
          </option>
          </select>
          </td>
          </tr>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-2">
          <button type="button" class="btn btn-default" id="add"><?php echo xlt('Add')?></button>
        </div>
        <div class="col-sm-2">
          <input type="hidden" name="form_action" value="holiday_submit">
          <!-- To distinguish between two types of holiday action while submitting: Add and Delete -->
          <input type="hidden" name="holiday_action" id="actionValue" value="">
          <button type="submit" class="btn btn-default" id="holiday_submit"><?php echo xlt('Submit')?></button>
          <!-- For passing datesAndTypes object to server via JSON -->
          <input type="hidden" name="jsonData" id="jsonValue" value="">
        </div>
      </div>
    </form>
    <br>
    <table class="table table-hover col-sm-offset-1" id="date-table">
      <tr style="background-color: #ddd;">
        <th><?php echo xlt('Selected date(s)')?></th>
        <th><?php echo xlt('Full day/Half day/Time')?></th>
        <th><?php echo xlt('Facility')?></th>
      </tr>
    </table>

    <script type="text/javascript">
      var datesAndTypes = {}; // declared in global scope when loading script for first time
      $(document).ready(function () {
        // Adding selected date, day type, facility of holiday to:
        // 1. an object - datesAndTypes       (adding facility id here)
        // 2. a table with id = "date-table"  (adding facility name here)
        $("#add").on("click", function () {
          // checking if date field is empty before adding to table
          var f = document.getElementById("clinic_form");
          if (!f.addDate.value) {
            alert('<?php echo addslashes( xl("Please select a date before adding.") ); ?>');
            return false;
          }
          // checking if any holiday action radio button - Add/Delete Holiday(s) - is selected before adding to table
          if($(".holiday-action").is(":checked")) {
            var holidayAction = $("input[name='holiday-actions']:checked").val(); // either Add Holiday or Delete Holiday
            if (holidayAction == "Add Holiday") {
              // checking if any holiday type radio button - Full day, First/Second half - is selected before adding to table
              if($(".holiday-type").is(":checked")) {
                var dayType = $("input[name='holiday-types']:checked").val(); // either Full day or First half or Second half
              } else {
                alert('<?php echo addslashes( xl("Please specify if closure is full day, half day or a time period.") ); ?>');
                return false;
              }
            } else {
              // holidayAction == "Delete Holiday", holiday type is not required
              var dayType = "Not required";
            }
          } else {
            alert('<?php echo addslashes( xl("Please specify if you want to add closure(s) or delete closure(s).") ); ?>');
            return false;
          }
          var formDate = $("input[name='addDate']").val(); // holiday date
          var facilityName = $("select[name='clinic_id'] option:selected").attr("data-text"); // holiday facility name
          var facilityId = $("select[name='clinic_id'] option:selected").attr("value"); // holiday facility id
          var formFacility = [facilityId, facilityName];
          if (formFacility[1] === "Undefined") {
            // if "Choose Clinic/Facility" option is selected
            alert('<?php echo addslashes( xl("Please select a Clinic/Facility for the closure.") ); ?>');
            return false;
          }
          var row = '<tr class="date-row">' +
                        '<td>' + formDate + '</td>' +
                        '<td>' + dayType + '</td>' +
                        '<td>' + formFacility[1] + '</td>' + // add facility name to table
                    '</tr>';
          $("#date-table").append(row).fadeIn(); // adding rows to a table with id = "date-table"
          datesAndTypes[formDate] = [dayType, formFacility[0]];  // adding data to object in format - "date" : ["day type", "facility id"]
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
          $(".holiday-action").prop("checked", false);  // uncheck all holiday action radio buttons
          $(".holiday-type").prop("checked", false);  // uncheck all holiday type radio button
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
      // if date input is empty
      if (!f.addDate.value) {
        alert('<?php echo addslashes( xl("A date is required for adding/deleting clinic Closures.") ); ?>');
        return false;
      }
      // if holiday specifications: action/facility/day type is not added to table (& datesAndTypes object) at least once
      if (jQuery.isEmptyObject(datesAndTypes)) {
        alert('<?php echo addslashes( xl("Please add at least one complete closure specification before submitting.") ); ?>');
        return false;
      }
      // pass holiday action string to an input element which POSTs it to server
      $("input[name='holiday_action']").val($("input[name='holiday-actions']:checked").val());
      var jsonString = JSON.stringify(datesAndTypes); // convert datesAndTypes object to a JSON string
      $("input[name='jsonData']").val(jsonString); // pass JSON string to an input element which POSTs it to server
      // validation done - submit form
      top.restoreSession();
      f.submit();
      return true;
    });
  });
  </script>
</body>
</html>