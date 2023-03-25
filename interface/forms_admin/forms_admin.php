<?php 
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

//INCLUDES, DO ANY ACTIONS, THEN GET OUR DATA
include_once("../globals.php");
include_once("$srcdir/registry.inc");
include_once("$srcdir/sql.inc");
require_once("$srcdir/headers.inc.php");
if ($_GET['method'] == "enable"){
    updateRegistered ( $_GET['id'], "state=1" );
}
elseif ($_GET['method'] == "disable"){
    updateRegistered ( $_GET['id'], "state=0" );
}
elseif ($_GET['method'] == "install_db"){
    $dir = getRegistryEntry ( $_GET['id'], "directory" );
    if (installSQL ("$srcdir/../interface/forms/{$dir['directory']}"))
        updateRegistered ( $_GET['id'], "sql_run=1" );
    else
        $err = xl('ERROR: could not open table.sql, broken form?');
}
elseif ($_GET['method'] == "register"){
    registerForm ( $_GET['name'] ) or $err=xl('error while registering form!');
}
$bigdata = getRegistered("%") or $bigdata = false;


//START OUT OUR PAGE....
?>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<?php call_required_libraries(array('bootstrap')) ?>
</head>
<body class="body_top">
<br><br>
<?php
    foreach($_POST as $key=>$val) {
           if (preg_match('/nickname_(\d+)/', $key, $matches)) {
                    $nickname_id = $matches[1];
            sqlQuery("update registry set nickname= ? where id= ?", array($val, $nickname_id));
        }
           if (preg_match('/category_(\d+)/', $key, $matches)) {
                    $category_id = $matches[1];
            sqlQuery("update registry set category=? where id=?", array($val, $category_id));
        }
           if (preg_match('/priority_(\d+)/', $key, $matches)) {
                    $priority_id = $matches[1];
            sqlQuery("update registry set priority=? where id=?", array($val, $priority_id));
        }
        }   
?>


<?php //ERROR REPORTING
if ($err)
    echo "<span class=bold>$err</span><br><br>\n";
?>

<style>
    td {
        padding:2px 8px !important;
    }
    .buttoncol {
        text-align: center;
    }
</style>
<?php //REGISTERED SECTION ?>
<form method=POST action ='./forms_admin.php'>
<h3 class="title"><?php xl('Forms Administration','e');?></h3>
<div class="panel panel-default">
    <div class="panel-heading"><?php xl('Registered','e');?></div>
    <div class="panel-body">
        <i><?php xl('click here to update priority, category and nickname settings','e'); ?></i>&nbsp
        <input type=submit name=update class='cp-positive btn' value='<?php xl('Update','e'); ?>'><br> 
    </div>
    <table class="table table-bordered" cellpadding=1 cellspacing=2>
        <tr>
            <td><?php xl('ID ','e'); ?></td>
            <td><?php xl('Name ','e'); ?></td>
            <td><?php xl('Status ','e'); ?></td>
            <td> </td>
            <td> </td>
            <td><?php xl('Priority ','e'); ?></td>
            <td><?php xl('Category ','e'); ?></td>
            <td><?php xl('Nickname','e'); ?></td>
        </tr>
    <?php
    $color="#eeeeee";
    if ($bigdata != false)
    foreach($bigdata as $registry)
    {
        $priority_category = sqlQuery("select priority, category, nickname from registry where id=?", array($registry['id'])); 
        ?>
        <tr td bgcolor="<?php echo $color?>">
            <td width="2%">
                <span class=text><?php echo $registry['id'];?></span> 
            </td>
            <td width="30%">
                <span class=bold><?php echo xl_form_title($registry['name']); ?></span> 
            </td>
            <?php
                if ($registry['sql_run'] == 0)
                    echo "<td width='10%'><div class='btn disabled'>".xl('registered')."</div>";
                elseif ($registry['state'] == "0")
                    echo "<td width='10%'><a style='background: #ffcccc;' class='btn link_submit' href='./forms_admin.php?id={$registry['id']}&method=enable'>".xl('disabled')."</a>";
                else
                    echo "<td width='10%'><a style='background: #ccffcc;' class='btn link_submit' href='./forms_admin.php?id={$registry['id']}&method=disable'>".xl('enabled')."</a>";
            ?></td>
            <td width="10%">
                <span class=text><?php
                
                if ($registry['unpackaged'])
                    echo xl('PHP extracted','e');
                else
                    echo xl('PHP compressed','e');
                
                ?></span> 
            </td>
            <td width="10%">
                <?php
                if ($registry['sql_run'])
                    echo "<span class=text>".xl('DB installed')."</span>";
                else
                    echo "<a class=link_submit href='./forms_admin.php?id={$registry['id']}&method=install_db'>".xl('install DB')."</a>";
                ?> 
            </td>
            <?php
                echo "<td><input class='form-control' type=text size=4 name=priority_".$registry['id']." value='".$priority_category['priority']."'></td>";
                echo "<td><input class='form-control' type=text size=8 name=category_".$registry['id']." value='".$priority_category['category']."'></td>";
                echo "<td><input class='form-control' type=text size=8 name=nickname_".$registry['id']." value='".$priority_category['nickname']."'></td>";
            ?>
        </tr>
        <?php
        if ($color=="#eeeeee")
            $color="#ffffff";
        else
            $color="#eeeeee";
    } //end of foreach
        ?>
    </table>
