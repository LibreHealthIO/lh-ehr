/*globals doctored, console, alert*/

(function(){
    "use strict";
    // DO NOT EDIT THE FOLLOWING
    // it is dynamically inserted by schemas/rebuild-schema-manifest.js
    // {MANIFEST-START}
    doctored.schemas_manifest = [{"schema_family":"DITA","children":[{"schema_family":"1.8","children":[{"schema":"/DITA/1.8/DITA Base.xsd","label":"DITA Base","schema_family":"dita"},{"schema":"/DITA/1.8/DITA Bookmap.xsd","label":"DITA Bookmap","schema_family":"dita"},{"schema":"/DITA/1.8/DITA Topic.xsd","label":"DITA Topic","schema_family":"dita"}]}]},{"schema_family":"DocBook","children":[{"schema":"/DocBook/DocBook 5.0.rng","label":"DocBook 5.0","schema_family":"docbook"}]},{"schema_family":"MARC","children":[{"schema":"/MARC/MARC21.xsd","label":"MARC21","schema_family":"marc"}]},{"schema_family":"TEI","children":[{"schema":"/TEI/TEI 2.6.rng","label":"TEI 2.6","schema_family":"tei"}]},{"schema_family":"TeXML","children":[{"schema":"/TeXML/texml.rng","label":"texml","schema_family":"texml"}]},{"schema_family":"phyloXML","children":[{"schema":"/phyloXML/phyloxml.xsd","label":"phyloxml","schema_family":"phyloxml"}]}];
    // {MANIFEST-END}
    // DO NOT EDIT THE PRECEDING
}());

