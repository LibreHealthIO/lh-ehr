<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'laboratories';
}

switch($sub) {

  case 'contacts':
    $contacts = json_decode($client->get('labcontact')->getBody()->getContents())->items;
  break;

  case 'createcontacts':
    $errors = [];
    if (isset($_POST['submit'])) {
      $required = [ 'fname' , 'sname' ];
      foreach ($required as $requiredField) {
        if (!isset($_POST[$requiredField]) || strlen($_POST[$requiredField]) === 0) {
          $errors[] = 'Please fill in all the fields';
          break;
        }
      }

      if (count($errors) === 0) {
        try {
          $client->post('labcontact', [
            'form_params' => [
              'Salutation' => $_POST['title'],
              'Firstname' => $_POST['fname'],
              'Middleinitial' => $_POST['midinitial'],
              'Middlename' => $_POST['mname'],
              'Surname' => $_POST['sname'],
              'JobTitle' => $_POST['jtitle'],
              'EmailAddress' => $_POST['email'],
              'BusinessPhone' => $_POST['bphone'],
              'BusinessFax' => $_POST['bfax'],
              'HomePhone' => $_POST['hphone'],
              'MobilePhone' => $_POST['mphone']
            ]
          ]); 
          } catch(Exception $e) {
            die($e->getMessage());
        }
        header('location: index.php?action=laboratory&sact=contacts');
      }
    }
  break;


  case 'departments':
    $departments = json_decode($client->get('department')->getBody()->getContents())->items;
  break;

  case 'createdepartments':
    $errors = [];
    if (isset($_POST['submit'])) {
      if ( !isset($_POST['title']) || strlen($_POST['title']) === 0) {
        $errors[] = 'Please fill in all the fields';
      }
      if (count($errors) === 0) {
        try {
          $client->post('department', [
            'form_params' => [
              'title' => $_POST['title'],
              'description' => $_POST['desc']
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=laboratory&sact=departments');
      }
    }
    
  break;

  case 'products':
    $products = json_decode($client->get('labproduct')->getBody()->getContents())->items;
  break;


  case 'createproducts':
  $errors = [];
  if (isset($_POST['submit'])) {
    $required = [ 'title' , 'pwVAT' ];
    foreach ($required as $requiredField) {
      if (!isset($_POST[$requiredField]) || strlen($_POST[$requiredField]) === 0) {
        $errors[] = 'Please fill in all the fields';
        break;
      }
    }
    if (count($errors) === 0) {
      try {
        $client->post('labproduct', [
          'form_params' => [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'Volume' => $_POST['volume'],
            'Unit' => $_POST['unit'],
            'VAT' => $_POST['vat'],
            'Price' => $_POST['pwVAT']
          ]
        ]);
      } catch (Exception $e) {
        die($e->getMessage());
      }
      header('location: index.php?action=laboratory&sact=products');
    }
  }
  
  break;

  case 'information':
  default:
  $sub = 'information';

  $labInformation = json_decode($client->get('laboratory')->getBody()->getContents())->items;
  $labContacts = json_decode($client->get('labcontact')->getBody()->getContents())->items;

}



require_once('./templates/pages/site/laboratory/'.$sub.'.php');