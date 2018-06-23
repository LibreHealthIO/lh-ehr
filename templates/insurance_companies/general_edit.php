
<script language="javascript">
function submit_insurancecompany() {
    if(document.insurancecompany.name.value.length>0) {
        top.restoreSession();
        document.insurancecompany.submit();
        //Z&H Removed redirection
    } else{
        document.insurancecompany.name.style.backgroundColor="red";
        document.insurancecompany.name.focus();
    }
}

function jsWaitForDelay(delay) {
     var startTime = new Date();
     var endTime = null;
     do {
        endTime = new Date();
     } while ((endTime - startTime) < delay);
}
</script>

<form name="insurancecompany" method="post" action="<?php echo $this->form_action; ?>">
<!-- it is important that the hidden form_id field be listed first, when it is called it populates any old information attached with the id, this allows for partial edits
        if it were called last, the settings from the form would be overwritten with the old information-->
<input type="hidden" name="form_id" value="<?php echo $this->insurancecompany->id;?>" />
<table class="table table-hover">
<tr>
    <td><?php echo xlt("Name");?> </td>
    <td>
        <input type="text" class="form-control input-sm" size="40" name="name" value="<?php echo $this->insurancecompany->get_name();?>" onKeyDown="PreventIt(event)" />
        *Required
    </td>
</tr>
<tr>
    <td><?php echo xlt("Attn");?> </td>
    <td>
        <input type="text" class="form-control input-sm" size="40" name="attn" value="<?php echo $this->insurancecompany->get_attn();?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>

<tr>
    <td><?php echo xlt("Address")."(".xlt("line1").")";?> </td>
    <td>
        <input type="text" class="form-control input-sm" size="40" name="address_line1" value="<?php echo $this->insurancecompany->address->line1;?>" 
               onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td><?php echo xlt("Address")."(".xlt("line2").")";?> </td>
    <td>
        <input type="text" class="form-control input-sm" size="40" name="address_line2" value="<?php echo $this->insurancecompany->address->line2;?>"
               onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td><?php echo xlt("City").",".xlt("state").",".xlt("zip");?> </td>
    <td class="form-inline">
        <input type="text" class="form-control input-sm" size="25" name="city" value="<?php echo $this->insurancecompany->address->city;?>" onKeyDown="PreventIt(event)" /> ,
        <input type="text" class="form-control input-sm" size="2" maxlength="2" name="state" value="<?php echo $this->insurancecompany->address->state;?>"
               onKeyDown="PreventIt(event)" /> ,
        <input type="text" class="form-control input-sm" size="5" name="zip" value="<?php echo $this->insurancecompany->address->zip;?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td><?php echo xlt("Phone");?> </td>
    <td>
        <input type="text" class="form-control input-sm" NAME="phone" SIZE="12" VALUE="<?php echo $this->insurancecompany->get_phone();?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>

    <td><?php echo xlt("CMS ID");?> </td>
    <td>
        <input type="text" class="form-control input-sm" size="15" name="cms_id" value="<?php echo $this->insurancecompany->get_cms_id();?>" onKeyDown="PreventIt(event)" />

        <?php if ($GLOBALS['support_encounter_claims']) {?>
                    &nbsp;&nbsp;For Encounter Claims:
                    <input type="text" class="form-control input-sm" size="15" name="alt_cms_id" value="<?php echo $this->insurancecompany->get_alt_cms_id();?>" onKeyDown="PreventIt(event)" />
        <?php }?>


    </td>
</tr>
<tr>
    <td><?php echo xlt("Payer Type");?> </td>
    <td>
        <!--{html_options name="ins_type_code" options=$insurancecompany->ins_type_code_array
        selected=$insurancecompany->get_ins_type_code()}-->
        <select class="form-control input-sm" name="ins_type_code">
            <?php foreach ($this->insurancecompany->ins_type_code_array   as $key => $value)
                {
                if($key==$this->insurancecompany->get_ins_type_code() ) { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
                <?php } else { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
                <?php }
                } ?>
        </select>
    </td>
</tr>
<!--
This is now deprecated use the newer x12 partner code instead
<tr>
    <td COLSPAN="1" ALIGN="LEFT" >X12 Receiver ID</td>
    <td COLSPAN="2" ALIGN="LEFT" >
        <input type="text" class="form-control input-sm" name="x12_receiver_id" value="{$insurancecompany->get_x12_receiver_id()}" >
    </td>
</tr>-->
<tr>
    <td><?php echo xlt("Default X12 Partner");?></td>
    <td>
        <!--{html_options name="x12_default_partner_id" options=$x12_partners selected=$insurancecompany->get_x12_default_partner_id()}-->
        <select class="form-control input-sm" name="x12_default_partner_id">
            <?php foreach ($this->x12_partners  as $key => $value)
                {
                if($key==$this->insurancecompany->get_x12_default_partner_id() ) { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
                <?php } else { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
                <?php }
                } ?>
        </select>
    </td>
</tr>
<tr>
    <?php if ($this->insurancecompany->get_allow_print_statement() == 0) { ?>
    <td><?php echo xlt("Do Not Print Statements");?> </td>
    <td>
        <input type="checkbox" class="control-label col-sm-2" size="1" name='allow_print_statement' class="checkbox" value="1" onKeyDown="PreventIt(event)" />
    </td>
    <?php } else { ?>
    <td><?php echo xlt("Print Statements");?> </td>
    <td>
        <input type="checkbox" class="control-label col-sm-2" size="1" name='allow_print_statement' class="checkbox" value="0" onKeyDown="PreventIt(event)" />
    </td>
    <?php }?>
</tr>
     <tr>
    <?php if ($this->insurancecompany->get_ins_inactive() == 0) { ?>
     <td><?php echo xlt("Deactivate");?></td>
      <td>
      <input type="checkbox" class="control-label col-sm-2" size="1" name='ins_inactive' class="checkbox" value="1" onKeyDown="PreventIt(event)" />
      </td>
    <?php } else { ?>
         <td><?php echo xlt("Activate");?></td>
      <td>
      <input type="checkbox" class="control-label col-sm-2" size="1" name='ins_inactive' class="checkbox" value="0" onKeyDown="PreventIt(event)" />
      </td>
    <?php }?>
     </tr>
<tr>
<tr height="25"><td colspan=2>&nbsp;</td></tr>
<tr>
    <td colspan="2"><a href="javascript:submit_insurancecompany();" class="css_button cp-submit"><span><?php echo xlt("Save");?></span></a>
        <a href="controller.php?practice_settings&insurance_company&action=list" class="css_button cp-negative" onclick="top.restoreSession()">
            <span><?php echo xlt("Cancel");?></span></a></td>
</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->insurancecompany->id;?>" />
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
</form>