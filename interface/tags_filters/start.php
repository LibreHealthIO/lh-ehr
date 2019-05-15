<?php

require_once __DIR__ . '/vendor/autoload.php';

if ( $GLOBALS['tags_filters_enabled'] ) {


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
    if ( !is_array( $menu_update_map["Administration"] ) ) {
        $menu_update_map["Administration"] = array( 'add_tag_filters_menu' );
    } else {
        $menu_update_map["Administration"] []= 'add_tag_filters_menu';
    }
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
}

if ( $GLOBALS['facility_acl']==1 ) {
///////////////////////////////////////////////////////////////////////////////
////test add of facility acl stuff.
function get_facilities_to_show( $username )
{
    // User facility is stored in users_facility table as facility_id
    $sql = "SELECT UF.facility_id, U.facility_id AS default_facility
        FROM users U
        JOIN users_facility UF
        ON UF.table_id = U.id
        WHERE
            U.username = ? AND
            UF.tablename = ?";
    $result = sqlStatement( $sql, array( $username, 'users') );
    $facilitiesToShow = array();
    $found = false;
    while ( $row = sqlFetchArray( $result ) ) {
        if ( $found === false ) {
            $facilitiesToShow[]= $row['default_facility'];
            $found = true;
        }
        $facilitiesToShow[]= $row['facility_id'];
    }

    return $facilitiesToShow;
}
////note, have not found filter_fetch_events in codebase yet.
function tf_filter_fetch_events( $username )
{
    $facilitiesToShow = get_facilities_to_show( $username );
    // Facility is id in patient_data
    $where = " p.facility = '-1' ";
    if ( count( $facilitiesToShow ) ) {
        $facilityString = implode( ",", $facilitiesToShow );
        $where = " AND p.facility IN ( $facilityString ) ";
    }

    return $where;
}
add_action( 'filter_fetch_events', 'tf_filter_fetch_events' );

function tf_filter_patient_select2( $username )
{
    $facilitiesToShow = get_facilities_to_show( $username );
    // Facility is id in patient_data
    $where = " patient_data.facility = '-1' ";
    if ( count( $facilitiesToShow ) ) {
        $facilityString = implode( ",", $facilitiesToShow );
        $where = " patient_data.facility IN ( $facilityString ) ";
    }

    return $where;
}
add_action( 'filter_patient_select', 'tf_filter_patient_select2' );

function tf_filter_patient_select_pnuserapi2( $username )
{
    $facilitiesToShow = get_facilities_to_show( $username );
    $where = " pd.facility = '-1' ";
    if ( count( $facilitiesToShow ) ) {
        $facilityString = implode(",", $facilitiesToShow);
        $where = " pd.facility IN ( $facilityString ) ";
    }

    return $where;
}
add_action( 'filter_patient_select_pnuserapi', 'tf_filter_patient_select_pnuserapi2' );

function tf_no_access_to_patient( $args )
{
    $pid = $args['pid'];
    $sql = "SELECT * FROM patient_data WHERE pid = ? LIMIT 1";
    $row = sqlQuery( $sql, array( $pid ) );
    $username = $args['username'];
    $facilitiesToShow = get_facilities_to_show( $username );
    $found = false;
    foreach ( $facilitiesToShow as $fid ) {
        if ( $fid == $row['facility'] ) {
            $found = true;
            break;
        }
    }

    if ( !$found ) {
        die(xl('Accessing this patient\'s demographics is not authorized.'));
    }
}
add_action( 'demographics_check_auth', 'tf_no_access_to_patient' );


//////////////////////////////////////////////////////////////////////
}
