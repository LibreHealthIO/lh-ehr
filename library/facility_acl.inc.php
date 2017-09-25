<?php

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

function tf_filter_patient_select( $username )
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
add_action( 'filter_patient_select', 'tf_filter_patient_select' );

function tf_filter_patient_select_pnuserapi( $username )
{
    $facilitiesToShow = get_facilities_to_show( $username );
    $where = " pd.facility = '-1' ";
    if ( count( $facilitiesToShow ) ) {
        $facilityString = implode(",", $facilitiesToShow);
        $where = " pd.facility IN ( $facilityString ) ";
    }

    return $where;
}
add_action( 'filter_patient_select_pnuserapi', 'tf_filter_patient_select_pnuserapi' );

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

