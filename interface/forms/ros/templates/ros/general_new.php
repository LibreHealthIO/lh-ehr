<html>
<head>
<?php html_header_show();?>
 <style type="text/css" title="mystyles" media="all">
ttd {
	font-size:12pt;
	font-family:helvetica;
}
li{
	font-size:11pt;
	font-family:helvetica;
	margin-left: 15px;
}
a {
	font-size:11pt;
	font-family:helvetica;
}

.main_title{
	font-family: sans-serif;
	font-size: 14pt;
	font-weight: bold;
	text-decoration: none;
	color: #000000;
}
.section_title{
	font-family: sans-serif;
	font-size: 12pt;
	font-weight: bold;
	text-decoration: none;
	color: #000000;
}

.response_title {
	font-family: sans-serif;
	font-size: 10pt;
	font-weight: bold;
	font-style: italic;
	color: #000000;
}

.response_prompt{
	text-align: right;
	font-family: sans-serif;
	font-size: 9pt;
	text-decoration: none;
	color: #000000;
}

.response{
	border-width:1px;
	border-style:solid;
	border-color:black;
	text-align: center;
	font-family: sans-serif;
	font-size: 9pt;
	font-weight: lighter;
	text-decoration: none;
	color: #000000;
}

.responsetd{
	border-width:1px;
	border-style:solid;
	border-color:black;
}
</style>
</head>
<body bgcolor="<?php echo $this->style['BGCOLOR2'];?>">
<form name="ros" method="post" action="<?php echo $this->form_action;?>/interface/forms/ros/save.php"
 onsubmit="return top.restoreSession()">
<table><tr><td colspan='10'>
<p><span class="main_title">Review Of Systems</span></p>
</td></tr>

