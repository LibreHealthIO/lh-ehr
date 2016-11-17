<?php
/**
 * edih_view.php
 * 
 * Copyright 2012 Kevin McCormick Longview, Texas
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 or later.  You should have 
 * received a copy of the GNU General Public License along with this program; 
 * if not, write to the Free Software Foundation, Inc., 
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *  <http://opensource.org/licenses/gpl-license.php>
 * 
 * @author Kevin McCormick
 * @link: http://www.open-emr.org
 * @package LibreEHR
 * @subpackage ediHistory
 */

$sanitize_all_escapes=true; 
$fake_register_globals=false; 
require_once(dirname(__FILE__) . "/../globals.php");
//
if (!acl_check('acct', 'eob')) die(xlt("Access Not Authorized"));
//
//include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php");
//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?php echo xlt("edi history"); ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
    
	<!-- LibreEHR css
	<link rel="stylesheet" href="<?php echo $web_root?>/interface/themes/style_oemr.css" />
	 -->
    <!-- jQuery-ui and datatables -->
    <link rel="stylesheet" href="<?php echo $web_root?>/library/js/jquery-ui-1.10.4.custom/css/custom-theme/jquery-ui-1.10.4.custom.min.css" />
<!--
    <link rel="stylesheet" href="<?php echo $web_root?>/library/js/DataTables-1.10.11/DataTables-1.10.11/css/jquery.dataTables_themeroller.css" />
 -->
	<link rel="stylesheet" href="<?php echo $web_root?>/library/js/DataTables-1.10.11/DataTables-1.10.11/css/jquery.dataTables.min.css" />
<!--  -->
	<link rel="stylesheet" href="<?php echo $web_root?>library/js/DataTables-1.10.11/DataTables-1.10.11/css/dataTables.jqueryui.min.css" />

<!--
	<link rel="stylesheet" href="<?php echo $web_root?>library/js/DataTables-1.10.11/css/dataTables.min.css" />
-->
	<link rel="stylesheet" href="<?php echo $web_root?>/library/js/DataTables-1.10.11/Scroller-1.4.1/css/scroller.jqueryui.min.css" />
<!-- -->
    <!-- edi_history css -->
    <link rel="stylesheet" href="<?php echo $web_root?>/library/css/edi_history_v2.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $web_root?>/library/dynarch_calendar.css" type="text/css" />

	<!-- LibreEHR Calendar -->

    <script type="text/javascript" src="<?php echo $web_root?>/library/dynarch_calendar.js"></script>
    <script type="text/javascript" src="<?php echo $web_root?>/library/dynarch_calendar_setup.js"></script>
    <script type="text/javascript" src="<?php echo $web_root?>/library/textformat.js"></script>
    
    <?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
</head>
<!-- style for LibreEHR color -->
<body style='background-color:#fefdcf'>

