<?php
/**
 * Display Measures Engine Report Form
 * Copyright (C) 2015 - 2017      Suncoast Connection
 * 
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details. 
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 * 
 * @author  Art Eaton <art@suncoastconnection.com>
 * @author  Bryan lee <leebc 11 at acm dot org>
 * @author  Sam Likins <sam.likins@wsi-services.com>
 * @package LibreHealthEHR 
 * @link    http://suncoastconnection.com
 * @link    http://librehealth.io
 *
 * Please support this product by sharing your changes with the LibreHealth.io community.
 */
// SANITIZE ALL ESCAPES
$sanitize_all_escapes = true;

// STOP FAKE REGISTER GLOBALS
$fake_register_globals = false;

require_once ('mips_headers.inc.php');
require_once ('clinical_rules.php');


function existsDefault(&$array, $key, $default = '') {
  if(array_key_exists($key, $array)) {
    $default = trim($array[$key]);
  }

  return $default;
}
/////page title array...not needed as array anymore.
$page_titles = array(
  'pqrs'                  => xlt('Quality Measures 2018'),
  'pqrs_individual_2016'  => xlt('Quality Measures 2018'),
);

// See if showing an old report or creating a new report
$report_id = existsDefault($_GET, 'report_id');

// Collect the back variable, if pertinent
$back_link = existsDefault($_GET, 'back');

// If showing an old report, then collect information
if(!empty($report_id)) {
  $report_view = collectReportDatabase($report_id);

  $date_report = $report_view['date_report'];
 
  $type_report = $report_view['type'];

  $rule_filter = $report_view['type'];

  $begin_date = $report_view['date_begin'];
  
  if (isset($report_view['title'])) {

     $report_title=$report_view['title'];

     } else {

       $report_title="";

            }

  $target_date = $report_view['date_target'];
  $plan_filter = $report_view['plan'];
  $organize_method = $report_view['organize_mode'];
  $provider  = $report_view['provider'];
  $pat_prov_rel = $report_view['pat_prov_rel'];
  

  $dataSheet = json_decode($report_view['data'], true);
 
  $page_subtitle = ' - '.xlt('Date of Report').': '.text($date_report);
  $dis_text = ' disabled="disabled" ';

} else {
    // This is for a new empty report
  // Collect report type parameter.  Is no longer needed, but expects and array here.  Check $type_report.
  $type_report = existsDefault($_GET, 'type', 'standard');
  $rule_filter = existsDefault($_POST, 'form_rule_filter', $type_report);

  //  Setting report type
  $type_report = 'pqrs_individual_2016';
  

  // Collect form parameters (set defaults if empty)

  $begin_date = existsDefault($_POST, 'form_begin_date', '2018-01-01 00:00:00');  //change defaults in 2018
  $target_date = existsDefault($_POST, 'form_target_date', '2018-12-31 23:59:59');  //change defaults in 2018

  $plan_filter = existsDefault($_POST, 'form_plan_filter', '');
  $organize_method = (empty($plan_filter)) ? 'default' : 'plans';
  $provider = trim(existsDefault($_POST, 'form_provider', ''));
  $pat_prov_rel = (empty($_POST['form_pat_prov_rel'])) ? 'encounter' : trim($_POST['form_pat_prov_rel']);
  $page_subtitle = '';
  $dis_text = '';
}
//end is empty/old report
$DateFormat = DateFormatRead();
$DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);

$widthDyn = '470px';  //determine what is needed for pqrs

?>


<html>
  <head>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">  <!--should include stylesheet in project-->
    <link rel="stylesheet" href="../../assets/css/jquery-datetimepicker/jquery.datetimepicker.css">
    <title><?php echo $page_titles[$type_report]; ?></title>
    <script type="text/javascript" src="../../library/overlib_mini.js"></script>
    <script type="text/javascript" src="../../library/textformat.js"></script>
    <script type="text/javascript" src="../../library/dialog.js"></script>
    <script type="text/javascript" src="../../assets/js/jquery-min-3-1-1/index.js"></script>
    <script language="JavaScript">
var mypcc = '<?php echo text($GLOBALS['phone_country_code']) ?>';

$(document).ready(function() {
  var win = top.printLogSetup ? top : openemr.top;
  win.printLogSetup(document.getElementById('printbutton'));
});

