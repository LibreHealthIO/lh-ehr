<?php
/*
 *  facilities.php for the adding of the facility information
 *
 * Copyright (C) 2016-2017
 *
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-license.php.
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package LibreEHR
 *
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../globals.php");
require_once("../../library/acl.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/classes/POSRef.class.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/erx_javascript.inc.php");

$alertmsg = '';
?>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
        <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
        <script src="<?php echo $GLOBALS['standard_js_path']; ?>anchorposition/AnchorPosition.js"></script>
        <script src="<?php echo $GLOBALS['standard_js_path']; ?>popupwindow/PopupWindow.js"></script>

        <script type="text/javascript">
            // TODO - move this to a common library
            function submitform() {
                <?php if($GLOBALS['erx_enable']){ ?>
                alertMsg='';
                f=document.forms[0];
                for (i=0;i<f.length;i++){
                    if (f[i].type=='text' && f[i].value) {
                        if (f[i].name == 'facility' || f[i].name == 'Washington') {
                            alertMsg += checkLength(f[i].name,f[i].value,35);
                            alertMsg += checkFacilityName(f[i].name,f[i].value);
                        }
                        else if (f[i].name == 'street') {
                            alertMsg += checkLength(f[i].name,f[i].value,35);
                            alertMsg += checkAlphaNumeric(f[i].name,f[i].value);
                        }
                        else if (f[i].name == 'phone' || f[i].name == 'fax') {
                            alertMsg += checkPhone(f[i].name,f[i].value);
                        }
                        else if (f[i].name == 'federal_ein') {
                            alertMsg += checkLength(f[i].name,f[i].value,10);
                            alertMsg += checkFederalEin(f[i].name,f[i].value);
                        }
                    }
                }
                if (alertMsg) {
                    alert(alertMsg);
                    return false;
                }
                <?php } ?>
                if (document.forms[0].facility.value.length>0 && document.forms[0].ncolor.value != '') {
                    top.restoreSession();
                    document.forms[0].submit();
                } else {
                    if (document.forms[0].facility.value.length<=0) {
                        document.forms[0].facility.style.backgroundColor="red";
                        document.forms[0].facility.focus();
                    }
                    else if (document.forms[0].ncolor.value == '') {
                        document.forms[0].ncolor.style.backgroundColor="red";
                        document.forms[0].ncolor.focus();
                    }
                }
            }

            function toggle( target, div ) {

                $mode = $(target).find(".indicator").text();
                if ( $mode == "collapse" ) {
                    $(target).find(".indicator").text( "expand" );
                    $(div).hide();
                } else {
                    $(target).find(".indicator").text( "collapse" );
                    $(div).show();
                }

            }

            $(document).ready(function(){

                $("#dem_view").click( function() {
                    toggle( $(this), "#DEM" );
                });

                tabbify();
            });

            $(document).ready(function(){
                $("#cancel").click(function() {
                    parent.$('#addFacilities-iframe').iziModal('close');
                });
            });

            function displayAlert() {
                if (document.getElementById('primary_business_entity').checked==false)
                    alert("<?php echo addslashes(xl('Primary Business Entity tax id is used as account id for NewCrop ePrescription. Changing the facility will affect the working in NewCrop.'));?>");
                else if (document.getElementById('primary_business_entity').checked==true)
                    alert("<?php echo addslashes(xl('Once the Primary Business Facility is set, it should not be changed. Changing the facility will affect the working in NewCrop ePrescription.'));?>");
            }

            function changeColor(id) {
                // Gets the value from the color input
                newColor = document.getElementById(id).value;
                // Set the display input to the new color
                document.getElementById('ncolor').value = newColor;
            }
        </script>
        <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    </head>
    <body class="body_top">
        <table>
            <tr>
                <td colspan=5 align=center style="padding-left:2px;">
                    <a onclick="submitform();" class="css_button large_button" name='form_save' id='form_save' href='#'>
                        <span class='css_button_span large_button_span'><?php echo xlt('Save');?></span>
                    </a>
                    <a class="css_button large_button" id='cancel' href='#' >
                        <span class='css_button_span large_button_span'><?php echo xlt('Cancel');?></span>
                    </a>
                </td>
            </tr>
        </table>

        <br>

        <form name='facility' method='post' action="facilities.php" target='_parent'>
            <input type=hidden name=mode value="facility">
            <table border=0 cellpadding=0 cellspacing=0>
                <tr>
                    <td width='150px'>
                        <span class="text"><?php echo xlt('Name'); ?>: </span>
                    </td>
                    <td width='220px'>
                        <input type=entry name=facility size=20 value=""><span class="mandatory">&nbsp;*</span>
                    </td>
                    <td width=20>&nbsp;</td>
                    <td width='150px'>
                        <span class='text'><?php echo xlt('Legal Entity'); ?>: </span>
                    </td>
                    <td width='220px'>
                        <input type='entry' name='alias' size='20' value="">
                        <span class="mandatory">&nbsp;*</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo xlt('Address'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry size=20 name=street value="">
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <span class="text"><?php echo xlt('Phone'); ?>: </span></td><td><input type=entry name=phone size=20 value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo xlt('City'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry size=20 name=city value="">
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <span class="text"><?php echo xlt('Fax'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry name=fax size=20 value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo xlt('State'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry size=20 name=state value="">
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <span class="text"><?php echo xlt('Tax ID'); ?>: </span>
                    </td>
                    <td>
                        <select name=tax_id_type>
                            <option value="EI"><?php echo xlt('EIN'); ?></option>
                            <option value="SY"><?php echo xlt('SSN'); ?></option>
                        </select>
                        <input type=entry size=11 name=federal_ein value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo xlt('Zip Code'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry size=20 name=postal_code value="">
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <span class="text"><?php if($GLOBALS['simplified_demographics']) {  echo xlt('Facility Code'); } else { echo xlt('Facility NPI'); }; ?>:</span>
                    </td>
                    <td>
                        <input type=entry size=20 name=facility_npi value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class=text><?php echo xlt('Country'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry size=20 name=country_code value="<?php echo htmlspecialchars($facility{"country_code"}, ENT_QUOTES) ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo xlt('Website'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry size=20 name=website value="">
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <span class="text"><?php echo xlt('Email'); ?>: </span>
                    </td>
                    <td>
                        <input type=entry size=20 name=email value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class='text'><?php echo xlt('Billing Location'); ?>: </span>
                    </td>
                    <td>
                        <input type='checkbox' name='billing_location' value = '1'>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <span class='text'>
                            <?php echo xlt('Accepts Assignment'); ?>
                            <br/>
                            (<?php echo xlt('only if billing location'); ?>):
                        </span>
                    </td>
                    <td>
                        <input type='checkbox' name='accepts_assignment' value = '1'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class='text'><?php echo xlt('Service Location'); ?>: </span>
                    </td>
                    <td>
                        <input type='checkbox' name='service_location' value = '1'>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo htmlspecialchars(xl('Color'),ENT_QUOTES); ?>:</span>
                        <span class="mandatory">&nbsp;*</span>
                    </td>
                    <td>
                        <span class="text">Pick:&nbsp;<input type="color" id="colorPicker" onChange="changeColor('colorPicker')"></span>
                        <span><input type=entry name=ncolor id=ncolor size=10 value="#000000"></span>
                    </td>
                </tr>
                <?php
                    $disabled='';
                    $resPBE=sqlStatement("select * from facility where primary_business_entity='1' and id!='".$my_fid."'");
                    if(sqlNumRows($resPBE)>0)
                        $disabled='disabled';
                ?>
                <tr>
                    <td>
                        <span class='text'><?php echo xlt('Primary Business Entity'); ?>: </span>
                    </td>
                    <td>
                        <input
                            type='checkbox'
                            name='primary_business_entity'
                            id='primary_business_entity'
                            value='1'
                            <?php if ($facility['primary_business_entity'] == 1) echo 'checked'; ?>
                            <?php if($GLOBALS['erx_enable']){ ?> onchange='return displayAlert()' <?php } ?>
                            <?php echo $disabled;?>
                        />
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <span class=text><?php echo xlt('POS Code'); ?>: </span>
                    </td>
                    <td colspan="6">
                        <select name="pos_code">
                            <?php
                                $pc = new POSRef();

                                foreach ($pc->get_pos_ref() as $pos) {
                                    echo "<option value=\"" . $pos["code"] . "\" ";
                                    echo ">" . $pos['code']  . ": ". $pos['title'];
                                    echo "</option>\n";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo xlt('Billing Attn'); ?>:</span>
                    </td>
                    <td colspan="4">
                        <input type="text" name="attn" size="45">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text"><?php echo xlt('CLIA Number'); ?>:</span>
                    </td>
                    <td colspan="4">
                        <input type="text" name="domain_identifier" size="45">
                    </td>
                </tr>
                <tr height="25" style="valign:bottom;">
                    <td>
                        <font class="mandatory">*</font>
                        <span class="text"> <?php echo xlt('Required'); ?></span>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </form>

        <script language="JavaScript">
            <?php
                if ($alertmsg = trim($alertmsg)) {
                    echo "alert('$alertmsg');\n";
                }
            ?>
        </script>
    </body>
</html>
