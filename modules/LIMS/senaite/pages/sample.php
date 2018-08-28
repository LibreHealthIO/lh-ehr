<?php


if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'sample';
}


switch ($sub) {

  case 'types':
    $sampleTypes = json_decode($client->get('sampletype')->getBody()->getContents())->items;
   

  break;


  case 'createtype':
    $samplePoints = json_decode($client->get('samplepoint')->getBody()->getContents())->items;
    $errors = [];
    if (isset($_POST['submit'])) {
      $required = [
        'title',
        'retentionDays',
        'retentionHours',
        'retentionMinutes',
        'typePrefix',
        'minimumVolume',
        'admittedStickerTemplates',
        'defaultSmallSticker',
        'defaultLargeSticker'
      ];
  
      foreach ($required as $requiredField) {
        if (valueOrNull($_POST[$requiredField]) === null ) {
          $errors[] = 'Please fill in all the fields';
          break;
        }
      }

      // custom validations
      if (count($errors) === 0) {
       if (!is_array($_POST['admittedStickerTemplates'])) {
        $admittedStickerTemplates = [ $_POST['admittedStickerTemplates' ] ];
       } else {
         $admittedStickerTemplates = $_POST['admittedStickerTemplates'];
       }
       
        // whitespaces not allowed in type prefix
        if (preg_match('/\s/', $_POST['typePrefix'])) {
          $errors[] = 'Whitespaces are not allowed in the type prefix';
        }

        // default small sticker should be in the admittedstickertemplate list
        $smallStickerFlag = 0;
        foreach($admittedStickerTemplates as $allowed) {
          if ($_POST['defaultSmallSticker'] === $allowed) {
            $smallStickerFlag = 1;
          }
        }

        if ($smallStickerFlag != 1) {
          $errors[] = 'The default small sticker value can only be one of the values from the picked admitted sticker template list.';
        }

        // default large sticker should be in the admittedstickertemplate list
        $largeStickerFlag = 0;
        foreach($admittedStickerTemplates as $allowed) {
          if ($_POST['defaultLargeSticker'] === $allowed) {
            $largeStickerFlag = 1;
          }
        }

        if ($largeStickerFlag != 1) {
          $errors[] = 'The default large sticker value can only be one of the values from the picked admitted sticker template list.';
        }
      }
      if (count($errors) === 0) {
        try {
          $response = $client->post('sampletype', [
            'json' => [
              'title' => $_POST['title'],
              'description' => valueOrNull($_POST['description']),
              'RetentionPeriod' => (['hours' => $_POST['retentionHours'], 'minutes' => $_POST['retentionMinutes'], 'days' => $_POST['retentionDays']]),
              'Hazardous' => (isset($_POST['hazardous']) && ($_POST['hazardous'] === 'on')) ? true : false,
              'SampleMatrix' => null,
              'Prefix' => $_POST['typePrefix'],
              'MinimumVolume' => $_POST['minimumVolume'],
              'ContainerType' => null,
              'SamplePoints' => null,
              'AdmittedStickerTemplates' => [ (['value' => '', 'small_default' => $_POST['defaultSmallSticker'], 'large_default' => $_POST['defaultLargeSticker'], 
                                             'admitted' => [ $admittedStickerTemplates ]
                                              ])],
            ]
          ]);
        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=sample&sact=types');
      }
    }
    
  break;

  
  case 'points':

    $samplePoints = getDataFromUrl($client, 'samplepoint');

    
  break;

  case 'createpoints':

    $samplePoints = json_decode($client->get('samplepoint')->getBody()->getContents())->items;
    $sampleTypes = getDataFromUrl($client, 'sampletype');
    $errors = [];

    if (isset($_POST['submit'])) {
      if (!isset($_POST['title']) || valueOrNull($_POST['title']) === null) {
        $errors[] = 'Please fill in the required fields';
      }

      // specific validations
      if (count($errors) === 0) {
        if (isset($_POST['latBear']) && ($_POST['latBear'] != 'N' && $_POST['latBear'] != 'S')) {
          $errors[] = 'Latitude bearing can only be N (north) or S (south)';
        }

        if (isset($_POST['longBear']) && ($_POST['longBear'] != 'W' && $_POST['longBear'] != 'E')) {
          $errors[] = 'Latitude bearing can only be E (east) or W (west)';
        }
      }
      var_dump($_POST['sampleTypes']);
      if (count($errors) === 0) {
        try {
          $client->post('samplepoint', [
            'json' => [
                'title' => $_POST['title'],
                'Title' => $_POST['title'],
                'description' => valueOrNull($_POST['description']),
                'SamplingFrequency' => [ 
                  'hours' => $_POST['sampleHours'],
                  'minutes' => $_POST['sampleMinutes'],
                  'days' => $_POST['sampleDays']
                ],
                'SampleTypes' => valueOrNull($_POST['sampleTypes']),
                'Composite' => (isset($_POST['composite']) ? 'true' : 'false'),
                'Latitude' => [
                  'seconds' => valueOrNull($_POST['latSec']),
                  'minutes' => valueOrNull($_POST['latMin']),
                  'degrees' => valueOrNull($_POST['latDeg']),
                  'bearing' => valueOrNull($_POST['latBear'])
                ],
                'Longitude' => [
                  'seconds' => valueOrNull($_POST['longSec']),
                  'minutes' => valueOrNull($_POST['longMin']),
                  'degrees' => valueOrNull($_POST['longDeg']),
                  'bearing' => valueOrNull($_POST['longBear'])
                ],
                'Elevation' => valueOrNull($_POST['elevation'])
      
            ]]);

        } catch (Exception $e) {
          die($e->getMessage());
        }
        header('location: index.php?action=sample&sact=points');
      }
     
      
    }
  
  break;
  
  case 'conditions':
    $sampleConditions = getDataFromUrl($client, 'samplecondition');
  break;

  case 'createconditions':
  $errors = [];
  if (isset($_POST['submit'])) {
    if (!isset($_POST['title']) || valueOrNull($_POST['title']) === null) {
      $errors[] = 'Please fill in the required fields';
    }

    if (count($errors) === 0) {
      try {
        $client->post('samplecondition', [
          'json' => [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
          ],
          'form_params' => [
            'title' => $_POST['title'],
          ]
        ]);

        header('location: index.php?action=sample&sact=conditions');
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }


  }

  break;


  case 'matrices':

    $sampleMatrices = getDataFromUrl($client, 'samplematrix');

  break;


  case 'creatematrices':
    $errors = [];
    if (isset($_POST['submit'])) {
      if (!isset($_POST['title']) || valueOrNull($_POST['title']) === null) {
        $errors[] = 'Please fill in the required fields';
      }

      if (count($errors) === 0) {
        try {
          $client->post('samplematrix', [
            'json' => [
              'title' => $_POST['title'],
              'description' => $_POST['description'],
            ],
            'form_params' => [
              'title' => $_POST['title'],
            ]
          ]);

          header('location: index.php?action=sample&sact=matrices');
        } catch (Exception $e) {
          die($e->getMessage());
        }
      }


    }

  break;



  case 'types':
  default:
    $sampleTypes = json_decode($client->get('sampletype')->getBody()->getContents())->items;
    $sub = 'types';
   
}

require_once('./templates/pages/site/samples/'.$sub.'.php');