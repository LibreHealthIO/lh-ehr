/*globals doctored, Node, alert, console*/
(function(){
	"use strict";

    var $ = doctored.$;

    doctored.util = {
        transition_end_event: function(){
            var element = document.createElement('fakeelement'),
                transitions = {
                'transition':       'transitionend',
                'OTransition':      'otransitionEnd',
                'MozTransition':    'transitionend',
                'WebkitTransition': 'webkitTransitionEnd'
                },
                key;

            for(key in transitions){
                if(element.style[key] !== undefined){
                    return transitions[key];
                }
            }
        }(), //self executing in order to calculate the result once
        debounce: function(fn, delay_in_milliseconds, context) {
            var timer = null;
            context = context || this;
            return function(){
                var args = arguments;
                clearTimeout(timer);
                timer = setTimeout(
                            function(){ fn.apply(context, args); },
                            delay_in_milliseconds
                        );
            };
        },
        this_function: function(fn, context){
            context = context || this;
            if(fn === undefined) {
                console.trace();
                alert("Error: this_function called with non-existant function. See console.log");
            }
            return function(){
                var args = arguments;
                return fn.apply(context, args);
            };
        },
        non_breaking_space: "\u00A0",
        increment_but_wrap_at: function(current_value, wrap_at, increment_by){
            var amount = increment_by || 1;

            current_value += amount;
            if(current_value >= wrap_at) current_value = 0;
            return current_value;
        },
        display_types: { //TODO: remove this - is it even used?
            block:  "block",
            inline: "inline"
        },
        looks_like_html: function(html_string_fragment){
            // Note that we cannot really succeed at identifying HTML
            // from a mere fragment but it works most of the
            // time and it's useful for users

            return (
                html_string_fragment.indexOf("<!DOCTYPE html") >= 0  ||
                html_string_fragment.indexOf("<html>") >= 0          ||
                html_string_fragment.indexOf("</html>") >= 0         ||
                html_string_fragment.indexOf("</p>") >= 0            ||
                html_string_fragment.indexOf("</a>") >= 0
            );
        },
        get_clipboard_xml_as_html_string: function(clipboard){
            var html_string = "",
                mimetypes_ordered_by_preference_last_most_prefered = ["Text", "text/plain", "text/html", "text/xml"],
                mimetype,
                i;

            for(i = 0; i < mimetypes_ordered_by_preference_last_most_prefered.length; i++){
                mimetype = mimetypes_ordered_by_preference_last_most_prefered[i];
                if(clipboard.types && clipboard.types.indexOf(mimetype) >= 0) {
                    html_string = clipboard.getData(mimetype).toString();
                }
            }
            return html_string;
        },
        set_theme: function(theme_name, instance){
            var filter_themes = function(element){
                    if(!element) return;
                    element.className = (element.className + " ").replace(/doctored-theme-[^ ]*?/g, '');
                    element.classList.add(theme_name);
                };

            instance.reset_theme();

            theme_name = doctored.CONSTANTS.theme_prefix + theme_name.toLowerCase().replace(/[^a-z]/g, '');

            filter_themes(instance.root);
            filter_themes(instance.dialog);
            filter_themes(instance.menu);
            filter_themes(instance.hamburger_menu);
            filter_themes(instance.tooltip);
            filter_themes(instance.view_source_textarea);
            filter_themes(instance.view_source_resizer);
            filter_themes(instance.tabs);

        },
        prevent_default: function(event){
            event.preventDefault();
        },
        alert: function(text){
            var epoch = Date.now();
            var result = alert(text);
            if(epoch + doctored.CONSTANTS.dialog_supression_wait_milliseconds > Date.now()) { // the confirm() above should ask the user for an answer but if they respond null and their response was within a few milliseconds then it's likely that the browser is blocking alert/confirm so write an appropriate error message to console.log
                console.log("DOCTORED.JS WARNING: Seems that window.confirm() is supressed by the browser.");
            }
            return result;
        },
        confirm: function(text){
            var epoch = Date.now();
            var result = confirm(text);
            if(epoch + doctored.CONSTANTS.dialog_supression_wait_milliseconds > Date.now()) { // the confirm() above should ask the user for an answer but if they respond null and their response was within a few milliseconds then it's likely that the browser is blocking alert/confirm so write an appropriate error message to console.log
                console.log("DOCTORED.JS WARNING: Seems that window.confirm() is supressed by the browser.");
            }
            return result;
        },
        prompt: function(text, value){
            var epoch = Date.now();
            var result = prompt(text, value);
            if(epoch + doctored.CONSTANTS.dialog_supression_wait_milliseconds > Date.now()) { // the confirm() above should ask the user for an answer but if they respond null and their response was within a few milliseconds then it's likely that the browser is blocking alert/confirm so write an appropriate error message to console.log
                console.log("DOCTORED.JS WARNING: Seems that window.prompt() is supressed by the browser.");
            }
            return result;
        },
        get_tab_index: function(tab){
            var i = 0;

            while((tab = tab.previousSibling) !== null) i++;
            return i;
        },
        parse_attributes_string: function(attributes_string){
            // although it would be easier to use the browsers DOM
            // to parse the attributes string (e.g. in a detached element)
            // that would make it potentially exploitable
            // eg a string of "<a onload='alert(\'deal with it\')'/>"
            // so we're doing string parsing even though it's a bit weird
            var attributes_regex = /([\-A\-Z:a-zA-Z0-9_]+)(?:\s*=\s*(?:(?:"((?:\\.|[^"])*)")|(?:'((?:\\.|[^'])*)')|([^>\s]+)))?/g, // originally via but tweaked to support xmlns http://ejohn.org/files/htmlparser.js
                attributes = {};

            attributes_string.replace(attributes_regex, function(match, name){
                var value = arguments[2] ? arguments[2] :
                                arguments[3] ? arguments[3] :
                                    arguments[4] ? arguments[4] : name;

                attributes[name] = value;
            });
            return attributes;
        },
        convert_xml_to_doctored_html: function(xml, inline_elements){
            return xml.replace(/<\?[\s\S]*?\?>/, '')
                      .replace(/<![\s\S]*?>/g, '')
                      .replace(/<([\s\S]*?)>/g, function(match, contents, offset, s){
                            var element_name,
                                attributes = "",
                                after = "",
                                display = doctored.CONSTANTS.block_class,
                                self_closing = match.substr(match.length - 2, 1) === "/";

                            if(match.substr(0, 2) === "</"){
                                return '</div>';
                            } else if(match.indexOf(" ") === -1){
                                if(self_closing){
                                    element_name = match.substr(1, match.length - 3);
                                } else {
                                    element_name = match.substr(1, match.length - 2);
                                }
                            } else {
                                element_name = match.substr(1, match.indexOf(" ") - 1);
                                match = match.substr(match.indexOf(" "));
                                attributes = ' data-attributes="' +
                                             doctored.util.encode_data_attributes(doctored.util.parse_attributes_string(match.substr(0, match.length - 1))) +
                                             '"';
                            }

                            if(self_closing) {
                                after = "</div>";
                            }

                            if(inline_elements && inline_elements.indexOf(element_name) >= 0) {
                                display = doctored.CONSTANTS.inline_class;
                            }
                            return '<div class="' + display + '" data-element="' + element_name + '"' + attributes + '>' + after;
                    });
        },
        encode_data_attributes: function(attributes){
            var sanitised_attributes = {},
                first_letter,
                key,
                index = 0;

            for(key in attributes){
                index += 1;
                key = key.replace(/["'<>&]/g, '').trim();
                if(!key.match(/^[a-zA-Z_:][\-a-zA-Z0-9_:.]/)){
                    key = "INVALID" + index + key;
                }
                sanitised_attributes[key] = attributes[key];
            }
            return JSON.stringify(sanitised_attributes).replace(/"/g, "&quot;");
        },
        gather_attributes: function(attribute_pairs){
            var attributes = {},
                attribute_pair,
                key,
                value,
                i;

            for(i = 0; i < attribute_pairs.length; i++){
                attribute_pair = attribute_pairs[i];
                key = attribute_pair.childNodes[0].value.replace(/\s/g, '');
                value = attribute_pair.childNodes[2].value;
                if(key.length && value.length){
                    attributes[key] = value;
                }
            }
            return attributes;
        },
        descend_building_xml: function(nodes, depth){
            var i,
                node,
                xml_string = "",
                attributes_string,
                data_element,
                text_node,
                display_type,
                escape_text = doctored.util.escape_text,
                has_children;
            
            if(depth === undefined) depth = 0;

            for(i = 0; i < nodes.length; i++){
                node = nodes[i];
                switch(node.nodeType){
                    case Node.ELEMENT_NODE:
                        data_element = node.getAttribute("data-element");
                        if(!data_element) data_element = "ERROR-NO-DATA-ELEMENT-ATTRIBUTE-FOUND";
                        xml_string += "<" + data_element;
                        attributes_string = node.getAttribute("data-attributes");
                        if(attributes_string) {
                            xml_string += doctored.util.build_xml_attributes_from_json_string(attributes_string.replace(/&quot;/g, '"'));
                        }
                        has_children = node.hasChildNodes();
                        if(!has_children) {
                            xml_string += " /";
                        }
                        xml_string += ">";
                        if(depth === 0){
                            xml_string += "\n";
                        }
                        if(has_children) {
                            xml_string += doctored.util.descend_building_xml(node.childNodes, depth+1);
                            xml_string += "</" + data_element + ">";
                        }
                        if(depth === 1 && node.classList.contains(doctored.CONSTANTS.block_class)){
                            xml_string += "\n";
                        }
                        break;
                    case Node.TEXT_NODE:
                        text_node = node.innerHTML || node.textContent || node.innerText;
                        if(text_node === undefined) break;
                        xml_string += escape_text(text_node.replace(/[\n]/g, " "));
                        break;
                }
            }
            return xml_string;
        },
        build_xml_attributes_from_map: function(attributes){
            var attributes_string = "",
                key;

            for(key in attributes) {
                attributes_string += " " +
                                     key.toString() +
                                     "=\"" +
                                     attributes[key].replace(/"/g, "&quot;") +
                                     "\"";
            }
            return attributes_string;
        },
        build_xml_attributes_from_json_string: function(json_string){
            if(!json_string || json_string.length === 0) return "";
            return doctored.util.build_xml_attributes_from_map(JSON.parse(json_string));
        },
        offer_download: function(xml, filename){
            var blob = new Blob([xml], {type: "text/xml;charset=utf-8"});
            filename = filename || "download.xml";
            window.saveAs(blob, filename);
        },
        remove_old_selection: function(selection, dialog){
            // remove old doctored selection (which is a `div.doctored-selection`).
            // replace it with its childnodes
            // the element must still contain the "doctored-selection" class or else it's probably
            // been turned into an 'element' (in the Doctored sense) -- part of the document
            // we generate rather than an element used to express text selection)
            if(!selection || !selection.classList.contains("doctored-selection")) return;
            var parentNode,
                selection_childNodes_length = selection.childNodes.length, // NOTE: not a terrible optimisation but rather it's necessary to make a note of this because it will keep changing
                i;
            
            parentNode = selection.parentNode;
            if(!parentNode) return;
            for(i = 0; i < selection_childNodes_length; i++){
                parentNode.insertBefore(selection.childNodes[0], selection); //NOTE is [0] because the childNode[0] keeps pointing to the new first child as we move them
            }
            parentNode.removeChild(selection);
            delete dialog.target;
        },
        insert_html_at_cursor_position: function(html, paste_event){
            var range,
                nodes,
                selection;
        
            paste_event.returnValue = false;
            selection = doctored.util.get_current_selection();
            range = selection.getRangeAt(0);
            nodes = range.createContextualFragment(html);
            range.insertNode(nodes);
        },
        surround_selection_with_element: function(nodeName, classNames, instance, selection, mouse){
            var range = selection.getRangeAt(0).cloneRange(),
                element,
                boundaries;
            
            if(range.toString().length === 0) return;
            element = document.createElement(nodeName);
            element.className = classNames;
            instance.dialog.target = element;
            try {
                range.surroundContents(element);
                selection.removeAllRanges();
                selection.addRange(range);
                selection.removeAllRanges();
            } catch(e) {
                // typically "BAD_BOUNDARYPOINTS_ERR: DOM Range Exception 1" when selecting across mutiple elements.
                // E.g. the selection in the following structure where square brackets indicate selection "<p>ok[this</p><p>bit]ok</p>".
                // So in this case we just display a confusing and bewildering message in a tooltip (if you can think of better wording, let me know!)
                // We leave the text selection there though because they might continue expanding it to make it valid
                boundaries = element.getBoundingClientRect();
                if(!mouse) mouse = {x:boundaries.left, y:boundaries.top};
                doctored.util.this_function(instance.show_tooltip, instance)("Invalid selection", mouse.x + document.body.scrollLeft, mouse.y + document.body.scrollTop);
                return;
            }
            return element;
        },
        remove_excessive_whitespace: function(content){
            return content.trim().replace(/[\n\t]/g, " ").replace(/  /g, " ");
        },
        get_uuid: function(){
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) { // credit to http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript
                var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                return v.toString(16);
            });
        },
        display_element_dialog: function(target, dialog, mouse, context_element, schema){
            var this_function = doctored.util.this_function,
                is_root       = target.classList.contains("doctored"),
                is_inline     = target.classList.contains(doctored.CONSTANTS.inline_class),
                schema_attributes,
                attributes_string,
                attributes_item,
                attributes,
                target_offset,
                key,
                i;

            dialog.schema_chooser.style.display       = (is_root ? "" : "none");
            dialog.schema_chooser_title.style.display = (is_root ? "" : "none");
            dialog.root_element_title.style.display   = (is_root ? "" : "none");
            dialog.attributes_div.style.display       = "";
            dialog.attributes_title.style.display     = "";
            dialog.add_siblings.style.display         = (is_root || is_inline ? "none" : "");
            if(mouse === undefined){
                target_offset = target.getBoundingClientRect();
                mouse = {x:target_offset.left, y:target_offset.top};
            }
            dialog.style.left = mouse.x + "px";
            dialog.style.top  = mouse.y + "px";
            dialog.mode = "editElement";
            dialog.target = target;

            for(i = dialog.attributes_div.childNodes.length - 2; i >= 0; i--){ // we leave the last one present, because it's never used
                dialog.attributes_div.removeChild(dialog.attributes_div.childNodes[i]);
            }

            attributes_string = dialog.target.getAttribute("data-attributes");
            if(attributes_string) {
                attributes = JSON.parse(attributes_string.replace(/&quot;/g, '"'));
                for(key in attributes){
                    schema_attributes = schema.attributes[key];
                    doctored.util.dialog_append_attribute(dialog, key, attributes[key], schema_attributes ? schema_attributes.help : undefined);
                }
            }
            this_function(schema.set_dialog_context, schema)(dialog, context_element, target.getAttribute("data-element"), attributes);
            doctored.util.set_element_chooser_to_element(target, dialog.element_chooser);
            dialog.element_chooser.focus();
            dialog.style.display = "block";
        },
        process_schema_groups: function(items, depth){
            var i = 0,
                item,
                html = '',
                depth_padding;

            depth = depth || 1;
            depth_padding = (new Array(depth)).join("&nbsp; ");
            for(i = 0; i < items.length; i++){
                item = items[i];
                if(item.children) { // Nested optgroups aren't really supported (the browser will flatten them, like nested paragraphs) so we fake it http://stackoverflow.com/questions/1037732/nesting-optgroups-in-a-dropdownlist-select
                    html += '<optgroup label="' + depth_padding + item.schema_family +'">' + doctored.util.process_schema_groups(item.children, depth + 1) + '</optgroup>';
                } else {
                    html += '<option value="' + item.schema + '" data-schema-family="' + item.schema_family + '">' + depth_padding + item.label + '</option>';
                }
            }
            return html;
        },
        is_webkit: !!window.webkitURL,
        process_linebreaks: function(brs){
            var br,
                block,
                block_clone,
                i,
                y,
                node,
                $ = doctored.$,
                to_delete = [];

            brs = Array.prototype.slice.call(brs);
            for(i = 0; i < brs.length; i++){
                br = brs[i];
                if(br && br.classList.contains(doctored.CONSTANTS.intentional_linebreak)) continue;
                block = doctored.util.get_closest_block(br);
                block_clone = block.cloneNode(true);
                block.parentNode.insertBefore(block_clone, block);
                node = $("br", block_clone)[0]; //FIXME this matches intentional inline BRs...should be a loop instead that filters those out (or a better selector that avoids them?)
                to_delete.push(node);
                while(node) {
                    if(node.classList && node.classList.contains(doctored.CONSTANTS.block_class)) {
                        node = undefined;
                    } else if(node.nextSibling){
                        to_delete.push(node.nextSibling);
                        node = node.nextSibling;
                    } else if(node.parentNode){
                        node = node.parentNode;
                    }
                }
                node = br;
                to_delete.push(node);
                while(node) {
                    if(node.classList && node.classList.contains(doctored.CONSTANTS.block_class)) {
                        node = undefined;
                    } else if(node.previousSibling){
                        to_delete.push(node.previousSibling);
                        node = node.previousSibling;
                    } else if(node.parentNode){
                        node = node.parentNode;
                    }
                }
                for(y = 0; y < to_delete.length; y++){
                    node = to_delete[y];
                    node.parentNode.removeChild(node);
                }
            }
        },
        to_xml_string: function(xml_element){
            return (new XMLSerializer()).serializeToString(xml_element);
        },
        get_closest_block: function(element) {
            if(element.classList.contains(doctored.CONSTANTS.block_class) || element.classList.contains(doctored.CONSTANTS.doctored_container_class)) return element;
            return doctored.util.get_closest_block(element.parentNode);
        },
        get_closest_by_nodeName: function(element, nodeName){
            if(element.nodeName.toLowerCase() === nodeName.toLowerCase()) return element;
            return doctored.util.get_closest_by_nodeName(element.parentNode, nodeName);
        },
        dialog_append_attribute: function(dialog, key, value, title){
            var attributes_item = dialog.attributes_template.cloneNode(true);
            attributes_item.childNodes[0].value = key;
            attributes_item.childNodes[2].value = value;
            if(title) attributes_item.setAttribute("title", title);
            dialog.attributes_div.insertBefore(attributes_item, dialog.attributes_add);
        },
        get_current_selection: function(){
            return window.getSelection() || document.getSelection() || (document.selection ? document.selection.createRange() : null);
        },
        simple_map_clone: function(map){
            // copies values into new map
            var new_map = {},
                key;

            for(key in map){
                if(map.hasOwnProperty(key)){
                    new_map[key] = map[key];
                }
            }
            return new_map;
        },
        regexIndexOf: function(target, regex, startpos){
            var indexOf = target.substring(startpos || 0).search(regex);
            return (indexOf >= 0) ? (indexOf + (startpos || 0)) : indexOf;
        },
        set_element_chooser_to_element: function(element, element_chooser, schema){
            var data_element = element.getAttribute("data-element"),
                option,
                i;

            if(!data_element) {
                element_chooser.selectedIndex = 0;
                return;
            }
            for(i = 0; i < element_chooser.options.length; i++){
                if(element_chooser.options[i].text === data_element) {
                    element_chooser.selectedIndex = i;
                    return;
                }
            }
            option = document.createElement("option");
            option.text = data_element;
            if(schema && schema.inline_elements && schema.inline_elements.indexOf(element) >= 0){
                option.setAttribute("class", "inline");
            } else {
                option.setAttribute("class", "block");
            }
            element_chooser.appendChild(option);
            element_chooser.selectedIndex = element_chooser.options.length - 1;
        },
        display_dialog_around_inline: function(inline, dialog, mouse, schema){
            var offsets = doctored.util.inlineOffset(inline);
            
            if(mouse){
                offsets.mouse_differences = {before_x: Math.abs(mouse.x - offsets.before.left), after_x: Math.abs(mouse.x - offsets.after.left)};
            }
            dialog.schema_chooser.style.display       = "none";
            dialog.schema_chooser_title.style.display = "none";
            dialog.root_element_title.style.display   = "none";
            dialog.attributes_div.style.display       = "none";
            dialog.attributes_title.style.display     = "none";
            dialog.add_siblings.style.display         = "none";
            dialog.style.display = "block"; //must be visible to obtain width/height
            offsets.dialog = {width: dialog.offsetWidth, height: dialog.offsetHeight};

            if(mouse && offsets.mouse_differences.before_x > offsets.mouse_differences.after_x) {  //move dialog to one end of selection, depending on which end is closest (horizontally) to mouse pointer / finger touch
                offsets.proposed = {x: offsets.after.left, y: offsets.after.top - offsets.dialog.height};
            } else {
                offsets.proposed = {x: offsets.before.left - offsets.dialog.width, y: offsets.before.top - dialog.offsetHeight};
            }
            if(offsets.proposed.x < 1) {
                offsets.proposed.x = 1;
            } else if(offsets.proposed.x > window.innerWidth - offsets.dialog.width) {
                offsets.proposed.x = window.innerWidth - offsets.dialog.width;
            }
            if(offsets.proposed.y < 1) {
                offsets.proposed.y = 1;
            }
            dialog.style.left = document.body.scrollLeft + offsets.proposed.x + "px";
            dialog.style.top  = document.body.scrollTop + offsets.proposed.y + "px";
            dialog.mode = "createElement";
            schema.set_dialog_context(dialog, inline.parentNode.getAttribute("data-element"));
            doctored.util.set_element_chooser_to_element(inline, dialog.element_chooser, schema);
            dialog.element_chooser.focus();
        },
        inlineOffset: function() {
            //NOTE: this is a closure - actual function returned below
            var i_before = document.createElement("i"),
                i_after  = document.createElement("i");

            return function(sender){
                var parentNode = sender.parentNode,
                    element_before_offset,
                    element_after_offset;

                parentNode.insertBefore(i_before, sender);
                parentNode.insertBefore(i_after,  sender.nextSibling);
                element_before_offset = i_before.getBoundingClientRect();
                element_after_offset  = i_after .getBoundingClientRect();
                parentNode.removeChild(i_before);
                parentNode.removeChild(i_after);
                return {
                        before: element_before_offset,
                        after:  element_after_offset
                };
            };
        }(),
        within_pseudoelement: function(target, position){
            var within = false,
                target_offset;
                
            if(position === undefined) return false;
            target_offset = target.getBoundingClientRect();
            if(target.classList.contains(doctored.CONSTANTS.inline_class)       && position.y > target_offset.top  - doctored.CONSTANTS.inline_label_height_in_pixels + target.offsetHeight) {
                within = doctored.CONSTANTS.edit_element_css_cursor;
            } else if(target.classList.contains(doctored.CONSTANTS.block_class) && position.x < target_offset.left + doctored.CONSTANTS.block_label_width_in_pixels) {
                within = doctored.CONSTANTS.edit_element_css_cursor;
            }
            return within;
        },
        rename_keys: function(attributes, attribute_mapping){
            var new_attributes = {},
                key;

            for(key in attributes) {
                if(attribute_mapping[key]) {
                    new_attributes[attribute_mapping[key]] = attributes[key];
                } else {
                    new_attributes[key] = attributes[key];
                }
            }
            return new_attributes;
        },
        range: function(i) {
            // returns [1,2,3...i]
            // ARGUMENTS
            // i is the upper limit
            /* jshint ignore:start */
            for(var list=[];list[--i]=i;){} //this code works and is intentional; it's not a mistake thanks to good ole variable hoisting
            /* jshint ignore:end */
            return list;
        },
        simple_transform: function(markup, element_mapping, attribute_mapping) {
            // transform one markup format
            // replaces elements and attribute keys with a simple 1:1 mapping, and returns the resulting string. attribute values are left the same.
            // ARGUMENTS
            // markup = html string
            // element_mapping = {oldNodeName1: newNodeName1, oldNodeName2: newNodeName2}
            // attribute_mapping = {oldkey1: newkey1, oldkey2: newkey2}
            return markup.replace(/<(.*?)>/g, function(match, contents, offset, s){
                var is_a_closing_tag = (match.substr(1, 1) === '/'),
                    element_name,
                    attributes,
                    attributes_string = "",
                    new_element_name,
                    indexOf_whitespace = match.indexOf(" "); // indexOf doesn't support regex, so we'll just match spaces. TODO: make this support other whitespace a la http://stackoverflow.com/questions/273789/is-there-a-version-of-javascripts-string-indexof-that-allows-for-regular-expr

                if(is_a_closing_tag) { //...is...a...closing...tag
                    element_name = match.substr(2, match.length - 3);
                } else if(indexOf_whitespace === -1){ //is an opening tag without attributes
                    element_name = match.substr(1, match.length - 2);
                } else { //is an opening tag WITH attributes
                    element_name = match.substr(1, indexOf_whitespace - 1);
                    attributes   = doctored.util.parse_attributes_string(
                                        match.substr(indexOf_whitespace)
                                   );
                    attributes   = doctored.util.rename_keys(attributes, attribute_mapping);
                    attributes_string = doctored.util.build_xml_attributes_from_map(attributes);
                }
                new_element_name = element_mapping[element_name] || element_name;
                return "<" +
                       (is_a_closing_tag ? "/" : "") +
                       new_element_name +
                       attributes_string +
                       ">";
            });
        },
        lint_response: function(errors, max_lines){
            var by_line = {},
                error_line,
                child_node,
                line_number,
                i,
                error;

            for(i = 0; i < errors.error_lines.length; i++){
                error_line = errors.error_lines[i];
                if(error_line.line_number === 0) error_line.line_number = 1; //line 0 and 1 are aggregated for the purposes of display
                if(by_line[error_line.line_number] === undefined) {
                    by_line[error_line.line_number] = [];
                }
                by_line[error_line.line_number].push(error_line);
            }
            if(errors.error_summary && errors.error_summary.message !== undefined){
                if(by_line[1] === undefined){
                    by_line[1] = [];
                }
                by_line[1].push(errors.error_summary);
            }
            return by_line;
        },
        format_lint_errors: function(line_errors){
            var i,
                error_string = "";
            for(i = 0; i < line_errors.length; i++){
                error_string += line_errors[i].message + ". ";
            }
            return error_string;
        },
        file_extension: function(uri){
            return uri.substr(uri.lastIndexOf(".") + 1);
        },
        escape_text: function(){
            // Note is a closure
            var _escape_chars = {
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    "'": "&apos;",
                    '"': "&quot;"
                },
                _escape = function(char){
                    return _escape_chars[char];
                };

            return function(str){
                return str.replace(/[&<>"']/g, _escape);
            };
        }(),
        strip_tags: function(markup){
            return markup.replace(/<.*?>/g, '');
        },
        to_options_tags: function(list, complex){
            var escape_text = doctored.util.escape_text,
                element_properties,
                element_name,
                html = "",
                keys = Object.keys(list).sort(),
                key_index;

            if(complex === undefined) complex = true;
            for(key_index = 0; key_index < keys.length; key_index++){
                element_name       = keys[key_index];
                element_properties = list[element_name];

                html += '<option value="';
                if(complex) {
                    html += element_properties.display.replace(/"/g, "&quot;") + '"';
                    if(element_properties.help) html += ' title="' + element_properties.help.replace(/"/g, "&quot;") + '"';
                } else {
                    html += element_properties.replace(/"/g, "&quot;");
                }

                html += '>' +
                        escape_text(element_name) +
                        '</option>';
                }
            return html;
        }
    };
}());