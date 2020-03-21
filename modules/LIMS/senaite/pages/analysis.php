<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'analysis';
}

switch($sub) {

  case 'categories':
    $categories = getDataFromUrl($client, 'analysiscategory');
    

  break;

  case 'createcategories':
    $labDepartments = getDataFromUrl($client, 'department');
    $errors = [];
    $required = ['title', 'department'];
    if (isset($_POST['submit'])) {
      if (!checkRequiredFields($required, $_POST)) {
        $errors[] = 'Please fill in the required fields';
      }
      if (count($errors) === 0) {
        try {
          $client->post('analysiscategory', [
            'json' => [
              'title' => $_POST['title'],
              'Title' => $_POST['title'],
              'description' => valueOrNull($_POST['description']),
              'Department' => $_POST['department'],
              'SortKey' => valueOrNull($_POST['sortKey'])
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=analysis&sact=categories');
      }
    }
    
  break;


  case 'services':
    $analysisServices = getDataFromUrl($client, 'analysisservice?inactive_state=active');
    
  break;

  case 'createservices':
    $errors = [];
    $identifierTypes = getDataFromUrl($client, 'identifiertype');
    $analysisCategories = getDataFromUrl($client, 'analysiscategory');
    $labDepartments = getDataFromUrl($client, 'department');
    if (isset($_POST['submit'])) {
      $required = ['title', 'category',  'identifierType', 'identifier', 'analysisKeyword', 'pointOfCapture', 'exponentialFormatPrecision'];
      if (!(checkRequiredFields($required, $_POST))) {
        
        $errors[] = 'Please fill in all the required fields';
      }

      if (count($errors) === 0) {

        /**
         * Have to do this part in two steps until some issues with senaite's API are resolved
         * https://github.com/senaite/senaite.jsonapi/issues/28
         */
        try {

         $serviceCreated = $client->post('analysisservice' , [
            'form_params' => [
              'title' => $_POST['title'],
              'Title' => $_POST['title'],
              'Category' => $_POST['category'],
              'description' => valueOrNull($_POST['description']),
              'SortKey' => valueOrNull($_POST['sortKey']),
              'ShortTitle' => valueOrNull($_POST['shortTitle']),
              'CommercialID' => valueOrNull($_POST['commercialID']),
              'ProtocolID' => valueOrNull($_POST['protocolID']),
              'Unit' => valueOrNull($_POST['unit']),
              'Keyword' => $_POST['analysisKeyword'],
              'PointOfCapture' => $_POST['pointOfCapture'],
              'Price' => valueOrNull($_POST['price']),
              'BulkPrice' => valueOrNull($_POST['bulkPrice']),
              'VAT' => valueOrNull($_POST['VAT']),
              'Department' => valueOrNull($_POST['department']),
              'Remarks' => valueOrNull($_POST['remarks']),
              'ExponentialFormatPrecision' => $_POST['exponentialFormatPrecision'],
            ],
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        $serviceCreatedUid = json_decode($serviceCreated->getBody()->getContents())->items[0]->uid;
        
        // update the entry with the identifiers
        try {
          $client->post('update/'.$serviceCreatedUid, [
            'json' => [
              'Identifiers' => [ 
                'Identifier' => $_POST['identifier'],
                'IdentifierType' => $_POST['identifierType'],
                'Description' => valueOrNull($_POST['identifierDescription']),
                'value' => ''
              ],
            ]
          ]);
        } catch (Exception $e) {
          $exceptionMessage = $e->getMessage();
          if (strpos($exceptionMessage, 'LowerDetectionLimit') !== false || strpos($exceptionMessage, 'UpperDetectionLimit') !== false) {
            header('location: index.php?action=analysis&sact=services');
          } else {
            die($e->getMessage());
            $client->get('delete/'.$serviceCreatedUid);
            header('location: index.php?action=analysis&sact=services');
          }
        }
         
      }

    }

  break;


  case 'profiles':

    $analysisProfiles = getDataFromUrl($client, 'analysisprofile');
  
  break;

  case 'createprofiles':
    $analysesServices = getDataFromUrl($client, 'analysisservice?inactive_state=active');
    $errors = [];
    $required = ['title', 'analyses'];
    
    if (isset($_POST['submit'])) {
      if (!checkRequiredFields($required, $_POST)) {
        $errors[] = 'Please fill in the required fields';
      }

      if (count($errors) === 0) {
        $selectedServices = null;
        if (is_array($_POST['analyses'])) {
          $selectedServices = $_POST['analyses'];
        } else {
          $selectedServices = [ $_POST['analyses'] ];
        }
        
        try {
          var_dump($selectedServices);
          $client->post('analysisprofile', [
            'json' => [
              'title' => $_POST['title'],
              'Title' => $_POST['title'],
              'description' => valueOrNull($_POST['description']),
              'Service' => $selectedServices,
              'ProfileKey' => valueOrNull($_POST['profileKeyword']),
              'CommercialID' => valueOrNull($_POST['commercialID']),
            ],
            'form_params' => [
              'title' => $_POST['title']
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=analysis&sact=categories');
      }
    }
  break;

  case 'createrequests':

    $ajax = [ 'clientContacts' ];
    $clients = getDataFromUrl($client, 'client');

    // unable to send AJAX requests to the senaite API due to CORS( cross-rogin resource sharing) being disabled (can't send authentication
    // cookie to the API).
    // workaround - load the contacts for each client here and use javascript to generate the select from already existing data

    $contacts = [];
    foreach ($clients as $contactClient) {
      $contactDetails = getDataFromUrl($client, 'search?getParentUID='.$contactClient->uid);
      if (!empty($contactDetails)) {
        $contacts[$contactClient->uid][] = $contactDetails;
      }
    }

    $samples = getDataFromUrl($client, 'sample');
    $analysisProfiles = getDataFromUrl($client, 'analysisprofile');
    $sampleTypes = getDataFromUrl($client, 'sampletype');
    $storageLocations = getDataFromUrl($client, 'storagelocation');
    $samplePoints = getDataFromUrl($client, 'samplepoint');
    $containers = getDataFromUrl($client, 'container');
    $analysisCategories = getDataFromUrl($client, 'analysiscategory');
    $analyses = getDataFromUrl($client, 'analysisservice');
    $errors = [];

    if (isset($_POST['submit'])) {
      $required = [ 'client' , 'contact' , 'sampleDate', 'sampleType', 'analyses' ];

      if (!(checkRequiredFields($required, $_POST))) {
        $errors[] = 'Please fill in all the required fields';
      }
      if (!isset($_POST['analysisProfies'])) {
        $_POST['analysisProfiles'] = '';
      }
      if (!isset($_POST['samplePoint'])) {
        $_POST['samplePoint'] = '';
      }
      if (count($errors) === 0) { 
        $clientInformation = getDataFromUrl($client, 'client/'.$_POST['client'])[0];
        $sampleTypeInformation = getDataFromUrl($client, 'sampletype/'.$_POST['sampleType'])[0];
        $contactInformation = getDataFromUrl($client, 'contact/'.$_POST['contact'])[0];

        try {
          $response = $client->post('analysisrequest', [
            'json' => [
              'parent_path' => $clientInformation->path,
              'Contact' => $contactInformation->path,
              'Profiles' => valueOrNull($_POST['analysisProfiles']),
              'DateSampled' => $_POST['sampleDate'],
              'SampleType' => "New SampleType",
              'SamplePoint' => valueOrNull($_POST['samplePoint']),
              'Priority' => valueOrNull($_POST['priority']),
              'EnvironmentalConditions' => valueOrNull($_POST['environmentalConditions']),
              'AdHoc' => valueOrNull($_POST['adhoc']),
              'Analyses' => $_POST['analyses'],
            ],
          ]);
          header('location: index.php?action=analysis&sact=requests');
        } catch (Exception $e) {
          die($e->getMessage());
        }
      }
    }



  break;

  case 'requests':

    $analysisRequests = getDataFromUrl($client, 'analysisrequest');
  break;


  case 'request':
    if (isset($_GET['id']) && ($_GET['id'] != null )) {
      $id = $_GET['id'];
    } else {
      header('location: index.php?action=analysis&sact=requests');
    }


    $procedureInformation = sqlStatement("SELECT * FROM lims_analysisrequests WHERE analysisrequest_id = ?", [ $id ]);
    $procedureInformation = sqlFetchArray($procedureInformation);


    $analysisRequestInformation = getDataFromUrl($client, 'analysisrequest/'.$id.'?workflow=yes')[0];
    $sampleInformation = getDataFromUrl($client, 'sample/'.$analysisRequestInformation->SampleUID.'?workflow=yes')[0];
    $analysts = getDataFromUrl($client, 'users');

    // need to account for retracted analysis results
    $analysisInformation = getDataFromUrl($client, $analysisRequestInformation->Analyses[0]->api_url.'?workflow=yes')[0]; 
    if (isset($_POST['receiveSample'])) {

      
      try {
        $client->post('update/'.$id, [
          'json' => [
            'transition' => 'receive',
          ]
        ]);

      } catch (Exception $e) {
        die($e->getMessage());
      }
      

      $updateAnalysisRequests = sqlStatement("UPDATE lims_analysisrequests SET status = ? WHERE analysisrequest_id = ?", [ 'received', $id ]);
      if ($updateAnalysisRequests) {
        header('location: index.php?action=analysis&sact=requests');
      }
    }

    if (isset($_POST['submitResult'])) {
      try {
        $client->post('update/'.$analysisInformation->uid, [
          'form_params' => [
            'Result' => $_POST['result'],
            'transition' => 'submit',
          ]
        ]);

        // this requires UDL/LDL validation to work
        // https://github.com/senaite/senaite.jsonapi/issues/28

        $client->post('update/'.$analysisRequestInformation->uid, [
          'json' => [
            'transition' => 'submit',
            'Analyst' => $_POST['analyst'],
          ]
        ]);
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }


    // this requires UDL/LDL validation to work
    // https://github.com/senaite/senaite.jsonapi/issues/28

    if (isset($_POST['verifyResult'])) {
      try {
        $client->post('update/'.$id, [
          'json' => [
            'transition' => 'verify'
          ]
        ]);

      } catch (Exception $e) {
        die($e->getMessage());
      }
    }


    if (isset($_POST['publish'])) {
      try {
        // submit report
        // data needed - proc_order_id, date_collected, date_report, source, specimen_num, report_status, review_status, report_notes
       
       
        $procedure_id = sqlFetchArray(sqlStatement(" SELECT * FROM lims_analysisrequests WHERE analysisrequest_id = ?", [$analysisRequestInformation->uid]))['procedure_order_id'];
        $procedure_order_details = sqlFetchArray(sqlStatement("SELECT * FROM procedure_order WHERE procedure_order_id = ?", [ $procedure_id ]));
        $procedure_order_code_details = sqlFetchArray(sqlStatement("SELECT * FROM procedure_order_code WHERE procedure_order_id = ?", [ $procedure_id ]));
        $procedure_type_details = sqlFetchArray(sqlStatement("SELECT * FROM procedure_type WHERE name = ?", [ $procedure_order_code_details['procedure_name']]));

        $date_collected = $analysisRequestInformation->DateReceived;
        $date_report = date('c');
        $source = "LIMS"; // not sure what the source should be. faculty performing analysis? user that was logged in while clicking the publish button?
        $specimen_num = 1;
        $report_status = 'complete';
        $review_status = 'reviewed';
        $report_notes = $analysisRequestInformation->Remarks;
        $procedure_order_seq = sqlFetchArray(sqlStatement("SELECT procedure_order_seq FROM procedure_order_code WHERE procedure_order_id = ?", [$procedure_id]))['procedure_order_seq'];
        
        $createProcedureReport = sqlStatement("INSERT INTO procedure_report (`procedure_order_id`,`procedure_order_seq`,
                                              `date_collected`,`date_report`,`source`,`specimen_num`,`report_status`, `review_status`, `report_notes`) VALUES(?,?,?,?,?,?,?,?,?)",
                                              [$procedure_id, $procedure_order_seq, $date_collected, $date_report, $source, $specimen_num, $report_status, $review_status, $report_notes]);
        if ($createProcedureReport) {
          $client->post('update/'.$analysisRequestInformation->uid, [
            'json' => [
              'transition' => 'publish'
            ]
          ]);
          // need some extra details here
          /*
          $procedureReportID = sqlFetchArray(sqlStatement("SELECT procedure_report_id FROM procedure_report WHERE procedure_order_id = ?", [ $procedure_id ]))['procedure_report_id'];
          
          $createProcedureResult = sqlStatement("INSERT INTO procedure_result(procedure_report_id, result_data_type, result_code, result_text,date,
                                                 facility, units, result, range, abnormal, comments, result_status VALUES(?,?,?,?,?,?,?,?,?,?,?,?)",
                                                 []);
          */
        }
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }




  break;



}



require_once('./templates/pages/site/analysis/'.$sub.'.php');