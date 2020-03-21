<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 3/3/16
 * Time: 1:35 PM
 */

// Build a WHERE ... clause for this username
// given a particular taggable object
function build_where( $taggable, $username )
{
    $myGroups = fetch_groups( $username );

    // Get all the filters related to this taggable entity
    $filters = $taggable->getFilters();


    // For each filter, build a where clause so the filter
    // can be applied to the query
    $where = "";
    foreach ( $filters as $filter ) {
        $where .= $filter->buildWhere();
    }

    return $where;
}

function get_tags()
{

}

function get_filters( Tag $tag )
{

}