<?php
  // Copyright (C) 2009-2010 Rod Roark <rod@sunsetsystems.com>
  //
  // This program is free software; you can redistribute it and/or
  // modify it under the terms of the GNU General Public License
  // as published by the Free Software Foundation; either version 2
  // of the License, or (at your option) any later version.
  
  require_once("../globals.php");
  require_once("$srcdir/acl.inc");
  require_once("$srcdir/options.inc.php");
  require_once("$srcdir/patient.inc");
  require_once("$srcdir/erx_javascript.inc.php");
  require_once("$srcdir/headers.inc.php");
  
  // Check authorization.
  if (!acl_check('patients','demo','',array('write','addonly') ))
    die("Adding demographics is not authorized.");
  
  $CPR = 4; // cells per row
  $DateFormat = DateFormatRead();
  $DateLocale = getLocaleCodeForDisplayLanguage($GLOBALS['language_default']);
  
  $searchcolor = empty($GLOBALS['layout_search_color']) ?
    '#ffff55' : $GLOBALS['layout_search_color'];
  
  $WITH_SEARCH = ($GLOBALS['full_new_patient_form'] == '1' || $GLOBALS['full_new_patient_form'] == '2');
  $SHORT_FORM  = ($GLOBALS['full_new_patient_form'] == '2' || $GLOBALS['full_new_patient_form'] == '3');
  
  function getLayoutRes() {
    global $SHORT_FORM;
    return sqlStatement("SELECT * FROM layout_options " .
      "WHERE form_id = 'DEM' AND uor > 0 AND field_id != '' " .
      ($SHORT_FORM ? "AND ( uor > 1 OR edit_options LIKE '%N%' ) " : "") .
      "ORDER BY group_name, seq");
  }
  
  // Determine layout field search treatment from its data type:
  // 1 = text field
  // 2 = select list
  // 0 = not searchable
  //
  function getSearchClass($data_type) {
    switch($data_type) {
      case  1: // single-selection list
      case 10: // local provider list
      case 11: // provider list
      case 12: // pharmacy list
      case 13: // squads
      case 14: // address book list
      case 26: // single-selection list with add
      case 35: // facilities
        return 2;
      case  2: // text field
      case  3: // textarea
      case  4: // date
      case  5: //email
      case  6: //integer
      case  7: //url  
        return 1;
    }
    return 0;
  }
  
  $fres = getLayoutRes();
  ?>
<html>
  <head>
    <?php html_header_show();
      //  Include Bootstrap, Fancybox, date-time-picker
  call_required_libraries(array("jquery-min-3-1-1","bootstrap","datepicker","fancybox-addpatient"));
?>

<style>
body, td, input, select, textarea {
 font-family: Arial, Helvetica, sans-serif;
 font-size: 10pt;
}

body {
 padding: 5pt 5pt 5pt 5pt;
}

div.section {
 /*
 border: solid;
 border-width: 1px;
 border-color: #0000ff;
 */
 margin: 0 0 0 10pt;
 padding: 5pt;
}

</style>

<script type="text/javascript" src="../../library/js/common.js"></script>

<?php include_once("{$GLOBALS['srcdir']}/options.js.php"); ?>

<SCRIPT LANGUAGE="JavaScript"><!--
//Visolve - sync the radio buttons - Start
if((top.window.parent) && (parent.window)){
        var wname = top.window.parent.left_nav;
        fname = (parent.window.name)?parent.window.name:window.name;
        wname.syncRadios();
        wname.setRadio(fname, "new");
}//Visolve - sync the radio buttons - End

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

// This may be changed to true by the AJAX search script.
var force_submit = false;

//code used from http://tech.irt.org/articles/js037/
function replace(string,text,by) {
 // Replaces text with by in string
 var strLength = string.length, txtLength = text.length;
 if ((strLength == 0) || (txtLength == 0)) return string;

 var i = string.indexOf(text);
 if ((!i) && (text != string.substring(0,txtLength))) return string;
 if (i == -1) return string;

 var newstr = string.substring(0,i) + by;

 if (i+txtLength < strLength)
  newstr += replace(string.substring(i+txtLength,strLength),text,by);

 return newstr;
}

