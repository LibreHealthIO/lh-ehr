<?php


// get sub-action
if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'method';
}


switch($sub) {


  case 'container':

    $containers = json_decode($client->get('container')->getBody()->getContents())->items;


  break;


  case 'createcontainer':

    $containerTypes = json_decode($client->get('containertype')->getBody()->getContents())->items;
    $errors = [];
    
    if (isset($_POST['submit'])) {
      $required = ['title', 'capacity'];
      foreach ($required as $requiredField) {
        if (valueOrNull($_POST[$requiredField]) === null ) {
          $errors[] = 'Please fill in all the fields';
          break;
        }
      }

      /*
       TO-DO:
       - Pre-preservation
       */
      
      if (count($errors) === 0) {
        try {
          $client->request('post', 'container', [
            'json' => [
              'title' => $_POST['title'],
              'description' => $_POST['description'],
              'ContainerType' => valueOrNull($_POST['containertype']),
              'Capacity' => $_POST['capacity'],
              'SecuritySealIntact' => valueOrNull($_POST['seal'])
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=method&sact=container');
        
      }
    }
  break;


  case 'containertype':

    $containerTypes = json_decode($client->get('containertype')->getBody()->getContents())->items;

  break;


  case 'createcontainertype':
    $containerTypes = json_decode($client->get('containertype')->getBody()->getContents())->items;
    $errors = [];
    
    if (isset($_POST['submit'])) {
      if (!isset($_POST['title']) || (strlen($_POST['title']) === 0)) {
        $errors[] = ' Please fill in all the required fields ';
      }

      if (count($errors) === 0) {
        try {
          $client->request('post', 'containertype', [
            'json' => [
              'title' => $_POST['title'],
              'description' => $_POST['description'],
              'parent_path' => "/senaite/bika_setup/bika_containertypes" // Parent path required temporarily, Senaite side issue
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=method&sact=containertype');
        
      }
    }

  break;

  case 'createmethod':
     // get method details
    $methods = json_decode($client->get('method')->getBody()->getContents())->items;
    $errors = [];
    
    if (isset($_POST['submit'])) {
      if (!isset($_POST['title']) || (strlen($_POST['title']) === 0)) {
        $errors[] = ' Please fill in all the required fields ';
      }

      if (count($errors) === 0) {
        try {
          $client->request('post', 'method', [
            'form_params' => [
              'title' => $_POST['title'],
              'description' => $_POST['description'],
              'MethodID' => $_POST['methodid'],
              'Instructions' => $_POST['instructions'],
              'ManualEntryOfResultsViewField' => (isset($_POST['manualentry']) && ($_POST['manualentry'])) ? true : false,
              'Accredited' => (isset($_POST['accredited']) && ($_POST['accredited'])) ? true : false,
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=method');
        
      }
    }

  break;

  case 'identifiertype':
    $identifierTypes = getDataFromUrl($client, 'identifiertype');
  break;

  case 'createidentifiertype':
  $errors = [];
  if (isset($_POST['submit'])) {
    if (!isset($_POST['title']) || strlen($_POST['title']) === 0) {
      $errors[] = 'Please fill in all the required fields';
    }
    if (count($errors) === 0) {
      try {
        $client->post('identifiertype', [
          'form_params' => [
            'title' => $_POST['title'],
            'description' => valueOrNull($_POST['description'])
          ]
        ]);
      } catch (Exception $e) {
        die($e->getMessage());
      }
      header('location: index.php?action=method&sact=identifiertype');
      
    }
  }

  

  break;

  case 'method':
  default:
    $methods = json_decode($client->get('method')->getBody()->getContents())->items;

}




require_once('./templates/pages/site/methods/'.$sub.'.php');