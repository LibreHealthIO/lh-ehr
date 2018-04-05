<?php
  require_once($GLOBALS['fileroot'] . "/library/headers.inc.php");
?>
<html>
  <head>
    <!-- {math equation="number * constant" number=$this->vitals->get_weight() constant=0.45359237 format="%.2f"}{/if}" -->
    <?php 
      html_header_show();       
      // Include Bootstrap and datetimepicker
      call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker"));
    ?>
    <script type="text/javascript">
      var mypcc = '<?php echo $GLOBALS['phone_country_code']; ?>';
      // Only load jquery if not already closing. This page serves two purposes now,
      //  for entring of vitals and for graphing/trending previous vitals by being embedded
      //  in the interface/patient_file/encounter/trend_form.php page.
      if (typeof jQuery === 'undefined') {
        document.write("<script type='text/javascript' src='<?php echo $GLOBALS['webroot']; ?>/library/js/jquery.js'><\/script>");
      }
      function vitalsFormSubmitted() {
        var invalid = "";
        var elementsToValidate = new Array();
        elementsToValidate[0] = new Array();
        elementsToValidate[0][0] = 'weight_input';
        elementsToValidate[0][1] = 'Weight' + ' (' + "lbs" + ')';
        elementsToValidate[1] = new Array();
        elementsToValidate[1][0] = 'weight_input_metric';
        elementsToValidate[1][1] = 'Weight' + ' (' + 'kg' + ')';
        elementsToValidate[2] = new Array();
        elementsToValidate[2][0] = 'height_input';
        elementsToValidate[2][1] = 'Height/Length' + ' (' + 'in' + ')';
        elementsToValidate[3] = new Array();
        elementsToValidate[3][0] = 'height_input_metric';
        elementsToValidate[3][1] = 'Height/Length' + ' (' + 'cm' + ')';
        elementsToValidate[4] = new Array();
        elementsToValidate[4][0] = 'bps_input';
        elementsToValidate[4][1] = 'BP Systolic';
        elementsToValidate[5] = new Array();
        elementsToValidate[5][0] = 'bpd_input';
        elementsToValidate[5][1] = 'BP Diastolic';
        for (var i = 0; i < elementsToValidate.length; i++) {
          var current_elem_id = elementsToValidate[i][0];
          var tag_name = elementsToValidate[i][1];
          document.getElementById(current_elem_id).classList.remove('error');
          if (isNaN(document.getElementById(current_elem_id).value)) {
            invalid += "The following field has an invalid value" + ": " + tag_name + "\n";
            document.getElementById(current_elem_id).className = document.getElementById(current_elem_id).className + " error";
            document.getElementById(current_elem_id).focus();
          }
        }
        if (invalid.length > 0) {
          invalid += "\n" + "Please correct the value(s) before proceeding!";
          alert(invalid);
          return false;
        }
        else {
          return top.restoreSession();
        }
      }
    </script>
    <style type="text/css" title="mystyles" media="all">
     
     .title {
        font-size: 120%;
        font-weight: bold;
      }
      .currentvalues {
        border-right: 1px solid black;
        padding-right:5px;
        text-align: left;
      }
      .valuesunfocus {
        border-right: 1px solid black;
        padding-right:5px;
        background-color: #ccc;
        text-align: left;
      }
      .unfocus {
        background-color: #ccc;
      }
      .historicalvalues {
        background-color: #ccc;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd;
        text-align: right;
      }      
      .hide {
        display:none;
      }
      .readonly {
        display:none;
      }
      .error {
        border:2px solid red;
      }
      
    </style>
  </head>
  <body >
    <p>
    <table class="table well">
      <tr>
        <td>
          <span class="title">
            <?php echo xlt("Vitals"); ?>
          </span>
        </td>
        <td>&nbsp;&nbsp;&nbsp;
          <a href="../summary/demographics.php"  class="readonly css_button_small" onclick="top.restoreSession()"> 
            <span>
              <?php echo xlt("View Patient"); ?>
            </span>
          </a>
        </td>
      </tr>
    </table>
    </p>
  <form name="vitals" method="post" action="<?php echo $this->form_action; ?>/interface/forms/vitals/save.php" 
        onSubmit="return vitalsFormSubmitted()">
    <div id="chart">
    </div>
    <table class="table">
      <tr>
        <th>
          <?php echo xlt("Name"); ?>
        </th>
        <th><?php echo xlt("Unit");?>
        </th>
        <th class='currentvalues' title='Date and time of this observation'>
          <input class='form-control' style='width: auto;' type='text' size='14' name='date' id='date' value='<?php 
                                                                    $dateGet = date_create($this->vitals->get_date());                    
                                                                    echo date_format($dateGet, "Y-m-d H:i");?>'/>                      
        </th>               
        <?php if(is_array($results)){ foreach ($this->results as $result) { ?>
        <th class='historicalvalues'>
          <?php echo date_format(date_create($result['date']),"Y-m-d H:i");?>
        </th>
        <?php }} ?>
      </tr>
      <?php if ($this->units_of_measurement == 4) { ?>
      <tr class="hide">
        <?php } else {  ?>
      <tr>
        <?php } ?>
        <?php if ($this->units_of_measurement == 2) { ?>
        <td class="unfocus graph" id="weight">
          <?php } else { ?> 
        <td class="graph" id="weight">
          <?php } echo xlt("Weight"); ?>
          <?php if ($this->units_of_measurement == 2) { ?>
        <td class="unfocus">
          <?php } else { ?>
        <td>
          <?php } echo xlt("lbs"); ?>   
          <?php if ($this->units_of_measurement == 2) { ?>
        <td class="valuesunfocus">
          <?php } else { ?>
        <td class='currentvalues'>
          <?php } ?>     
          <input type="text" class="form-control" size='5' name='weight' id='weight_input' 
                 value="<?php if ($this->vitals->get_weight() != 0) { echo $this->vitals->get_weight();} ?>"  onchange="convLbtoKg('weight_input');" 
                 title='<?php echo xlt("Decimal pounds or pounds and ounces separated by #(e.g. 5#4)"); ?>'/>
        </td>    
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td  class='historicalvalues'>
          <?php echo $this->vitals->display_weight($result['weight']); ?>
        </td>
        <?php }} ?>
        <?php if ($this->units_of_measurement == 3) { ?>
      <tr class="hide">
        <?php } else { ?>
      <tr>
        <?php } ?>
        <?php if ($this->units_of_measurement == 1) { ?>
        <td class="unfocus graph" id="weight_metric">
          <?php }else { ?>
        <td class="graph" id="weight_metric">
          <?php } echo xlt("Weight") ?>
        </td> 
        <?php if ($this->units_of_measurement == 1) { ?>
        <td class="unfocus">
          <?php } else { ?>
        <td>
          <?php } echo xlt("kg"); ?>
        </td>
        <?php if ($this->units_of_measurement == 1) { ?>
        <td class="valuesunfocus">
          <?php } else { ?>
        <td class='currentvalues'>
          <?php } ?>
          <input type="text" class="form-control" size='5' id='weight_input_metric' value="<?php if ($this->vitals->get_weight() != 0) {
                                                                      $final_result =  $this->vitals->get_weight()*0.45359237 ;
                                                                      echo sprintf("%.2f",$final_result); } ?>"                          
                 onChange="convKgtoLb('weight_input');"/>
        </td>
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td  class='historicalvalues'>
          <?php if($result['weight'] != 0){ 
                $final_result =  $result['weight']*0.45359237;
                echo sprintf("%.2f",$final_result);
                }?>
        </td>
        <?php }} ?>
      </tr>
      <!--     Math functions above...    -->
      <?php if ($this->units_of_measurement == 4) { ?>
      <tr class="hide">
        <?php } else{  ?>
      <tr><?php } ?>
        <?php if ($this->units_of_measurement == 2) { ?>
        <td class="unfocus graph" id="height">
          <?php } else { ?>
        <td class="graph" id="height">
          <?php } echo xlt("Height/Length"); ?>
        </td>
        <?php if ($this->units_of_measurement == 2) { ?>
        <td class="unfocus">
          <?php } else { ?>
        <td>
          <?php } echo "in"; ?>
        </td>
        <?php if ($this->units_of_measurement == 2) { ?>
        <td class="valuesunfocus">
          <?php } else { ?>
        <td class='currentvalues'>
          <?php } ?>
          <input type="text" class="form-control" size='5' name='height' id='height_input' 
                 value="<?php if ($this->vitals->get_height() != 0) { ?><?php echo $this->vitals->get_height();
                        } ?>" onChange="convIntoCm('height_input');"/>
        </td>
        <?php if(is_array($results)){ foreach ($this->results as $result) { ?>
        <td class='historicalvalues'>
          <?php if ($result['height'] != 0) {echo $result['height'];} ?>
        </td>
        <?php }} ?>   
        <?php if ($this->units_of_measurement == 3) { ?>
      <tr class="hide">
        <?php } else { ?>
        <tr><?php }?>
        <?php if ($this->units_of_measurement == 1) { ?>
        <td class="unfocus graph" id="height_metric">
          <?php } else { ?>
        <td class="graph" id="height_metric">
          <?php } echo xlt("Height/Length"); ?>
        </td>
        <?php if ($this->units_of_measurement == 1) { ?>
        <td class="unfocus">
          <?php } else { ?>
        <td>
          <?php }echo xlt("cm"); ?>
        </td>
        <?php if ($this->units_of_measurement == 1) { ?>
        <td class="valuesunfocus">
          <?php } else { ?>
        <td class='currentvalues'>
          <?php } ?>
          <input type="text" class="form-control" size='5' id='height_input_metric' 
                 value="<?php if($this->vitals->get_height() != 0) {
                        $final_result = $this->vitals->get_height()*2.54;
                        echo sprintf('%.2f',$final_result);}?>" 
                 onChange="convCmtoIn('height_input');"/>
        </td>
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td class='historicalvalues'>
          <?php if($result['height'] != 0){
            $final_result = $result['height'] * 2.54;
            echo sprintf("%.2f",$final_result);
            }?>
        </td>
        <?php }} ?>
      </tr>
      <!--    MATH FUNCTIONS ABOVE......      -->
      <tr>
        <td class="graph" id="bps">BP Systolic
        </td>
        <td>mmHg
        </td>
        <td class='currentvalues'>
          <input type="text" class="form-control" size='5'
                 name='bps' id='bps_input' value="<?php echo $this->vitals->get_bps(); ?>"/>
        </td>
        <?php if(is_array($results)){ foreach ($this->results as $result) { ?>
        <td class='historicalvalues'>
          <?php echo $result['bps']; ?>
        </td>
        <?php }} ?>
      </tr>
      <tr>
        <td class="graph" id="bpd">BP Diastolic
        </td>
        <td>mmHg
        </td>
        <td class='currentvalues'>
          <input type="text" class="form-control" size='5'
                 name='bpd' id='bpd_input' value="<?php echo $this->vitals->get_bpd(); ?>"/>
        </td>
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td  class='historicalvalues'>
          <?php echo $result['bpd']; ?>
        </td>
        <?php }} ?>
      </tr>
      <tr>
        <td class="graph" id="pulse">Pulse
        </td>
        <td>per min
        </td>
        <td class='currentvalues'>
          <input type="text" class="form-control" size='5' name='pulse' id='pulse_input' 
                 value="<?php if($this->vitals->get_pulse() != 0){
                        echo sprintf('%.0f',$this->vitals->get_pulse());}?>"/>
        </td>
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td class='historicalvalues'>
          <?php if($result['pulse'] != 0){ 
            echo sprintf("%.0f",$result['pulse']);
            }?>
        </td>
        <?php } }?>
      </tr>
      <tr>
        <td class="graph" id="respiration">
          <?php echo xlt("Respiration");?>
        </td>
        <td>per min
        </td>
        <td class='currentvalues'>
          <input type="text" class="form-control" size='5'
                 name='respiration' id='respiration_input' 
                 value="<?php if($this->vitals->get_respiration() != 0){
                        echo sprintf('%.0f',$this->vitals->get_respiration());}?>"/>
        </td>
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td class='historicalvalues'>
          <?php if($result['respiration'] != 0){
              echo sprintf("%.0f",$result["respiration"]);
              }?>
        </td>
        <?php }} ?>
      </tr>
      <?php if ($this->units_of_measurement == 4) { ?>
      <tr class="hide">
        <?php } else { ?>
      <tr>
        <?php } ?>
        <?php if ($this->units_of_measurement == 2) { ?>
        <td class="unfocus graph" id="temperature">
          <?php } else { ?>
        <td class="graph" id="temperature">
          <?php } echo xlt("Temperature"); ?>
        </td>
        <?php if ($this->units_of_measurement == 2) { ?>
        <td class="unfocus">
          <?php } else { ?>
        <td>
          <?php } echo "F"; ?>
        </td>
        <?php if ($this->units_of_measurement == 2) { ?>
        <td class="valuesunfocus">
          <?php } else { ?>
        <td class='currentvalues'>
          <?php } ?>
          <input type="text" class="form-control" size='5' name='temperature' id='temperature_input' 
                 value="<?php if ($this->vitals->get_temperature() != 0) {
                        echo $this->vitals->get_temperature();
                        } ?>" onChange="convFtoC('temperature_input');"/>
        </td>
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td class='historicalvalues'>
          <?php if ($result['temperature'] != 0) {
            echo $result['temperature'];
            } ?>
        </td>
        <?php }} ?>
        <?php if($this->units_of_measurement == 3){?>
      <tr class="hide">
        <?php } else { ?>
      <tr>
        <?php }?>
        <?php if($this->units_of_measurement == 1){?>
        <td class="unfocus graph" id="temperature_metric">
          <?php } else {?>
        <td class="graph" id="temperature_metric">
          <?php  } echo xlt("Temperature");?>
        </td>
        <?php if($this->units_of_measurement == 1){?>
        <td class="unfocus">
          <?php } else { ?>
        <td>
          <?php }echo "C";?>
        </td>
        <?php if($this->units_of_measurement == 1){?>
        <td class="valuesunfocus">
          <?php }else {?>
        <td class='currentvalues'>
          <?php }?>
          <input type="text" class="form-control" size='5' id='temperature_input_metric' 
                 value="<?php if($this->vitals->get_temperature() != 0) {
                        $final_result = ($this->vitals->get_temperature()-32)*0.5556;
                        echo sprintf('%.2f',$final_result);
                                           }?> "
                 onChange="convCtoF('temperature_input');"/>
        </td>
        <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
        <td class='historicalvalues'>
          <?php if($result['temperature'] != 0){
            $final_result  =  ($result['temperature'] -32)*0.5556;
            echo sprintf("%.2f",$final_result);
            }?>
        </td>
        <?php }} ?>
      </tr>
      <tr>
        <td>Temp Location
        <td>
        </td>
      </td>
    <td class='currentvalues'>
      <select class="form-control" name="temp_method" id='temp_method'/>
      <option value=""> 
      </option>  
      <option value="Oral"  >  
        <?php if ($this->vitals->get_temp_method() == "Oral" || $this->vitals->get_temp_method() == 2) { ?> selected
        <?php } echo xlt("Oral"); ?>
      </option>
      <option value="Tympanic Membrane">
        <?php if ($this->vitals->get_temp_method() == "Tympanic Membrane" || $this->vitals->get_temp_method() == 1) { ?> selected
        <?php } echo xlt("Tympanic Membrane"); ?>
      </option>
      <option value="Rectal" >     
        <?php if ($this->vitals->get_temp_method() == "Rectal" || $this->vitals->get_temp_method() == 3) { ?> selected
        <?php } echo xlt("Rectal"); ?>
      </option>
      <option value="Axillary">
        <?php if ($this->vitals->get_temp_method() == "Axillary" || $this->vitals->get_temp_method() == 4) { ?> selected
        <?php } echo xlt("Axillary"); ?>
      </option>
      <option value="Temporal Artery">
        <?php if ($this->vitals->get_temp_method() == "Temporal Artery") { ?> selected
        <?php } echo xlt("Temporal Artery"); ?>
      </option>
    </select>
  </td>
  <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
  <td class='historicalvalues'>
    <?php if ($result . temp_method) {
  echo xlt($result['temp_method']);
  } ?>
  </td>
  <?php }} ?>
  <tr>
  <td class="graph" id="oxygen_saturation">
    <?php echo xlt("Oxygen Saturation");?>
  </td>
  <td>%
  </td>
  <td class='currentvalues'>
    <input type="text" class="form-control" size='5'
           name='oxygen_saturation' id='oxygen_saturation_input' 
           value="<?php if($this->vitals->get_oxygen_saturation() != 0){
                  echo sprintf('%.0f',$this->vitals->get_oxygen_saturation());}?>"/>
  </td>
  <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
  <td  class='historicalvalues'>
    <?php if($result.oxygen_saturation != 0){
      echo sprintf("%.0f",$result['oxygen_saturation']);
      }?>
  </td>
  <?php }} ?>