<?php for ($i=1;$i<=3;$i++) { ?>
function auto_populate_employer_address<?php echo $i ?>(){
 var f = document.demographics_form;
 if (f.form_i<?php echo $i?>subscriber_relationship.options[f.form_i<?php echo $i?>subscriber_relationship.selectedIndex].value == "self") {
  f.i<?php echo $i?>subscriber_fname.value=f.form_fname.value;
  f.i<?php echo $i?>subscriber_mname.value=f.form_mname.value;
  f.i<?php echo $i?>subscriber_lname.value=f.form_lname.value;
  f.i<?php echo $i?>subscriber_street.value=f.form_street.value;
  f.i<?php echo $i?>subscriber_city.value=f.form_city.value;
  f.form_i<?php echo $i?>subscriber_state.value=f.form_state.value;
  f.i<?php echo $i?>subscriber_postal_code.value=f.form_postal_code.value;
  if (f.form_country_code)
    f.form_i<?php echo $i?>subscriber_country.value=f.form_country_code.value;
  f.i<?php echo $i?>subscriber_phone.value=f.form_phone_home.value;
  f.i<?php echo $i?>subscriber_DOB.value=f.form_DOB.value;
  f.i<?php echo $i?>subscriber_ss.value=f.form_ss.value;
  f.form_i<?php echo $i?>subscriber_sex.value = f.form_sex.value;
  f.i<?php echo $i?>subscriber_employer.value=f.form_em_name.value;
  f.i<?php echo $i?>subscriber_employer_street.value=f.form_em_street.value;
  f.i<?php echo $i?>subscriber_employer_city.value=f.form_em_city.value;
  f.form_i<?php echo $i?>subscriber_employer_state.value=f.form_em_state.value;
  f.i<?php echo $i?>subscriber_employer_postal_code.value=f.form_em_postal_code.value;
  if (f.form_em_country)
    f.form_i<?php echo $i?>subscriber_employer_country.value=f.form_em_country.value;
 }
}

<?php } ?>

function upperFirst(string,text) {
 return replace(string,text,text.charAt(0).toUpperCase() + text.substring(1,text.length));
}

// The ins_search.php window calls this to set the selected insurance.
function set_insurance(ins_id, ins_name) {
 var thesel = document.forms[0]['i' + insurance_index + 'provider'];
 var theopts = thesel.options; // the array of Option objects
 var i = 0;
 for (; i < theopts.length; ++i) {
  if (theopts[i].value == ins_id) {
   theopts[i].selected = true;
   return;
  }
 }
 // no matching option was found so create one, append it to the
 // end of the list, and select it.
 theopts[i] = new Option(ins_name, ins_id, false, true);
}

// Indicates which insurance slot is being updated.
var insurance_index = 0;

// The OnClick handler for searching/adding the insurance company.
function ins_search(ins) {
 insurance_index = ins;
 return false;
}

function checkNum () {
 var re= new RegExp();
 re = /^\d*\.?\d*$/;
 str=document.forms[0].monthly_income.value;
 if(re.exec(str))
 {
 }else{
  alert("Please enter a dollar amount using only numbers and a decimal point.");
 }
}

// This capitalizes the first letter of each word in the passed input
// element.  It also strips out extraneous spaces.
function capitalizeMe(elem) {
 var a = elem.value.split(' ');
 var s = '';
 for(var i = 0; i < a.length; ++i) {
  if (a[i].length > 0) {
   if (s.length > 0) s += ' ';
   s += a[i].charAt(0).toUpperCase() + a[i].substring(1);
  }
 }
 elem.value = s;
}