</div>
<hr>

<?php  //UNREGISTERED SECTION ?>
<div class="panel panel-default">
    <div class="panel-heading"><?php xl('Unregistered','e');?></div>
    <table class="table table-bordered" cellpadding=1 cellspacing=2>
    <tr>
        <td><?php xl('Name ','e'); ?></td>
        <td><?php xl('Action ','e'); ?></td>
        <td><?php xl('PHP ','e'); ?></td>
        <td></td>
    </tr>
    <?php
    $dpath = "$srcdir/../interface/forms/";
    $dp = opendir($dpath);
    $color="#eeeeee";
    for ($i=0; false != ($fname = readdir($dp)); $i++)
        if ($fname != "." && $fname != ".." && $fname != "CVS" && $fname != "LBF" &&
        (is_dir($dpath.$fname) || stristr($fname, ".tar.gz") ||
        stristr($fname, ".tar") || stristr($fname, ".zip") ||
        stristr($fname, ".gz")))
            $inDir[$i] = $fname;

    // ballards 11/05/2005 fixed bug in removing registered form from the list
    if ($bigdata != false)
    {
        foreach ( $bigdata as $registry )
        {
            $key = array_search($registry['directory'], $inDir) ;  /* returns integer or FALSE */
            unset($inDir[$key]);
        }
    }

    foreach ( $inDir as $fname )
    {
        if (stristr($fname, ".tar.gz") || stristr($fname, ".tar") || stristr($fname, ".zip") || stristr($fname, ".gz"))
            $phpState = "PHP compressed";
        else
            $phpState =  "PHP extracted";
        ?>
        <tr >
            <td width="20%" class="buttoncol">
                <?php
                    $form_title_file = @file($GLOBALS['srcdir']."/../interface/forms/$fname/info.txt");
                            if ($form_title_file)
                                    $form_title = $form_title_file[0];
                            else
                                    $form_title = $fname;
                    ?>
                <span class=bold><?php echo xl_form_title($form_title); ?></span> 
            </td>
            <td width="10%"><?php
                if ($phpState == "PHP extracted")
                    echo '<a type="button" class="btn btn-primary" href="./forms_admin.php?name=' . urlencode($fname) . '&method=register">' . xl('Register') . '</a>';
                else
                    echo '<span class=text>' . xl('n/a') . '</span>';
            ?></td>
            <td width="20%">
                <span class=text><?php echo xl($phpState); ?></span> 
            </td>
            <td width="10%">
                <span class=text><?php xl('n/a','e'); ?></span> 
            </td>
        </tr>
        <?php
        if ($color=="#eeeeee")
            $color="#ffffff";
        else
            $color="#eeeeee";
        flush();
    }//end of foreach
    ?>
    </table>
</div>

</body>
</html>
