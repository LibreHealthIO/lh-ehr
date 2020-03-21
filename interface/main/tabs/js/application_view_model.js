/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var app_view_model={};

app_view_model.application_data={};

app_view_model.application_data.tabs=new tabs_view_model();

app_view_model.application_data.patient=ko.observable(null);

app_view_model.application_data.user=ko.observable(null);