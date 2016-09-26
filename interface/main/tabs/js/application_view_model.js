/* 
 * Copyright (C) 2016 Kevin Yeh <kevin.y@integralemr.com>.
 * Licensed to the public under the MPL-HD license at www.librehealth.io.
 */


var app_view_model={};

app_view_model.application_data={};

app_view_model.application_data.tabs=new tabs_view_model();

app_view_model.application_data.patient=ko.observable(null);

app_view_model.application_data.user=ko.observable(null);