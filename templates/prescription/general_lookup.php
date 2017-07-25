<html>
<head>
<link rel="stylesheet" href="<?php echo $GLOBALS['css_header'];?>" type="text/css">
<script language="Javascript">
    function my_process () {
        // Pass the variable
        parent.iframetopardiv(document.lookup.drug.value);
        parent.opener.document.prescribe.drug.value = document.lookup.drug.value;
        // Close the window
        window.self.close();
    }

</script>

</head>
<body onload="javascript:document.lookup.drug.focus();">
<div style="" class="drug_lookup" id="newlistitem">
	<form NAME="lookup" ACTION="<?php echo $this->form_action;?>" METHOD="POST" onsubmit="return top.restoreSession()" style="margin:0px">

	<?php if($this->drug_options){?>
        <div>
        <!--{html_options name="drug" values=$drug_values options=$drug_options}-->
        <?php 
            $i=0;
            foreach($this->drug_values as $value)
            {
                $drug_values_res[$i] = $value;
                $i++; 
            }
        ?>
        <select name="drug">
        <?php $i=0;
        foreach ($this->drug_options as $value)
        { ?>            
            <option label="<?php echo $value;?>" value="<?php echo $drug_values_res[$i];?>" ><?php echo $value;?></option>                          
        <?php
        $i++;
        }
        ?>
        </select>
        <br/>
        </div>
        <div>
            <a href="javascript:;" onClick="my_process(); return true;">Select</a> |
            <a href="javascript:;" class="button" onClick="parent.cancelParlookup();">Cancel</a> |
            <a href="<?php echo $this->controller_this;?>" onclick="top.restoreSession()">New Search</a>
        </div>
    <?php } else {	
		echo $this->no_results;
        ?>

		<input TYPE="HIDDEN" NAME="varname" VALUE=""/>
		<input TYPE="HIDDEN" NAME="formname" VALUE=""/>
		<input TYPE="HIDDEN" NAME="submitname" VALUE=""/>
		<input TYPE="HIDDEN" NAME="action" VALUE="Search">
		<div ALIGN="CENTER" CLASS="infobox">
			<input TYPE="TEXT" NAME="drug" VALUE="<?php echo $this->drug;?>"/>
			<input TYPE="SUBMIT" NAME="action" VALUE="Search" class="button"/>
			<input TYPE="BUTTON" VALUE="Cancel" class="button" onClick="parent.cancelParlookup();"/>
		</div>
		<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />

    <?php }?></form>
	</div>
</body>
</html>
