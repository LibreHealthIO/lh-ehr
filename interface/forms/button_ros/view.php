<?php
//by Art Eaton button ROS
include_once("../../globals.php");
include_once("$srcdir/api.inc");
formHeader("Form: button_ros");
$obj = formFetch("form_button_ros", $_GET["id"]);
?>

<form method=post action="<?echo $rootdir?>/forms/button_ros/save.php?mode=update&id=<?echo $_GET["id"];?>" name="my_form">

<? if($obj{"finalize"}==="off"OR acl_check('admin', 'super')){?>
<a href="javascript:top.restoreSession();document.my_form.submit();" class="link_submit">[Save]</a>
<a href="<?php echo $GLOBALS['form_exit_url']; ?>" class="link" onclick="top.restoreSession()">[Don't Save Changes]</a>
<br>

 <input type=checkbox name='finalize' <? if ($obj{"finalize"} == "on") {echo "checked";};?>>&nbsp;<b>Check here to finalize this form:</b><br>
 <?}else{
		 echo"This form has been finalized and may not be edited!<br>";?>
 		<a href="<?php echo $GLOBALS['form_exit_url']; ?>" class="link" onclick="top.restoreSession()">[RETURN TO ENCOUNTER]</a>
 <?}?>
<br>

	<table id='ros'>
	<TR>
		<TD> REVIEW of SYSTEMS:</TD>
	</TR>
	<TR class="alt">
	<TD>
	<B>GEN:</b>
	</TD>
	</tr>
	<tr>
	<TD>
  
		<span class="checkbox-button">
			<input id="GEN1" name="GEN1" type="checkbox" value="on" <? if ($obj{"GEN1"} == "on") {echo "checked";}?>>
			<label for="GEN1" > Fatigue </label>                                 
		</span>                                                                  
                                                                                 
		<span class="checkbox-button">                                           
			<input id="GEN2" name="GEN2" type="checkbox" value="on" <? if ($obj{"GEN2"} == "on") {echo "checked";}?>>
			<label for="GEN2"> Fever </label>                                    
		</span>                                                                  
		                                                                         
		<span class="checkbox-button">                                           
			<input id="GEN3" name="GEN3" type="checkbox" value="on" <? if ($obj{"GEN3"} == "on") {echo "checked";}?>>
			<label for="GEN3"> Chills </label>                                   
		</span>                                                                  
		                                                                         
		<span class="checkbox-button">                                           
			<input id="GEN4" name="GEN4" type="checkbox" value="on" <? if ($obj{"GEN4"} == "on") {echo "checked";}?>>
			<label for="GEN4"> Night Sweats </label>                             
		</span>                                                                  
		                                                                         
		<span class="checkbox-button">                                           
			<input id="GEN5" name="GEN5" type="checkbox" value="on" <? if ($obj{"GEN5"} == "on") {echo "checked";}?>>
			<label for="GEN5"> Recent Weight Gain </label>                       
		</span>                                                                  
		                                                                         
		<span class="checkbox-button">                                           
			<input id="GEN6" name="GEN6" type="checkbox" value="on" <? if ($obj{"GEN6"} == "on") {echo "checked";}?>>
			<label for="GEN6"> Recent Weight Loss </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="GEN_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"GEN_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	

		<TR class="alt">
	<TD>
	<B>EYES:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="EYES1" name="EYES1" type="checkbox" value="on" <? if ($obj{"EYES1"} == "on") {echo "checked";}?>>
			<label for="EYES1"> Vision Changes </label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES2" name="EYES2" type="checkbox" value="on" <? if ($obj{"EYES2"} == "on") {echo "checked";}?>>
			<label for="EYES2"> Floaters </label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES3" name="EYES3" type="checkbox" value="on" <? if ($obj{"EYES3"} == "on") {echo "checked";}?>>
			<label for="EYES3"> Diplopia </label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES4" name="EYES4" type="checkbox" value="on" <? if ($obj{"EYES4"} == "on") {echo "checked";}?>>
			<label for="EYES4"> Blurry Vision </label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES5" name="EYES5" type="checkbox" value="on" <? if ($obj{"EYES5"} == "on") {echo "checked";}?>>
			<label for="EYES5"> Eye Pain </label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES6" name="EYES6" type="checkbox" value="on" <? if ($obj{"EYES6"} == "on") {echo "checked";}?>>
			<label for="EYES6"> Photophobia </label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES7" name="EYES7" type="checkbox" value="on" <? if ($obj{"EYES7"} == "on") {echo "checked";}?>>
			<label for="EYES7"> Red Eyes </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="EYES_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"EYES_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	

	
	
	<TR class="alt">
	<TD>
	<B>ENT:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="ENT1" name="ENT1" type="checkbox" value="on" <? if ($obj{"ENT1"} == "on") {echo "checked";}?>>
			<label for="ENT1"> Hearing Loss </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT2" name="ENT2" type="checkbox" value="on" <? if ($obj{"ENT2"} == "on") {echo "checked";}?>>
			<label for="ENT2"> Ear Ache </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT3" name="ENT3" type="checkbox" value="on" <? if ($obj{"ENT3"} == "on") {echo "checked";}?>>
			<label for="ENT3"> Otorrhea </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT4" name="ENT4" type="checkbox" value="on" <? if ($obj{"ENT4"} == "on") {echo "checked";}?>>
			<label for="ENT4"> Tinnitis </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT5" name="ENT5" type="checkbox" value="on" <? if ($obj{"ENT5"} == "on") {echo "checked";}?>>
			<label for="ENT5"> Epistaxis </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT6" name="ENT6" type="checkbox" value="on" <? if ($obj{"ENT6"} == "on") {echo "checked";}?>>
			<label for="ENT6"> Sneezing </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT7" name="ENT7" type="checkbox" value="on" <? if ($obj{"ENT7"} == "on") {echo "checked";}?>>
			<label for="ENT7"> Nasal Drip </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT8" name="ENT8" type="checkbox" value="on" <? if ($obj{"ENT8"} == "on") {echo "checked";}?>>
			<label for="ENT8"> Sore Throat </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT9" name="ENT9" type="checkbox" value="on" <? if ($obj{"ENT9"} == "on") {echo "checked";}?>>
			<label for="ENT9"> Mouth Dryness </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT10" name="ENT10" type="checkbox" value="on" <? if ($obj{"ENT10"} == "on") {echo "checked";}?>>
			<label for="ENT10"> Problem Swallowing </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT11" name="ENT11" type="checkbox" value="on" <? if ($obj{"ENT11"} == "on") {echo "checked";}?>>
			<label for="ENT11"> Dental Problems </label>
		</span>
		</TD>	
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="ENT_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"ENT_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	

	
	
	<TR class="alt">
	<TD>
	<B>RESP:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="RESP1" name="RESP1" type="checkbox" value="on" <? if ($obj{"RESP1"} == "on") {echo "checked";}?>>
			<label for="RESP1"> Speech Problem </label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP2" name="RESP2" type="checkbox" value="on" <? if ($obj{"RESP2"} == "on") {echo "checked";}?>>
			<label for="RESP2"> Shortness of Breath </label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP3" name="RESP3" type="checkbox" value="on" <? if ($obj{"RESP3"} == "on") {echo "checked";}?>>
			<label for="RESP3"> Orthopnea </label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP4" name="RESP4" type="checkbox" value="on" <? if ($obj{"RESP4"} == "on") {echo "checked";}?>>
			<label for="RESP4"> Cough </label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP5" name="RESP5" type="checkbox" value="on" <? if ($obj{"RESP5"} == "on") {echo "checked";}?>>
			<label for="RESP5"> Cough w/Sputum </label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP6" name="RESP6" type="checkbox" value="on" <? if ($obj{"RESP6"} == "on") {echo "checked";}?>>
			<label for="RESP6"> Hemoptysis </label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP7" name="RESP7" type="checkbox" value="on" <? if ($obj{"RESP7"} == "on") {echo "checked";}?>>
			<label for="RESP7"> Wheezing </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="RESP_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"RESP_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>

	<TR class="alt">
	<TD>
	<B>CVS:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="CVS1" name="CVS1" type="checkbox" value="on" <? if ($obj{"CVS1"} == "on") {echo "checked";}?>>
			<label for="CVS1"> Chest Pain </label>
		</span>
		
		<span class="checkbox-button">
			<input id="CVS2" name="CVS2" type="checkbox" value="on" <? if ($obj{"CVS2"} == "on") {echo "checked";}?>>
			<label for="CVS2"> Palpitations </label>
		</span>
		
		<span class="checkbox-button">
			<input id="CVS3" name="CVS3" type="checkbox" value="on" <? if ($obj{"CVS3"} == "on") {echo "checked";}?>>
			<label for="CVS3"> Leg Pain w/Exercise </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="CVS_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"CVS_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>		
	

	
	<TR class="alt">
	<TD>
	<B>GI:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="GI1" name="GI1" type="checkbox" value="on" <? if ($obj{"GI1"} == "on") {echo "checked";}?>>
			<label for="GI1"> Appetite Decrease </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI2" name="GI2" type="checkbox" value="on" <? if ($obj{"GI2"} == "on") {echo "checked";}?>>
			<label for="GI2"> Anorexia </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI3" name="GI3" type="checkbox" value="on" <? if ($obj{"GI3"} == "on") {echo "checked";}?>>
			<label for="GI3"> Dysphagia </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI4" name="GI4" type="checkbox" value="on" <? if ($obj{"GI4"} == "on") {echo "checked";}?>>
			<label for="GI4"> Heartburn </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI5" name="GI5" type="checkbox" value="on" <? if ($obj{"GI5"} == "on") {echo "checked";}?>>
			<label for="GI5"> Nausea </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI6" name="GI6" type="checkbox" value="on" <? if ($obj{"GI6"} == "on") {echo "checked";}?>>
			<label for="GI6"> Vomiting </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI7" name="GI7" type="checkbox" value="on" <? if ($obj{"GI7"} == "on") {echo "checked";}?>>
			<label for="GI7"> Hematemesis </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI8" name="GI8" type="checkbox" value="on" <? if ($obj{"GI8"} == "on") {echo "checked";}?>>
			<label for="GI8"> Abdominal Pain </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI9" name="GI9" type="checkbox" value="on" <? if ($obj{"GI9"} == "on") {echo "checked";}?>>
			<label for="GI9"> Jaundice </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI10" name="GI10" type="checkbox" value="on" <? if ($obj{"GI10"} == "on") {echo "checked";}?>>
			<label for="GI10"> Diarrhea </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI11" name="GI11" type="checkbox" value="on" <? if ($obj{"GI11"} == "on") {echo "checked";}?>>
			<label for="GI11"> Constipation </label>
		</span>
		</TD>	
	
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="GI_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"GI_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	

	
	
	
	<TR class="alt">
	<TD>
	<B>GU:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="GU1" name="GU1" type="checkbox" value="on" <? if ($obj{"GU1"} == "on") {echo "checked";}?>>
			<label for="GU1"> Hematura </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU2" name="GU2" type="checkbox" value="on" <? if ($obj{"GU2"} == "on") {echo "checked";}?>>
			<label for="GU2"> Change in Frequency of Urination </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU3" name="GU3" type="checkbox" value="on" <? if ($obj{"GU3"} == "on") {echo "checked";}?>>
			<label for="GU3"> Urinary Urgency </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU4" name="GU4" type="checkbox" value="on" <? if ($obj{"GU4"} == "on") {echo "checked";}?>>
			<label for="GU4"> Incontinence </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU5" name="GU5" type="checkbox" value="on" <? if ($obj{"GU5"} == "on") {echo "checked";}?>>
			<label for="GU5"> Slow Urination </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU6" name="GU6" type="checkbox" value="on" <? if ($obj{"GU6"} == "on") {echo "checked";}?>>
			<label for="GU6"> Irregular Periods </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU7" name="GU7" type="checkbox" value="on" <? if ($obj{"GU7"} == "on") {echo "checked";}?>>
			<label for="GU7"> Hot Flashes </label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU8" name="GU8" type="checkbox" value="on" <? if ($obj{"GU8"} == "on") {echo "checked";}?>>
			<label for="GU8"> Post Menopausal Bleeding </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="GU_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"GU_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>

	<TR class="alt">
	<TD>
	<B>MSK:</b>
	</TD>
	</tr>
	
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="MSK1" name="MSK1" type="checkbox" value="on" <? if ($obj{"MSK1"} == "on") {echo "checked";}?>>
			<label for="MSK1"> Back Pain </label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK2" name="MSK2" type="checkbox" value="on" <? if ($obj{"MSK2"} == "on") {echo "checked";}?>>
			<label for="MSK2"> Muscle Aches </label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK3" name="MSK3" type="checkbox" value="on" <? if ($obj{"MSK3"} == "on") {echo "checked";}?>>
			<label for="MSK3"> Arthralgias </label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK4" name="MSK4" type="checkbox" value="on" <? if ($obj{"MSK4"} == "on") {echo "checked";}?>>
			<label for="MSK4"> Muscle Cramps </label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK5" name="MSK5" type="checkbox" value="on" <? if ($obj{"MSK5"} == "on") {echo "checked";}?>>
			<label for="MSK5"> Localized Joint Pain </label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK6" name="MSK6" type="checkbox" value="on" <? if ($obj{"MSK6"} == "on") {echo "checked";}?>>
			<label for="MSK6"> Localized Joint Swelling </label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK7" name="MSK7" type="checkbox" value="on" <? if ($obj{"MSK7"} == "on") {echo "checked";}?>>
			<label for="MSK7"> Localized Joint Stiffness</label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="MSK_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"MSK_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>

		

	
	<TR class="alt">
	<TD>
	<B>NEURO:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="NEURO1" name="NEURO1" type="checkbox" value="on" <? if ($obj{"NEURO1"} == "on") {echo "checked";}?>>
			<label for="NEURO1"> Dizziness </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO2" name="NEURO2" type="checkbox" value="on" <? if ($obj{"NEURO2"} == "on") {echo "checked";}?>>
			<label for="NEURO2"> Vertigo </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO3" name="NEURO3" type="checkbox" value="on" <? if ($obj{"NEURO3"} == "on") {echo "checked";}?>>
			<label for="NEURO3"> Syncope </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO4" name="NEURO4" type="checkbox" value="on" <? if ($obj{"NEURO4"} == "on") {echo "checked";}?>>
			<label for="NEURO4"> Confusion </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO5" name="NEURO5" type="checkbox" value="on" <? if ($obj{"NEURO5"} == "on") {echo "checked";}?>>
			<label for="NEURO5"> Memory Loss </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO6" name="NEURO6" type="checkbox" value="on" <? if ($obj{"NEURO6"} == "on") {echo "checked";}?>>
			<label for="NEURO6"> Speech Disturbance </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO7" name="NEURO7" type="checkbox" value="on" <? if ($obj{"NEURO7"} == "on") {echo "checked";}?>>
			<label for="NEURO7"> Limb Weakness </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO8" name="NEURO8" type="checkbox" value="on" <? if ($obj{"NEURO8"} == "on") {echo "checked";}?>>
			<label for="NEURO8"> Paralysis </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO9" name="NEURO9" type="checkbox" value="on" <? if ($obj{"NEURO9"} == "on") {echo "checked";}?>>
			<label for="NEURO9"> Involuntary Movements </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO10" name="NEURO10" type="checkbox" value="on" <? if ($obj{"NEURO10"} == "on") {echo "checked";}?>>
			<label for="NEURO10"> Balance Issues </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO11" name="NEURO11" type="checkbox" value="on" <? if ($obj{"NEURO11"} == "on") {echo "checked";}?>>
			<label for="NEURO11"> Tingling </label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO12" name="NEURO12" type="checkbox" value="on" <? if ($obj{"NEURO12"} == "on") {echo "checked";}?>>
			<label for="NEURO12"> Numbness </label>
		</span>
		</TD>
	
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="NEURO_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"NEURO_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	
		
	<TR class="alt">
	<TD>
	<B>HEME:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="HEME1" name="HEME1" type="checkbox" value="on" <? if ($obj{"HEME1"} == "on") {echo "checked";}?>>
			<label for="HEME1"> Bleeding </label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME2" name="HEME2" type="checkbox" value="on" <? if ($obj{"HEME2"} == "on") {echo "checked";}?>>
			<label for="HEME2"> Bruising </label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME3" name="HEME3" type="checkbox" value="on" <? if ($obj{"HEME3"} == "on") {echo "checked";}?>>
			<label for="HEME3"> Anemia </label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME4" name="HEME4" type="checkbox" value="on" <? if ($obj{"HEME4"} == "on") {echo "checked";}?>>
			<label for="HEME4"> Sickle Cell Disease </label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME5" name="HEME5" type="checkbox" value="on" <? if ($obj{"HEME5"} == "on") {echo "checked";}?>>
			<label for="HEME5"> Hx Blood Transfusion </label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME6" name="HEME6" type="checkbox" value="on" <? if ($obj{"HEME6"} == "on") {echo "checked";}?>>
			<label for="HEME6"> HIV+ </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="HEME_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"HEME_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	
	
	<TR class="alt">
	<TD>
			<B>ENDO:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="ENDO1" name="ENDO1" type="checkbox" value="on" <? if ($obj{"ENDO1"} == "on") {echo "checked";}?>>
			<label for="ENDO1"> Polydipsia </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO2" name="ENDO2" type="checkbox" value="on" <? if ($obj{"ENDO2"} == "on") {echo "checked";}?>>
			<label for="ENDO2"> Heat Intolerance </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO3" name="ENDO3" type="checkbox" value="on" <? if ($obj{"ENDO3"} == "on") {echo "checked";}?>>
			<label for="ENDO3"> Cold Intolerance </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO4" name="ENDO4" type="checkbox" value="on" <? if ($obj{"ENDO4"} == "on") {echo "checked";}?>>
			<label for="ENDO4"> Excessive Sweating </label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO5" name="ENDO5" type="checkbox" value="on" <? if ($obj{"ENDO5"} == "on") {echo "checked";}?>>
			<label for="ENDO5"> Feelings of Weakness </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="ENDO_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"ENDO_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	
	
	<TR class="alt">
	<TD>
		<B>DERM:</b>
	</TD>
	</tr>
	<tr>
	<TD>
		<span class="checkbox-button">
			<input id="DERM1" name="DERM1" type="checkbox" value="on" <? if ($obj{"DERM1"} == "on") {echo "checked";}?>>
			<label for="DERM1"> Dry Skin </label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM2" name="DERM2" type="checkbox" value="on" <? if ($obj{"DERM2"} == "on") {echo "checked";}?>>
			<label for="DERM2"> Itching </label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM3" name="DERM3" type="checkbox" value="on" <? if ($obj{"DERM3"} == "on") {echo "checked";}?>>
			<label for="DERM3"> Peeling Skin </label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM4" name="DERM4" type="checkbox" value="on" <? if ($obj{"DERM4"} == "on") {echo "checked";}?>>
			<label for="DERM4"> Skin Scalding</label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM5" name="DERM5" type="checkbox" value="on" <? if ($obj{"DERM5"} == "on") {echo "checked";}?>>
			<label for="DERM5"> Localized Skin Discoloration </label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM6" name="DERM6" type="checkbox" value="on" <? if ($obj{"DERM6"} == "on") {echo "checked";}?>>
			<label for="DERM6"> Rash </label>
		</span>
		</TD>
		</tr>
		<TR>
		<TD>
		Note abnormalities: 
					<TEXTAREA NAME="DERM_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"><? echo stripslashes($obj{"DERM_notes"});?></TEXTAREA></FONT>
					</TD>
	</TR>	
			</table>
</form>
<?php
formFooter();
?>
