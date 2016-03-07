<tr>
    <td>
        <div class="section-header">
            <table>
                <tbody>
                <tr>
                    <td>
                        <a id="tags-edit-button" onclick="top.restoreSession()"
                           href="javascript;"
                           class="css_button_small tags-edit-button"><span>Edit</span></a>

                        <a id="tags-save-button" style="display: none;" onclick="top.restoreSession()"
                           href="javascript;"
                           class="css_button_small tags-save-button"><span>Save</span></a>

                        <a id="tags-cancel-button" style="display: none;" onclick="top.restoreSession()"
                           href="javascript;"
                           class="css_button_small tags-cancel-button"><span>Cancel</span></a>
                    </td>
                    <td><span class="text"><b>Tags</b></span></td>
                </tr>
                </tbody>
            </table>
        </div>

        <?php if ( acl_check( 'patients', 'demo', '', 'write' ) ) { ?>
            <div class="notab" id="TAGS">
                <img id="tags-ajax-loading"style="padding:8px;" src="../../pic/ajax-loader.gif">
                <div id="tags-content"></div>
            </div>
            <script type="text/javascript">
                $(document).ready( function () {

                    var url = '<?php echo $GLOBALS['webroot']; ?>/interface/tags_filters/index.php?action=patients!view_tags&pid=<?php echo $_SESSION['pid']; ?>';
                    $("#tags-content").load( url, function() {
                        $("#tags-ajax-loading").hide();
                    });

                    $(".tags-edit-button").click( function ( e ) {
                        e.stopPropagation();
                        e.preventDefault();
                        var url = '<?php echo $GLOBALS['webroot']; ?>/interface/tags_filters/index.php?action=patients!edit&pid=<?php echo $_SESSION['pid']; ?>';
                        $("#tags-content").hide();
                        $("#tags-ajax-loading").show();
                        $("#tags-content").load( url, function() {
                            $("#tags-content").show();
                            $("#tags-ajax-loading").hide();
                            $("#tags-edit-button").hide();
                            $("#tags-save-button").show();
                            $("#tags-cancel-button").show();
                        });
                    });

                    $(".tags-save-button").click( function ( e ) {
                        e.stopPropagation();
                        e.preventDefault();
                        var url = '<?php echo $GLOBALS['webroot']; ?>/interface/tags_filters/index.php?action=patients!save_tags&pid=<?php echo $_SESSION['pid']; ?>';
                        var tagValues = $("#patient-tags").val();
                        var tagValArray = tagValues.split(",");
                        var data = { 'tags' : tagValArray };
                        $("#tags-content").hide();
                        $("#tags-ajax-loading").show();
                        $("#tags-content").load( url, data, function () {
                            $("#tags-content").show();
                            $("#tags-ajax-loading").hide();
                            $("#tags-edit-button").show();
                            $("#tags-save-button").hide();
                            $("#tags-cancel-button").hide();
                        });

                    });

                    $(".tags-cancel-button").click( function ( e ) {
                        e.stopPropagation();
                        e.preventDefault();
                        var url = '<?php echo $GLOBALS['webroot']; ?>/interface/tags_filters/index.php?action=patients!view_tags&pid=<?php echo $_SESSION['pid']; ?>';
                        $("#tags-content").hide();
                        $("#tags-ajax-loading").show();
                        $("#tags-content").load( url, function() {
                            $("#tags-ajax-loading").hide();
                            $("#tags-edit-button").show();
                            $("#tags-save-button").hide();
                            $("#tags-cancel-button").hide();
                        });
                    });
                });
            </script>
        <?php } else { ?>
            Not Authorized To View Tags
        <?php } ?>
    </td>
</tr>