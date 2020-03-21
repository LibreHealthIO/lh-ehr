
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
            <td colspan="2"><?php echo xlt("X12 Partner"); ?></td>
        </tr>
        <tr>
            <td><?php echo xlt("Partner Name");?></td>
            <td>
                <input type="text" class="form-control" size="20" name="name" value="<?php echo $this->partner->get_name(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("ID")."&nbsp;".xlt("Number")."&nbsp;".xlt("ETIN");?></td>
            <td><input type="text" class="form-control" size="20" name="id_number" value="<?php echo $this->partner->get_id_number(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("User logon Required Indicator ")."(".xlt("ISA01")."~".xlt("use 00 or 03");?></td>
            <td><input type="text" class="form-control" size="2" name="x12_isa01" value="<?php echo $this->partner->get_x12_isa01(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("User Logon ")."(".xlt("If 03 above").",".xlt(" else leave spaces")." (".xlt("ISA02").")";?></td>
            <td><input type="text" class="form-control" size="20" name="x12_isa02" value="<?php echo $this->partner->get_x12_isa02(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("User password required Indicator ")."(".xlt("ISA03")."~".xlt(" use 00 or 01").")";?></td>
            <td><input type="text" class="form-control" size="2" name="x12_isa03" value="<?php echo $this->partner->get_x12_isa03(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("User Password ")."(".xlt("ISA04")."~".xlt(" if 01 above").",".xlt(" else leave spaces").")";?></td>
            <td><input type="text" class="form-control" size="20" name="x12_isa04" value="<?php $this->partner->get_x12_isa04(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <td><?php echo xlt("Sender ID Qualifier ")."(".xlt("ISA05").")";?></td>
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
            <td><?php echo xlt("Sender ID ").'('.xlt("ISA06").")";?></td>
            <td><input type="text" class="form-control" size="20" name="x12_sender_id" value="<?php $this->partner->get_x12_sender_id(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("Receiver ID Qualifier ")."(".xlt("ISA07").")";?></td>
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
            <td><?php echo xlt("Receiver ID ")."(".xlt("ISA08").")";?></td>
            <td><input type="text" class="form-control" size="20" name="x12_receiver_id" 
                                                    value="<?php $this->partner->get_x12_receiver_id(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("Acknowledgment Requested ")."(".xlt("ISA14").")";?></td>
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
            <td><?php echo xlt("Usage Indicator ")."(".xlt("ISA15").")";?></td>
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
            <td><?php echo xlt("Application Sender Code ")."(".xlt("GS02").")";?></td>
            <td><input type="text" class="form-control" size="20" name="x12_gs02" value="<?php $this->partner->get_x12_gs02(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("Submitter EDI Access Number ")."(".xlt("PER06").")";?></td>
            <td><input type="text" class="form-control" size="20" name="x12_per06" value="<?php $this->partner->get_x12_per06(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td><?php echo xlt("Version");?></td>
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
            <td><?php echo xlt("Processing")."&nbsp;".xlt("Format");?></td>
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
            <td><?php echo xlt("Application Receiver Code ")."(".xlt("GS03 ")."-".xlt(" If blank ISA08 will be used").")";?></td>
            <td><input type="text" class="form-control" size="20" name="x12_gs03"
                                                    value="<?php echo $this->partner->get_x12_gs03(); ?>" onKeyDown="PreventIt(event)" maxlength="15"/></td>
        </tr>
        <tr height="25"><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td colspan="2">
            <?php if ($this->partner->get_id() == "") { ?>
                                <a class="css_button cp-submit" href="javascript:add_x12();"><span><?php echo xlt("Save");?></span></a>
            <?php } else { ?>
                                <a class="css_button cp-submit" href="javascript:add_x12();"><span><?php echo xlt("Save");?></span></a>
            <?php } ?>               

                <a href="controller.php?practice_settings&x12_partner&action=list"  class="css_button cp-negative" onclick="top.restoreSession()">
                    <span><?php echo xlt("Cancel");?></span></a></td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo $this->partner->id; ?>" />
    <input type="hidden" name="process" value="<?php echo self::PROCESS; ?>" />
    <input type="hidden" name="sub" value="no" />
</form>