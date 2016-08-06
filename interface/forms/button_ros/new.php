<?php
//by Art Eaton
include_once("../../globals.php");
include_once("$srcdir/api.inc");
formHeader("Form: button_ros");
?>

<form method=post action="<?echo $rootdir;?>/forms/button_ros/save.php?mode=new" name="my_form">

 
<?php
$tempquery = "SELECT date FROM form_encounter WHERE encounter = $encounter";
 $datequery = sqlStatement($tempquery);
$encdate = SqlFetchArray($datequery);

$old_date = strtotime($encdate['date']);
$todays_date = strtotime(date("Ymd"));
if($old_date+(86400) < $todays_date) {
echo ("It is not permitted to enter a Review of Systems form in an encounter that is dated earlier than 3 days prior to today's date.");
} else {

?>

<a href="javascript:top.restoreSession();document.my_form.submit();" class="link_submit">[Save]</a>
<?php echo xlt('You must EDIT the form after saving and reviewing to finalize
<a href="<?php echo $GLOBALS['form_exit_url']; ?>" class="link_submit"
 onclick="top.restoreSession()">[Don't Save]</a>
<br>
<?php echo xlt('You must EDIT the form after saving and reviewing to finalize.
	
	<table id='ros'>
	
	<TR>
		<TD> <?php echo xlt('REVIEW of SYSTEMS:');?></TD>
	</TR>
	<TR class="alt">
	<TD>
	<B><?php echo xlt('GEN:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
  
		<span class="checkbox-button">
			<input id="GEN1" name="GEN1" type="checkbox" value="on">
			<label for="GEN1" > <?php echo xlt('Fatigue');?></label>
		</span>

		<span class="checkbox-button">
			<input id="GEN2" name="GEN2" type="checkbox" value="on" >
			<label for="GEN2"> <?php echo xlt('Fever');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GEN3" name="GEN3" type="checkbox" value="on" >
			<label for="GEN3"> <?php echo xlt('Chills');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GEN4" name="GEN4" type="checkbox" value="on" >
			<label for="GEN4"> <?php echo xlt('Night Sweats');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GEN5" name="GEN5" type="checkbox" value="on" >
			<label for="GEN5"> <?php echo xlt('Recent Weight Gain');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GEN6" name="GEN6" type="checkbox" value="on" >
			<label for="GEN6"> <?php echo xlt('Recent Weight Loss');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="GEN_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	

		<TR class="alt">
	<TD>
	<B><?php echo xlt('EYES:');?> </B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="EYES1" name="EYES1" type="checkbox" value="on" >
			<label for="EYES1"> <?php echo xlt('Vision Changes');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES2" name="EYES2" type="checkbox" value="on" >
			<label for="EYES2"> <?php echo xlt('Floaters');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES3" name="EYES3" type="checkbox" value="on" >
			<label for="EYES3"> <?php echo xlt('Diplopia');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES4" name="EYES4" type="checkbox" value="on" >
			<label for="EYES4"> <?php echo xlt('Blurry Vision');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES5" name="EYES5" type="checkbox" value="on" >
			<label for="EYES5"> <?php echo xlt('Eye Pain');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES6" name="EYES6" type="checkbox" value="on" >
			<label for="EYES6"> <?php echo xlt('Photophobia');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="EYES7" name="EYES7" type="checkbox" value="on" >
			<label for="EYES7"> <?php echo xlt('Red Eyes');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="EYES_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	

	
	
	<TR class="alt">
	<TD>
	<B><?php echo xlt('ENT:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="ENT1" name="ENT1" type="checkbox" value="on" >
			<label for="ENT1"> <?php echo xlt('Hearing Loss');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT2" name="ENT2" type="checkbox" value="on" >
			<label for="ENT2"> <?php echo xlt('Ear Ache');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT3" name="ENT3" type="checkbox" value="on" >
			<label for="ENT3"> <?php echo xlt('Otorrhea');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT4" name="ENT4" type="checkbox" value="on" >
			<label for="ENT4"> <?php echo xlt('Tinnitis');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT5" name="ENT5" type="checkbox" value="on" >
			<label for="ENT5"> <?php echo xlt('Epistaxis');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT6" name="ENT6" type="checkbox" value="on" >
			<label for="ENT6"> <?php echo xlt('Sneezing');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT7" name="ENT7" type="checkbox" value="on" >
			<label for="ENT7"> <?php echo xlt('Nasal Drip');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT8" name="ENT8" type="checkbox" value="on" >
			<label for="ENT8"> <?php echo xlt('Sore Throat');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT9" name="ENT9" type="checkbox" value="on" >
			<label for="ENT9"> <?php echo xlt('Mouth Dryness');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT10" name="ENT10" type="checkbox" value="on" >
			<label for="ENT10"> <?php echo xlt('Problem Swallowing');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENT11" name="ENT11" type="checkbox" value="on" >
			<label for="ENT11"> <?php echo xlt('Dental Problems');?></label>
		</span>
		</TD>	
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="ENT_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	
	
	<TR class="alt">
	<TD>
	<B><?php echo xlt('RESP:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="RESP1" name="RESP1" type="checkbox" value="on" >
			<label for="RESP1"> <?php echo xlt('Speech Problem');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP2" name="RESP2" type="checkbox" value="on" >
			<label for="RESP2"> <?php echo xlt('Shortness of Breath');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP3" name="RESP3" type="checkbox" value="on" >
			<label for="RESP3"> <?php echo xlt('Orthopnea');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP4" name="RESP4" type="checkbox" value="on" >
			<label for="RESP4"> <?php echo xlt('Cough');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP5" name="RESP5" type="checkbox" value="on" >
			<label for="RESP5"> <?php echo xlt('Cough w/Sputum');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP6" name="RESP6" type="checkbox" value="on" >
			<label for="RESP6"> <?php echo xlt('Hemoptysis');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="RESP7" name="RESP7" type="checkbox" value="on" >
			<label for="RESP7"> <?php echo xlt('Wheezing');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="RESP_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>

	<TR class="alt">
	<TD>
	<B><?php echo xlt('CVS:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="CVS1" name="CVS1" type="checkbox" value="on" >
			<label for="CVS1"> <?php echo xlt('Chest Pain');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="CVS2" name="CVS2" type="checkbox" value="on" >
			<label for="CVS2"> <?php echo xlt('Palpitations');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="CVS3" name="CVS3" type="checkbox" value="on" >
			<label for="CVS3"> <?php echo xlt('Leg Pain w/Exercise');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="CVS_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>		
		
	<TR class="alt">
	<TD>
	<B><?php echo xlt('GI:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="GI1" name="GI1" type="checkbox" value="on" >
			<label for="GI1"> <?php echo xlt('Appetite Decrease');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI2" name="GI2" type="checkbox" value="on" >
			<label for="GI2"> <?php echo xlt('Anorexia');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI3" name="GI3" type="checkbox" value="on" >
			<label for="GI3"> <?php echo xlt('Dysphagia');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI4" name="GI4" type="checkbox" value="on" >
			<label for="GI4"> <?php echo xlt('Heartburn');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI5" name="GI5" type="checkbox" value="on" >
			<label for="GI5"> <?php echo xlt('Nausea');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI6" name="GI6" type="checkbox" value="on" >
			<label for="GI6"> <?php echo xlt('Vomiting');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI7" name="GI7" type="checkbox" value="on" >
			<label for="GI7"> <?php echo xlt('Hematemesis');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI8" name="GI8" type="checkbox" value="on" >
			<label for="GI8"> <?php echo xlt('Abdominal Pain');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI9" name="GI9" type="checkbox" value="on" >
			<label for="GI9"> <?php echo xlt('Jaundice');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI10" name="GI10" type="checkbox" value="on" >
			<label for="GI10"> <?php echo xlt('Diarrhea');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GI11" name="GI11" type="checkbox" value="on" >
			<label for="GI11"> <?php echo xlt('Constipation');?></label>
		</span>
		</TD>	
	
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="GI_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	

	<TR class="alt">
	<TD>
	<B><?php echo xlt('GU:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="GU1" name="GU1" type="checkbox" value="on" >
			<label for="GU1"> <?php echo xlt('Hematura');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU2" name="GU2" type="checkbox" value="on" >
			<label for="GU2"> <?php echo xlt('Change in Frequency of Urination');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU3" name="GU3" type="checkbox" value="on" >
			<label for="GU3"> <?php echo xlt('Urinary Urgency');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU4" name="GU4" type="checkbox" value="on" >
			<label for="GU4"> <?php echo xlt('Incontinence');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU5" name="GU5" type="checkbox" value="on" >
			<label for="GU5"> <?php echo xlt('Slow Urination');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU6" name="GU6" type="checkbox" value="on" >
			<label for="GU6"> <?php echo xlt('Irregular Periods');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU7" name="GU7" type="checkbox" value="on" >
			<label for="GU7"> <?php echo xlt('Hot Flashes');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="GU8" name="GU8" type="checkbox" value="on" >
			<label for="GU8"> <?php echo xlt('Post Menopausal Bleeding');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="GU_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>

	<TR class="alt">
	<TD>
	<B><?php echo xlt('MSK:');?></B>
	</TD>
	</TR>
	
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="MSK1" name="MSK1" type="checkbox" value="on" >
			<label for="MSK1"> <?php echo xlt('Back Pain');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK2" name="MSK2" type="checkbox" value="on" >
			<label for="MSK2"> <?php echo xlt('Muscle Aches');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK3" name="MSK3" type="checkbox" value="on" >
			<label for="MSK3"> <?php echo xlt('Arthralgias');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK4" name="MSK4" type="checkbox" value="on" >
			<label for="MSK4"> <?php echo xlt('Muscle Cramps');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK5" name="MSK5" type="checkbox" value="on" >
			<label for="MSK5"> <?php echo xlt('Localized Joint Pain');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK6" name="MSK6" type="checkbox" value="on" >
			<label for="MSK6"> <?php echo xlt('Localized Joint Swelling');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="MSK7" name="MSK7" type="checkbox" value="on" >
			<label for="MSK7"> <?php echo xlt('Localized Joint Stiffness');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="MSK_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>

	<TR class="alt">
	<TD>
	<B><?php echo xlt('NEURO:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="NEURO1" name="NEURO1" type="checkbox" value="on" >
			<label for="NEURO1"> <?php echo xlt('Dizziness');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO2" name="NEURO2" type="checkbox" value="on" >
			<label for="NEURO2"> <?php echo xlt('Vertigo');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO3" name="NEURO3" type="checkbox" value="on" >
			<label for="NEURO3"> <?php echo xlt('Syncope');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO4" name="NEURO4" type="checkbox" value="on" >
			<label for="NEURO4"> <?php echo xlt('Confusion');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO5" name="NEURO5" type="checkbox" value="on" >
			<label for="NEURO5"> <?php echo xlt('Memory Loss');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO6" name="NEURO6" type="checkbox" value="on" >
			<label for="NEURO6"> <?php echo xlt('Speech Disturbance');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO7" name="NEURO7" type="checkbox" value="on" >
			<label for="NEURO7"> <?php echo xlt('Limb Weakness');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO8" name="NEURO8" type="checkbox" value="on" >
			<label for="NEURO8"> <?php echo xlt('Paralysis');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO9" name="NEURO9" type="checkbox" value="on" >
			<label for="NEURO9"> <?php echo xlt('Involuntary Movements');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO10" name="NEURO10" type="checkbox" value="on" >
			<label for="NEURO10"> <?php echo xlt('Balance Issues');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO11" name="NEURO11" type="checkbox" value="on" >
			<label for="NEURO11"> <?php echo xlt('Tingling');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="NEURO12" name="NEURO12" type="checkbox" value="on" >
			<label for="NEURO12"> <?php echo xlt('Numbness');?></label>
		</span>
		</TD>
	
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="NEURO_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	
		
	<TR class="alt">
	<TD>
	<B><?php echo xlt('HEME:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="HEME1" name="HEME1" type="checkbox" value="on" >
			<label for="HEME1"> <?php echo xlt('Bleeding');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME2" name="HEME2" type="checkbox" value="on" >
			<label for="HEME2"> <?php echo xlt('Bruising');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME3" name="HEME3" type="checkbox" value="on" >
			<label for="HEME3"> <?php echo xlt('Anemia');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME4" name="HEME4" type="checkbox" value="on" >
			<label for="HEME4"> <?php echo xlt('Sickle Cell Disease');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME5" name="HEME5" type="checkbox" value="on" >
			<label for="HEME5"> <?php echo xlt('Hx Blood Transfusion');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="HEME6" name="HEME6" type="checkbox" value="on" >
			<label for="HEME6"> <?php echo xlt('HIV+');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="HEME_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	
	
	<TR class="alt">
	<TD>
			<B><?php echo xlt('ENDO:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="ENDO1" name="ENDO1" type="checkbox" value="on" >
			<label for="ENDO1"> <?php echo xlt('Polydipsia');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO2" name="ENDO2" type="checkbox" value="on" >
			<label for="ENDO2"> <?php echo xlt('Heat Intolerance');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO3" name="ENDO3" type="checkbox" value="on" >
			<label for="ENDO3"> <?php echo xlt('Cold Intolerance');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO4" name="ENDO4" type="checkbox" value="on" >
			<label for="ENDO4"> <?php echo xlt('Excessive Sweating');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="ENDO5" name="ENDO5" type="checkbox" value="on" >
			<label for="ENDO5"> <?php echo xlt('Feelings of Weakness');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="ENDO_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	
	
	<TR class="alt">
	<TD>
		<B><?php echo xlt('DERM:');?></B>
	</TD>
	</TR>
	<TR>
	<TD>
		<span class="checkbox-button">
			<input id="DERM1" name="DERM1" type="checkbox" value="on" >
			<label for="DERM1"> <?php echo xlt('Dry Skin');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM2" name="DERM2" type="checkbox" value="on" >
			<label for="DERM2"> <?php echo xlt('Itching');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM3" name="DERM3" type="checkbox" value="on" >
			<label for="DERM3"> <?php echo xlt('Peeling Skin');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM4" name="DERM4" type="checkbox" value="on" >
			<label for="DERM4"> <?php echo xlt('Skin Scalding');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM5" name="DERM5" type="checkbox" value="on" >
			<label for="DERM5"> <?php echo xlt('Localized Skin Discoloration');?></label>
		</span>
		
		<span class="checkbox-button">
			<input id="DERM6" name="DERM6" type="checkbox" value="on" >
			<label for="DERM6"> <?php echo xlt('Rash');?></label>
		</span>
		</TD>
		</TR>
		<TR>
		<TD>
		<?php echo xlt('Note abnormalities:');?> 
					<TEXTAREA NAME="DERM_notes" ROWS=3 COLS=25 WRAP=SOFT STYLE="width: 2in; height: 0.21in"></TEXTAREA>
					</TD>
	</TR>	
</form>
<?php
}
formFooter();
?>
