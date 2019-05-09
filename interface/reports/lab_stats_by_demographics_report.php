<?php
/**
 * interface/reports/lab_stats_by_demographics.php Lists lab stats by demographics
 * integrates dataTables in report.  Ability to download in .pdf, .xls, or .csv..
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
 * along with this program. If not, see <http://opensource.org/licenses/mpl-license.php>;.
 * Copyright (c) 2018 Growlingflea Software <daniel@growlingflea.com>
 * File adapted for user activity log.
 * @package LibreEHR
 * @author  Daniel Pflieger daniel@growlingflea.com daniel@mi-squared.com
 */
$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/vendor/libreehr/Framework/DataTable/DataTable.php");
require_once "reports_controllers/ClinicalController.php";
require_once($GLOBALS['srcdir'].'/headers.inc.php');

$library_array = array('datatables');

$DateFormat = DateFormatRead();
//make sure to get the dates
if ( ! isset($_POST['form_from_date'])) {

    $from_date = fixDate(date($DateFormat));

} else {
    $from_date = fixDate($_POST['form_from_date']);
}

if ( !isset($_POST['form_to_date'])) {
    // If a specific patient, default to 2 years ago.
    $to_date = fixDate(date($DateFormat));


} else{

    $to_date = fixDate($_POST['form_to_date']);
}

$to_date = new DateTime($to_date);
$to_date->modify('+1 day');
$to_date = $to_date->format('Y-m-d');
$min_age = $_POST['min_age'];
$max_age = $_POST['max_age'];

function itemPerPage(int $interval, int $iterations) {
    while ($iterations--) {
        $interval += $interval;
        echo "<option value=$interval>$interval</option>";
    }
}

function totalResultRows() {
    $query  = "select count(*) from procedure_result pres ";
    $query .= "join procedure_report prep on pres.procedure_report_id = prep.procedure_report_id " ;
    $query .= "join procedure_order prord on prep.procedure_order_id = prord.procedure_order_id " ;
    $query .= "join patient_data pd on pd.pid = prord.patient_id ";
    $query .= "join list_options on option_id = pd.ethnicity and list_id = 'ethnicity' ";
    $query .= "where  pres.result_text != '' and pres.abnormal != ''";
    $queryArray = array();
    $result = sqlStatement($query, $queryArray);
    $resultArray = sqlFetchArray($result);
    return $resultArray['count(*)'];
}

function availablePages($totalRows, $interval) {
    echo round($totalRows / $interval);
}

?>
<head>
    <?php html_header_show();?>
    <title><?php xl('Lab Stats by Demographics','e'); ?></title>
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <?php call_required_libraries($library_array) ?>

    <script>
        $(document).ready(function() {
            loadOptionsToPageSelector();
            if($('#show_lab_details_selector').val()) {
                $('.session_table').hide();
                $('#show_lab_details_table').show();

                lab_result_details();

            }


        });


        var oTable;
        // This is for callback by the find-code popup.
        // Appends to or erases the current list of diagnoses.
        function set_related(codetype, code, selector, codedesc) {
            var f = document.forms[0][current_sel_name];
            var s = f.value;
            if (code) {
                if (s.length > 0) s += ';';
                s += codetype + ':' + code;
            } else {
                s = '';
            }
            f.value = s;
        }

        //This invokes the find-code popup.
        function sel_diagnosis(e) {
            current_sel_name = e.name;
            dlgopen('../patient_file/encounter/find_code_popup.php?codetype=<?php echo collect_codetypes("diagnosis","csv"); ?>', '_blank', 500, 400);
        }

        //This invokes the find-code popup.
        function sel_procedure(e) {
            current_sel_name = e.name;
            dlgopen('../patient_file/encounter/find_code_popup.php?codetype=<?php echo collect_codetypes("procedure","csv"); ?>', '_blank', 500, 400);
        }
        $("#form_from_date").val();
        //Function to initiate datatables plugin

        function refreshPage(){

            window.location.reload();

        }


        function lab_result_details() {
          
            $('#image').show();

            oTable=$('#show_lab_details_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'csv'
                ],
                ajax:{
                    type: "POST",
                    url: "../../library/ajax/clinical_stats_and_lab_stats_by_demographics_report_ajax.php",
                    data: {
                        func:"get_all_lab_data",
                        min_age:"<?php echo $_POST['min_age']  ; ?>",
                        max_age:"<?php echo $_POST['max_age']  ; ?>",
                        results_per_page: $('#rpp').val(),
                        page_number: $('#nof').val()

                    }, complete: function(){
                        $('#image').hide();
                    }},
                columns:[
                    { 'data': 'pid'         },
                    { 'data': 'gender'      },
                    { 'data': 'dob'         },
                    { 'data': 'ethnicity'   },
                    { 'data': 'result_text' },
                    { 'data': 'result'      },
                    { 'data': 'abnormal'    }

                ],
                "iDisplayLength": 100,
                "select":true,
                "searching":true,
                "retrieve" : true,
                 "destroy":true
            });

            $('#column0_search_show_lab_details_table').on( 'keyup', function () {
                oTable
                    .columns( 0 )
                    .search( this.value )
                    .draw();
            } );

            $('#column1_search_show_lab_details_table').on( 'keyup', function () {
                oTable
                    .columns( 1 )
                    .search( this.value)
                    .draw();
            } );

            $('#column2_search_show_lab_details_table').on( 'keyup', function () {
                oTable
                    .columns( 2 )
                    .search( this.value )
                    .draw();
            } );

            $('#column3_search_show_lab_details_table').on( 'keyup', function () {
                oTable
                    .columns( 3 )
                    .search( this.value )
                    .draw();
            } );

            $('#column4_search_show_lab_details_table').on( 'keyup', function () {
                oTable
                    .columns( 4 )
                    .search( this.value )
                    .draw();
            } );

            $('#column5_search_show_lab_details_table').on( 'keyup', function () {
                oTable
                    .columns( 5 )
                    .search( this.value )
                    .draw();
            } );

            $('#column6_search_show_lab_details_table').on( 'keyup', function () {
                oTable
                    .columns( 6 )
                    .search( this.value )
                    .draw();
            } );




        }







