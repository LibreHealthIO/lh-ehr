<html>
<head>
<link rel="stylesheet" href="<?php echo $this->css_header;?>" type="text/css">
</head>
<body class="body_top">

<span class="title"><b>Send</b></span>
<div style="margin-top:10px;">
    <?php if($this->process_result){
    echo $this->process_result;  }?>        
    <br/>    

    <div style="float:left">
        <form name="genform1" method="post" action="<?php echo $this->top_action;?>send&id=<?php echo $this->prescription->id;?>" target="_new" onsubmit="return top.restoreSession()">
        <input type="submit" name="submit" value="Download PDF " style="width:100;font-size:9pt;"/>
        <input type="hidden" name="process" value="<?php echo self::PROCESS;?>"  />
        </form>
    </div>

    <div style="float:left">
        <form name="send_prescription" method="post" action="<?php echo $this->top_action;?>send&id=<?php echo $this->prescription->id;?>" target="_new" onsubmit="return top.restoreSession()">
        <input type="submit" name="submit" value="View Printable HTML" style="width:100;font-size:9pt;"/>
        <input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
        </form>
    </div>

    <div style="float:none">
        <form name="send_prescription" method="post" action="<?php echo $this->top_action;?>send&id=<?php echo $this->prescription->id;?>&print_to_fax=true" target="_new" onsubmit="return top.restoreSession()">
        <input type="submit" name="submit" value="Download Fax" style="width:100;font-size:9pt;"/>
        <input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
        </form>
    </div>

    <div>
        <form name="send_prescription" method="post" action="<?php echo $this->top_action;?>send&id=<?php echo $this->prescription->id;?>" onsubmit="return top.restoreSession()">
        <input type="submit" name="submit" value="Email" style="width:100;font-size:9pt;" />
        <input type="text" name="email_to"  size="25" value="<?php echo $this->prescription->pharmacy->get_email();?>">
        <br/>
        <input type="submit" name="submit" value="Fax" style="width:100;font-size:9pt;"/>
        <input type="text" name="fax_to"  size="25" value="<?php echo $this->prescription->pharmacy->get_fax();?>" >
        <input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
        </form>
        <form name="send_prescription" method="post" action="<?php echo $this->top_action;?>send&id=<?php echo $this->prescription->id;?>" target="_new" onsubmit="return top.restoreSession()">
        <input type="submit" name="submit" value="Auto Send" style="width:100;font-size:9pt;" /> 
        
        <select name="pharmacy_id">        
        <?php foreach ($this->prescription->pharmacy->utility_pharmacy_array() as $key => $value)
        { 
            if($key==$this->prescription->pharmacy->id) { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" selected="selected" ><?php echo $value;?></option>
            <?php } else { ?>
            <option label="<?php echo $value;?>" value="<?php echo $key;?>" ><?php echo $value;?></option>
            <?php }                
        }
        ?>
        </select>

        <input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
        </form>
    </div>
</div>

</body>
</html>
