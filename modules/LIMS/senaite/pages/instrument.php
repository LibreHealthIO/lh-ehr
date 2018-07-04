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


}

require_once('./templates/pages/site/instruments/'.$sub.'.php');