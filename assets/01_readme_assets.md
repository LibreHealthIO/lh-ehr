Assets directory structure

@package LibreHealthEHR

The assets directory is a top-level directory for 3rd party maintained libraries,
3rd party resource assets such as images and fonts, and related items that did not
originate in the LibreHealth EHR project, and are not exclusive to a module.

No files other than 01_readme_assets.md should be found directly under /assets/.
Sub-directories must be typed (/fonts, /css) or be language specific (/js).
Common core includes and other assets must be located under libreehr/libraries/includes
 etc... if they are LibreHealth EHR specific.

Complete modules in isolated directories may or may not be restricted to this on a
 case-by-case basis, though compliance is required when duplication would otherwise exist.
 ONLY 3rd party resources belong in this directory!
 