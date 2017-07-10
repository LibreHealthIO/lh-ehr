<html>
<head>
<link rel="stylesheet" href="<?php echo $GLOBALS['css_header']; ?>" type="text/css">
<script type="text/javascript" src="<?php echo $this->web_root;?>/library/js/jquery-1.2.2.min.js"></script>


<style type="text/css" title="mystyles" media="all">
.inactive {
  color:#777777;
}
</style>

<script language="javascript">

function changeLinkHref(id,addValue,value) {
    var myRegExp = new RegExp(":" + value + ":");
    if (addValue){ //add value to href
        document.getElementById(id).href += ':' + value + ':';
    }
    else { //remove value from href
       document.getElementById(id).href = document.getElementById(id).href.replace(myRegExp,'');
    }
}

function changeLinkHref_All(id,addValue,value) {
    var myRegExp = new RegExp(":" + value + ":");
    if (addValue){ //add value to href
        document.getElementById(id).href += ':' + value + ':';
    }
    else { //remove value from href
        document.getElementById(id).href = document.getElementById(id).href.replace(myRegExp,'');
		// TajEmo Work By CB 2012/06/14 02:17:16 PM remove the target change 
    //document.getElementById(id).target = '';
    }
}

function Check(chk) {
    var len=chk.length;
    if (len==undefined) {chk.checked=true;}
    else {
        for (pr = 0; pr < chk.length; pr++){
            if($(chk[pr]).parents("tr.inactive").length==0)
                {
                    chk[pr].checked=true;
                    changeLinkHref_All('multiprint',true,chk[pr].value);
                    changeLinkHref_All('multiprintcss',true, chk[pr].value);
                    changeLinkHref_All('multiprintToFax',true, chk[pr].value);
                }
        }
    }
}

function Uncheck(chk) {
    var len=chk.length;
    if (len==undefined) {chk.checked=false;}
    else {
        for (pr = 0; pr < chk.length; pr++){
            chk[pr].checked=false;
            changeLinkHref_All('multiprint',false,chk[pr].value);
            changeLinkHref_All('multiprintcss',false, chk[pr].value);
            changeLinkHref_All('multiprintToFax',false, chk[pr].value);
        }
    }
}

var CheckForChecks = function(chk) {
    // Checks for any checked boxes, if none are found than an alert is raised and the link is killed
    if (Checking(chk) == false) { return false; }
    return top.restoreSession();
};

function Checking(chk) {
    var len=chk.length;
	var foundone=false;
	 
    if (len==undefined) {
			if (chk.checked == true){
				foundone=true;
			}
	} 
	else {
		for (pr = 0; pr < chk.length; pr++){
			if (chk[pr].checked == true) {
				foundone=true;
			}
		}
	}	
	if (foundone) {
		return true;
	} else {
		alert("<?php echo xl('Please select at least one prescription!');?>");
		return false;
	}
}

$(document).ready(function(){
  $(":checkbox:checked").each(function () { 
      changeLinkHref('multiprint',this.checked, this.value);
      changeLinkHref('multiprintcss',this.checked, this.value);
      changeLinkHref('multiprintToFax',this.checked, this.value);
  });
})

</script>


</head>
<body class="body_top">

