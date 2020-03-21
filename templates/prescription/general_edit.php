<html>
<head>

<link rel="stylesheet" href="<?php echo $this->css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $this->web_root;?>/interface/themes/jquery.autocomplete.css" type="text/css">

<style type="text/css">
    .text {
        font-size: 9pt;
    }
</style>

<script language="Javascript">

    function my_process () {
      // Pass the variable
      opener.document.prescribe.drug.value = document.lookup.drug.value;
      // Close the window
      window.self.close();
    }

</script>


<!---Gen Look up-->
<script type="text/javascript" src="<?php echo $this->web_root;?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $this->web_root;?>/library/js/jquery-1.2.2.min.js"></script>
<script type="text/javascript" src="<?php echo $this->web_root;?>/library/js/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="<?php echo $this->web_root;?>/library/js/jquery.dimensions.pack.js"></script>
<script type="text/javascript" src="<?php echo $this->web_root;?>/library/js/jquery.autocomplete.pack.js"></script>

<script language='JavaScript'>

 // This holds all the default drug attributes.

 var drugopts = [<?php echo $this->drug_attributes;?>];


 // Helper to choose an option from its value.
 function selchoose(sel, value) {
  var o = sel.options;
  for (i = 0; i < o.length; ++i) {
   o[i].selected = (o[i].value == value);
  }
 }

 // Fill in default values when a drop-down drug is selected.
 function drugselected(sel) {
  var f = document.forms[0];
  var i = f.drug_id.selectedIndex - 1;
  if (i >= 0) {
   var d = drugopts[i];
   f.drug.value = d[0];
   selchoose(f.form, d[1]);
   f.dosage.value = d[2];
   f.size.value = d[3];
   selchoose(f.unit, d[4]);
   selchoose(f.route, d[5]);
   selchoose(f.interval, d[6]);
   selchoose(f.substitute, d[7]);
   f.quantity.value = d[8];
   f.disp_quantity.value = d[8];
   selchoose(f.refills, d[9]);
   f.per_refill.value = d[10];
  }
 }

 // Invoke the popup to dispense a drug.
 function dispense() {
  var f = document.forms[0];
  dlgopen('interface/drugs/dispense_drug.php' +
   '?drug_id=<?php echo $this->prescription->get_drug_id();?>' +
   '&prescription=' + f.id.value +
   '&quantity=' + f.disp_quantity.value +
   '&fee=' + f.disp_fee.value,
   '_blank', 400, 200);
 }

 function quantityChanged() {
  var f = document.forms[0];
  f.per_refill.value = f.quantity.value;
  if (f.disp_quantity) {
   f.disp_quantity.value = f.quantity.value;
  }
 }

</script>

</head>
<body class="body_top">

<form name="prescribe" id="prescribe" method="post" action="<?php echo $this->form_action;?>">
<table>
    <tr><td class="title"><font><b>Add/Edit</b></font>&nbsp;</td>
    <td><a href="#" onclick="submitfun();" class="css_button_small"><span>Save</span></a>
    <?php if($this->drug_array_values){?>
    &nbsp; &nbsp; &nbsp; &nbsp;
    <?php if($this->prescription->get_refills() >= $this->prescription->get_dispensation_count()){?>
    <input type="submit" name="disp_button" value="Save and Dispense" />
    <input type="text" name="disp_quantity" size="2" maxlength="10" value="<?php echo $this->disp_quantity;?>" />
    units, $
    <input type="text" name="disp_fee" size="5" maxlength="10" value="<?php echo $disp_fee;?>" />
        <?php }else{?>&nbsp;
    prescription has reached its limit of <?php echo $this->prescription->get_refills();?> refills.
        <?php }
        }?>   
         <a class='css_button_small' href="controller.php?prescription&list&id=<?php echo $this->prescription->patient->id;?>"><span>
         Back</span></a>
</td></tr>
</table>

