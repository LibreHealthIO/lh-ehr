<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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