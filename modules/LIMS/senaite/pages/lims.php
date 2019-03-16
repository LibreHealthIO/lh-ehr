<?php

if (isset($_GET['sact']) && ($_GET['sact'] != null )) {
  $sub = $_GET['sact'];
} else {
  $sub = 'index';
}





switch($sub) {
  case 'index':
  default:
  $sub = 'index';
  $limsInformation = getDataFromUrl($client, getDataFromUrl($client, 'bikasetup')[0]->api_url)[0];
  
  if (isset($_POST['submit'])) {
    if (!isset($_POST['passLifetime']) || ($_POST['passLifetime'] === '')) {
      $errors[] = 'Please enter a password lifetime (0 if disabled)';
    }

    if (count($errors) === 0) {
      $selfVerification = ( isset($_POST['selfVerify']) && $_POST['selfVerify'] === 'on') ? true : false;
      try {
        $client->post('update/'.$limsInformation->uid, [
          'json' => [
            'PasswordLifetime' => $_POST['passLifetime'],
            'SelfVerificationEnabled' => $selfVerification
          ]
        ]);
        
        header('location: index.php?action=lims');
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
  }

}




require_once('./templates/pages/site/lims/'.$sub.'.php');