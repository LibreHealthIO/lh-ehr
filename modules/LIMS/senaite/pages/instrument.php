<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'instruments';
}

switch($sub) {
  case 'locations':

    $instrumentLocationData = $client->get('instrumentlocation')->getBody()->getContents();
    $instrumentLocationData = json_decode($instrumentLocationData)->items;

  break;

  case 'createlocation':
    $errors = [];
    if (isset($_POST['submit'])) {
      if (!isset($_POST['title']) || strlen($_POST['title']) === 0) {
        $errors[] = 'Please enter a title';
      } 

      if ( isset($errors) && count($errors) <= 0) {
        try {
          $client->request('post', 'instrumentlocation', [
            'form_params' => [
              'title' => $_POST['title'],
              'description' => $_POST['description'],
            ]
          ]);
        } catch(Exception $e) {
          $errors[] = $e->getMessage();
        } finally {
          header('location: index.php?action=instrument&sact=locations');
        }
      }
    }

  break;

  case 'types':

    $instrumentTypeData = $client->get('instrumenttype')->getBody()->getContents();
    $instrumentTypeData = json_decode($instrumentTypeData)->items;

  break;

  case 'createtype':
  $errors = [];
  if (isset($_POST['submit'])) {
    if (!isset($_POST['title']) || strlen($_POST['title']) === 0) {
      $errors[] = 'Please enter a title';
    } 

    if ( isset($errors) && count($errors) <= 0) {
      try {
        $client->request('post', 'instrumenttype', [
          'form_params' => [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
          ]
        ]);
      } catch(Exception $e) {
        $errors[] = $e->getMessage();
      } finally {
        header('location: index.php?action=instrument&sact=types');
      }
    }
  }
  
  break;


  case 'createinstrument':
    $instrumentTypes = json_decode($client->get('instrumenttype')->getBody()->getContents())->items;
    $instrumentLocations = json_decode($client->get('instrumentlocation')->getBody()->getContents())->items;
    $manufacturers = json_decode($client->get('manufacturer')->getBody()->getContents())->items;
    $suppliers = json_decode($client->get('supplier')->getBody()->getContents())->items;
    $methods = json_decode($client->get('method')->getBody()->getContents())->items;
    $errors = [];
    if (isset($_POST['submit'])) {
        
        $required = [
            'title',
            'instrumenttype',
            'manufacturer',
            'supplier',
        ];
        if (!(isset($_POST['methods']))) {
          $_POST['methods'] = [];
        }

        foreach($required as $requiredField) {
          if ($_POST[$requiredField] === null || $_POST[$requiredField] === '') {
            $errors[] = 'Please fill in all the required fields';
            break;
          }
        }
        /*
          To-do:
         * Creating schema-specific data to send relational data (instrument types, locations) along with the instrument data
         */

        
        var_dump($_POST);
        if (count($errors) === 0) {
          $response = $client->POST('instrument', [
            'json' => [
              'title' => $_POST['title'],
              'InstrumentType' => $_POST['instrumenttype'],
              'Manufacturer' => $_POST['manufacturer'],
              'Supplier' => $_POST['supplier'],
              'InstrumentLocation' => valueOrNull($_POST['location']),
              'Model' => valueOrNull($_POST['modelno']),
              'SerialNo' => valueOrNull($_POST['serialno']),
              'Methods' => valueOrNulL($_POST['methods']),
              'DataInterface' => valueOrNull($_POST['exportinterface']),
              'InlabCalibrationProcedure' => valueOrNull($_POST['calibproc']),
              'PreventiveMaintenanceProcedure' => valueOrNull($_POST['preventproc'])
            ]
          ]);
        }





    }

  break;

  case 'instruments':
  default:
  $sub = 'instruments';
  $instrumentData = json_decode($client->get('instrument')->getBody()->getContents());
  $instrumentData = $instrumentData->items;




}

require_once('./templates/pages/site/instruments/'.$sub.'.php');