
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
<table style="font-size:9pt;" width="500px" CELLSPACING="0" CELLPADDING="3">
<tr>
    <td width="220px" VALIGN="MIDDLE" ><?php echo xl("Name");?> </td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="40" name="name" value="<?php echo $this->insurancecompany->get_name();?>" onKeyDown="PreventIt(event)" /> 
        Required
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo xl("Attn");?> </td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="40" name="attn" value="<?php echo $this->insurancecompany->get_attn();?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>

<tr>
    <td VALIGN="MIDDLE" ><?php echo xl("Address");?> </td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="40" name="address_line1" value="<?php $this->insurancecompany->address->line1;?>" 
               onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo xl("Address");?> </td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="40" name="address_line2" value="<?php echo $this->insurancecompany->address->line2;?>" 
               onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo xl("City,state zip");?> </td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="25" name="city" value="<?php echo $this->insurancecompany->address->city;?>" onKeyDown="PreventIt(event)" /> , 
        <input type="text" size="2" maxlength="2" name="state" value="<?php echo $this->insurancecompany->address->state;?>" 
               onKeyDown="PreventIt(event)" /> 
        <input type="text" size="5" name="zip" value="<?php $this->insurancecompany->address->zip;?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo xl("Phone");?> </td>
    <td VALIGN="MIDDLE" >
        <input TYPE="TEXT" NAME="phone" SIZE="12" VALUE="<?php echo$this->insurancecompany->get_phone();?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    
    <td VALIGN="MIDDLE" ><?php echo xl("CMS ID");?> </td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="15" name="cms_id" value="<?php echo $this->insurancecompany->get_cms_id();?>" onKeyDown="PreventIt(event)" />
    
        <?php if ($GLOBALS['support_encounter_claims']) {?>
                    &nbsp;&nbsp;For Encounter Claims:
                    <input type="text" size="15" name="alt_cms_id" value="<?php echo $this->insurancecompany->get_alt_cms_id();?>" onKeyDown="PreventIt(event)" />
        <?php }?>           
                    

    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE"><?php echo xl("Payer Type");?> </td>
    <td VALIGN="MIDDLE">
        <!--{html_options name="ins_type_code" options=$insurancecompany->ins_type_code_array 
        selected=$insurancecompany->get_ins_type_code()}-->
        <select name="ins_type_code">
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
    <td COLSPAN="1" ALIGN="LEFT" VALIGN="MIDDLE">X12 Receiver ID</td>
    <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE">
        <input type="text" name="x12_receiver_id" value="{$insurancecompany->get_x12_receiver_id()}" >
    </td>
</tr>-->
<tr>
    <td VALIGN="MIDDLE">Default X12 Partner</td>
    <td VALIGN="MIDDLE">
        <!--{html_options name="x12_default_partner_id" options=$x12_partners selected=$insurancecompany->get_x12_default_partner_id()}-->
        <select name="x12_default_partner_id">
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
<tr height="25"><td colspan=2>&nbsp;</td></tr>
<tr>
    <td colspan="2"><a href="javascript:submit_insurancecompany();" class="css_button"><span>Save</span></a>
        <a href="controller.php?practice_settings&insurance_company&action=list" class="css_button" onclick="top.restoreSession()">
            <span>Cancel</span></a></td>
</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->insurancecompany->id;?>" />
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
</form>