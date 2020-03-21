<?php
/*
* Copyright (C) 2015-2017 Tony McCormick <tony@mi-squared.com>
*
* LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
* See the Mozilla Public License for more details. 
* If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
*
* @package LibreEHR
* @author  Tony McCormick <tony@mi-squared.com>
* @author  Terry Hill <teryhill@librehealth.io>
* @link    http://www.libreehr.org
*
* add a switch to the encounter const and make the query before assigning it to the const.
* check if Dollar amount in encounter so you can tell if the enconter has a dollar amount. Terry Hill 2016
*/

require_once(dirname(__FILE__)."/../../globals.php");
require_once("$srcdir/sql.inc");

$where1 = 'subscriber_relationship <> "" and insdat.inactive <> "1"';

class PreBillingIssuesAPI {
    
    const ENCOUNTERS_NO_RENDERING_PROVIDER =
    "SELECT pdat.lname as 'LName'
            , pdat.fname as 'FName'
            , pdat.pid as 'Pt ID'
            , pdat.pubpid as 'Pub Pt ID'
            , pdat.DOB as 'Pt DOB'
            , fenc.date AS 'Encounter Date'
            , fenc.encounter AS 'Enc ID'
            , concat('No Enc Rendering Provider') AS 'Rendering Provider'
    FROM form_encounter fenc
    inner join patient_data pdat on fenc.pid = pdat.pid 
    WHERE provider_id = 0 " ;
    
    const SUBSCRIBER_MISSING_DATA_FIELDS = 
    "SELECT pdat.lname as 'LName'
            , pdat.fname as 'FName'
            , insdat.pid as 'Pt ID'
            , pdat.pubpid as 'Pub Pt ID'
            , pdat.DOB as 'Pt DOB'
            , subscriber_relationship as 'Subscriber Relationship'
            , subscriber_lname as 'Subscriber Last Name'
            , concat('Ins type ',insdat.type) AS 'Insurance Type'
            , insdat.eDate AS 'End Date'
            , insdat.subscriber_street AS 'Pt Address Street'
            , insdat.subscriber_postal_code AS 'Pt Address Code'
            , insdat.subscriber_city AS 'Pt Address City'
            , insdat.subscriber_state AS 'Pt Address State'
            , insdat.subscriber_sex AS 'Pt Sex'
            , insdat.inactive AS 'inactive'
    FROM insurance_data insdat
    inner join patient_data pdat on insdat.pid = pdat.pid 
    WHERE subscriber_relationship <> '' and insdat.inactive <> '1'
    AND (
            insdat.subscriber_lname = '' OR
            insdat.subscriber_DOB = '' OR
            insdat.subscriber_street = '' OR
            insdat.subscriber_postal_code = '' OR
            insdat.subscriber_city = '' OR
            insdat.subscriber_state = '' OR
            insdat.subscriber_sex = ''
            )";
    
    const INSURANCE_NO_SUBSCRIBER_RELATIONSHIP = 
    "SELECT pdat.lname as 'LName'
            , pdat.fname as 'FName'
            , insdat.pid as 'Pt ID'
            , insdat.eDate AS 'End Date'
            , pdat.pubpid as 'Pub Pt ID'
            , pdat.DOB as 'Pt DOB'
            , insdat.inactive AS 'inactive'
            , concat('Ins type ',insdat.type) AS 'Insurance Type'
    FROM insurance_data insdat
    inner join patient_data pdat on insdat.pid = pdat.pid 
    WHERE subscriber_relationship = '' and insdat.inactive <> '1' 
    AND (
            insdat.provider <> '' OR
            insdat.provider > 0
            )";
    
    const INSURANCE_MISSING_FIELDS = 
    "SELECT pdat.lname as 'LName'
            , pdat.fname as 'FName'
            , insdat.pid as 'Pt ID'
            , pdat.pubpid as 'Pub Pt ID'
            , pdat.DOB as 'Pt DOB'
            , concat('Ins type ',insdat.type) AS 'Insurance Type'
            , insdat.plan_name AS 'Plan Name'
            , insdat.date AS 'Effective Date'
            , insdat.eDate AS 'End Date'
            , insdat.inactive AS 'inactive'
            , insdat.policy_number AS 'Policy Number'
            , insdat.group_number AS 'Group Number'
    FROM insurance_data insdat
    inner join patient_data pdat on insdat.pid = pdat.pid 
    WHERE insdat.provider > '' AND insdat.inactive <> '1' AND
            (
                insdat.plan_name = '' OR
                insdat.date = '000-00-00' OR
                insdat.policy_number = '' OR
                insdat.group_number = ''
            )";
    function __construct() {
    }
    
    function doQuery($query) {
        $result = array();
        $stmt = sqlStatement($query);
        while ($row = sqlFetchArray($stmt)) {
            array_push($result, $row);
        }
        
        if ( !$result || sizeof($result) < 1 ) {
            return null;
        }
        return $result;   
    }
    function findEncountersMissingProvider() {
        return $this->doQuery( self::ENCOUNTERS_NO_RENDERING_PROVIDER );
    }
    
    function findPatientInsuranceMissingSubscriberFields() {
        $results = $this->doQuery( self::SUBSCRIBER_MISSING_DATA_FIELDS );
        
        $data = array();
        
        for ($i = 0; $i < count($results); $i++) {
            $dataRow = array();
            $result = $results[$i];
            $decodedErrors = array();
            foreach($result as $key => $value) {
                if ( $key == 'Subscriber Last Name' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing subscriber last name'));
                }
                if ( $key == 'Pt Address Street' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing address street'));
                }
                if ( $key == 'Pt Address Code' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing address zip code'));
                }
                if ( $key == 'Pt Address City' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing address city'));
                }
                if ( $key == 'Pt Address State' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing address state'));
                }
                if ( $key == 'Pt Sex' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing subscriber sex'));
                }
                $dataRow[$key] = $value;
            }
            $dataRow['decodedErrors'] = $decodedErrors;
            $data[] = $dataRow;
        }
        
        return $data;
    }
    
    function findPatientInsuranceMissingSubscriberRelationship() {
        return $this->doQuery( self::INSURANCE_NO_SUBSCRIBER_RELATIONSHIP );
    }
    
    function findPatientInsuranceMissingInsuranceFields() {
        $results = $this->doQuery( self::INSURANCE_MISSING_FIELDS );
        
        $data = array();
        
        for ($i = 0; $i < count($results); $i++) {
            $dataRow = array();
            $result = $results[$i];
            $decodedErrors = array();
            foreach($result as $key => $value) {
                if ( $key == 'Plan Name' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing plan name'));
                }
                if ( $key == 'Effective Date' && $value == "000-00-00" ) {
                    array_push($decodedErrors, xl('Missing effective date'));
                }
                if ( $key == 'Policy Number' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing policy number'));
                }
                if ( $key == 'Group Number' && $value == "" ) {
                    array_push($decodedErrors, xl('Missing group number'));
                }
                $dataRow[$key] = $value;
            }
            $dataRow['decodedErrors'] = $decodedErrors;
            $data[] = $dataRow;
        }
        
        return $data;
    }
}
?>