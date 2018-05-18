<?php
  include_once("../../globals.php");
  include_once("$srcdir/api.inc");
  formHeader("Review of Systems");
?>
<html>
  <head>
    <?php call_required_libraries(['jquery-min-1-9-1', 'bootstrap']); ?>
    <script type="text/javascript">
      // Resizes the panels to be the height of the largest panel
      function resizePanel() {
        var h = $("#largest-panel").height();
        // Hack to ensure panels do not 
        $(".panel").height(h);
      }

      window.onload = resizePanel();
    </script>
    <style type="text/css">
      /* For the rare case when you open it as sole tab
         and then add another tab */
      #largest-panel {
        overflow: scroll;
      }
    </style>
  </head>
  <!-- Made each section panels, to organize and add Bootstrap -->
  <body onresize ="resizePanel()">
    <h2><?php echo xlt('Review of Systems'); ?></h2>
    <form
      name="ros"
      method="post"
      action="<?php echo $this->form_action;?>/interface/forms/ros/save.php"
      onsubmit="return top.restoreSession()"
    >
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Constitutional");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Weight Change");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_weight_change() ){?>
                <label><input type="radio" name="weight_change" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="weight_change" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Weakness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_weakness() ){?>
                <label><input type="radio" name="weakness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="weakness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Fatigue");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fatigue() ){?>
                <label><input type="radio" name="fatigue" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fatigue" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Anorexia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_anorexia() ){?>
                <label><input type="radio" name="anorexia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="anorexia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Fever");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fever() ){?>
                <label><input type="radio" name="fever" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fever" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Chills");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_chills() ){?>
                <label><input type="radio" name="chills" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="chills" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Night Sweats");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_night_sweats() ){?>
                <label><input type="radio" name="night_sweats" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="night_sweats" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Insomnia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_insomnia() ){?>
                <label><input type="radio" name="insomnia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="insomnia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Irritability");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_irritability() ){?>
                <label><input type="radio" name="irritability" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="irritability" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Heat or Cold");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_heat_or_cold() ){?>
                <label><input type="radio" name="heat_or_cold" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="heat_or_cold" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Intolerance");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Eyes");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Change in Vision");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_change_in_vision() ){?>
                <label><input type="radio" name="change_in_vision" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="change_in_vision" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Family History of Glaucoma");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_glaucoma_history() ){?>
                <label><input type="radio" name="glaucoma_history" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="glaucoma_history" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Eye Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_eye_pain() ){?>
                <label><input type="radio" name="eye_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="eye_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Irritation");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_irritation() ){?>
                <label><input type="radio" name="irritation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="irritation" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Redness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_redness() ){?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Excessive Tearing");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_excessive_tearing() ){?>
                <label><input type="radio" name="excessive_tearing" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="excessive_tearing" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Double Vision");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_double_vision() ){?>
                <label><input type="radio" name="double_vision" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="double_vision" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Blind Spots");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_blind_spots() ){?>
                <label><input type="radio" name="blind_spots" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="blind_spots" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Photophobia");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Ears");?>, <?php echo xlt("Nose");?>, <?php echo xlt("Mouth");?>, <?php echo xlt("Throat");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Hearing Loss");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hearing_loss() ){?>
                <label><input type="radio" name="hearing_loss" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hearing_loss" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Discharge");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_discharge() ){?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_pain() ){?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xlt("Vertigo");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_vertigo() ){?>
                <label><input type="radio" name="vertigo" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="vertigo" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Tinnitus");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_tinnitus() ){?>
                <label><input type="radio" name="tinnitus" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="tinnitus" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Frequent Colds");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_frequent_colds() ){?>
                <label><input type="radio" name="frequent_colds" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequent_colds" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Sore Throat");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_sore_throat() ){?>
                <label><input type="radio" name="sore_throat" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="sore_throat" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Sinus Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_sinus_problems() ){?>
                <label><input type="radio" name="sinus_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="sinus_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Post Nasal Drip");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_post_nasal_drip() ){?>
                <label><input type="radio" name="post_nasal_drip" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="post_nasal_drip" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>      
            <?php echo xlt("Nosebleed");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_nosebleed() ){?>
                <label><input type="radio" name="nosebleed" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="nosebleed" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Snoring");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_snoring() ){?>
                <label><input type="radio" name="snoring" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="snoring" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Apnea");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Breast");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Breast Mass");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_breast_mass() ){?>
                <label><input type="radio" name="breast_mass" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="breast_mass" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Discharge");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_discharge() ){?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="discharge" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Biopsy");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_biopsy() ){?>
                <label><input type="radio" name="biopsy" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="biopsy" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Abnormal Mammogram");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Respiratory");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Cough");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_cough() ){?>
                <label><input type="radio" name="cough" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="cough" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Sputum");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_sputum() ){?>
                <label><input type="radio" name="sputum" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="sputum" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Shortness of Breath");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_shortness_of_breath() ){?>
                <label><input type="radio" name="shortness_of_breath" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="shortness_of_breath" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Wheezing");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_wheezing() ){?>
                <label><input type="radio" name="wheezing" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="wheezing" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Hemoptysis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hemoptsyis() ){?>
                <label><input type="radio" name="hemoptsyis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hemoptsyis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Asthma");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_asthma() ){?>
                <label><input type="radio" name="asthma" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="asthma" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("COPD");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Cardiovascular");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Chest Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_chest_pain() ){?>
                <label><input type="radio" name="chest_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="chest_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xlt("Palpitation");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_palpitation() ){?>
                <label><input type="radio" name="palpitation" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="palpitation" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xlt("Syncope");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_syncope() ){?>
                <label><input type="radio" name="syncope" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="syncope" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xlt("PND");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_pnd() ){?>
                <label><input type="radio" name="pnd" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="pnd" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xlt("DOE");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_doe() ){?>
                <label><input type="radio" name="doe" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="doe" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>           
          </div>
          <div>
            <?php echo xlt("Orthopnea");?>:
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
            <?php echo xlt("Peripheral");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_peripheal() ){?>
                <label><input type="radio" name="peripheal" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="peripheal" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xlt("Edema");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_edema() ){?>
                <label><input type="radio" name="edema" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="edema" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xlt("Leg Pain/Cramping");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_legpain_cramping() ){?>
                <label><input type="radio" name="legpain_cramping" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="legpain_cramping" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xlt("History of Heart Murmur");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_history_murmur() ){?>
                <label><input type="radio" name="history_murmur" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="history_murmur" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xlt("Arryhthmia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_arryhthmia() ){?>
                <label><input type="radio" name="arryhthmia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="arryhthmia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?> 
          </div>
          <div>
            <?php echo xlt("Heart Problem");?>:
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
      <div id="largest-panel" class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Gastrointestinal");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Dysphagia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dysphagia() ){?>
                <label><input type="radio" name="dysphagia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dysphagia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Heartburn");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_heartburn() ){?>
                <label><input type="radio" name="heartburn" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="heartburn" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Bloating");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_bloating() ){?>
                <label><input type="radio" name="bloating" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="bloating" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Belching");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_belching() ){?>
                <label><input type="radio" name="belching" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="belching" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Flatulence");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_flatulence() ){?>
                <label><input type="radio" name="flatulence" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="flatulence" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Nausea");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_nausea() ){?>
                <label><input type="radio" name="nausea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="nausea" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Vomiting");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_vomiting() ){?>
                <label><input type="radio" name="vomiting" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="vomiting" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Hematemesis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hematemesis() ){?>
                <label><input type="radio" name="hematemesis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hematemesis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_pain() ){?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Food Intolerance");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_food_intolerance() ){?>
                <label><input type="radio" name="food_intolerance" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="food_intolerance" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("H/O Hepatitis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hepatitis() ){?>
                <label><input type="radio" name="hepatitis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hepatitis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Jaundice");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_jaundice() ){?>
                <label><input type="radio" name="jaundice" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="jaundice" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Hematochezia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hematochezia() ){?>
                <label><input type="radio" name="hematochezia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hematochezia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Changed Bowel");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_changed_bowel() ){?>
                <label><input type="radio" name="changed_bowel" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="changed_bowel" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Diarrhea");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_diarrhea() ){?>
                <label><input type="radio" name="diarrhea" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="diarrhea" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Constipation");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Genitourinary General");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Polyuria");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_polyuria() ){?>
                <label><input type="radio" name="polyuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="polyuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Polydypsia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_polydypsia() ){?>
                <label><input type="radio" name="polydypsia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="polydypsia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Dysuria");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dysuria() ){?>
                <label><input type="radio" name="dysuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dysuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Hematuria");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hematuria() ){?>
                <label><input type="radio" name="hematuria" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hematuria" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Frequency");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_frequency() ){?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Urgency");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_urgency() ){?>
                <label><input type="radio" name="urgency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="urgency" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Incontinence");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_incontinence() ){?>
                <label><input type="radio" name="incontinence" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="incontinence" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Renal Stones");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_renal_stones() ){?>
                <label><input type="radio" name="renal_stones" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="renal_stones" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("UTIs");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Genitourinary Male");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Hesitancy");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hesitancy() ){?>
                <label><input type="radio" name="hesitancy" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hesitancy" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Dribbling");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dribbling() ){?>
                <label><input type="radio" name="dribbling" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dribbling" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Stream");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_stream() ){?>
                <label><input type="radio" name="stream" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="stream" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Nocturia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_nocturia() ){?>
                <label><input type="radio" name="nocturia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="nocturia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Erections");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_erections() ){?>
                <label><input type="radio" name="erections" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="erections" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Ejaculations");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Genitourinary Female");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Female G");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_g() ){?>
                <label><input type="radio" name="g" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="g" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Female P");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_p() ){?>
                <label><input type="radio" name="p" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="p" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Female AP");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_ap() ){?>
                <label><input type="radio" name="ap" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="ap" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Female LC");?>:
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
            <?php echo xlt("Menarche");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_mearche() ){?>
                <label><input type="radio" name="mearche" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="mearche" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Menopause");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_menopause() ){?>
                <label><input type="radio" name="menopause" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="menopause" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("LMP");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_lmp() ){?>
                <label><input type="radio" name="lmp" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="lmp" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Frequency");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_f_frequency() ){?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequency" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Flow");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_f_flow() ){?>
                <label><input type="radio" name="f_flow" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="f_flow" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Symptoms");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_f_symptoms() ){?>
                <label><input type="radio" name="f_symptoms" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="f_symptoms" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Abnormal Hair Growth");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_abnormal_hair_growth() ){?>
                <label><input type="radio" name="abnormal_hair_growth" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="abnormal_hair_growth" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("F/H Female Hirsutism/Striae");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Musculoskeletal");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Chronic Joint Pain");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_joint_pain() ){?>
                <label><input type="radio" name="joint_pain" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="joint_pain" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Swelling");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_swelling() ){?>
                <label><input type="radio" name="swelling" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="swelling" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Redness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_redness() ){?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="redness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Warm");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_m_warm() ){?>
                <label><input type="radio" name="m_warm" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="m_warm" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Stiffness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_m_stiffness() ){?>
                <label><input type="radio" name="m_stiffness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="m_stiffness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Muscle");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_muscle() ){?>
                <label><input type="radio" name="muscle" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="muscle" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Aches");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_m_aches() ){?>
                <label><input type="radio" name="m_aches" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="m_aches" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("FMS");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fms() ){?>
                <label><input type="radio" name="fms" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fms" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Arthritis");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Neurologic");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("LOC");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_loc() ){?>
                <label><input type="radio" name="loc" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="loc" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Seizures");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_seizures() ){?>
                <label><input type="radio" name="seizures" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="seizures" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Stroke");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_stroke() ){?>
                <label><input type="radio" name="stroke" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="stroke" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("TIA");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_tia() ){?>
                <label><input type="radio" name="tia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="tia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Numbness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_n_numbness() ){?>
                <label><input type="radio" name="n_numbness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="n_numbness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Weakness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_n_weakness() ){?>
                <label><input type="radio" name="n_weakness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="n_weakness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Paralysis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_paralysis() ){?>
                <label><input type="radio" name="paralysis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="paralysis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Intellectual Decline");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_intellectual_decline() ){?>
                <label><input type="radio" name="intellectual_decline" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="intellectual_decline" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Memory Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_memory_problems() ){?>
                <label><input type="radio" name="memory_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="memory_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Dementia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_dementia() ){?>
                <label><input type="radio" name="dementia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="dementia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Headache");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Skin");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Cancer");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_s_cancer() ){?>
                <label><input type="radio" name="s_cancer" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="s_cancer" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Psoriasis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_psoriasis() ){?>
                <label><input type="radio" name="psoriasis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="psoriasis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Acne");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_s_acne() ){?>
                <label><input type="radio" name="s_acne" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="s_acne" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Other");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_s_other() ){?>
                <label><input type="radio" name="s_other" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="s_other" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Disease");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Psychiatric");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Psychiatric Diagnosis");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_p_diagnosis() ){?>
                <label><input type="radio" name="p_diagnosis" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="p_diagnosis" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Psychiatric Medication");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_p_medication() ){?>
                <label><input type="radio" name="p_medication" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="p_medication" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Depression");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_depression() ){?>
                <label><input type="radio" name="depression" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="depression" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Anxiety");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_anxiety() ){?>
                <label><input type="radio" name="anxiety" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="anxiety" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Social Difficulties");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Endocrine");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Thyroid Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_thyroid_problems() ){?>
                <label><input type="radio" name="thyroid_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="thyroid_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Diabetes");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_diabetes() ){?>
                <label><input type="radio" name="diabetes" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="diabetes" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Abnormal Blood Test");?>:
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
      <div class="col-xs-6 col-sm-4 col-md-3 panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo xlt("Hematologic/Allergic/Immunologic");?></h3>
        </div>
        <div class="panel-body">
          <div>
            <?php echo xlt("Anemia");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_anemia() ){?>
                <label><input type="radio" name="anemia" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="anemia" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("F/H Blood Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_fh_blood_problems() ){?>
                <label><input type="radio" name="fh_blood_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="fh_blood_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Bleeding Problems");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_bleeding_problems() ){?>
                <label><input type="radio" name="bleeding_problems" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="bleeding_problems" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Allergies");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_allergies() ){?>
                <label><input type="radio" name="allergies" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="allergies" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("Frequent Illness");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_frequent_illness() ){?>
                <label><input type="radio" name="frequent_illness" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="frequent_illness" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("HIV");?>:
            <?php foreach ($this->form->get_options() as $value) {
              if($value==$this->form->get_hiv() ){?>
                <label><input type="radio" name="hiv" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
              <?php } else {?>
                <label><input type="radio" name="hiv" value="<?php echo $value;?>"/><?php echo $value;?></label>
              <?php }
            }?>
          </div>
          <div>
            <?php echo xlt("HAI Status");?>:
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
        <input type="button" id="save" class='cp-submit' value="<?php echo xlt('Save'); ?>"> &nbsp;
        <input type="button" id="dontsave" class="deleter cp-negative" value="<?php echo xlt('Cancel'); ?>"> &nbsp;
      </div>
  </body>
</html>