</script>
</head>
<body class="body_top formtable">&nbsp;&nbsp;
<form action="./lab_stats_by_demographics_report.php" method="post">
    <label><input value="Refresh Query" type="submit" id="show_lab_details_selector" name="show_lab_details" ><?php ?></label>


    <table id="header_table">



            <tr><td>

                <input hidden id = 'show_lab_details_button' value = '<?php echo isset($_POST['show_lab_details']) ? $_POST['show_lab_details'] : null  ?>'>


            </td></tr>
        <tr>

            <td class='label'><?php echo htmlspecialchars(xl('Age Min'),ENT_NOQUOTES); ?>:</td>
            <td><input type='text' name='min_age' size='10' maxlength='250' value='<?php echo htmlspecialchars($min_age, ENT_QUOTES); ?>' > </td>
            <td></td>
            <td class='label'><?php echo htmlspecialchars(xl('Age Max'),ENT_NOQUOTES); ?>:</td>
            <td><input type='text' name='max_age' size='10' maxlength='250' value='<?php echo htmlspecialchars($max_age, ENT_QUOTES); ?>' > </td>
            <td class='label'><?php echo htmlspecialchars(xl('Results Per Page'),ENT_NOQUOTES); ?>:</td>
            <td><select name='results_per_page' id="rpp">
                <?php itemPerPage(250, 6); ?>
                <option value="all">all</option>
            </select></td>
            <td class='label'><?php echo htmlspecialchars(xl('Navigate to Page'),ENT_NOQUOTES); ?>:</td>
            <td><select name='number_of_pages' id="nof">
                <?php
                    $totalRows = totalResultRows(); 
                    
                ?>
            </select></td>

        </tr>
    </table>
</form>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               



&nbsp;&nbsp;

<img hidden id="image" src="../../images/loading.gif" width="100" height="100">



<table cellpadding="0" cellspacing="0" border="0" class="display formtable session_table" id="show_lab_details_table">
    <thead>

    <tr>
        <th><input  id = 'column0_search_show_lab_details_table'></th>
        <th><input  id = 'column1_search_show_lab_details_table'></th>
        <th><input  id = 'column2_search_show_lab_details_table'></th>
        <th><input  id = 'column3_search_show_lab_details_table'></th>
        <th><input  id = 'column4_search_show_lab_details_table'></th>
        <th><input  id = 'column5_search_show_lab_details_table'></th>
        <th><input  id = 'column6_search_show_lab_details_table'></th>


    </tr>

    <tr>
        <th><?php echo xla('PID'); ?></th>
        <th><?php echo xla('Gender'); ?></th>
        <th><?php echo xla('DOB'); ?></th>
        <th><?php echo xla('ETHNICITY'); ?></th>
        <th><?php echo xla('Test'); ?></th>
        <th><?php echo xla('Result'); ?></th>
        <th><?php echo xla('Abnormal'); ?></th>
    </tr>

    </thead>
    <tbody id="users_list">
    </tbody>
</table>

</body>
<link rel="stylesheet" href="../../library/css/jquery.datetimepicker.css">
<script type="text/javascript" src="../../library/js/jquery.datetimepicker.full.min.js"></script>
<style type="text/css">#header_table td{ padding-right: 3px; }</style>
<script>
    $(function() {
        $("#form_from_date").datetimepicker({
            timepicker: false,
            format: "Y-m-d"
        });
        $("#form_to_date").datetimepicker({
            timepicker: false,
            format: "Y-m-d"
        });

    });

    $('#rpp').change(function () {
        loadOptionsToPageSelector();
        oTable.destroy();
        lab_result_details();
    });
    $('#nof').change(function () {
        oTable.destroy();
        lab_result_details();
    });
    function generatePageOptions(pages) {
        var html = "";
        while (pages--) {
            html += "<option value='" + pages + "'>page " + pages  + "</option>";
        }
        return html;

    }

    function loadOptionsToPageSelector() {
        var rpp = $('#rpp').val();
        if (rpp == "all") {
            $('#nof').html("<option value='all'>All pages</option>");
        }
        else {
        //calculate the number of pages for the rows
        var numberOfRows = "<?php echo $totalRows; ?>";
        var pages = Math.round(numberOfRows / rpp);
        var availableOptions = generatePageOptions(pages);
        $('#nof').html(availableOptions);
        }
    }
</script>
