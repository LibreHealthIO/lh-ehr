<?php 
if($this->error) {
    echo $this->error;
    }
    else {
?>

<form name="provider" method="post" action="<?php echo $this->form_action;?>">
<!-- it is important that the hidden form_id field be listed first, when it is called it populates any old information attached with the id, this allows for partial edits
                if it were called last, the settings from the form would be overwritten with the old information-->
<input type="hidden" name="form_id" value="<?php echo $this->ins->id;?>" />

<table class="showborder" CELLSPACING="0" CELLPADDING="3">

<tr><td colspan="5" style="border-style:none;" class="bold">
   <?php echo $this->provider->get_name_display();?>
</td></tr>

<tr  class="showborder_head">
    <th class="small">Company Name</th>
    <th class="small">Provider Number</th>
    <th class="small">Rendering Provider Number</th>
    <th class="small">Group Number</th>
</tr>

<?php if(($this->provider->get_insurance_numbers())) {
    
foreach ($this->provider->get_insurance_numbers() as $numset) { ?>
<tr>
    <td valign="middle">
        <a href="<?php echo $this->current_action; ?>action=edit&id=<?php echo $numset->get_id(); ?>&showform=true" 
           onclick="top.restoreSession()">
            <?php echo $numset->get_insurance_company_name();?>&nbsp;</a>
    </td>
    <td><?php echo $numset->get_provider_number();?>&nbsp;</td>
    <td><?php echo $numset->get_rendering_provider_number();?>&nbsp;</td>
    <td><?php echo $numset->get_group_number();?>&nbsp;</td>
</tr>
<?php } } 
else { ?>
<tr>
   <td colspan="5">No entries found, use the form below to add an entry.</td>
</tr>
<?php } ?>


<tr> <td style="border-style:none;" colspan="5">
    <a href="<?php echo $this->current_action;?>action=edit&id=&provider_id=<?php echo $this->provider->get_id();?>&showform=true"
       class="css_button_small" style='margin-top:2px'
       onclick="top.restoreSession()">
            <span>Add New</span>
    </a>
</td> </tr>

<?php echo $_GET['showform'];?>
<?php if($_GET['showform'] == 'true') 
{
?>
<tr> <td style="border-style:none;" colspan="5">
<br>
<b><span>
        <?php if ($this->ins->get_id() == "") { echo "Add Provider Number"; }
            else { echo "Update Provider Number"; } 
            ?>
        </span></b><br>
        <table width="100%">
        <tr>
            <td style="border-style:none;" width="200px" VALIGN="MIDDLE" >Insurance Company</td>
            <td style="border-style:none;" VALIGN="MIDDLE" >
                    <!--
                    {if $ins->get_id() eq ""}
                            {html_options name="insurance_company_id" options=$ic_array values=$ic_array selected=$ins->get_insurance_company_id()}
                    {else}
                     {$ins->get_insurance_company_name()}
                    {/if}
                    -->
                    <?php if($this->ins->get_id() =="") { ?>
                    <select name="insurance_company_id">
                        <?php foreach ($this->ic_array as $key => $value) 
                        { 
                            if($key==$this->ins->get_insurance_company_id() ) { ?>
                            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
                            <?php } else { ?>
                            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
                            <?php }                
                       } 
                        ?> 
                    </select>
                    <?php  } else  { echo $this->ins->get_insurance_company_name();} ?>                     
        </td>     
    </tr>

<tr>
        <td style="border-style:none;" VALIGN="MIDDLE" >Provider Number</td>
        <td style="border-style:none;" VALIGN="MIDDLE" >
           <input type="text" size="20" name="provider_number" value="<?php $this->ins->get_provider_number();?>" onKeyDown="PreventIt(event)" />
        </td>
</tr>
<tr>
        <td style="border-style:none;" VALIGN="MIDDLE" >Provider Number Type</td>
        <td style="border-style:none;" VALIGN="MIDDLE" >
           <!--{html_options name="provider_number_type" options=$ic_type_options_array 
           values=$ins->provider_number_type_array 
           selected=$ins->get_provider_number_type()}-->
           
           <select name="provider_number_type">
                        <?php foreach ($this->ic_type_options_array  as $key => $value) 
                        { 
                            if($key==$this->ins->get_provider_number_type() ) { ?>
                            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
                            <?php } else { ?>
                            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
                            <?php }                
                       } 
                        ?> 
           </select>          
           
        </td>
</tr>
<tr>
        <td style="border-style:none;" VALIGN="MIDDLE" >Rendering Provider Number</td>
        <td style="border-style:none;" VALIGN="MIDDLE" >
                <input type="text" size="20" name="rendering_provider_number" value="<?php echo $this->ins->get_rendering_provider_number();?>" 
                       onKeyDown="PreventIt(event)" />
        </td>
</tr>
<tr>
        <td style="border-style:none;" VALIGN="MIDDLE" >Rendering Provider Number Type</td>
        <td style="border-style:none;" VALIGN="MIDDLE" >
           <!-- {html_options name="rendering_provider_number_type" options=$ic_rendering_type_options_array 
            values=$ins->rendering_provider_number_type_array 
            selected=$ins->get_rendering_provider_number_type()}
           -->
           <select name="rendering_provider_number_type">
               <?php foreach ($this->ic_rendering_type_options_array   as $key => $value) 
                        { 
                            if($key==$this->ins->get_rendering_provider_number_type() ) { ?>
                            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
                            <?php } else { ?>
                            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
                            <?php }                
                       } 
                        ?> 
           </select>
        </td>
</tr>
<tr>
        <td style="border-style:none;" VALIGN="MIDDLE" >Group Number</td>
        <td style="border-style:none;" VALIGN="MIDDLE" >
                <input type="text" size="20" name="group_number" value="<?php echo $this->ins->get_group_number(); ?>" 
                       onKeyDown="PreventIt(event)" />
        </td>
</tr>
<tr><td style="border-style:none;"></td></tr>
<tr>
        <td style="border-style:none;" colspan="2">                
                <?php if($this->ins->get_id() == "" ) {?>
                    <a href="javascript:submit_insurancenumbers_add();" class="css_button"><span>Save</span></a>
                <?php } else { ?>
                    <a href="javascript:submit_insurancenumbers_update();" class="css_button"><span>Save</span></a>
                <?php } ?>
                <a href="controller.php?practice_settings&insurance_numbers&action=list" class="css_button" onclick="top.restoreSession()">
                    
                    <span><?php echo xl("Cancel");?></span></a>
</td> </tr>
<?php } else {  ?>

<input type="hidden" name="provider_number" value="<?php echo $this->ins->get_provider_number();?>" />
<input type="hidden" name="provider_number_type" value="<?php echo $this->ins->get_provider_number_type();?>" />
<input type="hidden" name="rendering_provider_number" value="<?php echo $this->ins->get_rendering_provider_number();?>" />
<input type="hidden" name="rendering_provider_number_type" value="<?php echo $this->ins->get_rendering_provider_number_type();?>" />
<input type="hidden" name="group_number" value="<?php echo $this->ins->get_group_number();?>" />

<?php } ?>

    </table>
</td></tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->ins->id; ?>" />
<input type="hidden" name="provider_id" value="<?php echo $this->ins->get_provider_id();?>" />
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
</form>
    <?php } ?>

<script language="javascript">
function submit_insurancenumbers_update() {
    top.restoreSession();
    document.provider.submit();
}
function submit_insurancenumbers_add() {
    top.restoreSession();
    document.provider.submit();
    //Z&H Removed redirection
}

function Waittoredirect(delaymsec) {
 var st = new Date();
 var et = null;
 do {
 et = new Date();
 } while ((et - st) < delaymsec);

 }
</script>
<style type="text/css">
text,select {font-size:9pt;}
</style>