<?php if($GLOBALS['enable_amc_prompting']) {?> 
  <div style='float:right;margin-right:25px;border-style:solid;border-width:1px;'>
    <div style='float:left;margin:5px 5px 5px 5px;'>      
      <?php $amcCollectResult1 = amcCollect('e_prescribe_amc',$this->prescription->patient->id,'prescriptions',$this->prescription->id);
         if(!is_array($amcCollectResult1)){?>
        <input type="checkbox" id="escribe_flag" name="escribe_flag">
      <?php }else {?>
        <input type="checkbox" id="escribe_flag" name="escribe_flag" checked>
      <?php }?>
      <span class="text">E-Prescription?</span><br>
     
      <?php $amcCollectResult2 = amcCollect('e_prescribe_chk_formulary_amc',$this->prescription->patient->id,'prescriptions',$this->prescription->id);
         if(!is_array($amcCollectResult2)){?>
        <input type="checkbox" id="checked_formulary_flag" name="checked_formulary_flag">
      <?php }else {?>
        <input type="checkbox" id="checked_formulary_flag" name="checked_formulary_flag" checked>
      <?php }?>
      <span class="text"><?php echo xl('Checked Drug Formulary?')?></span><br>
      
      <?php 
         $amcCollectResult3 = amcCollect('e_prescribe_cont_subst_amc',$this->prescription->patient->id,'prescriptions',$this->prescription->id);
         if(!is_array($amcCollectResult3)){?>
        <input type="checkbox" id="controlled_substance_flag" name="controlled_substance_flag">
      <?php }else {?>
        <input type="checkbox" id="controlled_substance_flag" name="controlled_substance_flag" checked>
      <?php }?>
      <span class="text"><?php echo xl('Controlled Substance?')?></span><br>

    </div>
  </div>
<?php } ?>

<table CELLSPACING="0" CELLPADDING="3" BORDER="0">
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Currently Active');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >
    <input type="checkbox" name="active" value="1"<?php if($this->prescription->get_active() > 0){?> checked <?php }?> />
  </td>
</tr>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Starting Date');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >
    <?php // {html_select_date start_year="-10" end_year="+5" time=$this->prescription->start_date prefix="start_date_"} 
          ?>
    <select name="start_date_Month">
    <?php for($i=1;$i<=12;$i++) {?>
        <option value="<?php echo $i;?>" ><?php echo $i;?></option>
    <?php }?>     
    </select>
    <select name="start_date_Day">
    <?php for($i=1;$i<=31;$i++) {?>
        <option value="<?php echo $i;?>" ><?php echo $i;?></option>
    <?php }?>     
    </select>
    <select name="start_date_Year">
    <?php for($i=$this->prescription->start_date-10;$i<=$this->prescription->start_date+5;$i++) {
    if($i==$this->prescription->start_date){?>
        <option value="<?php echo $i;?>" selected="selected"><?php echo $i;?></option>
    <?php } else{?>
        <option value="<?php echo $i;?>" ><?php echo $i;?></option>
    <?php }}?>     
    </select>
  </td>
</tr>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Provider');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >    
        <select name="provider_id">            
            <?php foreach ($this->prescription->provider->utility_provider_array() as $key => $value) 
                { 
                if($key==$this->prescription->provider->get_id()) { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
                <?php } else { ?>
                <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
                <?php }                
                } ?>            
        </select>        
        <input type="hidden" name="patient_id" value="<?php echo $this->prescription->patient->id;?>" />
  </td>
</tr>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Drug');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >
            <input type="input" size="20" name="drug" id="drug" value="<?php echo $this->prescription->drug;?>"/>
            <a href="javascript:;" id="druglookup" class="small" name="B4" 
            onclick="$('#hiddendiv').show(); 
            document.getElementById('hiddendiv').innerHTML='&lt;iframe src=&quot;controller.php?prescription&amp;lookup&amp;drug=&quot; width=&quot;100%&quot;height=&quot;52&quot; scrolling=&quot;no&quot; frameborder=&quot;no&quot;&gt;&lt;/iframe&gt;'">
            (<?php echo xl('click here to search');?>)</a>
            <div id="hiddendiv" style="display:none">&nbsp;</div>
  </td>
