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
    $errors = [];
    if (isset($_POST['submit'])) {
        
        $required = [
            'title',
            'instrumenttype',
            'manufacturer',
            'supplier',
        ];

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
        if (count($errors) === 0) {
          $response = $client->request('POST', 'instrument', [
            'multipart' => [
            [
              'name' => 'title',
              'contents' => $_POST['title']
            ],
            [
              'name' => 'InstrumentTypeName',
              'contents' => 'Sample Instrument Type',
            ],
            ]
          ]);
          //var_dump($response->getBody()->getContents());
        }





    }

  break;

  case 'instruments':
  default:

  $instrumentData = json_decode($client->get('instrument')->getBody()->getContents());
  $instrumentData = $instrumentData->items;




}

require_once('./templates/pages/site/instruments/'.$sub.'.php');