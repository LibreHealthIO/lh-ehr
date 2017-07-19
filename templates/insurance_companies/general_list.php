<a href="controller.php?practice_settings&<?php echo $top_action;?>insurance_company&action=edit" 
   onclick="top.restoreSession()" class="css_button" >
<span><?php echo xl("Add a company");?> </span></a><br>
<br>
<table class="table table-hover ">
    <tr>
        <th><b><?php echo xl("Name");?> </b></th>
        <th><b><?php echo xl("City,State");?> </b></th>
        <th><b><?php echo xl("Default X12 Partner");?> </b></th>
    </tr>
    
    
    
   <?php if(is_array($this->icompanies)) {?>
    <?php foreach ($this->icompanies as $insurancecompany) { ?>
    <tr>
        <td><a href="<?php echo $this->current_action;?>action=edit&id=<?php echo $insurancecompany->id;?>" onsubmit="return top.restoreSession()">
                <?php echo $insurancecompany->name;?>&nbsp;</a>
        </td>
        <td>
            <td><?php echo $insurancecompany->address->city ;?>
                <?php echo $insurancecompany->address->state;?>
            </td>
        </td>
        <td>
            <td><?php echo $insurancecompany->get_x12_default_partner_name();?>&nbsp;</td>
        </td>
    </tr>
   <?php }   
    }
    else {
    ?>
    <tr class="center_display">
        <td colspan="3"><?php echo xl("No Insurance companies found.");?> </td>
    </tr>
</table>

    <?php } ?>