<!-- Begin tabs section  class="Clear"-->
<div id="tabs" style="visibility:hidden">
  <ul>
   <li><a href="#newfiles" id="btn-newfiles"><?php echo xlt("New Files"); ?></a></li>
   <li><a href="#csvdatatables" id="btn-csvdatatables"><?php echo xlt("CSV Tables"); ?></a></li>
   <li><a href="#x12text" id="btn-x12text"><?php echo xlt("EDI File"); ?></a></li>
   <li><a href="#edinotes" id="btn-edinotes"><?php echo xlt("Notes"); ?></a></li>
   <li><a href="#archive" id="btn-archive"><?php echo xlt("Archive"); ?></a></li>
  </ul> 	


    <div id="newfiles">
        <table> 
        <tr vertical-align="middle">
         <td align="center">       
            <form id="formupl" name="form_upl" action="edih_main.php" method="POST" enctype="multipart/form-data">
                <fieldset>
                <legend><?php echo xlt("Select one or more files to upload"); ?></legend> 
                <input type="file" id="uplmulti" name="fileUplMulti[]" multiple />
                <input type="hidden" name="NewFiles" form="formupl" value="ProcessNew" />
                <input type="submit" id="uplsubmit" name="upl_submit" form="formupl" value=<?php echo xla("Submit"); ?> />
                <input type="reset" id="uplreset" name="upl_reset" form="formupl" value=<?php echo xla("Reset"); ?> />
                </fieldset>
            </form>
         </td>
         <td align="center">
            <form id="processnew" name="process_new" action="edih_main.php" method="GET">
                <fieldset>
                <legend><?php echo xlt("Process new files for CSV records"); ?>:</legend>
                <input type="checkbox" id="processhtml" name="process_html" form="processnew"  value="htm" checked /> <?php echo xlt("HTML Output?"); ?> 
                <input type="checkbox" id="processerr" name="process_err" form="processnew"  value="err" checked /> <?php echo xlt("Show Errors Only?"); ?> &nbsp;&nbsp;<br>
                <input type="hidden" name="ProcessFiles" form="processnew" value="ProcessNew" />
                <label for="process"><?php echo xlt("Process New Files"); ?></label>
                <input type="submit" id="fuplprocess" name="process" form="processnew" value=<?php echo xla("Process"); ?> />
                </fieldset>
            </form>
         </td>
        </tr>
        </table>
    
		<div id="fileupl1"></div>
		<div id="fileupl2"></div>
		<div id="processed"></div>
        <div id="rsp" title="<?php echo xla("Response"); ?>"></div>
        <div id="sub" title="<?php echo xla("Submitted"); ?>"></div>
        <div id="seg" title="<?php echo xla("x12 Segments"); ?>"></div>

    </div> 
    
    <div id="csvdatatables">
		<table>
		<tr>
		<td colspan=4>
		
		<form id="formcsvtables" name="form_csvtables" action="edih_main.php" method="GET">
			<fieldset>
				<legend><?php echo xlt("View CSV tables"); ?>:</legend>
				<table>
					<tr>
						<td colspan=4><?php echo xlt("Choose a period or dates (YYYY-MM-DD)"); ?></td>
					</tr>
					<tr>
						<td align='center'><?php echo xlt("Choose CSV table"); ?>:</td>
						<td align='center'><?php echo xlt("From Period"); ?></td>
						<td align='center'><?php echo xlt("Start Date"); ?>: &nbsp;&nbsp <?php echo xlt("End Date"); ?>:</td>
						<td align='center'><?php echo xlt("Submit"); ?></td>
					</tr>
					<tr height='1.5em'>
						<td align='center'>					
							<select id="csvselect" name="csvtables"></select>
						</td>						
							
						<td align='center'>
							<select id="csvperiod" name="csv_period">
								<option value='2w' selected='selected'>2 weeks</option>
								<option value='1m'>1 month</option>
								<option value='2m'>2 months</option>
								<option value='3m'>3 months</option>
								<option value='6m'>6 months</option>
								<option value='9m'>9 months</option>
								<option value='1y'>1 year</option>
								<option value='ALL'>All Dates</option>
							</select>
						</td>
                        <!-- datekeyup(e, defcc, withtime)  dateblur(e, defcc, withtime) -->
                        <td align='left'>
						   <input type='text' size='10' name="csv_date_start" id="caldte1" value="" title="yyyy-mm-dd Start Date" />
                           <img src="<?php echo $web_root?>/interface/pic/show_calendar.gif" align='absbottom' width='24' height='22'
                              id="csvdate1_cal" border="0" alt="[?]" style="cursor:pointer;cursor:hand" title="Start date">
                        
                           <input type="text" size="10" name="csv_date_end" id="caldte2" value="" title="yyyy-mm-dd End Date" />
                           <img src="../pic/show_calendar.gif" align="absbottom" width="24" height="22"
                              id="csvdate2_cal" border="0" alt="[?]" style="cursor:pointer;cursor:hand" title="End date">
                        </td>
                        <!-- LibreEHR calendar srcipt -->
                        
                        <script type="text/javascript"> 
                            Calendar.setup({inputField:"caldte1", ifFormat:"%Y-%m-%d", button:"csvdate1_cal"});
                            Calendar.setup({inputField:"caldte2", ifFormat:"%Y-%m-%d", button:"csvdate2_cal"});
                        </script>
						<td align='center'>
							<input type="hidden" name="csvShowTable" form="formcsvtables" value="gettable">
							<input id="csvshow" type="submit" name="csv_show" form="formcsvtables" value="<?php echo xla("Submit"); ?>" />
						</td>
                        
					</tr>
                </table>
           </fieldset>
        </form> 
        
        </td>
        <td colspan=2>
	        <form id="formcsvhist" name="hist_csv" action="edih_main.php" method="get">
	           <fieldset>
			  <legend><?php echo xlt("Per Encounter"); ?></legend>
			  <table cols='2'> 
				        <tr><td colspan='2'><?php echo xlt("Enter Encounter Number"); ?></td></tr>
			        <tr>
							<td><?php echo xlt("Encounter"); ?></td>
							<td><?php echo xlt("Submit"); ?></td>	
					</tr>
					<tr>
							<td><input id="histenctr" type="text" size=10 name="hist_enctr" value="" /></td>
							<td><input id="histsbmt" type="submit" name="hist_sbmt" form="formcsvhist" value="<?php echo xla("Submit"); ?>" /></td>
					</tr>
			  </table>
			</fieldset>
		</form>
		        
		</td>
		</tr> 
		</table>
		
        <div id='tblshow'></div>
        <div id='tbcsvhist'></div> 
        <div id='tbrpt'></div>
		<div id='tbrsp'></div>
        <div id='tbsub'></div> 
        <div id='tbseg'></div>
     
    </div>
 <!--     erafiles to be replaced by functionality in x12text
    
    <div id='erafiles'>

    </div>
 -->
 
	<div id="x12text" > 
		<form id="x12view" name="x12_view" action="edih_main.php" enctype="multipart/form-data" method="post">
		<fieldset>
		<legend><?php echo xlt("View EDI x12 file"); ?>:</legend>
		<table>
			<tr>
			  <td align='left'><label for="x12htm"><?php echo xlt("Report?"); ?></label></td>
			  <td align='center'><label for="x12file"><?php echo xlt("Choose File"); ?>:</label></td>
			  <td align='left'><label for="x12_filebtn"><?php echo xlt("Submit"); ?>:</label></td>
			  <td align='center'><label for="x12_filereset"><?php echo xlt("Reset"); ?>:</label></td>
			</tr>
		<tr>
			  <td align='left'>
				<input type="hidden" name="viewx12Files" value="view_x12">
			    <input type="checkbox" id="x12htm" name="x12_html" value="html"  />
			  </td>
			  <td align='left'><input id="x12file" type="file" size=30 name="fileUplx12" /></td>
			  <td align='center'>
				  <input type="submit" id="x12filebtn" name="x12_filebtn" form="x12view" value="<?php echo xla("Submit"); ?>" />
		</td>
			  <td align='center'>
				  <input type="button" id="x12filerst" name="x12_filereset" form="x12view" value="<?php echo xla("Reset"); ?>" />
		</td>
		</tr>
		</table>  
		</fieldset>
		</form>
		
		<div id="x12rsp"></div> 
    
    </div>
    
    <div id="edinotes">
		<table>
			<tr>
				<td colspan=2><a href="<?php echo $web_root?>/Documentation/Readme_edihistory.html" target="_blank"><?php echo xlt("View the README file"); ?></a></td>
			</tr>
			<tr>
				<td>
					<form id ="formlog" name="form_log" action="edih_main.php" enctype="multipart/form-data" method="post">
					<fieldset><legend><?php echo xlt("Inspect the log"); ?></legend>
					<label for="logfile"><?php echo xlt("View Log"); ?></label>			
					<select id="logselect" name="log_select"> </select>	
					<input type="hidden" name="logshowfile" value="getlog">
					<input id="logshow" type="submit" form="formlog" value="<?php echo xla("Submit"); ?>" />								
					<input id="logclose" type="button" form="formlog" value="<?php echo xla("Close"); ?>" />
					<input id="logarch" type="button" form="formlog" value="<?php echo xla("Archive"); ?>" />
					</fieldset>
				</form>
			  </td>
				<td><form id ="formnotes" name="form_notes" action="edih_main.php" enctype="multipart/form-data" method="post">
					<fieldset><legend><?php echo xlt("Notes"); ?></legend>
					<label for="notesget"><?php echo xlt("Notes"); ?></label>
					<input id="notesget" type="button" name="notes_get" form="formnotes" value="<?php echo xla("Open"); ?>" />
					<input id="noteshidden" type="hidden" name="notes_hidden" value="putnotes" />
					<input id="notessave" type="submit" name="notes_save" form="formnotes" value="<?php echo xla("Save"); ?>" />
					<input id="notesclose" type="button" name="notes_close" form="formnotes" value="<?php echo xla("Close"); ?>" />
				</fieldset>
				</form>
			  </td>
			</tr> 
		</table>
        
		<div id='logrsp'></div> 
		<div id='notesrsp'></div>
    
	</div> 
        
    <div id="archive">
		<table>
			<tr>
				<td colspan=3><?php echo xlt("Selected files and data will be removed from folders and tables"); ?></td>
			</tr>
			<tr>
				<td colspan=2>
					<form id="formarchive" name="form_archive" action="edih_main.php" enctype="multipart/form-data" method="POST">
					<fieldset><legend><?php echo xlt("Archive old files "); ?></legend>
					<label for="archive_sel"><?php echo xlt("Older than"); ?>:</label>
					<select id="archiveselect" name="archive_sel">
						<option value="" selected="selected">Choose</option>
						<option value="24m">24 months</option>
						<option value="18m">18 months</option>
						<option value="12m">12 months</option>
						<option value="9m">9 months</option>
						<option value="6m">6 months</option>
						<option value="3m">3 months</option>
					</select>
					<label for="archivereport"><?php echo xlt("Report"); ?>:</label>					
					<input type="button" id="archiverpt" name="archivereport" form="formarchive" value="<?php echo xla("Report"); ?>" />
					<input type="hidden" name="ArchiveRequest" form="formarchive" value="requested" />
					<label for="archivesbmt"><?php echo xlt("Archive"); ?>:</label>
					<input type="submit" id="archivesbmt" name="archive_sbmt" form="formarchive" value="<?php echo xla("Archive"); ?>" />
					</fieldset>
					</form>
				</td>
				<td><form id="formarchrestore" name="form_archrestore" action="edih_main.php" enctype="multipart/form-data" method="POST">
					<fieldset><legend><?php echo xlt("Restore Archive"); ?></legend>
					<label for="archrestore_sel"><?php echo xlt("Restore"); ?>:</label>
					<select id="archrestoresel" name="archrestore_sel"> </select>
					<input type="hidden" name="ArchiveRestore" form="formarchrestore" value="restore" />
					<label for="arch_restore"><?php echo xlt("Restore"); ?>:</label>					
					<input type="submit" id="archrestore" name="arch_restore" form="formarchrestore" value=<?php echo xla("Restore"); ?> />
					</fieldset>
					</form>
				</td>
			</tr>
		</table>
        
		<div id="archiversp"></div>

    </div>
   