</tr>
<?php if ($this->units_of_measurement == 4 || $gbl_vitals_options > 0) { ?>
<tr class="hide">
  <?php } else{  ?>
  <tr><?php }?>
  <?php if ($this->units_of_measurement == 2) { ?>
  <td class="unfocus graph" id="head_circ">
    <?php } else { ?>
  <td class="graph" id="head_circ">  
    <?php } echo xlt("Head Circumference"); ?>
  </td>
  <?php if ($this->units_of_measurement == 2) { ?>
  <td class="unfocus">
    <?php } else { ?>
  <td>
    <?php } echo "in"; ?>
  </td>
  <?php if ($this->units_of_measurement == 2) { ?>
  <td class="valuesunfocus">
    <?php } else { ?>
    <td class='currentvalues'><?php }?>
    <input type="text" class="form-control" size='5' name='head_circ' id='head_circ_input' value="<?php if ($this->vitals->get_head_circ() != 0) {
                                                                             echo $this->vitals->get_head_circ();
                                                                             } ?>" onChange="convIntoCm('head_circ_input');"/>
  </td>
  <?php if(is_array($results)){ foreach ($this->results as $result) { ?>
  <td class='historicalvalues'>
    <?php if ($result['head_circ'] != 0) {
echo $result['head_circ'];
} ?>
  </td>
  <?php }} ?>
  <?php if($this->units_of_measurement == 3 || $gbl_vitals_options > 0){?>
<tr class="hide">
  <?php }else{ ?>
  <tr><?php }?>
  <?php if($this->units_of_measurement == 1){?>
  <td class="unfocus graph" id="head_circ_metric">
    <?php }else {?>
  <td class="graph" id="head_circ_metric">
    <?php } echo xlt("Head Circumference");?>
  </td>
  <?php if($this->units_of_measurement == 1){?>
  <td class="unfocus">
    <?php } else{?>
  <td>
    <?php } echo "cm"?>
  </td>
  <?php if($this->units_of_measurement == 1){?>
  <td class="valuesunfocus">
    <?php } else {?>
  <td class='currentvalues'>
    <?php }?>
    <input type="text" class="form-control" size='5' id='head_circ_input_metric' 
           value="<?php if($this->vitals->get_head_circ() != 0){
                  $final_value = $this->vitals->get_head_circ()*2.54;
                  echo sprintf('%.2f',$final_result);}?>" 
           onChange="convCmtoIn('head_circ_input');"/>
  </td>
  <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
  <td class='historicalvalues'>
    <?php if($result['head_circ'] != 0){
$final_value = $result['head_circ']*2.54;
echo sprintf("%.2f",$final_result);
}?>
  </td>
  <?php }} ?>
