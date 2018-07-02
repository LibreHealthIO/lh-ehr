<?php


// basic validations
if (isset($_POST['submit'])) {
  $errors = [];
  if (!isset($_POST['username'])) {
    $errors[] = 'Please enter a username';
  }
  if (!isset($_POST['password']) || strlen($_POST['password']) <= 0) {
    echo $_POST['password'];
    $errors[] = 'Please enter a password';
  }

  if (count($errors) <= 0) {
    $authenticated = true;
    try {
      $authRequest = $client->request('POST','login', [
        'form_params' => [ 
                            '__ac_name' => $_POST['username'],
                            '__ac_password' => $_POST['password'], 
                        ],      
      ]);
    } catch(\GuzzleHttp\Exception\ClientException $e) {
      $authenticated = false;
    }
    if ($authenticated) {
      session_start();
      $_SESSION['lims_login'] = 'senaite';
      $_SESSION['lims_user'] = $_POST['username'];
      $_SESSION['login_cookie'] = $authRequest->getHeader('set-cookie')[0];
      header('location: index.php');
    } else {
      $errors[] = 'Please enter valid credentials';
    }
    
  }
}








require_once('./templates/pages/login.php');
