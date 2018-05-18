<html>
    <head>
    <?php html_header_show();?>
    <?php call_required_libraries(['bootstrap', 'jquery-min-1-9-1']); ?>

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

    </head>
    <body bgcolor="<?php echo $this->style["BGCOLOR2"];?>">
    <p><span class="title" style="display: none"><?php echo  xl('SOAP','e'); ?></span></p>
    <h2><?php echo xlt('SOAP'); ?></h2>
        <form name="soap" method="post" action="<?php echo $this->form_action;?>/interface/forms/soap/save.php"
         onsubmit="return top.restoreSession()">
            <table class="table table-bordered table-hover table-condensed">
                <tr>
                    <td align="left"><?php echo  xl('Subjective','e'); ?></td>
                    <td width="90%">
                        <textarea class="form-control" style="width: auto;" name="subjective" cols="60" rows="6"><?php echo $this->data->get_subjective();?></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="left"><?php echo  xl('Objective','e'); ?></td>
                    <td width="90%">
                        <textarea class="form-control" style="width: auto;" name="objective" cols="60" rows="6"><?php echo $this->data->get_objective();?></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="left"><?php echo  xl('Assessment','e'); ?></td>
                    <td width="90%">
                        <textarea class="form-control" style="width: auto;" name="assessment" cols="60" rows="6"><?php echo $this->data->get_assessment();?></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="left"><?php echo  xl('Plan','e'); ?></td>
                    <td width="90%">
                        <textarea class="form-control" style="width: auto;" name="plan" cols="60" rows="6"><?php echo $this->data->get_plan();?></textarea>
                    </td>
                </tr>
    <tr>
        <td><input type="submit" name="Submit" class='cp-submit' value=<?php echo  xla('Save'); ?>></td>
    <td><input type="button" id="dontsave" class="deleter cp-negative" value="<?php echo xla('Cancel'); ?>"> &nbsp;</td>
    </tr>
            </table>
<input type="hidden" name="id" value="<?php echo $this->data->get_id();?>" />
<input type="hidden" name="activity" value="<?php echo $this->data->get_activity();?>">
<input type="hidden" name="pid" value="<?php echo $this->data->get_pid();?>">
<input type="hidden" name="process" value="true">
        </form>
           <script language="javascript">
    // jQuery stuff to make the page a little easier to use

    $(document).ready(function(){
        //$("#save").click(function() { top.restoreSession(); document.my_form.submit(); });
        $("#dontsave").click(function() { location.href='<?php echo "../encounter/encounter_top.php";?>'; });
    });
    </script>
    </body>
</html>
