
<table>
    <?php
        foreach($this->prescriptions as $prescription)
        {
            if($prescription->get_active() > 0)
            {?>
                <tr>
                    <td><?php echo $prescription->drug;?></td>
                    <td><?php echo $prescription->get_dosage_display();?></td>
                </tr>
            <?php 
            }
        }?>         
    ?>
</table>
