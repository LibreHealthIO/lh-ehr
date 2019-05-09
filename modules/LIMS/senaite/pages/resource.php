<?php


if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'manufacturer';
}

$errors = [];
switch($sub) {


  case 'supplier':
    $supplierDetails = $client->get('supplier')->getBody()->getContents();
    $supplierDetails = json_decode($supplierDetails)->items;

  break;

  case 'createsupplier':
    $errors = [];
    if (isset($_POST['submit'])) {
      if (!isset($_POST['name']) || strlen($_POST['name']) === 0) {
        $errors[] = 'Please enter a name';
      } 

      if ( isset($errors) && count($errors) <= 0) {
        try {
          // TO-DO: add rest of the form params
          $client->request('post', 'supplier', [
            'form_params' => [
              'title' => $_POST['name'],
            ]
          ]);
        } catch(Exception $e) {
          $errors[] = $e->getMessage();
        } finally {
          header('location: index.php?action=resource&sact=supplier');
        }
      }
    }
  break;


  case 'createmanufacturer':
    $errors = [];
    if (isset($_POST['submit'])) {
      if (!isset($_POST['title']) || strlen($_POST['title']) === 0) {
        $errors[] = 'Please enter a title';
      } 

      if ( isset($errors) && count($errors) <= 0) {
        try {
          $client->request('post', 'manufacturer', [
            'form_params' => [
              'title' => $_POST['title'],
              'description' => $_POST['description'],
            ]
          ]);
        } catch(Exception $e) {
          $errors[] = $e->getMessage();
        } finally {
          header('location: index.php?action=resource');
        }
      }
    }
  break;


  case 'manufacturer':
  default:
  $manufacturerDetails = $client->get('manufacturer')->getBody()->getContents();
  $manufacturerDetails = json_decode($manufacturerDetails)->items;

}


require_once('./templates/pages/site/resources/'.$sub.'.php');