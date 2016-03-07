<?php
$sanitize_all_escapes = true;
$fake_register_globals = false;
$controllerUrl = $GLOBALS['webroot']."/interface/tags_filters/index.php?action=".strtolower( $this->title );
?>
<head>
    <link rel="stylesheet" href="<?php echo $css_header; ?>" type="text/css">
    <style type="text/css">
        @import "<?php echo $GLOBALS['webroot'] ?>/library/js/datatables/media/css/demo_page.css";
        @import "<?php echo $GLOBALS['webroot'] ?>/library/js/datatables/media/css/demo_table.css";

        body.body_top {
            margin:0;
            background-color: #e0e0e0;
        }

        table{
            box-sizing: border-box;
            table-layout: fixed;
        }

        #<?php echo $this->dataTable->getTableId(); ?> {
            font-size: 14px;
        }

        a.delete {
            color: red;
        }

        .btn {
            background-color: #95a5a6;
        }

        .btn-primary {
            background-color: #e74c3c;
        }

        .search_init {
            width: 100%;
            margin: 0;
            line-height: 12px;
            font-size; 12px;
            text-align: left;
            float: left;
        }

        a.placement_facilities_checked {
            font-size: 9px;
        }
        #filters-table-table {
            width:100%;
        }
        #filters-table_processing {
            padding-bottom: 40px;
            z-index: 9999;
            top: 200px;
        }

        #time {
            font-size: 12px;
        }

        table.display td {
            padding: 3px 18px 3px 10px;
        }

        td a, a:focus, a:hover, a:visited {
            color: black;
        }

        td {
            color: #666666;
        }

        td a.editable-listbox-facility {
            font-weight: bold;
        }

        tr.odd td.sorting_1 {
            background-color: #fafafa;
        }

        tr.even td.sorting_1 {
            background-color: #f5f5f5;
        }

        tr.odd.purple
        {
            background-color: #d1c4e9;
        }

        tr.even.purple {
            background-color: #b39ddb;
        }

        tr.odd.teal {
            background-color: #b2dfdb;
        }

        tr.even.teal {
            background-color: #80cbc4;
        }

        tr.odd.green {
            background-color: #c8e6c9;
        }

        tr.even.green {
            background-color: #a5d6a7;
        }

        tr.odd.yellow {
            background-color: #fff9c4;
        }

        tr.even.yellow {
            background-color: #fff59d;
        }

        tr.odd.orange {
            background-color: #ffcc80;
        }

        tr.even.orange {
            background-color: #ffb74d;
        }

        tr.odd.red {
            background-color: #ef9a9a;
        }

        tr.even.red {
            background-color: #e57373;

        }

        tr.even.blue{
            background-color: #bbdefb;
        }

        tr.odd.blue{
            background-color: #90caf9;
        }

        tr.even.white{
            background-color: #fafafa;
        }

        tr.odd.white{
            background-color: #f5f5f5;
        }

        #<?php echo $this->dataTable->getTableId() ?> thead {
                                                          position: fixed;
                                                          width: 100%;
                                                      }

        #navbar {
            position: fixed;
            top: 0px;
            background-color: #ecf0f1;
            display: block;
            margin: 0;
            padding-top: 3px;
            width: 100%;
            z-index: 1000;
            height: 36px;
        }

        #alert-area.alert {
            display: inline-block;
            margin-bottom: 0px;
            margin-top: 0px;
            padding: 7px;
        }

        .last-update {
            float: right;
            padding-right: 15px;
        }

        .editable-buttons .editable-clear {
            margin-left: 7px;
        }

        #filters-table_filter {
            position: fixed;
            top: 36px;
            right: 0px;
            padding-right:100px;
            background-color: #ecf0f1;
        }

        #filters-table_length {
            position: fixed;
            top: 36px;
            width: 100%;
            background-color: #ecf0f1;
        }

        #<?php echo $this->dataTable->getTableId() ?> thead {
                                                          top: 60px;
                                                          background-color: #ecf0f1;
                                                      }

        #<?php echo $this->dataTable->getTableId() ?> tbody:before {
                                                          content: "-";
                                                          display: block;
                                                          line-height: 164px;
                                                          color: transparent;
                                                      }


        p.tt-suggestion {
            width: 400px;
            border-color: grey;
            border-style: solid;
            border-width: 1px 2px 1px 2px;
            background-color:rgba(255, 255, 255, 0.9);
            margin: 0px;
            padding: 4px;
        }

        .clear-date {
            cursor: pointer;
        }


    </style>
    <link href="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/jquery/jquery.js"></script>
    <script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/bootstrap/js/bootstrap.min.js"></script>
    <link href="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/bootstrap-editable/js/bootstrap-editable.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/datatables/media/js/jquery.dataTables.js"></script>
    <script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/data_table.js"></script>
    <script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/typeahead.bundle.min.js"></script>
    <link href="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/jquery.datetimepicker.css" rel="stylesheet">
    <script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/jquery.datetimepicker.js"></script>
    <script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/jquery.validate.js"></script>

    <script type="text/javascript">

        var data_table = new data_table( <?php echo $this->dataTable->toJson() ?>,
            function onShowDetails() {
            },
            function onHideDetails() {
            },
            function onAfterDraw() {
                // This is to reset width of columns after drawing because with fixed header, the widths don't line up
                $('table#<?php echo $this->dataTable->getTableId() ?> thead tr td.column-search-filter').each( function( index ) {
                    var thisWidth = $(this).width();
                    $('table#<?php echo $this->dataTable->getTableId() ?> tbody tr td').eq(index).css('width', thisWidth);
                    $('table#<?php echo $this->dataTable->getTableId() ?> tbody tr td').eq(index).css('column-width', thisWidth);
                    $('table#<?php echo $this->dataTable->getTableId() ?> tbody tr td').eq(index).css('-moz-column-width', thisWidth);
                    $('table#<?php echo $this->dataTable->getTableId() ?> tbody tr td').eq(index).css('column-width', thisWidth);
                });
            }
        );

        data_table.init();

        $(document).ready( function() {
            $(".search_init").change(function () {
                // Filter on the column (the index) of this element
                var oTable = data_table.getDatatable();
                var index = $(this).closest('td').index();
                oTable.fnFilter(this.value, index);
            });

            $('#createModal').on('shown.bs.modal', function () {
                // Set the hidden values

            });

            $('#createModal').on('hidden.bs.modal', function(){
                // When modal is cancelled or hidden, reset it
                $('#createModal textarea, #createModal input, #createModal select').val('');
            });



        });


    </script>
</head>


<body class="body_top">

<?php echo $this->content; ?>

</body>