(function(){
    "use strict";

    doctored.schemas = {
        list: doctored.schemas_manifest,
        get_schema_instance: function(instance, schema_family_id, schema_url){
            var schema_family = doctored.schema_family[schema_family_id],
                this_function = doctored.util.this_function,
                clone_of_schema_family;

            if(!schema_family) return alert("There is no support for the schema family of '" + schema_family_id + "' requested by " + schema_url + "'s config file.");

            clone_of_schema_family = doctored.util.simple_map_clone(schema_family);
            if(!clone_of_schema_family.init)                   clone_of_schema_family.init                   = schema_init;
            if(!clone_of_schema_family.new_document)           clone_of_schema_family.new_document           = new_document;
            if(!clone_of_schema_family.update_element_chooser) clone_of_schema_family.update_element_chooser = update_element_chooser;
            if(!clone_of_schema_family.set_dialog_context)     clone_of_schema_family.set_dialog_context     = set_dialog_context;
            return clone_of_schema_family;
        }
    };

    var $ = doctored.$,
        $xml = doctored.$xml,
        relaxng = {
            cache_useful_stuff_from_schema: function(){
                var this_function   = doctored.util.this_function,
                    schema_elements,
                    schema_element,
                    schema_element_help,
                    node_attribute_name,
                    block_or_inline,
                    i;

                this.elements = {};
                this.schema_elements = {}; //cache some lookups
                schema_elements = $("element", this.schema.documentElement);
                for(i = 0; i < schema_elements.length; i++){
                    schema_element = schema_elements[i];
                    node_attribute_name = schema_element.getAttribute("name");
                    if(node_attribute_name){
                        schema_element_help = $("documentation", schema_element)[0];
                        block_or_inline = (this.inline_elements && this.inline_elements.indexOf(node_attribute_name) >= 0) ? "inline" : "block";
                        this.elements[node_attribute_name] = {
                            display: block_or_inline,
                            help: schema_element_help ? schema_element_help.textContent : ""
                        };
                        this.schema_elements[node_attribute_name] = schema_element;
                    }
                }

                this.attributes = {};
                schema_elements = $("attribute", this.schema.documentElement);
                for(i = 0; i < schema_elements.length; i++){
                    schema_element = schema_elements[i];
                    node_attribute_name = schema_element.getAttribute("name");
                    if(node_attribute_name){
                        schema_element_help = $("documentation", schema_element)[0];
                        this.attributes[node_attribute_name] = {help: schema_element_help ? schema_element_help.textContent : ""};
                    }
                }

                this.schema_defines = {}; //cache some lookups
                schema_elements = $("define", this.schema.documentElement);
                for(i = 0; i < schema_elements.length; i++){
                    schema_element = schema_elements[i];
                    node_attribute_name = schema_element.getAttribute("name");
                    if(node_attribute_name){
                        this.schema_defines[node_attribute_name] = schema_element;
                    }
                }
                this.cached_context = {};
            },
            get_valid_nodes_for_context: function(element_name){
                var _this = this,
                    context,
                    max_depth = 10,
                    selector,
                    gather_below = function(nodes, depth){
                        var node,
                            node_name,
                            node_attribute_name,
                            i,
                            child_elements,
                            child_element_name;

                        if(depth === undefined) depth = 0;
                        for(i = 0; i < nodes.length; i++){
                            node = nodes[i];
                            node_name = node.nodeName;
                            node_attribute_name = (node.nodeType === node.ELEMENT_NODE) ? node.getAttribute("name") : undefined;
                            if(node_name === "element" && depth === 0) node_name = "we're not interested in this element so this is some random thing to skip to 'default' in switch/case";
                            switch(node_name) {
                                case "#text":
                                    break;
                                case "element":
                                    if(node_attribute_name) context.elements[node_attribute_name] = _this.elements[node_attribute_name];
                                    break;
                                case "attribute":
                                    if(node_attribute_name) context.attributes[node_attribute_name] = _this.attributes[node_attribute_name];
                                    break;
                                case "ref":
                                    node = _this.schema_defines[node_attribute_name]; //INTENTIONAL. NOT AN ERROR. SHUT UP JSHINT
                                default: // we have to go deeper
                                    if(depth <= max_depth && node.childNodes.length > 0) gather_below(node.childNodes, depth + 1);
                            }
                        }
                    };
                if(element_name === doctored.CONSTANTS.root_context) { //then it's the root node so we use different logic because there is no parent node
                    return {elements: {}, attributes: {}}; //FIXME allow different root nodes
                }
                if(!this.cached_context[element_name]) {
                    context = {elements: {}, attributes: {}};
                    //console.log(element_name, this.schema_elements[element_name]);
                    if(this.schema_elements[element_name]) {
                        gather_below([this.schema_elements[element_name]]);
                    }
                    this.cached_context[element_name] = context;
                }
                return this.cached_context[element_name];
            }
        },
        w3c_schema = {
            namespace: {
                default: "http://www.w3.org/2001/XMLSchema"
            },
            cache_useful_stuff_from_schema: function(){
                var this_function   = doctored.util.this_function,
                    schema_elements,
                    schema_element,
                    schema_element_help,
                    node_attribute_name,
                    block_or_inline,
                    i;

                this.elements = {};
                this.schema_elements = {}; //cache some lookups
                schema_elements = $xml("element", this.schema.documentElement, w3c_schema.namespace.default);
                for(i = 0; i < schema_elements.length; i++){
                    schema_element = schema_elements[i];
                    node_attribute_name = schema_element.getAttribute("name");
                    if(node_attribute_name){
                        schema_element_help = $xml("documentation", schema_element, w3c_schema.namespace.default)[0];
                        block_or_inline = (this.inline_elements && this.inline_elements.indexOf(node_attribute_name) >= 0) ? "inline" : "block";
                        this.elements[node_attribute_name] = {
                            display: block_or_inline,
                            help: schema_element_help ? doctored.util.remove_excessive_whitespace(schema_element_help.textContent) : ""
                        };
                        this.schema_elements[node_attribute_name] = schema_element;
                    }
                }

                this.attributes = {};
                schema_elements = $xml("attribute", this.schema.documentElement, w3c_schema.namespace.default);
                for(i = 0; i < schema_elements.length; i++){
                    schema_element = schema_elements[i];
                    node_attribute_name = schema_element.getAttribute("name");
                    if(node_attribute_name){
                        schema_element_help = $("xs:documentation", schema_element)[0];
                        this.attributes[node_attribute_name] = {help: schema_element_help ? doctored.util.remove_excessive_whitespace(schema_element_help.textContent) : ""};
                    }
                }

                this.schema_defines = {}; //cache some lookups
                schema_elements = $("[name]", this.schema.documentElement);
                for(i = 0; i < schema_elements.length; i++){
                    schema_element = schema_elements[i];
                    node_attribute_name = schema_element.getAttribute("name");
                    if(node_attribute_name){
                        if(!this.schema_defines[schema_element.nodeName]) this.schema_defines[schema_element.nodeName] = {};
                        this.schema_defines[schema_element.nodeName][node_attribute_name] = schema_element;
                    }
                }
                this.cached_context = {};
            },
            get_valid_nodes_for_context: function(element_name){
                var _this = this,
                    context,
                    max_depth = 25,
                    selector,
                    gather_below = function(nodes, depth){
                        var node,
                            node_name,
                            node_attribute_name,
                            node_attribute_ref,
                            node_attribute_base,
                            i,
                            child_elements,
                            child_element_name;

                        if(depth === undefined) depth = 0;
                        for(i = 0; i < nodes.length; i++){
                            node = nodes[i];
                            node_attribute_name = undefined;
                            node_attribute_ref  = undefined;
                            if(node.nodeType === node.ELEMENT_NODE){
                                node_attribute_ref  = node.getAttribute("ref");
                                node_attribute_base = node.getAttribute("base");
                                //console.log("REFER", node_attribute_base, node_attribute_ref)
                                if(node_attribute_ref && _this.schema_defines[node.nodeName][node_attribute_ref]) {
                                    //console.log("following ref", node.nodeName, node_attribute_ref, _this.schema_defines[node.nodeName][node_attribute_ref])
                                    gather_below([_this.schema_defines[node.nodeName][node_attribute_ref]], depth + 1);
                                } else if(node_attribute_base && _this.schema_defines["xs:complexType"][node_attribute_base]) {
                                    //console.log("drop the base")
                                    gather_below([_this.schema_defines["xs:complexType"][node_attribute_base]], depth + 1);
                                }
                                node_attribute_name = node.getAttribute("name");
                            }
                            node_name = node.nodeName;
                            if(node_name === "element" && depth === 0) node_name = "we're not interested in this element so this is some random thing to skip to 'default' in switch/case";
                            //console.log(node_name, node_attribute_name, node);
                            switch(node_name) {
                                case "#text":
                                    break;
                                case "xs:element":
                                case "element":
                                    if(node_attribute_name) context.elements[node_attribute_name] = _this.elements[node_attribute_name];
                                    break;
                                case "xs:attribute":
                                case "attribute":
                                    if(node_attribute_name) context.attributes[node_attribute_name] = _this.attributes[node_attribute_name];
                                    break;
                            }
                            if(depth <= max_depth && node.childNodes.length > 0) gather_below(node.childNodes, depth + 1);
                        }
                    };
                if(element_name === doctored.CONSTANTS.root_context) { //then it's the root node so we use different logic because there is no parent node
                    return {elements: {}, attributes: {}}; //FIXME allow different root nodes
                }
                if(!this.cached_context[element_name]) {
                    context = {elements: {}, attributes: {}};
                    if(this.schema_elements[element_name]) {
                        gather_below(this.schema_elements[element_name].childNodes);
                    }
                    this.cached_context[element_name] = context;
                }
                return this.cached_context[element_name];
            }
        },
        schema_init = function(instance, schema_url, new_document){
            var this_function  = doctored.util.this_function,
                file_extension = doctored.util.file_extension(schema_url),
                xhr;

            this.schema_url = doctored.base + "schemas" + schema_url;
            this.elements = {};
            this.attributes = {};
            this.instance = instance;
            switch(file_extension.toLowerCase()){
                case "rng":
                    this.cache_useful_stuff_from_schema = relaxng.cache_useful_stuff_from_schema;
                    this.get_valid_nodes_for_context = relaxng.get_valid_nodes_for_context;
                    break;
                case "xsd":
                    this.cache_useful_stuff_from_schema = w3c_schema.cache_useful_stuff_from_schema;
                    this.get_valid_nodes_for_context = w3c_schema.get_valid_nodes_for_context;
                    break;
                default:
                    return alert("Unable to use a schema '" + file_extension + "'. RelaxNG files must have extension .rng and W3C Schema files must have extension .xsd");
            }
            xhr = new XMLHttpRequest();
            xhr.open("GET", this.schema_url, true);
            xhr.send(null);
            xhr.onreadystatechange = this_function(function(){
                if(xhr.readyState !== 4) return;
                if(xhr.responseXML){
                    this.schema = xhr.responseXML;
                } else {
                    this.schema = ( new window.DOMParser() ).parseFromString(xhr.responseText, "text/xml");
                }
                this_function(this.cache_useful_stuff_from_schema, this)();
                this_function(this.update_element_chooser, this)();
                if(new_document) this_function(this.new_document, this)();
                this_function(this.instance.lint_soon, this.instance)();
            }, this);
        },
        update_element_chooser = function(){
            var element_chooser = this.instance.dialog.element_chooser,
                html = '<option value="" disabled selected>Choose Element</option>' +
                       '<optgroup label="Suggested elements in this context">' + // if you update this be sure to also update the one below in set_element_chooser_context()
                       '<option value="" disabled class="doctored-loading">Loading...</option>' +
                       '</optgroup>' +
                       '<optgroup label="All (' + Object.keys(this.elements).length + ' elements)">' +
                       doctored.util.to_options_tags(this.elements, true) +
                       '</optgroup>' +
                       '<optgroup label="Custom Element">' +
                       '<option value="(custom)">Choose a custom element</option>' +
                       '</optgroup>';

            element_chooser.innerHTML = html;
            element_chooser.context_chooser = $("optgroup", element_chooser)[0];
        },
        new_document = function(){
            this.instance.set_xml_string(this.new_document_xml);
        },
        set_dialog_context = function(dialog, elements_under_element_name, attributes_for_element_name, existing_attributes){
            var this_function       = doctored.util.this_function,
                context_chooser     = dialog.element_chooser.context_chooser || $("optgroup", dialog.element_chooser)[0],
                element_chooser     = dialog.element_chooser,
                number_of_elements,
                attribute_pair,
                context,
                keys,
                key,
                i;

            if(elements_under_element_name) {
                context = this_function(this.get_valid_nodes_for_context, this)(elements_under_element_name);
                number_of_elements = (context && context.elements) ? Object.keys(context.elements).length : 0;
                if(number_of_elements === 0) {
                    context_chooser.setAttribute("label", "Suggested (0 elements)"); //TODO fix this, detect valid root nodes
                    context_chooser.innerHTML = '<option value="" disabled>(None)</option>';
                } else {
                    context_chooser.setAttribute("label", "Suggested under '" + elements_under_element_name + "' (" + number_of_elements + " elements)");
                    context_chooser.innerHTML = doctored.util.to_options_tags(context.elements, true);
                }
            }
            if(attributes_for_element_name){
                //step 1. clear existing attributes that are empty
                
                for(i = 0; i < dialog.attributes_div.childNodes.length - 1; i++){ // note the " - 1" because the last row is irrelevant
                    attribute_pair = dialog.attributes_div.childNodes[i];
                    if($(".doctored-attribute-value", attribute_pair)[0].value.length === 0) {
                        dialog.attributes_div.removeChild(dialog.attributes_div.childNodes[i]);
                    }
                }
                context = this_function(this.get_valid_nodes_for_context, this)(attributes_for_element_name);
                keys = (context && context.attributes) ? Object.keys(context.attributes).sort() : [];
                for(i = 0; i < keys.length; i++){
                    key = keys[i];
                    if(!existing_attributes || !existing_attributes[key]){
                        doctored.util.dialog_append_attribute(dialog, key, "", context.attributes[key].help);
                    }
                }
                dialog.attributes_title.innerHTML = "Attributes for " + doctored.util.escape_text(attributes_for_element_name);
            }
        };
       

    doctored.schema_family = { //a way of grouping multiple schemas into types (e.g. DocBook 4 and 5 are both "docbook")
        docbook: {
            name: "DocBook 5",
            new_document_xml: '<book version="5.0" xmlns="http://docbook.org/ns/docbook" xmlns:xlink="http://wwww.w3.org/1999/xlink/"><title>Book Title</title><chapter><para>First paragraph <link xlink:href="http://docvert.org/">with hyperlink</link>.</para></chapter></book>',
            inline_elements: ["abbrev","accel","acronym","address","alt","anchor","annotation","application","author","bibliolist","biblioref","blockquote","bridgehead","calloutlist","caution","citation","citebiblioid","citerefentry","citetitle","classname","classsynopsis","cmdsynopsis","code","command","computeroutput","constant","constraintdef","constructorsynopsis","coref","database","date","destructorsynopsis","editor","email","emphasis","envar","epigraph","equation","errorcode","errorname","errortext","errortype","example","exceptionname","fieldsynopsis","figure","filename","footnote","footnoteref","foreignphrase","funcsynopsis","function","glosslist","guibutton","guiicon","guilabel","guimenu","guimenuitem","guisubmenu","hardware","important","indexterm","info","informalequation","informalexample","informalfigure","initializer","inlineequation","inlinemediaobject","interfacename","itemizedlist","jobtitle","keycap","keycode","keycombo","keysym","link","literal","literallayout","markup","mediaobject","menuchoice","methodname","methodsynopsis","modifier","mousebutton","msgset","nonterminal","note","olink","option","optional","orderedlist","org","orgname","package","parameter","person","personname","phrase","procedure","productionset","productname","productnumber","programlisting","programlistingco","prompt","property","qandaset","quote","remark","replaceable","returnvalue","revhistory","screen","screenco","screenshot","segmentedlist","shortcut","sidebar","simplelist","subscript","superscript","symbol","synopsis","systemitem","tag","task","termdef","tip","token","trademark","type","uri","userinput","variablelist","varname","warning","wordasword","xref"],
            convert_from_html: function(html_string){
                var element_mapping   = {"p":    "para", "a": "ulink"},
                    attribute_mapping = {"href": "url"};
                return doctored.util.simple_transform(html_string, element_mapping, attribute_mapping);
            },
        },
        'tei': {
            name: "TEI 2.6.0",
            inline_elements: ["title", "note", "name", "emph", "term"],
            new_document_xml: '<TEI xmlns="http://www.tei-c.org/ns/1.0"><teiHeader><fileDesc><titleStmt><title>Review: an electronic transcription</title></titleStmt><publicationStmt><p>Published as an example for the Introduction module of TBE.</p></publicationStmt><sourceDesc><p>No source: born digital.</p></sourceDesc></fileDesc></teiHeader><text><body><head>Review</head><p><title>Die Leiden des jungen Werther</title><note place="foot">by <name>Goethe</name></note>is an <emph>exceptionally</emph> good example of a book full of <term>Weltschmerz</term>.</p> </body> </text> </TEI>'
        },
        'dita': {
            name: "DITA 1.8",
            file_extension: ".dita",
            inline_elements: ["xref", "codeph"],
            new_document_xml: '<!-- This file is part of the DITA Open Toolkit project hosted on Sourceforge.net. Common Public License v1.0 --><!-- (c) Copyright IBM Corp. 2004, 2005 All Rights Reserved. --><!DOCTYPE concept PUBLIC "-//OASIS//DTD DITA Concept//EN" "../../dtd/concept.dtd"><concept id="bookmap-readme" xml:lang="en-us">  <title>Bookmap Readme</title>  <prolog/>  <conbody>    <p>This demonstration provides a proof-of-concept implementation of the DITA bookmap proposal. The proposal adds book output to DITA using a specialized DITA map known as a bookmap. The bookmap organizes the DITA topics with the correct nesting and sequence for the book. In addition, the bookmap assigns roles such as preface, chapter, and appendix to top-level topics within the book. </p>    <p class="- topic/p ">For more detailed information about the proposal, see the detailed posting on the DITA forum at <xref href="news://news.software.ibm.com:119/c11fd3$85qq$2@news.boulder.ibm.com" format="news">news://news.software.ibm.com:119/c11fd3$85qq$2@news.boulder.ibm.com</xref>.</p>    <note>This demonstration has the following limitations:<ul>        <li>For XSL-FO formatting and thus PDF generation, only the basics have been implemented. Through specialization, the DITA XHTML-based outputs for DITA map are also available for bookmap.</li>        <li>The design for the book info component of the proposal has been fleshed out based on antecedents in DocBook and IBMIDDoc (see the comments in the <codeph>bookinfo.mod</codeph> file). Most of the elements in bookinfo aren&apos;t processed.</li>        <li>The book list component of the proposal hasn&apos;t been implemented yet. Possible designs for a glossary list have been discussed extensively on the DITA forum (resulting in the proposal posted as <xref href="news://news.software.ibm.com:119/3FA29F54.83AFB251@ca.ibm.com" format="news">news://news.software.ibm.com:119/blfg38$5k0q$1@news.boulder.ibm.com</xref>).</li>        <li>The book style component of the proposal is much more experimental than the bookmap and bookinfo components. Processing for this component is limited.</li>      </ul></note>  </conbody></concept>'
        },
        'marc': {
            name: "MARC21 Slim",
            new_document_xml: '<?xml version="1.0"?> <collection xmlns="http://www.loc.gov/MARC21/slim">   <record>     <leader>01142cam  2200301 a 4500</leader>     <controlfield tag="001">   92005291 </controlfield>     <controlfield tag="003">DLC</controlfield>     <controlfield tag="005">19930521155141.9</controlfield>     <controlfield tag="008">920219s1993    caua   j      000 0 eng  </controlfield>     <datafield tag="010" ind1=" " ind2=" ">       <subfield code="a">   92005291 </subfield>     </datafield>     <datafield tag="020" ind1=" " ind2=" ">       <subfield code="a">0152038655 :</subfield>       <subfield code="c">$15.95</subfield>     </datafield>     <datafield tag="040" ind1=" " ind2=" ">       <subfield code="a">DLC</subfield>       <subfield code="c">DLC</subfield>       <subfield code="d">DLC</subfield>     </datafield>     <datafield tag="042" ind1=" " ind2=" ">       <subfield code="a">lcac</subfield>     </datafield>     <datafield tag="050" ind1="0" ind2="0">       <subfield code="a">PS3537.A618</subfield>       <subfield code="b">A88 1993</subfield>     </datafield>     <datafield tag="082" ind1="0" ind2="0">       <subfield code="a">811/.52</subfield>       <subfield code="2">20</subfield>     </datafield>     <datafield tag="100" ind1="1" ind2=" ">       <subfield code="a">Sandburg, Carl,</subfield>       <subfield code="d">1878-1967.</subfield>     </datafield>     <datafield tag="245" ind1="1" ind2="0">       <subfield code="a">Arithmetic /</subfield>       <subfield code="c">Carl Sandburg ; illustrated as an anamorphic adventure by Ted Rand.</subfield>     </datafield>     <datafield tag="250" ind1=" " ind2=" ">       <subfield code="a">1st ed.</subfield>     </datafield>     <datafield tag="260" ind1=" " ind2=" ">       <subfield code="a">San Diego :</subfield>       <subfield code="b">Harcourt Brace Jovanovich,</subfield>       <subfield code="c">c1993.</subfield>     </datafield>     <datafield tag="300" ind1=" " ind2=" ">       <subfield code="a">1 v. (unpaged) :</subfield>       <subfield code="b">ill. (some col.) ;</subfield>       <subfield code="c">26 cm.</subfield>     </datafield>     <datafield tag="500" ind1=" " ind2=" ">       <subfield code="a">One Mylar sheet included in pocket.</subfield>     </datafield>     <datafield tag="520" ind1=" " ind2=" ">       <subfield code="a">A poem about numbers and their characteristics. Features anamorphic, or distorted, drawings which can be restored to normal by viewing from a particular angle or by viewing the image\'s reflection in the provided Mylar cone.</subfield>     </datafield>     <datafield tag="650" ind1=" " ind2="0">       <subfield code="a">Arithmetic</subfield>       <subfield code="x">Juvenile poetry.</subfield>     </datafield>     <datafield tag="650" ind1=" " ind2="0">       <subfield code="a">Children\'s poetry, American.</subfield>     </datafield>     <datafield tag="650" ind1=" " ind2="1">       <subfield code="a">Arithmetic</subfield>       <subfield code="x">Poetry.</subfield>     </datafield>     <datafield tag="650" ind1=" " ind2="1">       <subfield code="a">American poetry.</subfield>     </datafield>     <datafield tag="650" ind1=" " ind2="1">       <subfield code="a">Visual perception.</subfield>     </datafield>     <datafield tag="700" ind1="1" ind2=" ">       <subfield code="a">Rand, Ted,</subfield>       <subfield code="e">ill.</subfield>     </datafield>   </record> </collection>'
        },
        'texml': {
            name: "TeXML",
            new_document_xml: '<?xml version="1.0"?><TeXML><TeXML escape="0">\\documentclass[a4paper]{article}\\usepackage[latin1]{inputenc}\\usepackage[T1]{fontenc}</TeXML><env name="document">NOTE We don\'t support linebreaks very well right now - this format isn\'t very well supported.\neI\'m not afraid of the symbols\n$, ^, > and others.</env></TeXML>'
        },
        'html': {
            name: "HTML",
            new_document_xml: '<?xml version="1.0"?><html xmlns="http://www.w3.org/1999/xhtml"><head><title>Page Title</title></head><body><p>Test Document</p></body></html>'
        },
        'phyloxml': {
            name: "phyloXML",
            new_document_xml: '<?xml version="1.0" encoding="UTF-8"?><phyloxml xmlns="http://www.phyloxml.org">   <phylogeny rooted="true">      <clade>         <clade>            <branch_length>0.18105</branch_length>            <confidence type="unknown">89.0</confidence>            <clade>               <branch_length>0.07466</branch_length>               <confidence type="unknown">32.0</confidence>               <clade>                  <branch_length>0.26168</branch_length>                  <confidence type="unknown">100.0</confidence>                  <clade>                     <branch_length>0.22058</branch_length>                     <confidence type="unknown">89.0</confidence>                     <clade>                        <branch_length>0.28901</branch_length>                        <confidence type="unknown">100.0</confidence>                        <clade>                           <branch_length>0.06584</branch_length>                           <confidence type="unknown">100.0</confidence>                           <clade>                              <branch_length>0.02309</branch_length>                              <confidence type="unknown">43.0</confidence>                              <clade>                                 <branch_length>0.0746</branch_length>                                 <confidence type="unknown">100.0</confidence>                                 <clade>                                    <branch_length>0.02365</branch_length>                                    <confidence type="unknown">88.0</confidence>                                    <clade>                                       <name>22_MOUSE</name>                                       <branch_length>0.05998</branch_length>                                       <taxonomy>                                          <code>MOUSE</code>                                       </taxonomy>                                       <sequence>                                          <domain_architecture length="1249">                                             <domain from="6" to="90" confidence="7.0E-26">CARD</domain>                                             <domain from="109" to="414" confidence="7.2E-117">NB-ARC</domain>                                             <domain from="605" to="643" confidence="2.4E-6">WD40</domain>                                             <domain from="647" to="685" confidence="1.1E-12">WD40</domain>                                             <domain from="689" to="729" confidence="2.4E-7">WD40</domain>                                             <domain from="733" to="771" confidence="4.7E-14">WD40</domain>                                             <domain from="872" to="910" confidence="2.5E-8">WD40</domain>                                             <domain from="993" to="1031" confidence="4.6E-6">WD40</domain>                                             <domain from="1075" to="1113" confidence="6.3E-7">WD40</domain>                                             <domain from="1117" to="1155" confidence="1.4E-7">WD40</domain>                                             <domain from="1168" to="1204" confidence="0.3">WD40</domain>                                          </domain_architecture>                                       </sequence>                                    </clade>                                    <clade>                                       <name>Apaf-1_HUMAN</name>                                       <branch_length>0.01825</branch_length>                                       <taxonomy>                                          <code>HUMAN</code>                                       </taxonomy>                                       <sequence>                                          <domain_architecture length="1248">                                             <domain from="6" to="90" confidence="1.1E-25">CARD</domain>                                             <domain from="109" to="414" confidence="3.0E-134">NB-ARC</domain>                                             <domain from="605" to="643" confidence="8.5E-6">WD40</domain>                                             <domain from="647" to="685" confidence="2.5E-11">WD40</domain>                                             <domain from="689" to="729" confidence="2.4E-8">WD40</domain>                                             <domain from="733" to="771" confidence="3.6E-14">WD40</domain>                                             <domain from="872" to="910" confidence="3.8E-8">WD40</domain>                                             <domain from="1075" to="1113" confidence="4.0E-7">WD40</domain>                                             <domain from="1117" to="1155" confidence="5.9E-8">WD40</domain>                                          </domain_architecture>                                       </sequence>                                    </clade>                                 </clade>                                 <clade>                                    <name>12_CANFA</name>                                    <branch_length>0.04683</branch_length>                                    <taxonomy>                                       <code>CANFA</code>                                    </taxonomy>                                    <sequence>                                       <domain_architecture length="1153">                                          <domain from="6" to="90" confidence="4.5E-22">CARD</domain>                                          <domain from="110" to="415" confidence="4.0E-119">NB-ARC</domain>                                          <domain from="597" to="635" confidence="3.9E-5">WD40</domain>                                          <domain from="639" to="677" confidence="2.5E-11">WD40</domain>                                          <domain from="681" to="721" confidence="1.8E-7">WD40</domain>                                          <domain from="725" to="763" confidence="9.4E-13">WD40</domain>                                          <domain from="889" to="927" confidence="1.1E-6">WD40</domain>                                          <domain from="971" to="1009" confidence="1.7E-7">WD40</domain>                                          <domain from="1013" to="1051" confidence="1.9E-7">WD40</domain>                                       </domain_architecture>                                    </sequence>                                 </clade>                              </clade>                              <clade>                                 <name>11_CHICK</name>                                 <branch_length>0.15226</branch_length>                                 <taxonomy>                                    <code>CHICK</code>                                 </taxonomy>                                 <sequence>                                    <domain_architecture length="1207">                                       <domain from="6" to="90" confidence="3.6E-21">CARD</domain>                                       <domain from="109" to="414" confidence="3.6E-109">NB-ARC</domain>                                       <domain from="603" to="641" confidence="1.6E-4">WD40</domain>                                       <domain from="645" to="683" confidence="8.2E-11">WD40</domain>                                       <domain from="687" to="727" confidence="6.2E-10">WD40</domain>                                       <domain from="731" to="769" confidence="1.8E-11">WD40</domain>                                       <domain from="828" to="866" confidence="1.8">WD40</domain>                                       <domain from="993" to="1030" confidence="2.9E-4">WD40</domain>                                       <domain from="1034" to="1072" confidence="1.7E-8">WD40</domain>                                       <domain from="1076" to="1114" confidence="7.5E-9">WD40</domain>                                       <domain from="1127" to="1163" confidence="0.044">WD40</domain>                                    </domain_architecture>                                 </sequence>                              </clade>                           </clade>                           <clade>                              <name>16_XENLA</name>                              <branch_length>0.4409</branch_length>                              <taxonomy>                                 <code>XENLA</code>                              </taxonomy>                              <sequence>                                 <domain_architecture length="1362">                                    <domain from="6" to="90" confidence="4.0E-20">CARD</domain>                                    <domain from="109" to="410" confidence="3.1E-56">NB-ARC</domain>                                    <domain from="148" to="298" confidence="0.8">NACHT</domain>                                    <domain from="729" to="767" confidence="7.0E-6">WD40</domain>                                    <domain from="771" to="809" confidence="2.3E-11">WD40</domain>                                    <domain from="813" to="853" confidence="1.1E-8">WD40</domain>                                    <domain from="857" to="895" confidence="1.1E-10">WD40</domain>                                    <domain from="992" to="1030" confidence="8.4E-9">WD40</domain>                                    <domain from="1116" to="1154" confidence="7.3E-11">WD40</domain>                                    <domain from="1158" to="1196" confidence="1.6E-8">WD40</domain>                                    <domain from="1200" to="1238" confidence="1.2E-7">WD40</domain>                                 </domain_architecture>                              </sequence>                           </clade>                        </clade>                        <clade>                           <branch_length>0.17031</branch_length>                           <confidence type="unknown">100.0</confidence>                           <clade>                              <branch_length>0.10929</branch_length>                              <confidence type="unknown">100.0</confidence>                              <clade>                                 <name>14_FUGRU</name>                                 <branch_length>0.02255</branch_length>                                 <taxonomy>                                    <code>FUGRU</code>                                 </taxonomy>                                 <sequence>                                    <domain_architecture length="1258">                                       <domain from="8" to="92" confidence="4.6E-24">CARD</domain>                                       <domain from="111" to="418" confidence="7.0E-74">NB-ARC</domain>                                       <domain from="609" to="647" confidence="2.8E-4">WD40</domain>                                       <domain from="651" to="689" confidence="2.3E-12">WD40</domain>                                       <domain from="740" to="778" confidence="1.8E-6">WD40</domain>                                       <domain from="874" to="912" confidence="1.6E-9">WD40</domain>                                       <domain from="998" to="1036" confidence="9.8E-13">WD40</domain>                                       <domain from="1040" to="1080" confidence="1.6E-4">WD40</domain>                                       <domain from="1084" to="1122" confidence="1.5E-9">WD40</domain>                                       <domain from="1126" to="1164" confidence="6.2E-9">WD40</domain>                                    </domain_architecture>                                 </sequence>                              </clade>                              <clade>                                 <name>15_TETNG</name>                                 <branch_length>0.09478</branch_length>                                 <taxonomy>                                    <code>TETNG</code>                                 </taxonomy>                                 <sequence>                                    <domain_architecture length="621">                                       <domain from="8" to="92" confidence="2.5E-24">CARD</domain>                                       <domain from="104" to="308" confidence="9.8E-11">NB-ARC</domain>                                       <domain from="366" to="404" confidence="1.2E-12">WD40</domain>                                       <domain from="455" to="493" confidence="1.0E-10">WD40</domain>                                       <domain from="537" to="575" confidence="4.1E-10">WD40</domain>                                    </domain_architecture>                                 </sequence>                              </clade>                           </clade>                           <clade>                              <name>17_BRARE</name>                              <branch_length>0.1811</branch_length>                              <taxonomy>                                 <code>BRARE</code>                              </taxonomy>                              <sequence>                                 <domain_architecture length="1260">                                    <domain from="6" to="90" confidence="3.9E-23">CARD</domain>                                    <domain from="137" to="444" confidence="1.7E-72">NB-ARC</domain>                                    <domain from="635" to="673" confidence="1.6">WD40</domain>                                    <domain from="694" to="732" confidence="5.3E-12">WD40</domain>                                    <domain from="783" to="821" confidence="2.0E-8">WD40</domain>                                    <domain from="1040" to="1078" confidence="2.4E-8">WD40</domain>                                    <domain from="1081" to="1121" confidence="6.6E-4">WD40</domain>                                    <domain from="1125" to="1163" confidence="5.1E-8">WD40</domain>                                    <domain from="1167" to="1205" confidence="1.3E-7">WD40</domain>                                 </domain_architecture>                              </sequence>                           </clade>                        </clade>                     </clade>                     <clade>                        <branch_length>0.01594</branch_length>                        <confidence type="unknown">53.0</confidence>                        <clade>                           <branch_length>0.10709</branch_length>                           <confidence type="unknown">68.0</confidence>                           <clade>                              <name>1_BRAFL</name>                              <branch_length>0.26131</branch_length>                              <taxonomy>                                 <code>BRAFL</code>                              </taxonomy>                              <sequence>                                 <domain_architecture length="1238">                                    <domain from="6" to="89" confidence="5.7E-19">CARD</domain>                                    <domain from="111" to="413" confidence="4.2E-48">NB-ARC</domain>                                    <domain from="600" to="638" confidence="3.2E-8">WD40</domain>                                    <domain from="642" to="680" confidence="1.5E-10">WD40</domain>                                    <domain from="730" to="768" confidence="9.6E-11">WD40</domain>                                    <domain from="857" to="895" confidence="3.7E-10">WD40</domain>                                    <domain from="984" to="1022" confidence="6.7E-10">WD40</domain>                                    <domain from="1025" to="1064" confidence="3.1E-6">WD40</domain>                                    <domain from="1069" to="1107" confidence="1.5E-4">WD40</domain>                                    <domain from="1111" to="1149" confidence="7.9E-7">WD40</domain>                                 </domain_architecture>                              </sequence>                           </clade>                           <clade>                              <name>18_NEMVE</name>                              <branch_length>0.38014</branch_length>                              <taxonomy>                                 <code>NEMVE</code>                              </taxonomy>                              <sequence>                                 <domain_architecture length="1290">                                    <domain from="7" to="90" confidence="1.5E-13">CARD</domain>                                    <domain from="117" to="200" confidence="5.4E-14">CARD</domain>                                    <domain from="216" to="517" confidence="1.5E-50">NB-ARC</domain>                                    <domain from="711" to="749" confidence="1.0E-7">WD40</domain>                                    <domain from="753" to="791" confidence="8.5E-12">WD40</domain>                                    <domain from="795" to="833" confidence="4.3E-11">WD40</domain>                                    <domain from="837" to="875" confidence="1.0E-12">WD40</domain>                                    <domain from="920" to="957" confidence="2.0E-4">WD40</domain>                                    <domain from="961" to="999" confidence="3.0E-6">WD40</domain>                                    <domain from="1085" to="1123" confidence="4.4E-6">WD40</domain>                                    <domain from="1128" to="1166" confidence="3.0E-4">WD40</domain>                                    <domain from="1170" to="1207" confidence="1.7E-10">WD40</domain>                                 </domain_architecture>                              </sequence>                           </clade>                        </clade>                        <clade>                           <name>23_STRPU</name>                           <branch_length>0.48179</branch_length>                           <taxonomy>                              <code>STRPU</code>                           </taxonomy>                           <sequence>                              <domain_architecture length="1236">                                 <domain from="110" to="402" confidence="1.0E-40">NB-ARC</domain>                                 <domain from="594" to="632" confidence="8.9E-4">WD40</domain>                                 <domain from="636" to="673" confidence="2.5E-6">WD40</domain>                                 <domain from="721" to="759" confidence="0.0043">WD40</domain>                                 <domain from="765" to="802" confidence="0.0036">WD40</domain>                                 <domain from="848" to="886" confidence="9.0E-10">WD40</domain>                                 <domain from="975" to="1013" confidence="1.9E-5">WD40</domain>                                 <domain from="1015" to="1053" confidence="2.4E-6">WD40</domain>                                 <domain from="1057" to="1095" confidence="2.7E-9">WD40</domain>                                 <domain from="1099" to="1137" confidence="4.9E-8">WD40</domain>                                 <domain from="1141" to="1177" confidence="0.011">WD40</domain>                              </domain_architecture>                           </sequence>                        </clade>                     </clade>                  </clade>                  <clade>                     <branch_length>0.34475</branch_length>                     <confidence type="unknown">100.0</confidence>                     <clade>                        <name>26_STRPU</name>                        <branch_length>0.36374</branch_length>                        <taxonomy>                           <code>STRPU</code>                        </taxonomy>                        <sequence>                           <domain_architecture length="1319">                              <domain from="18" to="98" confidence="3.4E-5">Death</domain>                              <domain from="189" to="481" confidence="1.8E-10">NB-ARC</domain>                              <domain from="630" to="668" confidence="8.2E-5">WD40</domain>                           </domain_architecture>                        </sequence>                     </clade>                     <clade>                        <name>25_STRPU</name>                        <branch_length>0.33137</branch_length>                        <taxonomy>                           <code>STRPU</code>                        </taxonomy>                        <sequence>                           <domain_architecture length="1947">                              <domain from="143" to="227" confidence="7.4E-5">Death</domain>                              <domain from="227" to="550" confidence="2.0E-13">NB-ARC</domain>                              <domain from="697" to="736" confidence="7.9E-4">WD40</domain>                              <domain from="745" to="785" confidence="1.5">WD40</domain>                              <domain from="1741" to="1836" confidence="2.0">Adeno_VII</domain>                           </domain_architecture>                        </sequence>                     </clade>                  </clade>               </clade>               <clade>                  <branch_length>1.31498</branch_length>                  <confidence type="unknown">100.0</confidence>                  <clade>                     <name>CED4_CAEEL</name>                     <branch_length>0.13241</branch_length>                     <taxonomy>                        <code>CAEEL</code>                     </taxonomy>                     <sequence>                        <domain_architecture length="714">                           <domain from="7" to="90" confidence="9.2E-14">CARD</domain>                           <domain from="116" to="442" confidence="5.8E-151">NB-ARC</domain>                        </domain_architecture>                     </sequence>                  </clade>                  <clade>                     <name>31_CAEBR</name>                     <branch_length>0.04777</branch_length>                     <taxonomy>                        <code>CAEBR</code>                     </taxonomy>                     <sequence>                        <domain_architecture length="554">                           <domain from="1" to="75" confidence="0.0046">CARD</domain>                           <domain from="101" to="427" confidence="2.1E-123">NB-ARC</domain>                        </domain_architecture>                     </sequence>                  </clade>               </clade>            </clade>            <clade>               <branch_length>0.13172</branch_length>               <confidence type="unknown">45.0</confidence>               <clade>                  <branch_length>0.24915</branch_length>                  <confidence type="unknown">95.0</confidence>                  <clade>                     <branch_length>0.76898</branch_length>                     <confidence type="unknown">100.0</confidence>                     <clade>                        <name>28_DROPS</name>                        <branch_length>0.1732</branch_length>                        <taxonomy>                           <code>DROPS</code>                        </taxonomy>                        <sequence>                           <domain_architecture length="535">                              <domain from="112" to="399" confidence="1.4E-5">NB-ARC</domain>                           </domain_architecture>                        </sequence>                     </clade>                     <clade>                        <name>Dark_DROME</name>                        <branch_length>0.18863</branch_length>                        <taxonomy>                           <code>DROME</code>                        </taxonomy>                        <sequence>                           <domain_architecture length="1421">                              <domain from="108" to="397" confidence="2.1E-5">NB-ARC</domain>                           </domain_architecture>                        </sequence>                     </clade>                  </clade>                  <clade>                     <name>29_AEDAE</name>                     <branch_length>0.86398</branch_length>                     <taxonomy>                        <code>AEDAE</code>                     </taxonomy>                     <sequence>                        <domain_architecture length="423">                           <domain from="109" to="421" confidence="9.3E-6">NB-ARC</domain>                        </domain_architecture>                     </sequence>                  </clade>               </clade>               <clade>                  <name>30_TRICA</name>                  <branch_length>0.97698</branch_length>                  <taxonomy>                     <code>TRICA</code>                  </taxonomy>                  <sequence>                     <domain_architecture length="1279">                        <domain from="5" to="81" confidence="0.59">CARD</domain>                        <domain from="92" to="400" confidence="9.0E-11">NB-ARC</domain>                        <domain from="630" to="668" confidence="1.1E-5">WD40</domain>                     </domain_architecture>                  </sequence>               </clade>            </clade>         </clade>         <clade>            <branch_length>0.18105</branch_length>            <confidence type="unknown">89.0</confidence>            <clade>               <branch_length>0.15891</branch_length>               <confidence type="unknown">64.0</confidence>               <clade>                  <branch_length>0.54836</branch_length>                  <confidence type="unknown">100.0</confidence>                  <clade>                     <branch_length>0.09305</branch_length>                     <confidence type="unknown">46.0</confidence>                     <clade>                        <branch_length>0.21648</branch_length>                        <confidence type="unknown">61.0</confidence>                        <clade>                           <branch_length>0.93134</branch_length>                           <confidence type="unknown">100.0</confidence>                           <clade>                              <name>34_BRAFL</name>                              <branch_length>0.093</branch_length>                              <taxonomy>                                 <code>BRAFL</code>                              </taxonomy>                              <sequence>                                 <domain_architecture length="752">                                    <domain from="49" to="356" confidence="9.0E-6">NB-ARC</domain>                                 </domain_architecture>                              </sequence>                           </clade>                           <clade>                              <name>35_BRAFL</name>                              <branch_length>0.08226</branch_length>                              <taxonomy>                                 <code>BRAFL</code>                              </taxonomy>                              <sequence>                                 <domain_architecture length="753">                                    <domain from="25" to="105" confidence="0.16">DED</domain>                                    <domain from="113" to="409" confidence="1.1E-6">NB-ARC</domain>                                 </domain_architecture>                              </sequence>                           </clade>                        </clade>                        <clade>                           <name>8_BRAFL</name>                           <branch_length>0.58563</branch_length>                           <taxonomy>                              <code>BRAFL</code>                           </taxonomy>                           <sequence>                              <domain_architecture length="916">                                 <domain from="58" to="369" confidence="8.4E-7">NB-ARC</domain>                              </domain_architecture>                           </sequence>                        </clade>                     </clade>                     <clade>                        <branch_length>0.28437</branch_length>                        <confidence type="unknown">84.0</confidence>                        <clade>                           <name>20_NEMVE</name>                           <branch_length>0.71946</branch_length>                           <taxonomy>                              <code>NEMVE</code>                           </taxonomy>                           <sequence>                              <domain_architecture length="786">                                 <domain from="8" to="91" confidence="1.7E-17">DED</domain>                                 <domain from="8" to="85" confidence="0.37">PAAD_DAPIN</domain>                                 <domain from="90" to="388" confidence="6.8E-5">NB-ARC</domain>                                 <domain from="575" to="608" confidence="0.27">TPR_1</domain>                                 <domain from="657" to="690" confidence="0.22">TPR_2</domain>                                 <domain from="698" to="731" confidence="4.2E-5">TPR_2</domain>                              </domain_architecture>                           </sequence>                        </clade>                        <clade>                           <name>21_NEMVE</name>                           <branch_length>0.9571</branch_length>                           <taxonomy>                              <code>NEMVE</code>                           </taxonomy>                           <sequence>                              <domain_architecture length="1563">                                 <domain from="234" to="317" confidence="2.3E-14">CARD</domain>                                 <domain from="238" to="318" confidence="0.5">Death</domain>                                 <domain from="329" to="619" confidence="0.022">NB-ARC</domain>                                 <domain from="906" to="939" confidence="0.17">TPR_1</domain>                                 <domain from="1326" to="1555" confidence="3.2E-26">RVT_1</domain>                              </domain_architecture>                           </sequence>                        </clade>                     </clade>                  </clade>                  <clade>                     <name>9_BRAFL</name>                     <branch_length>1.09612</branch_length>                     <taxonomy>                        <code>BRAFL</code>                     </taxonomy>                     <sequence>                        <domain_architecture length="1011">                           <domain from="5" to="321" confidence="5.0E-5">NB-ARC</domain>                           <domain from="497" to="616" confidence="1.6">BTAD</domain>                           <domain from="500" to="533" confidence="5.6E-4">TPR_1</domain>                           <domain from="542" to="575" confidence="0.43">TPR_1</domain>                           <domain from="626" to="659" confidence="0.0084">TPR_2</domain>                           <domain from="708" to="741" confidence="1.9E-4">TPR_2</domain>                           <domain from="708" to="739" confidence="1.3">TPR_3</domain>                           <domain from="708" to="733" confidence="0.16">TPR_4</domain>                           <domain from="833" to="916" confidence="5.6E-14">Death</domain>                           <domain from="846" to="868" confidence="0.36">LTXXQ</domain>                           <domain from="930" to="1011" confidence="8.3E-17">Death</domain>                        </domain_architecture>                     </sequence>                  </clade>               </clade>               <clade>                  <branch_length>0.34914</branch_length>                  <confidence type="unknown">98.0</confidence>                  <clade>                     <branch_length>0.22189</branch_length>                     <confidence type="unknown">95.0</confidence>                     <clade>                        <name>3_BRAFL</name>                        <branch_length>0.48766</branch_length>                        <taxonomy>                           <code>BRAFL</code>                        </taxonomy>                        <sequence>                           <domain_architecture length="2080">                              <domain from="116" to="423" confidence="1.4E-12">NB-ARC</domain>                              <domain from="620" to="659" confidence="1.4E-6">WD40</domain>                              <domain from="663" to="701" confidence="1.4E-8">WD40</domain>                              <domain from="705" to="743" confidence="3.0E-11">WD40</domain>                              <domain from="747" to="785" confidence="1.1E-8">WD40</domain>                              <domain from="788" to="826" confidence="1.6E-5">WD40</domain>                              <domain from="830" to="870" confidence="1.3E-4">WD40</domain>                              <domain from="874" to="914" confidence="6.2E-9">WD40</domain>                              <domain from="919" to="957" confidence="0.0011">WD40</domain>                              <domain from="961" to="1000" confidence="1.8E-8">WD40</domain>                              <domain from="1013" to="1051" confidence="1.3E-6">WD40</domain>                              <domain from="1055" to="1092" confidence="0.096">WD40</domain>                              <domain from="1794" to="1853" confidence="3.6E-4">Collagen</domain>                           </domain_architecture>                        </sequence>                     </clade>                     <clade>                        <name>2_BRAFL</name>                        <branch_length>0.65293</branch_length>                        <taxonomy>                           <code>BRAFL</code>                        </taxonomy>                        <sequence>                           <domain_architecture length="1691">                              <domain from="162" to="457" confidence="4.4E-10">NB-ARC</domain>                              <domain from="640" to="680" confidence="0.0068">WD40</domain>                              <domain from="684" to="722" confidence="1.6E-8">WD40</domain>                              <domain from="726" to="764" confidence="6.0E-9">WD40</domain>                              <domain from="827" to="865" confidence="6.9E-10">WD40</domain>                              <domain from="868" to="906" confidence="1.2E-6">WD40</domain>                              <domain from="910" to="950" confidence="0.0080">WD40</domain>                              <domain from="954" to="994" confidence="0.0016">WD40</domain>                              <domain from="999" to="1037" confidence="4.9E-6">WD40</domain>                              <domain from="1042" to="1080" confidence="6.3E-8">WD40</domain>                              <domain from="1100" to="1138" confidence="1.9E-8">WD40</domain>                              <domain from="1142" to="1178" confidence="1.4">WD40</domain>                              <domain from="1577" to="1615" confidence="4.3E-4">WD40</domain>                           </domain_architecture>                        </sequence>                     </clade>                  </clade>                  <clade>                     <name>19_NEMVE</name>                     <branch_length>0.57144</branch_length>                     <taxonomy>                        <code>NEMVE</code>                     </taxonomy>                     <sequence>                        <domain_architecture length="1649">                           <domain from="99" to="174" confidence="4.6E-7">DED</domain>                           <domain from="181" to="503" confidence="8.0E-13">NB-ARC</domain>                           <domain from="696" to="734" confidence="1.4E-8">WD40</domain>                           <domain from="738" to="776" confidence="2.9E-9">WD40</domain>                           <domain from="780" to="818" confidence="3.8E-10">WD40</domain>                           <domain from="822" to="860" confidence="6.4E-9">WD40</domain>                           <domain from="864" to="902" confidence="2.1E-10">WD40</domain>                           <domain from="906" to="944" confidence="1.3E-8">WD40</domain>                           <domain from="948" to="986" confidence="1.2E-8">WD40</domain>                           <domain from="990" to="1028" confidence="9.4E-8">WD40</domain>                           <domain from="1032" to="1070" confidence="6.0E-8">WD40</domain>                           <domain from="1074" to="1112" confidence="2.6E-4">WD40</domain>                           <domain from="1364" to="1597" confidence="1.9">SGL</domain>                           <domain from="1442" to="1480" confidence="9.7E-7">WD40</domain>                           <domain from="1527" to="1565" confidence="1.2">WD40</domain>                           <domain from="1568" to="1606" confidence="1.1E-6">WD40</domain>                        </domain_architecture>                     </sequence>                  </clade>               </clade>            </clade>            <clade>               <branch_length>0.43438</branch_length>               <confidence type="unknown">92.0</confidence>               <clade>                  <branch_length>0.92214</branch_length>                  <confidence type="unknown">100.0</confidence>                  <clade>                     <name>37_BRAFL</name>                     <branch_length>0.21133</branch_length>                     <taxonomy>                        <code>BRAFL</code>                     </taxonomy>                     <sequence>                        <domain_architecture length="1793">                           <domain from="6" to="89" confidence="9.6E-13">CARD</domain>                           <domain from="118" to="202" confidence="4.5E-9">CARD</domain>                           <domain from="206" to="491" confidence="0.0011">NB-ARC</domain>                           <domain from="238" to="388" confidence="0.0043">NACHT</domain>                        </domain_architecture>                     </sequence>                  </clade>                  <clade>                     <name>36_BRAFL</name>                     <branch_length>0.16225</branch_length>                     <taxonomy>                        <code>BRAFL</code>                     </taxonomy>                     <sequence>                        <domain_architecture length="918">                           <domain from="9" to="93" confidence="1.6E-9">CARD</domain>                           <domain from="98" to="403" confidence="0.0019">NB-ARC</domain>                        </domain_architecture>                     </sequence>                  </clade>               </clade>               <clade>                  <name>33_BRAFL</name>                  <branch_length>0.8363</branch_length>                  <taxonomy>                     <code>BRAFL</code>                  </taxonomy>                  <sequence>                     <domain_architecture length="1212">                        <domain from="5" to="87" confidence="4.7E-12">Death</domain>                        <domain from="154" to="465" confidence="2.0E-6">NB-ARC</domain>                     </domain_architecture>                  </sequence>               </clade>            </clade>         </clade>      </clade>   </phylogeny></phyloxml>'
        }
    };



}());

