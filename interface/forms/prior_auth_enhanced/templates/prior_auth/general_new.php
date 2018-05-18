<html>
<head>
<?php echo html_header_show();?>

<!-- other supporting javascript code -->
<script type="text/javascript" src="<?php echo $this->form_action;?>/library/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->form_action;?>/library/textformat.js"></script>



<!-- pop up calendar -->
<style type="text/css">@import url(<?php echo $this->form_action;?>/library/dynarch_calendar.css);</style>
<script type="text/javascript" src="<?php echo $this->form_action;?>/library/dynarch_calendar.js"></script>
<script type="text/javascript" src="<?php echo $this->form_action;?>/library/dynarch_calendar_en.js"></script>
<script type="text/javascript" src="<?php echo $this->form_action;?>/library/dynarch_calendar_setup.js"></script>

<style type="text/css" title="mystyles" media="all">
    td {
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
    .title {
        font-family: sans-serif;
        font-size: 12pt;
        font-weight: bold;
        text-decoration: none;
        color: #000000;
    }

    .form_text{
        font-family: sans-serif;
        font-size: 9pt;
        text-decoration: none;
        color: #000000;
    }
</style>
<script>
function showHint(str) {
    if (str.length == 0) { 
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}

 function validate() {
            var EnteredDate = document.getElementById("auth_from").value; // get the auth from date

            var today = document.getElementById("auth_to").value;

            if (EnteredDate > today) {
                alert("FROM: date is greater than TO: date ");
				return false;
            }
            var AuthReq = document.getElementById("noAuthReq").checked;	
            var Claims = document.getElementById("claims").value;
            //var ClearReq = document.getElementById("clear_req").checked; // Not needed will delete
			var n = Claims.length;
			
            if(document.getElementById("noAuthReq").checked && n === 0){
			    
			      alert("Please fill in number of claims");
			      return false;
				 
            }
         
        }

</script>

</head>
<body bgcolor="<?php echo $this->style['BGCOCLOR2'];?>">

<p><span class="title">Prior Authorization Form</span></p>
<form name="prior_auth_enhanced" method="post" action="<?php echo $this->form_action;?>/interface/forms/prior_auth_enhanced/save.php">
<table>
<tr>
    <td align="right">No Auth# Req:</td><td><input type="radio" name="not_req" value="Yes" id="noAuthReq" title="Select when No Authorization Number Required" 
    <?php if($this->NoAuth == "Yes") {?> checked ><?php } if($this->NoAuth == "Yes") { ?><br><input type="radio" name="not_req" id="clear_req" value="" 
           title="Click here to clear the No Auth need flag" onclick = 'clearThis("claims")'>Clear</td><?php }?>
</tr>
<tr>
    <td align="right">No Auth #Claims:</td><td><input type="text" size="5" name="auth_for" id="claims" value="<?php echo $prior_auth->get_auth_for();?>" title="Set the number of times no auth can be used">

</tr>
<tr>
	<td align="right">Auth #:</td><td><input type="text" size="35" name="prior_auth_number" value="<?php echo $prior_auth->get_prior_auth_number();?>"></td>

</tr>
<tr>
    <td align="right">Description:</td><td><input type="text" size="55" name="desc" value="<?php echo $prior_auth->get_desc();?>"></td>
</tr>

<tr>
    <td align="right">Auth Length:</td><td><label>From: </label>
	   <input type='text' size='10' name="auth_from" id="auth_from"
    value="<?php echo $prior_auth->get_auth_from();?>"
	title="<?php echo xl('yyyy-mm-dd','e');?>"
    onkeyup="datekeyup(this,mypcc)" onblur="dateblur(this,mypcc)" readonly/>
   <img src="../../pic/show_calendar.gif" align="absbottom" width="24" height="22"
    id="img_auth_from" border="0" alt="[?]" style="cursor:pointer;cursor:hand"
	title="<?php echo xl('Click here to choose a date','e');?>"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label>To: </label>
	   <input type='text' size='10' name='auth_to' id="auth_to"
    value="<?php echo $prior_auth->get_auth_to();?>"
	title="<?php echo xl('yyyy-mm-dd','e'); ?>"
    onkeyup="datekeyup(this,mypcc)" onblur="dateblur(this,mypcc)" readonly/>
   <img src="../../pic/show_calendar.gif" align="absbottom" width="24" height="22"
    id="img_auth_to" border="0" alt="[?]" style="cursor:pointer;cursor:hand"
	title="<?php echo xl('Click here to choose a date','e');?>"/>
	

        
    </td>
</tr>
<tr>
    <td align="right"># Used: </td><td><input type="text" size="5" name="used" value="<?php echo $prior_auth->get_used();?>" title="Read Only" readonly></td>
</tr>
<tr>
    <td align="right">Auth Contact:</td><td><input type="text" size="25" name="auth_contact" value="<?php echo $prior_auth->get_auth_contact();?>"></td>
</tr>
<tr>
    <td align="right">Auth Phone:</td><td> <input type="text" size="15" name="auth_phone" value="<?php echo $prior_auth->get_auth_phone();?>">  </td>
</tr>
<tr>
    <td align="right">Code:</td><td> 
	<input type="text" size="5" name="code1" value="<?php echo $prior_auth->get_code1();?>">
	<input type="text" size="5" name="code2" value="<?php echo $prior_auth->get_code2();?>">
	<input type="text" size="5" name="code3" value="<?php echo $prior_auth->get_code3();?>">
	<input type="text" size="5" name="code4" value="<?php echo $prior_auth->get_code4();?>">
	<input type="text" size="5" name="code5" value="<?php echo $prior_auth->get_code5();?>">
	<input type="text" size="5" name="code6" value="<?php echo $prior_auth->get_code6();?>">
	<input type="text" size="5" name="code7" value="<?php echo $prior_auth->get_code7();?>"></td>

</tr>
<tr>
	
</tr>
<tr>
	<td align="right">Notes:</td><td colspan="2"><textarea name="comments" value="<?php echo $prior_auth->get_comments();?>" wrap="virtual" cols="75" rows="8"><?php echo $prior_auth->get_comments();?></textarea></td>
</tr>
<tr>
	<td align="right">Auth Alert:</td><td><input type="text" size="5" value="" />
        <select><option name="units" value="" /></option>
                <option name="units" value="days" <?php if($this->alert == "days") { ?>selected <?php } ?>/>Days</option>
                <option name="units" value="units" <?php if($this->alert == "units") { ?>selected <?php } ?>/>Units</option> 
                <option name="units" value="open" <?php if($this->alert == "open") { ?>selected <?php } ?>/>Open Auth</option> 
        </select>	</td>
</tr>
<tr>
    <td align="right">Supervisor Override:</td><td><input type="radio" name="override" value="1" 
    <?php if($this->override == 1) { ?>checked <?php } ?>/><?php if($this->override == 1){ ?><input type="radio" name="override" value="" />Clear <?php }?>
</tr>
<tr>
	<td align="right">Archive this auth:</td><td><input type="radio" name="archived" value="1" />
</tr>
<tr>
	<td><br><br><input type="submit" name="Submit" value="Save Form" onclick="return validate();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->dont_save_link;?> " class="link">[Don't Save]</a></td>
</tr>
<?php if($this->view != true) ?>

</table>
<input type="hidden" name="id" value="<?php echo $prior_auth->get_id();?>" />
<input type="hidden" name="activity" value="<?php echo $prior_auth->get_activity();?>">
<input type="hidden" name="pid" value="<?php echo $prior_auth->get_pid();?>">
<input type="hidden" name="process" value="true">
</form>
</body>
<script language="javascript">
/* required for popup calendar */
/*Calendar.setup({inputField:"dob", ifFormat:"%Y-%m-%d", button:"img_dob"});*/
Calendar.setup({inputField:"auth_from", ifFormat:"%Y-%m-%d", button:"img_auth_from"});
Calendar.setup({inputField:"auth_to", ifFormat:"%Y-%m-%d", button:"img_auth_to"});

 function clearThis(target){
   target = document.getElementById(target);
   target.value = "";
 }
</script>

</html>