</div> 
<!-- End tabs section -->
<!--  -->
<script src="<?php echo $web_root?>/library/js/jquery-ui-1.10.4.custom/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo $web_root?>/library/js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="<?php echo $web_root?>/library/js/DataTables-1.10.11/datatables.min.js"></script>
<script src="<?php echo $web_root?>/library/js/DataTables-1.10.11/DataTables-1.10.11/datatables.jqueryui.min.js"></script>

<!-- -->
<script src="<?php echo $web_root?>library/js/DataTables-1.10.11/Scroller-1.4.1/js/dataTables.scroller.min.js"></script>
<!-- 
<script
	  src="https://code.jquery.com/jquery-1.10.2.min.js"
	  integrity="sha256-C6CB9UYIS9UJeqinPHWTHVqh/E1uhG5Twh+Y5qFQmYg="
	  crossorigin="anonymous">
</script>
--> 
<!-- end DataTables js Begin local js -->

<script type="text/javascript">
    jQuery(document).ready(function() {
        // activate tab interface
        jQuery("#tabs").tabs();
        jQuery("#tabs").tabs().css('visibility','visible');
        // set some button disabled
        jQuery('#processfiles').prop('disabled', true);
        jQuery('#archivesubmit').prop('disabled', true);
        // update list of available csv tables
		jQuery(function() { csvlist() });
		// update list of available log files
		jQuery(function() { loglist() });
		// update list of archive files
		jQuery(function() { archlist() });
        // hide these div elements until used
        jQuery("#fileupl1").toggle(false);
		jQuery("#fileupl2").toggle(false);
                    });
