<html>
  <head>
  <?php html_header_show();?>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  <script
    src="https://code.jquery.com/jquery-1.12.4.min.js"
    integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
    crossorigin="anonymous"></script>
  <script type="text/javascript">
    // Resizes the panels to be the height of the largest panel
        window.onload = function resizePanel(){
            var h = $("#largest-panel").height();
            $(".panel").height(h);
        }
  </script>
  </head>
  <!-- Made each section panels, to organize and add Bootstrap -->
  <body bgcolor="<?php echo $this->style['BGCOLOR2'];?>">
    <form
      name="ros"
      method="post"
      action="<?php echo $this->form_action;?>/interface/forms/ros/save.php"
      onsubmit="return top.restoreSession()"
    >
      <span class="title" ><?php xl('Review of Systems','e'); ?></span><br><br>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Constitutional");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Weight Change");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_weight_change() ){?>
                <label><input type="radio" name="weight_change" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="weight_change" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Weakness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_weakness() ){?>
                <label><input type="radio" name="weakness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="weakness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Fatigue");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fatigue() ){?>
                <label><input type="radio" name="fatigue" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fatigue" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Anorexia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_anorexia() ){?>
                <label><input type="radio" name="anorexia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="anorexia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Fever");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fever() ){?>
                <label><input type="radio" name="fever" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fever" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Chills");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_chills() ){?>
                <label><input type="radio" name="chills" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="chills" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Night Sweats");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_night_sweats() ){?>
                <label><input type="radio" name="night_sweats" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="night_sweats" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Insomnia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_insomnia() ){?>
                <label><input type="radio" name="insomnia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="insomnia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Irritability");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_irritability() ){?>
                <label><input type="radio" name="irritability" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="irritability" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Heat or Cold");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_heat_or_cold() ){?>
                <label><input type="radio" name="heat_or_cold" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="heat_or_cold" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Intolerance");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_intolerance() ){?>
                <label><input type="radio" name="intolerance" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="intolerance" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Eyes");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Change in Vision");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_change_in_vision() ){?>
                <label><input type="radio" name="change_in_vision" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="change_in_vision" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Family History of Glaucoma");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_glaucoma_history() ){?>
                <label><input type="radio" name="glaucoma_history" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="glaucoma_history" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Eye Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_eye_pain() ){?>
                <label><input type="radio" name="eye_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="eye_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Irritation");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_irritation() ){?>
                <label><input type="radio" name="irritation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="irritation" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Redness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_redness() ){?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Excessive Tearing");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_excessive_tearing() ){?>
                <label><input type="radio" name="excessive_tearing" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="excessive_tearing" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Double Vision");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_double_vision() ){?>
                <label><input type="radio" name="double_vision" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="double_vision" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Blind Spots");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_blind_spots() ){?>
                <label><input type="radio" name="blind_spots" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="blind_spots" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Photophobia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_photophobia() ){?>
                <label><input type="radio" name="photophobia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="photophobia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Ears");?>, <?php echo xl("Nose");?>, <?php echo xl("Mouth");?>, <?php echo xl("Throat");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Hearing Loss");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hearing_loss() ){?>
                <label><input type="radio" name="hearing_loss" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hearing_loss" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Discharge");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_discharge() ){?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_pain() ){?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xl("Vertigo");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_vertigo() ){?>
                <label><input type="radio" name="vertigo" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="vertigo" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Tinnitus");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_tinnitus() ){?>
                <label><input type="radio" name="tinnitus" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="tinnitus" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Frequent Colds");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_frequent_colds() ){?>
                <label><input type="radio" name="frequent_colds" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequent_colds" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Sore Throat");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_sore_throat() ){?>
                <label><input type="radio" name="sore_throat" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="sore_throat" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Sinus Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_sinus_problems() ){?>
                <label><input type="radio" name="sinus_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="sinus_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Post Nasal Drip");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_post_nasal_drip() ){?>
                <label><input type="radio" name="post_nasal_drip" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="post_nasal_drip" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>      
            <?php echo xl("Nosebleed");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_nosebleed() ){?>
                <label><input type="radio" name="nosebleed" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="nosebleed" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Snoring");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_snoring() ){?>
                <label><input type="radio" name="snoring" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="snoring" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Apnea");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_apnea() ){?>
                <label><input type="radio" name="apnea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="apnea" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Breast");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Breast Mass");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_breast_mass() ){?>
                <label><input type="radio" name="breast_mass" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="breast_mass" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Discharge");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_discharge() ){?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Biopsy");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_biopsy() ){?>
                <label><input type="radio" name="biopsy" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="biopsy" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Abnormal Mammogram");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_abnormal_mammogram() ){?>
                <label><input type="radio" name="abnormal_mammogram" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="abnormal_mammogram" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Respiratory");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Cough");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_cough() ){?>
                <label><input type="radio" name="cough" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="cough" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Sputum");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_sputum() ){?>
                <label><input type="radio" name="sputum" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="sputum" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Shortness of Breath");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_shortness_of_breath() ){?>
                <label><input type="radio" name="shortness_of_breath" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="shortness_of_breath" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Wheezing");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_wheezing() ){?>
                <label><input type="radio" name="wheezing" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="wheezing" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Hemoptysis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hemoptsyis() ){?>
                <label><input type="radio" name="hemoptsyis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hemoptsyis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Asthma");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_asthma() ){?>
                <label><input type="radio" name="asthma" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="asthma" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("COPD");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_copd() ){?>
                <label><input type="radio" name="copd" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="copd" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>          
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Cardiovascular");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Chest Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_chest_pain() ){?>
                <label><input type="radio" name="chest_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="chest_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xl("Palpitation");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_palpitation() ){?>
                <label><input type="radio" name="palpitation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="palpitation" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xl("Syncope");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_syncope() ){?>
                <label><input type="radio" name="syncope" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="syncope" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xl("PND");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_pnd() ){?>
                <label><input type="radio" name="pnd" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="pnd" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xl("DOE");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_doe() ){?>
                <label><input type="radio" name="doe" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="doe" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xl("Orthopnea");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_orthopnea() ){?>
                <label><input type="radio" name="orthopnea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="orthopnea" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <!-- The get_peripheal function is mispelled -->
          <div>
            <?php echo xl("Peripheral");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_peripheal() ){?>
                <label><input type="radio" name="peripheal" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="peripheal" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xl("Edema");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_edema() ){?>
                <label><input type="radio" name="edema" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="edema" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xl("Leg Pain/Cramping");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_legpain_cramping() ){?>
                <label><input type="radio" name="legpain_cramping" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="legpain_cramping" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xl("History of Heart Murmur");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_history_murmur() ){?>
                <label><input type="radio" name="history_murmur" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="history_murmur" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xl("Arrythmia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_arrythmia() ){?>
                <label><input type="radio" name="arrythmia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="arrythmia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xl("Heart Problem");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_legpain_cramping() ){?>
                <label><input type="radio" name="heart_problem" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="heart_problem" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div id="largest-panel" class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Gastrointestinal");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Dysphagia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dysphagia() ){?>
                <label><input type="radio" name="dysphagia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dysphagia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Heartburn");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_heartburn() ){?>
                <label><input type="radio" name="heartburn" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="heartburn" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Bloating");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_bloating() ){?>
                <label><input type="radio" name="bloating" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="bloating" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Belching");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_belching() ){?>
                <label><input type="radio" name="belching" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="belching" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Flatulence");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_flatulence() ){?>
                <label><input type="radio" name="flatulence" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="flatulence" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Nausea");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_nausea() ){?>
                <label><input type="radio" name="nausea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="nausea" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Vomiting");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_vomiting() ){?>
                <label><input type="radio" name="vomiting" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="vomiting" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Hematemesis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hematemesis() ){?>
                <label><input type="radio" name="hematemesis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hematemesis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_pain() ){?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Food Intolerance");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_food_intolerance() ){?>
                <label><input type="radio" name="food_intolerance" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="food_intolerance" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("H/O Hepatitis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hepatitis() ){?>
                <label><input type="radio" name="hepatitis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hepatitis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Jaundice");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_jaundice() ){?>
                <label><input type="radio" name="jaundice" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="jaundice" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Hematochezia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hematochezia() ){?>
                <label><input type="radio" name="hematochezia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hematochezia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Changed Bowel");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_changed_bowel() ){?>
                <label><input type="radio" name="changed_bowel" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="changed_bowel" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Diarrhea");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_diarrhea() ){?>
                <label><input type="radio" name="diarrhea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="diarrhea" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Constipation");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_constipation() ){?>
                <label><input type="radio" name="constipation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="constipation" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Genitourinary General");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Polyuria");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_polyuria() ){?>
                <label><input type="radio" name="polyuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="polyuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Polydypsia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_polydypsia() ){?>
                <label><input type="radio" name="polydypsia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="polydypsia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Dysuria");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dysuria() ){?>
                <label><input type="radio" name="dysuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dysuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Hematuria");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hematuria() ){?>
                <label><input type="radio" name="hematuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hematuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Frequency");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_frequency() ){?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Urgency");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_urgency() ){?>
                <label><input type="radio" name="urgency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="urgency" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Incontinence");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_incontinence() ){?>
                <label><input type="radio" name="incontinence" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="incontinence" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Renal Stones");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_renal_stones() ){?>
                <label><input type="radio" name="renal_stones" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="renal_stones" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("UTIs");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_utis() ){?>
                <label><input type="radio" name="utis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="utis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Genitourinary Male");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Hesitancy");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hesitancy() ){?>
                <label><input type="radio" name="hesitancy" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hesitancy" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Dribbling");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dribbling() ){?>
                <label><input type="radio" name="dribbling" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dribbling" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Stream");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_stream() ){?>
                <label><input type="radio" name="stream" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="stream" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Nocturia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_nocturia() ){?>
                <label><input type="radio" name="nocturia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="nocturia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Erections");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_erections() ){?>
                <label><input type="radio" name="erections" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="erections" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Ejaculations");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_ejaculations() ){?>
                <label><input type="radio" name="ejaculations" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="ejaculations" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Genitourinary Female");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Female G");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_g() ){?>
                <label><input type="radio" name="g" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="g" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Female P");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_p() ){?>
                <label><input type="radio" name="p" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="p" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Female AP");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_ap() ){?>
                <label><input type="radio" name="ap" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="ap" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Female LC");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_lc() ){?>
                <label><input type="radio" name="lc" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="lc" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <!-- The get_mearche function is mispelled -->
          <div>
            <?php echo xl("Menarche");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_mearche() ){?>
                <label><input type="radio" name="mearche" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="mearche" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Menopause");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_menopause() ){?>
                <label><input type="radio" name="menopause" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="menopause" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("LMP");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_lmp() ){?>
                <label><input type="radio" name="lmp" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="lmp" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Frequency");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_f_frequency() ){?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Flow");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_f_flow() ){?>
                <label><input type="radio" name="f_flow" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="f_flow" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Symptoms");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_f_symptoms() ){?>
                <label><input type="radio" name="f_symptoms" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="f_symptoms" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Abnormal Hair Growth");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_abnormal_hair_growth() ){?>
                <label><input type="radio" name="abnormal_hair_growth" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="abnormal_hair_growth" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("F/H Female Hirsutism/Striae");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_f_hirsutism() ){?>
                <label><input type="radio" name="f_hirsutism" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="f_hirsutism" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Musculoskeletal");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Chronic Joint Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_joint_pain() ){?>
                <label><input type="radio" name="joint_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="joint_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Swelling");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_swelling() ){?>
                <label><input type="radio" name="swelling" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="swelling" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Redness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_redness() ){?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Warm");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_m_warm() ){?>
                <label><input type="radio" name="m_warm" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="m_warm" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Stiffness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_m_stiffness() ){?>
                <label><input type="radio" name="m_stiffness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="m_stiffness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Muscle");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_muscle() ){?>
                <label><input type="radio" name="muscle" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="muscle" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Aches");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_m_aches() ){?>
                <label><input type="radio" name="m_aches" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="m_aches" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("FMS");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fms() ){?>
                <label><input type="radio" name="fms" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fms" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Arthritis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_arthritis() ){?>
                <label><input type="radio" name="arthritis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="arthritis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Neurologic");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("LOC");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_loc() ){?>
                <label><input type="radio" name="loc" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="loc" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Seizures");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_seizures() ){?>
                <label><input type="radio" name="seizures" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="seizures" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Stroke");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_stroke() ){?>
                <label><input type="radio" name="stroke" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="stroke" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("TIA");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_tia() ){?>
                <label><input type="radio" name="tia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="tia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Numbness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_n_numbness() ){?>
                <label><input type="radio" name="n_numbness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="n_numbness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Weakness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_n_weakness() ){?>
                <label><input type="radio" name="n_weakness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="n_weakness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Paralysis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_paralysis() ){?>
                <label><input type="radio" name="paralysis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="paralysis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Intellectual Decline");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_intellectual_decline() ){?>
                <label><input type="radio" name="intellectual_decline" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="intellectual_decline" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Memory Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_memory_problems() ){?>
                <label><input type="radio" name="memory_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="memory_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Dementia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dementia() ){?>
                <label><input type="radio" name="dementia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dementia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Headache");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_n_headache() ){?>
                <label><input type="radio" name="n_headache" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="n_headache" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Skin");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Cancer");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_s_cancer() ){?>
                <label><input type="radio" name="s_cancer" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="s_cancer" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Psoriasis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_psoriasis() ){?>
                <label><input type="radio" name="psoriasis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="psoriasis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Acne");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_s_acne() ){?>
                <label><input type="radio" name="s_acne" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="s_acne" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Other");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_s_other() ){?>
                <label><input type="radio" name="s_other" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="s_other" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Disease");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_s_disease() ){?>
                <label><input type="radio" name="s_disease" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="s_disease" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Psychiatric");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Psychiatric Diagnosis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_p_diagnosis() ){?>
                <label><input type="radio" name="p_diagnosis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="p_diagnosis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Psychiatric Medication");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_p_medication() ){?>
                <label><input type="radio" name="p_medication" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="p_medication" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Depression");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_depression() ){?>
                <label><input type="radio" name="depression" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="depression" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Anxiety");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_anxiety() ){?>
                <label><input type="radio" name="anxiety" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="anxiety" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Social Difficulties");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_social_difficulties() ){?>
                <label><input type="radio" name="social_difficulties" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="social_difficulties" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Endocrine");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Thyroid Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_thyroid_problems() ){?>
                <label><input type="radio" name="thyroid_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="thyroid_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Diabetes");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_diabetes() ){?>
                <label><input type="radio" name="diabetes" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="diabetes" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Abnormal Blood Test");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_abnormal_blood() ){?>
                <label><input type="radio" name="abnormal_blood" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="abnormal_blood" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xl("Hematologic/Allergic/Immunologic");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xl("Anemia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_anemia() ){?>
                <label><input type="radio" name="anemia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="anemia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("F/H Blood Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fh_blood_problems() ){?>
                <label><input type="radio" name="fh_blood_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fh_blood_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Bleeding Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_bleeding_problems() ){?>
                <label><input type="radio" name="bleeding_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="bleeding_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Allergies");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_allergies() ){?>
                <label><input type="radio" name="allergies" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="allergies" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("Frequent Illness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_frequent_illness() ){?>
                <label><input type="radio" name="frequent_illness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequent_illness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("HIV");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hiv() ){?>
                <label><input type="radio" name="hiv" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hiv" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xl("HAI Status");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hai_status() ){?>
                <label><input type="radio" name="hai_status" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hai" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
        </div>
      </div>
      <div>
        <!-- Save/Cancel buttons -->
        <input type="button" id="save" value="<?php echo xla('Save'); ?>"> &nbsp;
        <input type="button" id="dontsave" class="deleter" value="<?php echo xla('Cancel'); ?>"> &nbsp;
      </div>
  </body>
</html>