</tr>
<?php if(is_array($this->drug_array_values)){?>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" >&nbsp; <?php echo xl('in-house');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >
    <select name="drug_id" onchange="drugselected(this)">
        <?php $i=0;
         foreach($this->drug_array_output as $value)
         { 
            if($key==$this->prescription->get_drug_id()) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $drug_array_labels[$i];?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $drug_array_labels[$i];?>" ><?php echo $value;?></option>
            <?php }
            $i++;                
        } ?> 
    </select>

  </td>
</tr>
<?php }?>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Quantity');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >
    <input TYPE="TEXT" NAME="quantity" id="quantity" SIZE="10" MAXLENGTH="31"
     VALUE="<?php echo $this->prescription->quantity;?>"
     onchange="quantityChanged()" />
  </td>
</tr>
<?php if($this->simplified_prescriptions && !$this->prescription->size){?>
<tr style='display:none;'>
<?php } else {?>
<tr>
<?php }?>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Medicine Units');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >
    <input TYPE="TEXT" NAME="size" id="size" SIZE="11" MAXLENGTH="10" VALUE="<?php echo $this->prescription->size;?>"/>
    <select name="unit" id="unit">        
        <?php foreach ($this->prescription->unit_array as $key => $value)
        { 
            if($key==$this->prescription->unit) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
        ?>
    </select>
  </td>
</tr>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Take');?></td>
  <td COLSPAN="2" class="text" ALIGN="LEFT" VALIGN="MIDDLE" >
<?php if($this->simplified_prescriptions && !$this->prescription->form && !$this->prescription->route && !$this->prescription->interval){?>
    <input TYPE="text" NAME="dosage" id="dosage" SIZE="30" MAXLENGTH="100" VALUE="<?php echo $this->prescription->dosage;?>" />
    <input type="hidden" name="form" id="form" value="0" />
    <input type="hidden" name="route" id="route" value="0" />
    <input type="hidden" name="interval" id="interval" value="0" />
<?php } else {?>
    <input TYPE="TEXT" NAME="dosage" id="dosage" SIZE="2" MAXLENGTH="10" VALUE="<?php echo $this->prescription->dosage;?>"/> <?php echo xl('in');?>
    <select name="form" id="form">
        <?php foreach ($this->prescription->form_array as $key => $value)
        { 
            if($key==$this->prescription->form) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
        ?>
    </select>
    
    <select name="route" id="route">        
        <?php foreach ($this->prescription->route_array as $key => $value)
        { 
            if($key==$this->prescription->route) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
        ?>
    </select>
    <select name="interval" id="interval">        
        <?php foreach ($this->prescription->interval_array as $key => $value)
        { 
            if($key==$this->prescription->interval) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
        ?>
    </select>
<?php }?>
  </td>
</tr>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Refills');?></td>
  <td COLSPAN="2" class="text" ALIGN="LEFT" VALIGN="MIDDLE" >    
        <select name="refills">
        <?php foreach ($this->prescription->refills_array as $key => $value)
        { 
            if($key==$this->prescription->refills) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
        ?>
        </select>
<?php if($this->simplified_prescriptions){?>
    <input TYPE="hidden" ID="per_refill" NAME="per_refill" VALUE="<?php echo $this->prescription->per_refill;?>" />
<?php }else {?>
    &nbsp; &nbsp; # <?php echo xl('of tablets');?>:
    <input TYPE="TEXT" ID="per_refill" NAME="per_refill" SIZE="2" MAXLENGTH="10" VALUE="<?php echo $this->prescription->per_refill;?>" />
<?php }?>
  </td>
</tr>
<tr>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Notes');?></td>
  <td COLSPAN="2" class="text" ALIGN="LEFT" VALIGN="MIDDLE" >
  <textarea name="note" cols="30" rows="2" wrap="virtual"><?php echo $this->prescription->note;?></textarea>
  </td>
</tr>
<tr>
<?php if($this->weight_loss_clinic){?>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Substitution');?></td>
  <td COLSPAN="2" ALIGN="LEFT" VALIGN="MIDDLE" >    
    <select name="substitute">
    <?php foreach ($this->prescription->substitute_array as $key => $value)
        { 
            if($key==$this->prescription->substitute) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
        ?>
    </select>
  </td>
<?php }else {?>
  <td COLSPAN="1" class="text" ALIGN="right" VALIGN="MIDDLE" ><?php echo xl('Add to Medication List');?></td>
  <td COLSPAN="2" class="text" ALIGN="LEFT" VALIGN="MIDDLE" >    
    <?php foreach ($this->prescription->medication_array as $value) {
        if($value==$this->prescription->medication){?>
        <label><input type="radio" name="medication" value="<?php echo $value;?>" checked="checked" /><?php echo $value;?></label>
        <?php } else {?>
        <label><input type="radio" name="medication" value="<?php echo $value;?>"/><?php echo $value;?></label>
        <?php }
    }?> 
    &nbsp; &nbsp;    
    <select name="substitute">
    <?php foreach ($this->prescription->substitute_array as $key => $value)
        { 
            if($key==$this->prescription->substitute) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
    ?>
    </select>
  </td>
<?php }?>
</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->prescription->id;?>" />
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
<script language='JavaScript'>
<?php echo $this->ending_javascript;?>
</script>
</form>

<!-- for the fancy jQuery stuff -->
<script type="text/javascript">

function submitfun() {
    top.restoreSession();
    if (CheckForErrors(this)) {
        document.forms["prescribe"].submit();
    }
    else {
        return false;
    }
}

function iframetopardiv(string){
    var name=string
    document.getElementById('drug').value=name;
    $("#hiddendiv").html( "&nbsp;" );
    $('#hiddendiv').hide();
}

function cancelParlookup () {
    $('#hiddendiv').hide();
    $("#hiddendiv").html( "&nbsp;" );
}

$().ready(function() {
    $("#drug").autocomplete('library/ajax/prescription_drugname_lookup.php',
                            {
                            width: 200,
                            scrollHeight: 100,
                            selectFirst: true
                            });
    $("#drug").focus();
    $("#prescribe").submit(function() { return CheckForErrors(this) });
    $("#druglookup").click(function() { DoDrugLookup(this) });
});


// pop up a drug lookup window with the value of the drug name, if we have one
function DoDrugLookup(eObj) {
    drugname = "";
    if ($('#drug').val() != "") { drugname = $('#drug').val(); }
    $("#druglist").css('display','block');
    document.lookup.action='controller.php?prescription&edit&id=&pid=<?php echo $this->prescription->patient->id;?>&drug=sss'+drugname;
    drugPopup = window.open('controller.php?prescription&lookup&drug='+drugname, 'drugPopup', 'width=400,height=50,menubar=no,titlebar=no,left = 825,top = 400');
    drugPopup.opener = self;
    return true;
}


// check the form for required fields before submitting
var CheckForErrors = function(eObj) {
    // REQUIRED FIELDS
    if (CheckRequired('drug') == false) { return false; }
    //if (CheckRequired('quantity') == false) { return false; }
    //if (CheckRequired('unit') == false) { return false; }
    //if (CheckRequired('size') == false) { return false; }
    //if (CheckRequired('dosage') == false) { return false; }
    //if (CheckRequired('form') == false) { return false; }
    //if (CheckRequired('route') == false) { return false; }
    //if (CheckRequired('interval') == false) { return false; }

    return top.restoreSession();
};

function CheckRequired(objID) {

    // for text boxes
    if ($('#'+objID).is('input')) {
        if ($('#'+objID).val() == "") {
            alert("<?php echo xl('Missing a required field');?>");
            $('#'+objID).css("backgroundColor", "pink");
            return false;
        }
    }

    // for select boxes
    if ($('#'+objID).is('select')) {
        if ($('#'+objID).val() == "0") {
            alert("<?php echo xl('Missing a required field');?>");
            $('#'+objID).css("backgroundColor", "pink");
            return false;
        }
    }

    return true;
}

</script>


</html>
