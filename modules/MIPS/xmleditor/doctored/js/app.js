/*globals doctored, alert, console*/
(function(){
    "use strict";

    var defaults = {
            autosave_every_milliseconds:       30 * 1000,
            linting_debounce_milliseconds:     1000,
            retry_init_after_milliseconds:     50,
            view_source_debounce_milliseconds: 1000 / 60,
            schema:                            "/DocBook/DocBook 5.0.rng", //default... a key from doctored.schemas in app-formats.js ... may be overridden in call to init() or by per-user settings in localStorage
            theme:                             "flat" //key from options in hamburger_menu.theme_chooser
        },
        $ = doctored.$,
        $body = $('body')[0];
       
    doctored.event.on("app:ready", function(){ // called after all manifest items (in doctored.js) are loaded
        var i,
            instance;

        doctored.init = doctored._init; // no need to delay loading any future calls, overwrite with the real thing
        for(i = 0; i < doctored._to_be_initialized.length; i++){
            instance = doctored._to_be_initialized[i];
            doctored.init(instance.selector, instance.options);
        }
        delete doctored._to_be_initialized; //don't need this anymore.. delete and let it be garbage collected
        doctored.ready = true;
    });

    doctored._init = function(selector, options){ // returns an instance of the editor
        var root_element = $(selector),
            instance,
            property;

        if(!root_element) return console.log("Doctored.js: Unable to find the element selected by: " + selector);

        options = options || {};
        for (property in defaults) {
            if (options.hasOwnProperty(property)) continue;
            options[property] = defaults[property];
        }

        instance = {
            doctored: 1,
            root: root_element,
            root_selector: selector,
            options: options,
            cache: {},
            init: function(){ //initialize one instance of the editor
                var _this = this,
                    this_function = doctored.util.this_function,
                    theme = window.localStorage.getItem("doctored-theme"),
                    container = document.createElement("div"),
                    boundaries,
                    manifest,
                    tab_current_index,
                    menu,
                    tab,
                    xml,
                    i;
                
                this.root.innerHTML = "";
                this.lint_soon = doctored.util.debounce(_this.lint, _this.options.linting_debounce_milliseconds, _this);
                this.id = this.options.id || this.root_selector.replace(/[#-\[\]]/g, "").replace(/\s/g, "").toLowerCase();
                //window.localStorage.removeItem("doctored-manifest-" + this.id);
                manifest = window.localStorage.getItem("doctored-manifest-" + this.id);
                tab_current_index = window.localStorage.getItem("doctored-tab-current-index-" + this.id);
                this.root.contentEditable = true;
                this.root.className = doctored.CONSTANTS.doctored_container_class;
                this.root.addEventListener("input",     this_function(this.lint_soon, this), false);
                this.root.addEventListener('paste',     this_function(this.paste, this), false);
                this.root.addEventListener('mouseup',   this_function(this.click, this), false);
                this.root.addEventListener('touchend',  this_function(this.click, this), false);
                this.root.addEventListener('keyup',     this_function(this.keyup_contentEditable, this), false);
                this.root.addEventListener('keyup',     doctored.util.debounce(this.keyup_contentEditable_sync_view_source, _this.options.view_source_debounce_milliseconds, this), false);
                this.root.addEventListener('mousemove', this_function(this.mousemove, this), false);
                this.menu = document.createElement('menu');
                this.menu.className = "doctored-menu";
                this.dialog = document.createElement('menu');
                this.dialog.className = "doctored-dialog";
                this.dialog.addEventListener('keyup',   this_function(this.keyup_dialog_esc, this), false);
                this.dialog.innerHTML = '<a href title="Close">&times;</a><h6>schema</h6><select></select>' +
                                        '<h6>root element</h6><select id="' + this.id + '_elements" title="Change element"><optgroup label="Suggested elements in this context">' +
                                        '<option value="" disabled class="doctored-loading">Loading...</option></optgroup></select>' +
                                        '<h6>attributes</h6><div class="doctored-attributes"></div>';
                this.dialog.close = $('a', this.dialog)[0];
                this.dialog.close.addEventListener('click', this_function(this.close_dialog, this), false);
                this.dialog.schema_chooser = $('select', this.dialog)[0];
                this.dialog.schema_chooser.addEventListener('change', this_function(this.schema_chooser_change, this), false);
                this.dialog.schema_chooser_title = $('h6', this.dialog)[0];
                this.dialog.attributes_title = $('h6', this.dialog)[2];
                this.dialog.element_chooser = $('select', this.dialog)[1];
                this.dialog.element_chooser.addEventListener('blur', this_function(this.element_chooser_change, this), false);
                this.dialog.element_chooser.addEventListener('mouseup', this_function(this.element_chooser_change, this), false);
                this.dialog.root_element_title = $('h6', this.dialog)[1];
                this.dialog.attributes_div = $('div', this.dialog)[0];
                this.dialog.attributes_div.addEventListener('keyup',   this_function(this.keyup_dialog_attributes_enter, this), false);
                this.dialog.attributes_template = document.createElement("div");
                this.dialog.attributes_template.innerHTML = '<input class="doctored-attribute-name">=<input class="doctored-attribute-value">';
                this.dialog.attributes_add = this.dialog.attributes_template.cloneNode(true);
                this.dialog.attributes_add.childNodes[0].addEventListener("focus", this_function(this.add_attribute_item_key, this), false);
                this.dialog.attributes_add.childNodes[2].addEventListener("focus", this_function(this.add_attribute_item_value, this), false);
                this.dialog.attributes_div.appendChild(this.dialog.attributes_add);
                this.dialog.add_siblings = document.createElement("div");
                this.dialog.add_siblings.className = "doctored-add-siblings";
                this.dialog.appendChild(this.dialog.add_siblings);
                this.dialog.add_sibling_above = document.createElement("a");
                this.dialog.add_sibling_above.className = "doctored-add-sibling-above";
                this.dialog.add_sibling_above.innerHTML = "&#x25B2;";
                this.dialog.add_sibling_above.setAttribute("title", "Add element before");
                this.dialog.add_sibling_above.addEventListener("click", this_function(this.clone_element_above, this), false);
                this.dialog.add_siblings.appendChild(this.dialog.add_sibling_above);
                this.dialog.add_sibling_below = document.createElement("a");
                this.dialog.add_sibling_below.className = "doctored-add-sibling-below";
                this.dialog.add_sibling_below.innerHTML = "&#x25BC;";
                this.dialog.add_sibling_below.setAttribute("title", "Add element after");
                this.dialog.add_sibling_below.addEventListener("click", this_function(this.clone_element_below, this), false);
                this.dialog.add_siblings.appendChild(this.dialog.add_sibling_below);
                this.tabs = document.createElement("ul");
                this.tabs.className = "doctored-tabs";
                this.tabs.tab_item_template = document.createElement("li");
                this.tabs.tab_item_template.innerHTML = '<span>Untitled.xml</span> <a href title="Delete" class="doctored-delete">&times;</a>';
                this.tabs.innerHTML = '<li class="doctored-new-document"><a href title="New document">+</a></li>';
                this.tabs.addEventListener('click', doctored.util.prevent_default, false);
                this.tabs.addEventListener('mousedown', this_function(this.tabs_drag_start, this), false);
                this.tabs.addEventListener('touchstart', this_function(this.tabs_drag_start, this), false);
                this.tabs.dragging = false;
                this.tabs.new_document = $(".doctored-new-document", this.tabs)[0];
                this.tabs.new_document.addEventListener('click', this_function(this.tabs_add_tab, this), false);
                this.menu.innerHTML = '<a class="doctored-properties" href="" title="Document Properties">Document</a><a class="doctored-view-source" href="">View Source</a><a class="doctored-download" href="">Download</a>';
                container.innerHTML = '<a class="doctored-hamburger-button" href title="Doctored.js Configuration">&#9776;</a>';
                this.hamburger_button = container.childNodes[0];
                this.hamburger_button.addEventListener('click', this_function(this.hamburger_button_click, this), false);
                this.hamburger_menu = document.createElement("menu");
                this.hamburger_menu.className = "doctored-hamburger";
                this.hamburger_menu.innerHTML = '<a href title="Close">&times;</a><select><option value="" disabled selected>Choose Theme</option><option>Flat</option><option>Shadow</option><option>High Contrast</option></select>';
                this.hamburger_menu.theme_chooser = $("select", this.hamburger_menu)[0];
                this.hamburger_menu.theme_chooser.addEventListener('change', this_function(this.hamburger_change_theme, this), false);
                this.hamburger_menu.close = $("a", this.hamburger_menu)[0];
                this.hamburger_menu.close.addEventListener('click', this_function(this.hamburger_close, this), false);
                this.menu.properties_button = $(".doctored-properties", this.menu)[0];
                this.menu.properties_button.addEventListener('click', this_function(this.properties, this), false);
                this.menu.download = $(".doctored-download", this.menu)[0];
                this.menu.download.addEventListener('click', this_function(this.download, this), false);
                this.menu.view_source = $(".doctored-view-source", this.menu)[0];
                this.menu.view_source.addEventListener('click', this_function(this.view_source, this), false);
                this.tooltip = document.createElement('samp');
                this.tooltip.className = "doctored-tooltip";
                this.tooltip.addEventListener(doctored.util.transition_end_event, this_function(this.hide_tooltip, this), false);
                this.view_source_textarea = document.createElement('textarea');
                this.view_source_textarea.classList.add("doctored-view-source-textbox");
                this.view_source_textarea.addEventListener('keyup', doctored.util.debounce(this.view_source_change, this.options.view_source_debounce_milliseconds, this), false);
                this.view_source_resizer = document.createElement('div');
                this.view_source_resizer.classList.add("doctored-view-source-resizer");
                this.view_source_resizer.addEventListener('mousedown', this_function(this.view_source_resizer_drag_start, this), false);
                this.view_source_resizer.addEventListener('touchstart', this_function(this.view_source_resizer_drag_start, this), false);
                this.view_source_resizer.dragging = false;
                this.view_source_textarea.style.display = "none";
                doctored.util.set_theme(theme || this.options.theme, this);
                this.root.parentNode.insertBefore(this.hamburger_button, this.root);
                this.root.parentNode.insertBefore(this.tabs, this.root);
                this.root.parentNode.insertBefore(this.menu, this.root);
                this.root.parentNode.insertBefore(this.dialog, this.menu);
                this.root.parentNode.insertBefore(this.tooltip, this.dialog);
                this.root.parentNode.insertBefore(this.hamburger_menu, this.tooltip);
                this.root.parentNode.insertBefore(this.view_source_textarea, this.hamburger_menu);
                this.root.parentNode.insertBefore(this.view_source_resizer, this.view_source_textarea);

                if(manifest) {
                    this.manifest = JSON.parse(manifest);
                    for(i = 0; i < this.manifest.length; i++){
                        tab = this.manifest[i];
                        this_function(this.tabs_add_tab, this)(undefined, tab.filename, tab.uuid, true);
                    }
                    if(!tab_current_index || tab_current_index >= this.manifest.length) {
                        tab_current_index = this.manifest.length - 1;
                    }
                    this_function(this.schema_chooser_init, this)(this.manifest[tab_current_index].schema);
                    this.tabs_select_tab(this.tabs.childNodes[tab_current_index]);
                } else {
                    this.manifest = [];
                    this_function(this.schema_chooser_init, this)();
                    this_function(this.tabs_add_tab, this)(undefined, undefined, undefined, false);
                    this_function(this.schema.new_document, this.schema)();
                }
                document.addEventListener('mousemove', this_function(this.drag, this), false);
                document.addEventListener('touchmove', this_function(this.drag, this), false);
                document.addEventListener('mouseup', this_function(this.drag_end, this), false);
                document.addEventListener('touchend', this_function(this.drag_end, this), false);
                window.addEventListener('beforeunload', this_function(this.beforeunload, this), false);

                if(this.options.onload) {
                    this_function(this.options.onload, this)();
                }
            },
            lint: function(){
                // send linting job to one of the workers
                // NOTE you probably shouldn't call this directly instead call lint_soon()
                var xml = this.get_xml_string();
                if(xml === false) return this.lint_soon();
                this.root.classList.remove("valid");
                this.root.classList.remove("invalid");
                doctored.linters.lint(xml, this.schema.schema_url, this.lint_response, instance);
            },
            lint_response: function(errors){
                // handle a linting response, and write it to the page
                var by_line = doctored.util.lint_response(errors, this.root.childNodes.length),
                    i,
                    child_node,
                    line_number = 0;

                for(i = 0; i < this.root.childNodes.length; i++){
                    child_node = this.root.childNodes[i];
                    if(child_node.nodeType === Node.ELEMENT_NODE){ //ignore text nodes etc
                        line_number += 1;
                        if(by_line[line_number]) {
                            child_node.setAttribute("data-error", doctored.util.format_lint_errors(by_line[line_number]));
                            child_node.classList.add("has_errors");
                            child_node.classList.remove("hide_errors");
                        } else {
                            child_node.setAttribute("data-error", "");
                            child_node.classList.remove("has_errors");
                            child_node.classList.add("hide_errors");
                        }
                    }
                }
                if(errors && errors.error_lines && errors.error_lines.length === 0 && (errors.error_summary === undefined || errors.error_summary.message.length === 0)) {
                    this.root.classList.add("valid");
                    this.root.classList.remove("invalid");
                } else {
                    this.root.classList.add("invalid");
                    this.root.classList.remove("valid");
                }
            },
            get_xml_string: function(){
                if(this.root.childNodes.length === 0) return false;

                return doctored.CONSTANTS.xml_declaration +
                       (this.schema.dtd ? this.schema.dtd : "") +
                       doctored.util.descend_building_xml([this.root]);
            },
            set_xml_string: function(xml_string){
                var new_document,
                    new_document_root,
                    doctored_html,
                    data_element,
                    data_attributes;

                doctored_html = doctored.util.convert_xml_to_doctored_html(xml_string, this.schema.inline_elements).trim();
                new_document = document.createElement('div');
                new_document.innerHTML = doctored_html;
                new_document_root = new_document.childNodes[0];
                data_element = new_document_root.getAttribute("data-element");
                data_attributes = new_document_root.getAttribute("data-attributes");
                this.root.setAttribute("data-element", data_element || "ROOT-ERROR-NO-DATA-ELEMENT");
                this.root.setAttribute("data-attributes", data_attributes || "");
                this.root.innerHTML = new_document_root.innerHTML;
                this.lint_soon();
            },
            beforeunload: function(){
                var localStorage_manifest           = "doctored-manifest-" + this.id,
                    localStorage_file               = "doctored-file-"     + this.tabs.current_tab.getAttribute("data-uuid"),
                    localStorage_selected_tab_index = "doctored-tab-current-index-" + this.id,
                    this_function                   = doctored.util.this_function,
                    xml                             = this_function(this.get_xml_string, this)(),
                    current_tab_index               = doctored.util.get_tab_index(this.tabs.current_tab),
                    _this                           = this;

                window.localStorage.setItem(localStorage_manifest, JSON.stringify(this.manifest));
                window.localStorage.setItem(localStorage_selected_tab_index, current_tab_index);
                if(xml) {
                    window.localStorage.setItem(localStorage_file, xml);
                }
            },
            tabs_click: function(event){
                var tab = doctored.util.get_closest_by_nodeName(event.target, "li"),
                    filename,
                    tab_index,
                    epoch,
                    uuid;

                if(tab.classList.contains("doctored-new-document")) return;
                if(event.target.classList.contains("doctored-delete")) {
                    if($("li", this.tabs).length <= 2) {
                        doctored.util.alert("Can't delete your last document.");
                    } else if(doctored.util.confirm("Are you sure you want to delete '" + $("span", tab)[0].innerText + "'")){
                        tab_index = doctored.util.get_tab_index(tab);
                        uuid = this.manifest[tab_index].uuid;
                        window.localStorage.removeItem("doctored-file-" + uuid);
                        this.manifest.splice(tab_index, 1);
                        this.tabs.removeChild(tab);
                        if(tab === this.tabs.current_tab) {
                            this.tabs_select_tab($("li", this.tabs)[0]);
                        }
                    }
                } else {
                    if(this.tabs.current_tab && tab === this.tabs.current_tab) {
                        filename = $("span", tab)[0].textContent;
                        filename = doctored.util.prompt("New filename?", filename);
                        if(filename) {
                            $("span", tab)[0].textContent = filename;
                            tab.setAttribute("title", filename);
                            this.manifest[doctored.util.get_tab_index(tab)].filename = filename;
                        }
                    } else {
                        this.tabs_select_tab(tab);
                    }
                }
            },
            tabs_select_tab: function(tab){
                var xml;

                if(this.tabs.current_tab) {
                    this.tabs.current_tab.classList.remove(doctored.CONSTANTS.current_tab_class);
                    xml = this.get_xml_string();
                    if(xml){
                        window.localStorage.setItem("doctored-file-" + this.tabs.current_tab.getAttribute("data-uuid"), xml);
                    }
                }
                this.tabs.current_tab = tab;
                this.tabs.current_tab.classList.add(doctored.CONSTANTS.current_tab_class);
                xml = window.localStorage.getItem("doctored-file-" + tab.getAttribute("data-uuid"));
                if(xml) {
                    this.set_xml_string(xml);
                }
                this.schema_chooser_init(this.manifest[doctored.util.get_tab_index(this.tabs.current_tab)].schema);
                this.dialog.style.display = "none";
            },
            tabs_add_tab: function(event, filename, uuid, dont_select_tab){
                var this_function = doctored.util.this_function,
                    computed_style,
                    able_to_fit,
                    span,
                    tab;

                if(this.tabs.count === undefined) this.tabs.count = 0;
                this.tabs.count += 1;
                tab = this.tabs.tab_item_template.cloneNode(true);
                if(filename){
                    $("span", tab)[0].textContent = filename;
                } else {
                    filename = $("span", tab)[0].textContent;
                    uuid = doctored.util.get_uuid();
                    this.manifest.push({
                        uuid:     uuid,
                        filename: filename,
                        schema:   this.dialog.schema_chooser.options[this.dialog.schema_chooser.selectedIndex].value
                    });
                }
                tab.setAttribute("data-uuid", uuid);
                tab.setAttribute("title", filename);
                this.tabs.insertBefore(tab, this.tabs.new_document);
                if(!dont_select_tab){
                    this.tabs_select_tab(tab);
                    this_function(this.schema.new_document, this.schema)();
                }
                if(this.tabs.default_tab_width === undefined) { // only run this once
                    span = $("span", tab)[0];
                    computed_style = window.getComputedStyle(span);
                    this.tabs.tab_span_default_width = parseInt(computed_style.width, 10);
                    //this.tabs.tab_span_horizontal_padding = parseInt(computed_style["padding-left"], 10) + parseInt(computed_style["padding-right"], 10);
                    this.tabs.tab_default_width = tab.offsetWidth;
                    this.tabs.tab_boilerplate_width = this.tabs.tab_default_width - this.tabs.tab_span_default_width;
                    computed_style = window.getComputedStyle(tab);
                    this.tabs.tab_default_horizontal_margin = parseInt(computed_style["margin-left"] || computed_style["marginLeft"], 10) + parseInt(computed_style["margin-right"] || computed_style["marginRight"], 10);
                    computed_style = window.getComputedStyle(this.tabs.new_document);
                    this.tabs.new_document_width_including_margins = this.tabs.new_document.offsetWidth + parseInt(computed_style["margin-left"] || computed_style["marginLeft"], 10) + parseInt(computed_style["margin-right"] || computed_style["marginRight"], 10);
                }
                able_to_fit = this.tabs_resize();
                if(able_to_fit === false) {
                    this.tabs.removeChild(tab);
                    this.tabs.count -= 1;
                    // we don't this.manifest.pop() because we leave entries there so as not to lose them
                    doctored.util.alert("Unable to fit any more tabs. Sorry!");
                }
                if(event) {
                    event.preventDefault();
                }
            },
            tabs_resize: function(){
                var available_width = this.tabs.offsetWidth - this.tabs.new_document_width_including_margins,
                    span_available_width = (available_width / this.tabs.count) - this.tabs.tab_boilerplate_width - this.tabs.tab_default_horizontal_margin,
                    spans,
                    span,
                    i;

                if(span_available_width > this.tabs.tab_span_default_width) {
                    span_available_width = this.tabs.tab_span_default_width;
                }
                if(span_available_width < doctored.CONSTANTS.minimum_tab_width) {
                    return false;
                }
                spans = $("span", this.tabs);
                for(i = 0; i < spans.length; i++){
                    span = spans[i];
                    span.style.width = span_available_width + "px";
                }
            },
            schema_chooser_init: function(schema_url){
                var this_function = doctored.util.this_function,
                    prefered_schema = schema_url || this.options.schema,
                    chosen_schema_option,
                    first_valid_option,
                    option,
                    i;

                this.dialog.schema_chooser.innerHTML = '<option value="" disabled>Choose Schema</option>' + doctored.util.process_schema_groups(doctored.schemas.list);
                for(i = 0; i < this.dialog.schema_chooser.options.length; i++){
                    option = this.dialog.schema_chooser.options[i];
                    if(option.value && !option.disabled){
                        if(first_valid_option === undefined) first_valid_option = i;
                        if(option.value === prefered_schema) {
                            this.dialog.schema_chooser.selectedIndex = i;
                            chosen_schema_option = option;
                        }
                    }
                }
                if(chosen_schema_option === undefined && first_valid_option) { // if nothing matched the instance's options schema
                    this.dialog.schema_chooser.selectedIndex = first_valid_option;
                    chosen_schema_option = this.dialog.schema_chooser.options[this.dialog.schema_chooser.selectedIndex];
                }
                if(!chosen_schema_option) return doctored.util.alert("Doctored.js can't find a valid default schema.");
                this.schema = doctored.schemas.get_schema_instance(this, chosen_schema_option.getAttribute('data-schema-family'), chosen_schema_option.value);
                this_function(this.schema.init, this.schema)(this, chosen_schema_option.value, false);
                this_function(this.lint_soon, this)();
            },
            schema_chooser_change: function(event){
                var new_document         = !!doctored.util.confirm("Do you want a new document for that schema?\n(WARNING: current document will be lost!)"),
                    chosen_schema_option = this.dialog.schema_chooser.options[this.dialog.schema_chooser.selectedIndex],
                    this_function        = doctored.util.this_function;

                if(!chosen_schema_option) return doctored.util.alert("No schema chosen");
                this.schema = doctored.schemas.get_schema_instance(this, chosen_schema_option.getAttribute('data-schema-family'), chosen_schema_option.value);
                this_function(this.schema.init, this.schema)(this, chosen_schema_option.value, new_document);
                this.manifest[doctored.util.get_tab_index(this.tabs.current_tab)].schema = chosen_schema_option.value;
                this.dialog.style.display = "none";
                this.root.focus();
            },
            paste: function(event){
                var html = doctored.util.get_clipboard_xml_as_html_string(event.clipboardData),
                    this_function = doctored.util.this_function,
                    doctored_html;

                if(this.schema.convert_from_html && doctored.util.looks_like_html(html)) {
                    event.returnValue = false;
                    setTimeout(function(){ //for some reason in Chrome it runs confirm twice when it's not in a setTimeout. Odd, suspected browser bug.
                        if(doctored.util.confirm("That looks like HTML - want to convert it to " + this.schema.name + "?")) {
                            html = this.schema.convert_from_html(html);
                        }
                        doctored_html = doctored.util.convert_html_to_doctored_html(html);
                        doctored.util.insert_html_at_cursor_position(doctored_html, event);
                    }, 0);
                    return;
                }
                doctored_html = doctored.util.convert_xml_to_doctored_html(html);
                doctored.util.insert_html_at_cursor_position(doctored_html, event);
                this_function(this.lint_soon, this)();
            },
            element_chooser_change: function(event){
                var element_chooser,
                    element_chooser_option,
                    element_chooser_text,
                    element_chooser_value;

                if(this.cache.just_hit_esc) {
                    this.cache.just_hit_esc = false;
                    return;
                }
                element_chooser = this.dialog.element_chooser;
                element_chooser_option = element_chooser.options[element_chooser.selectedIndex];
                element_chooser_text = element_chooser_option.innerText || element_chooser_option.textContent;
                element_chooser_value = element_chooser_option.getAttribute("value");
                if(!element_chooser_value) return;
                switch(this.dialog.mode){
                    case "createElement":
                        doctored.util.this_function(this.update_element, this)(event);
                        this.dialog.style.display = "none";
                        delete this.dialog.target;
                        break;
                    case "editElement":
                        doctored.util.this_function(this.update_element, this)(event);
                        this.schema.set_dialog_context(this.dialog, undefined, element_chooser_text);
                        break;
                    default:
                        doctored.util.alert("Unrecognised dialog mode " + this.dialog.mode);
                }
            },
            update_element: function(event){
                // after choosing an element in the dialog... (see element_chooser_change, above)
                var dialog          = this.dialog,
                    element_chooser = dialog.element_chooser,
                    option          = element_chooser.options[element_chooser.selectedIndex],
                    option_value    = option.getAttribute("value"),
                    element_name    = option.innerText || option.textContent,
                    display_type    = "block",
                    this_function   = doctored.util.this_function;

                if(!option_value || option_value.length === 0) return doctored.util.remove_old_selection(dialog.target, dialog);
                if(!dialog.target) return "Trying to update element when there is no target?";
                if(!dialog.target.classList.contains("doctored")) { //set it unless it's the Doctored root node
                    switch(option_value){
                        case "inline":
                        case "block":
                            display_type = option_value;
                    }
                    dialog.target.className = doctored.CONSTANTS.block_or_inline_class_prefix + display_type; //must clobber other values
                }
                if(option_value === doctored.CONSTANTS.custom_element_value) {
                    element_name = doctored.util.prompt("Custom element:");
                    if(!element_name) return doctored.util.remove_old_selection(dialog.target, dialog);
                }
                dialog.target.setAttribute("data-element", element_name);
                this_function(this.lint_soon, this)();
            },
            properties: function(event){
                // clicking the 'properties' button
                doctored.util.display_element_dialog(this.root, this.dialog, undefined, doctored.CONSTANTS.root_context, this.schema);
                event.preventDefault();
            },
            hamburger_button_click: function(event){
                var position = this.hamburger_button.getBoundingClientRect();

                this.hamburger_menu.style.display = "block";
                this.hamburger_menu.style.left = (position.left - this.hamburger_menu.offsetWidth) + "px";
                this.hamburger_menu.style.top = position.top + "px";
                event.preventDefault();
            },
            hamburger_change_theme: function(event){
                var theme_chooser  = this.hamburger_menu.theme_chooser,
                    option         = theme_chooser.options[theme_chooser.selectedIndex],
                    option_text    = option.textContent;

                window.localStorage.setItem("doctored-theme", option_text);
                doctored.util.set_theme(option_text, this);
                this.hamburger_menu.style.display = "none";
                theme_chooser.selectedIndex = 0;
                event.preventDefault();
            },
            hamburger_close: function(event){
                this.hamburger_menu.style.display = "none";
                event.preventDefault();
            },
            close_dialog: function(event){
                // clicking [x] in the dialog
                this.dialog.style.display = "none";
                doctored.util.remove_old_selection(this.dialog.target, this);
                event.preventDefault();
            },
            reset_theme: function(){
                delete this.root.default_marginLeft;
                this.tabs_resize();
            },
            view_source: function(event){
                // clicking 'View Source' button
                var view_source_textarea = this.view_source_textarea,
                    view_source_resizer = this.view_source_resizer,
                    this_function = doctored.util.this_function,
                    should_be_visible,
                    root_boundaries,
                    resizer_boundaries;

                if(!this.root.default_marginLeft) { // this block only run once for init
                    view_source_resizer.style.display = "block";
                    resizer_boundaries = view_source_resizer.getBoundingClientRect();
                    view_source_resizer.style.height = 500 + "px";
                    view_source_resizer.padding_added_to_height = view_source_resizer.offsetHeight - 500;
                    view_source_resizer.outer_width = resizer_boundaries.width; //may as well cache it in case it requires a lookup
                    view_source_textarea.style.width = 500 + "px";
                    view_source_textarea.style.height = 500 + "px";
                    view_source_textarea.style.display = "block";
                    view_source_textarea.padding_added_to_width = view_source_textarea.offsetWidth - 500;
                    view_source_textarea.padding_added_to_height = view_source_textarea.offsetHeight - 500;
                    this.root.parent_boundaries = this.root.parentNode.getBoundingClientRect();
                    this.root.style.marginLeft = "";
                    root_boundaries = this.root.getBoundingClientRect();
                    this.root.default_marginLeft = parseInt(window.getComputedStyle(this.root)['margin-left'], 10);
                    view_source_textarea.maximum_width = this.root.offsetWidth - doctored.CONSTANTS.error_gutter_width_pixels - doctored.CONSTANTS.text_area_resizer_maximum_pixels - view_source_resizer.outer_width;
                    view_source_textarea.width = ((root_boundaries.width - view_source_resizer.outer_width) / 2) - view_source_textarea.padding_added_to_width;
                    view_source_textarea.style.width = view_source_textarea.width + "px";
                }

                this.menu.view_source.classList.toggle(doctored.CONSTANTS.menu_option_on);
                should_be_visible = this.menu.view_source.classList.contains(doctored.CONSTANTS.menu_option_on);
                if(should_be_visible){
                    view_source_textarea.style.display = "block";
                    view_source_resizer.style.display = "block";
                    view_source_textarea.value = this.get_xml_string();
                    view_source_textarea.focus();
                    this_function(this.view_source_resize, this)();
                } else {
                    view_source_textarea.style.display = "none";
                    view_source_resizer.style.display = "none";
                    this.root.style.marginLeft = this.root.default_marginLeft + "px";
                }
                event.preventDefault();
            },
            view_source_change: function(event){
                // When there's a text change in the view source textarea (this is typically debounced)
                this.set_xml_string(this.view_source_textarea.value);
            },
            view_source_resizer_drag_start: function(event){
                var root_boundaries,
                    view_source_textarea = this.view_source_textarea,
                    view_source_resizer = this.view_source_resizer;

                if(event.which !== doctored.CONSTANTS.left_mouse_button) return;
                root_boundaries = this.root.getBoundingClientRect();
                view_source_resizer.dragging = true;
                view_source_resizer.drag_offset = event.layerX;
            },
            tabs_drag_start: function(event){
                if(event.which !== doctored.CONSTANTS.left_mouse_button) return;
                this.tabs.dragging = true;
                this.tabs.dragging_tab = doctored.util.get_closest_by_nodeName(event.target, "li");
                this.tabs.dragging_tab_index = doctored.util.get_tab_index(this.tabs.dragging_tab);
                this.tabs.dragging_started = false;
                this.tabs.dragging_start_offset = {x: event.layerX};
                this.tabs.dragging_start_mouse_position = {x: (event.x || event.clientX)};
                if(this.tabs.drop_target === undefined){
                    this.tabs.drop_target = document.createElement("li");
                    this.tabs.drop_target.className = "doctored-drop-target";
                    this.tabs.appendChild(this.tabs.drop_target);
                    this.tabs.drop_target_padding_border = this.tabs.drop_target.offsetWidth - parseInt(window.getComputedStyle(this.tabs.drop_target).width, 10);
                    this.tabs.removeChild(this.tabs.drop_target);
                }
                this.tabs.tab_offsetWidth = this.tabs.dragging_tab.offsetWidth;
                this.tabs.drop_target.style.width = (this.tabs.tab_offsetWidth - this.tabs.drop_target_padding_border) + "px";
                this.tabs.drop_target.style.height = (this.tabs.dragging_tab.offsetHeight - 5) + "px";
                this.tabs.left = this.tabs.getBoundingClientRect().left;
                this.tabs.child_nodes_at_drag_start_time = Array.prototype.slice.call(this.tabs.childNodes);
                this.tabs.dragging_mouse_start_position = {x: (event.x || event.clientX)};
            },
            drag: function(event){
                var this_function = doctored.util.this_function;
                
                if(this.view_source_resizer.dragging === true) {
                    return this_function(this.drag_view_source, this)(event);
                } else if(this.tabs.dragging === true) {
                    return this_function(this.drag_tabs, this)(event);
                }
            },
            drag_tabs: function(event){
                var mouse_position = {x: (event.x || event.clientX)};

                if(this.tabs.dragging_started === false) {
                    if(mouse_position.x < this.tabs.dragging_mouse_start_position.x - doctored.CONSTANTS.drag_distance_activates_pixels ||
                       mouse_position.x > this.tabs.dragging_mouse_start_position.x + doctored.CONSTANTS.drag_distance_activates_pixels){
                        this.tabs.dragging_started = true;
                        this.tabs.dragging_tab.classList.add("doctored-dragging-tab");
                        this.tabs.insertBefore(this.tabs.drop_target, this.tabs.dragging_tab.nextSibling);
                    }
                }
                if(this.tabs.dragging_started === true) { // NOTE don't join this if() with above because the above might set this.tabs.dragging_started = true
                    this.tabs.dragging_tab.style.left = (mouse_position.x - this.tabs.dragging_start_offset.x) + "px";
                    this.tabs.drop_target_index = Math.floor((mouse_position.x - this.tabs.left) / this.tabs.tab_offsetWidth);
                    if(this.tabs.drop_target_index >= this.tabs.child_nodes_at_drag_start_time.length - 1) {
                        this.tabs.drop_target_index = this.tabs.child_nodes_at_drag_start_time.length - 2;
                    } else if(this.tabs.drop_target_index < 0) {
                        this.tabs.drop_target_index = 0;
                    }

                    if(mouse_position.x > this.tabs.dragging_start_mouse_position.x) {
                        this.tabs.drop_target_index += 1;
                    }
                    this.tabs.insertBefore(this.tabs.drop_target, this.tabs.child_nodes_at_drag_start_time[this.tabs.drop_target_index]);
                }
                event.preventDefault();
            },
            drag_view_source: function(event){
                var this_function = doctored.util.this_function,
                    view_source_textarea = this.view_source_textarea,
                    view_source_resizer = this.view_source_resizer;

                if(this.view_source_resizer.dragging === false) return;

                view_source_textarea.width = (event.x || event.clientX) - this.root.parent_boundaries.left - this.root.default_marginLeft - view_source_textarea.padding_added_to_width - this.view_source_resizer.drag_offset;
                if(view_source_textarea.width < doctored.CONSTANTS.text_area_resizer_minimum_pixels - this.root.default_marginLeft) {
                    view_source_textarea.width = doctored.CONSTANTS.text_area_resizer_minimum_pixels - this.root.default_marginLeft;
                } else if(view_source_textarea.width > view_source_textarea.maximum_width){
                    view_source_textarea.width = view_source_textarea.maximum_width;
                }
                this_function(this.view_source_resize, this)();
                event.preventDefault();
            },
            drag_end: function(event){
                var this_function = doctored.util.this_function,
                    dragging_tab_index;
                
                event.preventDefault();
                if(this.tabs.dragging === true){
                    this.tabs.dragging = false;
                    if(this.tabs.dragging_started){
                        this.tabs.dragging_tab.style.left = "0px";
                        this.tabs.dragging_tab.classList.remove("doctored-dragging-tab");
                        if(this.tabs.drop_target.parentNode && this.tabs.drop_target.parentNode == this.tabs){
                            this.tabs.removeChild(this.tabs.drop_target);
                            dragging_tab_index = doctored.util.get_tab_index(this.tabs.dragging_tab);
                            this.tabs.insertBefore(this.tabs.dragging_tab, this.tabs.child_nodes_at_drag_start_time[this.tabs.drop_target_index]);
                            if(dragging_tab_index < this.tabs.drop_target_index) {
                                this.tabs.drop_target_index -= 1;
                            }
                            this.manifest.splice(this.tabs.drop_target_index, 0, this.manifest.splice(dragging_tab_index, 1)[0]);
                        }
                    } else {
                        this_function(this.tabs_click, this)(event);
                    }
                } else if (this.view_source_resizer.dragging === true){
                    this.view_source_resizer.dragging = false;
                }
            },
            view_source_resize: function(){
                // This just applies whatever width is set on view_source_textarea.width which is
                // different to view_source_textarea.style.width .
                var view_source_textarea = this.view_source_textarea,
                    view_source_resizer = this.view_source_resizer,
                    root_boundaries;
                
                this.root.style.marginLeft = (this.root.default_marginLeft + view_source_textarea.width + view_source_textarea.padding_added_to_width + view_source_resizer.outer_width) + "px";
                root_boundaries = this.root.getBoundingClientRect(); // needs to be recalculated after this.root has its marginLeft changed because the height may change
                view_source_textarea.style.top = (document.body.scrollTop + root_boundaries.top) + "px";
                view_source_textarea.style.height = (root_boundaries.height - view_source_textarea.padding_added_to_height) + "px";
                view_source_textarea.style.width = view_source_textarea.width + "px";
                view_source_textarea.style.display = "block";
                view_source_textarea.style.marginLeft = this.root.default_marginLeft + "px";
                view_source_resizer.style.top = (document.body.scrollTop + root_boundaries.top) + "px";
                view_source_resizer.style.height = (root_boundaries.height - view_source_resizer.padding_added_to_height) + "px";
                view_source_resizer.style.left = (this.root.parent_boundaries.left + this.root.default_marginLeft + view_source_textarea.width + view_source_textarea.padding_added_to_width) + "px";
            },
            keyup_contentEditable_sync_view_source: function(event){
                var textarea = this.view_source_textarea;
                if(!textarea) return;
                textarea.value = this.get_xml_string();
            },
            download: function(event){
                event.preventDefault();
                // clicking the 'Download' button
                var xml               = this.get_xml_string(),
                    current_tab_index = doctored.util.get_tab_index(this.tabs.current_tab),
                    filename          = this.manifest[current_tab_index].filename;

                if(filename.indexOf(".xml") === -1) filename += ".xml";
                doctored.util.offer_download(xml, filename);
            },
            keyup_dialog_esc: function(event){
                // keyup event in the dialog, and we're only interested in the 'esc' key
                var esc_key = doctored.CONSTANTS.key.esc;

                if(event.keyCode != esc_key) return;
                doctored.util.remove_old_selection(this.dialog.target, this);
                this.dialog.style.display = "none";
                this.cache.just_hit_esc = true;
            },
            keyup_dialog_attributes_enter: function(event){
                // keyup event occuring in the dialog attributes div, and we're only interested in 'enter' key
                var enter_key = doctored.CONSTANTS.key.enter,
                    attributes,
                    selection;

                if(event.keyCode != enter_key) return;
                attributes = doctored.util.gather_attributes(this.dialog.attributes_div.childNodes);
                this.dialog.target.setAttribute('data-attributes', doctored.util.encode_data_attributes(attributes));
                this.dialog.style.display = "none";
                delete this.dialog.target;
            },
            keyup_contentEditable: function(event){
                // keyup event occuring in the editable area
                var esc_key = doctored.CONSTANTS.key.esc,
                    enter_key = doctored.CONSTANTS.key.enter,
                    browser_selection,
                    parentNode;

                if(event.keyCode === esc_key){
                    browser_selection = doctored.util.get_current_selection();
                    parentNode = browser_selection.getRangeAt(0).endContainer.parentNode;
                    doctored.util.display_element_dialog(parentNode, this.dialog, undefined, parentNode.getAttribute("data-element"), this.schema);
                    this.dialog.element_chooser.focus();
                } else if(event.keyCode === enter_key && !event.shiftKey){
                    if(doctored.util.is_webkit) return;
                    // Processing BRs rather than intercepting keydown keyCode = enter is intentional.
                    // On <enter> Chrome breaks <div>s like a block (cloning following siblings) whereas
                    // Firefox just inserts a BR which isn't what we want (<shift-enter> is for that).
                    // There doesn't seem to be a feature detect for this behaviour (other than inspecting)
                    // the DOM after keyup, so that's essentially what we're doing
                    // (except Chrome never gets this far)
                    // This way Chrome goes fast (because it returns above and breaks DIVs as we want
                    // natively, by default) and looking for BRs means we don't have to insert a marker
                    // to split text nodes
                    doctored.util.process_linebreaks($("br", this.root));
                    event.preventDefault();
                } else if(event.shiftKey === false){
                    doctored.util.this_function(this.click, this)(event);
                }
            },
            click: function(event){
                var browser_selection = doctored.util.get_current_selection(),
                    target   = event.toElement || event.target,
                    mouse_position = event.x || event.clientX ? {x:event.x || event.clientX, y:event.y || event.clientY} : undefined,
                    within_pseudoelement = doctored.util.within_pseudoelement(target, mouse_position),
                    new_doctored_selection,
                    target_clone;

                this.dialog.style.display = "none";
                doctored.util.remove_old_selection(this.dialog.target, this.dialog);
                if (browser_selection.rangeCount) {
                    new_doctored_selection = doctored.util.surround_selection_with_element("div", "doctored-selection", this, browser_selection, mouse_position);
                    if(new_doctored_selection && new_doctored_selection.parentNode) { //if it's attached to the page
                        doctored.util.display_dialog_around_inline(new_doctored_selection, this.dialog, mouse_position, this.schema);
                    } else if(within_pseudoelement === doctored.CONSTANTS.edit_element_css_cursor) {
                        doctored.util.display_element_dialog(target, this.dialog, mouse_position, target.parentNode.getAttribute("data-element"), this.schema);
                    }
                }
            },
            clone_element_below: function(){
                var target = this.dialog.target,
                    target_clone = target.cloneNode(true);

                target_clone.innerHTML = "";
                target.parentNode.insertBefore(target_clone, target.nextSibling);
                this.lint_soon();
            },
            clone_element_above: function(){
                var target = this.dialog.target,
                    target_clone = target.cloneNode(true);

                target_clone.innerHTML = "";
                target.parentNode.insertBefore(target_clone, target);
                this.lint_soon();
            },
            add_attribute_item: function(){
                var attributes_item = this.dialog.attributes_template.cloneNode(true);

                this.dialog.attributes_add.parentNode.insertBefore(attributes_item, this.dialog.attributes_add);
                return attributes_item;
            },
            add_attribute_item_key: function(event){
                doctored.util.this_function(this.add_attribute_item, this)().childNodes[0].focus();
            },
            add_attribute_item_value: function(event){
                doctored.util.this_function(this.add_attribute_item, this)().childNodes[2].focus();
            },
            mousemove: function(event){
                var target   = event.toElement || event.target,
                    cursor   = "auto",
                    within;

                if(!target) return;
                within = (doctored.util.within_pseudoelement(target, {x:event.x || event.clientX, y:event.y || event.clientY}));
                if(within !== false){
                    cursor = within;
                }
                this.root.style.cursor = cursor;
            },
            show_tooltip: function(text, x, y){
                var _this = this;

                this.tooltip.innerHTML = text;
                this.tooltip.style.left = x + "px";
                this.tooltip.style.top = y +  "px";
                this.tooltip.style.display =  "block";
                this.tooltip.classList.remove("doctored-hidden");
                if(this.tooltip.timer) clearTimeout(this.tooltip.timer);
                this.tooltip.timer = setTimeout(function(){
                    _this.tooltip.classList.add("doctored-hidden");
                }, 1000);
            },
            hide_tooltip: function(event){
                this.tooltip.style.display = "none";
            }
        };
        doctored.instances = doctored.instances || [];
        doctored.instances.push(instance);
        instance.init();
        return instance;
    };

    doctored.getInstanceByNode = function(node){
        var i,
            instance;

        if(!doctored.instances) return false;
        for(i = 0; i < doctored.instances.length; i++){
            instance = doctored.instances[i];
            if(instance.root === node) return instance;
        }
        return false;
    };

    doctored.getInstanceBySelector = function(selector){
        return doctored.getInstanceByNode($(selector));
    };

    doctored.CONSTANTS = {
        key: {
            enter: 13,
            esc:   27
        },
        current_tab_class:                "doctored-current",
        inline_label_height_in_pixels:    10,
        block_label_width_in_pixels:      25,
        edit_element_css_cursor:          "pointer",
        doctored_container_class:         "doctored",
        custom_element_value:             "(custom)",
        xml_declaration:                  '<?xml version="1.0"?>',
        theme_prefix:                     'doctored-theme-',
        intentional_linebreak_class:      'doctored-linebreak',
        root_context:                     '/',
        block_or_inline_class_prefix:     'doctored-',
        menu_option_on:                   'doctored-on',
        error_gutter_width_pixels:        200,
        left_mouse_button:                1,
        text_area_resizer_minimum_pixels: 100,
        text_area_resizer_maximum_pixels: 150, /* from right of error gutter*/
        minimum_tab_width:                4,
        drag_distance_activates_pixels:   25,
        dialog_supression_wait_milliseconds: 5
    };
    doctored.CONSTANTS.block_class  = doctored.CONSTANTS.block_or_inline_class_prefix + 'block';
    doctored.CONSTANTS.inline_class = doctored.CONSTANTS.block_or_inline_class_prefix + 'inline';

}());
