<?php

/* 
 * Copyright (C) 2016 Kevin Yeh <kevin.y@integralemr.com>.
 * Licensed to the public under the MPL-HD license at www.librehealth.io.
 */

require_once("menu_data.php");
require_once("menu_updates.php");
require_once("menu_db.php");

$menu_parsed=load_menu("default");
if(count($menu_parsed)==0)
{
    $menu_parsed=json_decode($menu_json);
}

menu_update_entries($menu_parsed);
$menu_restrictions=array();
menu_apply_restrictions($menu_parsed,$menu_restrictions);
?>
<script type="text/javascript">
    
    function menu_entry(object)
    {
        var self=this;
        self.label=ko.observable(object.label);

        self.header=false;
        if('url' in object )
        {
            self.url=ko.observable(object.url);
            self.header=false;
        }
        else
        {
            self.header=true;
        }
        if('target' in object)
        {
            self.target=object.target;
        }
        self.requirement=object.requirement;
        if(object.requirement===0)
        {
            self.enabled=ko.observable(true);
        } else if(object.requirement===1)
        {
            self.enabled=ko.computed(function()
            {
                return app_view_model.application_data.patient()!==null;
            });
        } else if((object.requirement===2) || (object.requirement===3))
        {
            self.enabled=ko.computed(function()
            {
                return (app_view_model.application_data.patient()!==null
                        && app_view_model.application_data.patient().selectedEncounter()!=null);
            });
            
        }
        if(self.header)
        {
            self.children=ko.observableArray();
            for(var childIdx=0;childIdx<object.children.length;childIdx++)
            {
                var childObj=new menu_entry(object.children[childIdx]);
                self.children.push(childObj);
            }
        }
        return this;
    }
    function process_menu_object(object,target)
    {
        var newEntry=new menu_entry(object);
        target.push(newEntry);
    }
    var menu_objects=<?php echo json_encode($menu_restrictions); ?>;
    app_view_model.application_data.menu=ko.observableArray();
    for(var menuIdx=0;menuIdx<menu_objects.length;menuIdx++)
    {
        var curObj=menu_objects[menuIdx];
        process_menu_object(curObj,app_view_model.application_data.menu);
    }
</script>