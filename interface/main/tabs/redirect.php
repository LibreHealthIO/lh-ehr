<?php

/* 
 * Copyright (C) 2016 Kevin Yeh <kevin.y@integralemr.com>.
 * Licensed to the public under the MPL-HD license at www.librehealth.io.
 */


$tabs=true;
if(isset($_REQUEST['tabs']))
{
    if($_REQUEST['tabs']==='false')
    {
        $tabs=false;
        ?>
        <script type="text/javascript">
            top.tab_mode=false;
        </script>
        <?php
    }
}
if ($tabs===true)
{
    $tabs_base_url=$web_root."/interface/main/tabs/main.php?url=".urlencode($frame1url);
    header('Location: '.$tabs_base_url);
    exit();
}
if(isset($_REQUEST['analysis']))
{
    if($_REQUEST['analysis']==='true')
    {
        ?>
            <script type="text/javascript" src="tabs/js/menu_analysis.js"></script>
        <?php
    }
}


?>