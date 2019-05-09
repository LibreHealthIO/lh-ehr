<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'analysis';
}

switch($sub) {

  case 'templates':
    $worksheetTemplates = getDataFromUrl($client, 'worksheettemplate', true);

  break;


  case 'createtemplate':
  $errors = [];
  $methods = getDataFromUrl($client, 'method', true);
  $instruments = getDataFromUrl($client, 'instrument', true);
  $analysisServices = getDataFromUrl($client, 'analysisservice', true);
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
            'form_params' => [
              'title' => $_POST['title']
            ]
          ]);
          header('location: index.php?action=worksheet&sact=templates');
        } catch (Exception $e) {
          die($e->getMessage());
        }

      }

    }
  }

  break;


  case 'worksheets':

    $worksheets = getDataFromUrl($client, 'worksheet', true);

  break;

  case 'createworksheets':
    $errors = [];
    $analysts = getDataFromUrl($client, 'users');
    $worksheetTemplates = getDataFromUrl($client, 'worksheettemplate');
    $instruments = getDataFromUrl($client, 'instrument');
    $analyses = getDataFromUrl($client, 'analysis?review_state=sample_received');
    if (isset($_POST['submit'])) {
      $required = [ 'analyst' ];
      if (!checkRequiredFields($required, $_POST)) {
        $errors[] = 'Please fill in the required fields';
      }


      /**
       * Seems to not completely update worksheets with analyses - waiting for senaite's response
       * Related to the LDL/UDL errors again: https://github.com/senaite/senaite.jsonapi/issues/28
       */

      if (count($errors) === 0) {
        $layout = [];

        $count = 0;
        foreach($_POST['analyses'] as $analysis) {
          $analysisInformation = getDataFromUrl($client, 'analysis/'.$analysis)[0];
          $layout[] = [
            'position' => ++$count,
            'type' => 'a',
            'analysis_uid' => $analysis,
            'container_uid' => $analysisInformation->parent_uid
          ];
        }

        try {
          $client->post('worksheet', [
            'json' => [
              'Analyst' => $_POST['analyst'],
              'Layout' => $layout,
              'WorksheetTemplate' => valueOrNull($_POST['template']),
              'Instrument' => valueOrNull($_POST['instrument']),
              'parent_path' => "/senaite/worksheets",
              'Analyses' => $_POST['analyses']
            ]
          ]);

          header('location: index.php?action=worksheet&sact=worksheets');
        } catch (Exception $e) {
          die($e->getMessage());
        }
      }
    }

  break;

  case 'worksheet':
    if (isset($_GET['id']) && ($_GET['id'] != null )) {
      $id = $_GET['id'];
    } else {
      header('location: index.php?action=worksheet&sact=worksheets');
    }
    $errors = [];
    $worksheetData = getDataFromUrl($client, 'worksheet/'.$id)[0];
    $analysts = getDataFromUrl($client, 'users');
    $worksheetTemplates = getDataFromUrl($client, 'worksheettemplate');
    $instruments = getDataFromUrl($client, 'instrument');
    $analyses = getDataFromUrl($client, 'analysis?review_state=sample_received');
    
  break;

}



require_once('./templates/pages/site/worksheets/'.$sub.'.php');