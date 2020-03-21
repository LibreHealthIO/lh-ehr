/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
    top.window.location=webroot_url+"/interface/logout.php";
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

function userPrefs()
{
    navigateTab(webroot_url+"/interface/super/edit_globals.php?mode=user","msc");
    activateTabByName("msc",true);
}

// this function is calling when clicking on "x unread messages" anchor on upper right corner
// this opens the messages tab
function unreadMessages() {
    navigateTab(webroot_url+"/interface/main/messages/messages.php?form_active=1", "pat");
    activateTabByName("pat", true);
}