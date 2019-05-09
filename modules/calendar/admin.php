<?php
require_once("../../interface/globals.php");
require_once("$srcdir/headers.inc.php");
require_once("includes/admin_helper.php");
require_once("$srcdir/formatting.inc.php");

$dateFormat = DateFormatRead();  // date format for datepicker
// get bootstrap
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker"));

// set up working variables related to repeated events
$my_recurrtype = 0;
$my_repeat_freq = 0 + $_POST['form_repeat_freq'];
$my_repeat_type = 0 + $_POST['form_repeat_type'];
$my_repeat_on_num  = 1;
$my_repeat_on_day  = 0;
$my_repeat_on_freq = 0;
$my_enddate = $_POST['form_enddate'];
// if recurring appointment is to be set using new repeat mechanism, i.e., using 'days every week' mechanism
if (!empty($_POST['days_every_week'])) {
  $my_recurrtype = 3;
  // loop through "days" checkboxes and insert selected days into array
  $days_every_week_arr = array();
  for ($i=1; $i<=7; $i++) {
    if (!empty($_POST['day_' . $i])) {
      array_push($days_every_week_arr, $i);
    }
  }
  $my_repeat_freq = implode(",", $days_every_week_arr); // store that array as a string
  $my_repeat_type = 6;
} elseif (!empty($_POST['form_repeat'])) {
  // if recurring appointment is to be set using old repeat mechanism, i.e., using 'every day', 'every 2nd day', etc.
  $my_recurrtype = 1;
}
// capture the recurring specifications
$recurrspec = array("event_repeat_freq" => "$my_repeat_freq",
                    "event_repeat_freq_type" => "$my_repeat_type",
                    "event_repeat_on_num" => "$my_repeat_on_num",
                    "event_repeat_on_day" => "$my_repeat_on_day",
                    "event_repeat_on_freq" => "$my_repeat_on_freq",
                    "exdate" => $_POST['form_repeat_exdate']
                  );
// $noRecurrspec is used in case a category has no recurring specifications
$noRecurrspec = array("event_repeat_freq" => "",
                      "event_repeat_freq_type" => "",
                      "event_repeat_on_num" => "1",
                      "event_repeat_on_day" => "0",
                      "event_repeat_on_freq" => "0",
                      "exdate" => ""
                    );
// when both "Repeats" and "Days of Week" boxes are unchecked
if ($my_recurrtype === 0) {
  $recurrspec = $noRecurrspec;
  $my_enddate =  "0000-00-00";
}
// serialize like in DB
$recurrspec = serialize($recurrspec);

// if update category button is used
if($_POST['updateCat'] == 1) {
  // call for creation/updation of category
  createUpdateCategory($_SESSION['category'], $_POST['catName'], $_POST['catCol'],
  $_POST['catDes'], $_POST['catType'], $_POST['catDur'], $my_recurrtype, $recurrspec, $my_enddate,
  $_POST['catAllDay'], $_SESSION['category'] == "__NEW__");
}

// if delete category button is used
if($_POST['deleteCat'] == 1) {
  deleteCategory($_SESSION['category']);
  $_SESSION['category'] = "__NEW__";
}

// retrieve available categories from admin_helper
$categories = getCategories();

// assign POSTed category to session
if(isset($_POST['category'])) {
  $_SESSION['category'] = $_POST['category'];
}

// get category info if we have a category selected
$selectedCat = array();
if(isset($_SESSION['category']) && $_SESSION['category']!=NULL) {
  foreach($categories as $category) {
    if($category['pc_catid'] == $_SESSION['category']) {
      $selectedCat = $category;
      break;
    }
  }
}

?>

<html>
<head>
  <title><?php echo xlt('Calendar Administration');?></title>
  <link href="css/admin.css" rel="stylesheet" />

    <link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
  <script src="<?php echo $GLOBALS['standard_js_path']; ?>jquery-min-3-1-1/index.js"></script>

