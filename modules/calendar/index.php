<?php
/*
 *  index.php main program for the Calendar
 *
 * Copyright (C) 2017 Ujjwal Arora <arora.ujjwal@gmail.com>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreHealth EHR
 * @author Ujjwal Arora <arora.ujjwal@gmail.com >
 * @author Terry Hill <teryhill@librehealth.io >
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreHealth EHR community.
 *
 */
require_once('../../interface/globals.php');
require_once('../../library/calendar.inc');
require_once('../../library/patient.inc');
require('includes/session.php');

?>
<html>
<head>
  <link href='full_calendar/fullcalendar.min.css' rel='stylesheet' />
  <link href='full_calendar/lib/cupertino/jquery-ui.min.css' rel='stylesheet' />
  <link href='full_calendar/fullcalendar.print.css' rel='stylesheet' media='print' />
  <link href='full_calendar_scheduler/scheduler.min.css' rel='stylesheet' />
  <link href="<?php echo $GLOBALS['css_path']; ?>jquery-datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
  <link href='css/index.css' rel='stylesheet' />

  <script src='full_calendar/lib/jquery.min.js'></script>
  <script src='full_calendar/lib/jquery-ui.min.js'></script>
  <script src='full_calendar/lib/moment.min.js'></script>
  <script src='full_calendar/fullcalendar.min.js'></script>
  <script src='full_calendar_scheduler/scheduler.min.js'></script>
  <script src='full_calendar/locale-all.js'></script>
  <script src="<?php echo $GLOBALS['standard_js_path']; ?>js.cookie/js.cookie.js"></script>
  <script src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
  <script src="../../library/dialog.js"></script>

  <style type="text/css">
    .show {
      display: block;
      background-color: #fff;
      border: 1px solid #ddd;
      z-index: 1000;
      position: absolute;
    }
    .hidden {
      display: none;
    }
    .show ul {
      padding: 0;
      margin: 0 0 0 25px;
      list-style: none;
    }
    .show ul li {
      font-size: 17px;
      padding: 10px 20px;
    }
    .show ul li:hover {
      font-size: 22px;
      background-color: #ddd;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div id="sidebar">
    <button id="datepicker"><?php echo xlt('Date Picker'); ?></button>

    <form name='theform' id='theform' method='post' onsubmit='return top.restoreSession()'>
    <?php
      // CHEMED
      $facilities = getUserFacilities($_SESSION['authId']); // from users_facility
      if ( $_SESSION['pc_facility'] ) {
         $provinfo = getProviderInfo('%', true, $_SESSION['pc_facility']);
      } else {
         $provinfo = getProviderInfo();
      }

       $default_lang_id = sqlQuery('SELECT lang_code FROM lang_languages WHERE lang_id = ?',array($_SESSION['language_choice']));

      // lemonsoftware
      if ($_SESSION['authorizeduser'] == 1) {
        $facilities = getFacilities();
      } else {
        $facilities = getUserFacilities($_SESSION['authId']); // from users_facility
        if (count($facilities) == 1)
          $_SESSION['pc_facility'] = key($facilities);
      }

      if (count($facilities) > 1) {
        echo "   <select name='pc_facility' id='pc_facility' >\n";
        if ( !$_SESSION['pc_facility'] ) $selected = "selected='selected'";
        echo "    <option value='0' $selected>"  .xl('All Facilities'). "</option>\n";

        foreach ($facilities as $fa) {
            $selected = ( $_SESSION['pc_facility'] == $fa['id']) ? "selected" : "" ;
            echo "    <option style=background-color:".htmlspecialchars($fa['color'],ENT_QUOTES)." value='" .htmlspecialchars($fa['id'],ENT_QUOTES). "' $selected>"  .htmlspecialchars($fa['name'],ENT_QUOTES). "</option>\n";
        }
        echo "   </select>\n";
      }

      // Select all providers if none are selected
      if(empty($_SESSION['pc_username'])) {
        $_SESSION['pc_username'] = array();
        array_push($_SESSION['pc_username'], '__PC_ALL__');
      }

      // PROVIDERS
      foreach($_SESSION['pc_username'] as $provider) {   //if __PC_ALL__ is one of selected, we set session as all the providers
        if($provider == "__PC_ALL__") {
          $_SESSION['pc_username'] = array();
          foreach($provinfo as $doc) {
            array_push($_SESSION['pc_username'], $doc['username']);
          }
        }
      }


      // remove those providers which aren't in provinfo from session
      $provinfo_users = array();
      foreach($provinfo as $doc) {
        array_push($provinfo_users, $doc['username']);
      }
      $_SESSION['pc_username'] = array_intersect($_SESSION['pc_username'], $provinfo_users);

      echo "   <select multiple size='15' name='pc_username[]' id='pc_username'>\n";
      echo "    <option value='__PC_ALL__' title='All Users'>"  .xl ("All Users"). "</option>\n";
      foreach ($provinfo as $doc) {
        $username = $doc['username'];
        echo "    <option value='$username'";
        foreach ($_SESSION['pc_username'] as $provider) {
          if ($provider == $username) {
            echo " selected";
          }
        }
        echo " title='" . $doc['lname'] . ", " . $doc['fname'] . "'>" . htmlspecialchars($doc['lname'],ENT_QUOTES) . ", " . htmlspecialchars($doc['fname'],ENT_QUOTES) . "</option>\n";
      }
      echo "   </select>\n";
    ?>
  </form>
    <?php
    if($_SESSION['pc_facility'] == 0){
      echo '<div id="facilityColor" style="overflow: auto; max-height: 166px; width: 96%; border: 1px solid black;">';
      echo '<table>';
      foreach ($facilities as $f){
        echo "<tr><td><p style='background-color:".$f['color'].";font-weight:bold; padding: 2px 1px; margin-top: -2px;'>".htmlspecialchars($f['name'],ENT_QUOTES)."</p></td></tr>";
      }
      echo '</table>';
      echo '</div>';
    }
    ?>
  </div>

  <div id='calendar-container'>
    <div id='calendar'></div>
  </div>

  <div class="hidden" id="context-menu">
    <ul></ul>
  </div>

  <script>
    $(document).ready(function() {
      var isClicked = false;
      var title_week = '<?php echo xlt('week'); ?>';
      var title_agenda2 = '<?php echo xlt('2 day'); ?>';
      var title_agenda = '<?php echo xlt('1 day'); ?>';
      var title_search = '<?php echo xlt('search'); ?>';
      var title_print = '<?php echo xlt('print'); ?>';
      var lang_default = '<?php echo $default_lang_id['lang_code']; ?>';

      function pasteEvent(eventClone, dropDetails) {
        // this function gets called when "Paste" is clicked in day's custom menu
        var eventData = eventClone;  // original (or copied) event's data
        var newDate = dropDetails[0].format("YYYY-MM-DD");  // clone event's date at drop point
        var newStartTime = dropDetails[0].format("HH:mm:ss");  // clone event's start time at drop point
        var duration = parseInt(eventData.pc_duration);  // clone event's duration (same as original)
        // clone event's end time at drop point
        var newEndTime = moment(newStartTime, "HH:mm:ss").add(duration, 'seconds').format("HH:mm:ss");
        var newProviderId = dropDetails[2].id;  // clone event's provider id at drop point

        var jsonData = { "date": newDate,
                         "startTime": newStartTime,
                         "endTime": newEndTime,
                         "providerId": newProviderId,
                         "multipleProvider": eventData.pc_multiple,
                         "patientId": eventData.pc_pid,
                         "categoryId": eventData.pc_catid,
                         "title": eventData.pc_title,
                         "comment": eventData.pc_hometext,
                         "endDate": eventData.pc_endDate,
                         "duration": eventData.pc_duration,
                         "recurrspec": eventData.pc_recurrspec,
                         "eventAllDay": eventData.pc_alldayevent,
                         "apptStatus": eventData.pc_apptstatus,
                         "prefCategory": eventData.pc_prefcatid,
                         "locationspec": eventData.pc_location,
                         "facility": eventData.pc_facility,
                         "billingFacility": eventData.pc_billing_location,
                         "room": eventData.pc_room,
                         "action": "paste" };
        // send new and cloned values to a php script to insert new event in DB, i.e., paste event to drop point
        $.ajax({
          type: "POST",
          url: "drag_copy_event.php",
          data: jsonData,
          success: function(response) {
            if (response === "query executed") {
              $('#calendar').fullCalendar('refetchEvents');  // refetch all events to reflect their specifications acc. to DB on Calendar
            }
          },
          dataType: "text",
          error: function(xhr, status, error) {
            var errorString = "Request failed. ";
            if (status) {
              errorString += status;
            }
            if (error) {
              errorString += ": ";
              errorString += error;
            }
            alert(errorString);
          }
        });
        copiedEvent = {}; // after paste, clear data in object
        isCopied = false; // and set this false
      }

      var copiedEvent = {}; // declared as an empty object when loading script for first time, holds copied event's details
      var isCopied = false; // used to ascertain whether an event is already copied and its details already in copiedEvent variable

      function showContextMenu(type, posX, posY, details) {
        // builds custom menu according to cell type - "event" or "day"
        var menuItems = "";
        if (type == "event") {
          menuItems = "<li id='copy'>Copy</li>";
        } else if (type == "day") {
          menuItems = "<li id='create'>Create event</li>" + "<li id='paste'>Paste</li>";
        }
        $("#context-menu>ul").empty().append(menuItems);
        $("#context-menu").removeClass("hidden").addClass("show");
        // positioning of custom menu at position of mouse
        $("#context-menu").css("top", posY);
        $("#context-menu").css("left", posX);

        $("#copy").click(function() {
          // when "Copy" in event's menu is clicked
          isCopied = true;
          copiedEvent = details; // store details of copied events
        });

        $("#create").click(function() {
          // when "Create event" in day's menu is clicked
          // open event panel according to parameters specified by details
          dlgopen('add_edit_event.php?' + '&starttimeh=' + details[0].get('hours') + '&userid=' + details[2].id +
          '&starttimem=' + details[0].get('minutes') + '&date=' + details[0].format('YYYYMMDD') // + '&catid=' + 0
           ,'_blank', 775, 375);
        });

        $("#paste").click(function() {
          // when "Paste" in day's menu is clicked
          if (isCopied) {
            // paste copied event at drop position specified by details
            pasteEvent(copiedEvent, details);
          } else {
            alert('<?php echo addslashes( xl("Please copy an event before pasting.") ); ?>');
            return false;
          }
        });
      }

      $('#calendar').fullCalendar({
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        locale: lang_default,
        height: 'parent',
        header: {
        left: 'prev,next,today print,search',
        center: 'title',
        right: 'providerAgenda,providerAgenda2Day,providerAgendaWeek,timelineMonth'
        },
        views: {
          providerAgendaWeek: {
            // options apply to basicWeek and agendaWeek views
            <?php if($GLOBALS['time_display_format'] == 0) { echo "slotLabelFormat: ['ddd, MMM D', 'H:mm'],"; } ?>
            type: 'agenda',
            duration: { days: 7 },
            buttonText: title_week,
            allDaySlot: false,
            displayEventTime: false,
            groupByResource: true,
            editable: true  // determines if the events can be dragged and resized
          },
 //         day: {
            // options apply to basicDay and agendaDay views
 //           <?php if($GLOBALS['time_display_format'] == 0) { echo "slotLabelFormat: 'H:mm',"; } ?>
 //           titleFormat: 'ddd, MMM D, YYYY',
 //           groupByDateAndResource: true
 //         },
          providerAgenda2Day: {
            type: 'agenda',
            duration: { days: 2 },
            buttonText: title_agenda2,
            allDaySlot: false,
            displayEventTime: false,
            groupByResource: true,
            editable: true  // determines if the events can be dragged and resized
          },
          providerAgenda: {
            type: 'agenda',
            duration: { days: 1 },
            buttonText: title_agenda,
            allDaySlot: false,
            displayEventTime: false,
            groupByDateAndResource: true,
            editable: true  // determines if the events can be dragged and resized
          }
        },
        resourceAreaWidth: "25%",
        navLinks: true,
        selectable: true,
        //selectHelper: true,
        defaultDate: Cookies.get('fullCalendarCurrentDate') || null,   // set last selected date and view when switching providers or facilities
        defaultView: Cookies.get('fullCalendarCurrentView') || '<?php echo $GLOBALS['calendar_view_type'] ?>',
        defaultTimedEventDuration: '00:10:00',
        minTime: '<?php echo $GLOBALS['schedule_start'] ?>:00:00',
        maxTime: '<?php echo $GLOBALS['schedule_end'] + 1 ?>:00:00',  // adding 1 to make ending hour as inclusive
        slotDuration: '00:<?php echo $GLOBALS['calendar_interval'] ?>:00',
        <?php if($GLOBALS['time_display_format'] == 0) { echo "timeFormat: 'H:mm',"; } ?>   // uppercase H for 24-hour clock
        resources: {
          url: 'includes/get_providers.php?skip_timeout_reset=1',
          type: 'POST',
          error: function() {
              alert('There was an error while fetching providers.');
          }
        },
        events: {
          url: 'includes/get_provider_events.php?skip_timeout_reset=1',
          type: 'POST',
          error: function() {
              alert('There was an error while fetching appointments.');
          }
        },
        customButtons: {
          print: {
              text: title_print,
              click: function() { // Printing currently works for a single provider.
                if($('#calendar').fullCalendar('getResources').length > 1) {
                  alert("Please select only a single provider.");
                }
                else {
                  var oldView = $('#calendar').fullCalendar('getView');
                  $('#calendar').fullCalendar('changeView', 'listDay');
                  window.print();
                  $('#calendar').fullCalendar('changeView', oldView.name);
                }
              }
            },
            search: {
              text: title_search,
              click: function() {
                window.location.href = 'search.php';
              }
            }
        },
        eventMouseover: function(calEvent, element, view) {
          if (calEvent.picture_url) {
            var picture = '<td><img src="../../profile_pictures/'+ calEvent.picture_url +'" height="64px" width="64px"></td>';
          }
          else {
            picture = " ";
          }
          var tooltip = '<div class="tooltipevent"><table><tr>' + picture+'<td style="color:red;">' + calEvent.description + '</td></tr></table></div>';
          var $tooltip = $(tooltip).appendTo('body');

         $(this).mouseover(function(e) {
             $(this).css('z-index', 10000);
             $tooltip.fadeIn('500');
             $tooltip.fadeTo('10', 1.9);
         }).mousemove(function(e) {
             $tooltip.css('top', e.pageY + 10);
             $tooltip.css('left', e.pageX + 20);
         });
        },
        eventMouseout: function(calEvent, element, view) {
          $(this).css('z-index', 8);
          $('.tooltipevent').remove();
        },
        select: function(start, end, jsEvent, view, resource) {
          // this function is triggered when a date/time selection is made
          // show custom menu for day cells
          var dayDetails = [start, end, resource];
          showContextMenu("day", jsEvent.clientX, jsEvent.clientY, dayDetails);
        },
        eventRender: function(event, element, view) {
          // this function is triggered while an event is being rendered
          // handle right click on an event
          var eventDetails = event;
          element.on("contextmenu", function(e) {
            e.preventDefault(); // don't show default options
            showContextMenu("event", e.clientX, e.clientY, eventDetails); // show custom menu for event cells
          });
          //converting event title text to hyperlink
          if (event['pc_pid'] > 0) {
            //only when event is a patient event
            //event['pc_pid'] is number string for patient events, empty "" for provider events
            let link = '<?php echo $GLOBALS['webroot'] . "/interface/patient_file/summary/demographics.php?set_pid="; ?>' + event['pc_pid'];
            var titleLink = "<a href='#'>" + event['title'] + "</a>";
            let titleInfo = "<span style='color: #000;'>" + event['e_info'] + "</span>";
            //find all event title div elements
            var patientEventTitle = element.find('.fc-title');
            //remove title text inside them and insert hyperlink
            patientEventTitle.empty().append(titleLink);
            //add event description text just after hyperlink
            patientEventTitle.after(titleInfo);
            patientEventTitle.on("click", function(e) {
              //to stop eventClick handler from executing
              //upon clicking text link
              e.stopImmediatePropagation();
              //open demographics in a new tab
              top.restoreSession();
              top.RTop.location = link;
              return false;
            });
          }
        },
        eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
          // this function gets called when dragging stops and the event has moved to
          // a different date, time slot and/or provider
          if (confirm("<?php echo addslashes(xl('Are you sure about this change in event location?')); ?>")) {
            //if selected "OK" on alert
            var eventId = event["pc_eid"];  // event's unique id
            var newDate = event.start.format("YYYY-MM-DD");  // event's new date
            var newStartTime = event.start.format("HH:mm:ss");  // event's new start time
            var newEndTime = event.end.format("HH:mm:ss");  // event's new end time
            var newProviderId = event.resourceId;  // event's new provider id
            var eventPatientId = event.pid;  // event's patient id
            var eventStatus = event.pc_apptstatus; // event's (appt.) status
            var jsonData = { "id": eventId,
                             "date": newDate,
                             "startTime": newStartTime,
                             "endTime": newEndTime,
                             "providerId": newProviderId,
                             "patientId": eventPatientId,
                             "apptStatus": eventStatus,
                             "action": "drag" };
            // send edited values to a php script to update DB
            $.ajax({
              type: "POST",
              url: "drag_copy_event.php",
              data: jsonData,
              success: function(response) {
                if (response === "query executed") {
                  $('#calendar').fullCalendar('refetchEvents');  // refetch all events to reflect their specifications acc. to DB on Calendar
                }
              },
              dataType: "text",
              error: function(xhr, status, error) {
                var errorString = "Request failed. ";
                if (status) {
                  errorString += status;
                }
                if (error) {
                  errorString += ": ";
                  errorString += error;
                }
                alert(errorString);
              }
            });
          } else {
            //if selected "Cancel" on alert
            revertFunc();  // reverts the event’s start/end date to the values before the drag
          }
        },
        eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) {
          // this function gets called when event resizing stops
          // and the event has changed in duration
          if (confirm("<?php echo addslashes(xl('Are you sure about this change in event duration?')); ?>")) {
            //if selected "OK" on alert
            var eventId = event["pc_eid"];  // event's unique id
            var startTime = event.start.format("HH:mm:ss");  // event's start time string
            var newEndTime = event.end.format("HH:mm:ss");  // event's new end time stirng
            var endTimeMoment = moment(newEndTime, "HH:mm:ss");  // moment object
            var startTimeMoment = moment(startTime, "HH:mm:ss");  // moment object
            var duration = moment.duration(endTimeMoment.diff(startTimeMoment)).asSeconds();  // event's new duration length

            var jsonData = { "id": eventId,
                             "endTime": newEndTime,
                             "duration": duration,
                             "action": "resize" };
            // send edited values to a php script to update DB
            $.ajax({
              type: "POST",
              url: "drag_copy_event.php",
              data: jsonData,
              success: function(response) {
                if (response === "query executed") {
                  $('#calendar').fullCalendar('refetchEvents');  // refetch all events to reflect their specifications acc. to DB on Calendar
                }
              },
              dataType: "text",
              error: function(xhr, status, error) {
                var errorString = "Request failed. ";
                if (status) {
                  errorString += status;
                }
                if (error) {
                  errorString += ": ";
                  errorString += error;
                }
                alert(errorString);
              }
            });
          } else {
            //if selected "Cancel" on alert
            revertFunc();  // reverts the event’s start/end date to the values before resizing
          }
        },
        eventClick: function(calEvent, jsEvent, view) {
          var pccattype = (calEvent['pc_pid'] && calEvent['pc_pid'] > 0) ? 0 :  1;
          console.log(pccattype);
          dlgopen('add_edit_event.php?date='+ calEvent.start.format('YYYYMMDD') +'&eid=' + calEvent.id +'&prov=' + pccattype, '_blank', 775, 375);
        },
        viewRender: function(view) {
            // Remember last selected date and view
            var inFifteenMinutes = new Date(new Date().getTime() + 15 * 60 * 1000);
            Cookies.set('fullCalendarCurrentDate', view.intervalStart.format(), {expires: inFifteenMinutes, path: ''});
            Cookies.set('fullCalendarCurrentView', view.name, {expires: inFifteenMinutes, path: ''});

            // update datepicker
            $('#datepicker').datetimepicker({ value: view.intervalStart.format() });
        }
      })

      // refetch events every few seconds.
      <?php if($GLOBALS['calendar_refresh_freq'] != 'none') { ?>
         setInterval(function() { $('#calendar').fullCalendar( 'refetchEvents' ) }, <?php if($GLOBALS['calendar_refresh_freq']) echo $GLOBALS['calendar_refresh_freq']; else echo '720000'; ?>);
      <?php } ?>

      // datepicker for calendar
      $('#datepicker').datetimepicker({
        timepicker: false,
        // inline: true,
        //weeks:true,
        todayButton: true,
        onChangeDateTime: function(d) {
          $('#calendar').fullCalendar('gotoDate', d);
        },
        onChangeMonth: function(d) {
          $('#calendar').fullCalendar('gotoDate', d);
        }
      });
      $.datetimepicker.setLocale('<?php echo $default_lang_id['lang_code'];?>');

    });

    $("#pc_username").change(function() { $('#theform').submit(); });
    $("#pc_facility").change(function() { $('#theform').submit(); });
    var docRoot = top.$('#mainBox');
    docRoot.click(function() {
      $("#context-menu").removeClass("show").addClass("hidden");
    });
    $(document).click(function() {
      $("#context-menu").removeClass("show").addClass("hidden");
    });
  </script>
</body>
</html>