function runReport() {
  // Validate first
  if(!validateForm()) {
    alert("<?php echo xls('Rule Set and Plan Set selections are not consistent. Please fix and Submit again.'); ?>");
    return false;
  }

  // Showing processing wheel
  $("#processing").show();

  // hide Submit buttons.  Most of these don't exist.
  $("#submit_button").hide();
  $("#xmla_button").hide();
  $("#xmlb_button").hide();
  $("#xmlc_button").hide();
  $("#print_button").hide();
  $("#genQRDA").hide();

  // hide instructions
  $("#instructions_text").hide();

  // Collect an id string via an ajax request
  top.restoreSession();
  $.get(
    "ajax/collect_new_report_id.php",
    function(data) {
      // Set the report id in page form
      $("#form_new_report_id").attr("value", data);

      // Start collection status checks
      collectStatus($("#form_new_report_id").val());

      // Run the report
      top.restoreSession();
      $.post(
        "ajax/execute_clinical_measures_report.php",
        {
          provider: $("#form_provider").val(),
          type: $("#form_rule_filter").val(),
          date_target: $("#form_target_date").val(),
          date_begin: $("#form_begin_date").val(),
          plan: $("#form_plan_filter").val(),
          labs: $("#labs_manual_entry").val(),
          pat_prov_rel: $("#form_pat_prov_rel").val(),
          execute_report_id: $("#form_new_report_id").val()
        }
      );
    }
  );
}

function collectStatus(report_id) {
  // Collect the status string via an ajax request and place in DOM at timed intervals
  top.restoreSession();

  // Do not send the skip_timeout_reset parameter, so don't close window before report is done.
  $.post(
    "ajax/status_report.php",
    { status_report_id: report_id },
    function(data) {
      if(data == "PENDING") {
        // Place the pending string in the DOM
        $('#status_span').replaceWith("<span id='status_span'><?php echo xlt('Preparing To Run Report'); ?></span>");
      } else if(data == "COMPLETE") {
        // Go into the results page
        top.restoreSession();
        link_report = "clinical_measures.php?report_id="+report_id;
        window.open(link_report, '_self', false);
        $("#processing").hide();
        $('#status_span').replaceWith("<a id='view_button' href='?report_id="+report_id+"' class='css_button' onclick='top.restoreSession()'><span><?php echo xlt('View Report'); ?></span></a>");
      } else {
        // Place the string in the DOM
        $('#status_span').replaceWith("<span id='status_span'>"+data+"</span>");
      }
    }
  );

  // run status check every 10 seconds
  var repeater = setTimeout("collectStatus("+report_id+")", 10000);
}


function GenXmlMIPS(sNested) {

  top.restoreSession();

  if(sNested == "PQRS") {
    var form_rule_filter = theform.form_rule_filter.value;
    var sLoc = 'generate_MIPS_xml.php?target_date='+theform.form_target_date.value+'&form_provider='+theform.form_provider.value+"&report_id=<?php echo attr($report_id); ?>&xmloptimize="+document.getElementById("xmloptimize").checked;
  } else {
    var sLoc = '../../custom/export_registry_xml.php?&target_date='+theform.form_target_date.value+'&nested='+sNested;
  }
  dlgopen(sLoc, '_blank', 600, 500);

  return false;
}



function validateForm() {
  return true;
}

