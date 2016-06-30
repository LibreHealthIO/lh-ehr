<?php

require_once __DIR__ . '/vendor/autoload.php';

//if ( $GLOBALS['tags_filters_enabled'] ) {


function tf_filter_patient_select( $username )
{
    // Fetch all the group filters
    $repo = new FilterRepository();
    $where = "";
    $patientsToHide = $repo->fetchPatientsToHideForUser( $username );
    if ( count( $patientsToHide ) ) {
        $pidString = implode(",", $patientsToHide);
        $where = " patient_data.pid NOT IN ( $pidString ) ";
    }

    return $where;
}
add_action( 'filter_patient_select', 'tf_filter_patient_select' );

function tf_filter_patient_select_pnuserapi( $username )
{
    // Fetch all the group filters
    $repo = new FilterRepository();
    $where = "";
    $patientsToHide = $repo->fetchPatientsToHideForUser( $username );
    if ( count( $patientsToHide ) ) {
        $pidString = implode(",", $patientsToHide);
        $where = " pd.pid NOT IN ( $pidString ) ";
    }

    return $where;
}
add_action( 'filter_patient_select_pnuserapi', 'tf_filter_patient_select_pnuserapi' );

function add_tags_filters_navigation()
{
    include __DIR__."/views/tags_filters_left_nav.php";
}
add_action('after_main_box', 'add_tags_filters_navigation');

function add_tag_filters_menu( &$menu_list )
{
    $option_id = 'test';
    $title = 'Tags';
    $formEntry=new stdClass();
    $formEntry->label=xl_form_title($title);
    $formEntry->url='/interface/tags_filters/index.php?action=tags';
    $formEntry->requirement=0;
    $formEntry->target='tags';
    array_push($menu_list->children,$formEntry);

    $title = 'Patients/Tags';
    $formEntry=new stdClass();
    $formEntry->label=xl_form_title($title);
    $formEntry->url='/interface/tags_filters/index.php?action=patients';
    $formEntry->requirement=0;
    $formEntry->target='tags';
    array_push($menu_list->children,$formEntry);

    $title = 'Filters';
    $formEntry=new stdClass();
    $formEntry->label=xl_form_title($title);
    $formEntry->url='/interface/tags_filters/index.php?action=filters';
    $formEntry->requirement=0;
    $formEntry->target='tags';
    array_push($menu_list->children,$formEntry);
}

function tag_filters_menu_update( &$menu_update_map )
{
    $menu_update_map["Administration"] = 'add_tag_filters_menu';
}
add_action('menu_update', 'tag_filters_menu_update');

function add_tags_demographics()
{
    include __DIR__."/views/tags_demographics.php";
}
add_action( 'demographics_before_first_table_row', 'add_tags_demographics' );

function update_tags_filters()
{
    $plugin = new TFPlugin();
    $plugin->migrate();
}
add_action( 'update_plugin', 'update_tags_filters' );

//}