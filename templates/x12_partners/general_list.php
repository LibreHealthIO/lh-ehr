<a href="<?php echo $this->current_action;?>action=edit&id=default" onclick="top.restoreSession()" class="css_button" >
<span>Add New Partner</span></a><br><br>
<table class="table table-hover">
    <tr>
        <th><?php echo xl("Name");?></th>
        <th><?php echo xl("Sender ID");?></th>
        <th><?php echo xl("Receiver ID");?></th>
        <th><?php echo xl("Version");?></th>
    </tr>
    
    <?php if(is_array($this->partners)) {?>
        <?php foreach ($this->partners as $partner) {?>
            <tr height="22">
                <td><a href="<?php echo $this->current_action;?>action=edit&x12_partner_id=<?php echo $partner->id;?>" onclick="top.restoreSession()">
                        <?php echo $partner->get_name();?>&nbsp;</a></td>
                <td><?php echo $partner->get_x12_sender_id();?>&nbsp;</td>
                <td><?php echo $partner->get_x12_receiver_id();?>&nbsp;</td>
                <td><?php echo $partner->get_x12_version();?>&nbsp;</td>
            </tr>
        <?php }?>
      <?php }  else { ?>
            <tr height="25" class="center_display">
                <td colspan="4"><?php echo xl("No Partners Found ");?></td>
            </tr>
      <?php }?>
</table>
         