function Form_Validate() {
<?php if(empty($report_id) && in_array($type_report, array('pqrs', 'pqrs_individual_2016',))) { ?>
  var d = document.forms[0];

  FromDate = d.form_begin_date.value;
  ToDate = d.form_target_date.value;

  if(FromDate.length > 0 && ToDate.length > 0) {
    if(FromDate > ToDate) {
      alert("<?php echo xls('End date must be later than Begin date!'); ?>");
      return false;
    }
  }

<?php
    }

    if($report_id != '') {
?>
  // For Results are in Gray Background & disabling anchor links
  $("#report_results").css("opacity", '0.4');
  $("#report_results").css("filter", 'alpha(opacity=40)');
  $("a").removeAttr("href");

<?php } ?>
  $("#form_refresh").attr("value","true");

  runReport();

  return true;
}
    </script>
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
    <div id="overDiv" style="position: absolute; visibility: hidden; z-index: 1000;"></div>

    <span class='title' hidden><?php echo xlt('MIPS: ').' '.$page_titles[$rule_filter].$page_subtitle; ?></span>
    <?php
    if(!empty($report_id)) {
        ?>
        <span class='label'><?php echo xlt('Report Dates:   ').' '.$begin_date.'   ~   '.$target_date; ?></span>
        <?php } ?>

    <form method='post' name='theform' id='theform' action='clinical_measures.php?type=<?php echo attr($type_report) ;?>' onsubmit='return validateForm()'>
      <div id="report_parameters">
        <table>
          <tr>
            <td scope="row" width='<?php echo $widthDyn; ?>'>
              <div style='float:left'>
                <table class='text'>
                
                


                      <td class='label'>
                         <?php echo htmlspecialchars( xl('Begin Date'), ENT_NOQUOTES);  ?>:
                      </td>
                      <td>
                         <input type='text' name='form_begin_date' id='form_begin_date' size='20'
                                value='<?php echo htmlspecialchars( $_POST['form_begin_date'], ENT_QUOTES); ?>'
                                title='<?php echo htmlspecialchars( xl('yyyy-mm-dd hh:mm:ss'), ENT_QUOTES); ?>'>
                      </td>
                   </tr>

                <tr>
                        <td class='label'>
                              <?php echo htmlspecialchars( xl('End Date'), ENT_NOQUOTES); ?>:
                        </td>
                    <td>
                           <input type='text' name='form_target_date' id='form_target_date' size='20'
                                  value='<?php echo htmlspecialchars( $_POST['form_target_date'], ENT_QUOTES); ?>'
                                  title='<?php echo htmlspecialchars( xl('yyyy-mm-dd hh:mm:ss'), ENT_QUOTES); ?>'>
                        </td>
                </tr>
               

                  <tr>
                    <td class='label'>
                      <?php echo xlt('Report Type'); ?>:
                    </td>
                    <td>
                      <select <?php echo $dis_text; ?> id='form_rule_filter' name='form_rule_filter'>
                        <option value='pqrs_individual_2016' selected><?php echo xlt('Registry Measures'); ?></option>
                      </select>
                    </td>
                  </tr>



		<input type='hidden' id='form_plan_filter' name='form_plan_filter' value=''>

                  <tr>
                    <td class='label'>
                      <?php echo htmlspecialchars(xl('Individual Provider Selection'), ENT_NOQUOTES); ?>:
                    </td>
                    <td>
                      <select <?php echo $dis_text; ?> id='form_provider' name='form_provider'>


<?php
      // Build a drop-down list of providers.
 
      $providers = sqlStatement('SELECT `id`, `lname`, `fname` FROM `users` WHERE `authorized` = 1  ORDER BY `lname`, `fname`;');

      while($providerRow = sqlFetchArray($providers)) {
?>
                        <option value='<?php echo htmlspecialchars($providerRow['id'], ENT_QUOTES); ?>' <?php if($providerRow['id'] == $provider) echo ' selected'; ?>><?php echo htmlspecialchars($providerRow['lname'].', '.$providerRow['fname'], ENT_NOQUOTES); ?></option>
<?php } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td class='label'>
                      <?php echo htmlspecialchars(xl('Individual NPI or Whole Group?'), ENT_NOQUOTES); ?>:
                    </td>
                    <td>
                      <select <?php echo $dis_text; ?> id='form_pat_prov_rel' name='form_pat_prov_rel' title='<?php echo xlt('Group selects patients that have the selected provider set in Demographics. Individual selects all patients that the above selected provider has seen.'); ?>'>
                        <option value='primary'<?php if($pat_prov_rel == 'primary') {echo ' selected';} ?>><?php echo xlt('Group (TIN)'); ?></option>
                        <option value='encounter'<?php if($pat_prov_rel == 'encounter') {echo ' selected';} ?>><?php echo xlt('Individual (NPI)'); ?></option>
                      </select>
                    </td>
                  </tr>

                </table>
              </div>
            </td>
            <td align='left' valign='middle' height="100%">

<?php if(empty($report_id)) { ?>
                      <a id='submit_button' href='#' class='css_button' onclick='runReport();'>
                        <span>
                          <?php echo htmlspecialchars(xl('Submit'), ENT_NOQUOTES); ?>
                        </span>
                      </a>
                      <span id='status_span'></span>
                      <div id='processing' style='margin:10px;display:none;'><img src='../../interface/pic/ajax-loader.gif'/></div>
<?php }

    if(!empty($report_id)) {
?>
                      <a href='#' class='css_button' id='printbutton'>
                        <span>
                          <?php echo htmlspecialchars(xl('Print'), ENT_NOQUOTES); ?>
                        </span>
                      </a>

                        Optimize XML report?
                        <input id="xmloptimize" type="checkbox" name="xmloptimize" value="1" />
                        
                        <a href="#"  id="xml_MIPS" class='css_button' onclick='GenXmlMIPS("PQRS");'>
                        <span>
                          <?php echo htmlspecialchars(xl('NEW XML for MIPS'), ENT_NOQUOTES); ?>
                        </span>
                      </a>
<?php

      if($back_link == 'list') {
?>
                      <a href='report_results.php' class='css_button' onclick='top.restoreSession()'><span><?php echo xlt('Return To Report Results'); ?></span></a>
<?php   } else { ?>
                      <a href='#' class='css_button' onclick='top.restoreSession(); $("#theform").submit();'><span><?php echo xlt('Start Another Report'); ?></span></a>
<?php
      }
    }