</tr>
<?php if ($this->units_of_measurement == 4 || $gbl_vitals_options > 0) { ?>
<tr class="hide">
  <?php } else { ?>
  <tr><?php }?>
  <?php if ($this->units_of_measurement == 2) { ?>
  <td class="unfocus graph" id="waist_circ">
    <?php } else { ?>
  <td class="graph" id="waist_circ">
    <?php }echo xlt("Waist Circumference"); ?>
  </td>
  <?php if ($this->units_of_measurement == 2) { ?>
  <td class="unfocus">
    <?php } else { ?>
  <td>
    <?php }echo "in"; ?>
  </td>
  <?php if ($this->units_of_measurement == 2) { ?>
  <td class="valuesunfocus">
    <?php } else { ?>
    <td class='currentvalues'><?php }?>
    <input type="text" class="form-control" size='5' name='waist_circ' id='waist_circ_input' 
           value="<?php if ($this->vitals->get_waist_circ() != 0) {
                  echo $this->vitals->get_waist_circ();
                  } ?>" onChange="convIntoCm('waist_circ_input');"/>
  </td>
  <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
  <td class='historicalvalues'>
    <?php if ($result['waist_circ'] != 0) {
echo $result['waist_circ'];
} ?>
  </td>
  <?php }} ?>
