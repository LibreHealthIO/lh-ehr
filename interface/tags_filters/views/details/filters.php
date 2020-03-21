<div>
    <a href="javascript;" class="delete" data-id="<?php echo $this->filterId; ?>">Delete</a>
</div>

<script type="text/javascript">
    $(document).ready( function() {
        $(".delete").on( 'click', function( e ) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var data = { id : id };
            $.post( '<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/index.php?action=filters!delete_filter', data, function( e ) {
                // Once the filter is deleted, hide the row
                var oTable = data_table.getDatatable();
                oTable.fnDraw();
            });
        });
    });
</script>