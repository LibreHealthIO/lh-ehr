/*globals rangy, alert, console */
(function($){
    "use strict";

    var test_prerequisites = function(){
        if(typeof window.Worker !== "function"){
            return "Web Workers";
        }
    },
    document_element,
    document_element_innerhtml,
    xml_element,
    show_xml_element,
    show_xml_element_visible = false,
    modal,
    schemas,
    cache = {},
    validation_result,
    templates = {
        modal: "<h1>Change Element</h1><div class='body'><label>Element: <select class=\"nodeName\">{{#elements}}<option value=\"{{name}}\" data-display=\"{{display}}\">{{name}}</option>{{/elements}}</select></label><ul class=\"attributes\">{{#attributes}}<li><input type=\"text\" value=\"{{key}}\"/>=<input type=\"text\" value=\"{{value}}\"/></li>{{/attributes}}<li><input type=\"text\" class=\"extra-attribute\">=<input type=\"text\" class=\"extra-attribute\"></ul></div><div class='buttonTray'><button class=\"change\">Change Element</button></div>",
        toolmenu: "<select class=\"nodeName\"><option value=\"\">Choose Element</option><optgroup label=\" \">{{#elements}}<option value=\"{{name}}\">{{name}}</option>{{/elements}}</optgroup></select>"
    },
    display_types = {
        block: "block",
        inline: "inline-block",
        table: "table",
        row: "row",
        cell: "cell"
    },
    chosen_schema = {
        "name":"docbook",
        "id"  :"docbook5"
    },
    chosen_schema_settings = {},
    modal_element,
    get_modal_element = function(){
        if(modal_element) return modal_element;
        //TODO: rewrite without jQuery
        var $modal_element = $("<div/>").addClass("doctored modal").html(
            cache.templates.toolmenu({
                "elements"  : chosen_schema_settings.elements
            })
        );
        $("body").append($modal_element);
        modal_element = $modal_element.get(0);
        modal_element.addEventListener("change", element_chooser_change, false);
        return modal_element;
    },
    sanitise_element_name = function(element_name){
        if(element_name === null) return element_name;
        element_name = element_name.replace(/^[^a-zA-Z]+/, ''); //ensure first character is a letter. FIXME make internationalized
        element_name = element_name.replace(/[^a-zA-Z\-_0-9]/g, '');
        if(element_name.length === 0) {
            element_name = "default";
        }
        return element_name;
    },
    sniff_display_type = function(node){
        if(!node) return;
        var className;
        switch(node.nodeType){
            case node.TEXT_NODE:
                return Node.TEXT_NODE;
            case node.ELEMENT_NODE:
                className = " " + node.className + " ";
                if(       / block / .test(className)){
                    return display_types.block;
                } else if(/ inline /.test(className) || / inline-block /.test(className)){
                    return display_types.inline;
                } else if(/ table / .test(className)){
                    return display_types.table;
                } else if(/ row /   .test(className)){
                    return display_types.row;
                } else if(/ cell /  .test(className)){
                    return display_types.cell;
                } else if(/ selection /  .test(className)){
                    return display_types.text_selection;
                }

                return alert("Unknown element type. className was " + className);
        }
        alert("Unknown element type. nodeName was " + node.nodeName);
    },
    click_element = function(event){
        var target       = event.originalTarget || event.target,
            display_type = sniff_display_type(target),
            new_element_name,
            modal_html,
            nodeName_element,
            attributes,
            attributes_key,
            attributes_list = [];
        if(!target || target.nodeType !== Node.ELEMENT_NODE) return;

        switch(display_type){ //ensure the clicked the :before area
            case display_types.block:
                if(event.clientX - $(target).offset().left > parseInt($(target).css("padding-left"), 10)) return console.log("click outside :before area"); //FIXME remove jQuery dependency
                break;
            case display_types.inline:
                if(event.clientY - $(target).offset().top < $(target).height() - parseInt($(target).css("padding-bottom"), 10)) return console.log("click outside :before area");  //FIXME remove jQuery dependency
                break;
        }
        attributes = target.getAttribute("data-attributes") || attributes_list;
        console.log("attributes", attributes);
        if(attributes.length !== 0){
            attributes = JSON.parse(attributes);
            for(attributes_key in attributes){
                attributes_list.push({"key": attributes_key, "value": attributes[attributes_key]});
            }
        }
        modal_html = cache.templates.modal({
            "elements"  : chosen_schema_settings.elements,
            "attributes": attributes_list
        });

        modal = picoModal(modal_html);

        nodeName_element = document.getElementsByClassName("nodeName")[0];
        util.set_select_by_value(nodeName_element, target.getAttribute("data-element"));

        modal.modalElem.querySelector('.change').addEventListener('click', function(event){
            if(!modal) return;
            var node_name,
                display_type,
                nodes,
                i,
                text_node;

            node_name = modal.modalElem.querySelector('.nodeName');
            display_type = node_name.options[node_name.options.selectedIndex].getAttribute("data-display");
            console.log(display_type);
            target.className = target.className.replace(/block|inline|inline-block|table|row|cell|list|listitem/g, "") + " " + display_type;            target.setAttribute("data-element", sanitise_element_name(node_name.value));
            if(display_type === display_types.inline){
                console.log("try to remove brs");
                nodes = target.getElementsByTagName("br");
                for(i = 0; i < nodes.length; i++){
                    text_node = document.createTextNode(" ");
                    nodes[i].parentNode.insertBefore(text_node, nodes[i]);
                    nodes[i].parentNode.removeChild(nodes[i]);
                }
            }
            modal.close();
        });
        event.stopPropagation();
        build_xml_soon();
    },
    util = {
        set_select_by_value: function(select_element, value){
            var i,
                option;
            for(i = 0; i < select_element.options.length; i++){
                option = select_element.options[i];
                option.selected = (option.value === value);
            }
        }
    },
    get_block_parent = function(target){
        if(!target) return;
        if(sniff_display_type(target) === display_types.block) return target;
        if(target.parentElement) return get_block_parent(target.parentElement);
    },
    append_after = function(after, new_node){
        if(after.nextSibling) {
            after.parentNode.insertBefore(new_node, after.nextSibling);
        }else{
            after.parentNode.appendChild(new_node);
        }
    },
    keydown_element = function(event){
        var target,
            display_type,
            esc_key   = 27,
            enter_key = 13;

        switch(event.keyCode){
            case enter_key:
                target = get_selection().anchorNode;
                display_type = sniff_display_type(target);
                switch(display_type){
                    case Node.TEXT_NODE:
                    case display_types.inline:
                        ascend_spans_before_hitting_enter(target);
                        setTimeout(ascend_spans_change_back, 250);
                }
                break;
            case esc_key:
                if(modal) modal.close();
                break;
        }
    },
    get_selection = function(){
        if(window.getSelection) { //Chrome/Firefox
            return window.getSelection();
        } else if (document.selection) {//IE
            return document.selection;
        }
    },
    keyup_element = function(event){
        
    },
    ascend_spans_change_back = function(){
        var selection = window.getSelection(),
            text_offset = selection.getRangeAt(0).startOffset,
            target  = selection.anchorNode,
            sibling = target.previousSibling,
            block_parent,
            clone;

        if(sibling && sibling.nodeType === sibling.ELEMENT_NODE && sibling.nodeName.toLowerCase() === "br"){
            block_parent = get_block_parent(target);
            clone = block_parent.cloneNode();
            append_after(block_parent, clone);
            console.log("clone block parent and split");
        }
        //$(".inline-changing").css({display:"inline-block"}).removeClass("inline-changing");
    },
    ascend_spans_before_hitting_enter = function(target) {
        var display_type;
        if(target === undefined) return;
        display_type = sniff_display_type(target);
        if(display_type === undefined){
            return ascend_spans_before_hitting_enter(target.parentElement);
        }else if(display_type !== display_types.inline){
            return;
        }
        console.log("change to inline", target);
        target.style.display = "inline";
        target.className += " inline-changing";
        ascend_spans_before_hitting_enter(target.parentElement);
    },
    build_xml_soon_timer,
    build_xml_soon = function(event){
        if(build_xml_soon_timer) {
            clearTimeout(build_xml_soon_timer);
        }
        build_xml_soon_timer = setTimeout(build_xml, 50);
    },
    synchronous_get_file = function(url){
        var AJAX = new XMLHttpRequest();
        AJAX.open("GET", url, false);
        AJAX.send(null);
        return AJAX.responseText;
    },
    build_xml = function(){
        var start_time = (new Date()).getTime(),
            xml = '<book xmlns="http://docbook.org/ns/docbook">' + descend_building_xml(document_element.childNodes) + "\n</book>";
        console.log( (new Date()).getTime() - start_time + " milliseconds to generate XML");
        window.xmllint.postMessage({
            "xml": xml,
            "schema_url": "../../schemas/" + chosen_schema.id + "/schema.rng"
        });
        xml_element.value = xml;
    },
    descend_building_xml = function(nodes){
        var i,
            node,
            xml_string = "",
            data_element,
            text_node,
            display_type;
        for(i = 0; i < nodes.length; i++){
            node = nodes[i];
            switch(node.nodeType){
                case Node.ELEMENT_NODE:
                    data_element = node.getAttribute("data-element");
                    if(!data_element) continue;
                    display_type = sniff_display_type(node);
                    if(display_type === display_types.block){
                        xml_string += "\n";
                    }
                    xml_string += "<" + data_element;
                    xml_string += build_xml_attributes_from_json_string(node.getAttribute("data-attributes"));
                    xml_string += ">";
                    if (node.hasChildNodes()) {
                        xml_string += descend_building_xml(node.childNodes);
                    }
                    if(display_type === display_types.block){
                        xml_string += "\n";
                    }
                    xml_string += "</" + data_element + ">";
                    break;
                case Node.TEXT_NODE:
                    text_node = node.innerHTML || node.textContent || node.innerText;
                    if(text_node === undefined) break;
                    xml_string += text_node.replace(/[\n]/g, " ");
                    break;
            }
        }
        return xml_string;
    },
    build_xml_attributes_from_json_string = function(json_string){
        var attributes,
            attributes_string = "",
            key;
        if(!json_string || json_string.length === 0) return "";
        attributes = JSON.parse(json_string);
        for(key in attributes) {
            attributes_string += " " + key.toString() + "=\"" + attributes[key] + "\"";
        }
        return attributes_string;
    },
    mouseup = function(event){
        check_for_text_selection();
    },
    insert_html_at_selection  = function(html, start_of_selection) {
        var sel,
            range,
            node;

        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                range = window.getSelection().getRangeAt(0);
                range.collapse(start_of_selection);
                var el = document.createElement("div");
                el.innerHTML = html;
                var frag = document.createDocumentFragment(),
                    lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);
            }
        } else if (document.selection && document.selection.createRange) {
            range = document.selection.createRange();
            range.collapse(start_of_selection);
            range.pasteHTML(html);
        }
    },
    element_chooser_change = function(){
        var modal_element = get_modal_element(),
            $modal_element = $(modal_element),
            selection,
            selections = document_element.getElementsByClassName("selection"),
            i,
            modal_element_val = $modal_element.val();

        for(i = 0; i < selections.length; i++){
            selection = selections[i];
            if(modal_element_val === ""){
    	        $(selection).replaceWith(selection.childNodes); //TODO replace with plain JavaScript
            } else {
                selection.setAttribute("class", "block");
                selection.setAttribute("data-element", modal_element_val);
            }
        }
        //$(selection).replaceWith(selection.childNodes); //TODO replace with plain JavaScript

    },
    element_chooser = function(above_element){
        //todo ditch jQuery / rewrite in plain JS
        var above_element_position = $(above_element).inlineOffset(),
             modal_element = get_modal_element();
        above_element_position.top -= $(modal_element).height();
        modal_element.style.top     = above_element_position.top  + "px";
        modal_element.style.left    = above_element_position.left + "px";
        modal_element.style.display = "block";

    },
    check_for_text_selection = function(){
        var text_selection  = rangy.getSelection().getRangeAt(0).toString(),
                 selections = document_element.getElementsByClassName("selection"),
                 selection,
                 i,
                 highlightApplier;

        for(i = 0; i < selections.length; i++){
            selection = selections[i];
	        $(selection).replaceWith(selection.childNodes); //TODO replace with plain JavaScript
        }

        if(text_selection.length > 0){
            highlightApplier = rangy.createCssClassApplier("selection", true);
            highlightApplier.applyToSelection();
            selections = document_element.getElementsByClassName("selection"); //must be done after above highlightApplier
            if(selections.length !== 0){
                element_chooser(selections[0]);
            }
        }
    },
    update_errors = function(error_lines){
        var error_line,
            xml_line_number,
            xml_line,
            xml_line_id_match,
            xml_line_id,
            xml_lines = xml_element.value.split("\n"),
            exit_after = 1000,
            block,
            error_blocks = {},
            blocks = document.getElementsByClassName("block"),
            i,
            format_errors = function(error_block){
                var i,
                    error_string = "";
                if(!error_block) return "";
                for(i = 0; i < error_block.length; i++){
                    error_string += error_block[i].message + ". ";
                }
                return error_string;
            },
            set_errors = function(nodes, error_blocks){
                var i,
                    node,
                    first_element = true,
                    additional_errors = "";
                for(i = 0; i < nodes.length; i++){
                    node = nodes[i];
                    if(node.nodeType !== Node.ELEMENT_NODE) continue;
                    if(first_element && error_blocks[-1]){
                        additional_errors = format_errors(error_blocks[-1]);
                        first_element = false;
                    }
                    if(error_blocks[i] || additional_errors.length > 0){
                        node.setAttribute("data-error", format_errors(error_blocks[i]) + additional_errors);
                        node.className += " has_errors";
                    } else {
                        node.setAttribute("data-error", "");
                        node.className = (node.className + " ").replace(/ has_errors /g, "");
                    }
                    additional_errors = "";
                }
            };

        for(i = 0; i < error_lines.length; i++){
            error_line = error_lines[i];
            xml_line_number = error_line.line_number;
            do {
                xml_line = xml_lines[xml_line_number];
                xml_line_id_match = xml_line.match(/id="([^"]*)"/);
                if(xml_line_id_match && xml_line_id_match.length === 2) {
                    xml_line_id = parseInt(xml_line_id_match[1], 10);
                } else {
                    xml_line_id = -1;
                }
                if(error_blocks[xml_line_id] === undefined) {
                    error_blocks[xml_line_id] = [];
                }
                if(error_blocks[xml_line_id].indexOf(error_line) === -1){
                    error_blocks[xml_line_id].push(error_line);
                }
                xml_line_number--;
            } while(xml_line_number >= 0);
        }
        set_errors(document_element.childNodes, error_blocks);
    },
    toggle_xml_visibility = function(event){
        xml_element.style.display = !show_xml_element_visible ? "block" : "none";
        show_xml_element_visible = !show_xml_element_visible;
    },
    init = function(){
        if(typeof console === "undefined") {
            window.console = {log:function(){}};
        }
        var key,
            node;
        if(test_prerequisites()) return alert("Unable to continue. Your browser doesn't support " + test_prerequisites());
        $.getJSON("schemas/" + chosen_schema.id + "/display.json", function(data){
            chosen_schema_settings = data;
        });

        xml_element = document.getElementById("xml");
        document_element = document.getElementById("document");
        show_xml_element = document.getElementById("see_xml");

        cache.templates = {};
        for(key in templates){
            cache.templates[key] = Handlebars.compile(templates[key]);
        }

        cache.display_types_array = [];
        for(key in display_types){
            cache.display_types_array.push({
                "value" : key,
                text : display_types[key]
            });
        }

        cache.edit_toolbar = document.createElement("div");
        cache.edit_toolbar.className = "doctored_edit_toolbar";
        node = document.createElement("div");
        node.className = "doctored_edit_toolbar_arrow";
        cache.edit_toolbar.appendChild(node);

        validation_result = document.getElementById("validation_result");

        document_element.addEventListener("input", build_xml_soon, false);
        document_element.addEventListener('click', click_element, false);
        document_element.addEventListener('keydown', keydown_element, false);
        document_element.addEventListener('keyup', keyup_element, false);
        document_element.addEventListener('mouseup', mouseup, false);
        show_xml_element.addEventListener('click', toggle_xml_visibility);

        try {
            window.xmllint = new Worker(window.doctored.base + "js/rng.js");
        } catch(exception){
                if(window.location.toString().substr(0, 5) === "file:") {
                    return alert("Unable to start WebWorker from file:/// path.");
                } else {
                    return alert("Unable to start WebWorker. No idea why though, sorry. COMPUTER SAYS NO: " + exception.toString());
                }
        }
        window.xmllint.onmessage = function(event) {
            console.log("Worker said : " + event.data, event.data.error_lines);
            if(event.data.debug) return console.log("WORKER SAID", event.data);
            validation_result.value = JSON.stringify(event.data);
            if(event.data && event.data.error_lines){
                update_errors(event.data.error_lines);
            }
        };
        build_xml_soon();
    };
    window.onload=init;
}(jQuery));