</tr>
<?php if($this->units_of_measurement == 3 || $gbl_vitals_options > 0){?>
<tr class="hide">
  <?php } else {?>
  <tr><?php }?>
  <?php if($this->units_of_measurement == 1){  ?>
  <td class="unfocus graph" id="waist_circ_metric">
    <?php } else {?>
  <td class="graph" id="waist_circ_metric">
    <?php } echo xlt("Waist Circumference");?>
  </td>
  <?php if($this->units_of_measurement == 1){?>
  <td class="unfocus">
    <?php } else { ?>
  <td>
    <?php } echo "cm";?>
  </td>
  <?php if($this->units_of_measurement == 1){?>
  <td class="valuesunfocus">
    <?php } else {?>
  <td class='currentvalues'>
    <?php }?>
    <input type="text" class="form-control" size='5' id='waist_circ_input_metric' 
           value="<?php if($this->vitals->get_waist_circ() != 0){
                  $final_value = $this->vitals->get_waist_circ()*2.54;
                  echo sprintf("%.2f",$final_value);                                       
                                     }?>" onChange="convCmtoIn('waist_circ_input');"/>
  </td>
  <?php if(is_array($results)){  foreach ($this->results as $result) { ?>
  <td class='historicalvalues'>
    <?php if($result[ 'waist_circ' ]!= 0){
$final_value = $result[ 'waist_circ' ]*2.54;
echo sprintf("%.2f",$final_value);      
}?>
  </td>
  <?php }} ?>