/* ************ 
 *   end of document ready() jquery 
 * ************
         */
/* ****  from http://scratch99.com/web-development/javascript/convert-bytes-to-mb-kb/ *** */
	function bytesToSize(bytes) {
	    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	    if (bytes == 0) return 'n/a';
	    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	    if (i == 0) return bytes + ' ' + sizes[i];
	    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
	};
/* *** variables for upload maximums *** */
/* *** phpserver: 'maxfsize''maxfuploads''postmaxsize''tmpdir'	phpserver['postmaxsize'] *** */
	var phpserver = [];
	jQuery(function() {
		jQuery.ajax({
			url: 'edih_main.php', 
                data: { srvinfo: 'yes' }, 
                dataType: 'json',
			success: function(rsp){ phpserver = rsp }
            }); 
        });
/* *** update the list of available csv tables  *** */
	function csvlist() {
		jQuery.ajax({
			type: 'GET',
			url: 'edih_main.php',
                data: { csvtbllist: 'yes' },
                dataType: 'json',
                success: function(data) {
			  var options = jQuery('#csvselect').attr('options');
			  var optct = jQuery.isPlainObject(data);  // data.length
			  if (optct) {
				var options = [];
				options.push("<option value='' selected='selected'><?php echo xla("Choose from list"); ?></option>");
				jQuery.each(data.claims, function(idx, value) {
					options.push("<option value=" + value.fname + ">" + value.desc + "</option>");
				});
				jQuery.each(data.files, function(idx, value) {
					options.push("<option value=" + value.fname + ">" + value.desc + "</option>");
				});
				jQuery("#csvselect").html(options.join(''));
			  }
			}
		});
	};	
/* *** update the list of log files *** */
	function loglist() {
		jQuery.ajax({
			type: 'GET',
			url: 'edih_main.php',
			data: { loglist: 'yes' },
			dataType: 'json',
			success: function(data) {	
			  var options = jQuery('#logselect').attr('options');
                  var optct = data.length;
                  if (optct) {
                    var options = [];
				options.push('<option selected="selected"><?php echo xla("Choose from list"); ?></option>');
                    for (var i=0; i<optct; i++) {
				  options.push('<option value=' + data[i] + '>' + data[i] + '</option>');
                    }
				jQuery("#logselect").html(options.join(''));
                  }
                }
            });
	};
/* *** update the list of archive files *** id="archrestoresel name="archrestore_sel" */
	function archlist() {
		jQuery.ajax({
			type: 'GET',
			url: 'edih_main.php',
			data: { archlist: 'yes' },
			dataType: 'json',
			success: function(data) {
			  var options = jQuery('#archrestoresel').attr('options');
			  var optct = data.length;
			  if (optct) {
				var options = [];
				options.push("<option selected='selected'><?php echo xla("Choose from list"); ?></option>");
				for (var i=0; i<optct; i++) {
				  options.push("<option value=" + data[i] + ">" + data[i] + "</option>");
				}
				jQuery('#archrestoresel').html(options.join(""));
			  }
			}
     }); 
	};
/* ************ 
 * called to bind links in ajax retrieved content for dialog display
 * from links in csv data tables
 * .on( events [, selector] [, data], handler(eventObject) )
 * bindlinks('#tblshow', 'click', '.seg', 'click', '#tbseg', '<?php echo xla("Code Text"); ?>')	
 */        
/* ****** dialog cruft
buttons: [{ text: "Close", click: function(){
								jQuery(this).html("");
								jQuery(this).dialog("close");
								}
							}],
 
// add this div for minimized dialog
<div id="dialog_window_minimized_container"></div>
// **** script *****
var _init = jQuery.ui.dialog.prototype._init;
$.ui.dialog.prototype._init = function() {
    //Run the original initialization code
    _init.apply(this, arguments);
     
    //set some variables for use later
    var dialog_element = this;
    var dialog_id = this.uiDialogTitlebar.next().attr('id');
     
    //append our minimize icon
    this.uiDialogTitlebar.append('<a href="#" id="' + dialog_id + 
    '-minbutton" class="ui-dialog-titlebar-minimize ui-corner-all">'+
    '<span class="ui-icon ui-icon-minusthick"></span></a>');
     
    //append our minimized state
    $('#dialog_window_minimized_container').append(
        '<div class="dialog_window_minimized ui-widget ui-state-default ui-corner-all" id="' + 
        dialog_id + '_minimized">' + this.uiDialogTitlebar.find('.ui-dialog-title').text() + 
        '<span class="ui-icon ui-icon-newwin"></div>');
     
    //create a hover event for the minimize button so that it looks good
    $('#' + dialog_id + '-minbutton').hover(function() {
        $(this).addClass('ui-state-hover');
    }, function() {
        $(this).removeClass('ui-state-hover');
    }).click(function() {
        //add a click event as well to do our "minimalization" of the window
        dialog_element.close();
        $('#' + dialog_id + '_minimized').show();
    });
     
    //create another click event that maximizes our minimized window
    $('#' + dialog_id + '_minimized').click(function() {
        $(this).hide();
        dialog_element.open();
    });

    // z-index of dialog
    $( ".selector" ).on( "dialogfocus", function( event, ui ) {} );
    $( ".selector" ).dialog( "moveToTop" );
    
'a.rsp .sub .seg'
    $(function(){
    //$('a').on('click', function(e){
    jQuery('a.rsp .sub .seg').on('click', function(e){
        e.preventDefault();
        $('<div/>', {'class':'myDlgClass', 'id':'link-'+($(this).index()+1)})
        .load($(this).attr('href')).appendTo('body').dialog();
        });
    });         

};
jQuery('div#tblshow > td').hover(
function(e){ jQuery(this).parentsUntil('tr').parent().addClass('outlinetr'); },
function(e){ jQuery(this).parentsUntil('tr').parent().removeClass('outlinetr'); }
);
jQuery('div#tblshow > tr').hover(
function(e){ jQuery(this).addClass('outlinetr'); },
function(e){ jQuery(this).removeClass('outlinetr'); }
);
*/
/** alternative to bindlinks function ?  
    control visibility by designating to which div the dialog is appended
    dialogs appended to div#tabs will be on top (appendTo as option also) appendTo: "#tabs", 
**** */
    function dialogOptions(appendElem) {
		var tblDialogOpts = {
			appendTo: appendElem,
			draggable: true,
			resizable: true,
			height: 328,
			width: 512,
			maxWidth: 768,
			title: 'Transaction Detail',
			close: function(event, ui)
	        {
	            jQuery(this).dialog("close");
	            jQuery(this).remove();
	        }
	    };
	    return tblDialogOpts;
        }            
 
			
	jQuery('#tbcsvhist').on('click', 'a', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var options = dialogOptions('#tbcsvhist');
		jQuery('<div/>', {'class':'edihDlg', 'id':'link-'+(jQuery(this).index()+1)})
	        .load(jQuery(this).attr('href')).appendTo('#tbcsvhist').dialog(options);
	});
