/* 
 * Copyright (C) 2016 Kevin Yeh <kevin.y@integralemr.com>.
 * Licensed to the public under the MPL-HD license at www.librehealth.io.
 */



/* This is code needed to connect the iframe for a dialog back to the window which makes the call.
 * It is neccessary to include this script at the "top" of any php file that is used as a dialog.
 * It was not possible to inject this code at "document ready" because sometimes the opened dialog 
 * has a redirect or a close before the document ever becomes ready.
 */

if(top.tab_mode===true)
{
    if(!opener)
    {
        opener=top.get_opener(window.name);
    }

    window.close=
            function()
            {
                var dialogDiv=top.$("#dialogDiv");
                var frameName=window.name
                var body=top.$("body");
                    var removeFrame=body.find("iframe[name='"+frameName+"']");
                    removeFrame.remove();
                    var removeDiv=body.find("div.dialogIframe[name='"+frameName+"']");
                    removeDiv.remove();
                    if(body.children("div.dialogIframe").length===0)
                    {   
                        dialogDiv.hide();
                    };
                };    
}
