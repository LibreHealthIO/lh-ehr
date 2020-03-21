<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 3/9/16
 * Time: 2:07 PM
 */

require_once($GLOBALS['srcdir']."/acl.inc");

class FilterRepository
{
    public function fetchPatientsToHideForUser( $username )
    {
        // Create the initial query making sure that we're within our time limitations,
        // and grab any filters that specify the logged-in user's username
        $sql = "SELECT
            F.id,
            F.created_at,
            F.created_by,
            F.requesting_action,
            F.requesting_type,
            F.requesting_entity,
            F.object_type,
            F.object_entity,
            F.effective_datetime,
            F.expiration_datetime,
            F.priority,
            F.note,
            F.updated_at,
            F.updated_by,
            F.deleted
            FROM tf_filters F
            WHERE F.deleted = 0 AND
                (
                    ( F.requesting_type = ? AND F.requesting_entity = ? ) ";

        $binds = array( 'user', $username );

        // Now get the logged-in user's groups and add them to the query
        $myGroups = acl_get_group_titles( $username );
        if ( is_array( $myGroups ) ) {
            $sql.= " OR ( ";
            $count = 0;
            foreach ( $myGroups as $group ) {
                $sql.= " ( F.requesting_type = ? AND F.requesting_entity = ? ) ";
                $binds[]= 'group';
                $binds[]= $group;
                if ( $count < count( $myGroups ) -1 ) {
                    $sql .= " OR ";
                }
                $count++;
            }
            $sql.= " ) ";
        }
        $sql .= " ) AND ";

        // Now calculate the dates
        $now = date( 'Y-m-d H:i:s' );
        $sql .= "( F.effective_datetime = '0000-00-00 00:00:00' OR ( F.effective_datetime != '0000-00-00 00:00:00' AND F.effective_datetime <= ? ) ) AND
                ( F.expiration_datetime = '0000-00-00 00:00:00' OR ( F.expiration_datetime != '0000-00-00 00:00:00' AND F.expiration_datetime >= ? ) )";
        $binds[]= $now;
        $binds[]= $now;

        $sql .= " ORDER BY F.priority DESC, F.requesting_type ASC ";

        // Execute the query that will give us all the filters for this user (and this users groups)
        // We order by explicit priority first, then user level filters get priority
        // We decide which patients to hide be going through the rules in order
        $result = sqlStatement( $sql, $binds );
        $patientsToHide = array();
        while ( $row = sqlFetchArray( $result ) ) {

            $filter = new Filter( $row );

            if ( $filter->object_type == 'tag' ) {

                // If the filter object type is a tag, look up all patients with this tag
                $binds = array();
                $sql = "SELECT PT.pid
                FROM tf_patients_tags PT
                WHERE PT.tag_id = ?";
                $binds[]= $filter->object_entity;

                $ptResult = sqlStatement( $sql, $binds );
                while ( $prow = sqlFetchArray( $ptResult ) ) {
                    if ( $row['requesting_action'] == 'allow' ) {
                        unset( $patientsToHide[$prow['pid']] );
                    } else if ( $row['requesting_action'] == 'deny' ) {
                        $patientsToHide[$prow['pid']] = $prow['pid'];
                    }
                }

            } else if ( $filter->object_type == 'patient' ) {

                // If the filter object is patient, hide or unhide patient accordingly
                if ( $row['requesting_action'] == 'allow' ) {
                    unset( $patientsToHide[$filter->object_entity] );
                } else if ( $row['requesting_action'] == 'deny' ) {
                    $patientsToHide[$filter->object_entity] = $filter->object_entity;
                }
            }


        }

        return $patientsToHide;
    }

    public function fetchAll( array $filters = null )
    {
        $sql = "SELECT
            F.id,
            F.created_at,
            F.created_by,
            F.requesting_action,
            F.requesting_type,
            F.requesting_entity,
            F.object_type,
            F.object_entity,
            F.effective_datetime,
            F.expiration_datetime,
            F.priority,
            F.note,
            F.updated_at,
            F.updated_by
            FROM tf_filters F";

        $binds = array();
        if ( count( $filters ) ) {
            $count = 0;
            $sql.= " WHERE deleted = 0 AND ( ";
            foreach ( $filters as $field => $value ) {
                if ( is_array( $value ) ) {
                    $sql.= " ( ";
                    $valueCount = 0;
                    foreach ( $value as $part ) {
                        $sql.= "F.$field = ? ";
                        $binds[]= $part;
                        if ( $valueCount < count( $value ) -1 ) {
                            $sql .= " OR ";
                        }
                        $valueCount++;
                    }
                    $sql.= " ) ";
                } else {
                    $sql.= " ( F.$field = ? ) ";
                    $binds[]= $value;
                }

                if ( $count < count( $filters ) -1 ) {
                    $sql .= " AND ";
                }
                $count++;
            }
            $sql.= " ) ";
        }

        $sql .= " ORDER BY F.priority DESC, F.requesting_type ASC ";

        $filters = array();
        $result = sqlStatement( $sql, $binds );
        while ( $row = sqlFetchArray( $result ) ) {
            $filter = new Filter( $row );
            $filters[]= $filter;
        }

        return $filters;
    }

    public function create( $args )
    {
        global $gacl;

        $fields = array( 'created_at', 'created_by', 'updated_at', 'updated_by',
            'requesting_action', 'requesting_type', 'requesting_entity',
            'object_type', 'object_entity',
            'note', 'gacl_aro', 'gacl_acl', 'effective_datetime', 'expiration_datetime', 'priority' );
        $sql = "INSERT INTO tf_filters ( ";
        $count = 0;
        $valuesString = "";
        $binds = array();
        foreach ( $fields as $field ) {
            $binds[$field]= isset( $args[$field] ) ? $args[$field] : '';
            $sql.= $field;
            $valuesString.= "?";
            if ( $count < count( $fields ) - 1 ) {
                $sql.= ", ";
                $valuesString.= ", ";
            }
            $count++;
        }
        $sql .= " ) VALUES ( $valuesString )";

//
//        Example ACO "Section > Values":
//
//    "Floors > 1st"
//
//    "Floors > 2nd"
//
//    "Rooms > Engines"


        $objectName = $binds['object_type'];
        $objectValue = $binds["object_$objectName"];

        // $gacl->add_object_section( $objectName, $objectValue, 1, 0, 'ACO' );
        //$gacl->add_object_section( $objectName, $objectValue, 1, 0, 'ACO' );

        //$gacl->add_object( 'acct', 'Billing (write optional)', 'bill', 10, 0, 'ACO' );

        $acl_id = 0;
        // Create the GACL records
//        $acl_id = $gacl->add_acl(
//            array( $binds['object_type'] => ), // aco array
//            array(), // aro array
//            array(), // aro group ids
//            null, // axo array
//            null, // axo group ids
//            ( $binds['requesting_action'] == 'allow' ) ? 1 : 0, // allow flag
//            1, // enabled
//            'write', // return value
//            $binds['note'], // note
//            'users', // ACL section value
//            null // ACL ID # Specific request
//        );

        $now = date( 'Y-m-d H:i:s' );
        $binds['created_at'] = $now;
        $binds['created_by'] = $_SESSION['authUser'];
        $binds['updated_at'] = $now;
        $binds['updated_by'] = $_SESSION['authUser'];
        $binds['gacl_acl'] = $acl_id;

        if ( $binds['requesting_type'] == 'group' ) {
            $binds['requesting_entity'] = $args['requesting_group'];
        } else if ( $binds['requesting_type'] == 'user' ) {
            $binds['requesting_entity'] = $args['requesting_user'];
        }

        if ( $binds['object_type'] == 'patient' ) {
            $binds['object_entity'] = $args['object_patient'];
        } else if ( $binds['object_type'] == 'tag' ) {
            $binds['object_entity'] = $args['object_tag'];
        }

        $result = sqlInsert( $sql, array_values( $binds ) );
        return $result;
    }

    public function delete( $id )
    {
        //acl_remove( $acl_title,  $acl_value );
        $statement = "UPDATE tf_filters SET deleted = 1 WHERE id = ?";
        return sqlQuery( $statement, array( $id ) );
    }

}