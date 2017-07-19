
<script language="javascript">
    function add_x12()
    {
        if (document.x12_partner.name.value.length > 0)
        {
            top.restoreSession();
            document.x12_partner.submit();
        } else
        {
            document.x12_partner.name.style.backgroundColor = "red";
            document.x12_partner.name.focus();
        }
    }

    function Waittoredirect(delaymsec) {
        var st = new Date();
        var et = null;
        do {
            et = new Date();
        } while ((et - st) < delaymsec);

    }
</script>


<form name="x12_partner" method="post" action="<?php echo $this->form_action; ?>">
    <table class="table table-hover">
        <tr>
            <td colspan="2"><?php echo xl("X12 Partner"); ?></td>
        </tr>
        <tr>
            <td>Partner Name</td>
            <td>
                <input type="text" class="form-control" size="20" name="name" value="<?php echo $this->partner->get_name(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>ID&nbsp;Number&nbsp;ETIN</td>
            <td><input type="text" class="form-control" size="20" name="id_number" value="<?php echo $this->partner->get_id_number(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>User logon Required Indicator (ISA01~ use 00 or 03)</td>
            <td><input type="text" class="form-control" size="2" name="x12_isa01" value="<?php echo $this->partner->get_x12_isa01(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>User Logon (If 03 above, else leave spaces) (ISA02)</td>
            <td><input type="text" class="form-control" size="20" name="x12_isa02" value="<?php echo $this->partner->get_x12_isa02(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>User password required Indicator (ISA03~ use 00 or 01)</td>
            <td><input type="text" class="form-control" size="2" name="x12_isa03" value="<?php echo $this->partner->get_x12_isa03(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>User Password (ISA04~ if 01 above, else leave spaces)</td>
            <td><input type="text" class="form-control" size="20" name="x12_isa04" value="<?php $this->partner->get_x12_isa04(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <td>Sender ID Qualifier (ISA05)</td>
        <td>
            <!-- {html_options name="x12_isa05" options=$partner->get_idqual_array() selected=$partner->get_x12_isa05()} -->
            <select class="form-control" name="x12_isa05">
                <?php
                foreach ($this->partner->get_idqual_array() as $key => $value) {
                    if ($key == $this->partner->get_x12_isa05()) {
                        ?>
                        <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>
                    <?php } else { ?>
                        <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                    <?php }
                }
                ?>     
            </select>
        </td>
        </tr>
        <tr>
            <td>Sender ID (ISA06)</td>
            <td><input type="text" class="form-control" size="20" name="x12_sender_id" value="<?php $this->partner->get_x12_sender_id(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>Receiver ID Qualifier (ISA07)</td>
            <td>
                <!--{html_options name="x12_isa07" options=$partner->get_idqual_array() selected=$partner->get_x12_isa07()}</td>-->
                <select class="form-control" name="x12_isa07">
                    <?php
                    foreach ($this->partner->get_idqual_array() as $key => $value) {
                        if ($key == $this->partner->get_x12_isa07()) {
                            ?>
                            <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>
                        <?php } else { ?>
                            <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" ><?php echo $value; ?></option>
    <?php }
}
?>                     
                </select>
        </tr>
        <tr>
            <td>Receiver ID (ISA08)</td>
            <td><input type="text" class="form-control" size="20" name="x12_receiver_id" 
                                                    value="<?php $this->partner->get_x12_receiver_id(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>Acknowledgment Requested (ISA14)</td>
            <td>
                <!--{html_options name="x12_isa14" options=$partner->get_x12_isa14_array() selected=$partner->get_x12_isa14()}</td>-->
                <select class="form-control" name="x12_isa14">
                    <?php
                    foreach ($this->partner->get_x12_isa14_array() as $key => $value) {
                        if ($key == $this->partner->get_x12_isa14()) {
                            ?>
                            <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>
                            <?php } else { ?>
                                                    <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                            <?php }
                        }
                        ?>                     
                </select>            
        </tr>
        <tr>
            <td>Usage Indicator (ISA15)</td>
            <td>
                <!--{html_options name="x12_isa15" options=$partner->get_x12_isa15_array() selected=$partner->get_x12_isa15()}</td>-->
                <select class="form-control" name="x12_isa15">
                    <?php
                    foreach ($this->partner->get_x12_isa15_array() as $key => $value) {
                        if ($key == $this->partner->get_x12_isa15()) {
                            ?>
                            <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>
                            <?php } else { ?>
                                                    <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                            <?php }
                        }
                        ?>     
                </select>
        </tr>
        <tr>
            <td>Application Sender Code (GS02)</td>
            <td><input type="text" class="form-control" size="20" name="x12_gs02" value="<?php $this->partner->get_x12_gs02(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>Submitter EDI Access Number (PER06)</td>
            <td><input type="text" class="form-control" size="20" name="x12_per06" value="<?php $this->partner->get_x12_per06(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td>Version</td>
            <td>
                <!--{html_options name="x12_version" options=$partner->get_x12_version_array() selected=$partner->get_x12_version()}</td>-->
                <select class="form-control" name="x12_version">
                    <?php
                    foreach ($this->partner->get_x12_version_array() as $key => $value) {
                        if ($key == $this->partner->get_x12_version()) {
                            ?>
                            <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>
                                <?php } else { ?>
                                                        <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                                <?php }
                            }
                            ?>    
                </select>
        </tr>
        <tr>
            <td>Processing&nbsp;Format</td>
            <td>
                <!-- {html_options name="processing_format" options=$partner->get_processing_format_array() 
                selected=$partner->get_processing_format()}</td>-->
                <select class="form-control" name="processing_format">
                        <?php
                        foreach ($this->partner->get_processing_format_array() as $key => $value) {
                            if ($key == $this->partner->get_processing_format()) {
                                ?>
                                                    <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" selected="selected" ><?php echo $value; ?></option>
                            <?php } else { ?>
                                                    <option label="<?php echo $value; ?>" value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                            <?php }
                        }
                        ?>                
                </select>
        </tr>
        <tr>
            <td>Application Receiver Code (GS03 - If blank ISA08 will be used)</td>
            <td><input type="text" class="form-control" size="20" name="x12_gs03"
                                                    value="<?php echo $this->partner->get_x12_gs03(); ?>" onKeyDown="PreventIt(event)" maxlength="15"/></td>
        </tr>
        <tr height="25"><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td colspan="2">
            <?php if ($this->partner->get_id() == "") { ?>
                                <a class="css_button" href="javascript:add_x12();"><span>Save</span></a>
            <?php } else { ?>
                                <a class="css_button" href="javascript:add_x12();"><span>Save</span></a>
            <?php } ?>               

                <a href="controller.php?practice_settings&x12_partner&action=list"  class="css_button" onclick="top.restoreSession()">
                    <span>Cancel</span></a></td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo $this->partner->id; ?>" />
    <input type="hidden" name="process" value="<?php echo self::PROCESS; ?>" />
    <input type="hidden" name="sub" value="no" />
</form>