<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Constitutional");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Weight Change");?>:</td>
			<td class="response">                                                         
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_weight_change() ){?>
                                                            <label><input type="radio" name="weight_change" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="weight_change" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
			<td class="response_prompt"><?php echo xl("Weakness");?>:</td>
			<td class="response">                                                          
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_weakness()){?>
                                                            <label><input type="radio" name="weakness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="weakness" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                          </td>
			<td class="response_prompt"><?php echo xl("Fatigue");?>:</td>
			<td class="response">                                                          
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_fatigue()){?>
                                                            <label><input type="radio" name="fatigue" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="fatigue" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                          </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Anorexia");?>:</td>
			<td class="response">                            
                                                           <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_anorexia()){?>
                                                            <label><input type="radio" name="anorexia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="anorexia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
			<td class="response_prompt"><?php echo xl("Fever");?>:</td>
			<td class="response">                           
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_fever()){?>
                                                            <label><input type="radio" name="fever" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="fever" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
			<td class="response_prompt"><?php echo xl("Chills");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_chills()){?>
                                                            <label><input type="radio" name="chills" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="chills" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Night Sweats");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_night_sweats()){?>
                                                            <label><input type="radio" name="night_sweats" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="night_sweats" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                          </td>
			<td class="response_prompt"><?php echo xl("Insomnia");?>:</td>
			<td class="response">                            
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_insomnia()){?>
                                                            <label><input type="radio" name="insomnia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="insomnia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                           </td>
			<td class="response_prompt"><?php echo xl("Irritability");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_irritability()){?>
                                                            <label><input type="radio" name="irritability" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="irritability" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                          </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Heat or Cold");?>:</td>
			<td class="response">                            
                                                           <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_heat_or_cold()){?>
                                                            <label><input type="radio" name="heat_or_cold" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="heat_or_cold" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                           </td>
			<td class="response_prompt"><?php echo xl("Intolerance");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_intolerance()){?>
                                                            <label><input type="radio" name="intolerance" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="intolerance" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Eyes");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Change in Vision");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_weight_change()){?>
                                                            <label><input type="radio" name="change_in_vision" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="change_in_vision" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
			<td class="response_prompt"><?php echo xl("Family History of Glaucoma");?>:</td>
			<td class="response">                          
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_glaucoma_history()){?>
                                                            <label><input type="radio" name="glaucoma_history" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="glaucoma_history" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                          </td>
			<td class="response_prompt"><?php echo xl("Eye Pain");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_eye_pain()){?>
                                                            <label><input type="radio" name="eye_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="eye_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Irritation");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_irritation()){?>
                                                            <label><input type="radio" name="irritation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="irritation" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                          </td>
			<td class="response_prompt"><?php echo xl("Redness");?>:</td>
			<td class="response">                           
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_redness()){?>
                                                            <label><input type="radio" name="redness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="redness" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                          </td>
			<td class="response_prompt"><?php echo xl("Excessive Tearing");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_excessive_tearing()){?>
                                                            <label><input type="radio" name="excessive_tearing" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="excessive_tearing" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Double Vision");?>:</td>
			<td class="response">                          
			<?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_double_vision()){?>
                                                            <label><input type="radio" name="double_vision" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="double_vision" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                             </td>
                                                          <td class="response_prompt"><?php echo xl("Blind Spots");?>:</td>
			<td class="response">                          
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_blind_spots()){?>
                                                            <label><input type="radio" name="blind_spots" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="blind_spots" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
			<td class="response_prompt"><?php echo xl("Photophobia");?>:</td>
			<td class="response">                          
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_photophobia()){?>
                                                            <label><input type="radio" name="photophobia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="photophobia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Ears");?>, <?php echo xl("Nose");?>, <?php echo xl("Mouth");?>, <?php echo xl("Throat");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Hearing Loss");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hearing_loss()){?>
                                                            <label><input type="radio" name="hearing_loss" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hearing_loss" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
			<td class="response_prompt"><?php echo xl("Discharge");?>:</td>
			<td class="response">                          
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_discharge()){?>
                                                            <label><input type="radio" name="discharge" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="discharge" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
			<td class="response_prompt"><?php echo xl("Pain");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_pain()){?>
                                                            <label><input type="radio" name="pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Vertigo");?>:</td>
			<td class="response">                           
                                                           <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_vertigo()){?>
                                                            <label><input type="radio" name="vertigo" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="vertigo" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
			<td class="response_prompt"><?php echo xl("Tinnitus");?>:</td>
			<td class="response">                         
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_tinnitus()){?>
                                                            <label><input type="radio" name="tinnitus" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="tinnitus" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                         </td>
			<td class="response_prompt"><?php echo xl("Frequent Colds");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_frequent_colds()){?>
                                                            <label><input type="radio" name="frequent_colds" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="frequent_colds" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                        </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Sore Throat");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_sore_throat()){?>
                                                            <label><input type="radio" name="sore_throat" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="sore_throat" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> </td>
			<td class="response_prompt"><?php echo xl("Sinus Problems");?>:</td>
			<td class="response">                           
			<?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_sinus_problems()){?>
                                                            <label><input type="radio" name="sinus_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="sinus_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> 
                                                            </td>
                                                          <td class="response_prompt"><?php echo xl("Post Nasal Drip");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_frequent_colds()){?>
                                                            <label><input type="radio" name="post_nasal_drip" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="post_nasal_drip" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Nosebleed");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_nosebleed()){?>
                                                            <label><input type="radio" name="nosebleed" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="nosebleed" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> </td>
			<td class="response_prompt"><?php echo xl("Snoring");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_snoring()){?>
                                                            <label><input type="radio" name="snoring" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="snoring" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> </td>
			<td class="response_prompt"><?php echo xl("Apnea");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_apnea()){?>
                                                            <label><input type="radio" name="apnea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="apnea" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?> </td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Breast");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Breast Mass");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_breast_mass()){?>
                                                            <label><input type="radio" name="breast_mass" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="breast_mass" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Discharge");?>:</td>
			<td class="response">                            
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_breast_discharge()){?>
                                                            <label><input type="radio" name="breast_discharge" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="breast_discharge" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Biopsy");?>:</td>
			<td class="response">                            
                                                       <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_biopsy()){?>
                                                            <label><input type="radio" name="biopsy" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="biopsy" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Abnormal Mammogram");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_abnormal_mammogram()){?>
                                                            <label><input type="radio" name="abnormal_mammogram" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="abnormal_mammogram" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  
                                                        </td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Respiratory");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Cough");?>:</td>
			<td class="response">                           
                                                          <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_cough()){?>
                                                            <label><input type="radio" name="cough" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="cough" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  
                                                            </td>
			<td class="response_prompt"><?php echo xl("Sputum");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_sputum()){?>
                                                            <label><input type="radio" name="sputum" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="sputum" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  </td>
			<td class="response_prompt"><?php echo xl("Shortness of Breath");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_shortness_of_breath()){?>
                                                            <label><input type="radio" name="shortness_of_breath" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="shortness_of_breath" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Wheezing");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_wheezing()){?>
                                                            <label><input type="radio" name="wheezing" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="wheezing" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  </td>
			<td class="response_prompt"><?php echo xl("Hemoptysis");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hemoptsyis()){?>
                                                            <label><input type="radio" name="hemoptsyis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hemoptsyis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  </td>
			<td class="response_prompt"><?php echo xl("Asthma");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_asthma()){?>
                                                            <label><input type="radio" name="asthma" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="asthma" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("COPD");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_copd()){?>
                                                            <label><input type="radio" name="copd" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="copd" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>  </td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Cardiovascular");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Chest Pain");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_chest_pain()){?>
                                                            <label><input type="radio" name="chest_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="chest_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Palpitation");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_palpitation()){?>
                                                            <label><input type="radio" name="palpitation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="palpitation" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Syncope");?>:</td>
			<td class="response">                          
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_syncope()){?>
                                                            <label><input type="radio" name="syncope" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="syncope" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("PND");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_pnd()){?>
                                                            <label><input type="radio" name="pnd" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="pnd" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("DOE");?>:</td>
			<td class="response">                           
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_doe()){?>
                                                            <label><input type="radio" name="doe" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="doe" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Orthopnea");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_orthopnea()){?>
                                                            <label><input type="radio" name="orthopnea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="orthopnea" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Peripheral");?>:</td>
			<td class="response">                                
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_peripheal()){?>
                                                            <label><input type="radio" name="peripheal" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="peripheal" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Edema");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_edema()){?>
                                                            <label><input type="radio" name="edema" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="edema" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Leg Pain/Cramping");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_legpain_cramping()){?>
                                                            <label><input type="radio" name="legpain_cramping" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="legpain_cramping" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("History of Heart Murmur");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_history_murmur()){?>
                                                            <label><input type="radio" name="history_murmur" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="history_murmur" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Arrythmia");?>:</td>
			<td class="response">                            
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_arrythmia()){?>
                                                            <label><input type="radio" name="arrythmia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="arrythmia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Heart Problem");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_heart_problem()){?>
                                                            <label><input type="radio" name="heart_problem" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="heart_problem" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Gastrointestinal");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Dysphagia");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_dysphagia()){?>
                                                            <label><input type="radio" name="dysphagia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="dysphagia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Heartburn");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_heartburn()){?>
                                                            <label><input type="radio" name="heartburn" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="heartburn" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Bloating");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_bloating()){?>
                                                            <label><input type="radio" name="bloating" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="bloating" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Belching");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_belching()){?>
                                                            <label><input type="radio" name="belching" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="belching" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Flatulence");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_flatulence()){?>
                                                            <label><input type="radio" name="flatulence" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="flatulence" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Nausea");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_nausea()){?>
                                                            <label><input type="radio" name="nausea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="nausea" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Vomiting");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_vomiting()){?>
                                                            <label><input type="radio" name="vomiting" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="vomiting" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Hematemesis");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hematemesis()){?>
                                                            <label><input type="radio" name="hematemesis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hematemesis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Pain");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_gastro_pain()){?>
                                                            <label><input type="radio" name="gastro_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="gastro_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Food Intolerance");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_food_intolerance()){?>
                                                            <label><input type="radio" name="food_intolerance" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="food_intolerance" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("H/O Hepatitis");?>:</td>
			<td class="response">                          
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hepatitis()){?>
                                                            <label><input type="radio" name="hepatitis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hepatitis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Jaundice");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_jaundice()){?>
                                                            <label><input type="radio" name="jaundice" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="jaundice" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Hematochezia");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hematochezia()){?>
                                                            <label><input type="radio" name="hematochezia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hematochezia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Changed Bowel");?>:</td>
			<td class="response">                           
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_changed_bowel()){?>
                                                            <label><input type="radio" name="changed_bowel" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="changed_bowel" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Diarrhea");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_copd()){?>
                                                            <label><input type="radio" name="diarrhea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="diarrhea" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Constipation");?>:</td>
			<td class="response">                           
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_constipation()){?>
                                                            <label><input type="radio" name="constipation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="constipation" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Genitourinary");?> <?php echo xl("General");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Polyuria");?>:</td>
			<td class="response">                           
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_polyuria()){?>
                                                            <label><input type="radio" name="polyuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="polyuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Polydypsia");?>:</td>
			<td class="response">                            
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_polydypsia()){?>
                                                            <label><input type="radio" name="polydypsia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="polydypsia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Dysuria");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_dysuria()){?>
                                                            <label><input type="radio" name="dysuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="dysuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Hematuria");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hematuria()){?>
                                                            <label><input type="radio" name="hematuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hematuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Frequency");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_copd()){?>
                                                            <label><input type="radio" name="frequency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="frequency" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Urgency");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_urgency()){?>
                                                            <label><input type="radio" name="urgency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="urgency" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Incontinence");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_incontinence()){?>
                                                            <label><input type="radio" name="incontinence" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="incontinence" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Renal Stones");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_renal_stones()){?>
                                                            <label><input type="radio" name="renal_stones" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="renal_stones" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("UTIs");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_utis()){?>
                                                            <label><input type="radio" name="utis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="utis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                    </td>
		</tr>
</tr><td>
	
		
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Genitourinary");?> <?php echo xl("Male");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Hesitancy");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hesitancy()){?>
                                                            <label><input type="radio" name="hesitancy" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hesitancy" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Dribbling");?>:</td>
			<td class="response">                            
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_dribbling()){?>
                                                            <label><input type="radio" name="dribbling" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="dribbling" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Stream");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_stream()){?>
                                                            <label><input type="radio" name="stream" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="stream" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Nocturia");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_nocturia()){?>
                                                            <label><input type="radio" name="nocturia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="nocturia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Erections");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_erections()){?>
                                                            <label><input type="radio" name="erections" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="erections" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                          </td>
			<td class="response_prompt"><?php echo xl("Ejaculations");?>:</td>
			<td class="response">
                                                         <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_ejaculations()){?>
                                                            <label><input type="radio" name="ejaculations" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="ejaculations" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Genitourinary");?> <?php echo xl("Female");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Female G");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_g()){?>
                                                            <label><input type="radio" name="g" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="g" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Female P");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_p()){?>
                                                            <label><input type="radio" name="p" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="p" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Female AP");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_ap()){?>
                                                            <label><input type="radio" name="ap" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="ap" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                            </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Female LC");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_lc()){?>
                                                            <label><input type="radio" name="lc" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="lc" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Menarche");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_mearche()){?>
                                                            <label><input type="radio" name="mearche" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="mearche" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Menopause");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_menopause()){?>
                                                            <label><input type="radio" name="menopause" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="menopause" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("LMP");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_lmp()){?>
                                                            <label><input type="radio" name="lmp" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="lmp" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Frequency");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_f_frequency()){?>
                                                            <label><input type="radio" name="f_frequency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="f_frequency" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                         </td>
			<td class="response_prompt"><?php echo xl("Flow");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_f_flow()){?>
                                                            <label><input type="radio" name="f_flow" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="f_flow" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Symptoms");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_f_symptoms()){?>
                                                            <label><input type="radio" name="f_symptoms" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="f_symptoms" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
			<td class="response_prompt"><?php echo xl("Abnormal Hair Growth");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_abnormal_hair_growth()){?>
                                                            <label><input type="radio" name="abnormal_hair_growth" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="abnormal_hair_growth" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                            </td>
			<td class="response_prompt"><?php echo xl("F/H Female Hirsutism/Striae");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_f_hirsutism()){?>
                                                            <label><input type="radio" name="f_hirsutism" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="f_hirsutism" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?>
                                                        </td>
		</tr>
</tr><td>
	</td>
</tr></table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Musculoskeletal");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Chronic Joint Pain");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_joint_pain()){?>
                                                            <label><input type="radio" name="joint_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="joint_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Swelling");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_swelling()){?>
                                                            <label><input type="radio" name="swelling" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="swelling" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Redness");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_m_redness()){?>
                                                            <label><input type="radio" name="m_redness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="m_redness" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Warm");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_m_warm()){?>
                                                            <label><input type="radio" name="m_warm" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="m_warm" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Stiffness");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_m_stiffness()){?>
                                                            <label><input type="radio" name="m_stiffness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="m_stiffness" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Muscle");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_muscle()){?>
                                                            <label><input type="radio" name="muscle" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="muscle" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Aches");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_m_aches()){?>
                                                            <label><input type="radio" name="m_aches" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="m_aches" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("FMS");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_fms()){?>
                                                            <label><input type="radio" name="fms" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="fms" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Arthritis");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_arthritis()){?>
                                                            <label><input type="radio" name="arthritis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="arthritis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Neurologic");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("LOC");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_loc()){?>
                                                            <label><input type="radio" name="loc" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="loc" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Seizures");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_seizures()){?>
                                                            <label><input type="radio" name="seizures" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="seizures" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Stroke");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_stroke()){?>
                                                            <label><input type="radio" name="stroke" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="stroke" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("TIA");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_tia()){?>
                                                            <label><input type="radio" name="tia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="tia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Numbness");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_n_numbness()){?>
                                                            <label><input type="radio" name="n_numbness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="n_numbness" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Weakness");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_n_weakness()){?>
                                                            <label><input type="radio" name="n_weakness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="n_weakness" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Paralysis");?>:</td>
			<td class="response">
                                                    <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_paralysis()){?>
                                                            <label><input type="radio" name="paralysis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="paralysis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Intellectual Decline");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_intellectual_decline()){?>
                                                            <label><input type="radio" name="intellectual_decline" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="intellectual_decline" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Memory Problems");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_memory_problems()){?>
                                                            <label><input type="radio" name="memory_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="memory_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Dementia");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_dementia()){?>
                                                            <label><input type="radio" name="dementia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="dementia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Headache");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_n_headache()){?>
                                                            <label><input type="radio" name="n_headache" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="n_headache" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Skin");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Cancer");?>:</td>
			<td class="response">
                                                    <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_s_cancer()){?>
                                                            <label><input type="radio" name="s_cancer" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="s_cancer" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Psoriasis");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_psoriasis()){?>
                                                            <label><input type="radio" name="psoriasis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="psoriasis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Acne");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_s_acne()){?>
                                                            <label><input type="radio" name="s_acne" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="s_acne" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Other");?>:</td>
			<td class="response">
                                                    <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_s_other()){?>
                                                            <label><input type="radio" name="s_other" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="s_other" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Disease");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_s_disease()){?>
                                                            <label><input type="radio" name="s_disease" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="s_disease" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Psychiatric");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Psychiatric Diagnosis");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_p_diagnosis()){?>
                                                            <label><input type="radio" name="p_diagnosis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="p_diagnosis" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Psychiatric Medication");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_p_medication()){?>
                                                            <label><input type="radio" name="p_medication" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="p_medication" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Depression");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_depression()){?>
                                                            <label><input type="radio" name="depression" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="depression" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Anxiety");?>:</td>
			<td class="response">
                                                            <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_anxiety()){?>
                                                            <label><input type="radio" name="anxiety" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="anxiety" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Social Difficulties");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_social_difficulties()){?>
                                                            <label><input type="radio" name="social_difficulties" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="social_difficulties" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Endocrine");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Thyroid Problems");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_thyroid_problems()){?>
                                                            <label><input type="radio" name="thyroid_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="thyroid_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Diabetes");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_diabetes()){?>
                                                            <label><input type="radio" name="diabetes" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="diabetes" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Abnormal Blood Test");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_abnormal_blood()){?>
                                                            <label><input type="radio" name="abnormal_blood" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="abnormal_blood" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>
<tr>
	<td><table><td class ="responsetd"><span class="section_title"><?php echo xl("Hematologic");?>/<?php echo xl("Allergic");?>/<?php echo xl("Immunologic");?></span><table>
		<tr>
			<td class="response_prompt"><?php echo xl("Anemia");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_anemia()){?>
                                                            <label><input type="radio" name="anemia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="anemia" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("F/H Blood Problems");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_s_acne()){?>
                                                            <label><input type="radio" name="fh_blood_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="fh_blood_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Bleeding Problems");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_bleeding_problems()){?>
                                                            <label><input type="radio" name="bleeding_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="bleeding_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("Allergies");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_allergies()){?>
                                                            <label><input type="radio" name="allergies" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="allergies" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("Frequent Illness");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_frequent_illness()){?>
                                                            <label><input type="radio" name="frequent_illness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="frequent_illness" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
			<td class="response_prompt"><?php echo xl("HIV");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hiv()){?>
                                                            <label><input type="radio" name="hiv" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hiv" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
		<tr>
			<td class="response_prompt"><?php echo xl("HAI Status");?>:</td>
			<td class="response">
                                                        <?php foreach ($this->form->get_options() as $value) {
                                                          if($value==$this->form->get_hai_status()){?>
                                                            <label><input type="radio" name="hai_status" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
                                                         <?php } else {?>
                                                            <label><input type="radio" name="hai_status" value="<?php echo $value;?>"/><?php echo $value;?></label>
                                                         <?php }
                                                         }?></td>
		</tr>
</tr><td>	
	</td>
</tr>

	</table></td>
</tr>

<tr>
<td>
	<input type="hidden" name="id" value="<?php echo $this->form->get_id();?>" />
	<input type="hidden" name="pid" value="<?php echo $this->form->get_pid();?>" />
	<input type="hidden" name="process" value="true" />
</td>
</tr>
<tr>
<td>
	<input type="submit" name="Submit" value=<?php echo  xl('Save Form','e','"','"'); ?>>
</td>
<td>
	<a href="<?php echo $this->dont_save_link;?>" class="link" onclick="top.restoreSession()">[<?php xl("Don't Save","e"); ?>]</a>
</td>
</tr>
</table>
</body>
</html>
