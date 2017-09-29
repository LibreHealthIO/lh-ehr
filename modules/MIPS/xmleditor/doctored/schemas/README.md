#Schemas must be flattened before Doctored.js can use them.

Doctored.js cannot resolve includes/imports/etc. That means you need to resolve all the external references to make a single file.

## Relax NG
Trang/Jing can be used to flatten RelaxNG (I think).

## W3C Schema
There isn't any Free software that I'm aware of that can flatten XSDs that have XML catalogues and includes.

XML Spy for Windows (which has a free trial and can run in Wine) can flatten files.

# How to add a schema
1. Flatten your schema so that it has no imports.
1. Put your schema file in a directory under doctored/schemas along with a config.json
1. Run ./node rebuild-schema-manifest.js which will modify js/app-schemas.js
1. Open js/app-schemas.js and find doctored.schema_family and default document for that schema, name the inline-level elements (by default all elements are block-level, so you only name the inline-level elements), etc.

This is a bit convoluted, I know. If you can think of a better way, or if you can contribute code to make it better, then that would be lovely.
