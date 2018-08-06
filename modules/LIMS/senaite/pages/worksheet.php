<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'analysis';
}

switch($sub) {

  case 'templates':
    $worksheetTemplates = getDataFromUrl($client, 'worksheettemplate');

  break;


  case 'createtemplate':
  $errors = [];
  $methods = getDataFromUrl($client, 'method');
  $instruments = getDataFromUrl($client, 'instrument');
  $analysisServices = getDataFromUrl($client, 'analysisservice');
  if (isset($_POST['submit'])) {
    $required = [ 'title', 'analysisTypes' ];

    if (!checkRequiredFields($required, $_POST)) {
      $errors[] = 'Please fill in the required fields';
    }

    if (count($errors) === 0) {
      // duplicate and analysis validation
      
      $duplicateList = $_POST['duplicateOf'];
      $analysisList = $_POST['analysisTypes'];
      $count = 0;
      foreach ($analysisList as $key => $analyses) {
        if (($analyses) && $analyses !== '') {
          $count++;
        }
        if ($analyses === 'd' && $duplicateList[$key] === '') {
          $errors[] = ' Please select a duplicate source for '. ($key+1);
        }
      }
      if ($count === 0) {
        $errors[] = ' Please fill at least one worksheet layout field';
      }
      foreach ($duplicateList as $key => $duplicate) {
        if ($duplicate != '' && $analysisList[$key] === 'd') {
          if ( $analysisList[$duplicate-1] === 'd') {
            $errors[] = ($key + 1). " cannot be a duplicate of ". $duplicate. " because ". $duplicate. " is already a duplicate";
          }
          if ($duplicate-1 == $key) {
            $errors[] = $duplicate." cannot be a duplicate of itself ";
          }
        }
      }


      if (count($errors) === 0) {
        $layout = [];

        foreach ($analysisList as $key => $analyses) {
          if ($analyses && $analyses !== '') {
            if ($analyses === 'd') {
              $layout[] = [
                'type' => 'd',
                'pos' => ($key+1),
                'dup' => $duplicateList[$key]
              ];
            } else {
              $layout[] = [
                'type' => 'a',
                'pos' => ($key+1)
              ];
            }
          }
        }

        try {
          $client->post('worksheettemplate', [
            'json' => [
              'title' => $_POST['title'],
              'Layout' => $layout,
              'description' => valueOrNull($_POST['description']),
              'RestrictToMethod' => valueOrNull($_POST['method']),
              'Instrument' => valueOrNull($_POST['instrument']),
              'Service' => valueOrNull($_POST['analysisServices'])
            ],
            'form_data' => [
              'title' => $_POST['title']
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        



      }

    }
  }

  break;



}



require_once('./templates/pages/site/worksheets/'.$sub.'.php');