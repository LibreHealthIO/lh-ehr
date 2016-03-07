<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="encounterModalLabel"><?php echo xl( 'New Filter' ); ?></h4>
            </div>
            <div class="modal-body">
                <form id="new-patient-encounter-form">
                    <div id="filter-form">
                        <div class="form-group form-horizontal">
                            <label for="filter-for-tag" class="control-label">Filter for Tag:</label>
                            <select name="filter-for-tag" class="form-control" id="filter-for-tag">
                                <option value=""> -- </option>
                                <?php foreach ( $this->tags as $tag ) { ?>
                                <option value="<?php echo $tag->id; ?>"><?php echo $tag->tag_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group form-horizontal">
                            <label for="action" class="control-label">Action:</label>
                            <select name="action" type="text" class="form-control" id="action">
                                <option value="deny">deny</option>
                                <option value="allow">allow</option>
                            </select>
                        </div>
                        <div class="form-group form-horizontal" id="usernname-typeahead">
                            <label for="usernname" class="control-label">User Name:</label>
                            <div id="patient-container">
                                <input required name="usernname" type="text" style="display: block; width: 100%;" class="form-control typeahead" id="usernname">
                            </div>
                        </div>
                        <div class="form-group form-horizontal">
                            <label for="effective-date" class="control-label">Effective Date:</label>
                            <input name="effective-date" type="text" data-date-format='dd/mm/yyyy' class="form-control" id="effective-date">
                        </div>
                        <div class="form-group form-horizontal">
                            <label for="expiration-date" class="control-label">Expiration Date:</label>
                            <input name="expiration-date" type="text" data-date-format='dd/mm/yyyy' class="form-control" id="expiration-date">
                        </div>
                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save-new-encounter" class="btn btn-primary">Save</button>
            </div>

        </div>
    </div>
</div>