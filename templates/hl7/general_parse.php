<html>
<head>
</head>

<body >
    <form name="prescribe" method="post" action="<?php echo $this->form_action;?>" onsubmit="return top.restoreSession()">
<table class="table">
<!--<tr><td>Example HL7 data<td></tr>
<tr><td>MSH|^~\&|ADT1|CUH|LABADT|CUH|198808181127|SECURITY|ADT^A01|MSG00001|P|2.3|
EVN|A01|198808181122||
PID|||PATID1234^5^M11||RYAN^HENRY^P||19610615|M||C|1200 N ELM STREET^^GREENSBORO^NC^27401-1020|GL|(919)379-1212|(919)271-3434 ||S||PATID12345001^2^M10|123456789|987654^NC|
NK1|JOHNSON^JOAN^K|WIFE||||||NK^NEXT OF KIN
PV1|1|I|2000^2053^01||||004777^FISHER^BEN^J.|||SUR||||ADM|A0|</td></tr>-->
<tr>
<td colspan="2"><b><?php echo xlt("Paste HL7 Data");?></b></td>
</tr>
<tr height="25">
	<td colspan="2">
        <textarea class="form-control" rows="10" wrap="virtual" cols="70" name="hl7data"></textarea>
	</td></tr>
	<tr class="text"><td colspan="2">
	<a href="javascript:document.forms[0].reset();" class="css_button cp-negative"><span>Clear HL7 Data</span></a>
                    <a href="javascript:document.forms[0].submit();" class="css_button cp-submit" ><span>Parse HL7</span></a>
	</td>

<?php if($this->hl7_message_err)?>
	<tr height="25"><td colspan="2"><?php echo $hl7_message_err;?></td></tr>
        
 <?php if($this->hl7_array){?>       
     <tr class="text"><td colspan="2">
	<table class="table">
                    <?php foreach ($this->hl7_array as $hl7item => $hl7key) {?>
                        <tr height="25"><td colspan="3"><?php echo $hl7item;?></td></tr>                      
                       
                        <?php foreach ($hl7item as $segment_val=> $segment_name) {?>
                            <tr><td>&nbsp;</td><td><?php echo $segment_name;?> : </td><td><?php echo $segment_val;?></td></tr>
                            
                        <?php }?>
                    <?php }?>   
                     </table>
	</td>
     </tr>
 <?php }?>
     
 <input type="hidden" name="process" value="<?php echo self::PROCESS; ?>" />
</table>
</form>

</body>
</html>                    
                                    
                                


 
 