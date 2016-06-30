<script type="text/javascript">
<!--
    $(document).ready( function() {

        var link = "<li><a id='tags' href='' onclick=\"return loadFrame2('max0','RTop','tags_filters/index.php?action=tags')\"><?php echo xl('Tags') ?></a></li>";
        $(link).insertAfter( $( "a:contains('ACL')" ).parent() );

        var link = "<li><a id='filters' href='' onclick=\"return loadFrame2('max0','RTop','tags_filters/index.php?action=patients')\"><?php echo xl('Patients/Tags') ?></a></li>";
        $(link).insertAfter( $( "a:contains('ACL')" ).parent() );

        var link = "<li><a id='filters' href='' onclick=\"return loadFrame2('max0','RTop','tags_filters/index.php?action=filters')\"><?php echo xl('Filters') ?></a></li>";
        $(link).insertAfter( $( "a:contains('ACL')" ).parent() );
    });
//-->
</script>