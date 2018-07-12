<?php


// get sub-action
if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'method';
}


switch($sub) {



  case 'method':
  default:

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



}




require_once('./templates/pages/site/methods/'.$sub.'.php');