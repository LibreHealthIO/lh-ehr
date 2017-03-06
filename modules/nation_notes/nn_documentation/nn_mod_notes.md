Core code changes:

In library/options.inc.php:
if ($GLOBALS['mod_nn'] == true){
		require_once(dirname(dirname(__FILE__)) . "/modules/nation_notes/nn_library_options.inc");}
else{
[main file contents]
}

In interface/main/forms/LBF/new.php
if ($GLOBALS['mod_nn'] == true){
		require_once(dirname(dirname(__FILE__)) . "/modules/nation_notes/nn_lbf_new.inc");}
else{
[main file contents]
}

In interface/main/super/edit_layout.php
if ($GLOBALS['mod_nn'] == true){
		require_once(dirname(dirname(__FILE__)) . "/modules/nation_notes/nn_super_edit_layout.inc");}
else{
[main file contents]
}

In interface/main/themes/ltr_mod_themes:
@import url("../../modules/nation_notes/nn_themes/nn_ltr_theme.css");

In interface/main/themes/rtl_mod_themes:
@import url("../../modules/nation_notes/nn_themes/nn_rtl_theme.css");