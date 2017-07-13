<table cellpadding="1" cellspacing="0" class="showborder">
        <tr class="showborder_head">
                <th width="130px"><?php echo xl("Name");?></th>
                <th width="80px">&nbsp;</th>
                <th width="100px"><?php echo xl("Provider");?> #</th>
                <th width="100px"><?php echo xl("Rendering");?>  #</th>
                <th width="100px"><?php echo xl("Group");?>  #</th>
        </tr>
        
        <?php if(is_array($this->providers)) { 
           foreach ($this->providers as $value) {?>
            <tr height="22">
                <td><a href="<?php echo $this->current_action; ?>action=edit&id=default&provider_id=<?php echo $value->id;?>">
                    <?php echo $value->get_name_display();?></a>
                </td>
                <td>
                    Default&nbsp;
                </td>
                <td><?php echo $value->get_provider_number_default();?>&nbsp;</td>
                <td><?php echo $value->get_rendering_provider_number_default();?>&nbsp;</td>
                <td><?php echo $value->get_group_number_default();?>&nbsp;</td>                    
            </tr>
        <?php } }
        else { ?>
            <tr class="center_display">
                <td colspan="5"><?php echo xl("No Providers Found");?> </td>
            </tr>
        <?php }?>
    </table>

 
 
        
        <!--
        {foreach from=$providers item=provider}
        <tr height="22">
                <td><a href="{$CURRENT_ACTION}action=edit&id=default&provider_id={$provider->id}">{$provider->get_name_display()}</a></td>
                <td>{xl t='Default'}&nbsp;</td>
                <td>{$provider->get_provider_number_default()}&nbsp;</td>
                <td>{$provider->get_rendering_provider_number_default()}&nbsp;</td>
                <td>{$provider->get_group_number_default()}&nbsp;</td>
        </tr>
        {foreachelse}
        <tr class="center_display">
                <td colspan="5">{xl t='No Providers Found'}</td>
        </tr>
        {/foreach}
</table>
        -->

