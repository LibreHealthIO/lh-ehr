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
    $instrumentTypes = getDataFromUrl($client, 'instrumenttype', true);
    $instrumentLocations = getDataFromUrl($client, 'instrumentlocation', true);
    $manufacturers = getDataFromUrl($client, 'manufacturer', true); 
    $suppliers = getDataFromUrl($client, 'supplier', true);
    $methods = getDataFromUrl($client, 'method', true);
    $errors = [];
    if (isset($_POST['submit'])) {
        
        $required = [
            'title',
            'instrumenttype',
            'manufacturer',
            'supplier',
            'location',
        ];
        if (!(isset($_POST['methods']))) {
          $_POST['methods'] = [];
        }

        if (!checkRequiredFields($required, $_POST)) {
          $errors[] = 'Please fill in the required fields';
        }

        if (!isset($_POST['location'])) {
          $_POST['location'] = null;
        }

        if (count($errors) === 0) {
          try {
            $response = $client->post('instrument', [
              'json' => [
                "title" => $_POST["title"],
                "AssetNumber" => valueOrNull($_POST["assetNum"]),
                "description" => valueOrNull($_POST["description"]),
                "InstrumentType" => $_POST["instrumenttype"],
                "Manufacturer" => $_POST["manufacturer"],
                "Supplier" => $_POST["supplier"],
                "InstrumentLocation" => valueOrNull($_POST["location"]),
                "Model" => valueOrNull($_POST["modelno"]),
                "SerialNo" => valueOrNull($_POST["serialno"]),
                "Methods" => valueOrNulL($_POST["methods"]),
                "DataInterface" => valueOrNull($_POST["exportinterface"]),
                "InlabCalibrationProcedure" => valueOrNull($_POST["calibproc"]),
                "PreventiveMaintenanceProcedure" => valueOrNull($_POST["preventproc"])
              ],
              'form_params' => [
                'title' => $_POST['title'],
              ]
            ]);
            header('location: index.php?action=instrument');
          } catch (Exception $e) {
            die($e->getMessage());
          }
      }

    }

  break;

  case 'instruments':
  default:
  $sub = 'instruments';
  $instrumentData = getDataFromUrl($client, 'instrument', true);




}

require_once('./templates/pages/site/instruments/'.$sub.'.php');