</head>
<body class="body_top">
  <div class="container-fluid">
    <div class="row block cat-body">

      <form class="form-horizontal" id="cat-select-form" action="" method="POST">
        <div class="form-group col-xs-12">
          <label for="category"><?php echo xlt('Select Category');?></label>
          <select class="form-control" id="category" name="category" required>
           <option value="__NEW__" select="selected"><?php echo xlt('New Category');?></option>
            <?php
            if (empty($_SESSION['category'])) {
                error_log("nul?: ".$_SESSION['category'], 0);
                $_SESSION['category'] = "__NEW__";
            }
            foreach($categories as $category) {
              $catid = $category['pc_catid'];
              $catname = $category['pc_catname'];
              echo "<option value='$catid'";
                if($catid == $_SESSION['category']) {
                  echo " selected";
                }
              echo ">$catname</option>";
            }
            ?>
          </select>
        </div>
      </form>

    </div>

    <div class="row block">
      <div class="cat-head">
        <?php
          // if our selected category info array isn't empty
          if(!empty($selectedCat)) {
            echo "<h4>". $selectedCat['pc_catname'] . "</h4>";
          } else {
            echo "<h4>" . xlt('New Category') . "</h4>";
          }
        ?>
      </div>
      <form class="form-horizontal cat-body" id="cat-field-form" action="" method="POST">

        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catName"><?php echo xlt('Name');?></label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="catName" name="catName" placeholder="<?php echo xlt('Category Name');?>" required
              <?php if(!empty($selectedCat)) echo " value=\"" . $selectedCat['pc_catname'] . "\"";  ?> >
            </div>
          </div>

          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catCol"><?php echo xlt('Color');?></label>
            <div class="col-md-10">
              <input type="color" class="form-control" id="catCol" name="catCol" placeholder="<?php echo xlt('Select a Color');?>" required
              <?php if(!empty($selectedCat)) echo " value=\"" . $selectedCat['pc_catcolor'] . "\"";
              else echo "value=\"#e5e5e5\"";  ?> >
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catDur"><?php echo xlt('Duration (Minutes)');?></label>
            <div class="col-md-10">
              <input type="number" class="form-control" id="catDur" name="catDur" placeholder="<?php echo xlt('Minutes');?>" required
              <?php if(!empty($selectedCat)) echo " value=\"" . $selectedCat['pc_duration']/60 . "\"" ?>
              <?php if(!empty($selectedCat) && $selectedCat['pc_end_all_day'] == 1) echo " disabled"; ?> >
            </div>
          </div>

          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catAllDay"><?php echo xlt('All Day');?></label>
            <div class="col-md-10">
              <div class="radio-inline">
                <label><input type="radio" id="allDay1" name="catAllDay" value="1" required
                <?php if(!empty($selectedCat) && $selectedCat['pc_end_all_day'] == 1) echo " checked"; ?>><?php echo xlt('Yes');?></label>
              </div>
              <div class="radio-inline">
                <label><input type="radio" id="allDay0" name="catAllDay" value="0" required
                <?php if(!empty($selectedCat) && $selectedCat['pc_end_all_day'] == 0) echo " checked"; ?>><?php echo xlt('No');?></label>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catDes"><?php echo xlt('Description');?></label>
            <div class="col-md-10">
              <textarea rows="2" class="form-control" id="catDes" name="catDes" placeholder="<?php echo xlt('Category Description');?>"
              required><?php if(!empty($selectedCat)) echo $selectedCat['pc_catdesc']; ?></textarea>
            </div>
          </div>

          <div class="form-group col-xs-6">
            <label class="control-label col-md-2" for="catType"><?php echo xlt('Type');?></label>
            <div class="col-md-10">
              <select class="form-control" id="catType" name="catType" required>
                <option value="0"><?php echo xlt('Patient');?></option>
                <option value="1" <?php if(!empty($selectedCat) && $selectedCat['pc_cattype'] == 1) echo "selected" ?>><?php echo xlt('Provider');?></option>
                <option value="2" <?php if(!empty($selectedCat) && $selectedCat['pc_cattype'] == 2) echo "selected" ?>><?php echo xlt('Clinic');?></option>
              </select>
            </div>
          </div>
        </div>
        <style>
          .box{
            border: ;
          }
        </style>
        <div class="row">
          <div class="form-group col-xs-6">
            <label class="control-label col-md-2 box" for="catDes"><?php echo xlt('Repeat Parameters');?></label>
            <div class="col-md-10 box">
              <?php
                /*
                Functions 'isDaysEveryWeek' and 'isRegularRepeat' are used to check which repeating mechanism (new or old) is being used, and load settings in the event panel form accordingly.
                */

                // check if recurring appointment is to be set using new repeat mechanism, i.e., using 'days every week' mechanism
                function isDaysEveryWeek($repeat) {
                  if($repeat == 3){
                    return true;
                  } else {
                    return false;
                  }
                }

                // check if recurring appointment is to be set using old repeat mechanism, i.e., using 'every day', 'every 2nd day', etc.
                function isRegularRepeat($repeat) {
                  if($repeat == 1 || $repeat == 2){
                    return true;
                  } else {
                    return false;
                  }
                }

                // if no existing category is selected, then:
                if (empty($selectedCat)) {
                  $repeats = 0; // don't check "Repeats" and "Days of Week" boxes
                  $repeattype = '0'; // stores "day/workday/week" option in regular repeat
                  $repeatfreq = '0'; // stores "Every" option in regular repeat or "Days" checkboxes in 'days every week' repeat
                  $enddate = ""; // stores end date of "until" input
                } else {
                  // otherwise, load the values from DB corresponding to selected category
                  $repeats = $selectedCat['pc_recurrtype'];
                  // parse out the repeating data, if any:
                  $rspecs = unserialize($selectedCat['pc_recurrspec']); // extract recurring data
                  $repeattype = $rspecs['event_repeat_freq_type'];
                  $repeatfreq = $rspecs['event_repeat_freq'];
                  $repeatexdate = $rspecs['exdate']; // repeating date exceptions
                  // adjustments for repeat type 2, a particular weekday of the month
                  if ($repeats == 2) {
                    $repeatfreq = $rspecs['event_repeat_on_freq'];
                    if ($rspecs['event_repeat_on_num'] < 5) {
                      $repeattype = 5;
                    } else {
                      $repeattype = 6;
                    }
                  }
                  $enddate = $selectedCat['pc_enddate'];
                }
              ?>
              <table class="box">
                <!-- first row of repeat parameters -->
                <tr class="box">
                  <td>
                    <input type='checkbox' name='form_repeat' id='form_repeat' value='1'<?php if (isRegularRepeat($repeats)) echo " checked" ?>/>
                    <input type='hidden' name='form_repeat_exdate' id='form_repeat_exdate' value='<?php echo attr($repeatexdate); ?>' /> <!-- dates excluded from the repeat -->
                  </td>
                  <td id='repeats_label'>&nbsp;<?php echo xlt('Repeats'); ?></td>
                  <td>
                    <select name='form_repeat_freq' id="form_repeat_freq" title='<?php echo xla('Every, every other, every 3rd, etc.'); ?>'>
                    <?php
                      $freq_options = array(1 => xl('every'), 2 => xl('2nd'), 3 => xl('3rd'), 4 => xl('4th'), 5 => xl('5th'), 6 => xl('6th'));
                      foreach ($freq_options as $key => $value) {
                        echo "<option value='" . attr($key) . "'";
                        if ($key == $repeatfreq && isRegularRepeat($repeats)) {
                          echo " selected";
                        }
                        echo ">" . text($value) . "</option>\n";
                      }
                    ?>
                    </select>
                    <select name='form_repeat_type' id="form_repeat_type">
                    <?php
                      $type_options = array(0 => xl('day') , 4 => xl('workday'), 1 => xl('week'), 2 => xl('month'), 3 => xl('year'));
                      foreach ($type_options as $key => $value) {
                        echo "<option value='" . attr($key) . "'";
                        if ($key == $repeattype && isRegularRepeat($repeats)) {
                          echo " selected";
                        }
                        echo ">" . text($value) . "</option>\n";
                      }
                    ?>
                    </select>
                  </td>
                </tr>
                <!-- first row of repeat parameters-->
                <style>
                  #days_every_week_row input[type="checkbox"]{
                    float:right;
                  }
                  #days_every_week_row div{
                    display: inline-block;
                    text-align: center;
                    width: 12%;
                  }
                  #days_every_week_row div input{
                    width: 100%;
                  }
                </style>
                <!-- second row of repeat parameters -->
                <tr id="days_every_week_row" class="box">
                  <td>
                    <input type='checkbox' id='days_every_week' name='days_every_week' <?php echo isDaysEveryWeek($repeats) ? " checked" : null ?> />
                  </td>
                  <td id="days_label">&nbsp;<?php echo xlt('Days Of Week') . ":"; ?>&nbsp;</td>
                  <td id="days">
                  <?php
                    $weekdays = array(1 => xl('Su{{Sunday}}') , 2 => xl('M{{Monday}}'), 3 => xl('Tu{{Tuesday}}'), 4 => xl('W{{Wednesday}}'),
                     5 => xl('Th{{Thursday}}'), 6 => xl('F{{Friday}}'), 7 => xl('Sa{{Saturday}}'));
                    foreach ($weekdays as $key => $value) {
                      echo " <div><input type='checkbox' name='day_". attr($key) ."'";
                      // checks appropriate boxes according to days in string $repeatfreq
                      if (in_array($key, explode(',', $repeatfreq)) && isDaysEveryWeek($repeats)) {
                        echo " checked";
                      }
                      echo " /><label>" . text($value) . "</label></div>\n";
                    }
                  ?>
                  </td>
                </tr>
                <!-- second row of repeat parameters -->

                <!-- third row of repeat parameters -->
                <tr class="box">
                  <td>&nbsp;</td>
                  <td id='endDate_label'>&nbsp;<?php echo xlt('until'); ?></td>
                  <td>
                    <input type='text' size='10' name='form_enddate' id='form_enddate' value='<?php echo oeFormatShortDate(attr($enddate)) ?>'/>
                    <?php
                      if ($repeatexdate != "") {
                        $tmptitle = "The following dates are excluded from the repeating series";
                        if ($multiple_value) {
                          $tmptitle .= " for one or more providers:\n";
                        } else {
                          $tmptitle .= "\n";
                        }
                        $exdates = explode(",", $repeatexdate);
                        foreach ($exdates as $exdate) {
                          $tmptitle .= date("d M Y", strtotime($exdate))."\n";
                        }
                        echo "<a href='#' title='" . attr($tmptitle) . "' alt='" . attr($tmptitle) . "'><img src='../../interface/pic/warning.gif' title='" . attr($tmptitle) . "' alt='*!*' style='border:none;'/></a>";
                      }
                    ?>
                  </td>
                </tr>
                <!-- third row of repeat parameters -->
              </table>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-xs-9">
            <div class=col-md-12>
              <button type="submit" class="btn btn-primary cp-positive" name="updateCat" value="1"><?php echo xlt('Update');?></button>
            </div>
          </div>

          <!-- show delete button only if you select a category -->
          <?php if(!empty($selectedCat)) { ?>
          <div class="form-group col-xs-3">
            <div class=col-md-12>
              <button type="submit" class="btn btn-danger cp-negative" name="deleteCat" value="1"
              onclick="return confirm('<?php echo xlt('Are you sure you want to do that?');?>');"><?php echo xlt('Delete');?></button>
            </div>
          </div>
          <!-- end if -->
          <?php } ?>

        </div>

      </form>
    </div>
  </div>

  <script type="text/javascript">
  $(document).ready(function () {
    // selector for category submits the form
    $("#category").change(function() { $('#cat-select-form').submit(); });

    // change card color according to selected color
    $('.cat-head').css("background", $("#catCol").val());
    $("#catCol").change(function() { $('.cat-head').css("background", $("#catCol").val());});

    // disable duration if all day is selected
    $('#allDay1').click(function () {
        if ($(this).is(':checked')) {
          $("#catDur").prop("disabled", true);
        }
    });

    // enale duration if not all day
    $('#allDay0').click(function () {
        if ($(this).is(':checked')) {
          $("#catDur").prop("disabled", false);
        }
    });

    // end date picker for "until" input
    $('#form_enddate').datetimepicker({
      timepicker: false,
      format: '<?php echo $dateFormat; ?>',
      formatDate: '<?php echo $dateFormat; ?>',
    });
  });

  // upon loading of Calendar Admin or after each change of category:
  <?php if (empty($selectedCat)) { ?>
    // if no category (New Category) is selected:
    // $repeats = 0 and disable all repeat parameters fields
    set_repeat();
    set_days_every_week();
  <?php } else {
            // when a category is selected
            if (isRegularRepeat($repeats)) { ?>
              // $repeats = 1 or 2
              // disable only "Days of week" parameters fields
              set_repeat();
      <?php } elseif (isDaysEveryWeek($repeats)) { ?>
                // $repeats = 3
                // disable only "Repeats" parameters fields
                set_days_every_week();
      <?php } else { ?>
                // $repeats = 0
                // disable all repeat parameters fields
                set_repeat();
                set_days_every_week();
      <?php }
        } ?>
    // modify some visual attributes when:
    // 1. "Repeats" checkbox is clicked
    $("#form_repeat").on("click", function () {
      set_repeat();
    });
    function set_repeat() {
      var f = document.getElementById("cat-field-form");
      var isdisabled = true;
      var mycolor = '#777777';
      var myvisibility = 'hidden';
      if (f.form_repeat.checked) {
        // uncheck "Days of Week" checkbox
        f.days_every_week.checked = false;
        // and disable, make grey all input related to it
        document.getElementById("days_label").style.color = mycolor;
        var days = document.getElementById("days").getElementsByTagName("input");
        var labels = document.getElementById("days").getElementsByTagName("label");
        for (var i = 0; i < days.length; i++) {
          days[i].disabled = isdisabled;
          labels[i].style.color = mycolor;
        }
        isdisabled = false;
        mycolor = '#000000';
        myvisibility = 'visible';
      }
      // if "Repeats" is checked, enable all input related to it
      // if "Repeats" is unchecked, disable and make grey all input related to it
      f.form_repeat_type.disabled = isdisabled;
      f.form_repeat_freq.disabled = isdisabled;
      f.form_enddate.disabled = isdisabled;
      document.getElementById("form_repeat_freq").style.color = mycolor;
      document.getElementById("form_repeat_type").style.color = mycolor;
      document.getElementById("repeats_label").style.color = mycolor;
      document.getElementById("endDate_label").style.color = mycolor;
      document.getElementById("form_enddate").style.color = mycolor;
    }

    // 2. "Days Of Week" checkbox is clicked
    $("#days_every_week").on("click", function () {
      set_days_every_week();
    });
    function set_days_every_week() {
      var f = document.getElementById("cat-field-form");
      if (f.days_every_week.checked) {
        // if "Days Of Week" is checked then,
        // uncheck "Repeats" checkbox
        // and disable, make grey all input related to it
        f.form_repeat.checked = false;
        f.form_repeat_type.disabled = true;
        f.form_repeat_freq.disabled = true;
        f.form_enddate.disabled = false;
        document.getElementById("form_repeat_freq").style.color = '#777777';
        document.getElementById("form_repeat_type").style.color = '#777777';
        document.getElementById("repeats_label").style.color = '#777777';
        // enable all input related to "Days of Week"
        document.getElementById("endDate_label").style.color = '#000000';
        document.getElementById("form_enddate").style.color = '#000000';
        var isdisabled = false;
        var mycolor = '#000000';
        var myvisibility = 'visible';
      } else {
        // "Days Of Week" is not checked
        // disable and make grey all input related to it
        var isdisabled = true;
        var mycolor = '#777777';
        var myvisibility = 'hidden';
      }
      document.getElementById("days_label").style.color = mycolor;
      var days = document.getElementById("days").getElementsByTagName("input");
      var labels = document.getElementById("days").getElementsByTagName("label");
      for (var i = 0; i < days.length; i++) {
        days[i].disabled = isdisabled;
        labels[i].style.color = mycolor;
      }
      // if both "Repeats" and "Days of Week" boxes are unchecked
      if(!f.days_every_week.checked  && !f.form_repeat.checked){
        // disable end_date ("until") input
        document.getElementById("endDate_label").style.color = mycolor;
        f.form_enddate.disabled = isdisabled;
        document.getElementById("form_enddate").style.color = mycolor;
      }
    }
  </script>
</body>
</html>
