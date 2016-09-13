<?php
# The extra lines are the menu blocks on the screen/
# insert a line like this {\"label\":\"Visit Forms\",\"children\":[],\"requirement\":0},
# Then edit the information needed
#

// Pull in globals settings
$usermenufile = $GLOBALS['OE_SITE_DIR'] . "/menu_data.json";
$SQL_ADMIN = $GLOBALS['sql_admin_tool_url'];

// Load JSON Menu file from sites directory
$menu_temp = file_get_contents($usermenufile);

// replace ##TAGS## with globals data
$menu_json = str_replace('##SQL_ADMIN##',$SQL_ADMIN, $menu_temp);