</tr>
<tr>
  <td class="graph" id="BMI">
    <?php echo xlt("BMI");?>
  </td>
  <td>kg/m^2
  </td>
  <td class='currentvalues'>
    <input type="text" class="form-control" size='5'  name='BMI' id='BMI_input' 
           value="<?php if($this->vitals->get_BMI() != 0){
                  echo substr($this->vitals->get_BMI(), 0, 5);
                  }?>"/>
  </td>
  <?php if(is_array($results)){ foreach ($this->results as $result) { ?>
  <td class='historicalvalues'>
    <?php if($result['BMI'] != 0){
echo substr($result['BMI'], 0, 5);
}?>
  </td>
  <?php }} ?>
</tr>
<tr>
  <td>
    <?php echo xlt("BMI Status");?>
  </td>
  <td>Type
  </td>
  <td class='currentvalues'>
    <input type="text" class="form-control" size='15' name="BMI_status" id="BMI_status" 
           value="<?php echo $this->vitals->get_BMI_status();?>"/>
  </td>
  <?php if(is_array($results)){ foreach ($this->results as $result) { ?>
  <td  class='historicalvalues'>
    <?php if($result['BMI_status']){
echo $result['BMI_status'];
}
?>
  </td>
  <?php }} ?>
</tr>
<tr>
  <td>Other Notes
  <td>
  </td>
