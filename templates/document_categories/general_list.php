
 <style type="text/css" title="mystyles" media="all">

</style>

<script type="text/javascript">
var deleteLabel="Delete'";
</script>

<script type="text/javascript" src="library/js/DocumentTreeMenu.js"></script>
<table>
	<tr>
		<td height="20" valign="top">Document Categories</td>

	</tr>
	<tr>
		<td valign="top"><?php echo $this->tree_html;?></td>
		<!--{if $message}-->
                                        <?php if($this->message) {?>
		<td valign="top"><?php echo $this->message;?></td>
                                        <?php }  
                                        if($this->add_node == true) {
                                        ?>		
		<td width="25"></td>
		<td valign="top">
		The new category will be a sub-category of  <?php echo $this->parent_name;?><br>
		<form method="post" action="<?php echo $this->form_action;?>" onsubmit="return top.restoreSession()">
		Category Name:&nbsp;<input type="text" name="name" onKeyDown="PreventIt(event)" >&nbsp;&nbsp;
		<input type="submit" name="Add Category" value="Add Category">
		<input type="hidden" name="parent_is" value="<?php echo $this->parent_is;?>">
		<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
		</form>
		</td>
                                        <?php }?>
	</tr>

</table>
