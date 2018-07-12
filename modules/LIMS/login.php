<?php

// set the username as there can be only one
// should allow to enter the username instead for validation?
$limsUserQuery = sqlStatement("SELECT * FROM globals WHERE gl_name = 'lims_username'");
$username = sqlFetchArray($limsUserQuery)['gl_value'];


// basic validations
if (isset($_POST['submit'])) {
  $errors = [];
  if (!isset($_POST['username'])) {
    $errors[] = 'Please set a username in the global configuration';
  }
  if (!isset($_POST['password']) || strlen($_POST['password']) <= 0) {
    echo $_POST['password'];
    $errors[] = 'Please enter a password';
  }

  if (count($errors) <= 0) {


    // temporarily from database, plan to remove password from db and have it natively validate against the LIMS
    $validateQuery = sqlStatement("SELECT * from globals WHERE gl_name = 'lims_password'");
    $password = sqlFetchArray($validateQuery)['gl_value'];

    if ($password === $_POST['password']) {
      session_start();
      $_SESSION['lims_login'] = 'senaite';
      $_SESSION['lims_user'] = $_POST['username'];
      header('location: index.php');
    } else {
      $errors[] = 'Please enter valid credentials';
    }
    
  }
}








require_once('./templates/pages/login.php');
