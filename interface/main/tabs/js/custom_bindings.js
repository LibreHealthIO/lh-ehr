/* 
 * Copyright (C) 2016 Kevin Yeh <kevin.y@integralemr.com>.
 * Licensed to the public under the MPL-HD license at www.librehealth.io.
 */



ko.bindingHandlers.location={
    init: function(element,valueAccessor, allBindings,viewModel, bindingContext)
    {
        var tabData = ko.unwrap(valueAccessor());
        tabData.window=element.contentWindow;
        element.addEventListener("load",
            function()
            {
                
                var cwDocument=this.contentWindow.document
                $(cwDocument).ready(function(){
                        var jqDocument=$(cwDocument);
                        var titleText="Unknown";
                        var titleClass=jqDocument.find(".title:first");
                        if(titleClass.length>=1)
                        {
                            titleText=titleClass.text();
                        }
                        else
                        {
                            var frameDocument=jqDocument.find("frame");
                            if(frameDocument.length>=1)
                            {
                                titleText=frameDocument.attr("name");
                                var jqFrameDocument=$(frameDocument.get(0).contentWindow.document);
                                titleClass=jqFrameDocument.find(".title:first");
                                if(titleClass.length>=1)
                                {
                                    titleText=titleClass.text();                                
                                }
                                var subFrame= frameDocument.get(0);
                                subFrame.addEventListener("load",
                                function()
                                {
                                    var subFrameDocument=$(subFrame.contentWindow.document);
                                    titleClass=$(subFrameDocument).find(".title:first");
                                    if(titleClass.length>=1)
                                    {
                                        titleText=titleClass.text();
                                        tabData.title(titleText);
                                    }
                                   
                                });
                            }
                            else
                            {
                                var bold=jqDocument.find("b:first");
                                if(bold.length)
                                {
                                    titleText=bold.text();
                                }
                                else
                                {
                                    var title=jqDocument.find("title");
                                    if(title.length)
                                    {
                                        titleText=title.text();
                                    }
                                }
                                        
                            }
                            
                        }
                        tabData.title(titleText);
                    }
                );
            }
            ,true
        );

    },
    update: function(element,valueAccessor, allBindings,viewModel, bindingContext)
    {
        var tabData = ko.unwrap(valueAccessor());
        element.src=tabData.url();
    }
}

ko.bindingHandlers.iframeName = {
    init: function(element,valueAccessor, allBindings,viewModel, bindingContext)
    {
    },
    update: function(element,valueAccessor, allBindings,viewModel, bindingContext)
    {
        element.name=ko.unwrap(valueAccessor());        
    }
}


