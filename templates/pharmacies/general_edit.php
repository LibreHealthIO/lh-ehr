<form class="form-horizontal" name="pharmacy" method="post" action="<?php echo $this->form_action;?>">
<!-- it is important that the hidden form_id field be listed first, when it is called is populates any old information attached with the id, this allows for partial edits
        if it were called last, the settings from the form would be overwritten with the old information-->

<input type="hidden" name="form_id" value="<?php echo $this->pharmacy->id;?>" />
<table class="table table-hover">
<tr>
    <td><?php echo xl("Name");?> </td>
    <td>
        <input type="text" class="form-control" size="40" name="name" value="<?php echo $this->pharmacy->name;?>" onKeyDown="PreventIt(event)" />(Required)
    </td>
</tr>
<tr>
    <td><?php echo xl("Address(line1)");?></td>
    <td>
        <input type="text" class="form-control" size="40" name="address_line1" value="<?php echo $this->pharmacy->address->line1;?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td><?php echo xl("Address(line2)");?></td>
    <td >
        <input type="text" class="form-control" size="40" name="address_line2" value="<?php echo $this->pharmacy->address->line2;?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td><?php echo xl("City ,state, zip");?></td>
    <td class="form-group form-inline">        
        <input type="text" class="form-control " size="25" name="city" value="<?php echo $this->pharmacy->address->city;?>" onKeyDown="PreventIt(event)" /> , 
        <input type="text" class="form-control " size="2" maxlength="2" name="state" value="<?php echo $this->pharmacy->address->state;?>" onKeyDown="PreventIt(event)" /> ,
        <input type="text" class="form-control " size="5" name="zip" value="<?php $this->pharmacy->address->zip;?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td  ><?php echo xl("Email");?></td>
    <td >
        <input type="text" class="form-control" NAME="email" SIZE="35" VALUE="<?php echo $this->pharmacy->email;?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td><?php echo xl("Phone");?></td>
    <td>
        <input type="text" class="form-control" NAME="phone" SIZE="12" VALUE="<?php echo $this->pharmacy->get_phone();?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td><?php echo xl("Fax");?></td>
    <td>
        <input type="text" class="form-control" NAME="fax" SIZE="12" VALUE="<?php echo $this->pharmacy->get_fax();?>" onKeyDown="PreventIt(event)" />
    </td>
</tr>

<tr>
    <td><?php echo xl("Default Method");?></td>
    <td>
        <select class="form-control" name="transmit_method">
            <!--{html_options    options=$pharmacy->transmit_method_array  selected=$pharmacy->transmit_method}-->
            <?php foreach ($this->pharmacy->transmit_method_array as $key => $value) 
                { 
                if($key==$this->pharmacy->transmit_method) { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
                <?php } else { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
                <?php }                
                } ?>            
        </select>
    </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td colspan="2"><a href="javascript:submit_pharmacy();" class="css_button"><span><?php echo xl("Save");?></span></a>
        <a href="controller.php?practice_settings&pharmacy&action=list" class="css_button" onclick="top.restoreSession()">
                    <span><?php echo xl("Cancel");?></span></a>
    </td>
</tr>
</table>

<input type="hidden" name="id" value="<?php $this->pharmacy->id;?>" />
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
</form>


<script language="javascript">
function submit_pharmacy()
{
    if(document.pharmacy.name.value.length>0)
    {
        top.restoreSession();
        document.pharmacy.submit();
        //Z&H Removed redirection
    }
    else
    {
        document.pharmacy.name.style.backgroundColor="red";
        document.pharmacy.name.focus();
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