/* #csvTable  ****	*/
	jQuery('#tblshow').on('click', 'a', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var ttl = jQuery(this).attr('title');
		var options = dialogOptions('#tblshow');
		jQuery('<div/>', {'class':'edihDlg', 'id':'link-'+(jQuery(this).index()+1)})
	        .load(jQuery(this).attr('href')).appendTo('#tblshow').dialog(options);
    });      
/* 		
	jQuery('#tbrpt').on('click', 'a', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var options = dialogOptions('#tblshow');
		jQuery('<div/>', {'class':'edihDlg', 'id':'link-'+(jQuery(this).index()+1)})
	        .load(jQuery(this).attr('href')).appendTo('#tblshow').dialog(options);
	});
*/
/* **** links in dialog in uploads - processed div  ****	*/
	jQuery('#processed').on('click', 'a', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var options = dialogOptions('#processed');
		jQuery('<div/>', {'class':'edihDlg', 'id':'link-'+(jQuery(this).index()+1)})
	        .load(jQuery(this).attr('href')).appendTo('#processed').dialog(options);
	});

/*
// **** script ****
// ***** not used due to multiple event attachment -- see added jQuery(dElem).off(dEvt, cClass); 
// .on( events [, selector ] [, data ], handler )
// bind 'a.cClass' child of dElem to event dEvt
// bindlinks('#processed', 'click', '.rsp', 'click', '#rsp', '<?php echo xla("Response"); ?>'),
// bindlinks('#tblshow', 'click', '.rsp', 'click', '#tbrsp', '<?php echo xla("Response"); ?>'),
*/
    function bindlinks(dElem, dEvt, cClass, cEvt, cElem, mytitle){
		 jQuery(dElem).off(dEvt, cClass); 
         jQuery(dElem).on(dEvt, cClass, cEvt, function(e) {
            e.preventDefault();
            e.stopPropagation();
            //
            var statDialog = jQuery(cElem).dialog({
				appendTo: dElem, //"#tabs",
                autoOpen: false,
				resizable: true,
                draggable: true,
                modal: false,
                title: mytitle, //jQuery(this).attr('title'),
                height: 360,
                width: 600,
                close: function(event, ui) {
		            //$(this).dialog("close");
		            //$(this).remove();
		            console.log(this);
		            $(this).empty();
		        }           
    });

            //
            console.log(this);
            jQuery.get(jQuery(this).attr('href'), function(data){ jQuery(cElem).html(data); });
            //
            if (statDialog.dialog('isOpen')) { statDialog.dialog('close'); };
            statDialog.dialog('open');
    });

    }

/* ******************* end document ready ***********/
/* ****
 * jQuery-UI accordian -- for 27x file html (not used -- have not figured out how to invoke)
 */
    function apply_accordion() {
	    jQuery( "#accordion" )
	      .accordion({
	        header: "> div > h3",
	        collapsible: true,
	        heightStyle: "fill"
	      })
	      .sortable({
	        axis: "y",
	        handle: "h3",
	        stop: function( event, ui ) {
	          // IE doesn't register the blur when sorting
	          // so trigger focusout handlers to remove .ui-state-focus
	          ui.item.children( "h3" ).triggerHandler( "focusout" );
	 
	          // Refresh accordion to handle new order
	          $( this ).accordion( "refresh" );
	        }
    });	

	  };
/* ****************************
 *
 * === upload multiple files
 *     buttons are enabled/disabled
 *     selected and uploaded files are listed    
 *     the process files script html output displayed,
 */
/* **** if files have been uploaded **** */
	var upld_ct = 0;
