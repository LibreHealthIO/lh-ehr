/* 
 * Copyright (C) 2016 Kevin Yeh <kevin.y@integralemr.com>.
 * Licensed to the public under the MPL-HD license at www.librehealth.io.
 */



var opener_list=[];

function set_opener(window,opener)
{
    top.opener_list[window]=opener;
}

function get_opener(window)
{
    return top.opener_list[window];
}