<?php /* Smarty version 2.6.29, created on 2017-05-17 15:43:25
         compiled from /home/azwreith/Documents/GitHub/LibreEHR/templates/pharmacies/general_edit.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', '/home/azwreith/Documents/GitHub/LibreEHR/templates/pharmacies/general_edit.html', 7, false),array('function', 'html_options', '/home/azwreith/Documents/GitHub/LibreEHR/templates/pharmacies/general_edit.html', 52, false),)), $this); ?>
<form name="pharmacy" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
">
<!-- it is important that the hidden form_id field be listed first, when it is called is populates any old information attached with the id, this allows for partial edits
        if it were called last, the settings from the form would be overwritten with the old information-->
<input type="hidden" name="form_id" value="<?php echo $this->_tpl_vars['pharmacy']->id; ?>
" />
<table style="font-size:9pt;" width="500px" CELLSPACING="0" CELLPADDING="3">
<tr>
    <td width="150px" VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="40" name="name" value="<?php echo $this->_tpl_vars['pharmacy']->name; ?>
" onKeyDown="PreventIt(event)" />(<?php echo smarty_function_xl(array('t' => 'Required'), $this);?>
)
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Address'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="40" name="address_line1" value="<?php echo $this->_tpl_vars['pharmacy']->address->line1; ?>
" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Address'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="40" name="address_line2" value="<?php echo $this->_tpl_vars['pharmacy']->address->line2; ?>
" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'City, State Zip'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <input type="text" size="25" name="city" value="<?php echo $this->_tpl_vars['pharmacy']->address->city; ?>
" onKeyDown="PreventIt(event)" /> , <input type="text" size="2" maxlength="2" name="state" value="<?php echo $this->_tpl_vars['pharmacy']->address->state; ?>
" onKeyDown="PreventIt(event)" /> <input type="text" size="5" name="zip" value="<?php echo $this->_tpl_vars['pharmacy']->address->zip; ?>
" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Email'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <input TYPE="TEXT" NAME="email" SIZE="35" VALUE="<?php echo $this->_tpl_vars['pharmacy']->email; ?>
" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Phone'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <input TYPE="TEXT" NAME="phone" SIZE="12" VALUE="<?php echo $this->_tpl_vars['pharmacy']->get_phone(); ?>
" onKeyDown="PreventIt(event)" />
    </td>
</tr>
<tr>
    <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Fax'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <input TYPE="TEXT" NAME="fax" SIZE="12" VALUE="<?php echo $this->_tpl_vars['pharmacy']->get_fax(); ?>
" onKeyDown="PreventIt(event)" />
    </td>
</tr>

<tr>
    <td VALIGN="MIDDLE" ><?php echo smarty_function_xl(array('t' => 'Default Method'), $this);?>
</td>
    <td VALIGN="MIDDLE" >
        <select name="transmit_method"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['pharmacy']->transmit_method_array,'selected' => $this->_tpl_vars['pharmacy']->transmit_method), $this);?>
</select>
    </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td colspan="2"><a href="javascript:submit_pharmacy();" class="css_button"><span><?php echo smarty_function_xl(array('t' => 'Save'), $this);?>
</span></a><a href="controller.php?practice_settings&pharmacy&action=list" class="css_button" onclick="top.restoreSession()">
<span><?php echo smarty_function_xl(array('t' => 'Cancel'), $this);?>
</span></a>
    </td>
</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['pharmacy']->id; ?>
" />
<input type="hidden" name="process" value="<?php echo $this->_tpl_vars['PROCESS']; ?>
" />
</form>

<?php echo '
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
'; ?>