/* ***** list files selected in the multifile upload input **** */
	jQuery('#uplmulti').change( function(){
		// clear uploaded files list, since new selected files list is coming
		jQuery('#fileupl2').html('');
		jQuery('#fileupl2').removeClass('flist');
		jQuery('#processed').html('');
		var uplfiles = this.files; //event.target.files;
		var fct = uplfiles.length;
		var fsize = 0;
		var fl1 = jQuery('#fileupl1');
		fl1.html('');
		fl1.toggle(true);
		fl1.addClass('flist1');
		var fmaxupl = phpserver['maxfuploads'];   // jQuery("#srvvals").data('mf');
		var pmaxsize = phpserver['postmaxsize']
		var str = "<p><em>Selected Files:</em></p>";
		str = str + "<ul id='uplsel' class='fupl'>";
		for(var i = 0; i < fct; i++) {
			if (i == fmaxupl) str = str + '</ul><p>max file count reached<br> - reload names below </p><ul class=fupl>';
			str = str + "<li>" + uplfiles[i].name + "</li>";  //' ' +
			fsize += uplfiles[i].size;
		};
		str = str + '</ul><p>Total size: ' + bytesToSize(fsize) + ' (max ' + pmaxsize + ')</p>';
		jQuery('#uplsubmit').prop('disabled', false);
		if (upld_ct === 0 ) {
			jQuery('#processupl').prop('disabled', true);
            }
		fl1.html(str);
        });
	// uplreset button click the file input is reset and associated values cleared
	jQuery('#uplreset').on('click', function( event ) {
		//console.log(event.type);
		//console.log(jQuery('#ufile').value);
		event.preventDefault();
		event.stopPropagation();
		jQuery('#fileupl1').html('');
		jQuery('#fileupl2').html('');
		jQuery('#fileupl1').hide();
		jQuery('#fileupl2').hide();
		jQuery('#processed').html('');
		jQuery('#uplsubmit').prop('disabled', true);
		if (upld_ct == 0 ) {
			jQuery('#fuplprocess').prop('disabled', true);
		} else {
			jQuery('#fuplprocess').prop('disabled', false);
		}
		// jQuery('#fupl').reset();
		document.getElementById('formupl').reset();
		return false;
    }); 

/* ***** uplsubmit button click --upload files are scanned and copied into folders  *** */
/* ***** files are listed next to file selected list by css  *** */
	jQuery('#formupl').on('submit', function( event )  {
		event.stopPropagation();
		event.preventDefault();
		var uplForm = document.getElementById("formupl"); 
		var upldata = new FormData( document.getElementById('formupl') );  
		var rspElem = jQuery('#fileupl2');
		rspElem.html('');
		jQuery.ajax({
			    url: jQuery('#formupl').attr('action'),  
			    type: 'POST',
			    cache: false, 
			    data: upldata,
			    dataType: 'html',
			    processData: false,
			    contentType: false,
			    success: function(data) {
					rspElem.html(data);
					rspElem.show();
					jQuery('#fuplprocess').prop('disabled', false );
					jQuery('#fuplupload').prop('disabled', true);
					uplForm.reset();
					upld_ct++;
				},
			    error: function( xhr, status ) { alert( "Sorry, there was a problem!" ); },
        });
		return false;
    }); 

/* **** process button, files parsed and csv rows displayed  *** */
	jQuery('#processnew').on('submit', function(e) {
		e.stopPropagation();
		e.preventDefault();
		//var prcForm = document.getElementById("process_new"); 
		jQuery.ajax({
			    url: jQuery('#processnew').attr('action'), 
            type:'GET',
			    data: jQuery('#processnew').serialize(),  //prcForm.serialize(),
			    success: [
				    function(data) {
						jQuery('#fileupl1').html('');
						jQuery('#fileupl1').hide();
						jQuery('#fileupl2').html('');
						jQuery('#fileupl2').hide();
						//
						jQuery('#processed').html(data);
						jQuery('#processed').show();
					}
				],
			    error: function( xhr, status ) {
					alert( "Sorry, there was a problem!" ),
					jQuery('#procdessed').html(status)
            }
        });
		upld_ct = 0;
		/* ***  update list of csv tables *** */
		csvlist();
		jQuery('#fuplprocess').prop('disabled', true );
		return false;
    });	

/* *********************************************
 *
 *  ==== file upload lists  match uploaded to selected
 *       when mouse is over element in one list, matching element
 *       in other list is highlighted also
 */
	function outlineMatch(matchElem, matchText) {
		if (matchText == 'none') {
			matchElem.css('font-weight', 'normal');
			return false;
		} else {
			matchElem.each(function( index ) {
				if ( matchText == jQuery(this).text() ) {
					jQuery(this).siblings().css('font-weight', 'normal');
					jQuery(this).css('font-weight', 'bolder');
					return false;
				};
    });

		}
	   return false;
	}

/* *** do not use .hover event   */
	jQuery('#fileupl2').on('mouseenter', 'li', function(event){
		var fl1 = jQuery('#fileupl1').find('li');
		var fname = jQuery(this).text();
		jQuery(this).css('font-weight', 'bolder');
		jQuery(this).siblings().css('font-weight', 'normal');
		outlineMatch(fl1, fname);
    });

	jQuery('#fileupl2').on('mouseleave', 'li', function(){
		var fl1 = jQuery('#fileupl1').find('li');
		jQuery(this).css('font-weight', 'normal');
		outlineMatch(fl1, 'none');
            });
	jQuery('#fileupl1').on('mouseenter', 'li', function(event){	
		jQuery(this).css('font-weight', 'bolder');
		if ( jQuery('#fileupl2').length ) {
			var fl2 = jQuery('#fileupl2').find('li');
			var fname = jQuery(this).text();
			outlineMatch(fl2, fname);
		}
        });
	jQuery('#fileupl1').on('mouseleave', 'li', function(){
		jQuery(this).css('font-weight', 'normal');
		if ( jQuery('#fileupl2').length ) {
			var fl2 = jQuery('#fileupl2').find('li');
			var fname = jQuery(this).text();
			outlineMatch(fl2, 'none');
    } 
    
	});

