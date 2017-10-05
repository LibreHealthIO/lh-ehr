/*globals require, __filename, __dirname, console, process */
(function(){
    "use strict";
 
    var fs = require('fs'),
        path = require('path'),
        approot = __dirname,
        i,
        schemas,
        app_schemas,
        app_schemas_marker = /\{MANIFEST\-START\}[\s\S]*?\{MANIFEST\-END\}/g,
        app_schemas_path = path.join(path.dirname(approot), 'js', 'app-schemas.js'),
        manifest_path = path.join(approot, 'manifest.json'),
        blacklist = [path.basename(__filename), 'manifest.json', 'options.json', 'README.md'],
        manifest = {},
        get_schema_family = function(full_path){
            var file_options = full_path.substr(0, full_path.length - path.extname(full_path).length) + '.json',
                directory_options = path.dirname(full_path) + '/config.json',
                options_path;

            if(fs.existsSync && fs.existsSync(file_options) || path.existsSync && path.existsSync(file_options)){
                options_path = file_options;
            } else if(fs.existsSync && fs.existsSync(directory_options) || path.existsSync && path.existsSync(directory_options)){
                options_path = directory_options;
            } else {
                console.log("Can't find options file for schema at either " + file_options + " or " + directory_options + ". This file should define the schema-family so that Doctored.js can offer a better UI for editing. The schema-family value should align with a key in app-schemas.js and the variable doctored.schema_family = { ... ");
                process.exit();
            }
            return JSON.parse(fs.readFileSync(options_path))['schema-family'];
        },
        walk = function(files, current_directory){
            var i,
                file,
                full_path,
                manifest = [];

            for(i = 0; i < files.length; i++){
                file = files[i];
                if(blacklist.indexOf(file) >= 0) continue;
                if(path.extname(file) === ".json") continue;
                full_path = path.join(current_directory, file);
                if(fs.lstatSync(full_path).isDirectory()) {
                    manifest.push({
                        schema_family: file,
                        children: walk(fs.readdirSync(full_path), full_path)
                    });
                } else {
                    manifest.push({
                        schema: full_path.replace(approot, ''),
                        label: file.replace(path.extname(file), ''),
                        schema_family: get_schema_family(full_path)
                    });
                }
            }
            return manifest;
        };


    manifest = walk(fs.readdirSync(approot), approot);

    app_schemas = fs.readFileSync(app_schemas_path, 'utf8');

    if(!app_schemas.match(app_schemas_marker)){
        console.log("ERROR: No app_schemas_marker found in " + app_schemas_path);
        process.exit();
    }
    app_schemas = app_schemas.replace(app_schemas_marker, function(match){
        return '{MANIFEST-START}\n    doctored.schemas_manifest = ' + JSON.stringify(manifest) + ';\n    // {MANIFEST-END}';
    });

    fs.writeFileSync(
        app_schemas_path,
        app_schemas
    );

    console.log("Done. Manifest written to " + app_schemas_path);

}());