?>
                    </div>
                  </td>
                </tr>

            </td>
          </tr>
        </table>
      </div>  <!-- end of search parameters -->
      <br>
<?php if(!empty($report_id)) { ?>
      <div id="report_results">
        <table>
          <thead>
            <th style="text-align:left"><?php echo htmlspecialchars(xl('Title'), ENT_NOQUOTES); ?></th>
            <th style="text-align:center"><?php echo htmlspecialchars(xl('Total Patients'), ENT_NOQUOTES); ?></th>
            <th style="text-align:center"><?php echo htmlspecialchars(xl('Denominator'), ENT_NOQUOTES);  ?></th>
            <th style="text-align:center"><?php echo htmlspecialchars(xl('Exclusions'), ENT_NOQUOTES); ?></th>
            <th style="text-align:center"><?php echo htmlspecialchars(xl('Performance Met'), ENT_NOQUOTES);  ?></th>
            <th style="text-align:center"><?php echo htmlspecialchars(xl('Not Met'), ENT_NOQUOTES);  ?></th>
            <th style="text-align:center"><?php echo htmlspecialchars(xl('Performance Rate'), ENT_NOQUOTES); ?></th>
          </thead>
          <tbody>  <!-- added for better print-ability -->
<?php

      $firstProviderFlag = true;
      $firstPlanFlag = true;
      $existProvider = false;
$bgcolor = 0;
      foreach($dataSheet as $row) {
?>
            <tr bgcolor="<?php if ($bgcolor % 2 == 0){echo 'AliceBlue';}else{echo 'BurlyWood';} ?>">
<?php
        if(isset($row['is_main']) || isset($row['is_sub'])) {
?>
              <td class='detail'>
<?php
          if(isset($row['is_main'])) {

            $main_pass_filter = $row['pass_filter'];

            echo '<b>'.generate_display_field(array('data_type' => '1', 'list_id' => 'clinical_rules'), $row['id']).'</b>';

            $tempMeasuresString = '';

                if(!empty($row['pqrs_code'])) {
                  $tempMeasuresString .= ' '.htmlspecialchars(xl('MIPS ').preg_replace('/PQRS_/', '',$row['pqrs_code']), ENT_NOQUOTES).' ';              
                }
  

            if(!empty($tempMeasuresString)) {
                $patterns = array();
                $patterns[0] = '/PQRS_0/';
                $patterns[1] = '/pre_0/';

                $mipsnumber = preg_replace($patterns, '_', $row['pqrs_code']);
                $measureURL = 'http://suncoastconnection.com/standards/Registrymeasures/2017_Measure'. $mipsnumber.'_Registry.pdf';
                ?>
                <a href='<?php echo $measureURL;?>' target="_blank"><?php echo $tempMeasuresString;?></a>
                <?php
             
            }

            if(!(empty($row['concatenated_label']))) {  ///this condition can be removed...not sure which way yet.
              echo ', '.htmlspecialchars(xl($row['concatenated_label']), ENT_NOQUOTES).' ';
            }
          } else {
            echo generate_display_field(array('data_type' => '1', 'list_id' => 'rule_action_category'), $row['action_category']);
            echo ': '.generate_display_field(array('data_type' => '1', 'list_id' => 'rule_action'), $row['action_item']);
          }
?>
              </td>
              <td style="text-align:center"><?php echo $row['total_patients']; ?></td>
<?php
          if(isset($row['itemized_test_id']) && $row['pass_filter'] > 0) {
            $query = http_build_query(array(
              'from_page' => 'pqrs_report',
              'pass_id' => 'all',
              'report_id' => attr($report_id),
              'itemized_test_id' => attr($row['itemized_test_id']),
              'numerator_label' => attr($row['numerator_label'])
            ));
?>

              <td style="text-align:center"><a href='patient_select.php?<?php echo $query; ?>' onclick='top.restoreSession()'><?php echo $row['pass_filter']; ?></a></td>
<?php
          } else {
?>
              <td style="text-align:center"><?php echo $row['pass_filter']; ?></td>
<?php
          }



            if(isset($row['itemized_test_id']) && $row['excluded'] > 0) {

              $query = http_build_query(array(
                'from_page' => 'pqrs_report',
                'pass_id' => 'exclude',
                'report_id' => attr($report_id),
                'itemized_test_id' => attr($row['itemized_test_id']),
                'numerator_label' => attr($row['numerator_label']),
              ));
?>
              <td style="text-align:center"><a href='patient_select.php?<?php echo $query; ?>' onclick='top.restoreSession()'><?php echo $row['excluded']; ?></a></td>
<?php
            } else {
?>
              <td style="text-align:center"><?php echo $row['excluded']; ?></td>
<?php
            }


 
          if(isset($row['itemized_test_id']) && $row['pass_target'] > 0) {
            $query = http_build_query(array(
              'from_page' => 'pqrs_report',
              'pass_id' => 'pass',
              'report_id' => attr($report_id),
              'itemized_test_id' => attr($row['itemized_test_id']),
              'numerator_label' => attr($row['numerator_label']),
            ));
?>
              <td style="text-align:center"><a href='patient_select.php?<?php echo $query; ?>' onclick='top.restoreSession()'><?php echo $row['pass_target']; ?></a></td>
<?php
          } else {
?>
              <td style="text-align:center"><?php echo $row['pass_target']; ?></td>
<?php
          }

          $failed_items = 0;

          if(isset($row['is_main'])) {
              $failed_items = $row['pass_filter'] - $row['pass_target'] - $row['excluded'];

          } 

          if(isset($row['itemized_test_id']) && ($failed_items > 0) ) {
            $query = http_build_query(array(
              'from_page' => 'pqrs_report',
              'pass_id' => 'fail',
              'report_id' => attr($report_id),
              'itemized_test_id' => attr($row['itemized_test_id']),
              'numerator_label' => attr($row['numerator_label']),
            ));
?>
              <td style="text-align:center"><a href='patient_select.php?<?php echo $query; ?>' onclick='top.restoreSession()'><?php echo $failed_items; ?></a></td>
<?php
          } else {
?>
              <td style="text-align:center"><?php echo $failed_items; ?></td>
<?php
          }

?>
              <td style="text-align:center"><?php echo $row['percentage']; ?></td>
<?php
        } elseif(isset($row['is_provider'])) {
          // Display the provider information
          if(!$firstProviderFlag && $provider == 'collate_outer') {
?>
            <tr><td>&nbsp;</td></tr>
<?php
          }

          $contents = htmlspecialchars(xl('Provider').': '.$row['prov_lname'].','.$row['prov_fname'], ENT_NOQUOTES);

          if(!empty($row['npi']) || !empty($row['federaltaxid'])) {
            $contents .= ' (';
            if(!empty($row['npi'])) {
              $contents .= ' '.htmlspecialchars(xl('NPI').':'.$row['npi'], ENT_NOQUOTES).' ';
            }
            if(!empty($row['federaltaxid'])) {
              $contents .= ' '.htmlspecialchars(xl('TIN').':'.$row['federaltaxid'], ENT_NOQUOTES).' ';
            }
            $contents .= ')';
          }
?>
              <td class='detail' align='center'><b><?php echo $contents; ?></b></td>
<?php
          $firstProviderFlag = false;
          $existProvider = true;
        } else {
          if(!$firstPlanFlag && $provider != 'collate_outer') {
?>
            <tr><td>&nbsp;</td></tr>
<?php
          }

          $contents = htmlspecialchars(xl('Plan'), ENT_NOQUOTES).': '.generate_display_field(array('data_type' => '1', 'list_id' => 'clinical_plans'), $row['id']);

?>
              <td class='detail' align='center'><b><?php echo $contents; ?></b></td>
<?php
          $firstPlanFlag = false;
        }
?>
            </tr>
<?php 
 $bgcolor +=1; 

  } ?>
          </tbody>
        </table>
      </div>  <!-- end of search results -->
<?php } else { ?>
      <div id="instructions_text" class='text'><?php echo htmlspecialchars(xl('Please input search criteria above, and click Submit to start report.'), ENT_NOQUOTES); ?></div>
<?php } ?>
      <input type='hidden' name='form_new_report_id' id='form_new_report_id' value=''/>
    </form>
  </body>
<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_begin_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_target_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale;?>');
    });
</script>

</html>
