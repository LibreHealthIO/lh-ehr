/* 
 * Copyright (C) 2016 Kevin Yeh <kevin.y@integralemr.com>.
 * Licensed to the public under the MPL-HD license at www.librehealth.io.
 */



function user_data_view_model(username,fname,lname,authGrp)
{
    var self=this;
    self.username=ko.observable(username);
    self.fname=ko.observable(fname);
    self.lname=ko.observable(lname);
    self.authorization_group=ko.observable(authGrp);
    return this;
    
}

function logout()
{
    top.window.location=webroot_url+"/interface/logout.php"
}

function changePassword()
{
    navigateTab(webroot_url+"/interface/usergroup/user_info.php","msc");
    activateTabByName("msc",true);
}

function framesMode()
{
    top.window.location=webroot_url+"/interface/main/main_screen.php?tabs=false";
}