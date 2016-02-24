<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function include_js_library($path)
{
?>
<script type="text/javascript" src="<?php echo $GLOBALS['web_root']."/library/js/".$path;?>"></script>
<?php
}