</td>
<td class='currentvalues'>
  <input type="text" class="form-control" size='20'
         name="note" id='note' value="<?php echo $this->vitals->get_note();?>" />
</td>
<?php if(is_array($results)){ foreach ($this->results as $result) { ?>
<td class='historicalvalues'>
  <?php echo $result['note'];?>
</td>
<?php }} ?>
</tr>
<tr>
  <td colspan='3' style='text-align:center'>
    <?php if($this->patient_age <= 20 || (preg_match('/month/', $this->patient_age))){?>
    <!-- only show growth-chart button for patients < 20 years old -->
    <!-- <input type="button" id="growthchart" value="{xl t="Growth-Chart"}" style='margin-left: 20px;'> -->
    <input type="button" id="pdfchart" value="Growth-Chart (PDF)" style='margin-left: 20px;'>
    <input type="button" id="htmlchart" value="Growth-Chart (HTML)" style='margin-left: 20px;'>
    <?php }?>
  </td>
</tr>
<tr>
  <td colspan='3' style='text-align:center'>&nbsp;
  </td>
</tr>
<tr>
  <td colspan='3' style='text-align:center'>
    <input type="submit" name="Submit" class='cp-submit' value="Save Form">
    <input type="button" class="deleter cp-negative" id="cancel" value="Don't Save">
  </td>
