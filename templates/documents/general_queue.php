
<head>
<script language="javascript">
function submit_documents()
{
    top.restoreSession();
    document.queue.submit();
}
</script>
</head>


<a href="controller.php?practice_settings&<?php echo $this->top_action; ?>document_category&action=list" 
   onclick="top.restoreSession()" class="css_button cp-positive" >
<span><?php echo xlt("Edit Categories");?></span></a><a href="#" onclick="submit_documents();" class="css_button cp-positive" target="_self" ><span>Update files</span></a>
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />

<form name="queue" method="post" action="<?php echo $this->form_action; ?>" onsubmit="return top.restoreSession()">
<table class="table table-hover">
    <tr class="center_display">
        <td colspan="6"><?php $this->messages; ?></td>
    </tr>
    <tr>
        <th colspan="2"><?php echo xlt("Name");?></td>
        <th><?php echo xlt("Date");?></td>
        <th><?php echo xlt("Patient");?></td>
        <th colspan="2"><?php echo xlt("Category");?></td>
    </tr>
    
   <?php if(!(empty($this->queue_files)))  
   {
     foreach ($this->queue_files as $file) { ?>
        <tr>
        <td><input type="checkbox" name="files[<?php echo $file.document_id;?>][active]" value="1" 
                   <!--{if is_numeric($file.patient_id)}checked{/if}>-->
                   <?php if(is_numeric($file.patient_id)) {
                            echo xlt("checked");                           
                   }
                   ?>                           
        </td>

        <td><a href="<?php echo $file.web_path;?>" onclick="top.restoreSession()"><?php echo $file.filename;?></a>
            <input type="hidden" name="files[<?php echo $file.document_id;?>][name]" value="<?php echo $file.filename;?>"></td>

        <td><?php echo $file.mtime;?></td>

        <td><input type="text" name="files[<?php echo $file.document_id;?>][patient_id]" size="5" value="<?php echo $file.patient_id;?>">
            <input type="hidden" name="patient_name" value=""></td>

        <td><a href="javascript:" onclick="top.restoreSession();
            var URL='controller.php?patient_finder&find&form_id=queue<?php echo "['files[`$file.document_id`][patient_id]']";?>&form_name=patient_name'; 
            window.open(URL, 'queue', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=450,height=400,left = 425,top = 250');"><img src="images/stock_search-16.png" border="0"></a>&nbsp;&nbsp;&nbsp;</td>
        <td>
            <select name="files[<?php echo $file.document_id;?>][category_id]"><?php echo $this->tree_html_listbox;?></select>
        </td>

        </tr>
   <?php }    
   } else{ ?>
        <tr height="25" class="center_display">
                <td colspan="6"><?php echo xlt("No Documents Found");?></td>
         </tr>
   <?php }?>
         
 </table><br><br>

</form>

        
        
         
  
    <!--
    {foreach name=queue_list from=$queue_files item=file}
    <tr>
        <td><input type="checkbox" name="files[{$file.document_id}][active]" value="1" {if is_numeric($file.patient_id)}checked{/if}></td>

        <td><a href="{$file.web_path}" onclick="top.restoreSession()">{$file.filename}</a>
            <input type="hidden" name="files[{$file.document_id}][name]" value="{$file.filename}"></td>

        <td>{$file.mtime}</td>

        <td><input type="text" name="files[{$file.document_id}][patient_id]" size="5" value="{$file.patient_id}">
            <input type="hidden" name="patient_name" value=""></td>

        <td><a href="javascript:{literal}{}{/literal}" onclick="top.restoreSession();
    var URL='controller.php?patient_finder&find&form_id=queue{"['files[`$file.document_id`][patient_id]']"|escape:"url"}&form_name=patient_name'; window.open(URL, 'queue', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=450,height=400,left = 425,top = 250');"><img src="images/stock_search-16.png" border="0"></a>&nbsp;&nbsp;&nbsp;</td>
        <td><select name="files[{$file.document_id}][category_id]">{$tree_html_listbox}</select></td>

    </tr>
    {foreachelse}
    <tr height="25" class="center_display">
        <td colspan="6">{xl t='No Documents Found'}</td>
    </tr>
    {/foreach}
    -->