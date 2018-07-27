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
  <link href='full_calendar/fullcalendar.print.css' rel='stylesheet' media='print' />
  <link href='full_calendar_scheduler/scheduler.min.css' rel='stylesheet' />
  <link href="<?php echo $GLOBALS['css_path']; ?>jquery-datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
  <link href='css/index.css' rel='stylesheet' />
  <link type="text/css" rel="stylesheet" href="<?php echo $GLOBALS['css_path']; ?>qTip/jquery.qtip.min.css" />

  <script src='full_calendar/lib/jquery.min.js'></script>
  <script src='full_calendar/lib/moment.min.js'></script>
  <script src='full_calendar/fullcalendar.min.js'></script>
  <script src='full_calendar_scheduler/scheduler.min.js'></script>
  <script src='full_calendar/locale-all.js'></script>
  <script src="<?php echo $GLOBALS['standard_js_path']; ?>js.cookie/js.cookie.js"></script>
  <script src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-datetimepicker/jquery.datetimepicker.full.min.js"></script>
  <script src="<?php echo $GLOBALS['standard_js_path']; ?>qTip/jquery.qtip.min.js"></script>
  <script src="../../library/dialog.js"></script>

  <style type="text/css">
    .tooltip-element {
      display: none;
      font-size: 16px;
      max-width: 350px;
    }
    .tooltip-element img {
      height: 64px;
      width: 64px;
      float: left;
      margin: 5px 15px 0 1px;
      border-radius: 5px;
    }
    .event-details {
      margin: 0;
      display: flex;
      flex-direction: column;
    }
    .event-details h1 {
      font-size: 24px;
    }
    .event-details p {
      font-size: 18px;
      margin: 0 0 8px 12px;
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

  <script>
    $(document).ready(function() {
      var title_week = '<?php echo xlt('week'); ?>';
      var title_agenda2 = '<?php echo xlt('2 day'); ?>';
      var title_agenda = '<?php echo xlt('1 day'); ?>';
      var title_search = '<?php echo xlt('search'); ?>';
      var title_print = '<?php echo xlt('print'); ?>';
      var lang_default = '<?php echo $default_lang_id['lang_code']; ?>';

      function scrollCalTime(calView) {
        var date = new Date();  // current date and time related info.
        var currHour = date.getHours();  // (0-23) according to local time
        var currMinutes = date.getMinutes();  // (0-59) according to local time
        if (currHour < 10) {
          currHour = "0" + currHour;  // to avoid times like "9:20:00"
        }
        if (currMinutes < 10) {
          currMinutes = "0" + currMinutes;  // to avoid times like "13:9:00"
        }
        var currCalTime = currHour + ":" + currMinutes + ":00";  // format "hh:mm:00"
        // scrollTime determines how much forward scroll pane is initially scrolled
        calView.options.scrollTime = currCalTime;  // set scrollTime to current time
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
            nowIndicator: true,
            groupByResource: true
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
            nowIndicator: true,
            groupByResource: true
          },
          providerAgenda: {
            type: 'agenda',
            duration: { days: 1 },
            buttonText: title_agenda,
            allDaySlot: false,
            displayEventTime: false,
            nowIndicator: true,
            groupByDateAndResource: true
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
        select: function(start, end, jsEvent, view, resource) {
          dlgopen('add_edit_event.php?' + '&starttimeh=' + start.get('hours') + '&userid=' + resource.id +
          '&starttimem=' + start.get('minutes') + '&date=' + start.format('YYYYMMDD') // + '&catid=' + 0
           ,'_blank', 775, 375);
              },
        eventRender: function(event, element, view) {
          var tooltipElement = '';
          if (event['pc_pid'] > 0) {
            // when event is a patient event
            // converting event title text to hyperlink
            // event['pc_pid'] is number string for patient events, empty "" for provider events
            let link = '<?php echo $GLOBALS['webroot'] . "/interface/patient_file/summary/demographics.php?set_pid="; ?>' + event['pc_pid'];
            var titleLink = "<a href='#'>" + event['title'] + "</a>";
            // find all event title div elements
            var patientEventTitle = element.find('.fc-title');
            // remove title text inside them and insert hyperlink
            patientEventTitle.empty().append(titleLink);
            if (event['e_info']) {
              // value defined/undefined as per calendar_appt_style global (see: get_provider_events.php)
              // add event category title, event comment, just after hyperlink
              var titleInfo = "<span style='color: #000;'>" + event['e_info'] + "</span>";
              patientEventTitle.after(titleInfo);
            }
            patientEventTitle.on("click", function(e) {
              // to stop eventClick handler from executing
              // upon clicking text link
              e.stopImmediatePropagation();
              // open demographics in a new tab
              top.restoreSession();
              top.RTop.location = link;
              return false;
            });
            // appointment (patient event) tooltip's content
            var imgElement = '';
            if (event.picture_url) {
              // when image exists
              imgElement = '<img src="../../profile_pictures/' + event.picture_url + '">';
            }
            var headingElement = '';
            var categoryTitle = '';
            var eventDescription = '';
            // setting these values as per appt_tooltip_style global (see: get_provider_events.php)
            if (event.tooltip.lname) {
              headingElement = '<h1>' + event.lname + '</h1>';
              if (event.tooltip.fname) {
                headingElement = '<h1>' + event.lname + ', ' + event.fname + '</h1>';
                if (event.tooltip.category) {
                  categoryTitle = '<p>' + event.pc_catname + '</p>';
                  if (event.tooltip.comment) {
                    eventDescription = '<p>"' + event.pc_hometext + '"</p>';
                  }
                }
              }
            }
            tooltipElement  = '<div class="tooltip-element">' +
                                imgElement +
                                '<div class="event-details">' +
                                  headingElement +
                                  categoryTitle +
                                  '<p>Status: ' + event.statusTitle + '</p>' +
                                  eventDescription +
                                '</div>' +
                              '</div>';
            element.append(tooltipElement);
          } else {
            // provider event tooltip's content
            var headingElement = '<h1>' + event.ulname + ', ' + event.ufname + '</h1>';
            var categoryTitle = '';
            var eventDescription = '';
            // setting these values as per logic in get_provider_events.php
            if (event.tooltip.category) {
              categoryTitle = '<p>' + event.pc_catname + '</p>';
              if (event.tooltip.comment) {
                eventDescription = '<p>"' + event.pc_hometext + '"</p>';
              }
            }
            tooltipElement  = '<div class="tooltip-element">' +
                                '<div class="event-details">' +
                                  headingElement +
                                  categoryTitle +
                                  eventDescription +
                                '</div>' +
                              '</div>';
            element.append(tooltipElement);
          }
        },
        eventClick: function(calEvent, jsEvent, view) {
          var pccattype = (calEvent['pc_pid'] && calEvent['pc_pid'] > 0) ? 0 :  1;
          dlgopen('add_edit_event.php?date='+ calEvent.start.format('YYYYMMDD') +'&eid=' + calEvent.id +'&prov=' + pccattype, '_blank', 775, 375);
        },
        viewRender: function(view) {
            // Remember last selected date and view
            var inFifteenMinutes = new Date(new Date().getTime() + 15 * 60 * 1000);
            Cookies.set('fullCalendarCurrentDate', view.intervalStart.format(), {expires: inFifteenMinutes, path: ''});
            Cookies.set('fullCalendarCurrentView', view.name, {expires: inFifteenMinutes, path: ''});

            // update datepicker
            $('#datepicker').datetimepicker({ value: view.intervalStart.format() });

            scrollCalTime(view);  // when view changes or any date navigation method (prev, next, today) is called
        },
        loading: function(isLoading, view) {
            // triggered when event or resource fetching starts/stops.
            if(isLoading) {
                // fetching starts
                scrollCalTime(view);  // when Calendar is loaded or refreshed
            }
        },
        eventAfterAllRender: function(view) {
          $('.fc-event').each(function() { // loop over all events
            $(this).qtip({
              content: {
                text: $(this).find('div.tooltip-element'), // use tooltipElement as tooltip's content of this event
              },
              style: {
                classes: 'tooltip-element' // custom styling for tooltip's content
              },
              position: {
                target: 'mouse', // use mouse position as origin for tooltip's initial position
                adjust: { // adjusting tooltip's position
                  mouse: true, // follow the mouse when hovering over target
                  x: 20, // offset in the horizontal plane, +ve value moves tooltip to the right
                  y: 10  // offset in the vertical plane, +ve value moves tooltip downwards
                }
              }
            });
          });
        }
      });

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


  </script>
</body>
</html>