</tr>
</table>
<br>
<br>
<input type="hidden" name="id" id='id' value="<?php echo $this->vitals->get_id(); ?>" />
<input type="hidden" name="activity" id='activity' value="<?php echo $this->vitals->get_activity(); ?>">
<input type="hidden" name="pid" id='pid' value="<?php echo $this->vitals->get_pid(); ?>">
<input type="hidden" name="process" id='process' value="true">
</form>
</body>
<script language="javascript">
  //var formdate = '{$vitals->get_date()|date_format:"%Y%m%d"}';
  var formdate = '<?php echo date_format(date_create($this->vitals->get_date()), "Ymd"); ?>';
  //// vitals array elements are in the format:
  //   date-height-weight-head_circumference
  var vitals = new Array();
  // get values from the current form elements
  vitals[0] = formdate+'-<?php echo $this->vitals->get_height();?>-<?php echo $this->vitals->get_weight();?>-<?php echo $this->vitals->get_head_circ();?>';
  // historic values
  <?php
  if(is_array($results)){ foreach ($this->results as $result) {
    ?>   
      vitals[vitals.length] = '<?php echo date_format(date_create($result["date"]),"Ymd"); ?>-<?php echo $result["height"];?>-<?php echo $result["weight"];?>-<?php echo $result["head_circ"];?>'  ;
    <?php }}
  ?>
    var patientAge='<?php echo $this->patient_age?>';
  var patient_dob='<?php echo $this->patient_dob;?>';
  var webroot = '<?php echo $this->form_action;?>';
  var pid = '<?php echo $this->vitals->get_pid();?>';
  var cancellink = '<?php echo $this->dont_save_link;?>';
  var birth_xl='Birth-24 months';
  var older_xl='2-20 years';
  function addGCSelector()
  {
    var options=new Array();
    var birth={
      'display':birth_xl,'param':'birth'};
    var age2={
      'display':older_xl,'param':'2-20'};
    if((patientAge.indexOf('24 month')>=0) || (patientAge.indexOf('month')==-1))
    {
      var dob_data=patient_dob.split("-");
      var dob_date=new Date(dob_data[0],parseInt(dob_data[1])-1,dob_data[2]);
      options[0]=age2;
      for(var idx=0;idx<vitals.length;idx++)
      {
        var str_data_date=vitals[idx].split("-")[0];
        var data_date=new Date(str_data_date.substr(0,4),parseInt(str_data_date.substr(4,2))-1,str_data_date.substr(6,2));
        if(((data_date-dob_date)/86400000)<=2*365)
        {
          idx=vitals.length;
          options[1]=birth;
        }
      }
    }
    else
    {
      options[0]=birth;
    }
    var chart_buttons_cell=$("#pdfchart").parent("td");
    var select=$("<select id='chart_type'></select>");
    chart_buttons_cell.prepend(select);
    for(idx=0;idx<options.length;idx++)
    {
      var option=$("<option value='"+options[idx].param+"'>"+options[idx].display+"</option>");
      select.append(option);
    }
    select.find("option:first").attr("selected","true");
    if(options.length<2)
    {
      select.css("display","none");
    }
  }
  $(document).ready(function(){
    $("#growthchart").click(function() {
      ShowGrowthchart();
    }
                           );
    $("#pdfchart").click(function() {
      ShowGrowthchart(1);
    }
                        );
    $("#htmlchart").click(function() {
      ShowGrowthchart(2);
    }
                         );
    $("#cancel").click(function() {
      location.href=cancellink;
    }
                      );
    addGCSelector();
  }
                   );
  function ShowGrowthchart(doPDF) {
    // get values from the current form elements
    vitals[0] = formdate+'-'+$("#height_input").val()+'-'+$("#weight_input").val()+'-'+$("#head_circ_input").val();
    // build the data string
    var datastring = "";
    for(var i=0; i<vitals.length; i++) {
      datastring += vitals[i]+"~";
    }
    newURL = webroot+'/interface/forms/vitals/growthchart/chart.php?pid='+pid+'&data='+datastring;
    if (doPDF == 1) newURL += "&pdf=1";
    if (doPDF == 2) newURL += "&html=1";
    newURL+="&chart_type="+$("#chart_type").val();
    // do the new window stuff
    top.restoreSession();
    window.open(newURL, '_blank', "menubar=1,toolbar=1,scrollbars=1,resizable=1,width=600,height=450");
  }
  function convLbtoKg(name) {
    var lb = $("#"+name).val();
    var hash_loc=lb.indexOf("#");
    if(hash_loc>=0)
    {
      var pounds=lb.substr(0,hash_loc);
      var ounces=lb.substr(hash_loc+1);
      var num=parseInt(pounds)+parseInt(ounces)/16;
      lb=num;
      $("#"+name).val(lb);
    }
    if (lb == "0") {
      $("#"+name+"_metric").val("0");
    }
    else if (lb == parseFloat(lb)) {
      kg = lb*0.45359237;
      kg = kg.toFixed(2);
      $("#"+name+"_metric").val(kg);
    }
    else {
      $("#"+name+"_metric").val("");
    }
    if (name == "weight_input") {
      calculateBMI();
    }
  }
  function convKgtoLb(name) {
    var kg = $("#"+name+"_metric").val();
    if (kg == "0") {
      $("#"+name).val("0");
    }
    else if (kg == parseFloat(kg)) {
      lb = kg/0.45359237;
      lb = lb.toFixed(2);
      $("#"+name).val(lb);
    }
    else {
      $("#"+name).val("");
    }
    if (name == "weight_input") {
      calculateBMI();
    }
  }
  function convIntoCm(name) {
    var inch = $("#"+name).val();
    if (inch == "0") {
      $("#"+name+"_metric").val("0");
    }
    else if (inch == parseFloat(inch)) {
      cm = inch*2.54;
      cm = cm.toFixed(2);
      $("#"+name+"_metric").val(cm);
    }
    else {
      $("#"+name+"_metric").val("");
    }
    if (name == "height_input") {
      calculateBMI();
    }
  }
  function convCmtoIn(name) {
    var cm = $("#"+name+"_metric").val();
    if (cm == "0") {
      $("#"+name).val("0");
    }
    else if (cm == parseFloat(cm)) {
      inch = cm/2.54;
      inch = inch.toFixed(2);
      $("#"+name).val(inch);
    }
    else {
      $("#"+name).val("");
    }
    if (name == "height_input") {
      calculateBMI();
    }
  }
  function convFtoC(name) {
    var Fdeg = $("#"+name).val();
    if (Fdeg == "0") {
      $("#"+name+"_metric").val("0");
    }
    else if (Fdeg == parseFloat(Fdeg)) {
      Cdeg = (Fdeg-32)*0.5556;
      Cdeg = Cdeg.toFixed(2);
      $("#"+name+"_metric").val(Cdeg);
    }
    else {
      $("#"+name+"_metric").val("");
    }
  }
  function convCtoF(name) {
    var Cdeg = $("#"+name+"_metric").val();
    if (Cdeg == "0") {
      $("#"+name).val("0");
    }
    else if (Cdeg == parseFloat(Cdeg)) {
      Fdeg = (Cdeg/0.5556)+32;
      Fdeg = Fdeg.toFixed(2);
      $("#"+name).val(Fdeg);
    }
    else {
      $("#"+name).val("");
    }
  }
  function calculateBMI() {
    var bmi = 0;
    var height = $("#height_input").val();
    var weight = $("#weight_input").val();
    if(height == 0 || weight == 0) {
      $("#BMI").val("");
    }
    else if((height == parseFloat(height)) && (weight == parseFloat(weight))) {
      bmi = weight/height/height*703;
      bmi = bmi.toFixed(1);
      $("#BMI_input").val(bmi);
    }
    else {
      $("#BMI_input").val("");
    }
  }
  $(function() {
    $("#date").datetimepicker({
      timepicker: true,
      format: "Y-m-d H:i"
    }
                             );
  }
   );
</script>
</html>
