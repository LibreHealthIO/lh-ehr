<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 3/9/16
 * Time: 2:07 PM
 */

class PatientTagRepository
{
    public function fetchPatientsForTag( $tag )
    {

    }

    public function fetchTagsForPatient( $pid )
    {
        $entry = new PatientTagEntry();
        $sql = $entry->getStatement()." WHERE P.pid = ?";
        $result = sqlStatement( $sql, array ( $pid ) );
        $tags = array();
        while ( $row = sqlFetchArray( $result ) ) {
            $tags[]= new Tag( $row );
        }
        return $tags;
    }

    public function deleteTagsForPatient( array $tag_ids, $pid )
    {
        $result = true;
        if ( count ( $tag_ids ) ) {
            // Only perfomr delelte if there are tags to delete
            $sql = "DELETE FROM tf_patients_tags WHERE pid = ?";
            $binds = array($pid);
            foreach ( $tag_ids as $tag ) {
                $sql .= " AND tag_id = ? ";
                $binds[] = $tag;
            }
            $result = sqlStatement($sql, $binds);
        }
        return $result;
    }

    public function addTagsForPatient( array $tag_ids, $pid )
    {
        $result = true;
        if ( count( $tag_ids ) ) {
            $sql = "INSERT INTO tf_patients_tags ( tag_id, pid, created_at, created_by, updated_at, updated_by, status ) VALUES ";
            $count = 0;
            foreach ($tag_ids as $tag) {
                $binds[] = $tag;
                $binds[] = $pid;
                $now = date('Y-m-d H:i:s');
                $binds[] = $now;
                $binds[] = $_SESSION['authUser'];
                $binds[] = $now;
                $binds[] = $_SESSION['authUser'];
                $binds[] = Tag::STATUS_ACTIVE;
                $sql .= " ( ?, ?, ?, ?, ?, ?, ? )";
                if ($count < count($tag_ids) - 1) {
                    $sql .= ", ";
                }
                $count++;
            }
            $result = sqlStatement($sql, $binds);
        }
        return $result;
    }

}