/*  This function allows only digits to be entered in a text-field,numeric field,etc. */
function allowOnlyDigits(elem_name){
    document.querySelector('input[name='+elem_name+']').addEventListener("keypress", function (evt) {
    if(evt.which == 8){return} // to allow BackSpace
    if (evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
       });
       // no matching option was found so create one, append it to the
       // end of the list, and select it.
       theopts[i] = new Option(ins_name, ins_id, false, true);
      }
      
      // Indicates which insurance slot is being updated.
      var insurance_index = 0;
      
      // The OnClick handler for searching/adding the insurance company.
      function ins_search(ins) {
       insurance_index = ins;
       return false;
      }
      
      function checkNum () {
       var re= new RegExp();
       re = /^\d*\.?\d*$/;
       str=document.forms[0].monthly_income.value;
       if(re.exec(str))
       {
       }else{
        alert("Please enter a dollar amount using only numbers and a decimal point.");
       }
      }
      
      // This capitalizes the first letter of each word in the passed input
      // element.  It also strips out extraneous spaces.
      function capitalizeMe(elem) {
       var a = elem.value.split(' ');
       var s = '';
       for(var i = 0; i < a.length; ++i) {
        if (a[i].length > 0) {
         if (s.length > 0) s += ' ';
         s += a[i].charAt(0).toUpperCase() + a[i].substring(1);
        }
       }
       elem.value = s;
      }
      
      /*  This function allows only digits to be entered in a text-field,numeric field,etc. */
      function allowOnlyDigits(elem_name){
          document.querySelector('input[name='+elem_name+']').addEventListener("keypress", function (evt) {
          if(evt.which == 8){return} // to allow BackSpace
          if (evt.which < 48 || evt.which > 57)
              {
                  evt.preventDefault();
              }
          });
      }
      
      // Onkeyup handler for policy number.  Allows only A-Z and 0-9.
      function policykeyup(e) {
       var v = e.value.toUpperCase();
       var filteredString="";
       for (var i = 0; i < v.length; ++i) {
        var c = v.charAt(i);
        if ((c >= '0' && c <= '9') ||
           (c >= 'A' && c <= 'Z') ||
           (c == '*') ||
           (c == '-') ||     
           (c == '_') ||
           (c == '(') ||
           (c == ')') ||
           (c == '#'))
           {
               filteredString+=c;
           }
       }
       e.value = filteredString;
       return;
      }
      
      function divclick(cb, divid) {
       var divstyle = document.getElementById(divid).style;
       if (cb.checked) {
        divstyle.display = 'block';
       } else {
        divstyle.display = 'none';
       }
       return true;
      }
      
      // Compute the length of a string without leading and trailing spaces.
      function trimlen(s) {
       var i = 0;
       var j = s.length - 1;
       for (; i <= j && s.charAt(i) == ' '; ++i);
       for (; i <= j && s.charAt(j) == ' '; --j);
       if (i > j) return 0;
       return j + 1 - i;
      }
      
      /*Function to check if entered data in the form is of correct format(eg. email,URL..) or not. */
      function checkInputFormat(f){        
          for(i=0;i<f.length;i++){
                           
              if(f[i].type=='email')
              {
                  var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;            
                  if(f[i].value && reg.test(f[i].value)==false)
                  {
                      f[i].style.border =  "thick solid red";                
                      return false;
                  }
                  else
                  {     
                     f[i].style.border =  "";            
                  }
              }     
              
              //By default, this hasn't been used anywhere. Can be used for future purpose.
              //URL's can have following types: http://www.google.com.. or www.google.com or google.com.
              if(f[i].type=='url')
              {
                  var reg = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
                  if(f[i].value && reg.test(f[i].value)==false)
                  {
                      f[i].style.border =  "thick solid red";                
                      return false;
                  }
                  else
                  {               
                     f[i].style.border =  "";
                  }
              }       
          }
      }
      
      function validate(f) {
        var errMsgs = new Array();
        var isInputFormatValid = checkInputFormat(f);
        <?php generate_layout_validation('DEM'); ?>
        <?php if($GLOBALS['erx_enable']){ ?>
        alertMsg='';
        for(i=0;i<f.length;i++){
          if(f[i].type=='text' && f[i].value)
          {
            if(f[i].name == 'form_fname' || f[i].name == 'form_mname' || f[i].name == 'form_lname')
            {
              alertMsg += checkLength(f[i].name,f[i].value,35);
              alertMsg += checkUsername(f[i].name,f[i].value);
            }
            else if(f[i].name == 'form_street' || f[i].name == 'form_city')
            {
              alertMsg += checkLength(f[i].name,f[i].value,35);
              alertMsg += checkAlphaNumericExtended(f[i].name,f[i].value);
            }
            else if(f[i].name == 'form_phone_home')
            {
             alertMsg += checkPhone(f[i].name,f[i].value);
            }
          }
        }
        if(alertMsg)
        {
          alert(alertMsg);
          return false;
        }
        <?php } ?>
        var msg = "";
        msg += "<?php echo htmlspecialchars(xl('The following fields are required'),ENT_QUOTES); ?>:\n\n";
        for ( var i = 0; i < errMsgs.length; i++ ) {
               msg += errMsgs[i] + "\n";
        }
        msg += "\n<?php echo htmlspecialchars(xl('Please fill them in before continuing.'),ENT_QUOTES); ?>";
       
        if ( errMsgs.length > 0 ) {
               alert(msg);
               return false;
        }
        else if(isInputFormatValid == false)
        {
              wrongFormatmsg = "<?php echo htmlspecialchars(xl('Items marked in red have invalid entries.Please enter valid data'),ENT_QUOTES); ?>";
              alert(wrongFormatmsg);
              return false;
        }
       return true;
      }
      
      function toggleSearch(elem) {
       var f = document.forms[0];
      <?php if ($WITH_SEARCH) { ?>
       // Toggle background color.
       if (elem.style.backgroundColor == '')
        elem.style.backgroundColor = '<?php echo $searchcolor; ?>';
       else
        elem.style.backgroundColor = '';
      <?php } ?>
       if (force_submit) {
        force_submit = false;
        f.create.value = '<?php xl('Create New Patient','e'); ?>';
       }
       return true;
      }
      
      // If a <select> list is dropped down, this is its name.
      var open_sel_name = '';
      
      function selClick(elem) {
       if (open_sel_name == elem.name) {
        open_sel_name = '';
       }
       else {
        open_sel_name = elem.name;
        toggleSearch(elem);
       }
       return true;
      }
      
      function selBlur(elem) {
       if (open_sel_name == elem.name) {
        open_sel_name = '';
       }
       return true;
      }
      
      // This invokes the patient search dialog.
      function searchme() {
       var f = document.forms[0];
       var url = '../main/finder/patient_select.php?popup=1';
      
      <?php
        $lres = getLayoutRes();
        
        while ($lrow = sqlFetchArray($lres)) {
          $field_id  = $lrow['field_id'];
          if (strpos($field_id, 'em_') === 0) continue;
          $data_type = $lrow['data_type'];
          $fldname = "form_$field_id";
          switch(getSearchClass($data_type)) {
            case  1:
              echo
              " if (f.$fldname.style.backgroundColor != '' && trimlen(f.$fldname.value) > 0) {\n" .
              "  url += '&$field_id=' + encodeURIComponent(f.$fldname.value);\n" .
              " }\n";
              break;
            case 2:
              echo
              " if (f.$fldname.style.backgroundColor != '' && f.$fldname.selectedIndex > 0) {\n" .
              "  url += '&$field_id=' + encodeURIComponent(f.$fldname.options[f.$fldname.selectedIndex].value);\n" .
              " }\n";
              break;
          }
        }
        ?>
      
       dlgopen(url, '_blank', 700, 500);
      }
      
      //-->
      
    </script>
  </head>
  <body class="body_top">
    <form action='new_comprehensive_save.php' name='demographics_form' method='post' onkeyup="checkInputFormat(f)" onsubmit='return validate(this)' enctype="multipart/form-data">
      <span class='title'><?php xl('Search or Add Patient','e'); ?></span>
      <table cellspacing="4" cellpadding="5" align="right">
      <tr>
      <td colspan="1"><img id="prof_img" style="display: none; border-radius: 40px;">
      <a id="show_upload_button" style="display: none;"><?php echo xlt('Remove Profile Picture'); ?></a>
      </td>
      <td><input type="file" name="profile_picture" id="files" onchange="readURL(this);" class="hidden" />
      <label for="files" class="btn cp-positive" id="file_input_button"><?php echo xlt('Add Profile Picture'); ?></label></b>
      </td>
      </tr>
      </table>
      <table class="table">
        <tr>
          <td align='left'>
            <?php if ($SHORT_FORM) echo "  <center>\n"; ?>
            <?php
              function end_cell() {
                global $item_count, $cell_count;
                if ($item_count > 0) {
                  echo "</td>";
                  $item_count = 0;
                }
              }
              
              function end_row() {
                global $cell_count, $CPR;
                end_cell();
                if ($cell_count > 0) {
                  for (; $cell_count < $CPR; ++$cell_count) echo "<td></td>";
                  echo "</tr>\n";
                  $cell_count = 0;
                }
              }
              
              function end_group() {
                global $last_group, $SHORT_FORM;
                if (strlen($last_group) > 0) {
                  end_row();
                  echo " </table>\n";
                  if (!$SHORT_FORM) echo "</div>\n";
                }
              }
              
              $last_group    = '';
              $cell_count    = 0;
              $item_count    = 0;
              $display_style = 'block';
              /* this is the div width which must be a fixed and absolute VALUE
                 in order to avoid breaklining problems when opening more tabs */
              $div_width     = '1200px'; 
              $group_seq     = 0; // this gives the DIV blocks unique IDs
              
              while ($frow = sqlFetchArray($fres)) {
                $this_group = $frow['group_name'];
                $titlecols  = $frow['titlecols'];
                $datacols   = $frow['datacols'];
                $data_type  = $frow['data_type'];
                $field_id   = $frow['field_id'];
                $list_id    = $frow['list_id'];
                $currvalue  = '';
                $condition_str = get_conditions_str($condition_str,$group_fields);
              
                if (strpos($field_id, 'em_') === 0) {
                  $tmp = substr($field_id, 3);
                  if (isset($result2[$tmp])) $currvalue = $result2[$tmp];
                }
                else {
                  if (isset($result[$field_id])) $currvalue = $result[$field_id];
                }
              
                // Handle a data category (group) change.
                if (strcmp($this_group, $last_group) != 0) {
                  if (!$SHORT_FORM) {
                    end_group();
                    $group_seq++;    // ID for DIV tags
                    $group_name = substr($this_group, 1);
                    if (strlen($last_group) > 0) echo "<br />";
                    echo "<span class='bold'><input type='checkbox' name='form_cb_$group_seq' id='form_cb_$group_seq' value='1' " .
                      "onclick='return divclick(this,\"div_$group_seq\");'";
                    if ($display_style == 'block') echo " checked";
                      
                    // Modified 6-09 by BM - Translate if applicable  
                    echo " /><b>" . xl_layout_label($group_name) . "</b></span>\n";
                      
                    echo "<div id='div_$group_seq' class='section' style='display:$display_style;'>\n";
                    echo " <table class='table' >\n";
                    $display_style = 'none';
                  }
                  else if (strlen($last_group) == 0) {
                    echo " <table class='table'>\n";
                  }
                  $last_group = $this_group;
                }
              
                // Handle starting of a new row.
                if (($titlecols > 0 && $cell_count >= $CPR) || $cell_count == 0) {
                  end_row();
                  echo "  <tr>";
                }
              
                if ($item_count == 0 && $titlecols == 0) $titlecols = 1;
                $field_id_label='label_'.$frow['field_id'];
                // Handle starting of a new label cell.
                if ($titlecols > 0) {
                  end_cell();
                  echo "<td colspan='$titlecols' id='$field_id_label'";
                  echo ($frow['uor'] == 2) ? " class='required'" : " class='bold'";
                  if ($cell_count == 2) echo " style='padding-left:10pt'";
                  echo ">";
                  $cell_count += $titlecols;
                }
                ++$item_count;
              
                echo "<b>";
                  
                // Modified 6-09 by BM - Translate if applicable
                if ($frow['title']) echo (xl_layout_label($frow['title']).":"); else echo "&nbsp;";
                  
                echo "</b>";
              
                // Handle starting of a new data cell.
                if ($datacols > 0) {
                  end_cell();
                  echo "<td colspan='$datacols' class='text'";
                  if ($cell_count > 0) echo " style='padding-left:5pt'";
                  echo ">";
                  $cell_count += $datacols;
                }
              
                ++$item_count;
                generate_form_field($frow, $currvalue);
              }
              end_group();
              ?>
            <?php
              if (! $GLOBALS['simplified_demographics']) {
                $insurancei = getInsuranceProviders();
                $pid = 0;
                $insurance_headings = array(xl("Primary Insurance Provider"), xl("Secondary Insurance Provider"), xl("Tertiary Insurance provider"));
                $insurance_info = array();
                $insurance_info[1] = getInsuranceData($pid,"primary");
                $insurance_info[2] = getInsuranceData($pid,"secondary");
                $insurance_info[3] = getInsuranceData($pid,"tertiary");
                $subscriber_placeholder = "'" . xl("Student/leave blank if unemployed") . "'";
              
                echo "<br /><span class='bold'><input type='checkbox' name='form_cb_ins' value='1' " .
                  "onclick='return divclick(this,\"div_ins\");'";
                if ($display_style == 'block') echo " checked";
                echo " /><b>" . xl('Insurance') . "</b></span>\n";
                echo "<div id='div_ins' class='section' style='display:$display_style; width:$div_width;'>\n";
              
                for($i=1;$i<=3;$i++) {
                 $result3 = $insurance_info[$i];
              ?>
            <table class='table' border="0">
              <tr>
                <td colspan='2'>
                  <span class='required'><?php echo $insurance_headings[$i -1].":"?></span>
                  <select name="i<?php echo $i?>provider">
                    <option value=""><?php xl('Unassigned','e'); ?></option>
                    <?php
                      foreach ($insurancei as $iid => $iname) {
                       echo "<option value='" . $iid . "'";
                       if (strtolower($iid) == strtolower($result3{"provider"}))
                        echo " selected";
                       echo ">" . $iname . "</option>\n";
                      }
                      ?>
                  </select>
                  &nbsp;<a class='iframe medium_modal' href='../practice/ins_search.php' onclick='ins_search(<?php echo $i?>)'>
                  <span> <?php xl('Search/Add Insurer','e'); ?></span></a>
                </td>
              </tr>
              <tr>
                <td >
                  <table class='table' border="0">
                    <tr>
                      <td>
                        <span class='required'><?php xl('Plan Name','e'); ?>: </span>
                      </td>
                      <td>
                        <input type='entry' size='20' name='i<?php echo $i?>plan_name' value="<?php echo $result3{"plan_name"} ?>"
                          onchange="capitalizeMe(this);" />&nbsp;&nbsp;
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class='required'><?php xl('Effective Date','e'); ?>: </span>
                      </td>
                      <td>
                        <input type='entry' size='11' name='i<?php echo $i ?>effective_date' id='i<?php echo $i ?>effective_date' value='<?php echo $result3['date'] ?>'/>
                        <script>
                          $(function() {
                              $("#i<?php echo $i ?>effective_date").datetimepicker({
                                  timepicker: false,
                                  format: "<?= $DateFormat; ?>"
                              });
                          });
                        </script>
                      </td>
                    </tr>
                    <tr>
                      <td><span class=required><?php xl('Policy Number','e'); ?>: </span></td>
                      <td><input type='entry' size='16' name='i<?php echo $i?>policy_number' value="<?php echo $result3{"policy_number"}?>"
                        onkeyup='policykeyup(this)'></td>
                    </tr>
                    <tr>
                      <td><span class=required><?php xl('Group Number','e'); ?>: </span></td>
                      <td><input type=entry size=16 name=i<?php echo $i?>group_number value="<?php echo $result3{"group_number"}?>" onkeyup='policykeyup(this)'></td>
                    </tr>
                    <tr<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
                      <td class='required'><?php xl('Subscriber Employer (SE)','e'); ?>
                      </td>
                      <td><input type=entry size=25 name=i<?php echo $i?>subscriber_employer
                        value="<?php echo $result3{"subscriber_employer"}?>"
                        onchange="capitalizeMe(this);" placeholder=<?php echo $subscriber_placeholder; ?>></td>
                    </tr>
                    <tr<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
                      <td><span class=required><?php xl('SE Address','e'); ?>: </span></td>
                      <td><input type=entry size=25 name=i<?php echo $i?>subscriber_employer_street
                        value="<?php echo $result3{"subscriber_employer_street"}?>"
                        onchange="capitalizeMe(this);" /></td>
                    </tr>
                    <tr<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
                      <td>
                        <span class=required><?php xl('SE City','e'); ?>: </span>
                      </td>
                      <td>
                        <input type=entry size=15 name=i<?php echo $i?>subscriber_employer_city
                          value="<?php echo $result3{"subscriber_employer_city"}?>"
                          onchange="capitalizeMe(this);" />
                      </td>
                    </tr>
                    <tr<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
                      <td>
                        <span class=required><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('SE State','e') : xl('SE Locality','e') ?>: </span>
                      </td>
                      <td>
                        <?php
                          // Modified 7/2009 by BM to incorporate data types
                          generate_form_field(array('data_type'=>$GLOBALS['state_data_type'],'field_id'=>('i'.$i.'subscriber_employer_state'),'list_id'=>$GLOBALS['state_list'],'fld_length'=>'15','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_employer_state']);
                          ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=required><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('SE Zip Code','e') : xl('SE Postal Code','e') ?>: </span>
                      </td>
                      <td>
                        <input type=entry size=10 name=i<?php echo $i?>subscriber_employer_postal_code value="<?php echo $result3{"subscriber_employer_postal_code"}?>">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=required><?php xl('SE Country','e'); ?>: </span>
                      </td>
                      <td>
                        <?php
                          // Modified 7/2009 by BM to incorporate data types
                          generate_form_field(array('data_type'=>$GLOBALS['country_data_type'],'field_id'=>('i'.$i.'subscriber_employer_country'),'list_id'=>$GLOBALS['country_list'],'fld_length'=>'10','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_employer_country']);
                          ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class='required'><?php xl('Accept Assignment','e'); ?>: </span>
                      </td>
                      <td>
                        <select name=i<?php echo $i?>accept_assignment>
                          <option value="TRUE" <?php if (strtoupper($result3{"accept_assignment"}) == "TRUE") echo "selected"?>><?php xl('YES','e'); ?></option>
                          <option value="FALSE" <?php if (strtoupper($result3{"accept_assignment"}) == "FALSE") echo "selected"?>><?php xl('NO','e'); ?></option>
                        </select>
                      </td>
                    </tr>
                  </table>
                </td>
                <td >
                  <table class='table' border="0">
                    <tr>
                      <td>
                        <span class=required><?php xl('Relationship','e'); ?>: </span>
                      </td>
                      <td>
                        <?php
                          // Modified 6/2009 by BM to use list_options and function
                          generate_form_field(array('data_type'=>1,'field_id'=>('i'.$i.'subscriber_relationship'),'list_id'=>'sub_relation','empty_title'=>' '), $result3['subscriber_relationship']);
                          ?>
                        <a href="javascript:popUp('../../interface/patient_file/summary/browse.php?browsenum=<?php echo $i?>')" class=text>(<?php xl('Browse','e'); ?>)</a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=required><?php xl('Subscriber','e'); ?>: </span>
                      </td>
                      <td>
                        <input type=entry size=10 name=i<?php echo $i?>subscriber_fname
                          value="<?php echo $result3{"subscriber_fname"}?>"
                          onchange="capitalizeMe(this);" />
                        <input type=entry size=3 name=i<?php echo $i?>subscriber_mname
                          value="<?php echo $result3{"subscriber_mname"}?>"
                          onchange="capitalizeMe(this);" />
                        <input type=entry size=10 name=i<?php echo $i?>subscriber_lname
                          value="<?php echo $result3{"subscriber_lname"}?>"
                          onchange="capitalizeMe(this);" />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=bold><?php xl('D.O.B.','e'); ?>: </span>
                      </td>
                      <td>
                        <input type='entry' size='11' name='i<?php echo $i?>subscriber_DOB' id='i<?php echo $i?>subscriber_DOB' value='<?php echo $result3['subscriber_DOB'] ?>'/>
                        <script>
                          $(function() {
                            $("#i<?php echo $i?>subscriber_DOB").datetimepicker({
                                timepicker: false,
                                maxDate:0,
                                format: "<?= $DateFormat; ?>"
                            });
                            $.datetimepicker.setLocale('<?= $DateLocale;?>');
                          });
                        </script>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=bold><?php xl('S.S.','e'); ?>: </span>
                      </td>
                      <td>
                        <input type=entry size=11 name=i<?php echo $i?>subscriber_ss value="<?php echo $result3{"subscriber_ss"}?>" />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=bold><?php xl('Sex','e'); ?>: </span>
                      </td>
                      <td>
                        <?php
                          // Modified 6/2009 by BM to use list_options and function
                          generate_form_field(array('data_type'=>1,'field_id'=>('i'.$i.'subscriber_sex'),'list_id'=>'sex'), $result3['subscriber_sex']);
                          ?>   
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=required><?php xl('Subscriber Address','e'); ?>: </span>
                      </td>
                      <td>
                        <input type=entry size=25 name=i<?php echo $i?>subscriber_street
                          value="<?php echo $result3{"subscriber_street"}?>"
                          onchange="capitalizeMe(this);" />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=required><?php xl('City','e'); ?>: </span>
                      </td>
                      <td>
                        <input type=entry size=15 name=i<?php echo $i?>subscriber_city
                          value="<?php echo $result3{"subscriber_city"}?>"
                          onchange="capitalizeMe(this);" />
                      </td>
                    <tr>
                      <td>
                        <span class=required><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('State','e') : xl('Locality','e') ?>: </span>
                      </td>
                      <td>
                        <?php
                          // Modified 7/2009 by BM to incorporate data types
                          generate_form_field(array('data_type'=>$GLOBALS['state_data_type'],'field_id'=>('i'.$i.'subscriber_state'),'list_id'=>$GLOBALS['state_list'],'fld_length'=>'15','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_state']);
                          ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=required><?php echo ($GLOBALS['phone_country_code'] == '1') ? xl('Zip Code','e') : xl('Postal Code','e') ?>: </span>
                      </td>
                      <td>
                        <input type=entry size=10 name=i<?php echo $i?>subscriber_postal_code value="<?php echo $result3{"subscriber_postal_code"}?>">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class='required'<?php if ($GLOBALS['omit_employers']) echo " style='display:none'"; ?>>
                        <?php xl('Country','e'); ?>:
                        </span>
                      </td>
                      <td>
                        <?php
                          // Modified 7/2009 by BM to incorporate data types
                          generate_form_field(array('data_type'=>$GLOBALS['country_data_type'],'field_id'=>('i'.$i.'subscriber_country'),'list_id'=>$GLOBALS['country_list'],'fld_length'=>'10','max_length'=>'63','edit_options'=>'C'), $result3['subscriber_country']);
                          ?>
                      </td>
                    </tr>
                    </tr>
                    <tr>
                      <td>
                        <span class=bold><?php xl('Subscriber Phone','e'); ?>:</span>
                      </td>
                      <td>
                        <input type='text' size='20' name='i<?php echo $i?>subscriber_phone' value='<?php echo $result3["subscriber_phone"] ?>' onkeyup='phonekeyup(this,mypcc)' />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span class=bold><?php xl('CoPay','e'); ?>: </span>
                      </td>
                      <td>
                        <input type=text size="6" name=i<?php echo $i?>copay value="<?php echo $result3{"copay"}?>">
                      </td>
                    </tr>
                
                  </table>
                </td>
              </tr>
            </table>
            <hr />
            <?php
              }
              echo "</div>\n";
              } // end of "if not simplified_demographics"
              ?>
            <?php if (!$SHORT_FORM) echo "  <center>\n"; ?>
            <br />
            <?php if ($WITH_SEARCH) { ?>
            <input type="button" id="search" class='cp-submit' value=<?php xl('Search','e','\'','\''); ?>
              style='background-color:<?php echo $searchcolor; ?>' />
            &nbsp;&nbsp;
            <?php } ?>
            <input type="button" name='create' id="create" class='cp-positive' value=<?php xl('Create New Patient','e','\'','\''); ?> />
            </center>
          </td>
          <td align='right' width='1%' nowrap>
            <!-- Image upload stuff was here but got moved. -->
          </td>
        </tr>
      </table>
    </form>
    <!-- include support for the list-add selectbox feature -->
    <?php include($GLOBALS['fileroot']."/library/options_listadd.inc"); ?>
  </body>
  <script language="JavaScript">
    // fix inconsistently formatted phone numbers from the database
    var f = document.forms[0];
    if (f.form_phone_contact) phonekeyup(f.form_phone_contact,mypcc);
    if (f.form_phone_home   ) phonekeyup(f.form_phone_home   ,mypcc);
    if (f.form_phone_biz    ) phonekeyup(f.form_phone_biz    ,mypcc);
    if (f.form_phone_cell   ) phonekeyup(f.form_phone_cell   ,mypcc);
    
    <?php echo $date_init; ?>
    
    // -=- jQuery makes life easier -=-
    
    // var matches = 0; // number of patients that match the demographic information being entered
    // var override = false; // flag that overrides the duplication warning
    
    $(document).ready(function() {
    enable_modals();
     $(".medium_modal").fancybox( {
                    'overlayOpacity' : 0.0,
                    'showCloseButton' : true,
                    'frameHeight' : 460,
                    'frameWidth' : 650
            });
        // added to integrate insurance stuff
        <?php for ($i=1;$i<=3;$i++) { ?>
        $("#form_i<?php echo $i?>subscriber_relationship").change(function() { auto_populate_employer_address<?php echo $i?>(); });
        <?php } ?>
        
        $('#search').click(function() { searchme(); });
        $('#create').click(function() { submitme(); });
    
        var submitme = function() {
          top.restoreSession();
          var f = document.forms[0];
    
          if (validate(f)) {
            if (force_submit) {
              // In this case dups were shown already and Save should just save.
              f.submit();
              return;
            }
    <?php
      // D in edit_options indicates the field is used in duplication checking.
      // This constructs a list of the names of those fields.
      $mflist = "";
      $mfres = sqlStatement("SELECT * FROM layout_options " .
        "WHERE form_id = 'DEM' AND uor > 0 AND field_id != '' AND " .
        "edit_options LIKE '%D%' " .
        "ORDER BY group_name, seq");
      while ($mfrow = sqlFetchArray($mfres)) {
        $field_id  = $mfrow['field_id'];
        if (strpos($field_id, 'em_') === 0) continue;
        if (!empty($mflist)) $mflist .= ",";
        $mflist .= "'" . htmlentities($field_id) . "'";
      }
      ?>
            // Build and invoke the URL to create the dup-checker dialog.
            var url = 'new_search_popup.php';
            var flds = new Array(<?php echo $mflist; ?>);
            var separator = '?';
            for (var i = 0; i < flds.length; ++i) {
              var fval = $('#form_' + flds[i]).val();
              if (fval && fval != '') {
                url += separator;
                separator = '&';
                url += 'mf_' + flds[i] + '=' + encodeURIComponent(fval);
              }
            }
            dlgopen(url, '_blank', 700, 500);
    
          } // end if validate
        } // end function
    
    // Set onclick/onfocus handlers for toggling background color.
    <?php
      $lres = getLayoutRes();
      while ($lrow = sqlFetchArray($lres)) {
        $field_id  = $lrow['field_id'];
        if (strpos($field_id, 'em_') === 0) continue;
        switch(getSearchClass($lrow['data_type'])) {
          case 1:
            echo "    \$('#form_$field_id').click(function() { toggleSearch(this); });\n";
            break;
          case 2:
            echo "    \$('#form_$field_id').click(function() { selClick(this); });\n";
            echo "    \$('#form_$field_id').blur(function() { selBlur(this); });\n";
            break;
        }
      }
      ?>
    
    }); // end document.ready
    
  </script>
  <script language='JavaScript'>
    // Array of skip conditions for the checkSkipConditions() function.
    var skipArray = [
        <?php echo $condition_str; ?>
    ];
    checkSkipConditions();
    $("input").change(function() {
        checkSkipConditions();
    });
    $("select").change(function() {
        checkSkipConditions();
    });
    $("#form_email").attr('type','email');

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#prof_img')
                    .attr('src', e.target.result)
                    .width(64)
                    .height(64);
                $('#prof_img').css("display", "block"); 
                $('#file_input_button').css("display", "none"); 
                $('#show_upload_button').css("display", "block");  
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    $('#show_upload_button').click(function () {
      $('#prof_img').css("display", "none"); 
      $('#file_input_button').css("display", "block"); 
      $('#show_upload_button').css("display", "none"); 
    });
  </script>
</html>
