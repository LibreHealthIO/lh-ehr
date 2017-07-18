/*global importScripts validateXML*/
(function(self){
	"use strict";

	//importScripts("shims.js");
	importScripts("../libs/xml.js/xmllint.js");

	var current_event,
		xml_file = "document.xml",
		rng_file = "schema.rng",
		xsd_file = "schema.xsd",
		schemas = {},
		last_line_number = 0,
		console = {log: function(debug_string){
							if(!current_event) {
								current_event = {index:-1};
							}
							self.postMessage({
								type:    "debug",
								index:   current_event.index,
								message: debug_string
							});
                  }
				},
		sjax    = function sjax(url){ //synchronous http request
					var synchronous_request = new XMLHttpRequest();

					synchronous_request.open("GET", url, false);
					synchronous_request.send(null);
					return to_ncrs(synchronous_request.responseText);
				},
		to_ncrs  = function(input){
			var response = "",
				i,
				char_code;

			for(i = 0; i < input.toString().length; i++){
				char_code = input.charCodeAt(i);
				response += (char_code > 127) ? "&#" + char_code + ";" : input.charAt(i);
			}
			return response;
		},
		parse_xmllint_line = function(line, xml_file, schema_file){
			var response = {
					line_number: undefined,
					target:      undefined,
					message:     undefined,
					type:        undefined
				};
			if(line.substr(0, xml_file.length + 1) === xml_file + ":" && line.indexOf("Relax-NG validity error") !== -1) {
				response.type = "error_line";
				line = line.substr(xml_file.length + 1).trim();
				response.line_number = parseInt(line.substr(0, line.indexOf(":")), 10) - 1; //seems to use a 1-based index. Weird.
				line = line.substr(line.indexOf(":") + 1).trim();
				if(line.substr(0, "element ".length) === "element ") {
					line = line.substr("element ".length).trim();
					response.target = line.substr(0, line.indexOf(":"));
				} else {
					console.log("Lint weirdness. Bug in the code? " + line);
				}
				line = line.substr(line.indexOf(":") + 1).trim();
				if(line.substr(0, "Relax-NG validity error :".length) === "Relax-NG validity error :"){
					line = line.substr("Relax-NG validity error :".length).trim();
				}
				response.message = line;
			} else if(line.substr(0, xml_file.length + 1) === xml_file + " " && line.indexOf("fails to validate") !== -1){
				response.type = "error_summary";
				response.message = line.substr(xml_file.length + 1);
			} else if(line.indexOf("parser error : Start tag expected")){
				response.type = "error_summary";
				response.message = "Malformed document.";
			} else if(line.indexOf("document.xml validates") >= 0) {
				response.type = "valid_document";
				response.message = "Document is valid";
			} else {
				console.log("Unable to parse xmlline_line: " + line);
			}
			if(response.line_number) {
				last_line_number = response.line_number;
			}
			return response;
		},
		get_schema = function(url){
			if(!schemas[current_event.schema_url]) {
				schemas[current_event.schema_url] = sjax(current_event.schema_url);
			}
			return schemas[current_event.schema_url];
		};


	self.onmessage = function(event){
		var module,
			xmllint_lines,
			xmllint_line,
			line,
			i,
			schema_file_extension,
			error_lines = [],
			error_summary = "",
			last_line_number;
		
		current_event = event.data;
		schema_file_extension = current_event.schema_url.substr(current_event.schema_url.lastIndexOf("."));
		module = {
			"xml":       to_ncrs(current_event.xml),
			"schema":    get_schema(current_event.schema_url),
			
		};
		switch(schema_file_extension){
			case ".xsd":
				module.arguments = ["--noout", "--schema", xsd_file, xml_file];
				break;
			case ".rng":
				module.arguments = ["--noout", "--relaxng", rng_file, xml_file];
				break;
			default:
				console.log("Error. Schema URL must end in either RNG or XSD.")
		}
		xmllint_lines = validateXML(module).split("\n");

		for(i = 0; i < xmllint_lines.length; i++){
			xmllint_line = xmllint_lines[i];
			if(!xmllint_line.length) continue;
			line = parse_xmllint_line(xmllint_line, xml_file, rng_file);
			if(line.type === "error_line"){
				error_lines.push(line);
			} else if(line.type === "error_summary"){
				error_summary = line;
			} else {
				console.log("New Line.type = " + line.type);
			}
		}

		self.postMessage({
			type:   "result",
			index:  current_event.index,
			result: {
					error_lines:   error_lines,
					error_summary: error_summary
					}
		});
	};
}(self));

