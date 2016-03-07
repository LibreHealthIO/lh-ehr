<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="encounterModalLabel"><?php echo xl( 'Rule Builder' ); ?></h4>
            </div>
            <div class="modal-body">
                <form id="rule-builder-form"style="margin:0px">
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <select name="requesting_action" class="form-control" id="requesting-action">
                                <option value="" selected> -- </option>
                                <option value="allow">Allow</option>
                                <option value="deny">Deny</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <select name="requesting_type" class="form-control" id="requesting-type">
                                <option value="" selected> -- </option>
                                <option value="user">User</option>
                                <option value="group">Group</option>
                            </select>
                        </div>

                        <div id="requesting-user-fg" class="form-group col-sm-2">
                            <select name="requesting_user" class="form-control" id="requesting-user">
                                <option value="" selected> -- </option>
                                <?php foreach ( $this->users as $user ) { ?>
                                <option value="<?php echo $user->username; ?>"><?php echo $user->name; ?>s</option>
                                <?php } ?>
                            </select>
                        </div>

                        <div id="requesting-group-fg" class="form-group col-sm-2" style="display: none;">
                            <select name="requesting_group" class="form-control" id="requesting-group">
                                <option value="" selected> -- </option>
                                <?php foreach ( $this->groups as $key => $title ) { ?>
                                    <option value="<?php echo $title; ?>"><?php echo $title; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class ="row">
                        <div class="form-group col-sm-2">
                            <select name="object_type" class="form-control" id="object-type">
                                <option value="patient">Patient</option>
                                <option value="tag">Tag</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-2" id="object-patient-fg">
                            <div id="patient-container">
                                <input required type="text" style="display: block; width: 100%;" class="form-control typeahead" id="object-patient-tt" placeholder="last, first">
                                <input type="hidden" id="object-patient" name="object_patient" />
                            </div>
                        </div>

                        <div id="object-tag-fg" class="form-group col-sm-2" style="display: none;">
                            <select name="object_tag" class="form-control" id="object-tag">
                                <option value=""> -- </option>
                                <?php foreach ( $this->tags as $tag ) { ?>
                                <option style="background-color: <?php echo $tag->hex_color; ?>" value="<?php echo $tag->tag_id; ?>"><?php echo $tag->tag_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <div class="input-group">
                                <input readonly placeholder="from ..." name="effective_datetime" type="text" data-date-format='dd/mm/yyyy' class="datetime form-control" id="effective-datetime">
                                <div class="input-group-addon"><span class="clear-date glyphicon glyphicon-ban-circle">&nbsp;</span></div>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <div class="input-group">
                                <input readonly placeholder="to ..." name="expiration_datetime" type="text" data-date-format='dd/mm/yyyy' class="datetime form-control" id="expiration-datetime">
                                <div class="input-group-addon"><span class="clear-date glyphicon glyphicon-ban-circle">&nbsp;</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-2">
                            <label for="priority" class="control-label">Priority</label>
                            <select name="priority" class="form-control" id="priority">
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="note" class="control-label">Note</label>
                            <input type="text" name="note" class="form-control" id="note"/>
                        </div>
                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save-new-rule" class="btn btn-primary">Save</button>
            </div>

        </div>
    </div>
    <script type="text/javascript">
        $(document).ready( function() {

            $("#save-new-rule").on( "click", function( e ) {

                var v = $("#rule-builder-form").validate();
                e.preventDefault();
                if ( v.form() == true ) {

                    var data = $('#rule-builder-form').serializeArray();

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $GLOBALS['webroot']; ?>/interface/tags_filters/index.php?&action=filters!create_filter',
                        data: data,
                        success: function (data) {
                            var oTable = data_table.getDatatable();
                            oTable.fnDraw();
                            $('#createModal').modal('hide');
                        }
                    });
                }
            });

            $(".clear-date").click( function() {
                var input = $(this).parent().siblings('input');
                var value = input.val();
                input.datetimepicker('reset')
            });

            $(".datetime").each( function() {
                $(this).datetimepicker({
                    step: 30,
                    mask:false,
                    format: 'Y-m-d H:i',
                    formatDate:'Y-m-d',
                    formatTime:'H:i',
                    defaultDate: '<?php echo date('Y-m-d'); ?>',
                    defaultTime: '<?php echo date('H:i'); ?>',
                    onChangeDateTime: function() {

                    }
                });
            });

            var patientSearch = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/index.php?action=filters!patient_search&query=%QUERY',
                    wildcard: '%QUERY'
                }
            });

            $('#object-patient-tt').typeahead({
                hint: true,
                highlight: true,
                minLength: 2
            }, {
                name: 'patient-search',
                source: patientSearch,
                display: "name",
                templates: {
                    suggestion: function ( e ) {
                        return '<p>' + e.displayKey + '</p>';
                    }
                },
                limit: 20
            });

            $('#object-patient-tt').bind('typeahead:selected', function( obj, datum, name ) {
                $("#object-patient").val( datum.pid );
            });


            $("#requesting-type").change( function() {
                var requestingType = $(this).val()
                if ( requestingType == 'user' ) {
                    $("#requesting-group").val('');
                    $("#requesting-group-fg").hide();
                    $("#requesting-user-fg").show();
                } else if ( requestingType == 'group' ) {
                    $("#requesting-user").val('');
                    $("#requesting-user-fg").hide();
                    $("#requesting-group-fg").show();
                }
            });


            $("#object-type").change( function() {
                var objectType = $(this).val()
                if ( objectType == 'patient' ) {
                    $("#object-tag").val('');
                    $("#object-tag-fg").hide();
                    $("#object-patient-fg").show();
                } else if ( objectType == 'tag' ) {
                    $("#object-patient").val('');
                    $("#object-patient-fg").hide();
                    $("#object-tag-fg").show();
                }
            });
        });

    </script>
</div>