<?php if($this->prescriptions){?>
<span class="title"><b><?php echo xl('List');?></b></span>

<div id="prescription_list">

<form name="presc">

<div id="print_links">
    <table width="100%">
        <tr>
            <td align="left">
                <table>
                    <tr>
                        <td>
                            <a id="multiprint" href="<?php echo $this->top_action;?>multiprint&id=<?php echo $this->printm;?>" onclick="top.restoreSession()" class="css_button"><span><?php echo xl('Download');?> (<?php echo xl('PDF');?>)</span></a>
                        </td>
                        <td>
                          <!-- TajEmo work by CB 2012/06/14 02:16:32 PM target="_script" opens better -->
                            <a target="_script" id="multiprintcss" href="<?php echo $this->top_action;?>multiprintcss&id=<?php echo $this->printm;?>" onclick="top.restoreSession()" class="css_button"><span><?php echo xl('View Printable Version');?> (<?php echo xl('HTML');?>)</span></a>
                        </td>
                        <td style="border-style:none;">
                            <a id="multiprintToFax" href="<?php echo $this->top_action;?>multiprintfax&id=<?php echo $this->printm;?>" onclick="top.restoreSession()" class="css_button"><span><?php echo xl('Download');?> (<?php echo xl('Fax');?>)</span></a>
                        </td>
                    </tr>
                </table>
            </td>
            <td align="right">
                <table>
                <tr>
                    <td>
                        <a href="#" class="small" onClick="Check(document.presc.check_list);"><span><?php echo xl('Check All');?></span></a> |
                        <a href="#" class="small" onClick="Uncheck(document.presc.check_list);"><span><?php echo xl('Clear All');?></span></a>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
    </table>
</div>


<table width="100%" class="showborder_head" cellspacing="0px" cellpadding="2px">
    <tr> 
       <!-- TajEmo Changes 2012/06/14 02:01:43 PM by CB added Heading for checkbox column -->   
        <th width="8px">&nbsp;</th>
		    <th width="8px">&nbsp;</th>
        <th width="180px"><?php echo xl('Drug');?></th>
        <th><?php echo xl('Created');?><br /><?php echo xl('Changed');?></th>
        <th><?php echo xl('Dosage');?></th>
        <th><?php echo xl('Qty');?>.</th>
        <th><?php echo xl('Unit');?></th>
        <th><?php echo xl('Provider');?></th>
    </tr>

	<?php foreach($this->prescriptions as $prescription){?> 
  <!-- TajEmo Changes 2012/06/14 02:03:17 PM by CB added cursor:pointer for easier user understanding -->  
  <tr style="cursor:pointer" id="<?php echo $prescription->id;?>" class="showborder onescript 
    <?php if($prescription->active <= 0){ ?> inactive <?php }?>" title="<?php echo xl('Click to view/edit');?>">
	 <td align="center"> 
      <input class="check_list" id="check_list" type="checkbox" value="<?php echo $prescription->id;?>" 
      <?php if($prescription->encounter == $prescription->get_encounter() 
      && $prescription->active > 0){?>checked="checked" <?php }?> onclick="changeLinkHref('multiprint',this.checked, this.value);
      changeLinkHref('multiprintcss',this.checked, this.value);changeLinkHref('multiprintToFax',this.checked, this.value)" 
      title="<?php echo xl('Select for printing');?>">
    </td>
	<?php if($prescription->erx_source==0){?>
    <td class="editscript"  id="<?php echo $prescription->id;?>">
        <a class='editscript css_button_small' id='<?php echo $prescription->id;?>' 
            href="controller.php?prescription&edit&id=<?php echo $prescription->id;?>" style="margin-top:-2px"><span>
                <?php echo xl('Edit');?></span></a>
      <!-- TajEmo Changes 2012/06/14 02:02:22 PM by CB commented out, to avoid duplicate display of drug name
        {if $this->prescription->active > 0}<b>{/if}{$this->prescription->drug}{if $this->prescription->active > 0}</b>{/if}&nbsp;
      --> 
    </td>
	<td class="editscript"  id="<?php echo $prescription->id;?>">
	<?php if($prescription->active > 0){?><b><?php }?>
        <?php echo $prescription->drug;?>
        <?php if($prescription->active > 0){?></b><?php }?>&nbsp;
    </td>
    <?php } else {?>
    <td id="<?php echo $prescription->id;?>">
	<?php if($prescription->active > 0){?><b><?php }?>
    <?php echo $prescription->drug;?>
    <?php if($prescription->active > 0){?></b><?php }?>&nbsp;
    </td>
    <?php }?>
    <td id="<?php echo $prescription->id;?>">
      <?php echo $prescription->date_added;?><br />
      <?php echo $prescription->date_modified;?>&nbsp;
    </td>
    <td id="<?php echo $prescription->id;?>">
      <?php echo $prescription->get_dosage_display();?> &nbsp;
    </td>
	<?php if($prescription->erx_source==0){?>
    <td class="editscript" id="<?php echo $prescription->id;?>">
      <?php echo $prescription->quantity;?> &nbsp;
    </td>
    <?php } else {?>
	<td id="<?php echo $prescription->id;?>">
      <?php echo $prescription->quantity;?> &nbsp;
    </td>
    <?php }?>
    <td id="<?php echo $prescription->id;?>">
       <?php echo $prescription->get_size();
             echo $prescription->get_unit_display();?>
       &nbsp;
    </td>
    <td id="<?php echo $prescription->id;?>">
      <?php echo $prescription->provider->get_name_display();?>&nbsp;
    </td>
  </tr>
    <?php }?>
</table>

</form>
</div>

<?php }else {?>
<div class="text" style="margin-top:10px"><?php echo xl('There are currently no prescriptions');?>.</div>
<?php }?>

</body>

<script language='JavaScript'>

$(document).ready(function(){
$("#multiprint").click(function() { return CheckForChecks(document.presc.check_list); });
$("#multiprintcss").click(function() { return CheckForChecks(document.presc.check_list); });
$("#multiprintToFax").click(function() { return CheckForChecks(document.presc.check_list); });
$(".editscript").click(function() { ShowScript(this); });
$(".onescript").mouseover(function() { $(this).children().toggleClass("highlight"); });
$(".onescript").mouseout(function() { $(this).children().toggleClass("highlight"); });
});

var ShowScript = function(eObj) {
    top.restoreSession();
    objID = eObj.id;
    document.location.href="<?php echo $this->web_root;?>/controller.php?prescription&edit&id="+objID;
    return true;
};

</script>

</html>