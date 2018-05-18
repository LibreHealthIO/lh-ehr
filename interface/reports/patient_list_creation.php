<?php
/**
 * This report lists all the demographics allergies,problems,drugs and lab results
 *
 * Copyright (C) 2014 Ensoftek, Inc
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
 * @package LibreHealth EHR
 * @link    http://librehealth.io
 */
require_once "reports_controllers/PatientListCreationController.php";
?>
<html>
    <head>
        <?php html_header_show();?>
        <title>
            <?php echo xlt('Patient List Creation'); ?>
        </title>
        <script type="text/javascript" src="../../library/overlib_mini.js"></script>
        <script type="text/javascript" src="../../library/dialog.js"></script>
        <script type="text/javascript" src="../../library/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="../../library/js/jquery-ui-1.8.21.custom.min.js"></script>
        <script language="JavaScript">
        var global_date_format = '%Y-%m-%d';
        function Form_Validate() {
            var d = document.forms[0];
            FromDate = d.form_from_date.value;
            ToDate = d.form_to_date.value;
            if ( (FromDate.length > 0) && (ToDate.length > 0) ) {
                if ( FromDate > ToDate ){
                    alert("<?php echo xls('To date must be later than From date!'); ?>");
                    return false;
                }
            }
            $("#processing").show();
            return true;
        }

        </script>
        <script type="text/javascript" src="../../library/dialog.js"></script>
        <link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
        <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
        <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['standard_js_path']?>fancybox-1.3.4/jquery.fancybox-1.3.4.css" media="screen" />
        <?php include_js_library("fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js");?>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
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
            #report_image {
                visibility: hidden;
                display: none;
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
        <script language="javascript" type="text/javascript">

            function submitForm() {
                var d_from = new String($('#form_from_date').val());
                var d_to = new String($('#form_to_date').val());

                var d_from_arr = d_from.split('-');
                var d_to_arr = d_to.split('-');

                var dt_from = new Date(d_from_arr[0], d_from_arr[1], d_from_arr[2]);
                var dt_to = new Date(d_to_arr[0], d_to_arr[1], d_to_arr[2]);

                var mili_from = dt_from.getTime();
                var mili_to = dt_to.getTime();
                var diff = mili_to - mili_from;

                $('#date_error').css("display", "none");

                if(diff < 0) //negative
                {
                    $('#date_error').css("display", "inline");
                }
                else
                {
                    $("#form_refresh").attr("value","true");
                                        top.restoreSession();
                    $("#theform").submit();
                }
            }

            //sorting changes
            function sortingCols(sort_by,sort_order)
            {
                $("#sortby").val(sort_by);
                $("#sortorder").val(sort_order);
                $("#form_refresh").attr("value","true");
                $("#theform").submit();
            }

            $(document).ready(function() {
                $(".numeric_only").keydown(function(event) {
                    //alert(event.keyCode);
                    // Allow only backspace and delete
                    if ( event.keyCode == 46 || event.keyCode == 8 ) {
                        // let it happen, don't do anything
                    }
                    else {
                        if(!((event.keyCode >= 96 && event.keyCode <= 105) || (event.keyCode >= 48 && event.keyCode <= 57)))
                        {
                            event.preventDefault();
                        }
                    }
                });
                <?php if($_POST['srch_option'] == "Communication"){ ?>
                        $('#com_pref').show();
                <?php } ?>
            });

        </script>
    </head>

    <body class="body_top">
        <!-- Required for the popup date selectors -->
        <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
        <span class='title'>
        <?php echo xlt('Report - Patient List Creation');?>
        </span>
        <!-- Search can be done using age range, gender, and ethnicity filters.
        Search options include diagnosis, procedure, prescription, medical history, and lab results.
        -->

        <div id="report_parameters_daterange">
            <p>
            <?php echo "<span style='margin-left:5px;'><b>".xlt('Date Range').":</b>&nbsp;".text(date($from_date, strtotime($from_date))) .
              " &nbsp; to &nbsp; ". text(date($to_date, strtotime($to_date)))."</span>"; ?>
            <span style="margin-left:5px; " ><b><?php echo xlt('Option'); ?>:</b>&nbsp;<?php echo text($_POST['srch_option']);
            if($_POST['srch_option'] == "Communication" && $_POST['communication'] != ""){
                if (isset($comarr[$_POST['communication']])) {
                echo "(".text($comarr[$_POST['communication']]).")";
                } else {
                echo "(".xlt('All').")";
                }
            }  ?></span>
            </p>
        </div>
        <form name='theform' id='theform' method='post' action='patient_list_creation.php' onSubmit="return Form_Validate();">
            <div id="report_parameters">
                <input type='hidden' name='form_refresh' id='form_refresh' value=''/>
                <table>
                      <tr>
                    <td width='900px'>
                                            <div class="cancel-float" style='float:left'>
                        <table class='text'>
                            <tr>
                                <?php // Show From and To dates fields. (TRK)
                                  showFromAndToDates(); ?>
                                <td class='label'><?php echo xlt('Option'); ?>: </td>
                                <td class='label'>
                                    <select name="srch_option" id="srch_option"
                                            onchange="javascript:$('#sortby').val('');$('#sortorder').val('');if(this.value == 'Communication'){ $('#communication').val('');$('#com_pref').show();}else{ $('#communication').val('');$('#com_pref').hide();}">
                                        <?php foreach($search_options as $skey => $svalue){ ?>
                                            <option <?php if ($_POST['srch_option'] == $skey) {
                                                echo 'selected';
                                            } ?> value="<?php echo attr($skey); ?>"><?php echo text($svalue); ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php ?>
                                </td>

                                <td >
                                    <span id="com_pref" style="display:none">
                                    <select name="communication" id="communication" title="<?php echo xlt('Select Communication Preferences'); ?>">
                                        <option> <?php echo xlt('All'); ?></option>
                                        <option value="allow_sms" <?php if ($communication == "allow_sms") {
                                            echo "selected";
                                        } ?>><?php echo xlt('Allow SMS'); ?></option>
                                        <option value="allow_voice" <?php if ($communication == "allow_voice") {
                                            echo "selected";
                                        } ?>><?php echo xlt('Allow Voice Message'); ?></option>
                                        <option value="allow_mail" <?php if ($communication == "allow_mail") {
                                            echo "selected";
                                        } ?>><?php echo xlt('Allow Mail Message'); ?></option>
                                        <option value="allow_email" <?php if ($communication == "allow_email") {
                                            echo "selected";
                                        } ?>><?php echo xlt('Allow Email'); ?></option>
                                    </select>
                                    </span>
                                </td>

                            </tr>
                            <tr>
                                <td class='label'><?php echo xlt('Patient ID'); ?>:</td>
                                <td><input name='patient_id' class="numeric_only" type='text' id="patient_id"
                                           title='<?php echo xla('Optional numeric patient ID'); ?>' value='<?php echo attr($patient_id); ?>'
                                           size='10' maxlength='20'/></td>
                                <?php   // Show patient age range input field. (TRk)
                                  showPatientAgeRange(); ?>
                                <td class='label'><?php echo xlt('Gender'); ?>:</td>
                                <td colspan="2"><?php echo generate_select_list('gender', 'sex', $sql_gender, 'Select Gender', 'Unassigned', '', ''); ?></td>
                            </tr>

                        </table>

                    </div>
                </td>
                <td height="100%" valign='middle' width="175">
                    <table style='border-left:1px solid; width:80%; height:100%'>
                            <tr>
                            <td width="130px">
                                <div style='margin-left:15px'><a href='#' class='css_button cp-submit' onclick='submitForm();'> <span>
                                            <?php echo xlt('Submit'); ?>
                                            </span> </a>
                                    </div>
                                </td>
                                <td>
                                    <div id='processing' style='display:none;' ><img src='../pic/ajax-loader.gif'/></div>
                                </td>

                            </tr>
                    </table>
                </td>
                    </tr>
                </table>
            </div>
        <!-- end of parameters -->
        <?php //Prepare and show results for report. (TRK)
          prepareAndShowResults() ?>
        </form>

<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "<?= $DateFormat; ?>"
        });
        $.datetimepicker.setLocale('<?= $DateLocale; ?>');
    });
        </script>
    </body>
</html>
