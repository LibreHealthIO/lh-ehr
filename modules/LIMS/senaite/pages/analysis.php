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
    $sampleConditions = getDataFromUrl($client, 'samplecondition');
    $containers = getDataFromUrl($client, 'container');
    $analysisCategories = getDataFromUrl($client, 'analysiscategory');
    $analyses = getDataFromUrl($client, 'analysisservice');
    $errors = [];

    if (isset($_POST['submit'])) {
      $required = [ 'client' , 'contact' , 'sampleDate', 'sampleType', 'analyses' ];

      if (!(checkRequiredFields($required, $_POST))) {
        $errors[] = 'Please fill in all the required fields';
      }

      if (count($errors) === 0) { 
        try {
          $client->post('analysisrequest', [
            'json' => [
              'Client' => $_POST['client'],
              'Contact' => $_POST['contact'],
              'Profiles' => valueOrNull($_POST['analysisProfiles']),
              'DateSampled' => $_POST['sampleDate'],
              'SampleType' => $_POST['sampleType'],
              'SamplePoint' => valueOrNull($_POST['samplePoint']),
              'SampleCondition' => valueOrNull($_POST['sampleCondition']),
              'Priority' => valueOrNull($_POST['priority']),
              'EnvironmentalConditions' => valueOrNull($_POST['environmentalConditions']),
              'AdHoc' => valueOrNull($_POST['adhoc']),
              'Analyses' => $_POST['analyses']  
            ],
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
      }
    }



  break;



}



require_once('./templates/pages/site/analysis/'.$sub.'.php');