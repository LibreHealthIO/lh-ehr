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





}



require_once('./templates/pages/site/analysis/'.$sub.'.php');