/* *****  ==== end file upload lists  match uploaded to selected
/* ****************************
 * ===  end upload multiple files section
 */

/* ****************
 * begin csv tables section
 * the csv tables are displayed using jquery dataTables plugin
 * here, the 'success' action is to execute an array of functions
 * the helper function bindlinks() applies jquery .on method
 * so most links will open a jquery-ui dialog
 */
	jQuery('#formcsvtables').on('submit', function(e) {
		e.preventDefault();
		e.stopPropagation();
		// verify a csv file is selected
		if (jQuery('#csvselect').val() == '') {
			jQuery("#tblshow").html('<?php echo xla("No table selected! Select a table."); ?>');
			return false;
		}
		jQuery.ajax({
			type:'get',
			url: "edih_main.php", 
			data: jQuery('#formcsvtables').serialize(), 
			dataType: "html",
			success: [ 
				function(data){ 
					jQuery('#tblshow').html(data);
					jQuery('#tblshow').css('maxWidth', 'fit-contents'); 
					jQuery('#tblshow table#csvTable').DataTable({
						//DisplayLength: 10,    
						//bJQueryUI: true, 
						//bScrollInfinite: true,
						//bScrollCollapse: true,
                        //iScrollLoadGap: 20,
                        //sScrollY:"200px",
                        //sScrollX: true
                        //bPaginate: true,
                        //scroll: true,
                        //'jQuery UI': true,
                        'processing': true,
						'scrollY': '300px',
						'scrollCollapse': true,
						'scrollX': true,
						//sScrollXInner: '100%'
						'paging': true
					});
                    //jQuery("#csvTable_filter").before(tbltl);
				},
			]              
		});
	}); 
	
	// csv encounter history
	jQuery('#formcsvhist').on('submit', function(e) {
		e.preventDefault();
		jQuery('#tbcsvhist').empty();
		var chenctr = jQuery('#histenctr').value;
		var histopts = { modal: false,
					appendTo: '#tbcsvhist',
					height: 'auto',
					width: 568,
					maxWidth: 616,
					title: "<?php echo xla("Encounter EDI Record"); ?>",
					close: function(event, ui) {
						jQuery(this).empty();
			            jQuery(this).dialog('close');
			        }
				};
		jQuery.ajax({
			type: "GET",
			url: jQuery('#formcsvhist').attr('action'), 
			data: jQuery('#formcsvhist').serialize(), //{ csvenctr: chenctr },
			dataType: "html",
			success: [ function(data){
				jQuery('<div/>', {'class':'edihDlg', 'id':'link-'+(jQuery(this).index()+1)})
					.appendTo('#tbcsvhist').html(jQuery.trim(data)).dialog(histopts).dialog('open');
				}
			]				
		});
    });	
    //		
    jQuery('#csvClear').on('click', function(e) {
		e.preventDefault();
        jQuery("#tblshow").html('');
    });
/* **************
 * === end of csv tables and claim history
 */
/* ****************8
 * === view x12 file form  form"view_x12" file"x12file" submit"fx12" check"ifhtml" newWin"x12nwin"
 */
	jQuery('#x12view').on('submit', function(e) {
		e.preventDefault();
		e.stopPropagation();
		//
		var rspElem = jQuery('#x12rsp');
		var frmData = new FormData( document.getElementById('x12view') );
		jQuery.ajax({
		    url: jQuery('#x12view').attr('action'),
		    type: 'POST',
		    data: frmData,
		    processData: false,
		    contentType: false,
		    //
		    success: function(data) {
				rspElem.html('');
				rspElem.html(data);
				jQuery('#x12filesbmt').prop('disabled', true);
			},
		    error: function( xhr, status ) { alert( "Sorry, there was a problem!" ); }
		});
		return false;
	});
	//
	jQuery('#x12file').change( function(){
		// clear file display
		jQuery('#x12rsp').html('');
		jQuery('#x12filesbmt').prop('disabled', false);
	});
	//
	jQuery('#x12filerst').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();
		// clear file display
		jQuery('#x12rsp').html('');
		jQuery('#x12filesbmt').prop('disabled', true);
		jQuery('#x12view').trigger('reset');
	});

