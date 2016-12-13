<?php
/*
 * Inc file for the 276 Claim Status file creation
 *
 * This program creates the segments for the x12 276 Claim Status file
 *
 * @copyright Copyright (C) 2016-2017 Terry Hill <teryhill@librehealth.io>
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0 and the following
 * Healthcare Disclaimer
 *
 * In the United States, or any other jurisdictions where they may apply, the following additional disclaimer of
 * warranty and limitation of liability are hereby incorporated into the terms and conditions of MPL 2.0:
 *
 * No warranties of any kind whatsoever are made as to the results that You will obtain from relying upon the covered code
 * (or any information or content obtained by way of the covered code), including but not limited to compliance with privacy
 * laws or regulations or clinical care industry standards and protocols. Use of the covered code is not a substitute for a
 * health care provider’s standard practice or professional judgment. Any decision with regard to the appropriateness of treatment,
 * or the validity or reliability of information or content made available by the covered code, is the sole responsibility
 * of the health care provider. Consequently, it is incumbent upon each health care provider to verify all medical history
 * and treatment plans with each patient.
 *
 * Under no circumstances and under no legal theory, whether tort (including negligence), contract, or otherwise,
 * shall any Contributor, or anyone who distributes Covered Software as permitted by the license,
 * be liable to You for any indirect, special, incidental, consequential damages of any character including,
 * without limitation, damages for loss of goodwill, work stoppage, computer failure or malfunction,
 * or any and all other damages or losses, of any nature whatsoever (direct or otherwise)
 * on account of or associated with the use or inability to use the covered content (including, without limitation,
 * the use of information or content made available by the covered code, all documentation associated therewith,
 * and the failure of the covered code to comply with privacy laws and regulations or clinical care industry
 * standards and protocols), even if such party shall have been informed of the possibility of such damages.
 *
 * See the Mozilla Public License for more details.
 *
 * @package LibreEHR
 * @author Terry Hill <teryhill@librehealth.io>
 * @link http://www.libreehr.org
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

# SEGMENT FUNCTION START
# ISA Segment  - EDI-276 format

function create_ISA($row,$X12info,$segTer,$compEleSep) {

    $ISA    = array();
    $ISA[0] = "ISA";                             # Interchange Control Header Segment ID
    $ISA[1] = "00";                              # Author Info Qualifier
    $ISA[2] = str_pad("0000000",10," ");         # Author Information
    $ISA[3] = "00";                              # Security Information Qualifier
    $ISA[4] = str_pad("0000000000",10," ");      # Security Information
    $ISA[5] = str_pad("ZZ",2," ");               # Interchange ID Qualifier
    $ISA[6] = str_pad($X12info[2],15," ");       # INTERCHANGE SENDER ID
    $ISA[7] = str_pad("ZZ",2," ");               # Interchange ID Qualifier
    $ISA[8] = str_pad($X12info[3],15," ");       # INTERCHANGE RECEIVER ID
    $ISA[9] = str_pad(date('ymd'),6," ");        # Interchange Date (YYMMDD)
    $ISA[10] = str_pad(date('Hi'),4," ");        # Interchange Time (HHMM)
    $ISA[11] = "^";                              # Interchange Control Standards Identifier
    $ISA[12] = str_pad("00501",5," ");           # Interchange Control Version Number
    $ISA[13] = str_pad("000000001",9," ");       # INTERCHANGE CONTROL NUMBER
    $ISA[14] = str_pad("1",1," ");               # Acknowledgment Request [0= not requested, 1= requested]
    $ISA[15] =  str_pad("T",1," ");              # Usage Indicator [ P = Production Data, T = Test Data ]
    $ISA['Created'] = implode('*', $ISA);        # Data Element Separator
    $ISA['Created'] = $ISA['Created'] ."*";
    $ISA['Created'] = $ISA ['Created']  . $compEleSep . $segTer;
    return trim($ISA['Created']);
}

# GS Segment  - EDI-276 format

function create_GS($row,$X12info,$segTer,$compEleSep) {

    $GS    = array();
    $GS[0] = "GS";                               # Functional Group Header Segment ID
    $GS[1] = "HR";                               # Functional ID Code [ HR = Health Care Claim Status Request (276) ]
    $GS[2] =  $X12info[2];                       # Application Sender's ID
    $GS[3] =  $X12info[3];                       # Application Receiver's ID
    $GS[4] = date('Ymd');                        # Date [CCYYMMDD]
    $GS[5] = date('His');                        # Time [HHMM] Group Creation Time
    $GS[6] = "2";                                # Group Control Number No zeros for 5010
    $GS[7] = "X";                                # Responsible Agency Code Accredited Standards Committee X12 ]
    $GS[8] = "005010X279A1";                     # Version Release / Industry[ Identifier Code Query 005010X279A1
    $GS['Created'] = implode('*', $GS);          # Data Element Separator
    $GS['Created'] = $GS ['Created'] . $segTer;  # change the information in the tag or change the tag
    return trim($GS['Created']);
}

# ST Segment  - EDI-276 format

function create_ST($row,$X12info,$segTer,$compEleSep) {

    $ST    = array();
    $ST[0] = "ST";                               # Transaction Set Header Segment ID
    $ST[1] = "276";                              # Transaction Set Identifier Code (Inquiry Request)
    $ST[2] = "000000003";                        # Transaction Set Control Number - Must match SE's
    $ST[3] = "005010X279A1";                     # Standard 005010X279A1 in $ST[3]
    $ST['Created'] = implode('*', $ST);          # Data Element Separator
    $ST['Created'] = $ST ['Created'] . $segTer;
    return trim($ST['Created']);
}

# BHT Segment  - EDI-276 format

function create_BHT($row,$X12info,$segTer,$compEleSep) {

    $BHT    = array();
    $BHT[0] = "BHT";                             # Beginning of Hierarchical Transaction Segment ID
    $BHT[1] = "0022";                            # Subscriber Structure Code
    $BHT[2] = "13";                              # Purpose Code - This is a Request
    $BHT[3] = "LibreEHRTest";                    # Submitter Transaction Identifier
    $BHT[4] = str_pad(date('Ymd'),8," ");        # Date Transaction Set Created
    $BHT[5] = str_pad(date('Hi'),4," ");         # Time Transaction Set Created no space after and 1300 is plenty
    $BHT['Created'] = implode('*', $BHT);        # Data Element Separator
    $BHT['Created'] = $BHT ['Created'] . $segTer;
    return trim($BHT['Created']);
}

# HL Segment  - EDI-276 format

function create_HL($row, $nHlCounter,$X12info,$segTer,$compEleSep) {

    $HL        = array();
    $HL[0]     = "HL";                           # Hierarchical Level Segment ID
    $HL_LEN[0] =  2;
    $HL[1]     = $nHlCounter;                    # Hierarchical ID No.
    if($nHlCounter == 1)
    {
        $HL[2] = "";
        $HL[3] = 20;                             # Description: Identifies the payor, maintainer, or source of the information.
        $HL[4] = 1;                              # 1 Additional Subordinate HL Data Segment in This Hierarchical Structure.
    }
    else if($nHlCounter == 2)
    {
        $HL[2] = 1;                              # Hierarchical Parent ID Number
        $HL[3] = 21;                             # Hierarchical Level Code. '21' Information Receiver
        $HL[4] = 1;                              # 1 Additional Subordinate HL Data Segment in This Hierarchical Structure.
    }
    else
    {
        $HL[2] = 2;
        $HL[3] = 22;                             # Hierarchical Level Code.'22' Subscriber
        $HL[4] = 0;                              # 0 no Additional Subordinate in the Hierarchical Structure.
    }

    $HL['Created'] = implode('*', $HL);          # Data Element Separator
    $HL['Created'] = $HL ['Created'] . $segTer;
    return trim($HL['Created']);
}

# NM1 Segment  - EDI-276 format

function create_NM1($row,$nm1Cast,$X12info,$segTer,$compEleSep) {

    $NM1        = array();
    $NM1[0]     = "NM1";                         # Subscriber Name Segment ID
    if($nm1Cast == 'PR')
    {
        $NM1[1] = "PR";                          # Entity ID Code - Payer [PR Payer]
        $NM1[2] = "2";                           # Entity Type - Non-Person
        $NM1[3] = $row["payer_name"];            # Organizational Name
        $NM1[4] = "";                            # Data Element not required.
        $NM1[5] = "";                            # Data Element not required.
        $NM1[6] = "";                            # Data Element not required.
        $NM1[7] = "";                            # Data Element not required.
        $NM1[8] = "46";                          # 46 - Electronic Transmitter Identification Number (ETIN)
        $NM1[9] = $X12info[3];                   # Application Sender's ID
    }
    else if($nm1Cast == '1P')
    {
        $NM1[1] = "1P";                          # Entity ID Code - Facility [FA Facility]
        $NM1[2] = "2";                           # Entity Type - Non-Person
        $NM1[3] = $row['facility_name'];         # Organizational Name
        $NM1[4] = "";                            # Data Element not required.
        $NM1[5] = "";                            # Data Element not required.
        $NM1[6] = "";                            # Data Element not required.
        $NM1[7] = "";                            # Data Element not required.
        $NM1[8] = "XX";
        $NM1[9] = $row['facility_npi'];
    }

       else if($nm1Cast == '41')
    {
        $NM1[1] = "41";                          # Insured or Subscriber
        $NM1[2] = "2";                           # Entity Type - Non-Person
        $NM1[3] = $X12info[0];                   # X12 Name
        $NM1[4] = "";                            # Data Element not required.
        $NM1[5] = "";                            # Data Element not required.
        $NM1[6] = "";                            # Data Element not required.
        $NM1[7] = "";                            # Data Element not required.
        $NM1[8] = "46";                          # 46 - Electronic Transmitter Identification Number (ETIN)
        $NM1[9] = $X12info[3];                   # Application Sender's ID
    }

    else if($nm1Cast == 'IL')
    {
        $NM1[1] = "IL";                          # Insured or Subscriber
        $NM1[2] = "1";                           # Entity Type - Person
        $NM1[3] = $row['lname'];                 # last Name
        $NM1[4] = $row['fname'];                 # first Name
        $NM1[5] = $row['mname'];                 # middle Name
        $NM1[6] = "";                            # data element
        $NM1[7] = "";                            # data element
        $NM1[8] = "MI";                          # Identification Code Qualifier
        $NM1[9] = $row['subscriber_ss'];         # Identification Code
    }
    
    $NM1['Created'] = implode('*', $NM1);        # Data Element Separator
    $NM1['Created'] = $NM1['Created'] . $segTer;
    return trim($NM1['Created']);
}

# REF Segment  - EDI-276 format

function create_REF($row,$ref,$X12info,$segTer,$compEleSep) {

    $REF    = array();
    $REF[0] = "REF";                             # Subscriber Additional Identification
    if($ref == '1P')
    {
        $REF[1] = "4A";                          # Reference Identification Qualifier
        $REF[2] = $row['provider_pin'];          # Provider Pin.
    }
    else
    {
        $REF[1] = "EJ";                          # 'EJ' for Patient Account Number
        $REF[2] = $row['pid'];                   # Patient Account No.
    }
    $REF['Created'] = implode('*', $REF);        # Data Element Separator
    $REF['Created'] = $REF['Created'] . $segTer;
    return trim($REF['Created']);
}

# TRN Segment - EDI-276 format

function create_TRN($row,$tracno,$refiden,$X12info,$segTer,$compEleSep) {

    $TRN    = array();
    $TRN[0] = "TRN";                             # Subscriber Trace Number Segment ID
    $TRN[1] = "1";                               # Trace Type Code � Current Transaction Trace Numbers
    $TRN[2] = $tracno;                           # Trace Number
   # $TRN[3] = "";                                # Data Element not required.
   # $TRN[4] = "";                                # Data Element not required.
    $TRN['Created'] = implode('*', $TRN);        # Data Element Separator
    $TRN['Created'] = $TRN['Created'] . $segTer;
    return trim($TRN['Created']);
}

# DMG Segment - EDI-276 format

function create_DMG($row,$X12info,$segTer,$compEleSep) {

    $DMG    = array();
    $DMG[0] = "DMG";                             # Date or Time or Period Segment ID
    $DMG[1] = "D8";                              # Date Format Qualifier - (D8 means CCYYMMDD)
    $DMG[2] = $row['dob'];                       # Subscriber's Birth date
    $DMG['Created'] = implode('*', $DMG);        # Data Element Separator
    $DMG['Created'] = $DMG['Created'] .  $segTer;
    return trim($DMG['Created']);
}

# DTP Segment - EDI-276 format

function create_DTP($row,$qual,$X12info,$segTer,$compEleSep) {

    $DTP    = array();
    $DTP[0] = "DTP";                             # Date or Time or Period Segment ID
    $DTP[1] = $qual;                             # Qualifier - Date of Service
    $DTP[2] = "D8";                              # Date Format Qualifier - (D8 means CCYYMMDD)
    if($qual == '102'){
        $DTP[3] = $row['date'];                  # Date
    }else{
        $DTP[3] = $row['enc_dosDate'];          # Date of Service !!! Change this to the thru date for 276
    }
    $DTP['Created'] = implode('*', $DTP);        # Data Element Separator
    $DTP['Created'] = $DTP['Created'] .  $segTer;
    return trim($DTP['Created']);
}

# EQ Segment - EDI-276 format

function create_EQ($row,$X12info,$segTer,$compEleSep) {

    $EQ       = array();
    $EQ[0]    = "EQ";                            # Subscriber Eligibility or Benefit Inquiry Information
    $EQ[1]    = "30";                            # Service Type Code
    $EQ['Created'] = implode('*', $EQ);          # Data Element Separator
    $EQ['Created'] = $EQ['Created'] . $segTer;
    return trim($EQ['Created']);
}

# SE Segment - EDI-276 format

function create_SE($row,$segmentcount,$X12info,$segTer,$compEleSep) {

    $SE    = array();
    $SE[0] = "SE";                               # Transaction Set Trailer Segment ID
    $SE[1] = $segmentcount;                      # Segment Count
    $SE[2] = "000000003";                        # Transaction Set Control Number - Must match ST's
    $SE['Created'] = implode('*', $SE);          # Data Element Separator
    $SE['Created'] = $SE['Created'] . $segTer;
    return trim($SE['Created']);
}

# GE Segment - EDI-276 format

function create_GE($row,$X12info,$segTer,$compEleSep) {

    $GE    = array();
    $GE[0] = "GE";                               # Functional Group Trailer Segment ID
    $GE[1] = "1";                                # Number of included Transaction Sets
    $GE[2] = "2";                                # Group Control Number
    $GE['Created'] = implode('*', $GE);          # Data Element Separator
    $GE['Created'] = $GE['Created'] . $segTer;
    return trim($GE['Created']);
}

# IEA Segment - EDI-276 format

function create_IEA($row,$X12info,$segTer,$compEleSep) {

    $IEA    = array();
    $IEA[0] = "IEA";                             # Interchange Control Trailer Segment ID
    $IEA[1] = "1";                               # Number of included Functional Groups
    $IEA[2] = "000000001";                       # Interchange Control Number
    $IEA['Created'] = implode('*', $IEA);
    $IEA['Created'] = $IEA['Created'] .  $segTer;
    return trim($IEA['Created']);
}

function translate_relationship($relationship) {
    switch ($relationship) {
        case "spouse":
            return "01";
            break;
        case "child":
            return "19";
            break;
        case "self":
        default:
            return "S";
    }
}

# EDI-276 Batch file Generation

function print_clstr($res,$X12info,$segTer,$compEleSep){

    $i=1;
    $PATEDI = "";

    # For Header Segment
    $nHlCounter = 1;
    $rowCount   = 0;
    $trccnt     = 0;
    $trcNo     = "54273347" . $trccnt ; 
    $refiden    = 5432101;

    while ($row = sqlFetchArray($res))
    {
        if($nHlCounter == 1)
        {
            # create ISA
            $PATEDI = create_ISA($row,$X12info,$segTer,$compEleSep);

            # create GS
            $PATEDI .= create_GS($row,$X12info,$segTer,$compEleSep);

            # create ST
            $PATEDI .= create_ST($row,$X12info,$segTer,$compEleSep);

            # create BHT
            $PATEDI .= create_BHT($row,$X12info,$segTer,$compEleSep);

            # For Payer Segment
            $PATEDI .= create_HL($row,1,$X12info,$segTer,$compEleSep);
            $PATEDI .= create_NM1($row,'PR',$X12info,$segTer,$compEleSep);

            # For Provider Segment
            $PATEDI .= create_HL($row,2,$X12info,$segTer,$compEleSep);
            $PATEDI .= create_NM1($row,'41',$X12info,$segTer,$compEleSep);
            $PATEDI .= create_HL($row,3,$X12info,$segTer,$compEleSep);
            $PATEDI .= create_NM1($row,'1P',$X12info,$segTer,$compEleSep);

            #$PATEDI .= create_REF($row,'FA',$X12info,$segTer,$compEleSep);

            $nHlCounter = $nHlCounter + 3;
            $segmentcount = 7; # segement counts - start from ST
        }

        # For Subscriber Segment

        $PATEDI .= create_HL($row,$nHlCounter,$X12info,$segTer,$compEleSep);
        #$PATEDI .= create_TRN($row,$trcNo,$refiden,$X12info,$segTer,$compEleSep);
        #$PATEDI .= create_NM1($row,'IL',$X12info,$segTer,$compEleSep);
        #$PATEDI .= create_REF($row,'IL',$X12info,$segTer,$compEleSep);
        $PATEDI .= create_DMG($row,$X12info,$segTer,$compEleSep);

        #$PATEDI .= create_DTP($row,'102',$X12info,$segTer,$compEleSep);

        $PATEDI .= create_NM1($row,'IL',$X12info,$segTer,$compEleSep);
        $PATEDI .= create_TRN($row,$trcNo,$refiden,$X12info,$segTer,$compEleSep);
        $PATEDI .= create_DTP($row,'472',$X12info,$segTer,$compEleSep);
        #$PATEDI .= create_EQ($row,$X12info,$segTer,$compEleSep);

        $segmentcount = $segmentcount + 7;
        $nHlCounter   = $nHlCounter + 1;
        $rowCount     = $rowCount + 1;
        $trccnt       = $trccnt  + 1;
        $trcNo        = substr($trcNo,0,8) . $trccnt;
        $refiden      = $refiden + 1;


        if($rowCount == sqlNumRows($res))
        {
            $segmentcount = $segmentcount + 1;
            $PATEDI .= create_SE($row,$segmentcount,$X12info,$segTer,$compEleSep);
            $PATEDI .= create_GE($row,$X12info,$segTer,$compEleSep);
            $PATEDI .= create_IEA($row,$X12info,$segTer,$compEleSep);
        }
    }

    echo $PATEDI;
}

# Report Generation

function show_clstr($res,$X12info,$segTer,$compEleSep){

    $i=0;
    echo "  <div id='report_results'>
            <table>
                <thead>
                    <th style='width:12%;'>". xlt('Facility Name') ."</th>
                    <th style='width:9%;' >". xlt('Facility NPI') ."</th>
                    <th style='width:15%;'>". xlt('Insurance Comp') ."</th>
                    <th style='width:8%;' >". xlt('Policy No') ."</th>
                    <th style='width:16%;'>". xlt('Patient Name') ."</th>
                    <th style='width:7%;' >". xlt('DOB') ."</th>
                    <th style='width:6%;' >". xlt('Gender') ."</th>
                    <th style='width:9%;' >". xlt('SSN') ."</th>
                    <th style='width:2%;' >&nbsp;</th>
                </thead>
                <tbody>
        ";

    while ($row = sqlFetchArray($res)) {


        $i= $i+1;

        if($i%2 == 0){
            $background = '#FFF';
        }else{
            $background = '#FFF';
        }

        $clstr      = array();
        $clstr[0]  = $row['facility_name'];                # Inquiring Provider Name  calendadr
        $clstr[1]  = $row['facility_npi'];                 # Inquiring Provider NPI
        $clstr[2]  = $row['payer_name'];                   # Payer Name  our insurance co name
        $clstr[3]  = $row['policy_number'];                # Subscriber ID
        $clstr[4]  = $row['subscriber_lname'];             # Subscriber Last Name
        $clstr[5]  = $row['subscriber_fname'];             # Subscriber First Name
        $clstr[6]  = $row['subscriber_mname'];             # Subscriber Middle Initial
        $clstr[7]  = $row['subscriber_dob'];               # Subscriber Date of Birth
        $clstr[8]  = substr($row['subscriber_sex'], 0, 1); # Subscriber Sex
        $clstr[9]  = $row['subscriber_ss'];                # Subscriber SSN
        $clstr[10] = translate_relationship($row['subscriber_relationship']);    # Pt Relationship to insured
        $clstr[11] = $row['lname'];                        # Dependent Last Name
        $clstr[12] = $row['fname'];                        # Dependent First Name
        $clstr[13] = $row['mname'];                        # Dependent Middle Initial
        $clstr[14] = $row['dob'];                          # Dependent Date of Birth
        $clstr[15] = substr($row['sex'], 0, 1);            # Dependent Sex
        $clstr[16] = $row['enc_dosDate'];                  # Date of service
        $clstr[17] = "30";                                 # Service Type
        $clstr[18] = $row['pubpid'];                       # Patient Account Number pubpid

        echo "    <tr id='PR".$i."_". attr($row['policy_number'])."'>
                <td class ='detail' style='width:12%;'>". attr($row['facility_name']) ."</td>
                <td class ='detail' style='width:9%;'>".  attr( $row['facility_npi']) ."</td>
                <td class ='detail' style='width:15%;'>". attr( $row['payer_name']) ."</td>
                <td class ='detail' style='width:8%;'>".  attr( $row['policy_number']) ."</td>
                <td class ='detail' style='width:16%;'>". attr( $row['subscriber_lname']." ".$row['subscriber_fname']) ."</td>
                <td class ='detail' style='width:7%;'>".  attr( $row['subscriber_dob']) ."</td>
                <td class ='detail' style='width:6%;'>".  attr( $row['subscriber_sex']) ."</td>
                <td class ='detail' style='width:9%;'>".  attr( $row['subscriber_ss']) ."</td>
                <td class ='detail' style='width:2%;'>
                    <img src='../../images/deleteBtn.png' title=' .xlt('Delete Row') . ' style='cursor:pointer;cursor:hand;' onclick='deletetherow(\"" . $i."_". attr($row['policy_number']) . "\")'>
                </td>
            </tr>
        ";


        unset($clstr);
    }

    if($i==0){

        echo "    <tr>
                <td class='norecord' colspan=9>
                    <div style='padding:5px;font-family:arial;font-size:13px;text-align:center;'>". xlt('No records found') . "</div>
                </td>
            </tr>    ";
    }
        echo "    </tbody>
            </table>";
}

# return array of X12 partners

function getX12Partner() {
    $rez = sqlStatement("select * from x12_partners");
    for($iter=0; $row=sqlFetchArray($rez); $iter++)
        $returnval[$iter]=$row;

    return $returnval;
}

# return array of provider usernames
function getUsernames() {
    $rez = sqlStatement("select distinct username, lname, fname,id from users " .
        "where authorized = 1 and username != ''");
    for($iter=0; $row=sqlFetchArray($rez); $iter++)
        $returnval[$iter]=$row;

    return $returnval;
}

# return formated array

function arrFormated(&$item, $key){
    $item = strstr($item, '_');
    $item = substr($item,1,strlen($item)-1);
    $item = "'".$item;
}
?>
