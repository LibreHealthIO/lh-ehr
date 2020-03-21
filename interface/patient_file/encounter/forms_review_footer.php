<?php if(isset($_REQUEST['review_id']))
{ ?>

<script type="text/javascript">
    jQuery(document).ready(function()
    {
        jQuery("body table:first").hide();
        jQuery(".encounter-summary-column").hide();
        jQuery(".css_button").hide();
        jQuery(".css_button_small").hide();
        jQuery(".encounter-summary-column:first").show();
        jQuery(".title:first").text("Review " + jQuery(".title:first").text() + " ("+<?php echo $encounter; ?>+")");
    });
</script>

<?php } ?>