/*
 * === functions for logs, notes, and archive "frm_archive" "archiveselect""archivesubmit"
 */
	jQuery('#logarch').on('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		//
		jQuery.ajax({
            type: 'get',
            url: jQuery('#formlog').attr('action'), 
            data: { archivelog: 'yes' },
            dataType: "json",
            success: function(data) {
				var str = "<p>Archive Log Files</p><ul id='logarchlist'>";
				var fct = data.length;
				if (fct == 0) {
					str = str + "<li>No logs older than 7 days</li>";
				} else {
					for(var i = 0; i < fct; i++) {
						str = str + "<li>" + data[i] + "</li>";
					}
				};
				str = str + "</ul>";
				jQuery('#notesrsp').hide();
		        jQuery('#logrsp').html('');
		        jQuery('#logrsp').html(str);
		        jQuery('#logrsp').show();				
			},
		    error: function( xhr, status ) { alert( "Sorry, there was a problem!" ); }
		});
		loglist();

    });
    
    jQuery('#logclose').on('click', function(e) {
		e.preventDefault();
        jQuery('#logrsp').html('');
        jQuery('#logrsp').hide();
        jQuery('#notesrsp').show();
    });
    	
	jQuery('#logselect').on('change', function(e) {
		jQuery('#logshow').prop('disabled', false );
	});
	     
    jQuery('#logshow').on('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var fn = jQuery('#logselect').val();
        jQuery.ajax({
            type: 'get',
            url: jQuery('#formlog').attr('action'), 
            //data: { archivelog: 'yes', logfile: fn },
            data: jQuery('#formlog').serialize(),
            dataType: "html",
            success: function(data){
				jQuery('#notesrsp').hide();
                jQuery('#logrsp').html(''), 
                jQuery('#logrsp').html(jQuery.trim(data));
                jQuery('#logrsp').show(); 
            }
        });
    }); 

    jQuery('#notesget').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        jQuery.ajax({
            type:'GET',
            url: jQuery('#formnotes').attr('action'),
            data: { getnotes: "yes"},
            dataType: "text",
            success: function(data){
				jQuery('#notesrsp').html('');
                jQuery('#notesrsp').html("<H4>Notes:</H4>");
                jQuery('#notesrsp').append("<textarea id='txtnotes', name='txtnotes',form='formnotes',rows='10',cols='600',wrap='hard' autofocus='autofocus'></textarea>"); 
                // necessary to trim the data since php from script has leading newlines (UTF-8 issue) '|:|'
		        jQuery('#logrsp').hide();
                jQuery('#notesrsp \\:textarea').val(jQuery.trim(data));
                jQuery('#notesrsp').show();
            }
        });
    });	

    jQuery('#notessave').on('click', function(e) {
		e.preventDefault();
        var notetxt = jQuery('#notesrsp :textarea').val();
        var noteURL = jQuery('#formnotes').attr('action');
        jQuery.post(noteURL, { putnotes: 'yes', tnotes: notetxt },
            function(data){ jQuery('#notesrsp').append(data); });
    });

    jQuery('#notesclose').on('click', function(e) {
		e.preventDefault();
        jQuery('#notesrsp').html('');
        jQuery('#notesrsp').toggle(false);
    });

/*
 * ==== Archive form id="formarchive"
 *   note use of 'one' instead of 'on' so archive command is run only once
 */
	// The .one() method is especially useful ...
	jQuery('#formarchive').one('submit', function(e) {
		//e.stopPropagation();
		e.preventDefault();
		var archForm = document.getElementById('formarchive'); 
		var archdata = new FormData(archForm);  
		var rspElem = jQuery('#archiversp');
		rspElem.html('');
		jQuery.ajax({
			url: jQuery('#formarchive').attr('action'),
			type: 'POST',
			cache: false, 
			data: archdata,
			dataType: 'html',
			processData: false,
			contentType: false,
			success: function(data) {
				rspElem.html(data);
				jQuery('#archivesubmit').prop('disabled', true );
				archForm.reset();
				
			},
			error: function( xhr, status ) { alert( "Sorry, there was a problem!" ); },
			// code to run regardless of success or failure
			// complete: function( xhr, status ) { alert( "The request is complete!" ); }
		});
		$( this ).click(function() {
			console.log( "You have clicked this before!" );
			alert("Archive already performed!");
	    });
	    archlist();
	    csvlist();
		return false;
	});
	//
	jQuery('#archiverpt').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		// id="#archiversp"
		var rspElem = jQuery('#archiversp');
		rspElem.html('');
		var sprd = jQuery('#archiveselect').val();
		var surl = jQuery('#formarchive').attr('action');
		//
		//console.log(surl);
		jQuery.ajax({
			url: 'edih_main.php',
			type: 'GET',
			//cache: false,
			dataType: 'html',
			data: { archivereport: 'yes', period: sprd },
			
			success: function(data) {
				//rspElem.html(data);
				//rspElem.show();
				jQuery('#archiversp').html(data);
			},
			error: function( xhr, status ) {
				alert( "Sorry, there was a problem!" );
				rspElem.html(status);
				rspElem.show();
			}
		});
		return false;
	});
	//		
	jQuery('#archiveselect').on('change', function(e) {
		jQuery('#archivesubmit').prop('disabled', false );
	});

	// 
	jQuery('#formarchrestore').on('submit', function(e) {
		//e.stopPropagation();
		e.preventDefault();
		var archrstForm = document.getElementById('formarchrestore'); 
		var archrstdata = new FormData(archrstForm);  
		var rspElem = jQuery('#archiversp');
		//var archf = jQuery('#archrestoresel').val();
		//archrstdata = { archrestore: 'yes', archfile: archf };
		jQuery.ajax({
			url: jQuery('#formarchrestore').attr('action'),
			type: 'POST', 
			data: archrstdata,
			dataType: 'html',
			processData: false,
			contentType: false,
			success: function(data) {
				rspElem.html('');
				rspElem.html(data);
			},
			error: function( xhr, status ) { alert( "Sorry, there was a problem!" ); },
			// code to run regardless of success or failure
			// complete: function( xhr, status ) { alert( "The request is complete!" ); }
		});
		$( this ).click(function() {
			console.log( "You have clicked this before!" );
			alert("Restore already performed!");
	    });
	    archlist();
	    csvlist();
	    archrstForm.reset();
		return false;
	});

/* ************ 
 * end of javascript block
 */             
</script>     

</body>

</html>
