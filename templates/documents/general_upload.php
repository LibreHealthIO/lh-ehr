<form method=post enctype="multipart/form-data" action="<?php echo $this->form_action;?>" onsubmit="return top.restoreSession()">
<input type="hidden" name="MAX_FILE_SIZE" value="64000000" />

<?php if(!($patient_id > 0)) { ?>
<div class="text" style="color:red;">
    <?php echo xl(" IMPORTANT: This upload tool is only for uploading documents on patients that are not yet entered into the system.
        To upload files for patients whom already have been entered into the system, please use the upload tool linked within the Patient Summary screen.");?>
   
    <br/>
    <br/>
  </div>
<?php } ?>

<div class="text">
   <?php echo xl(" NOTE: Uploading files with duplicate names will cause the files to be automatically renamed (for example, file.jpg will become file.1.jpg). 
    Filenames are considered unique per patient, not per category.");?>
    <br/>
    <br/>
</div>
<div class="text bold">
    <?php xl("Upload Document"); ?>
    
    <?php if(!empty($this->category_name)) {    
        echo xl("to category ".$this->category_name);
    }
?>
        
</div>
<div class="text">
    <p><span><?php echo xl("Source File Path:");?></span> <input type="file" name="file[]" id="source-name" multiple="true"/>&nbsp;
        (<font size="1">Multiple files can be uploaded at one time by selecting them using CTRL+Click or SHIFT+Click.</font>)</p>
    <p><span title="Leave Blank To Keep Original Filename"><?php echo xl("Optional Destination Name:");?></span> 
        <input type="text" name="destination" title="Leave Blank To Keep Original Filename" id="destination-name" /></p>
    <?php if( !$this->hide_encryption) {?>
        </br>
	<p><span title="Check the box if this is an encrypted file"><?php echo xl("Is The File Encrypted?:");?></span> 
                        <input type="checkbox" name="encrypted" title="Check the box if this is an encrypted file" id="encrypted" /></p>
	<p><span title="Pass phrase to decrypt document"><?php echo xl("Pass Phrase:");?></span> 
                        <input type="text" name="passphrase" title="{xl t='Pass phrase to decrypt document'}" id="passphrase" /></p>
	<p><i><?php echo xl('Supports TripleDES encryption/decryption only.');?></i></p>
    <?php }?>   
    
    <p><input type="submit" value="<?php echo xl("Upload");?>" /></p>
</div>

<input type="hidden" name="patient_id" value="<?php echo $this->patient_id;?>" />
<input type="hidden" name="category_id" value="<?php echo $this->category_id;?>" />
<input type="hidden" name="process" value="<?php echo self::PROCESS;?>" />
</form>

<!-- Section for document template download -->
<form method='post' action='interface/patient_file/download_template.php' onsubmit='return top.restoreSession()'>
    <input type='hidden' name='patient_id' value='<?php echo $this->patient_id;?>' />
<p class='text bold'>
    <?php echo xl("Download document template for this patient and visit");?> 
</p>
<p class='text'>
 <select name='form_filename'><?php echo $this->templates_list;?></select> &nbsp;
 <input type='submit' value='Fetch' />
</p>
</form>
<!-- End document template download section -->

<?php if(!empty($this->file)) {?>
<div class="text bold">
		<br/>
		<?php echo xl("Upload Report");?>
</div>
<?php foreach ($this->file as $file) { ?>
<div class="text">
                    <?php
                    if($this->error) {?>
                    <i><?php echo $this->error; }?></i><br/>                    
                    ?>
                    ID: <?php echo $file->get_id();?><br>
                    Patient: <?php echo $file->get_foreign_id();?><br>
                    URL: <?php echo $file->get_url();?><br>
                    Size: <?php echo $file->get_size();?><br>
                    Date: <?php echo $file->get_date();?><br>
                    Hash: <?php echo $file->get_hash();?><br>
                    MimeType: <?php echo $file->get_mimetype();?><br>
                    Revision: <?php echo $file->revision;?><br><br>
</div>
<?php } }?>      
  