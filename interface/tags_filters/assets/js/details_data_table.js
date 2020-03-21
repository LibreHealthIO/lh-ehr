var details_data_table = function( options, onShowDetails, onHideDetails, onAfterDraw ) {
	
	var tableId = options.tableId;
	var resultsUrl = options.resultsUrl; // Url to fetch results
	var columnHeadersJSON = options.columnHeadersJSON; // JSON array of header titles
	var iDisplayLength = options.iDisplayLength; // integer items per page
    var setPatientUrl = options.setPatientUrl;
    var baseUrl = options.baseUrl;
	
	// pagination strings
	var sFirst = options.sFirst;
    var sPrevious = options.sPrevious;
    var sNext = options.sNext;
    var sLast = options.sLast;
    var lastOpenRow = null;
    
    var oTable;

    var getDatatable = function()
    {
        return oTable;
    }
    	
    var fn_work = function() 
    {
    	 // Initializing the DataTable.
    	 //
    	 oTable = $('#'+tableId).dataTable( {
    	  "bProcessing": true,
             "fnDrawCallback" : function(){
                 if ( typeof onAfterDraw === 'function' ) {
                     onAfterDraw();
                 }
             },
    	  // next 2 lines invoke server side processing
    	  "bServerSide": true,
    	  "sAjaxSource": resultsUrl,
          "aaSorting": [[0,'desc']],
    	  // sDom invokes ColReorderWithResize and allows inclusion of a
			// custom div
    	  //"sDom"       : 'Rlfrt<"mytopdiv">ip',
    	  // These column names come over as $_GET['sColumns'], a
			// comma-separated list of the names.
    	  // See: http://datatables.net/usage/columns and
    	  // http://datatables.net/release-datatables/extras/ColReorder/server_side.html
    	  "aoColumns": columnHeadersJSON,
         aLengthMenu: [
             [25, 50, 100, 200, -1],
             [25, 50, 100, 200, "All"]
         ],
    	  "iDisplayLength": 100,
    	  // language strings are included so we can translate them
    	  "oLanguage": {
    	   "sSearch"      : "Search all columns",
    	   "sLengthMenu"  : "Show _MENU_ entries",
    	   "sZeroRecords" : "No matching records found",
    	   "sInfo"        : "Showing _START_ to _END_ of _TOTAL_ entries",
    	   "sInfoEmpty"   : "Nothing to show",
    	   "sInfoFiltered": "(filtered from _MAX_ total entries)",
    	   "oPaginate": {
    	    "sFirst"   : sFirst,
    	    "sPrevious": sPrevious,
    	    "sNext"    : sNext,
    	    "sLast"    : sLast
    	   }
    	  }
    	 });


        // When we click on the patient name, set the active patient
        $('#'+tableId+' tbody').on( 'click', 'a.active-encounter', function( e ) {

            e.preventDefault();
            e.stopPropagation();

            var pid = $(this).attr('data-pid');
            var encounter = $(this).attr('data-encounter');
            var encDate = $(this).attr( 'data-encounter-date' );

            $.post(setPatientUrl, {pid: pid, encounter: encounter}, function (response) {
                    // When call returns set the UI patient in the top navigation using code similar to line 397
                    // of /interface/patient_file/demographics.php
                    parent.left_nav.setPatient(response['patientname'], response['pid'], response['pubpid'], response['frname'], response['str_dob'], response['encounter']);
                    parent.left_nav.setPatientEncounter(response['encounterIdArray'], response['encounterDateArray'], response['calendarCategoryArray']);
                    parent.left_nav.setEncounter(encDate, encounter, 'RBot');
                },
            'json');
        });

        // When double-clicked, open demographics popup
        $('#'+tableId+' tbody').on( 'dblclick', 'a.active-encounter', function( e ) {

            e.preventDefault();
            e.stopPropagation();

            var pid = $(this).attr('data-pid');
            var encounter = $(this).attr('data-encounter');
            var encDate = $(this).attr( 'data-encounter-date' );

            $.post(setPatientUrl, {pid: pid, encounter: encounter}, function (response) {
                    // When call returns set the UI patient in the top navigation using code similar to line 397
                    // of /interface/patient_file/demographics.php
                    parent.left_nav.setPatient(response['patientname'], response['pid'], response['pubpid'], response['frname'], response['str_dob'], response['encounter']);
                    parent.left_nav.setPatientEncounter(response['encounterIdArray'], response['encounterDateArray'], response['calendarCategoryArray']);
                    parent.left_nav.setEncounter(encDate, encounter, 'RBot');

                    if ( log_display_window != null &&
                        log_display_window.open ) {
                        log_display_window.close();
                    }
                    log_display_window = window.open( baseUrl+"/interface/patient_file/summary/demographics.php?set_pid=" + pid + "&set_encounter=1&encounter="+encounter+"&encounter-date="+encDate,
                        'log_display_window',
                        'height=800,width=800,menubar=no,status=no'
                    );

                },
            'json');
        });

        $('#'+tableId+' tbody').on( 'click', 'tr.encrow', function( e ) {

            e.preventDefault();
            e.stopPropagation();

            var id = $(this).attr('id');
            var pid = $(this).attr('data-pid');
            var encounter = $(this).attr('data-encounter');
            if ( encounter ) {
                parent.left_nav.loadFrame('ens0','RBot', "patient_file/encounter/encounter_top.php?set_encounter="+encounter );
            }
            setRadio('RBot', 'ens');
        });
    	 
     	// Array to track the ids of the details displayed rows
         var detailRows = [];
         
      
         $('#'+tableId+' tbody').on( 'click', 'a.column_behavior_details', function( e ) {
        	
             e.preventDefault();
      		 e.stopPropagation();

             var pid = $(this).attr( 'data-pid' );             
             var encounter = $(this).attr('data-encounter');

             $.post( setPatientUrl, { pid: pid, encounter: encounter }, function(response) {
                 // When call returns set the UI patient in the top navigation using code similar to line 397
                 // of /interface/patient_file/demographics.php
                parent.left_nav.setPatient( response['patientname'], response['pid'], response['pubpid'], response['frname'], response['str_dob'], response['encounter']);
                parent.left_nav.setPatientEncounter(response['encounterIdArray'], response['encounterDateArray'], response['calendarCategoryArray']);

             }, 
             'json');
             
            if (lastOpenRow != null && tr != lastOpenRow){
                oTable.fnClose(lastOpenRow);
            }

            var tr = $(this).closest('tr').get(0);

            if ( $(this).text() == 'Hide' ) {

                if ( typeof onHideDetails === 'function' ) {
                    onHideDetails();
                }

                /* This row is already open - close it */
                oTable.fnClose( tr );
                lastOpenRow = null;
                $(this).text( "Show" );

            } else {

                if ( typeof onShowDetails === 'function' ) {
                    onShowDetails();
                }

                /* Open this row */
                $(this).text( "Hide" );

                var requestUrl = $(this).attr( "href" );
                $.get( requestUrl, function( data ) {
                    oTable.fnOpen( tr, data, 'details' );
                    $(lastOpenRow).find('a.column_behavior_details').text('Show');
                    lastOpenRow = tr;
                });
            }
         } );
    }

    var fn_wire_events = function() 
    {

    }

    return {
        init: function() {
            $( document ).ready( function() {
                fn_wire_events();
                fn_work();
            });
        },
        getDatatable: function() {
            return getDatatable();
        }
    };
}
