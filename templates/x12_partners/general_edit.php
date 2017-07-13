
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
    <table width="400px" style="font-size:9pt;" CELLSPACING="0" CELLPADDING="3" border="0">
        <tr>
            <td colspan="2"><?php echo xl("X12 Partner"); ?></td>
        </tr>
        <tr>
            <td ALIGN="LEFT" VALIGN="MIDDLE" >Partner&nbsp;Name</td>
            <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >
                <input type="text" size="20" name="name" value="<?php echo $this->partner->get_name(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td ALIGN="LEFT" VALIGN="MIDDLE" >ID&nbsp;Number&nbsp;ETIN</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="id_number" value="<?php echo $this->partner->get_id_number(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >User logon Required Indicator (ISA01~ use 00 or 03)'}</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="2" name="x12_isa01" value="<?php echo $this->partner->get_x12_isa01(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >User Logon (If 03 above, else leave spaces) (ISA02)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_isa02" value="<?php echo $this->partner->get_x12_isa02(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >User password required Indicator (ISA03~ use 00 or 01)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="2" name="x12_isa03" value="<?php echo $this->partner->get_x12_isa03(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >User Password (ISA04~ if 01 above, else leave spaces)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_isa04" value="<?php $this->partner->get_x12_isa04(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <td VALIGN="MIDDLE" >Sender ID Qualifier (ISA05)</td>
        <td COLSPAN="2" VALIGN="MIDDLE" >
            <!-- {html_options name="x12_isa05" options=$partner->get_idqual_array() selected=$partner->get_x12_isa05()} -->
            <select name="x12_isa05">
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
            <td VALIGN="MIDDLE" >Sender ID (ISA06)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_sender_id" value="<?php $this->partner->get_x12_sender_id(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >Receiver ID Qualifier (ISA07)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" >
                <!--{html_options name="x12_isa07" options=$partner->get_idqual_array() selected=$partner->get_x12_isa07()}</td>-->
                <select name="x12_isa07">
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
            <td VALIGN="MIDDLE" >Receiver ID (ISA08)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_receiver_id" 
                                                    value="<?php $this->partner->get_x12_receiver_id(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >Acknowledgment Requested (ISA14)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" >
                <!--{html_options name="x12_isa14" options=$partner->get_x12_isa14_array() selected=$partner->get_x12_isa14()}</td>-->
                <select name="x12_isa14">
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
            <td VALIGN="MIDDLE" >Usage Indicator (ISA15)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" >
                <!--{html_options name="x12_isa15" options=$partner->get_x12_isa15_array() selected=$partner->get_x12_isa15()}</td>-->
                <select name="x12_isa15">
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
            <td VALIGN="MIDDLE" >Application Sender Code (GS02)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_gs02" value="<?php $this->partner->get_x12_gs02(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >Submitter EDI Access Number (PER06)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_per06" value="<?php $this->partner->get_x12_per06(); ?>" onKeyDown="PreventIt(event)" /></td>
        </tr>
        <tr>
            <td VALIGN="MIDDLE" >Version</td>
            <td COLSPAN="2" VALIGN="MIDDLE" >
                <!--{html_options name="x12_version" options=$partner->get_x12_version_array() selected=$partner->get_x12_version()}</td>-->
                <select name="x12_version">
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
            <td VALIGN="MIDDLE" >Processing&nbsp;Format</td>
            <td COLSPAN="2" VALIGN="MIDDLE" >
                <!-- {html_options name="processing_format" options=$partner->get_processing_format_array() 
                selected=$partner->get_processing_format()}</td>-->
                <select name="processing_format">
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
            <td VALIGN="MIDDLE" >Application Receiver Code (GS03 - If blank ISA08 will be used)</td>
            <td COLSPAN="2" VALIGN="MIDDLE" ><input type="text" size="20" name="x12_gs03"
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
